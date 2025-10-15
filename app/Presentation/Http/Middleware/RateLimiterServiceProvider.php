<?php

namespace App\Presentation\Http\Middleware;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimiterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('api', fn (Request $request): array => [
            Limit::perMinute(60)->by(
                optional($request->user())->id ?: $request->ip()
            ),
        ]);
        RateLimiter::for('login', fn (Request $request): array => [
            Limit::perMinute(10)->by($request->ip()),
        ]);
    }
}
