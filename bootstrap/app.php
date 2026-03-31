<?php

use App\Http\Middleware\CustomAuthenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'web' => \App\Http\Middleware\CheckIfLocked::class,
        ]);

        // Register middleware groups
        $middleware->group('api', [
            EnsureFrontendRequestsAreStateful::class,
            ThrottleRequests::class . ':60,1', // 60 requests per minute with a 1-minute decay
            SubstituteBindings::class,
           // CustomAuthenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle exceptions if needed
    })
    ->create();


// use Illuminate\Foundation\Application;
// use Illuminate\Foundation\Configuration\Exceptions;
// use Illuminate\Foundation\Configuration\Middleware;
// use Illuminate\Routing\Middleware\SubstituteBindings;
// use Illuminate\Routing\Middleware\ThrottleRequests;
// use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// return Application::configure(basePath: dirname(__DIR__))
//     ->withRouting(
//         web: __DIR__.'/../routes/web.php',
//         api: __DIR__.'/../routes/api.php',
//         commands: __DIR__.'/../routes/console.php',
//         health: '/up',
//     )
    
//     ->withMiddleware(function (Middleware $middleware) {
//         $middleware->alias([
//             'web' => \App\Http\Middleware\CheckIfLocked::class,
//         ]);
//         // Register middleware groups
//         $middleware->group('api', [
//             EnsureFrontendRequestsAreStateful::class,
//             ThrottleRequests::class . ':api',
//             SubstituteBindings::class,
//         ]);
        
//     })
    
//     ->withExceptions(function (Exceptions $exceptions) {
//         //
//     })->create();
