<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Limite de tentativas de login para evitar ataques de força bruta
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->input('email'))
                ->response(function ($request, array $headers) {
                    $retryAfter = $headers['Retry-After'] ?? 60;
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Você excedeu o número máximo de tentativas de login. Por favor, tente novamente mais tarde.',
                        'retry_after' => (int) $retryAfter,
                    ], 429);
                });
        });
    }
}
