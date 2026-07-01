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
use Picqer\Barcode\BarcodeGeneratorPNG; 
 

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
       
        return view('packages.index');
    }
    public function waybill($waybill)
    {  $waybillWithoutHyphens = str_replace('-', ' ', $waybill);
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
    public function printLabelPage($id = null){
     
        if($id){
            $package = PackageModel::where('waybill',$id)->first();
            if(!$package){
                return response()->json([
                    'status' => 'not_found',
                    'msg' => 'Package with this waybill does not exist!', 
                ]);
            }else{
                $package_id = $package->id;
                return redirect()->route('package-print-label', ['id' => $package_id]);
            }
        }else{
            return view('packages.print_label');
        }
    }

    public function getPackages(Request $request)
    { $query = DB::table('packages')
        ->select('packages.*', 'origins.origin_name', 'clients.first_name', 'clients.last_name')
        ->leftJoin('origins', 'packages.origin_id', '=', 'origins.id')
        ->leftJoin('clients', 'packages.client_suite', '=', 'clients.suite');
     
    if ($request->package_status) {
        $query->where('packages.status', $request->package_status);
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
       if(!$record){
        return response()->json([
            'status' => 'not_found',
            'msg' => 'Invalid Record!', 
        ]);
       }
       if($record){
        $client = ClientsModel::find($record->client_id);
       }
       $record->status = $request->status;
       $record->save();
       if($client){
           Mail::to($client->email)->send(new PackageStatusUpdated($client, $record));
       }
        return response()->json([
            'status' => 'success',
            'msg' => 'Packages status updated Successfully', 
        ]);
    }
    public function changeTrackingStatus(Request $request)
    {
       $record = PackageModel::where('waybill',$request->id)->first(); 
       $client = '';
       if(!$record){
        return response()->json([
            'status' => 'not_found',
            'msg' => 'Package not found agaisnt this waybill', 
        ]);
       }
       if($record){
        $client = ClientsModel::find($record->client_id);
       }
       $record->status = $request->status;
       $record->save();
       if($client){
           Mail::to($client->email)->send(new PackageStatusUpdated($client, $record));
       }
      
        return response()->json([
            'status' => 'success',
            'msg' => 'Packages status updated Successfully', 
        ]);
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
        try{ 
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
        try{ 
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
    
    public function imageToBase64($imagePath) { 
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


 

    public function create($package_id = null){
        $package = null;
        $origins = OriginModel::select('origins.id', 'origins.origin_name', DB::raw('MAX(freight_rates.rate) as rate'), DB::raw('MAX(freight_rates.additional) as additional'))
        ->join('freight_rates', 'origins.id', '=', 'freight_rates.origin_id')
        ->groupBy('origins.id', 'origins.origin_name')
        ->get();
 
        if($package_id){
            $package = PackageModel::find($package_id);
            if(GetActiveGuardDetail()->is_web != 1){
                if (!$package || $package->client_id != GetActiveGuardDetail()->id) {
                    return redirect()->route('create-customer-package');
                }
            } 
        } 
        return view('packages.create',compact('package','origins')); 
     }


    public function store(Request $request)
    {   $waybill = null;
        $package = '';
        $barcodeUrl = '';
        if($request->package_id){
            $package = PackageModel::find($request->package_id);
            if($package){
                $package->updated_at = Carbon::now();
                $package->updated_by = Auth::user()->id;
            }
        }
        if(!$package){
            $package = new PackageModel();
            $package->created_at = Carbon::now();
            $package->created_by = Auth::user()->id;
        }

        $package->original_tracking = $request->original_tracking;
        $package->type              = $request->type;
        $package->origin_id         = $request->origin; 
        if(!GetActiveGuardDetail()->is_web){
            $client     = ClientsModel::find(GetActiveGuardDetail()->id);
            if($client){
                $package->client_suite      = $client->suite;
                $package->client_id         = $client->id;
                
            }else{
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Unable to '. ($request->package_id ? 'Updated ' : 'Added')  . 'Package  at this moment', 
                ]);
            }
        }else{
            $package->client_suite      = $request->suite;
            $package->client_id         = $request->client_id;
        } 
        $package->description       = $request->description;
        $package->kg = doubleval($request->kg);
        $package->cbm = doubleval($request->cbm ?? 0.0);  
        $package->grand_total = doubleval($request->grand_total);
        $package->comments          = $request->comments;
        if($package->save()){ 
            if($package  && $package->id && !$request->package_id){ 
              $waybill          =   $this->makeWayBill($request);
              $package->waybill =    $waybill;
              $barcodeUrl = $this->generateBarcode($package->waybill);
              $package->barcodeImage = $barcodeUrl; 
              $package->save(); 
            } 
            return response()->json([
                'status' => 'success',
                'barcodeUrl'=> $barcodeUrl,
                'package'=> $package,
                'msg' => 'Package ' . ($request->package_id ? 'Updated ' : 'Added')  . ' successfully!', 
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'msg' => 'Unable to '. ($request->package_id ? 'Updated ' : 'Added')  . 'Package  at this moment', 
            ]);
        }    
    } 
    private function generateBarcode($waybill)
    { 
        $appUrl = config('app.url'); 
        $waybill = str_replace(' ', '-', $waybill); 
        $url = $appUrl . '/waybill/' . urlencode($waybill); 
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($url, $generator::TYPE_CODE_128, 2, 50); 
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
        return asset('barcodes/' . $sanitizedWaybill . '.png'); 
    } 
    private function makeWayBill($request){
    $originNamePart       = strtoupper(substr($request->origin_name, 0, 2)); 
    $typePart             = strtoupper(substr($request->type, 0, 1)); 
    $todayDate            = Carbon::now()->format('dmy'); 
    $clientSuit           = $request->suite;
    $today                = Carbon::now()->startOfDay();
    $existingRecordsCount = PackageModel::where('client_suite', $clientSuit)
        ->whereDate('created_at', $today)
        ->count(); 
         $recordNumber = str_pad($existingRecordsCount, 4, '0', STR_PAD_LEFT);
     
     
    $waybill              = $originNamePart . $typePart . $todayDate. ' ' .   $recordNumber;
    return $waybill;
    }
    public function getPackageClient($suit){
        $client = ClientsModel::select('id','first_name','last_name')->where('suite',$suit)->first();
        return response()->json(['status' => 'success', 'msg' => 'Client fetched successfully!','client'=> $client]);
    }
    public function destroy(Request $request)
    { 
        PackageModel::find($request->id)->delete();  
        return response()->json(['status' => 'success', 'msg' => 'Package deleted successfully!']);
    }
    public function changePaymentStatus(Request $request){
        if($request->id){
            $package = PackageModel::find($request->id);
            if($package){
               
                $package->payment_status = $package->payment_status == 1?0:1;
            
                $package->save();
                return response()->json(['status' =>'success','msg' => 'Payment status updated successfully!']);
            }else{
                return response()->json(['status' => 'error','msg' => 'Package not found!']);
            }
        }else{
            return response()->json(['status' => 'error','msg' => 'Package not found!']); 
        }
    }
   
    
  

   
}
