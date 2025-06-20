<?php

namespace App\Filament\Resources\ChatMessageResource\Pages;

use App\Filament\Resources\ChatMessageResource;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListChatMessages extends ListRecords
{
    protected static string $resource = ChatMessageResource::class;

    // Tambahkan button di header
    protected function getHeaderActions(): array
    {
        return [
            Action::make('markAllAsRead')
                ->label('Tandai Semua Dibaca')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    // Update semua pesan client yang belum dibaca menjadi sudah dibaca
                    $updatedCount = ChatMessage::where('is_read', false)
                        ->where('sender_type', 'client')
                        ->update(['is_read' => true]);
                    
                    // Tampilkan notifikasi sukses
                    Notification::make()
                        ->title('Berhasil!')
                        ->body("Berhasil menandai {$updatedCount} pesan sebagai sudah dibaca.")
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Tandai semua pesan sebagai sudah dibaca?')
                ->modalDescription('Ini akan menandai semua pesan klien yang belum dibaca sebagai sudah dibaca. Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, tandai sebagai dibaca'),
        ];
    }

    protected function getTableQuery(): Builder
    {
        // Start from chat_sessions instead of chat_messages
        return ChatSession::query()
            ->select([
                'chat_sessions.id',
                'chat_sessions.created_at',
                'clients.name as client_name',
                DB::raw('COUNT(chat_messages.id) as message_count'),
                DB::raw('COUNT(CASE WHEN chat_messages.is_read = 0 AND chat_messages.sender_type = "client" THEN 1 END) as unread_count'),
                DB::raw('MAX(chat_messages.created_at) as last_message_time')
            ])
            ->join('clients', 'chat_sessions.client_id', '=', 'clients.id')
            ->leftJoin('chat_messages', 'chat_sessions.id', '=', 'chat_messages.chat_session_id')
            ->groupBy('chat_sessions.id', 'clients.name', 'chat_sessions.created_at')
            ->orderBy('last_message_time', 'desc'); // Urutkan berdasarkan pesan terakhir
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Nama Klien')
                    ->searchable()
                    ->sortable()
                    ->badge(fn ($record) => $record->unread_count > 0 ? 'Pesan Baru' : null)
                    ->color(fn ($record) => $record->unread_count > 0 ? 'danger' : 'gray'),
                Tables\Columns\TextColumn::make('message_count')
                    ->label('Total Pesan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unread_count')
                    ->label('Pesan Belum Dibaca')
                    ->numeric()
                    ->badge()
                    ->color(fn ($record) => $record->unread_count > 0 ? 'danger' : 'success')
                    ->formatStateUsing(fn ($state) => $state > 0 ? $state : '✓')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_message_time')
                    ->label('Pesan Terakhir')
                    ->dateTime()
                    ->sortable()
                    ->since(), // Menampilkan "2 jam yang lalu", dll
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dimulai Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
            ])
            ->actions([
                Tables\Actions\Action::make('view_chat')
                    ->label('Lihat Chat')
                    ->url(fn ($record) => static::getResource()::getUrl('chat', ['chatSessionId' => $record->id]))
                    ->icon('heroicon-o-chat-bubble-left-right'),
            ]);
    }
}