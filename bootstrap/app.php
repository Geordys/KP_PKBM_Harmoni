<?php

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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.token' => \App\Http\Middleware\CheckAdminToken::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Allow API routes to read Laravel session (needed for session-based admin auth)
        $middleware->api(prepend: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/logout',
        ]);

        $middleware->redirectGuestsTo(fn ($request) => $request->is('admin') || $request->is('admin/*') ? route('admin.login') : route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
