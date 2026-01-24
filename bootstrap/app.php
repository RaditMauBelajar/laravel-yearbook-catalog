<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\BookAccessMiddleware;
use App\Http\Middleware\AdminOnly;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    // =========================
    // MIDDLEWARE REGISTRATION
    // =========================
    ->withMiddleware(function (Middleware $middleware) {

        // Alias middleware (pengganti Kernel.php)
        $middleware->alias([
            // middleware lama (JANGAN DIHAPUS)
            'book.access' => BookAccessMiddleware::class,

            // middleware baru untuk admin
            'admin'       => AdminOnly::class,
        ]);

    })

    // =========================
    // EXCEPTION HANDLER
    // =========================
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

    ->create();
