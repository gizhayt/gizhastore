<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected function throwFailureValidationException(): never
    {
        // Tampilkan notifikasi error di halaman login
        Notification::make()
            ->title('Login Gagal!')
            ->body('Email atau password yang Anda masukkan salah.')
            ->danger()
            ->duration(4000)
            ->send();
            
        throw ValidationException::withMessages([
            'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
    
    public function authenticate(): ?\Filament\Http\Responses\Auth\Contracts\LoginResponse
    {
        try {
            // Tampilkan notifikasi sukses sebelum proses authentication
            $response = parent::authenticate();
            
            if ($response) {
                // Notifikasi sukses di halaman login
                Notification::make()
                    ->title('Login Berhasil!')
                    ->body('Selamat datang! Mengalihkan ke dashboard...')
                    ->success()
                    ->duration(2000)
                    ->send();
            }
            
            return $response;
            
        } catch (ValidationException $exception) {
            // Error sudah di-handle di throwFailureValidationException
            throw $exception;
        }
    }
}