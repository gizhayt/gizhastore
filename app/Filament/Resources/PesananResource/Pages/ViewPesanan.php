<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Actions\Action as PageAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Storage;

class ViewPesanan extends ViewRecord
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            // Action untuk memproses pesanan dari pending ke diproses
            PageAction::make('proses_pesanan')
                ->label('Proses Pesanan')
                ->icon('heroicon-o-play')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Proses Pesanan')
                ->modalSubheading('Pesanan akan diubah statusnya menjadi diproses')
                ->action(function () {
                    $this->record->update([
                        'status' => 'diproses',
                    ]);

                    Notification::make()
                        ->title('Pesanan berhasil diproses')
                        ->success()
                        ->send();
                })
                ->visible(fn () => $this->record->status === 'pending'),

            // Action untuk upload hasil pesanan pertama kali
            PageAction::make('upload_hasil')
                ->label('Upload Hasil Pesanan')
                ->icon('heroicon-o-cloud-arrow-up')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Upload Hasil Pesanan')
                ->modalSubheading('Upload file hasil pesanan untuk pelanggan')
                ->form([
                    FileUpload::make('hasil_pesanan')
                        ->label('File Hasil')
                        ->directory('hasil-pesanan')
                        ->multiple()
                        ->preserveFilenames()
                        ->downloadable()
                        ->openable()
                        ->required()
                        ->acceptedFileTypes([
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'image/*',
                            'application/zip',
                            'text/*'
                        ]),

                    Textarea::make('keterangan_hasil')
                        ->label('Keterangan Hasil')
                        ->required()
                        ->placeholder('Berikan penjelasan mengenai hasil pesanan'),

                    Select::make('update_status')
                        ->label('Status Pesanan Setelah Upload')
                        ->options([
                            'diproses' => 'Tetap Diproses',
                            'selesai' => 'Selesai',
                        ])
                        ->default('selesai')
                        ->required()
                        ->helperText('Pilih "Selesai" jika tidak memerlukan revisi lagi'),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'hasil_pesanan' => json_encode($data['hasil_pesanan']),
                        'keterangan_hasil' => $data['keterangan_hasil'],
                        'status' => $data['update_status'],
                        'status_revisi' => $data['update_status'] === 'selesai' ? 'selesai' : 'belum_ada',
                    ]);

                    $message = $data['update_status'] === 'selesai' 
                        ? 'Hasil pesanan berhasil diupload dan pesanan selesai'
                        : 'Hasil pesanan berhasil diupload';

                    Notification::make()
                        ->title($message)
                        ->success()
                        ->send();
                })
                ->visible(fn () => 
                    $this->record->status === 'diproses' && 
                    empty($this->record->hasil_pesanan)
                ),

            // Action untuk menerima pengajuan revisi
            PageAction::make('terima_revisi')
                ->label('Terima Revisi')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Terima Pengajuan Revisi')
                ->modalSubheading('Anda akan menerima pengajuan revisi dari pelanggan')
                ->form([
                    Textarea::make('tanggapan_revisi')
                        ->label('Tanggapan Revisi')
                        ->required()
                        ->placeholder('Berikan tanggapan mengenai revisi yang akan dikerjakan'),
                ])
                ->action(function (array $data) {
                    $pengajuanRevisi = $this->record->pengajuanRevisi()->where('status', 'menunggu')->latest()->first();

                    if ($pengajuanRevisi) {
                        $pengajuanRevisi->update(['status' => 'diproses']);
                        
                        // Kurangi revisi tersisa saat menerima pengajuan
                        $revisiTersisaBaru = max(0, $this->record->revisi_tersisa - 1);
                        
                        $this->record->update([
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
                ->visible(fn () => 
                    $this->record->status_revisi === 'menunggu' && 
                    $this->record->revisi_tersisa > 0
                ),

            // Action untuk upload hasil revisi
            PageAction::make('upload_hasil_revisi')
                ->label('Upload Hasil Revisi')
                ->icon('heroicon-o-arrow-up-on-square')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Upload Hasil Revisi')
                ->modalSubheading('Upload file hasil revisi untuk pelanggan')
                ->form([
                    FileUpload::make('hasil_revisi')
                        ->label('File Hasil Revisi')
                        ->multiple()
                        ->required()
                        ->directory('hasil-revisi')
                        ->preserveFilenames()
                        ->downloadable()
                        ->openable()
                        ->acceptedFileTypes([
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'image/*',
                            'application/zip',
                            'text/*'
                        ]),
                    Textarea::make('deskripsi_hasil')
                        ->label('Deskripsi Hasil Revisi')
                        ->required()
                        ->placeholder('Jelaskan perubahan yang telah dilakukan'),
                ])
                ->action(function (array $data) {
                    $pengajuanRevisi = $this->record->pengajuanRevisi()->where('status', 'diproses')->latest()->first();

                    if ($pengajuanRevisi) {
                        // Update status pengajuan revisi menjadi selesai
                        $pengajuanRevisi->update(['status' => 'selesai']);

                        // Simpan hasil revisi (jika ada model HasilRevisi)
                        if (method_exists($pengajuanRevisi, 'hasilRevisi')) {
                            $pengajuanRevisi->hasilRevisi()->create([
                                'file_hasil' => json_encode($data['hasil_revisi']),
                                'deskripsi_hasil' => $data['deskripsi_hasil'],
                                'tanggal_revisi' => now(),
                            ]);
                        }
                        
                        // Update data pesanan
                        $updateData = [
                            'hasil_pesanan' => json_encode($data['hasil_revisi']),
                            'keterangan_hasil' => $data['deskripsi_hasil'],
                        ];
                        
                        // Tentukan status berdasarkan revisi tersisa
                        if ($this->record->revisi_tersisa == 0) {
                            // Jika tidak ada revisi tersisa, pesanan selesai
                            $updateData['status'] = 'selesai';
                            $updateData['status_revisi'] = 'selesai';
                            $statusMessage = 'Hasil revisi berhasil diunggah dan pesanan selesai';
                        } else {
                            // Jika masih ada revisi tersisa, reset status revisi untuk pengajuan berikutnya
                            $updateData['status'] = 'diproses';
                            $updateData['status_revisi'] = 'belum_ada';
                            $updateData['tanggapan_revisi'] = null; // Reset tanggapan
                            $statusMessage = 'Hasil revisi berhasil diunggah (tersisa ' . $this->record->revisi_tersisa . ' revisi)';
                        }
                        
                        $this->record->update($updateData);
                            
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
                ->visible(fn () =>
                    $this->record->status === 'diproses' &&
                    $this->record->status_revisi === 'diterima' &&
                    $this->record->pengajuanRevisi()->where('status', 'diproses')->exists()
                ),

            // Action untuk menolak pengajuan revisi
            PageAction::make('tolak_revisi')
                ->label('Tolak Revisi')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Tolak Pengajuan Revisi')
                ->modalSubheading('Anda akan menolak pengajuan revisi dari pelanggan')
                ->form([
                    Textarea::make('alasan_penolakan')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->placeholder('Berikan alasan mengapa revisi ditolak'),
                ])
                ->action(function (array $data) {
                    $pengajuanRevisi = $this->record->pengajuanRevisi()->where('status', 'menunggu')->latest()->first();
                    
                    if ($pengajuanRevisi) {
                        $pengajuanRevisi->update([
                            'status' => 'ditolak',
                            'alasan_penolakan' => $data['alasan_penolakan'] ?? null
                        ]);
                        
                        $this->record->update([
                            'status_revisi' => 'ditolak',
                            'tanggapan_revisi' => $data['alasan_penolakan'],
                        ]);

                        Notification::make()
                            ->title('Pengajuan revisi ditolak')
                            ->body('Alasan penolakan telah dikirim ke pelanggan')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Tidak ada pengajuan revisi yang menunggu')
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn () => $this->record->status_revisi === 'menunggu'),

            // Action untuk download file pesanan
            PageAction::make('download_file')
                ->label('Download File Bahan')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    if ($this->record->file_pesanan && Storage::exists($this->record->file_pesanan)) {
                        return Storage::download($this->record->file_pesanan);
                    } else {
                        Notification::make()
                            ->title('File tidak ditemukan')
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn () => !empty($this->record->file_pesanan)),

            // Action untuk download hasil pesanan
            PageAction::make('download_hasil')
                ->label('Download Hasil')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $hasilPesanan = $this->record->hasil_pesanan;
                    
                    if ($hasilPesanan) {
                        $files = is_string($hasilPesanan) ? json_decode($hasilPesanan, true) : $hasilPesanan;
                        
                        if (is_array($files) && count($files) > 0) {
                            // Jika hanya satu file, download langsung
                            if (count($files) == 1) {
                                $file = $files[0];
                                if (Storage::exists($file)) {
                                    return Storage::download($file);
                                }
                            }
                            
                            // Jika multiple files, buat zip (opsional - butuh implementasi tambahan)
                            Notification::make()
                                ->title('Multiple files detected')
                                ->body('Silakan download file satu per satu dari form edit')
                                ->info()
                                ->send();
                        }
                    } else {
                        Notification::make()
                            ->title('Tidak ada file hasil')
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn () => !empty($this->record->hasil_pesanan)),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Pesanan')
                    ->description('Detail lengkap informasi pesanan')
                    ->schema([
                        TextEntry::make('nomor_pesanan')
                            ->label('Nomor Pesanan')
                            ->weight(FontWeight::Bold)
                            ->size(TextEntry\TextEntrySize::Large)
                            ->copyable()
                            ->badge()
                            ->color('primary'),

                        TextEntry::make('user.name')
                            ->label('Pelanggan')
                            ->icon('heroicon-o-user'),
                            
                        TextEntry::make('layanan.nama')
                            ->label('Layanan')
                            ->icon('heroicon-o-cog-6-tooth'),
                            
                        TextEntry::make('paketRevisi.nama')
                            ->label('Paket Revisi')
                            ->placeholder('Tidak ada paket revisi')
                            ->icon('heroicon-o-arrow-path'),

                        TextEntry::make('revisi_tersisa')
                            ->label('Revisi Tersisa')
                            ->badge()
                            ->color(fn (int $state): string => match (true) {
                                $state > 2 => 'success',
                                $state > 0 => 'warning',
                                default => 'danger'
                            }),

                        TextEntry::make('batas_waktu')
                            ->label('Batas Waktu')
                            ->dateTime()
                            ->badge()
                            ->color(fn ($record) => $record->batas_waktu < now() ? 'danger' : 'success')
                            ->icon('heroicon-o-clock'),

                        TextEntry::make('harga')
                            ->label('Harga')
                            ->money('IDR')
                            ->icon('heroicon-o-currency-dollar'),
                            
                        TextEntry::make('total_harga')
                            ->label('Total Harga')
                            ->money('IDR')
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-currency-dollar'),

                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'diproses' => 'info',
                                'selesai' => 'success',
                                'revisi' => 'danger',
                                'dibatalkan' => 'gray',
                                default => 'gray',
                            })
                            ->icon(fn (string $state): string => match ($state) {
                                'pending' => 'heroicon-o-clock',
                                'diproses' => 'heroicon-o-cog-6-tooth',
                                'selesai' => 'heroicon-o-check-circle',
                                'revisi' => 'heroicon-o-arrow-path',
                                'dibatalkan' => 'heroicon-o-x-circle',
                                default => 'heroicon-o-question-mark-circle',
                            }),

                        TextEntry::make('created_at')
                            ->label('Tanggal Dibuat')
                            ->dateTime()
                            ->icon('heroicon-o-calendar'),
                            
                        TextEntry::make('updated_at')
                            ->label('Terakhir Diupdate')
                            ->dateTime()
                            ->since()
                            ->icon('heroicon-o-clock'),
                    ])
                    ->columns(2),

                Section::make('Detail Pesanan')
                    ->description('Persyaratan dan file pesanan dari pelanggan')
                    ->schema([
                        TextEntry::make('persyaratan')
                            ->label('Persyaratan')
                            ->columnSpanFull()
                            ->prose()
                            ->markdown()
                            ->placeholder('Tidak ada persyaratan khusus'),

                        TextEntry::make('file_pesanan')
                            ->label('File Pesanan')
                            ->formatStateUsing(fn ($state) => $state ? '📎 File tersedia' : 'Tidak ada file')
                            ->url(fn ($record) => $record->file_pesanan ? Storage::url($record->file_pesanan) : null)
                            ->openUrlInNewTab()
                            ->badge()
                            ->color(fn ($state) => $state ? 'success' : 'gray'),
                    ]),

                Section::make('Hasil Pesanan')
                    ->description('File hasil dan keterangan dari admin')
                    ->schema([
                        TextEntry::make('hasil_pesanan')
                            ->label('File Hasil')
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) return 'Belum ada hasil';
                                
                                $files = is_string($state) ? json_decode($state, true) : $state;
                                if (!is_array($files)) return 'Format file tidak valid';
                                
                                $count = count($files);
                                return "📁 {$count} File tersedia";
                            })
                            ->badge()
                            ->color(fn ($state) => empty($state) ? 'gray' : 'success'),

                        TextEntry::make('keterangan_hasil')
                            ->label('Keterangan Hasil')
                            ->prose()
                            ->markdown()
                            ->columnSpanFull()
                            ->placeholder('Belum ada keterangan hasil'),
                    ])
                    ->collapsible()
                    ->collapsed(fn ($record) => empty($record->hasil_pesanan)),

                Section::make('Informasi Revisi')
                    ->description('Status dan tanggapan revisi')
                    ->schema([
                        TextEntry::make('status_revisi')
                            ->label('Status Revisi')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'belum_ada' => 'gray',
                                'menunggu' => 'warning',
                                'diterima' => 'info',
                                'ditolak' => 'danger',
                                'selesai' => 'success',
                                default => 'gray',
                            })
                            ->icon(fn (string $state): string => match ($state) {
                                'belum_ada' => 'heroicon-o-minus',
                                'menunggu' => 'heroicon-o-clock',
                                'diterima' => 'heroicon-o-check',
                                'ditolak' => 'heroicon-o-x-mark',
                                'selesai' => 'heroicon-o-check-circle',
                                default => 'heroicon-o-question-mark-circle',
                            }),

                        TextEntry::make('tanggapan_revisi')
                            ->label('Tanggapan Revisi')
                            ->prose()
                            ->markdown()
                            ->columnSpanFull()
                            ->placeholder('Belum ada tanggapan revisi'),
                    ])
                    ->collapsible()
                    ->collapsed(fn ($record) => $record->status_revisi === 'belum_ada' || empty($record->status_revisi))
                    ->visible(fn ($record) => $record->status_revisi !== 'belum_ada' && !empty($record->status_revisi)),
            ]);
    }
}