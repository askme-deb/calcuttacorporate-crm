<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Apply rate limiting for the API group
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);  // 60 requests per minute
        });
    }
}
