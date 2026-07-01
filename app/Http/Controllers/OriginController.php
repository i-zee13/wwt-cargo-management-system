<?php

namespace App\Http\Controllers;

use App\Country;
use App\Models\Branches;
use App\Models\CarrierDetails;
use App\Models\ConsigneeDetails;
use App\Models\OriginModel; 
use App\Models\DocumentTypes;
use App\Models\ShipperDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class OriginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
       
        return view('origins.index');
    }

    public function getOrigins()
    {
        $origins = DB::table('origins') 
        ->leftJoin('countries', 'origins.country_id', '=', 'countries.id')
        ->leftJoin('states', 'origins.state_id', '=', 'states.id')
        ->leftJoin('cities', 'origins.city_id', '=', 'cities.id')
        ->select('origins.id','origins.origin_name' , 'countries.name AS country_name','states.name AS state_name','cities.name AS city_name')
        ->get();     
        return response()->json([
            'status' => 'success',
            'msg' => 'Origins Fetched Successfully',
            'origins' => $origins
        ]);
    }

 

    public function create($origin_id = null){
        $origin = null;
        $countries = Country::all();
        if($origin_id){
            $origin = OriginModel::with(['consigneeDetails', 'shipperDetails', 'CarrierDetails'])
            ->find($origin_id);
        } 
        return view('origins.create',compact('origin','countries'));

     }


    public function store(Request $request)
    {     
        $origin = '';
        if($request->origin_id){
            $origin = OriginModel::find($request->origin_id);
            if($origin){
                $origin->updated_at = Carbon::now();
                $origin->updated_by = Auth::user()->id;
            }
        }
        if(!$origin){
            $origin = new OriginModel();
            $origin->created_at = Carbon::now();
            $origin->created_by = Auth::user()->id;
        }

        $origin->origin_name = $request->name;
        $origin->order_no = $request->order_no;
        $origin->country_id = $request->country;
        $origin->state_id = $request->state;
        $origin->city_id = $request->city;
        $origin->address = $request->address;
        $origin->address_2 = $request->address_2;
        $origin->zip_code = $request->zip_code;
        $origin->phone = $request->phone;
        $origin->email = $request->email;
        if($origin->save()){
           $this->saveShipment($origin->id,$request);
           $this->saveConsignement($origin->id,$request);
           $this->saveCarrier($origin->id,$request);
            return response()->json([
                'status' => 'success',
                'msg' => 'Origin ' . ($request->origin_id ? 'Updated ' : 'Added')  . ' successfully!', 
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'msg' => 'Unable to '. ($request->origin_id ? 'Updated ' : 'Added')  . 'Origin  at this moment', 
            ]);
        }

         
    }
    private function saveShipment($origin_id,$request){
        $shipper = ShipperDetails::where('origin_id',$origin_id)->first();
        if(!$shipper) {
            $shipper = new ShipperDetails(); 
        }
        $shipper->shipper_name    = $request->shipper_name;
        $shipper->shipper_address = $request->shipper_address;
        $shipper->origin_id = $origin_id;
        $shipper->shipper_city_id = $request->shipper_city;
        $shipper->shipper_state_id = $request->shipper_state;
        $shipper->shipper_country_id = $request->shipper_country;
        $shipper->save();
      
        
    } 
    private function saveCarrier($origin_id,$request){
        $carrier = CarrierDetails::where('origin_id',$origin_id)->first();
        if(!$carrier){
            $carrier = new CarrierDetails(); 
        }
        $carrier->carrier_name    = $request->carrier_name;
        $carrier->origin_id    =  $origin_id;
        $carrier->carrier_ruc    = $request->carrier_ruc;
        $carrier->carrier_address = $request->carrier_address;
        $carrier->carrier_city_id = $request->carrier_city;
        $carrier->carrier_state_id = $request->carrier_state;
        $carrier->carrier_country_id = $request->carrier_country;
        $carrier->save();
      
        
    } 
    private function saveConsignement($origin_id,$request){
        $consignement = ConsigneeDetails::where('origin_id',$origin_id)->first();
        if(!$consignement) {
            $consignement = new ConsigneeDetails(); 
        }
        $consignement->consignee_name    = $request->consignee_name;
        $consignement->origin_id    = $origin_id;
        $consignement->consignee_ruc    = $request->consignee_ruc;
        $consignement->consignee_address = $request->consignee_address;
        $consignement->consignee_city_id = $request->consignee_city;
        $consignement->consignee_state_id = $request->consignee_state;
        $consignement->consignee_country_id = $request->consignee_country;
        $consignement->save();
      
        
    }


    public function destroy(Request $request)
    { 
        OriginModel::find($request->id)->delete(); 
        ShipperDetails::where('origin_id',$request->id)->delete();
        ConsigneeDetails::where('origin_id',$request->id)->delete();
        CarrierDetails::where('origin_id',$request->id)->delete();
        return response()->json(['status' => 'success', 'msg' => 'Origin deleted successfully!']);
    }
    
  

   
}
