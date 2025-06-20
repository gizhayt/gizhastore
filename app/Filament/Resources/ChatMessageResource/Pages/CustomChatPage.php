<?php

namespace App\Filament\Resources\ChatMessageResource\Pages;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use Filament\Resources\Pages\Page;

class CustomChatPage extends Page
{
    protected static string $resource = \App\Filament\Resources\ChatMessageResource::class;

    protected static string $view = 'filament.resources.custom-chat-page';

    public $chatSessionId;

    public function mount($chatSessionId)
    {
        // Periksa apakah chat session ada, jika tidak maka tampilkan error atau redirect
        $this->chatSessionId = $chatSessionId;

        if (! ChatSession::find($this->chatSessionId)) {
            abort(404, 'Chat session tidak ditemukan');
        }

        // Otomatis mark as read semua pesan client di chat session ini
        $this->markChatSessionAsRead();
    }

    /**
     * Mark semua pesan client di chat session ini sebagai sudah dibaca
     */
    private function markChatSessionAsRead()
    {
        ChatMessage::where('chat_session_id', $this->chatSessionId)
            ->where('sender_type', 'client')
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Method untuk dipanggil dari Livewire jika ada update pesan baru
     */
    public function markAsRead()
    {
        $this->markChatSessionAsRead();
    }

    /**
     * Method untuk polling/auto refresh
     */
    public function refreshMessages()
    {
        // Mark pesan baru sebagai sudah dibaca
        $this->markChatSessionAsRead();
        
        // Refresh component
        $this->dispatch('refreshChat');
    }

    /**
     * Polling untuk auto refresh setiap 3 detik
     */
    public function getPollingInterval(): int
    {
        return 3000; // 3 detik dalam milliseconds
    }
}