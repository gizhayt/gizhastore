<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationGroup = 'Manajemen Client';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->email()
                ->unique(ignoreRecord: true)
                ->required(),

            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn ($livewire) => $livewire instanceof Pages\CreateClient)
                ->dehydrated(fn ($state) => filled($state))
                ->label(fn ($livewire) =>
                    $livewire instanceof Pages\EditClient
                        ? 'Password (kosongkan jika tidak ingin mengubah)'
                        : 'Password'
                ),

            Forms\Components\TextInput::make('user_id')
                ->label('User ID')
                ->disabled()
                ->dehydrated(false)
                ->visible(fn ($livewire) => $livewire instanceof Pages\EditClient),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('user_id')->label('User ID')->sortable()->searchable(),
                TextColumn::make('user.name')->label('User Name')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()->before(function (Client $record) {
                    if ($record->user_id) {
                        User::find($record->user_id)?->delete();
                    }
                }),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->before(function ($records) {
                    $userIds = $records->pluck('user_id')->filter()->toArray();
                    User::whereIn('id', $userIds)->delete();
                }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
