<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middlewareGroups = $middleware->getMiddlewareGroups();
        $middleware->group('mobile', $middlewareGroups['web']);
        // $middleware->replaceInGroup('mobile', );

        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,

            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

            'quick_access' => \App\Http\Middleware\RedirectIfHasAccess::class,
        ]);

        // dd($middleware->getMiddlewareAliases());
    })
    ->withExceptions(function (Exceptions $exceptions) {
        \Sentry\Laravel\Integration::handles($exceptions);
    })->create();
