<?php

namespace App\Filament\Resources\PesananResource\RelationManagers;

use App\Models\PengajuanRevisi;
use App\Models\Pesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\{
    FileUpload, Textarea, Select, Hidden
};
use Filament\Tables\Columns\{
    TextColumn, IconColumn
};
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class PengajuanRevisiRelationManager extends RelationManager
{
    protected static string $relationship = 'pengajuanRevisi';
    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $title = 'Pengajuan Revisi';
    protected static ?string $modelLabel = 'Pengajuan Revisi';
    protected static ?string $pluralModelLabel = 'Pengajuan Revisi';

    public function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('pesanan_id')
                ->default(fn ($livewire) => $livewire->ownerRecord->id),

            Hidden::make('user_id')
                ->default(fn () => Auth::id()),

            Textarea::make('deskripsi')
                ->label('Deskripsi Revisi')
                ->required()
                ->columnSpanFull(),

            FileUpload::make('file_pendukung')
                ->label('File Pendukung')
                ->directory('revisi-pendukung')
                ->multiple()
                ->downloadable()
                ->openable()
                ->preserveFilenames()
                ->acceptedFileTypes([
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'image/*'
                ]),

            Select::make('status')
                ->label('Status')
                ->options([
                    'menunggu' => 'Menunggu',
                    'diproses' => 'Diproses',
                    'ditolak' => 'Ditolak',
                    'selesai' => 'Selesai',
                ])
                ->default('menunggu')
                ->required()
                ->visible(fn () => Auth::check() && Auth::user()->is_admin),

            Textarea::make('tanggapan_admin')
                ->label('Tanggapan Admin')
                ->columnSpanFull()
                ->visible(fn () => Auth::check() && Auth::user()->is_admin),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('user.name')->label('Pengaju')->searchable()->sortable(),
                TextColumn::make('deskripsi')->label('Deskripsi')->limit(30)->searchable(),
                IconColumn::make('file_pendukung')->label('File')->boolean()
                    ->getStateUsing(fn (PengajuanRevisi $record) => is_array($record->file_pendukung) && count($record->file_pendukung) > 0),
                TextColumn::make('status')->label('Status')->badge()->color(fn (string $state) => match($state) {
                    'menunggu' => 'warning',
                    'diproses' => 'primary',
                    'ditolak' => 'danger',
                    'selesai' => 'success',
                }),
                TextColumn::make('created_at')->label('Tanggal Pengajuan')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'menunggu' => 'Menunggu',
                    'diproses' => 'Diproses',
                    'ditolak' => 'Ditolak',
                    'selesai' => 'Selesai',
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data, $livewire): PengajuanRevisi {
                        $pengajuanRevisi = PengajuanRevisi::create($data);

                        $pesanan = $livewire->ownerRecord;
                        $pesanan->update([
                            'status' => 'revisi',
                            'status_revisi' => 'menunggu',
                        ]);

                        Notification::make()->title('Pengajuan revisi berhasil dibuat')->success()->send();
                        return $pengajuanRevisi;
                    })
                    ->visible(fn ($livewire) => $livewire->ownerRecord->revisi_tersisa > 0 && $livewire->ownerRecord->status !== 'dibatalkan' && !empty($livewire->ownerRecord->hasil_pesanan)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn (PengajuanRevisi $record) => $record->status === 'menunggu' || (Auth::check() && Auth::user()->is_admin)),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (PengajuanRevisi $record) => $record->status === 'menunggu' && Auth::id() === $record->user_id),
                $this->getTanggapiRevisiAction(),
                $this->getSelesaikanRevisiAction(),
                $this->getDownloadFileAction(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::check() && Auth::user()->is_admin),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (!Auth::check() || !Auth::user()->is_admin) {
                    $query->where('user_id', Auth::id());
                }
                return $query;
            });
    }

    protected function getTanggapiRevisiAction(): Action
{
    return Action::make('tanggapi_revisi')
        ->label('Tanggapi')
        ->icon('heroicon-o-chat-bubble-left')
        ->color('primary')
        ->form([
            Select::make('status')
                ->label('Status')
                ->options([
                    'diproses' => 'Terima & Proses', // ← VALUE yang disimpan benar
                    'ditolak' => 'Tolak'
                ])
                ->required(),
            Textarea::make('tanggapan_admin')
                ->label('Tanggapan')
                ->required(),
        ])
        ->action(function (PengajuanRevisi $record, array $data) {
            $record->update([
                'status' => $data['status'], // value: 'diproses' atau 'ditolak'
                'tanggapan_admin' => $data['tanggapan_admin'],
            ]);

            if ($data['status'] === 'diproses') {
                $record->pesanan->update([
                    'status' => 'diproses',
                    'status_revisi' => 'diterima',
                    'revisi_tersisa' => max(0, $record->pesanan->revisi_tersisa - 1),
                ]);
                Notification::make()->title('Pengajuan revisi diterima dan sedang diproses')->success()->send();
            } else {
                $record->pesanan->update(['status_revisi' => 'ditolak']);
                Notification::make()->title('Pengajuan revisi ditolak')->warning()->send();
            }
        })
        ->visible(fn (PengajuanRevisi $record) =>
            $record->status === 'menunggu' &&
            Auth::check() &&
            Auth::user()->is_admin
        );
}


    protected function getSelesaikanRevisiAction(): Action
    {
        return Action::make('selesaikan_revisi')
            ->label('Selesaikan')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->form([
                FileUpload::make('file_hasil')->label('File Hasil Revisi')->directory('hasil-revisi')->multiple()->downloadable()->required(),
                Textarea::make('deskripsi_hasil')->label('Deskripsi Hasil')->required(),
            ])
            ->action(function (PengajuanRevisi $record, array $data) {
                $record->update(['status' => 'selesai']);

                $record->hasilRevisi()->create([
                    'file_hasil' => $data['file_hasil'],
                    'deskripsi_hasil' => $data['deskripsi_hasil'],
                    'tanggal_revisi' => now(),
                ]);

                $record->pesanan->update([
                    'status' => 'selesai',
                    'status_revisi' => 'selesai',
                    'hasil_pesanan' => $data['file_hasil'],
                ]);

                Notification::make()->title('Revisi berhasil diselesaikan')->success()->send();
            })
            ->visible(fn (PengajuanRevisi $record) => $record->status === 'diproses' && Auth::check() && Auth::user()->is_admin);
    }

    protected function getDownloadFileAction(): Action
    {
        return Action::make('download_file')
            ->label('Download File')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('primary')
            ->action(function (PengajuanRevisi $record) {
                if (is_array($record->file_pendukung) && count($record->file_pendukung) > 0) {
                    return redirect(Storage::url($record->file_pendukung[0]));
                }
                Notification::make()->title('Tidak ada file pendukung')->warning()->send();
            })
            ->hidden(fn (PengajuanRevisi $record) => empty($record->file_pendukung));
    }

    protected function canCreate(): bool
    {
        $pesanan = $this->getOwnerRecord();
        return Auth::check() && $pesanan->revisi_tersisa > 0 && $pesanan->status !== 'dibatalkan' && !empty($pesanan->hasil_pesanan);
    }

    public function hasPendingRelationship(): bool
    {
        $pesanan = $this->getOwnerRecord();
        return $pesanan->status === 'revisi' && $pesanan->status_revisi === 'menunggu';
    }
}
