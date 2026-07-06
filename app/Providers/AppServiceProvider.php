<?php

namespace App\Providers;

use App\ControllersList as CL;
use App\Models\ControllerDesignationAssignment;
use Auth;
use DB;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        Schema::defaultStringLength(191);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        
        app('view')->composer('layouts.master', function ($view) {
          
            $action = app('request')->route()->getAction();
            $controller = class_basename($action['controller']);
            $designation = '';
            list($controller, $action) = explode('@', $controller);
            $userPermissions    =   array(); 
            $allControllers = [];

         
            if(Auth::guard('web')->check()){
                $designation = DB::table('designations')->where("id", Auth::user()->designation)->first(); 
            
                $allControllers     =   CL::orderBy('parent_module_priority')->get();
                $allControllers     =   $allControllers->filter(function($item){
                    $item->designation_ids  =   json_encode(DB::table("controller_designation_assignments")->whereRaw("controller_id = $item->id")->pluck("designation_id")->toArray());
                    return $item; 
                })->values();  
                if(Auth::user()->super){
                    $controllers            =   CL::get();
                    foreach($controllers as $obj)
                    {
                        $userPermissions[]  =   $obj->controller;
                    }
                }else{
                    $employee_des           =   Auth::user()->designation;
            
                    $controllers            =   ControllerDesignationAssignment::whereRaw("designation_id = $employee_des")->get();
                    $userPermissions        =   ['admin/Profile','/admin/home'];
                    foreach($controllers as $object)
                    {
                        $userPermissions[] =    $object->controller_name;
                    } 
                }
            }else if(Auth::guard('clients')->check()){
                    $allControllers =   $controllers =[
                        [
                            'id' => 1,
                            'controller' => 'customer-profile',
                            'made_up_name' => 'Customer Profile',
                            'parent_module' => 'Customer',
                            'sub_module' => 'Profile',
                            'sub_module_priority' => 2, 
                            'parent_module_priority' => 1,  
                            'show_in_sidebar' => 0,
                            'show_in_sub_menu' => 0,  
                            'admin_right' => 0,  
                            'sub_menu_icon' => 'profile-icon.svg',  
                            'logo' => null, 
                            'designation_ids' => '[]',  
                        ],
                        [
                            'id' => 2,
                            'controller' => 'customer-home',
                            'made_up_name' => 'Customer Home',
                            'parent_module' => 'Customer',
                            'sub_module' => 'Home',
                            'sub_module_priority' => 2,
                            'parent_module_priority' => 1,
                            'show_in_sidebar' => 0,
                            'show_in_sub_menu' => 0,
                            'admin_right' => 0,
                            'sub_menu_icon' => 'home-icon.svg',
                            'logo' => null,
                            'designation_ids' => '[]',
                        ],
                        [
                            'id' => 3,
                            'controller' => 'customer-packages',
                            'made_up_name' => 'Customer Packages',
                            'parent_module' => 'Customer Packages',
                            'sub_module' => 'Packages',
                            'sub_module_priority' => 2,
                            'parent_module_priority' => 1,
                            'show_in_sidebar' => 1,
                            'show_in_sub_menu' => 1,
                            'admin_right' => 0,
                            'logo' => 'package.png', 
                            'sub_menu_icon' => 'package.png', 
                            'designation_ids' => '[]',
                        ],
                        [
                            'id' => 4,
                            'controller' => 'create-customer-package',
                            'made_up_name' => 'Customer Packages',
                            'parent_module' => 'Customer Packages',
                            'sub_module' => 'Create Package',
                            'sub_module_priority' => 2,
                            'parent_module_priority' => 1,
                            'show_in_sidebar' => 0,
                            'show_in_sub_menu' => 1,
                            'admin_right' => 0,
                            'logo' => 'package.png', 
                            'sub_menu_icon' => 'package.png', 
                            'designation_ids' => '[]',
                        ],
                       
                    ];
                    $userPermissions        =   ['customer-profile','customer-home','customer-packages','create-customer-package'];
                     
            }   
            $activeLang = session('locale', config('app.locale'));
             
            $view->with(compact('controller', 'action', 'userPermissions',   'allControllers',   'designation','activeLang'));
        });

     
    }

     

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
