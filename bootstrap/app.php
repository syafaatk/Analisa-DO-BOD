<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO
        );

        // Railway terminates TLS at the edge. Force generated Laravel/Vite
        // asset and route URLs to HTTPS whenever APP_URL is HTTPS.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        $middleware->alias([
            'lab.auth' => \App\Http\Middleware\LabAuth::class,
            'client.auth' => \App\Http\Middleware\ClientAuth::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'lab.context' => \App\Http\Middleware\LaboratoryContext::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
