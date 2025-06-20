<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\{
    TextInput, DateTimePicker, Select, Section
};
use Filament\Tables\Columns\{
    TextColumn, BadgeColumn
};
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Manajemen Pesanan';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $pluralLabel = 'Pembayaran';
    protected static ?string $recordTitleAttribute = 'kode_pembayaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('pesanan_id')
                            ->relationship('pesanan', 'nomor_pesanan')
                            ->label('Nomor Pesanan')
                            ->searchable()
                            ->required()
                            ->preload(),

                        TextInput::make('jumlah')
                            ->label('Jumlah Pembayaran')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Select::make('status_pembayaran')
                            ->label('Status Pembayaran')
                            ->options([
                                Pembayaran::STATUS_PENDING => 'Pending',
                                Pembayaran::STATUS_BERHASIL => 'Berhasil',
                                Pembayaran::STATUS_EXPIRED => 'Expired',
                                Pembayaran::STATUS_DIBATALKAN => 'Dibatalkan',
                                Pembayaran::STATUS_DITOLAK => 'Ditolak',
                                Pembayaran::STATUS_CHALLENGE => 'Challenge',
                            ])
                            ->required(),

                        Select::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->options([
                                'bank_transfer' => 'Transfer Bank',
                                'virtual_account' => 'Virtual Account',
                                'credit_card' => 'Kartu Kredit',
                                'e_wallet' => 'E-Wallet',
                                'manual' => 'Manual'
                            ])
                            ->required(),

                        DateTimePicker::make('tanggal_pembayaran')
                            ->label('Tanggal Pembayaran')
                            ->nullable(),

                        TextInput::make('kode_pembayaran')
                            ->label('Kode Pembayaran')
                            ->maxLength(255)
                            ->required(),

                        TextInput::make('snap_token')
                            ->label('Snap Token')
                            ->maxLength(255)
                            ->nullable(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pesanan.nomor_pesanan')
                    ->label('Nomor Pesanan')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => PesananResource::getUrl('view', ['record' => $record->pesanan_id])),

                TextColumn::make('pesanan.user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kode_pembayaran')
                    ->label('Kode Pembayaran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status_pembayaran')
                    ->label('Status')
                    ->colors([
                        'warning' => Pembayaran::STATUS_PENDING,
                        'success' => Pembayaran::STATUS_BERHASIL,
                        'danger' => Pembayaran::STATUS_EXPIRED,
                        'gray' => Pembayaran::STATUS_DIBATALKAN,
                    ])
                    ->sortable(),

                TextColumn::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'bank_transfer' => 'Transfer Bank',
                        'virtual_account' => 'Virtual Account',
                        'credit_card' => 'Kartu Kredit',
                        'e_wallet' => 'E-Wallet',
                        'manual' => 'Manual',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('tanggal_pembayaran')
                    ->label('Tanggal Pembayaran')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_pembayaran')
                    ->options([
                        Pembayaran::STATUS_PENDING => 'Pending',
                        Pembayaran::STATUS_BERHASIL => 'Berhasil',
                        Pembayaran::STATUS_EXPIRED => 'Expired',
                        Pembayaran::STATUS_DIBATALKAN => 'Dibatalkan',
                        Pembayaran::STATUS_DITOLAK => 'Ditolak',
                        Pembayaran::STATUS_CHALLENGE => 'Challenge',
                    ]),

                SelectFilter::make('metode_pembayaran')
                    ->options([
                        'bank_transfer' => 'Transfer Bank',
                        'virtual_account' => 'Virtual Account',
                        'credit_card' => 'Kartu Kredit',
                        'e_wallet' => 'E-Wallet',
                        'manual' => 'Manual'
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Action::make('update_status')
                    ->label('Update Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->form([
                        Select::make('status_pembayaran')
                            ->label('Status Pembayaran')
                            ->options([
                                Pembayaran::STATUS_PENDING => 'Pending',
                                Pembayaran::STATUS_BERHASIL => 'Berhasil',
                                Pembayaran::STATUS_EXPIRED => 'Expired',
                                Pembayaran::STATUS_DIBATALKAN => 'Dibatalkan',
                                Pembayaran::STATUS_DITOLAK => 'Ditolak',
                                Pembayaran::STATUS_CHALLENGE => 'Challenge',
                            ])
                            ->required(),
                        
                        DateTimePicker::make('tanggal_pembayaran')
                            ->label('Tanggal Pembayaran')
                            ->visible(fn (callable $get) => $get('status_pembayaran') === Pembayaran::STATUS_BERHASIL)
                            ->required(fn (callable $get) => $get('status_pembayaran') === Pembayaran::STATUS_BERHASIL),
                    ])
                    ->action(function (Pembayaran $record, array $data) {
                        $record->update([
                            'status_pembayaran' => $data['status_pembayaran'],
                            'tanggal_pembayaran' => $data['tanggal_pembayaran'] ?? $record->tanggal_pembayaran,
                        ]);
                        
                        // Update pesanan status if payment is successful
                        if ($data['status_pembayaran'] === Pembayaran::STATUS_BERHASIL && $record->pesanan) {
                            $record->pesanan->update([
                                'status' => 'diproses',
                            ]);
                        }
                        
                        Notification::make()
                            ->title('Status pembayaran berhasil diperbarui')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'view' => Pages\ViewPembayaran::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['pesanan', 'pesanan.user']);
    }
}