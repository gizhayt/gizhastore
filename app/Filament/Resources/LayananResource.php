<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LayananResource\Pages;
use App\Models\Layanan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class LayananResource extends Resource
{
    protected static ?string $model = Layanan::class;

    protected static ?string $navigationGroup = 'Manajemen Pesanan';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Layanan';
    protected static ?string $pluralLabel = 'Layanan';
    protected static ?string $slug = 'layanan';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Layanan')
                    ->required()
                    ->maxLength(255),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->nullable(),

                FileUpload::make('gambar')
                    ->label('Gambar')
                    ->image()
                    ->directory('layanan')
                    ->visibility('public') // Mengatur visibility menjadi public
                    ->nullable(),

                Select::make('paket_revisi_id')
                    ->label('Paket Revisi')
                    ->relationship('paketRevisi', 'nama')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                TextInput::make('harga')
                    ->label('Harga')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Layanan')
                    ->sortable()
                    ->searchable(),

                ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->disk('public') // Tentukan disk penyimpanan
                    ->visibility('public'), // Tentukan visibility

                TextColumn::make('paketRevisi.nama')
                    ->label('Paket Revisi')
                    ->sortable()
                    ->searchable()
                    ->default('Tidak ada'),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('paketRevisi');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLayanans::route('/'),
            'create' => Pages\CreateLayanan::route('/create'),
            'edit' => Pages\EditLayanan::route('/{record}/edit'),
        ];
    }
}