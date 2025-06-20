<?php

namespace App\Filament\Resources\PengajuanRevisiResource\Pages;

use App\Filament\Resources\PengajuanRevisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanRevisis extends ListRecords
{
    protected static string $resource = PengajuanRevisiResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
