<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Response;

class CustomMiddlewareForRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {  
        if (!$request->ajax()) {
            if(Auth::guard('web')->check()){
            $auth       =   Auth::guard('web')->user();
            $where      =   "designation_id = $auth->designation"; 
            $slug       =   $request->getRequestUri(); 
 
            if ($slug ) {
                $slug   =   explode('/', $slug) ? explode('/', $slug)[1].'/'.explode('/', $slug)[2] : $request->getRequestUri();
            }  
            
            if ($slug) {
                
                $isRouteExist = DB::table('controllers')->where('controller', $slug)->exists();
                if (!$isRouteExist) {
                    return Response::view('errors.404', [], 404);
                }
                if ($auth->super == 0  ) {
                    $checkRoute =   [];
                    $checkRoute     =   DB::SELECT("
                                            SELECT
                                                controller_designation_assignments.*
                                            FROM
                                            controller_designation_assignments 
                                            WHERE
                                                controller_name = '$slug'
                                            AND
                                            $where 
                                        ");

                    if (collect($checkRoute)->count() > 0) {

                        return $next($request);
                    } else {
                        return Response::view('errors.404', [], 404);
                    }
                } else {
                    return $next($request);
                }
            } else {
                return Response::view('errors.404', [], 404);
            }
        } else { 
            return $next($request);
        }
    }else{
       
        return $next($request);
    }
    }
}
