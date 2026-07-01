<?php

namespace App\Http\Controllers;

use App\Country;
use App\Models\Branches;
use App\Models\ClientsModel; 
use App\Models\DocumentTypes;
use App\Models\FreightRates;
use App\Models\OriginModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class FreightRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $branches = Branches::all() ;
        $origins = OriginModel::all(); 
        $customers = ClientsModel::all(); 
        return view('freights.index',compact('branches','origins','customers'));
    }

    public function getRates()
    {
        $rates = DB::table('freight_rates')
        ->leftJoin('branches', 'freight_rates.branch_id', '=', 'branches.id') 
        ->leftJoin('origins', 'freight_rates.origin_id', '=', 'origins.id') 
        ->select('freight_rates.*', 'branches.branch AS branch_name', 'origins.origin_name')
        ->get();     
        return response()->json([
            'status' => 'success',
            'msg' => 'Rates Fetched Successfully',
            'rates' => $rates
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    


    public function store(Request $request)
    {   $rate = '';
        if($request->opp_id){
            $rate = FreightRates::find($request->opp_id);
            if($rate){
                $rate->updated_at = Carbon::now();
                $rate->updated_by = Auth::user()->id;
            }
        }
        if(!$rate){
            $rate = new FreightRates();
            $rate->created_at = Carbon::now();
            $rate->created_by = Auth::user()->id;
        }
        $rate->branch_id = $request->branch;
        $rate->client_id = $request->client_id;
        $rate->origin_id = $request->origin;
        $rate->rate = $request->rate;
        $rate->additional = $request->additional;
        $rate->type = $request->rate_type; 
        if($rate->save()){
            return response()->json([
                'status' => 'success',
                'msg' => 'Freight Rate ' . ($request->opp_id ? 'Updated ' : 'Added')  . ' successfully!', 
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'msg' => 'Unable to '. ($request->opp_id ? 'Updated ' : 'Added')  . 'Freight Rate at this moment', 
            ]);
        }

         
    }


    public function destroy(Request $request)
    { 
        FreightRates::find($request->id)->delete(); 
        return response()->json(['status' => 'success', 'msg' => 'Freight Rate deleted successfully!']);
    }
    
  

   
}
