<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Client\ChatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\WelcomeController;




Route::get('/', [WelcomeController::class, 'index'])->name('welcome');



// Manual logout route
Route::post('/admin/logout', function () {
    Auth::logout();
    Session::flush();
    Session::regenerate();
    return redirect('/admin/login');
})->name('filament.admin.auth.logout');

// Tambahkan juga GET route untuk berjaga-jaga
Route::get('/admin/logout', function () {
    Auth::logout();
    Session::flush();
    Session::regenerate();
    return redirect('/admin/login');
});

Route::get('/list-routes', function () {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'methods' => $route->methods(),
            'action' => $route->getActionName(),
        ];
    });
    
    return $routes->filter(function ($route) {
        return str_contains($route['uri'], 'admin') || 
               str_contains($route['name'] ?? '', 'admin') ||
               str_contains($route['uri'], 'logout') || 
               str_contains($route['name'] ?? '', 'logout');
    });
});

// 📦 Client Auth & Dashboard
Route::prefix('client')->name('client.')->group(function () {

    // 🔐 Auth routes (guest only)
    Route::middleware('guest:client')->group(function () {
        Route::get('/login', [ClientAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [ClientAuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [ClientAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [ClientAuthController::class, 'register'])->name('register.submit');

        // 🔁 Forgot & Reset Password
        Route::get('/forgot-password', [ClientAuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [ClientAuthController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password/{token}', [ClientAuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [ClientAuthController::class, 'resetPassword'])->name('password.update');
    });

    // 🏠 Authenticated client area
    Route::middleware('auth:client')->group(function () {

        // 📊 Dashboard
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

        // 🚪 Logout
        Route::post('/logout', [ClientAuthController::class, 'logout'])->name('logout');

        // 📄 Layanan
        Route::get('/layanan', [ClientController::class, 'layanan'])->name('layanan');

        // 📦 CRUD Pesanan
        Route::resource('pesanan', PesananController::class);

        // Routes for client pesanan revision
        Route::get('/pesanan/{pesanan}/revisi-form', [PesananController::class, 'showRevisionForm'])
            ->name('pesanan.revision-form');

        // Fix: Changed route name to match what's used in the controller
        Route::post('/pesanan/{pesanan}/submit-revisi', [PesananController::class, 'submitRevision'])
            ->name('pesanan.submit-revision');  // This was previously causing the error

        // 📁 Download File Pesanan Asli
        Route::get('/pesanan/{pesanan}/download', [PesananController::class, 'download'])
            ->name('pesanan.download');

        // 📁 Download Hasil Pesanan
        Route::get('/pesanan/{pesanan}/download-hasil', [PesananController::class, 'downloadHasil'])
            ->name('pesanan.downloadHasil');

        // Rute untuk menyelesaikan pesanan
        Route::post('/pesanan/{pesanan}/complete', [PesananController::class, 'completeOrder'])
            ->name('pesanan.complete');

        // 💰 Proses Pembayaran
        Route::get('/pesanan/{pesanan}/payment/process', [PesananController::class, 'processPayment'])
            ->name('pesanan.processPayment');

        // 💳 Selesai Pembayaran
        Route::get('/pesanan/{pesanan}/payment/finish', [PesananController::class, 'handlePaymentFinish'])
            ->name('pesanan.payment.finish');

        // 🔄 Cek Status Pembayaran
        Route::get('/pesanan/{pesanan}/check-payment', [PesananController::class, 'checkPaymentStatus'])
            ->name('pesanan.checkPaymentStatus');

        // 📬 Webhook Pembayaran (Midtrans)
        Route::post('/payment/notification', [PesananController::class, 'handlePaymentNotification']);

        // Fix: Changed route definition to use the correct controller format
        Route::get('/pesanan/revisi/{pengajuanRevisi}/download', [PesananController::class, 'downloadHasilRevisi'])
            ->name('pesanan.downloadHasilRevisi');

        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
        Route::post('/chat/send-message', [ChatController::class, 'sendMessage'])->name('chat.send-message');
        Route::post('/chat/get-new-messages', [ChatController::class, 'getNewMessages'])->name('chat.get-new-messages');
    });
});