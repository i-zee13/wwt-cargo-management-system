<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
            app()->setLocale(Session::get('locale'));
        } else {
            app()->setLocale(config('app.locale'));
        }

        return $next($request);
    } 
}
