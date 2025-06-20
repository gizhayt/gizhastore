<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $hashedPassword = Hash::make($data['password']);

            Log::info('Creating client with email: ' . $data['email']);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $hashedPassword,
                'role' => 'client',
            ]);

            Log::info('User created with ID: ' . $user->id);

            $client = static::getModel()::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $hashedPassword,
                'user_id' => $user->id,
            ]);

            Log::info('Client created with ID: ' . $client->id);

            return $client;
        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
