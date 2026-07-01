<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User as Emp;
use App\Http\Controllers\Core\AccessRightsAuth;
use App\AccessRights as AR;
use App\ControllersList as CL;
use App\Models\User;
use DB;
use Auth;
use Illuminate\Routing\Router;

class AccessRights extends AccessRightsAuth
{
    public $controllerName = "AccessRights";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // PageBreadCrumbsFunction("Access Rights Managment","Organization","Access Rights");
        $access_rights = CL::where('admin_right', 0)->get()->toArray();
        $headings = array_unique(array_column($access_rights, 'parent_module'));
        $data = array();
        foreach ($headings as $key => $parent) {
            $data[$key]['heading'] = $parent;
            $data[$key]['sub_mod'] = array_filter($access_rights, fn ($x) => $x['parent_module'] == $parent);
        }
        return view('access_rights.list', ['employees' => Emp::select('id', 'username')->whereRaw('super = 0 AND active = 1')->get(), 'controllers' => $data]);
    }

    public function listAllRights()
    {
        $data = AR::selectRaw('(SELECT CONCAT(first_name, " ", last_name) FROM users WHERE id = employee_id) AS name, employee_id, COUNT(*) AS total_rights')
            ->groupBy('employee_id')
            ->get();

        echo json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($action, $employee_id = null)
    {
        $accessed_rights    = '';
        $employees  = '';
        if ($action == 'edit' && $employee_id) {
            $employees  =  Emp::select('id', 'username')->where('id', $employee_id)->whereRaw('super = 0')->get();
            $accessed_rights    =  AR::where('employee_id', $employee_id)->get();
        } else {
            $employees  =  Emp::select('id', 'username')->where('active', 1)->whereRaw('super = 0')->get();
        }
        $access_rights   =    CL::where('admin_right', 0)->get()->toArray();
        $headings        =    array_unique(array_column($access_rights, 'parent_module'));
        $controllers            =    array();
        foreach ($headings as $key => $parent) {
            $controllers[$key]['heading'] = $parent;
            $controllers[$key]['sub_mod'] = array_filter($access_rights, fn ($x) => $x['parent_module'] == $parent);
        }

        return view('access_rights.create_access_rights', compact('accessed_rights', 'access_rights', 'employees', 'controllers', 'action', 'employee_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (AR::where('employee_id', $request->employee_id)->first()) {
            echo json_encode("exist");
            die;
        }
        $data = array();
        foreach ($request->rights as $right) {
            if ($right) {
                $data[] = array('employee_id' => $request->employee_id, 'controller_right' => $right);
            }
        }


        AR::insert($data);
        echo json_encode('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $employee_id
     * @return \Illuminate\Http\Response
     */
    public function show($employee_id)
    {
        echo json_encode(AR::where('employee_id', $employee_id)->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (AR::where('employee_id', $id)->delete()) {
            $data = array();
            foreach ($request->rights as $right) {
                if ($right) {
                    $data[] = array('employee_id' => $id, 'controller_right' => $right);
                }
            }
            AR::insert($data);
            echo json_encode('success');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($employeeId)
    {
    }

    public function revokeAccRight($employeeId)
    {

        AR::where('employee_id', $employeeId)->delete();
        return response()->json([
            'status'    =>  'success',
            'msg'       =>  "Access Rights Revoked successfully !",
        ]);
    }
}
