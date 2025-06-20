<?php

namespace App\Filament\Resources\PengajuanRevisiResource\Pages;

use App\Filament\Resources\PengajuanRevisiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanRevisi extends EditRecord
{
    protected static string $resource = PengajuanRevisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
