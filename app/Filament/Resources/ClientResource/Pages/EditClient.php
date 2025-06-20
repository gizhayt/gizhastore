<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Models\User;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Jika password kosong, hapus dari data untuk menghindari override password
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Jika user_id ada dan password diubah, update juga password user
        if (!empty($data['password']) && $this->record->user_id) {
            DB::transaction(function () use ($data) {
                $user = User::find($this->record->user_id);
                if ($user) {
                    $user->update([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => bcrypt($data['password']),
                    ]);
                }
            });
        } 
        // Jika password tidak diubah tapi data lain diubah
        elseif ($this->record->user_id) {
            DB::transaction(function () use ($data) {
                $user = User::find($this->record->user_id);
                if ($user) {
                    $user->update([
                        'name' => $data['name'],
                        'email' => $data['email'],
                    ]);
                }
            });
        }

        return $data;
    }
}