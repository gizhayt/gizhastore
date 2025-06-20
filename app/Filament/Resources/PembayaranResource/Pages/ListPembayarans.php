<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use Filament\Actions\CreateAction as ActionsCreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Actions\CreateAction;

class ListPembayarans extends ListRecords
{
    protected static string $resource = PembayaranResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         ActionsCreateAction::make(),
    //     ];
    // }
}
