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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'perm' => \App\Http\Middleware\EnsurePermission::class,
            'customer.active' => \App\Http\Middleware\EnsureCustomerActive::class,
        ]);

        // Portal (customer) guests go to the client login; everyone else to the admin login.
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            return $request->is('portal/*') ? route('customer.login') : route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
