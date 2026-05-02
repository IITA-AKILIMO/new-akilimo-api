<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configureRateLimiters();
    }

    private function configureRateLimiters(): void
    {
        // 5 attempts per minute per IP — redirects back with a field error on breach
        RateLimiter::for('admin-login', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip())
                ->response(fn () => back()
                    ->withErrors(['username' => 'Too many login attempts. Please wait a minute and try again.'])
                    ->onlyInput('username')
                );
        });
    }
}
