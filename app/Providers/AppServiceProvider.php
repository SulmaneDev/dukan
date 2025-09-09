<?php

namespace App\Providers;

use App\Http\Middleware\Auth;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('auth.middleware', function ($app) {
            return new Auth($app['auth']);
        });
        $this->app->extend(\Illuminate\Auth\Middleware\Authenticate::class, function ($middleware, $app) {
            return new \App\Http\Middleware\Auth($app['auth']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('scan', function ($request) {
            return Limit::perMinutes(1, 6) 
                ->by($request->user()?->id ?: $request->ip());

            return Limit::perSecond(10, 1)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Too many scan attempts. Please wait 10 seconds.'
                    ], 429);
                });
        });
    }
}
