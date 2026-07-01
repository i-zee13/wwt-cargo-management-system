<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use URL;
use Auth;
use DB;
use App\Http\Controllers\Core\AccessRightsAuth;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\LoginLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Mpdf\Output\Destination;

class Employee extends AccessRightsAuth
{
    public $controllerName = "Employee";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function GetEmployeeInfo($id)
    {

        echo json_encode(array('employee' => User::find($id), 'base_url' => URL::to('/') . '/'));
    }
    public function employee_create($id = null)
    {
        // PageBreadCrumbsFunction("Employee Management","Organization","Employees");
        $employee = '';
        if ($id) {
            $employee = User::find($id);
        }

        $designations   =   DB::table('designations')->get();
        $languages    =   DB::table('languages')->get();
        $branches    =   DB::table('branches')->get(); 
        return view('auth.create_new_emp', compact('employee', 'designations', 'languages', 'branches'));
    }

    public function UpdateEmployee(EmployeeUpdateRequest $request, $id)
    {
        // dd($request->all());
        $employee = User::find($id);

        $employee->first_name           = $request->first_name;
        $employee->last_name            = $request->last_name;
        // $employee->phone                = $request->phone;
        $employee->email                = $request->email; 
        // $employee->city                 = $request->city;
        // $employee->state                = $request->state;
        // $employee->country              = $request->country;
        $employee->address              = $request->address;
        $employee->username             = $request->email;
        if ($request->password) {
            $password                   = bcrypt($request->password);
            $employee->password         = $password;
            $employee->password_changed = '0';
        }
        // $employee->hiring               = $request->hiring;
        // $employee->salary               = $request->salary;
        // $employee->reporting_to         = $request->reporting;
        // $employee->department_id        = $request->department;
        $employee->designation          = $request->designation;
        $employee->language_id          = $request->language;
        $employee->branch_id            = $request->branch;
        $employee->updated_by           = Auth::user()->id;
        $employee->updated_at           = Carbon::now();

        if ($request->hasFile('employeePicture')) {
            $completeFileName           = $request->file('employeePicture')->getClientOriginalName();
            $fileNameOnly               = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension                  = $request->file('employeePicture')->getClientOriginalExtension();
            $empPicture                 = str_replace(' ', '_', $fileNameOnly) . '_' . time() . '.' . $extension;
            $path                       = $request->file('employeePicture')->storeAs('public/employees', $empPicture);
            if (Storage::exists('public/employees/' . str_replace('./storage/employees/', '', $employee->picture))) {
                Storage::delete('public/employees/' . str_replace('./storage/employees/', '', $employee->picture));
            }
            $employee->picture          = '/storage/employees/' . $empPicture;
        }
        echo json_encode($employee->save());
    }

    public function update_user_password(Request $request)
    {

        $employee       =   User::find(GetActiveGuardDetail()->id);
        $hashedPassword =   $employee->password;

        if ($request->current_password) {
            if (Hash::check($request->current_password, $hashedPassword)) {
                $employee->password = bcrypt($request->confirm_password);
                if ($employee->save()) {
                    echo json_encode("success");
                    die;
                } else {
                    echo json_encode("failed");
                    die;
                }
            } else {
                echo json_encode('not_match');
                die;
            }
        } else {
            echo json_encode("empty");
            die;
        }
    }
    public function update_user_profile_pic(Request $request)
    {
        $employee = User::find(GetActiveGuardDetail()->id);
        if ($request->hasFile('employeePicture')) {
            $completeFileName = $request->file('employeePicture')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('employeePicture')->getClientOriginalExtension();
            $empPicture = str_replace(' ', '_', $fileNameOnly) . '_' . time() . '.' . $extension;
            $path = $request->file('employeePicture')->storeAs('public/employees', $empPicture);
            if (Storage::exists('public/employees/' . str_replace('./storage/employees/', '', $employee->picture))) {
                Storage::delete('public/employees/' . str_replace('./storage/employees/', '', $employee->picture));
            }
            $employee->picture = '/storage/employees/' . $empPicture;
           
        } else {
            if(empty($request->employeePictureHidden)){
                echo json_encode('empty'); 
            }else{
                $employee->picture = $request->employeePictureHidden;
            }
        }
        if ($employee->save()) {
            echo json_encode("success");
        } else {
            echo json_encode("failed");
        }
    }
    public function edit_profile($id)
    {
 
        if ($id != FacadesAuth::user()->id) {
            return redirect('/');
        }

        $user_id                    =   NULL;
        $designation_name           =   NULL; 
        $country_id                  =GetActiveGuardDetail()->country_id?? NULL;
        $state_id                   =  GetActiveGuardDetail()->state_id?? NULL;
        
        $user_id                =   FacadesAuth::user()->id;
        $designation            =   DB::table('designations')->where('id', $user_id > 0 ? FacadesAuth::user()->designation : 0)->first();
        $country                =   DB::table('countries')->where('id', $country_id)->first();
        $state                  =   DB::table('states')->where('id', $state_id)->first();
        if (!empty($designation)) {
            $designation_name   =   $designation->designation;
        } 
        $country_name           = $country && $country->name? $country->name:'NA';
        $state_name             = $state && $state->name? $state->name:'NA';
        
        return view('includes.edit_profile', ['designation_name' => $designation_name,'country_name' => $country_name,'state_name' => $state_name]);
    }
    public function loginLogs()
    {
        $records = LoginLog::orderBy('id', 'desc')->take(30)->get(); 
        return view('includes.login_logs', ['records' => $records]);
    }
    public function getLogs(Request $request)
    {  
        $logs = DB::select("SELECT login_logs.*  
                                    FROM login_logs  
                                    WHERE DATE(login_logs.created_at) BETWEEN '$request->from_date' AND '$request->to_date' Order by id desc");
        return response()->json([
            'status'    => 'success',
            'msg'       => 'Logs Fetched Successfully',
            'logs'      => $logs
        ]);
    }
}
