<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Path to redirect users after login.
     *
     * You can override this in RedirectIfAuthenticated.php
     */
    public const HOME = '/home'; // Ubah ke /admin jika mau default ke filament

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // Web Routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // API Routes
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }
}
