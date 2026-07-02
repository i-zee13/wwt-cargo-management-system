<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CustomMiddleware;
use App\Http\Middleware\CustomMiddlewareForRoutes;
use App\Http\Middleware\LangSet;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RedirectIfNotAuthenticatedClients;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'auth' => Authenticate::class,
            'auth.clients' => RedirectIfNotAuthenticatedClients::class,
            'guest' => RedirectIfAuthenticated::class,
            'is_password_changed' => CustomMiddleware::class,
            'is_route_assigned' => CustomMiddlewareForRoutes::class,
            'lang_set' => LangSet::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
