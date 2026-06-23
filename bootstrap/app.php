<?php

use App\Http\Middleware\EnsureAdminAuthenticated;
use App\Http\Middleware\EnsureDoctorAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register route middleware aliases
        $middleware->alias([
            'auth.admin'  => EnsureAdminAuthenticated::class,
            'auth.doctor' => EnsureDoctorAuthenticated::class,
        ]);

        $middleware->redirectTo(
            guests: '/',
            users: function (Request $request) {
                // Determine redirect based on requested URL path
                if ($request->is('doctor') || $request->is('doctor/*')) {
                    if (Auth::guard('doctor')->check()) {
                        return '/doctor/dashboard';
                    }
                }

                if ($request->is('admin') || $request->is('admin/*')) {
                    if (Auth::guard('admin')->check()) {
                        return '/admin/dashboard';
                    }
                }

                // Default fallbacks
                if (Auth::guard('admin')->check()) {
                    return '/admin/dashboard';
                }
                if (Auth::guard('doctor')->check()) {
                    return '/doctor/dashboard';
                }
                return '/';
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
