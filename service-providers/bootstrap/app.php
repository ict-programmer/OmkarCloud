<?php

use App\Exceptions\ApiException;
use App\Http\Exceptions\BadRequest;
use App\Http\Exceptions\Forbidden;
use App\Http\Exceptions\NotFound;
use App\Http\Middleware\ManualAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

if (!defined('START_EXECUTION_TIME')) {
    define('START_EXECUTION_TIME', microtime(true));
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'manual.auth' => ManualAuth::class,
        ]);
        
        // Add CSRF protection
        $middleware->web([
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);
        
        // Exclude API routes from CSRF protection
        $middleware->api([
            // No CSRF protection for API routes
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReport([
            BadRequest::class,
            Forbidden::class,
            NotFound::class,
            ApiException::class,
        ]);

        $exceptions->render(function (BadRequest $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'timestamp' => now()->format('Y-m-d, H:i:s'),
            ], Response::HTTP_BAD_REQUEST);
        });

        $exceptions->render(function (Forbidden $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'timestamp' => now()->format('Y-m-d, H:i:s'),
            ], Response::HTTP_FORBIDDEN);
        });

        $exceptions->render(function (NotFound $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'timestamp' => now()->format('Y-m-d, H:i:s'),
            ], Response::HTTP_NOT_FOUND);
        });

        $exceptions->render(function (ApiException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'details' => $e->details,
                'success' => false,
                'timestamp' => now()->format('Y-m-d, H:i:s'),
            ], $e->getStatusCode());
        });
    })->create();
