<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin', // Pastikan kolom 'role' ada di tabel users
        ]);

        // Client User
        User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'client',
        ]);
    }
}
