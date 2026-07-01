<?php

namespace App\Http\Controllers;

use App\Models\ControllerDesignationAssignment;
use Illuminate\Http\Request;  
use App\ControllersList as CL;
use App\ControllersList;        
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Core\AccessRightsAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Admin extends AccessRightsAuth
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allRoutes      =   CL::orderBy('parent_module_priority', 'asc')->get()->toArray();
        $data           =   array_values($this->unique_multidim_array($allRoutes, 'parent_module'));
        
        foreach ($data as $key => $value) {
            $data[$key]['sub_mods'] = array_values(array_filter($allRoutes, function($item) use($value){
                return $value['parent_module'] == $item['parent_module'];
            }));

            array_multisort(array_column($data[$key]['sub_mods'], 'sub_module_priority'), SORT_ASC, $data[$key]['sub_mods']);
        }
        $designations   =   DB::table("designations")->selectRaw("id,designation")->whereRaw("designation != 'Admin'")->get();

        return view('admin.settings', [ 'data' => $data,'designations' => $designations ]);
    }

    public function SaveSubMod(Request $request){
        $assign_to_designation      =   $request->assign_to_designation; 
        
        $icon                       =   "";
        $cl                         =   new CL;
        if($request->item_id){
            $cl                     =   CL::find($request->item_id);
        }
        $parentMod                  =   DB::table('controllers')->where('parent_module', $request->parent)->first();
        
        $cl->controller             =   $request->route;
        $cl->made_up_name           =   $request->made_up_name;
        $cl->sub_module             =   $request->module_name;
        $cl->show_in_sub_menu       =   $request->show_in_sub_menu;
        $cl->parent_module          =   $request->parent;
        $cl->sub_module_priority    =   $request->priority;
        $cl->parent_module_priority =   $parentMod->parent_module_priority;
        if($cl->save()){
            if(collect($assign_to_designation)->count() > 0){
                DB::table("controller_designation_assignments")->whereRaw("controller_id = $cl->id")->delete();
                foreach($assign_to_designation as $desgination){ 
                    $insertAssi                     =   new ControllerDesignationAssignment();
                    $insertAssi->designation_id     =   $desgination; 
                    $insertAssi->controller_id      =   $cl->id;
                    $insertAssi->controller_name    =   $request->route;
                    $insertAssi->created_at         =   Carbon::now();
                    $insertAssi->created_by         =   Auth::user()->id;
                    $insertAssi->save();
                }
            }
        }
        return [ 'code' => 200 ];
    }

    public function UpdateSubModPriority(Request $request){
        foreach ($request->data as $key => $item) {
            $cl = CL::find($item['id']);
            $cl->sub_module_priority = $item['priority'];
            $cl->save();
        }
        return [ 'code' => 200 ];
    }

    public function DeleteSubNavItem(Request $request){
        CL::find($request->id)->delete();
    }
    
    public function UpdateParentMod(Request $request){
        CL::where('parent_module', $request['old_parent_mod'])->update(['parent_module' => $request['parentMod']]);
    }

    public function UpdateParentModPriority(Request $request){
        foreach ($request->data as $key => $item) {
            CL::where('parent_module', $item['module'])->update(['parent_module_priority' => $item['priority']]);
        }
        return [ 'code' => 200 ];
    }

    public function SaveParentMod(Request $request){
       
        $parent_icon                =   "";
       
        if ($request->hasFile('parent_icon')) {
            // Retrieve the existing icon record from the database
            $existingIcon = CL::where('parent_module', $request->parent_module_name_update)->first();
            
            if ($existingIcon) {
                // Check if the icon exists in storage and delete it
                if (Storage::exists('public/menu_icons/' . $existingIcon->logo)) {
                    Storage::delete('public/menu_icons/' . $existingIcon->logo);
                }
            }
        
            // Generate a unique name for the new file
            $completeFileName = $request->file('parent_icon')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('parent_icon')->getClientOriginalExtension();
            $iconPath = str_replace(' ', '_', $fileNameOnly) . '-' . rand() . '_' . time() . '.' . $extension;
        
            // Store the file in 'public/menu_icons'
            $path = $request->file('parent_icon')->storeAs('public/menu_icons', $iconPath);
            
            // Save the new icon path
            $parent_icon = $iconPath;
        }
        

         
        $assign_to_designation      =   $request->assign_to_designation;
        $cl                         =   new CL;
        if($request->parent_module_name_update){
            if($parent_icon){
                CL::where('parent_module', $request->parent_module_name_update)->update([ 'parent_module' => $request->parent_module_name, 'logo' => $parent_icon, 'show_in_sidebar' => $request->show_in_sidebar ]);
            }else{
                CL::where('parent_module', $request->parent_module_name_update)->update([ 'parent_module' => $request->parent_module_name, 'show_in_sidebar' => $request->show_in_sidebar ]);
            }
            return [ 'code' => 200 ];
        }
            
        $cl->controller             =   $request->route;
        $cl->made_up_name           =   $request->made_up_name;
        $cl->sub_module             =   $request->module_name;
        $cl->show_in_sidebar        =   $request->show_in_sidebar; 
        if ($parent_icon) {
            $cl->logo                   =   $parent_icon;
        }
       
        $cl->parent_module          =   $request->parent_module_name;
        $cl->sub_module_priority    =   1;
        $cl->parent_module_priority =   $request->priority;
        if($cl->save()){
            if(collect($assign_to_designation)->count() > 0){
                DB::table("controller_designation_assignments")->whereRaw("controller_id = $cl->id")->delete();
                foreach($assign_to_designation as $desgination){
                  
                    $insertAssi                     =   new ControllerDesignationAssignment();
                    $insertAssi->designation_id     =   $desgination; 
                    $insertAssi->controller_id      =   $cl->id;
                    $insertAssi->controller_name    =   $request->route;
                    $insertAssi->created_at         =   Carbon::now();
                    $insertAssi->created_by         =   Auth::user()->id;
                    $insertAssi->save();
                }
            }
        }
        return [ 'code' => 200 ];
    }

    public function DeleteParentMod(Request $request){
        CL::where('parent_module', $request->parent_module)->delete();
    }

 

  

    

    // updateModuleForInController
    function updateModuleForInController(){
        $getAccessRights        =   DB::table("access_rights")
                                    ->selectRaw("employee_id,controller_right")
                                    ->whereRaw("employee_id IN (SELECT id FROM users )")
                                    ->groupBy("employee_id","controller_right")
                                    ->get();
        $merged                 =   $getAccessRights->groupBy('controller_right')->map(function ($items) { 
                                        return $items->pluck('employee_id')->unique()->values();
                                    });         
                                    
        $merged                 =   $merged->toArray();
        DB::table("controller_designation_assignments")->delete();
        foreach($merged as $right => $employees){
            $employee_ids       =   implode(',',collect($employees)->toArray());
            $controllerRec      =   DB::table("controllers")->selectRaw("id")->whereRaw("controller = '$right'")->first();
            if($controllerRec){
                $desRecords     =   DB::table("users")->selectRaw("id,designation")
                                    ->whereRaw("id IN ({$employee_ids})")->groupBy("designation")->get();
                if(collect($desRecords)->count() > 0){
                    foreach($desRecords as $designation){
                        $insertAssi                     =   new ControllerDesignationAssignment();
                        $insertAssi->designation_id     =   $designation->designation; 
                        $insertAssi->controller_id      =   $controllerRec->id;
                        $insertAssi->controller_name    =   $right;
                        $insertAssi->created_at         =   Carbon::now();
                        $insertAssi->created_by         =   Auth::user()->id;
                        $insertAssi->save();
                    }
                }
            }
        }
    }

    
}
