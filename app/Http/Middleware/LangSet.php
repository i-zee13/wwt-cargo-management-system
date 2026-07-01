<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Response;
use Session;

class LangSet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {   if ($request->is('client/login')) {
        return redirect('/');
    }
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        return $next($request);
    } 
}
