<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiters();
        Gate::define('viewApiDocs', fn (?User $user) => true);
//        Gate::define('viewApiDocs', function (User $user) {
//            return $user->email == 'admin@app.com';
//        });
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
