<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Core\AccessRightsAuth;
use App\AccessRights as AR;
use App\Models\ControllerDesignationAssignment;
use App\ControllersList as CL;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DesignationAccessRightsController extends AccessRightsAuth
{
    public $controllerName = "DesignationAccessRightsController";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $access_rights = CL::where('admin_right', 0)->get()->toArray();
        $headings = array_unique(array_column($access_rights, 'parent_module'));
        $data = array();
        foreach ($headings as $key => $parent) {
            $data[$key]['heading'] = $parent;
            $data[$key]['sub_mod'] = array_filter($access_rights, fn($x) => $x['parent_module'] == $parent);
        }
        $designations   =   DB::table("designations")->selectRaw("id,designation")->whereRaw("designation != 'Admin'")->get();
         
        return view('access_rights.designation_rights', ['designations' => $designations, 'controllers' => $data]);
    }

    public function listAllRights()
    {
        $designations   =   DB::table("designations")->selectRaw("id,designation,(SELECT count(DISTINCT controller_name) FROM controller_designation_assignments WHERE designation_id = designations.id) as total_rights")->whereRaw("designation != 'Admin'")->get();
       
        echo json_encode($designations);
    }
    public function store(Request $request)
    {
       
        $designation_id = $request->designation_id;
        $data           = array();
        DB::table('controller_designation_assignments')->where('designation_id', $designation_id)->destory();
    
        foreach ($request->rights as $right) {
            if ($right) {
                $cl_id  =   CL::where('controller', $right)->value('id');
                $data[] = [
                    'controller_id'     =>  $cl_id,
                    'controller_name'   =>  $right,
                    'designation_id'    =>  $designation_id, 
                    'created_at'        =>  Carbon::now(),
                    'created_by'        =>  Auth::user()->id
                ]; 
                DB::table('controller_designation_assignments')->insert($data);
            }
        }
        $this->_logs('Access Rights', ['operation' => 'Add', 'employee' => $request->employee_id]);
        echo json_encode('success');
    }


    public function show($designation_id)
    { 
        echo json_encode(ControllerDesignationAssignment::where('designation_id', $designation_id)->get());
    }



    public function update(Request $request, $id)
    { 
        $designation_id = $request->designation_id;
        $data           = array();
       ControllerDesignationAssignment::where('designation_id', $designation_id)->delete(); 
        foreach ($request->rights as $right) {
            if ($right) {
                $data   =   array(); 
                $cl_id  =   CL::where('controller', $right)->value('id');
                $data[] = [
                    'controller_id'     =>  $cl_id,
                    'controller_name'   =>  $right,
                    'designation_id'    =>  $designation_id, 
                    'created_at'        =>  Carbon::now(),
                    'created_by'        =>  Auth::user()->id
                ]; 
                DB::table('controller_designation_assignments')->insert($data);
            }
        }
              echo json_encode('success');
    } 

    public function destroy($employeeId) {}

    public function revokeAccRight($designation_id)
    { 
       $old_record = ControllerDesignationAssignment::where('designation_id', $designation_id)->get();
        ControllerDesignationAssignment::where('designation_id', $designation_id)->delete(); 
        echo json_encode('success');
    }
}
