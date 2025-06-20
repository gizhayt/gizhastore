<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChatMessageResource\Pages;
use App\Models\ChatMessage;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Pages\Actions\Action;
use Filament\Notifications\Notification;

class ChatMessageResource extends Resource
{
    protected static ?string $model = ChatMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationGroup = 'Manajemen Client';

    protected static ?string $navigationLabel = 'Pesan Chat';

    // Menambahkan badge notifikasi di sidebar navigation
    public static function getNavigationBadge(): ?string
    {
        // Debug: cek semua pesan belum dibaca dulu
        $allUnread = static::getModel()::where('is_read', false)->count();
        
        // Hitung pesan dari client yang belum dibaca oleh admin
        $clientUnread = static::getModel()::where('is_read', false)
            ->where('sender_type', 'client')
            ->count();
        
        // Untuk debug, tampilkan total dulu
        return $allUnread > 0 ? (string) $allUnread : null;
        
        // Nanti ganti ke ini setelah sudah benar:
        // return $clientUnread > 0 ? (string) $clientUnread : null;
    }

    // Atur warna badge notifikasi (merah untuk pesan baru)
    public static function getNavigationBadgeColor(): string|array|null
    {
        $unreadCount = static::getModel()::where('is_read', false)
            ->where('sender_type', 'client') // Hanya pesan dari client
            ->count();
        
        return $unreadCount > 0 ? 'danger' : null;
    }

    public static function form(Form $form): Form
    {
        return $form; // Empty form as we're using Livewire
    }

    public static function table(Table $table): Table
    {
        return $table; // Empty table as we're using custom chat interface
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            // Add a default index page without parameters
            'index' => Pages\ListChatMessages::route('/'),
            
            // Add the custom chat page with chatSessionId parameter
            'chat' => Pages\CustomChatPage::route('/{chatSessionId}'),
        ];
    }
}