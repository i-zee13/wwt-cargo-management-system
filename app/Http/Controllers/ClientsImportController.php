<?php

namespace App\Http\Controllers;

use App\AcquisitionSource;
use App\City;
use App\Client;
use App\ContactMainDetails;
use App\Http\Controllers\Core\AccessRightsAuth;
use App\Models\Branches;
use App\Models\ClientsModel;
use App\Models\CommunityAssignments;
use App\Models\CommunityModel;
use App\Country;
use App\Models\DocumentTypes;
use App\Models\SubCommunityModel;
use App\Models\Warehouse;
use App\PostalCode;
use App\State;
use Hash;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Import\Imports\ClientsImport;
use Validator;
use DB;
use Auth;
use DateTime;
use Carbon\Carbon;
use Modules\Import\Imports\CommunityDataImport;

class ClientsImportController extends AccessRightsAuth
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $page['page_header'] = 'Clients Import';
        $page['url'] = '/admin/clients-uploads';
        $page['sample-url'] = 'Client Bulk Upload Sample.xlsx';
        return view('index', compact('page'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('import::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        ini_set('memory_limit', '3000M');
        ini_set('max_execution_time', '0');
        $this->validate($request, [
            'file' => 'required|file|mimes:xls,xlsx'
        ]);
        $import = new \App\Imports\ClientsImport();
        Excel::import($import, $request->file);
        if ($import->getRowCount() > 0) {
            if (@count($import->getErrors()) > 0) {
                return Response()->json([
                    'status' => 'error',
                    'msg' => 'Invalid Data',
                    'errors' => $import->getErrors(),
                    'header' => $import->getHeader()
                ]);
            } else {
                $created_by = GetActiveGuardDetail()->id;
                foreach ($import->getData() as $row) {
                    $suite                      =          $row['suite'];
                    $first_name                 =          $row['first_name'];
                    $last_name                  =          $row['last_name'];
                    $email                      =          $row['email'];
                    $password                   =          $row['password'];
                    $phone_number               =          $row['phone_number'];
                    $address                    =          $row['address'];
                    $country_name               =          $row['country_name'];
                    $state_name                 =          $row['state_name'];
                    $branch_name                =          $row['branch_name'];
                    $document_type              =          $row['document_type'];
                    $document_number            =          $row['document_number'];
                    $postal_code                =          $row['postal_code'];
                    $email_verified_date        =          $row['email_verified_date'];
                    $locality                   =          $row['locality'];
                    $person_or_company          =          $row['person_or_company'] ?strtolower($row['person_or_company']):'';  
                    $company_name               =          $row['company_name']; 
                    if (!$suite || !trim($suite)) {
                        $maxId                  =          ClientsModel::max('id');
                        $client_id              =          $maxId + 1;
                        $mBranch                =          strtoupper(substr($branch_name, 0, 2));
                        $suite                  =          'COMM' . $mBranch . $client_id;
                    } 
                    $country                    =           Country::whereRaw('LOWER(name) = ?', [strtolower($country_name)])->first();
                    $state                      =           State::where('country_id', $country->id)
                                                            ->whereRaw('LOWER(name) = ?', [strtolower($state_name)])
                                                            ->first();   
                    $document_type              =           DocumentTypes::whereRaw('LOWER(document_name) = ?', [strtolower($document_type)])->first();
                    $branch_id                  =           Branches::whereRaw('LOWER(branch) = ?', [strtolower($branch_name)])->first();
                    if (!empty($row['email_verified_date'])) {
                        $rawValue = $row['email_verified_date']; // Get the raw value from the row
                    
                        if (is_numeric($rawValue)) {
                            // Handle Excel serialized date
                            $email_verified_date = Carbon::instance(
                                \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$rawValue)
                            )->toDateTimeString();
                        } else {
                            // Define a list of supported date formats
                            $supportedFormats = [
                                'd-m-Y H:i:s', // e.g., 15-05-2024 14:30:00
                                'd-m-Y',       // e.g., 15-05-2024
                                'Y-m-d H:i:s', // e.g., 2024-05-15 14:30:00
                                'Y-m-d',       // e.g., 2024-05-15
                                'm/d/Y H:i:s', // e.g., 05/15/2024 14:30:00
                                'm/d/Y',       // e.g., 05/15/2024
                            ];
                    
                            // Try parsing the date with each supported format
                            $email_verified_date = null;
                            foreach ($supportedFormats as $format) {
                                $parsedDate = \DateTime::createFromFormat($format, $rawValue);
                                if ($parsedDate !== false) {
                                    $email_verified_date = Carbon::createFromFormat($format, $rawValue)->format('Y-m-d H:i:s');
                                    break;
                                }
                            }
                    
                            // If no format matches, handle invalid values
                            if (is_null($email_verified_date)) {
                                // Optionally, log or track invalid values
                                $email_verified_date = null; // Or handle invalid data as needed
                            }
                        }
                    } else {
                        $email_verified_date = null; // Handle empty or null input
                    } 
                    $country                              =             $country ? $country->id : null;
                    $state                                =             $state ? $state->id : null;
                    $branch_id                            =             $branch_id ? $branch_id->id : null;
                    $document_type                        =             $document_type ? $document_type->id : null; 
                    $newClient                            =             new ClientsModel();
                    $newClient->suite                     =             $suite;
                    $newClient->first_name                =             $first_name;
                    $newClient->last_name                 =             $last_name;
                    $newClient->email                     =             $email;
                    $newClient->phone                     =             $phone_number;
                    $newClient->address                   =             $address;
                    $newClient->document_number           =             $document_number;
                    $newClient->postal_code               =             $postal_code;
                    $newClient->email_verified_at         =             $email_verified_date;
                    $newClient->document_type_id          =             $document_type;
                    $newClient->password                  =             Hash::make($password);
                    $newClient->locality                  =             $locality; 
                    $newClient->client_type               =             $person_or_company; 
                    $newClient->country_id                =             $country; 
                    $newClient->state_id                  =             $state; 
                    $newClient->branch_id                 =             $branch_id; 
                    $newClient->created_at                =             Carbon::now();  
                    $newClient->created_by                =             $created_by; 
                    
                    if ($person_or_company === 'company') {
                        $newClient->company_name = $company_name; 
                    }   
                    $newClient->save();
                }
                
                return Response()->json([
                    'status' => 'success',
                    'msg' => 'Uploaded Successfully',
                    'rows' => $import->getRowCount()
                ]);
            }
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('import::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('import::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
