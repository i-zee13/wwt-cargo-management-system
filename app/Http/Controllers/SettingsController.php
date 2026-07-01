<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\DocumentTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Core\AccessRightsAuth;
use App\Models\NotificationTypes;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB as FacadesDB;

class SettingsController extends AccessRightsAuth
{

    public function manage_settings()
    {
        return view('settings.settings');
    }

    public function GetDesignationsData()
    {
        $designations = DB::table('designations')->get();
        echo json_encode($designations);
    }
    public function GetBranchData($id = null)
    {   if($id){
        echo json_encode(DB::table('branches')->where('id', $id)->first());
    }else{
        $branches = DB::table('branches')->get();
        echo json_encode($branches);
    }
    
    }
    public function GetDocumentTypes($id = null)
    {   if($id){
        echo json_encode(DB::table('documents_types')->where('id', $id)->first());
    }else{
        $branches = DB::table('documents_types')->get();
        echo json_encode($branches);
    }
    
    }
    public function GetDepartmentData()
    {
        $departments = DB::table('departments')->get();
        echo json_encode($departments);
    }
    public function GetLanguageData()
    {
        $languages = DB::table('languages')->get();
        echo json_encode($languages);
    }

    public function GetDesignation($id)
    {
        echo json_encode(DB::table('designations')->where('id', $id)->first());
    }

    public function GetDepartment($id)
    {
        echo json_encode(DB::table('departments')->where('id', $id)->first());
    }
    public function getLanguage($id)
    {
        echo json_encode(DB::table('languages')->where('language_id', $id)->first());
    }

   
    public function save_settings(Request $request)
    {

        $already_exist = false;
        $insert = null;
        $update = null;
        if ($request->operation == 'add') {
            if ($request->opp_name_input == 'designation') {
                if (DB::table('designations')->where('designation', $request->designation_name)->first()) {
                    $already_exist = true;
                } else {
                    $insert = DB::table('designations')->insert([
                        'designation' => $request->designation_name,
                        'created_at'  => date('Y-m-d H:i:s'),
                        'created_by'  => Auth::user()->id
                    ]);
                }
            } 
            if ($request->opp_name_input == 'branches') {
                if (DB::table('branches')->where('branch', $request->branch)->first()) {
                    $already_exist = true;
                } else {
                    $insert = DB::table('branches')->insert([
                        'branch' => $request->branch,
                        'code' => $request->code,
                        'created_at'  => Carbon::now(),
                        'created_by'  => Auth::user()->id
                    ]);
                }
            } 
            if ($request->opp_name_input == 'documents') {
                if (DB::table('documents_types')->where('document_name', $request->document_name)->first()) {
                    $already_exist = true;
                } else {
                    $insert = DB::table('documents_types')->insert([
                        'document_name' => $request->document_name, 
                        'created_at'  => Carbon::now(),
                        'created_by'  => Auth::user()->id
                    ]);
                }
            } 
            else if ($request->opp_name_input == 'department') {
                if (DB::table('departments')->where('department', $request->department_name)->first()) {
                    $already_exist = true;
                } else {
                    $insert = DB::table('departments')->insert([
                        'department' => $request->department_name,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id
                    ]);
                }
            } else if ($request->opp_name_input == 'language') {
                if (DB::table('languages')->where('language_title', $request->language_title)->first()) {
                    $already_exist = true;
                } else {
                    $insert = DB::table('languages')->insert([
                        'language_title' => $request->language_title,
                        'iso_code'       => $request->iso_code,
                        'rtl'            => $request->rtl,
                        'created_at'     => date('Y-m-d H:i:s'),
                        'created_by'     => Auth::user()->id
                    ]);
                }
            }

            if ($insert) {
                echo json_encode('success');
            } else if ($already_exist) {
                echo json_encode('already_exist');
            } else {
                echo json_encode('failed');
            }
        } else {
            if ($request->opp_name_input == 'designation') {
                if (DB::table('designations')->whereRaw('designation = "' . $request->designation_name . '" And id NOT IN (' . $request->opp_id . ')')->first()) {
                    $already_exist = true;
                } else {
                    try {
                        $update = DB::table('designations')->where('id', $request->opp_id)->update([
                            'designation' => $request->designation_name,
                            'updated_at'  => date('Y-m-d H:i:s'),
                            'updated_by'  => Auth::user()->id
                        ]);
                    } catch (\Illuminate\Database\QueryException $ex) {
                        $insert = null;
                    }
                }
            } else if ($request->opp_name_input == 'department') {
                if (DB::table('departments')->whereRaw('department = "' . $request->department_name . '" And id NOT IN (' . $request->opp_id . ')')->first()) {
                    $already_exist = true;
                } else {
                    try {
                        $update = DB::table('departments')->where('id', $request->opp_id)->update([
                            'department' => $request->department_name,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => Auth::user()->id
                        ]);
                    } catch (\Illuminate\Database\QueryException $ex) {
                        $insert = null;
                    }
                }
            }
            else if ($request->opp_name_input == 'branches') {
                if (DB::table('branches')->whereRaw('branch = "' . $request->branch . '" And id NOT IN (' . $request->opp_id . ')')->first()) {
                    $already_exist = true;
                } else {
                    try {
                        $update = DB::table('branches')->where('id', $request->opp_id)->update([
                            'branch' => $request->branch,
                            'code' => $request->code,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => Auth::user()->id
                        ]);
                    } catch (\Illuminate\Database\QueryException $ex) {
                        $insert = null;
                    }
                }
            } else if ($request->opp_name_input == 'documents') {
                if (DB::table('documents_types')->whereRaw('document_name = "' . $request->document_name . '" And id NOT IN (' . $request->opp_id . ')')->first()) {
                    $already_exist = true;
                } else {
                    try {
                        $update = DB::table('documents_types')->where('id', $request->opp_id)->update([
                            'document_name' => $request->document_name, 
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => Auth::user()->id
                        ]);
                    } catch (\Illuminate\Database\QueryException $ex) {
                        $insert = null;
                    }
                }
            } 
             else if ($request->opp_name_input == 'language') {
                if (DB::table('languages')->whereRaw('language_title = "' . $request->language_title . '" And language_id NOT IN (' . $request->opp_id . ')')->first()) {
                    $already_exist = true;
                } else {
                    try {
                        $update = DB::table('languages')->where('language_id', $request->opp_id)->update([
                            'language_title' => $request->language_title,
                            'iso_code'       => $request->iso_code,
                            'rtl'            => $request->rtl,
                            'updated_at'     => date('Y-m-d H:i:s'),
                            'updated_by'     => Auth::user()->id
                        ]);
                    } catch (\Illuminate\Database\QueryException $ex) {
                        $insert = null;
                    }
                }
            }

            if ($update) {
                echo json_encode('success');
            } else if ($already_exist) {
                echo json_encode('already_exist');
            } else {
                echo json_encode('failed');
            }
        }
    }

   
    public function updateLanguageStatus(Request $req)
    {
        $lang = DB::table('languages')->where('language_id', $req->id)->update([
            'status' => $req->status
        ]);
       


        echo json_encode('success');
    }
    public function updateStatus(Request $req)
{    
    $req->validate([
        'id' => 'required|integer',
        'type' => 'required|string',
    ]); 
    switch ($req->type) {
        case 'department':
            $model = Departments::find($req->id);
            break;
        case 'designation':
            $model = Designations::find($req->id);
            break; 
        case 'branches':
            $model = Branches::find($req->id);
            break;
        case 'documents':
            $model = DocumentTypes::find($req->id);
            break;
        default:
            return response()->json(['error' => 'Invalid type'], 400);
    } 
    if (!$model) {
        return response()->json(['error' => 'Resource not found'], 404);
    } 
    $model->status = !$model->status;
    $model->save();  

    return response()->json('success');
}

    
    
}
