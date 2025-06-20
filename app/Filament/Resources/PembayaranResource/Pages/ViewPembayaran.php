<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use Filament\Actions\EditAction as ActionsEditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\ViewRecord\EditAction; 
class ViewPembayaran extends ViewRecord
{
    protected static string $resource = PembayaranResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         ActionsEditAction::make(), // ✅ Gunakan versi baru yang diimpor
    //     ];
    // }
}
