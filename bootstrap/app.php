<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        App\Infrastructure\Providers\RepositoryServiceProvider::class,
        App\Presentation\Http\Middleware\RateLimiterServiceProvider::class,
        App\Presentation\Policies\PolicyProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'jwt.auth' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
            'jwt.refresh' => \Tymon\JWTAuth\Http\Middleware\RefreshToken::class,
            'jwt.cookie' => \App\Presentation\Http\Middleware\JwtCookieToAuthHeader::class,
        ]);

        $middleware->appendToGroup('api', \Illuminate\Routing\Middleware\ThrottleRequests::class.':api');

    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // ===== 401: no autenticado (Laravel) =====
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $req) {
            if ($req->is('api/*')) {
                return response()->json(['message' => 'No autenticado'], 401);
            }
        });

        // ===== 403: prohibido =====
        $exceptions->renderable(function (\Illuminate\Auth\Access\AuthorizationException $e, $req) {
            if ($req->is('api/*')) {
                return response()->json(['message' => 'Prohibido'], 403);
            }
        });

        // ===== 404: modelo no encontrado =====
        $exceptions->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $req) {
            if ($req->is('api/*')) {
                return response()->json(['message' => 'Recurso no encontrado'], 404);
            }
        });

        // ===== 405: método no permitido =====
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, $req) {
            if ($req->is('api/*')) {
                return response()->json(['message' => 'Método no permitido'], 405);
            }
        });

        // ===== 422: validación =====
        $exceptions->renderable(function (ValidationException $e, $req) {
            if ($req->is('api/*')) {
                return response()->json([
                    'message' => 'Validación fallida',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // ===== JWT: variantes de 401 =====
        $exceptions->renderable(function (UnauthorizedHttpException $e, $req) {
            if ($req->is('api/*')) {
                return response()->json(['message' => 'Token no provisto'], 401);
            }
        });

        $exceptions->renderable(function (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e, $req) {
            if ($req->is('api/*')) {
                return response()->json(['message' => 'Token expirado'], 401);
            }
        });

        $exceptions->renderable(function (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e, $req) {
            if ($req->is('api/*')) {
                return response()->json(['message' => 'Token inválido'], 401);
            }
        });

        $exceptions->renderable(function (\Tymon\JWTAuth\Exceptions\JWTException $e, $req) {
            if ($req->is('api/*')) {
                return response()->json(['message' => 'Token no presente'], 401);
            }
        });

        // ===== Catch-all 5xx para /api/* (sin trace) =====
        $exceptions->renderable(function (\Throwable $e, $req) {
            if ($req->is('api/*')) {
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                $msg = config('app.debug') ? $e->getMessage() : 'Error interno del servidor';

                return response()->json(['message' => $msg], $status);
            }
        });

        $exceptions->renderable(function (TooManyRequestsHttpException $e, $req) {
            if ($req->is('api/*')) {
                return response()->json([
                    'message' => 'Demasiadas solicitudes, intentá de nuevo más tarde.',
                    'retry_after' => $e->getHeaders()['Retry-After'] ?? null,
                ], 429);
            }
        });

    })
    ->create();
