<?php

namespace App\Filament\Resources\PaketRevisiResource\Pages;

use App\Filament\Resources\PaketRevisiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaketRevisi extends EditRecord
{
    protected static string $resource = PaketRevisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
