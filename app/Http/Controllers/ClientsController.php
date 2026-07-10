<?php

namespace App\Http\Controllers;

use App\Country;
use App\Models\Branches;
use App\Models\ClientsModel; 
use App\Models\DocumentTypes;
use App\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $branches = Branches::where('status',1)->get();
        $document_types = DocumentTypes::where('status',1)->get();
        $countries = Country::all();
        $states = State::all();
        return view('clients.index',compact('branches','document_types','countries','states'));
    }
    

    public function getClients(Request $request)
    {
        $clients = DB::table('clients')
        ->leftJoin('branches', 'clients.branch_id', '=', 'branches.id')
        ->leftJoin('countries', 'clients.country_id', '=', 'countries.id')
        ->leftJoin('states', 'clients.state_id', '=', 'states.id')
        ->select('clients.*', 'branches.branch AS branch_name', 'countries.name AS country_name', 'states.name AS state_name');
    
        if ($request->date) {
            $clients->whereDate('clients.created_at', '<=', $request->date);
        }
        
        $clients = $clients->get();
        return response()->json([
            'status' => 'success',
            'msg' => 'Clients Fetched Successfully',
            'clients' => $clients
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    


     public function store(Request $request)
     {   
         
        $isEmailExist = ClientsModel::where('email',$request->email)->where('id','!=',$request->opp_id)->exists();
         if($isEmailExist){ 
            return response()->json([
                'status' => 'error',
                'msg' => 'Email already exists. Please try with another email address',
            ]);
        }
        $isSuiteALreadyExist = ClientsModel::where('suite',$request->suite)->where('id','!=',$request->opp_id)->exists();
         if($isSuiteALreadyExist){ 
            return response()->json([
                'status' => 'error',
                'msg' => 'Suite already exists. Please try with another Suite',
            ]);
        }
         $client = null; 
         if ($request->opp_id && intval($request->opp_id)) {
             $client = ClientsModel::find($request->opp_id);
             if ($client) {
                 $client->updated_at = Carbon::now();  
                 $client->updated_by = Auth::user()->id;
             }
         } 
         if (!$client) {
             $client = new ClientsModel();
             $client->created_at = Carbon::now();  
             $client->created_by = Auth::user()->id;
         } 
         if($request->suite){
             $client->suite = $request->suite;
            
         }
         $client->first_name = $request->first_name;
         $client->last_name = $request->last_name;
         if($request->client_type != 'person'){ 
            $client->company_name = $request->company_name;
         }
         $client->email = $request->email;
         $client->phone = $request->phone;
         
         $client->document_type_id = $request->document_type;
         $client->country_id = $request->country;
         $client->branch_id = $request->branch;
         $client->document_number = $request->document_number; 
         $client->address = $request->address; 
         $client->postal_code = $request->postal_code; 
         $client->state_id = $request->state; 
         $client->client_type = $request->client_type; 
         if($request->password){
            $client->password =  bcrypt($request->password);
         }
         if ($client->save()) { 
             if (!$request->opp_id && $client->id && !$request->suite) {
                 $branch_name = $request->branch_name;
                 $mBranch = strtoupper(substr($branch_name, 0, 2));
                 $client->suite = config('brand.suite_prefix', 'WWT') . $mBranch . $client->id;
                 $client->save(); 
             }else if($request->opp_id && $request->suite) {
                $this->updatePackagesClientSuite($client);
             }
     
             return response()->json([
                 'status' => 'success',
                 'msg' => 'Client ' . ($request->opp_id ? 'Updated' : 'Added') . ' successfully!',
             ]);
         } else {
             return response()->json([
                 'status' => 'error',
                 'msg' => 'Unable to ' . ($request->opp_id ? 'update' : 'add') . ' client at this moment',
             ]);
         }
     }
     
     private function updatePackagesClientSuite($client){
        \DB::statement("UPDATE packages SET client_suite = ? WHERE client_id = ?", [$client->suite, $client->id]);
     }

    public function destroy(Request $request)
    { 
        ClientsModel::find($request->id)->delete(); 
        return response()->json(['status' => 'success', 'msg' => 'Client deleted successfully!']);
    }
    public function verifyClient(Request $request){
        $client = ClientsModel::find($request->id);
        $client->email_verified_at = Carbon::now()->toDateString();  
        $client->save();
        return response()->json(['status' => 'success', 'msg' => 'Client verified successfully!']);
    }
    
  

   
}
