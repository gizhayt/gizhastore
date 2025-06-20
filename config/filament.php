<?php

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

return [

    /*
    |--------------------------------------------------------------------------
    | Panel Path
    |--------------------------------------------------------------------------
    |
    | This is the base path where Filament will be accessible from.
    |
    */

    'path' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be applied to every Filament request.
    |
    */

    'middleware' => [
        'auth' => [
            'web',
            'auth',   // Pastikan auth session aktif
            'admin',  // ✅ Middleware role admin (harus sudah didaftarkan di Kernel)
        ],
        'base' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    |
    | This configures how Filament handles authentication.
    |
    */

    'auth' => [
        'guard' => 'web', // Guard yang digunakan (admin via User model)
        'pages' => [
            'login' => \Filament\Pages\Auth\Login::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Branding
    |--------------------------------------------------------------------------
    |
    | Customize the appearance of the panel.
    |
    */

    'brand' => [
        'name' => env('APP_NAME', 'Filament Admin'),
        'logo' => null,
        'favicon' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Colors
    |--------------------------------------------------------------------------
    */

    'colors' => [
        'primary' => \Filament\Support\Colors\Color::Purple,
    ],

    /*
    |--------------------------------------------------------------------------
    | Resources / Pages / Widgets Auto-Discovery
    |--------------------------------------------------------------------------
    */

    'resources' => [
        'path' => app_path('Filament/Resources'),
        'namespace' => 'App\\Filament\\Resources',
    ],

    'pages' => [
        'path' => app_path('Filament/Pages'),
        'namespace' => 'App\\Filament\\Pages',
    ],

    'widgets' => [
        'path' => app_path('Filament/Widgets'),
        'namespace' => 'App\\Filament\\Widgets',
    ],

    /*
    |--------------------------------------------------------------------------
    | Other Settings
    |--------------------------------------------------------------------------
    */

    'database_notifications' => [
        'enabled' => true,
    ],

];
