<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class CustomMiddleware
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
        // if (FacadesAuth::user()->password_changed != 1) {
        //     return redirect('/reset-password-first');
        // } else {
        //     return $next($request);
        // }
        if (Auth::guard('web')->check()){
            $auth           =   GetActiveGuardDetail();
            if ($auth  && $auth->password_changed != 0) {
                return $next($request);
            } else {
                $message    = 'Please update your password first.';
                return redirect('/reset-password-first')->with(['message'=>$message]);
            }
        }else{
            return $next($request);
        }
    }
}
