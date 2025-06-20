<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use Filament\Actions\DeleteAction as ActionsDeleteAction;
use Filament\Actions\ViewAction as ActionsViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord\Actions\ViewAction;

class EditPembayaran extends EditRecord
{
    protected static string $resource = PembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionsDeleteAction::make(),
            ActionsViewAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
