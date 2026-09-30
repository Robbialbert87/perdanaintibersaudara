<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\TrackVisitor;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
            TrackVisitor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            $status = match (true) {
                $e instanceof AuthenticationException => 401,
                $e instanceof NotFoundHttpException => 404,
                $e instanceof AccessDeniedHttpException => 403,
                $e instanceof ValidationException => 422,
                $e instanceof HttpException => $e->getStatusCode(),
                default => 500,
            };

            if ($request->header('X-Inertia')) {
                return;
            }

            if ($request->expectsJson()) {
                $message = match ($status) {
                    401 => 'Sesi login sudah berakhir. Silakan login kembali.',
                    404 => 'Halaman tidak ditemukan.',
                    403 => 'Akses ditolak.',
                    422 => 'Validasi gagal. Periksa kembali data yang Anda isi.',
                    default => 'Terjadi kesalahan. Silakan coba lagi.',
                };

                $payload = ['error' => true, 'message' => $message];

                if ($e instanceof ValidationException) {
                    $payload['errors'] = $e->errors();
                }

                return response()->json($payload, $status);
            }

            if (view()->exists("errors.{$status}")) {
                return response()->view("errors.{$status}", status: $status);
            }
        });
    })->create();
