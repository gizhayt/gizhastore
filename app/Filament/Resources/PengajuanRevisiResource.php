<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanRevisiResource\Pages;
use App\Models\PengajuanRevisi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class PengajuanRevisiResource extends Resource
{
    protected static ?string $model = PengajuanRevisi::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Manajemen Revisi';
    protected static ?string $label = 'Pengajuan Revisi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('pesanan_id')
                    ->relationship('pesanan', 'nomor_pesanan')
                    ->required()
                    ->label('Nomor Pesanan'),

                Textarea::make('deskripsi')
                    ->required()
                    ->label('Deskripsi Revisi'),

                FileUpload::make('file_pendukung')  // Changed from 'file_revisi' to 'file_pendukung' to match the database column
                    ->multiple()
                    ->directory('revisi')
                    ->label('File Revisi'),

                Select::make('status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'ditolak' => 'Ditolak',
                        'diterima' => 'Diterima',
                        'selesai' => 'Selesai',  // Added 'selesai' to match the migration
                    ])
                    ->required()
                    ->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pesanan.nomor_pesanan')
                    ->label('Nomor Pesanan')
                    ->searchable(),

                TextColumn::make('deskripsi')
                    ->limit(40)
                    ->label('Deskripsi'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu' => 'gray',
                        'ditolak' => 'danger',
                        'diterima' => 'success',
                        'selesai' => 'primary',  // Added color for 'selesai' status
                        default => 'secondary',
                    })
                    ->label('Status'),
                
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'ditolak' => 'Ditolak',
                        'diterima' => 'Diterima',
                        'selesai' => 'Selesai',  // Added 'selesai' to match the migration
                    ])
                    ->label('Filter Status'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengajuanRevisis::route('/'),
            'create' => Pages\CreatePengajuanRevisi::route('/create'),
            'edit' => Pages\EditPengajuanRevisi::route('/{record}/edit'),
        ];
    }
}