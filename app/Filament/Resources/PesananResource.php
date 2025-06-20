<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesananResource\Pages;
use App\Filament\Resources\PesananResource\RelationManagers\PengajuanRevisiRelationManager;
use App\Models\Pesanan;
use App\Models\Layanan;
use App\Models\PaketRevisi;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\{
    TextInput, Textarea, Select, DatePicker, FileUpload, Section
};
use Filament\Tables\Columns\{
    TextColumn, IconColumn
};
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\{Action, ViewAction, EditAction, DeleteAction};
use Filament\Notifications\Notification;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Manajemen Pesanan';
    protected static ?string $navigationLabel = 'Pesanan';
    protected static ?string $pluralLabel = 'Pesanan';
    protected static ?string $recordTitleAttribute = 'nomor_pesanan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                TextInput::make('nomor_pesanan')
                    ->label('Nomor Pesanan')
                    ->required()
                    ->default(fn () => 'PSN-' . strtoupper(uniqid()))
                    ->disabled(fn (?Pesanan $record) => $record !== null),

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->required(),

                Select::make('layanan_id')
                    ->relationship('layanan', 'nama')
                    ->label('Layanan')
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($layanan = Layanan::find($state)) {
                            $set('harga', $layanan->harga);
                            $set('total_harga', $layanan->harga);
                        }
                    }),

                Select::make('paket_revisi_id')
                    ->relationship('paketRevisi', 'nama')
                    ->label('Paket Revisi')
                    ->nullable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state && $paketRevisi = PaketRevisi::find($state)) {
                            $set('revisi_tersisa', $paketRevisi->jumlah_revisi);
                        } else {
                            $set('revisi_tersisa', 0);
                        }
                    }),

                TextInput::make('revisi_tersisa')
                    ->label('Revisi Tersisa')
                    ->numeric()
                    ->disabled(),

                Textarea::make('persyaratan')
                    ->label('Persyaratan')
                    ->required()
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'revisi' => 'Revisi',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->required(),

                TextInput::make('harga')->label('Harga')->numeric()->prefix('Rp')->required(),
                TextInput::make('total_harga')->label('Total Harga')->numeric()->prefix('Rp')->required(),

                DatePicker::make('batas_waktu')
                    ->label('Batas Waktu')
                    ->required()
                    ->minDate(now()->addDay()),

                FileUpload::make('file_pesanan')
                    ->label('File Pesanan')
                    ->directory('pesanan')
                    ->preserveFilenames()
                    ->downloadable()
                    ->openable()
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/*',
                        'application/zip'
                    ]),
            ])->columns(2),

            Section::make()->schema([
                FileUpload::make('hasil_pesanan')
                    ->label('Hasil Pesanan')
                    ->directory('hasil-pesanan')
                    ->multiple()
                    ->downloadable()
                    ->openable()
                    ->preserveFilenames(),

                Textarea::make('keterangan_hasil')
                    ->label('Keterangan Hasil')
                    ->columnSpanFull(),

                Select::make('status_revisi')
                    ->label('Status Revisi')
                    ->options([
                        'belum_ada' => 'Belum Ada Pengajuan',
                        'menunggu' => 'Menunggu Respons',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ]),

                Textarea::make('tanggapan_revisi')
                    ->label('Tanggapan Revisi')
                    ->columnSpanFull()
                    ->placeholder('Tanggapan atas pengajuan revisi dari client'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_pesanan')->searchable()->sortable(),
                TextColumn::make('user.name')->label('Pelanggan')->sortable()->searchable(),
                TextColumn::make('layanan.nama')->label('Layanan')->searchable()->sortable(),
                TextColumn::make('paketRevisi.nama')->label('Paket Revisi')->sortable(),
                TextColumn::make('revisi_tersisa')->label('Revisi Tersisa')->sortable(),
                TextColumn::make('status')->label('Status')->sortable(),
                IconColumn::make('hasil_pesanan')->label('Hasil')->boolean(),
                TextColumn::make('status_revisi')->label('Status Revisi')->sortable(),
                TextColumn::make('total_harga')->label('Total Harga')->money('IDR')->sortable(),
                TextColumn::make('batas_waktu')->label('Batas Waktu')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                    'revisi' => 'Revisi',
                    'dibatalkan' => 'Dibatalkan',
                ]),
                SelectFilter::make('status_revisi')->options([
                    'belum_ada' => 'Belum Ada Pengajuan',
                    'menunggu' => 'Menunggu Respons',
                    'diterima' => 'Diterima',
                    'ditolak' => 'Ditolak',
                    'selesai' => 'Selesai',
                ]),
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Pelanggan')
                    ->searchable(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),

                // PERBAIKAN: Action untuk menerima pengajuan revisi
                Action::make('terima_pengajuan_revisi')
                    ->label('Terima Revisi')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->form([
                        Textarea::make('tanggapan_revisi')->label('Tanggapan')->required(),
                    ])
                    ->action(function (Pesanan $record, array $data) {
                        $pengajuanRevisi = $record->pengajuanRevisi()->where('status', 'menunggu')->latest()->first();

                        if ($pengajuanRevisi) {
                            $pengajuanRevisi->update(['status' => 'diproses']);
                            
                            // PERBAIKAN: Kurangi revisi_tersisa saat menerima pengajuan revisi
                            $revisiTersisaBaru = max(0, $record->revisi_tersisa - 1);
                            
                            $record->update([
                                'status_revisi' => 'diterima',
                                'tanggapan_revisi' => $data['tanggapan_revisi'],
                                'status' => 'diproses',
                                'revisi_tersisa' => $revisiTersisaBaru,
                            ]);

                            Notification::make()
                                ->title('Pengajuan revisi diterima')
                                ->body('Revisi tersisa: ' . $revisiTersisaBaru)
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Tidak ada pengajuan revisi yang menunggu')
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (Pesanan $record) =>
                        $record->status_revisi === 'menunggu' && $record->revisi_tersisa > 0
                    ),

                // PERBAIKAN: Action untuk upload hasil revisi
                Action::make('upload_hasil_revisi')
                    ->label('Upload Hasil Revisi')
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Upload Hasil Revisi')
                    ->modalSubheading('Silakan upload file hasil revisi')
                    ->form([
                        Textarea::make('deskripsi_hasil')
                            ->label('Deskripsi Hasil')
                            ->required(),
                        FileUpload::make('hasil_revisi')
                            ->label('File Hasil Revisi')
                            ->required()
                            ->multiple()
                            ->directory('hasil-revisi')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable(),
                    ])
                    ->action(function (Pesanan $record, array $data) {
                        $pengajuanRevisi = $record->pengajuanRevisi()->where('status', 'diproses')->latest()->first();

                        if ($pengajuanRevisi) {
                            // Update status pengajuan revisi menjadi selesai
                            $pengajuanRevisi->update(['status' => 'selesai']);

                            // Simpan hasil revisi
                            $pengajuanRevisi->hasilRevisi()->create([
                                'file_hasil' => json_encode($data['hasil_revisi']),
                                'deskripsi_hasil' => $data['deskripsi_hasil'],
                                'tanggal_revisi' => now(),
                            ]);
                            
                            // Tentukan status berdasarkan revisi tersisa
                            $updateData = [
                                'hasil_pesanan' => json_encode($data['hasil_revisi']),
                                'keterangan_hasil' => $data['deskripsi_hasil'],
                            ];
                            
                            if ($record->revisi_tersisa == 0) {
                                // Jika tidak ada revisi tersisa, pesanan selesai
                                $updateData['status'] = 'selesai';
                                $updateData['status_revisi'] = 'selesai';
                                $statusMessage = 'Hasil revisi berhasil diunggah dan pesanan selesai';
                            } else {
                                // Jika masih ada revisi tersisa, reset status revisi untuk pengajuan berikutnya
                                $updateData['status'] = 'diproses';
                                $updateData['status_revisi'] = 'belum_ada';
                                $updateData['tanggapan_revisi'] = null; // Reset tanggapan
                                $statusMessage = 'Hasil revisi berhasil diunggah (tersisa ' . $record->revisi_tersisa . ' revisi)';
                            }
                            
                            $record->update($updateData);
                                
                            Notification::make()
                                ->title('Upload Berhasil')
                                ->body($statusMessage)
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Error')
                                ->body('Tidak ada pengajuan revisi yang sedang diproses')
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (Pesanan $record) =>
                        $record->status === 'diproses' &&
                        $record->status_revisi === 'diterima' &&
                        $record->pengajuanRevisi()->where('status', 'diproses')->exists()
                    ),

                // Action untuk menolak pengajuan revisi
                Action::make('tolak_pengajuan_revisi')
                    ->label('Tolak Revisi')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->form([
                        Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (Pesanan $record, array $data) {
                        $pengajuanRevisi = $record->pengajuanRevisi()->where('status', 'menunggu')->latest()->first();

                        if ($pengajuanRevisi) {
                            $pengajuanRevisi->update([
                                'status' => 'ditolak',
                                'alasan_penolakan' => $data['alasan_penolakan']
                            ]);
                            
                            $record->update([
                                'status_revisi' => 'ditolak',
                                'tanggapan_revisi' => $data['alasan_penolakan'],
                            ]);

                            Notification::make()
                                ->title('Pengajuan revisi ditolak')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Tidak ada pengajuan revisi yang menunggu')
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (Pesanan $record) =>
                        $record->status_revisi === 'menunggu'
                    ),

                DeleteAction::make()
                    ->before(function (Pesanan $record) {
                        // Delete associated files before deleting the record
                        if ($record->file_pesanan) {
                            Storage::delete($record->file_pesanan);
                        }
                        
                        if ($record->hasil_pesanan) {
                            $hasilFiles = is_string($record->hasil_pesanan) 
                                ? json_decode($record->hasil_pesanan, true) 
                                : $record->hasil_pesanan;
                            
                            if (is_array($hasilFiles)) {
                                foreach ($hasilFiles as $file) {
                                    Storage::delete($file);
                                }
                            }
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Pesanan berhasil dihapus')
                            ->body('Data pesanan dan file terkait telah dihapus.')
                    ),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesanans::route('/'),
            'create' => Pages\CreatePesanan::route('/create'),
            'edit' => Pages\EditPesanan::route('/{record}/edit'),
            'view' => Pages\ViewPesanan::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'layanan', 'paketRevisi', 'pembayaran']);
    }
}