<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Core\AccessRightsAuth;
use App\PostalCode;
use App\State;
use DB;
use Auth;
use Dotenv\Regex\Success;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class GeographicalSettingsController extends AccessRightsAuth
{
    public function geographicalsetting()
    {   
        return view('organization.geographicalsetting');
    }
    public function GetGeoData()
    {
        $countries              =   Country::all();
        $states                 =   DB::SELECT("SELECT states.id as state_id,states.name,states.country_id,countries.id as country_id,countries.name as country_name FROM `states` inner join countries on countries.id=states.country_id");

        $result                 =   [];
        $result['countries']    =   $countries;
        $result['states']       =   $states;
        return $result;
    }
    function GetStatesagianstCountry($id)
    {
        $query = DB::Select("SELECT * From states Where country_id = $id ");
        return $query;
    }
    function GetCitiesagianstStates($id)
    {
        $querycities = DB::Select("SELECT states.id as state_id,states.name as state_name,countries.id as country_id,countries.name as countries_name,cities.id as city_id,
                    cities.name as city_name,cities.state_id as city_state,cities.country_id as city_country FROM `cities` inner join countries ON countries.id=cities.country_id
                    INNER JOIN states ON states.id=cities.state_id WHERE cities.state_id = $id;");
        return $querycities;
    }
    function GetStatesagianstCountryforPostal($id)
    {
        $query = DB::Select("SELECT * From states Where country_id = $id ");
        return $query;
    }
    function GetCitiesagianstStatesforPostal($id)
    {
        $querycitiespostal = DB::Select("SELECT * From cities Where state_id = $id ");
        return $querycitiespostal;
    }
    public function get_cities_against_state($id)
    {
        $cities     =   DB::Select("SELECT * From cities Where state_id = $id ");
        return response()->JSON([
            'msg'      =>  'success',
            'cities'   =>  $cities
        ]);
    }
    /// Get Postal Code Against City
    public function get_postal_code_against_city($id)
    {
        $postal_codes   =   PostalCode::WHERE('postal_codes.city_id', $id)
            ->leftjoin('countries',   'postal_codes.country_id', '=', 'countries.id')
            ->leftjoin('states',   'postal_codes.state_id', '=', 'states.id')
            ->leftjoin('cities',   'postal_codes.city_id', '=', 'cities.id')
            ->select('postal_codes.*', 'countries.name as country_name', 'cities.name as city_name', 'states.name as state_name')
            ->get();

        return response()->JSON([
            'msg'           =>  'success',
            'postalcodes'   =>  $postal_codes
        ]);
    }
    //Save Country
    function save_country(Request $request)
    {
        ///Query for Country
        if ($request->operation == 'add_country') {
            $check = DB::table('countries')->whereRaw('name = "' . $request->country_name . '"')->first();
            if ($check) {
                echo json_encode('already_exist');
            } else {
                $insert = DB::table('countries')->insert([
                    'name' => $request->country_name,
                    'iso'  => $request->iso,
                    'created_by' => FacadesAuth::user()->id
                ]);
                if ($insert) {
                    echo json_encode('success');
                } else {
                    echo json_encode('failed');
                }
            }
        } elseif ($request->operation == 'update_country') {
            if (DB::table('countries')->whereRaw('name = "' . $request->country_name . '" AND id Not IN (' . $request->hidden_country_id . ')')->first()) {
                echo json_encode('already_exist');
            } else {
                $update = DB::table('countries')->where('id', $request->hidden_country_id)->update([
                    'name' => $request->country_name,
                    'iso'  => $request->iso,
                    'updated_by' => FacadesAuth::user()->id
                ]);
                echo json_encode('update');
            }
        } elseif ($request->operation_state == 'add_state') {
            $check = DB::table('states')->whereRaw('name = "' . $request->state_name . '" And country_id = "' . $request->country_id . '"')->first();
            if ($check) {
                echo json_encode('already_exist');
            } else {
                $insert = DB::table('states')->insert([
                    'name' => $request->state_name,
                    'country_id' => $request->country_id,
                    'created_by' => FacadesAuth::user()->id
                ]);
                if ($insert) {
                    echo json_encode('success');
                } else {
                    echo json_encode('failed');
                }
            }
        } elseif ($request->operation_state == 'update_state') {
            if (DB::table('states')->whereRaw('name = "' . $request->state_name . '" And country_id = "' . $request->country_id . '"  AND id Not IN (' . $request->hidden_state_id . ')')->first()) {
                echo json_encode('already_exist');
            } else {
                $update = DB::table('states')->where('id', $request->hidden_state_id)->update([
                    'country_id' => $request->country_id,
                    'name' => $request->state_name,
                    'updated_by' => FacadesAuth::user()->id
                ]);
                echo json_encode('update');
            }
        } elseif ($request->operation_city == 'add_city') {
            $check = DB::table('cities')->whereRaw('name = "' . $request->city_name . '" And country_id = "' . $request->country_id . '" And state_id = "' . $request->state_id . '"')->first();
            if ($check) {
                echo json_encode('already_exist');
            } else {
                $insert = DB::table('cities')->insert([
                    'name' => $request->city_name,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'created_by' => FacadesAuth::user()->id
                ]);
                if ($insert) {
                    echo json_encode('success');
                } else {
                    echo json_encode('failed');
                }
            }
        } elseif ($request->operation_city == 'update_city') {
            if (DB::table('cities')->whereRaw('name = "' . $request->city_name . '" And country_id = "' . $request->country_id . '" And state_id = "' . $request->state_id . '" AND id Not IN (' . $request->hidden_city_id . ')')->first()) {
                echo json_encode('already_exist');
            } else {
                $update = DB::table('cities')->where('id', $request->hidden_city_id)->update([
                    'name' => $request->city_name,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'updated_by' => FacadesAuth::user()->id
                ]);
                echo json_encode('update');
            }
        } elseif ($request->operation_postalcode == 'add_postalcode') {
            $check = DB::table('postal_codes')->whereRaw('postal_code = "' . $request->postal_code . '" And country_id = "' . $request->country_id . '" And state_id = "' . $request->state_id . '" And city_id = "' . $request->city_id . '"')->first();
            if ($check) {
                echo json_encode('already_exist');
            } else {
                $insert = DB::table('postal_codes')->insert([
                    'postal_code' => $request->postal_code,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'created_by' => FacadesAuth::user()->id
                ]);
                if ($insert) {
                    echo json_encode('success');
                } else {
                    echo json_encode('failed');
                }
            }
        } elseif ($request->operation_postalcode == 'update_postalcode') {
            if (DB::table('postal_codes')->whereRaw('postal_code = "' . $request->postal_code . '" And country_id = "' . $request->country_id . '" And state_id = "' . $request->state_id . '" And city_id = "' . $request->city_id . '" And id Not IN (' . $request->hidden_postal_id . ')')->first()) {
                echo json_encode('already_exist');
            } else {
                $update = DB::table('postal_codes')->where('id', $request->hidden_postal_id)->update([
                    'postal_code' => $request->postal_code,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'updated_by' => FacadesAuth::user()->id
                ]);
                echo json_encode('update');
            }
        } else {
            echo json_encode('Error in Query Function');
        }
    }
    public function GetCountry($id)
    {
        echo json_encode(DB::table('countries')->where('id', $id)->first());
        exit;
    }
    public function GetState($id)
    {
        echo json_encode(DB::table('states')->where('id', $id)->first());
        exit;
    }
    public function GetCity($id)
    {
        echo json_encode(DB::table('cities')->where('id', $id)->first());
        exit;
    }
    public function GetPostalCode($id)
    {
        echo json_encode(DB::table('postal_codes')->where('id', $id)->first());
        exit;
    }
    public function GetCitiesforPostal($id)
    {
        $query_cities_p = DB::Select("SELECT * From cities Where state_id = $id ");
        return $query_cities_p;
    }
    public function delete_geographical(Request $request)
    {

        if ($request->type == 'country') {
            $delete = DB::table('countries')->where('id', $request->id)->delete();
        }
        if ($request->type == 'state') {
            $delete = DB::table('states')->where('id', $request->id)->delete();
        }
        if ($request->type == 'city') {
            $delete = DB::table('cities')->where('id', $request->id)->delete();
        }
        if ($request->type == 'postal_code') {
            $delete = DB::table('postal_codes')->where('id', $request->id)->delete();
        }
        if ($delete) {
            echo json_encode('success');
        } else {
            echo json_encode('failed');
        }
    }

    public function getStatesCities()
    {
        try {
            $states = State::all();
            $cities = City::all();
            return response()->json(['states' => $states, 'cities' => $cities], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json($ex, 422);
        }
    }
    public function getStatesCitiesPostals()
    {
        try {
            $states         = State::all();
            $cities         = City::all();
            $postal_codes   = PostalCode::all();
            return response()->json(['states' => $states, 'cities' => $cities, 'postal_codes' => $postal_codes], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json($ex, 422);
        }
    }
    ////
    /////
    /////
    /////
    /////
    /////
    /////
    /////
    /////
    /////
    /////
    /////
    /////////////////Country Status Change//////////////////
    public function changeCountryStatus(Request $request)
    {
        $id       = $request->id;
        $status   = $request->status;
        $change_status        =   Country::where('id', $id)->update(['default_status' => '1']);
        $change_status_all    =   Country::where('id', '!=', $id)->update(['default_status' => '0']);
        return response()->JSON([
            'msg'      =>  'Status Change',
            'status_id' =>   $id
        ]);
    }
    public function getSearchedCities(Request $request)
    {    
        $cities = City::where('country_id', $request->searchCountry)->where('state_id', $request->searchState)
        ->get();
        return response(['status' => 'success', 'cities' => $cities]);
    }
}
