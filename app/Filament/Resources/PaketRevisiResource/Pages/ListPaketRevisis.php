<?php

namespace App\Filament\Resources\PaketRevisiResource\Pages;

use App\Filament\Resources\PaketRevisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaketRevisis extends ListRecords
{
    protected static string $resource = PaketRevisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
