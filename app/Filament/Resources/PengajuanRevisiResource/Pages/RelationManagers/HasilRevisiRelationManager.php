<?php

namespace App\Filament\Resources\PengajuanRevisiResource\RelationManagers;

use App\Models\HasilRevisi;
use App\Models\PengajuanRevisi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\{
    FileUpload, Textarea, DateTimePicker
};
use Filament\Tables\Columns\{
    TextColumn, IconColumn
};
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class HasilRevisiRelationManager extends RelationManager
{
    protected static string $relationship = 'hasilRevisi';
    
    protected static ?string $recordTitleAttribute = 'id';
    
    protected static ?string $title = 'Hasil Revisi';
    
    protected static ?string $modelLabel = 'Hasil Revisi';
    
    protected static ?string $pluralModelLabel = 'Hasil Revisi';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('file_hasil')
                    ->label('File Hasil Revisi')
                    ->directory('hasil-revisi')
                    ->multiple()
                    ->downloadable()
                    ->openable()
                    ->preserveFilenames()
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/*',
                        'application/zip'
                    ])
                    ->required(),
                
                Textarea::make('deskripsi_hasil')
                    ->label('Deskripsi Hasil')
                    ->required()
                    ->columnSpanFull(),
                
                DateTimePicker::make('tanggal_revisi')
                    ->label('Tanggal Revisi')
                    ->required()
                    ->default(now()),
                
                Forms\Components\Select::make('pengajuan_revisi_id')
                    ->relationship('pengajuanRevisi', 'id')
                    ->label('Pengajuan Revisi')
                    ->required()
                    ->disabled(fn ($livewire) => !is_null($livewire->ownerRecord))
                    ->default(fn ($livewire) => $livewire->ownerRecord?->id)
                    ->hidden(fn ($livewire) => !is_null($livewire->ownerRecord)),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                TextColumn::make('pengajuanRevisi.deskripsi_revisi')
                    ->label('Pengajuan Revisi')
                    ->limit(30),
                
                IconColumn::make('file_hasil')
                    ->label('File Hasil')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->getStateUsing(fn (HasilRevisi $record) => !empty($record->file_hasil)),
                
                TextColumn::make('deskripsi_hasil')
                    ->label('Deskripsi Hasil')
                    ->limit(30)
                    ->searchable(),
                
                TextColumn::make('tanggal_revisi')
                    ->label('Tanggal Revisi')
                    ->dateTime()
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data, $livewire): HasilRevisi {
                        // If we're in a related context, use the owner record's ID
                        if ($livewire->ownerRecord) {
                            // Find the related PengajuanRevisi
                            $pengajuanRevisi = PengajuanRevisi::where('pesanan_id', $livewire->ownerRecord->id)->first();
                            if ($pengajuanRevisi) {
                                $data['pengajuan_revisi_id'] = $pengajuanRevisi->id;
                            }
                        }
                        
                        $hasilRevisi = HasilRevisi::create($data);
                        
                        // Update the related pesanan to reflect the revision is completed
                        if (isset($data['pengajuan_revisi_id'])) {
                            $pengajuanRevisi = PengajuanRevisi::find($data['pengajuan_revisi_id']);
                            if ($pengajuanRevisi && $pengajuanRevisi->pesanan) {
                                $pengajuanRevisi->pesanan->update([
                                    'status_revisi' => 'selesai',
                                    'status' => 'selesai',
                                ]);
                            }
                        }
                        
                        Notification::make()
                            ->title('Hasil revisi berhasil ditambahkan')
                            ->success()
                            ->send();
                        
                        return $hasilRevisi;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                Action::make('download_file')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->action(function (HasilRevisi $record) {
                        if (is_array($record->file_hasil)) {
                            // If multiple files, download the first one
                            return redirect(Storage::url($record->file_hasil[0]));
                        } elseif (!empty($record->file_hasil)) {
                            return redirect(Storage::url($record->file_hasil));
                        }
                        
                        Notification::make()
                            ->title('Tidak ada file yang tersedia')
                            ->warning()
                            ->send();
                    })
                    ->hidden(fn (HasilRevisi $record) => empty($record->file_hasil)),
                    
                Action::make('view_details')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalContent(fn (HasilRevisi $record) => view('filament.components.hasil-revisi-detail', [
                        'hasilRevisi' => $record
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}