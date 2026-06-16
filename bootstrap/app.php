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
        $middleware->validateCsrfTokens(except: [
            'mercadopago/webhook',
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\XssSanitizer::class,
        ]);

        $middleware->api(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\XssSanitizer::class,
        ]);

        $middleware->throttleApi('anti-ddos');
        // Apply rate limit globally to web group as well if needed
        // But usually throttle is for API, or applied to specific routes.
        // For Laravel 11+, we can append throttle to web group:
        $middleware->web(append: [
            'throttle:anti-ddos',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
