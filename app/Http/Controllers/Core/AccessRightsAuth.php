<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\User as User;
use DB;
use Auth;
use URL;

class AccessRightsAuth extends Controller
{
    protected $user;

    function __construct(Request $request){
        
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = 1;
            return $next($request);
        });
    }
    
    public function validateRights($userId, $controller){
       
        if(User::find($userId)->super)
            return true;

        // $controller = explode('.', Route::currentRouteName())[0];
        if(Auth::guard('web')->check()){
            if(!DB::table('access_rights')->where(['controller_right' => $controller, 'employee_id' => $userId])->first()){
                $anyRight = DB::table('access_rights')->where('employee_id', $userId)->first();
                if($anyRight){
                    if($anyRight->controller_right == "HomeController")
                        return ['redirection' => true, 'path' => '/admin/home'];
                    return ['redirection' => true, 'path' => '/'.$anyRight->controller_right];
                }
                
                abort(403, 'Unauthorized action.');
                die;
            }
        }else{
            return true;
        }
       
    }

    public function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
       
        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

}
