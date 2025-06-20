<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaketRevisiResource\Pages;
use App\Models\PaketRevisi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaketRevisiResource extends Resource
{
    protected static ?string $model = PaketRevisi::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationGroup = 'Manajemen Revisi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('deskripsi')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                Forms\Components\TextInput::make('jumlah_revisi')
                    ->required()
                    ->numeric()
                    ->minValue(1),

                Forms\Components\Toggle::make('aktif') // ✅ disesuaikan
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah_revisi')
                    ->sortable(),

                Tables\Columns\IconColumn::make('aktif') // ✅ disesuaikan
                    ->label('Status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('aktif') // ✅ disesuaikan
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaketRevisis::route('/'),
            'create' => Pages\CreatePaketRevisi::route('/create'),
            'edit' => Pages\EditPaketRevisi::route('/{record}/edit'),
        ];
    }    
}
