<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CustomMiddlewareForRoutes
{
    /**
     * Access rights are stored as "admin/home", "admin/packages", etc.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->ajax()) {
            return $next($request);
        }

        if (! Auth::guard('web')->check()) {
            return $next($request);
        }

        $auth = Auth::guard('web')->user();
        $slug = $this->resolveAccessSlug($request);

        if ($slug === '') {
            return Response::view('errors.404', [], 404);
        }

        $isRouteExist = DB::table('controllers')->where('controller', $slug)->exists();
        if (! $isRouteExist) {
            return Response::view('errors.404', [], 404);
        }

        if ((int) $auth->super === 1) {
            return $next($request);
        }

        $designationId = $auth->designation;
        if ($designationId === null || $designationId === '') {
            return Response::view('errors.404', [], 404);
        }

        $hasRight = DB::table('controller_designation_assignments')
            ->where('controller_name', $slug)
            ->where('designation_id', $designationId)
            ->exists();

        if ($hasRight) {
            return $next($request);
        }

        return Response::view('errors.404', [], 404);
    }

    private function resolveAccessSlug(Request $request): string
    {
        $segments = $request->segments();

        if (count($segments) === 0) {
            return '';
        }

        $adminIndex = array_search('admin', $segments, true);
        if ($adminIndex !== false && isset($segments[$adminIndex + 1])) {
            return 'admin/'.$segments[$adminIndex + 1];
        }

        if (count($segments) >= 2) {
            return $segments[0].'/'.$segments[1];
        }

        return $segments[0];
    }
}
