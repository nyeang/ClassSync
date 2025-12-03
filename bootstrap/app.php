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
    ->withMiddleware(function (Middleware $middleware) {
        // Register BOTH middlewares
        $middleware->alias([
        'teacher' => \App\Http\Middleware\EnsureUserIsTeacher::class,
        'student' => \App\Http\Middleware\EnsureUserIsStudent::class,
        'role'    => \App\Http\Middleware\CheckRole::class, // ← added
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
