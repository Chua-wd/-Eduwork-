<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,               // admin + system admin
            'system_admin' => \App\Http\Middleware\SystemAdminMiddleware::class, // system admin only
        ]);

        // Already logged in and opening /login or /register: admins go to the dashboard,
        // users to the shop (Laravel's default is /dashboard, which users can't open: 403)
        $middleware->redirectUsersTo(fn (Request $request) => $request->user()?->isAdmin() ? route('dashboard') : url('/'));
    })
        ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
