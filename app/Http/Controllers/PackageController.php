<?php

namespace App\Http\Controllers;

use App\Country;

use App\Mail\PackageStatusUpdated;
use App\Models\Branches;
use App\Models\CarrierDetails;
use App\Models\ClientsModel;
use App\Models\ConsigneeDetails;
use App\Models\OriginModel;
use App\Models\DocumentTypes;
use App\Models\PackageModel;
use App\Models\ShipperDetails;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Mail;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\Types\TypeCode128;
use Picqer\Barcode\Renderers\HtmlRenderer;


class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status = null)
    {
        $previousUrl = url()->previous();
        $origins = OriginModel::select('origins.id', 'origins.origin_name', DB::raw('MAX(freight_rates.rate) as rate'), DB::raw('MAX(freight_rates.additional) as additional'))
                                ->join('freight_rates', 'origins.id', '=', 'freight_rates.origin_id')
                                ->groupBy('origins.id', 'origins.origin_name')
                                ->get();
        $previousRoute = app('router')->getRoutes()->match(app('request')->create($previousUrl));
        if ($previousRoute->uri != 'admin/home' || $status == null) {
            $status = null;
        }
        return view('packages.index', compact('status','origins' ));
    }
    public function waybill($waybill)
    {
        $waybillWithoutHyphens = str_replace('-', ' ', $waybill);
        $details = PackageModel::where('waybill', $waybillWithoutHyphens)
            ->join('clients', 'packages.client_id', '=', 'clients.id')
            ->select('packages.*', 'clients.first_name', 'clients.last_name')
            ->first();
        return view('packages.waybill', compact('details'));

    }
    public function packageTracking()
    {

        return view('packages.tracking');
    }
    public function printLabelPage($id = null)
    {

        if ($id) {
            $package = PackageModel::where('waybill', $id)->first();
            if (!$package) {
                return response()->json([
                    'status' => 'not_found',
                    'msg' => 'Package with this waybill does not exist!',
                ]);
            } else {
                $package_id = $package->id;
                return redirect()->route('package-print-label', ['id' => $package_id]);
            }
        } else {
            return view('packages.print_label');
        }
    }

    public function getPackages(Request $request)
    {
// dd($request->all()); 
        // $query = DB::table('packages')
        //     ->select('packages.*', 'origins.origin_name', 'clients.first_name', 'clients.last_name')
        //     ->leftJoin('origins', 'packages.origin_id', '=', 'origins.id')
        //     ->leftJoin('clients', 'packages.client_suite', '=', 'clients.suite');
        $query = DB::table('packages')
                ->select(
                    'packages.id','packages.waybill',
                    'packages.original_tracking',
                    'packages.client_suite',
                    'packages.client_name', 
                     DB::raw('(SELECT origin_name FROM origins WHERE origins.id = packages.origin_id) as origin_name'),
                    'packages.created_at', // or use `packages.date` if separate field exists
                    'packages.kg',
                    'packages.grand_total',
                    'packages.status'
                );

        if ($request->package_status) {
            $query->where('packages.status', $request->package_status);
        }
        if ($request->homeStatus) {
            if ($request->homeStatus == 'package_today' && $request->date) {
                $query->whereDate('packages.created_at', '=', $request->date);
            } else if ($request->homeStatus == 'retired' && $request->date) {
                $query->whereDate('packages.status_change_date', '=', $request->date);
            } else if ($request->homeStatus == 'in-progress' && $request->date) {
                $query->whereDate('packages.status_change_date', '=', $request->date);
            }

        } 
        if ($request->week_flag == 'true') {
            $query->whereDate('packages.created_at', '>=', now()->subDays(7))
                  ->whereDate('packages.created_at', '<=', now());
        }

        if (Auth::guard('clients')->check()) {
            $query->where('packages.client_id', GetActiveGuardDetail()->id);
        }
        $packages = $query->get(); 
        return response()->json([
            'status' => 'success',
            'msg' => 'Packages Fetched Successfully',
            'packages' => $packages
        ]);
    }
  public function changeStatus(Request $request)
{
    $record = PackageModel::find($request->id);
    $client = '';
    if (!$record) {
        return response()->json([
            'status' => 'not_found',
            'msg' => 'Invalid Record!',
        ]);
    }
    if ($record) {
        $client = ClientsModel::find($record->client_id);
    }
    $record->status = $request->status;
    $record->status_change_date = Carbon::now();
    $record->save();

    if ($client) {
        $htmlContent = $this->updateStatusEmail($client, $record);
        $subject = emailContentSettings('status-change')->subject ?? 'Package Status Update';
        
        try {
          
            SendInBlue($client->email, $client->first_name, $subject, $htmlContent);
           
        } catch (\Exception $e) {
            
           
        }
    }

    return response()->json([
        'status' => 'success',
        'msg' => 'Packages status updated Successfully',
    ]);
}

    public function changeTrackingStatus(Request $request)
    {
        $record = PackageModel::where('waybill', $request->id)->first();
        $client = '';
        if (!$record) {
            return response()->json([
                'status' => 'not_found',
                'msg' => 'Package not found agaisnt this waybill',
            ]);
        }
        if ($record) {
            $client = ClientsModel::find($record->client_id);
        }
        $record->status = $request->status;
        $record->save();
        if ($client) {
            $htmlContent = $this->updateStatusEmail($client, $record);
            $subject = emailContentSettings('status-change')->subject ?? 'Package Status Update';
            SendInBlue($client->email, $client->first_name, $subject, $htmlContent);
        }

        return response()->json([
            'status' => 'success',
            'msg' => 'Packages status updated Successfully',
        ]);
    }
    function updateStatusEmail($client, $record)
    {
        $formattedStatus = ucwords(str_replace('-', ' ', $record->status));
        $headerContent = emailContentSettings('status-change')->header_text;
        $bodyContent = emailContentSettings('status-change')->body_text;
        $footerContent = emailContentSettings('status-change')->footer_text;
        $placeholders = [
            '{{ waybill }}' => $record->waybill,
            '{{ status }}' => $formattedStatus,
            '{{ client_name }}' => $client->first_name,
            '{{ kg }}' => $record->kg,
            '{{ grand_total }}' => $record->grand_total,
        ];
        $headerContent = str_replace(array_keys($placeholders), array_values($placeholders), $headerContent);
        $bodyContent = str_replace(array_keys($placeholders), array_values($placeholders), $bodyContent);
        $footerContent = str_replace(array_keys($placeholders), array_values($placeholders), $footerContent);
       
        return view('client_auth.package-update', [
            'headerContent' => $headerContent,
            'bodyContent' => $bodyContent,
            'footerContent' => $footerContent,
        ])->render();
    }
    function updatePackageEmail($client, $record)
    {    
        $formattedStatus = ucwords(str_replace('-', ' ', $record->status));
        $headerContent = emailContentSettings('create-package')->header_text;
        $bodyContent = emailContentSettings('create-package')->body_text;
        $footerContent = emailContentSettings('create-package')->footer_text;
        $placeholders = [
            '{{ waybill }}' => $record->waybill,
            '{{ status }}' => $formattedStatus,
            '{{ client_name }}' => $client->first_name,
            '{{ kg }}' => $record->kg,
            '{{ grand_total }}' => $record->grand_total,
            '{{ original_tracking }}' => $record->original_tracking,
            '{{ description }}' => $record->description,
            '{{ comments }}' => $record->comments,
        ];
        $headerContent = str_replace(array_keys($placeholders), array_values($placeholders), $headerContent);
        $bodyContent = str_replace(array_keys($placeholders), array_values($placeholders), $bodyContent);
        $footerContent = str_replace(array_keys($placeholders), array_values($placeholders), $footerContent);
    
        return view('client_auth.package-update', [
            'headerContent' => $headerContent,
            'bodyContent' => $bodyContent,
            'footerContent' => $footerContent,
        ])->render();
    }
     
    
    public function printPackageLabel($waybill = null)
    {
        error_reporting(0);

        if ($waybill) {
            $waybill = trim($waybill);
            $waybill = str_replace('-', ' ', $waybill);
            $package = PackageModel::where('waybill', $waybill)->first();
            if (!$package) {
                return redirect()->back()->withErrors(['waybill' => 'Package not found for the given waybill.']);
            } else {
                $id = $package->id;
            }
        } else {
            return view('packages.print_label');
        }

        \Log::info("Fetching package details for ID: $id");
        $package = DB::select("SELECT packages.*, origins.origin_name, clients.first_name, clients.last_name
            FROM packages
            LEFT JOIN origins ON packages.origin_id = origins.id
            LEFT JOIN clients ON packages.client_suite = clients.suite
            WHERE packages.id = ? LIMIT 1", [$id]);
        if (empty($package)) {
            \Log::error("Package not found for ID: $id");
            return redirect()->back()->with('error', 'Package not found.');
        }
        \Log::info("Query package result:", (array) $package);
        $package = $package[0];
        \Log::info("Package ID: " . $package->id);
        \Log::info("Client Name: " . $package->first_name . " " . $package->last_name);
        \Log::info("barcode: " . $package->barcodeImageBase64 . " " . $package->barcodeImageBase64);
        try {
            $package->barcodeImageBase64 = $this->imageToBase64($package->barcodeImage);
            $pdf = Pdf::loadView('packages.print', compact('package'))->setPaper('a4');
            \Log::info("load view called: ");
            return response($pdf->stream('package_invoice.pdf'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="package_invoice.pdf"');
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Could not generate PDF: ' . $e->getMessage()
            ], 500);
        }

    }
    public function printLabel($id)
    {
        error_reporting(0);


        \Log::info("Fetching package details for ID: $id");
        $package = DB::select("SELECT packages.*, origins.origin_name, clients.first_name, clients.last_name
            FROM packages
            LEFT JOIN origins ON packages.origin_id = origins.id
            LEFT JOIN clients ON packages.client_suite = clients.suite
            WHERE packages.id = ? LIMIT 1", [$id]);
        if (empty($package)) {
            \Log::error("Package not found for ID: $id");
            return redirect()->back()->with('error', 'Package not found.');
        }
        \Log::info("Query package result:", (array) $package);
        $package = $package[0];
        \Log::info("Package ID: " . $package->id);
        \Log::info("Client Name: " . $package->first_name . " " . $package->last_name);
        \Log::info("barcode: " . $package->barcodeImageBase64 . " " . $package->barcodeImageBase64);
        try {
            $package->barcodeImageBase64 = $this->imageToBase64($package->barcodeImage);
            $pdf = Pdf::loadView('packages.print', compact('package'))->setPaper('a4');
            \Log::info("load view called: ");
            return response($pdf->stream('package_invoice.pdf'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="package_invoice.pdf"');
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Could not generate PDF: ' . $e->getMessage()
            ], 500);
        }

    }
    public function changePaymentStatus(Request $request)
    {
        if ($request->id) {
            $package = PackageModel::find($request->id);
            if ($package) {
                $package->payment_status = $package->payment_status == 1 ? 0 : 1;
                $package->save();
                return response()->json(['status' => 'success', 'msg' => 'Payment status updated successfully!']);
            } else {
                return response()->json(['status' => 'error', 'msg' => 'Package not found!']);
            }
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Package not found!']);
        }
    }
    public function printCustomerPackage($id)
    {
        error_reporting(0);
        \Log::info("Fetching package details for ID: $id");
        $package = DB::select("SELECT packages.*, origins.origin_name, clients.first_name, clients.last_name
            FROM packages
            LEFT JOIN origins ON packages.origin_id = origins.id
            LEFT JOIN clients ON packages.client_suite = clients.suite
            WHERE packages.id = ? LIMIT 1", [$id]);
        if (empty($package)) {
            \Log::error("Package not found for ID: $id");
            return redirect()->back()->with('error', 'Package not found.');
        }
        $package = $package[0];
        try {
            $package->barcodeImageBase64 = $this->imageToBase64($package->barcodeImage);
            $pdf = Pdf::loadView('packages.print', compact('package'))->setPaper('a4');
            return response($pdf->stream('package_invoice.pdf'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="package_invoice.pdf"');
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Could not generate PDF: ' . $e->getMessage()
            ], 500);
        }

    }

    public function imageToBase64($imagePath)
    {
        $imagePath = public_path(str_replace(url('/'), '', $imagePath));

        if (file_exists($imagePath)) {
            $imageData = file_get_contents($imagePath);
            $mimeType = mime_content_type($imagePath);
            \Log::error("imageToBase64: $mimeType");
            return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        \Log::error("imageToBase64: $imagePath");

        return null;
    }




    public function create($package_id = null)
    {
        $package = null;
       
        $origins = OriginModel::select('origins.id', 'origins.origin_name', DB::raw('MAX(freight_rates.rate) as rate'), DB::raw('MAX(freight_rates.additional) as additional'))
            ->join('freight_rates', 'origins.id', '=', 'freight_rates.origin_id')
            ->groupBy('origins.id', 'origins.origin_name')
            ->get();
      
        $customers = ClientsModel::all();
        if ($package_id) {
            $package  = PackageModel::find($package_id);
            if (GetActiveGuardDetail()->is_web != 1) { 
                    return redirect()->route('create-customer-package'); 
            }
        } 
        return view('packages.create', compact('package', 'origins','customers'));
    }


    public function store(Request $request)
    {
        $waybill = null;
        $package = '';
        $isPackageTrackingExist = PackageModel::where('original_tracking',$request->original_tracking)->where('id','!=',$request->package_id)->exists();
        if($isPackageTrackingExist){
            return response()->json([
                'status' => 'error',
                'msg'    => 'This Original Tracking is assosiated with an other package',
             ]);
        }
        $barcodeUrl = '';
        if ($request->package_id) {
            $package = PackageModel::find($request->package_id);
            if ($package) {
                $package->updated_at = Carbon::now();
                $package->updated_by = Auth::user()->id;
            }
        }
        if (!$package) {
            $package = new PackageModel();
            $package->created_at = Carbon::now();
            $package->created_by = Auth::user()->id;
        }

        $package->original_tracking = $request->original_tracking;
        $package->type              = $request->type;
        $package->origin_id         = $request->origin;
        
        if (!$request->kg) {
            $package->status = 'on-the-way';
        } elseif ($request->kg && !$request->package_id) {
            $package->status = 'received';
        } elseif ($request->kg && $request->package_id && $package->status == 'on-the-way') {
            $package->status = 'received';
        } 
        if (!GetActiveGuardDetail()->is_web) {
            $client = ClientsModel::find(GetActiveGuardDetail()->id);
            if ($client) { 
                $package->client_suite  = $client->suite;
                $package->client_id     = $client->id; 

            } else {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Unable to ' . ($request->package_id ? 'Updated ' : 'Added') . 'Package  at this moment',
                ]);
            }
        } else{
            if(!$request->client_id){
                return response()->json([
                   'status' => 'error',
                   'msg'    => 'Please select client',
                ]);
            }else{
                $client = ClientsModel::find(GetActiveGuardDetail()->id);
                $package->client_suite = $request->suite;
                $package->client_id    = $request->client_id;
              
            } 
        }
        if(!$request->origin){
            return response()->json([
               'status' => 'error',
               'msg'    => 'Please select origin',
            ]);
        }

        $package->description = $request->description;
        if ($request->hasFile('invoice_image')) { 
            $completeFileName = $request->file('invoice_image')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('invoice_image')->getClientOriginalExtension(); 
            $invoice_image = str_replace(' ', '_', $fileNameOnly) . '.' . $extension; 
            $request->file('invoice_image')->storeAs('public/packages', $invoice_image); 
            if (Storage::exists('public/packages/' . str_replace('./storage/packages/', '', $package->invoice_image))) {
                Storage::delete('public/packages/' . str_replace('./storage/packages/', '', $package->invoice_image));
            } 
            $package->invoice_image = 'storage/packages/' . $invoice_image;
        } else { 
            $package->invoice_image = $request->hidden_invoice_image;
        }
        if ($request->hasFile('other_document')) { 
            $completeFileName = $request->file('other_document')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('other_document')->getClientOriginalExtension(); 
            $other_document = str_replace(' ', '_', $fileNameOnly) . '.' . $extension; 
            $request->file('other_document')->storeAs('public/packages', $other_document); 
            if (Storage::exists('public/packages/' . str_replace('./storage/packages/', '', $package->other_document))) {
                Storage::delete('public/packages/' . str_replace('./storage/packages/', '', $package->other_document));
            } 
            $package->other_document = 'storage/packages/' . $other_document;
        } else { 
            $package->other_document = $request->hidden_other_document;
        }

        $package->is_insured = $request->is_insured;
        $package->kg = doubleval($request->kg);
        $package->cbm = doubleval($request->cbm ?? 0.0);
        $package->grand_total = doubleval($request->grand_total);
        $package->comments = $request->comments; 
        if ($package->save()) { 
             $client = ClientsModel::find($package->client_id);
             $package->client_name   =  $client->first_name . ' '.$client->last_name;;
             
            if (!$package->waybill ) { //&& !$request->package_id
                $waybill        = $this->makeWayBill($request); 
                $package->waybill = $waybill;
               
                $package->save();
            }
            if(!$package->barcodeImage){
                $barcodeRecords =   $this->generateBarcode($package->waybill);
                $barcodeUrl     =   $barcodeRecords['barcodeImage'];   
                $barcodeHTML    =   $barcodeRecords['barcodeHtml'];   
                $package->barcodeImage  = $barcodeUrl;
                $package->barcodeHTML   = $barcodeHTML;
                $package->save();
            }
            if(!$request->package_id){ 
                    $client = ClientsModel::find($package->client_id);
                    if ($client) {
                        $htmlContent = $this->updatePackageEmail($client, $package);
                        $subject = emailContentSettings('create-package')->subject ?? 'Package Created'; 
                        try { 
                           
                            SendInBlue($client->email, $client->first_name, $subject, $htmlContent); 
                        } catch (\Exception $e) {
                           
                           
                        }
                    }
                
            }
            
            return response()->json([
                'status' => 'success',
                'barcodeUrl' => $barcodeUrl,
                'package' => $package,
                'msg' => 'Package ' . ($request->package_id ? 'Updated ' : 'Added') . ' successfully!',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'msg' => 'Unable to ' . ($request->package_id ? 'Update ' : 'Create') . 'Package  at this moment',
            ]);
        }
    }
    public function updateAllPackagesBarcode()
{ 
    $packages = PackageModel::all();  
    foreach ($packages as $package) { 
        if($package->waybill){
            $barcodeData = $this->generateBarcode($package->waybill); 
            $package->barcodeImage = $barcodeData['barcodeImage'];
            $package->save();
        }
        
    } 
    echo 'Barcode images updated successfully'; 
}
    private function generateBarcode($waybill)
    {
        $appUrl = config('app.url');
        $orignalWaybill = $waybill;
        $waybill = str_replace(' ', '-', $waybill);
        $url = $appUrl . '/waybill/' . urlencode($waybill);  
        $generator = new BarcodeGeneratorPNG();  
        $barcode = $generator->getBarcode($orignalWaybill, $generator::TYPE_CODE_128, 2, 40);  
        $rendererhtml = new BarcodeGeneratorHTML(); 
        $barcodeHtml = $rendererhtml->getBarcode($url, $generator::TYPE_CODE_128, 1, 40);   
        $base64Barcode = base64_encode($barcode);
        $barcodeImage = 'data:image/png;base64,' . $base64Barcode;  
        $directoryPath = public_path('barcodes');
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        } 
        $sanitizedWaybill = preg_replace('/[^A-Za-z0-9_\-]/', '', $waybill);
        $filePath = $directoryPath . '/' . $sanitizedWaybill . '.png';  
        try {
            file_put_contents($filePath, $barcode);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save barcode: ' . $e->getMessage()], 500);
        }  
        return [
            'barcodeImage' => asset('barcodes/' . $sanitizedWaybill . '.png'),  // Base64 barcode image
            'barcodeHtml'  => $barcodeHtml,  // HTML layout for the barcode
        ];
    }
    
    private function makeWayBill($request)
    {
        $originNamePart = strtoupper(substr($request->origin_name, 0, 2));
        $typePart = strtoupper(substr($request->type, 0, 1));
        $todayDate = Carbon::now()->format('dmy');
        $clientSuit = $request->suite;
        $today = Carbon::now()->startOfDay();
        $existingRecordsCount = PackageModel::whereDate('created_at', $today)
            ->count();
        $recordNumber = str_pad($existingRecordsCount, 4, '0', STR_PAD_LEFT);
        $waybill = $originNamePart . $typePart . $todayDate . ' ' . $recordNumber;
        return $waybill;
    }
    public function getPackageClient($suit)
    {
        $client = ClientsModel::select('id', 'first_name', 'last_name')->where('suite', $suit)->first();
        return response()->json(['status' => 'success', 'msg' => 'Client fetched successfully!', 'client' => $client]);
    }
    public function destroy(Request $request)
    {
        PackageModel::find($request->id)->delete();
        return response()->json(['status' => 'success', 'msg' => 'Package deleted successfully!']);
    }
    public function saveSidebarPackage(Request $request){
         
        $package                    =   PackageModel::find($request->package_id);
        if($package){
            if (!$request->kg) {
                $package->status = 'on-the-way';
            } elseif ($request->kg && !$request->package_id) {
                $package->status = 'received';
            } elseif ($request->kg && $request->package_id && $package->status == 'on-the-way') {
                $package->status = 'received';
            }
            
            $package->origin_id     =   $request->origin;
             
            $package->kg            =   $request->kg;
            $package->cbm           =   $request->cbm;
            $package->grand_total   =   $request->grand_total;
            $package->save();
            return response()->json(['status' =>'success','msg' => 'Payment  updated successfully!']);
        }else{
            return response()->json(['status' => 'error','msg' => 'Package not found!']); 
        }
    }




}