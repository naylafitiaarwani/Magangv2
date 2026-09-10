<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // =========================================================
        // SESSION / CSRF EXPIRED
        // =========================================================
        $exceptions->render(function (TokenMismatchException $e, Request $request) {

            // Khusus halaman CMS / admin
            if ($request->is('admin/*')) {
                return redirect('/login')
                    ->with(
                        'error',
                        'Sesi login telah berakhir. Silakan login kembali.'
                    );
            }

            // Untuk halaman lain tetap tampilkan 419
            return response('Page Expired', 419);
        });


        // =========================================================
        // NOT FOUND
        // =========================================================
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {

            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 404);
            }

        });


        // =========================================================
        // METHOD NOT ALLOWED
        // =========================================================
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {

            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 404);
            }

        });

    })->create();
