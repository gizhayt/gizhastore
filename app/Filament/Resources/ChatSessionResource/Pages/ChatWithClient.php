<?php

namespace App\Filament\Resources\ChatSessionResource\Pages;

use App\Filament\Resources\ChatSessionResource;
use Filament\Resources\Pages\Page;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;

class ChatWithClient extends Page
{
    protected static string $resource = ChatSessionResource::class;

    protected static string $view = 'filament.resources.chat-session-resource.pages.chat-with-client';

    public ChatSession $record;
    public string $messageText = '';
    public $messages = [];

    protected $listeners = ['refreshMessages' => 'loadMessages'];

    public function mount(ChatSession $record): void
    {
        $this->record = $record;
        $this->loadMessages();

        // Tandai semua pesan client sebagai terbaca
        ChatMessage::where('chat_session_id', $this->record->id)
            ->where('sender_type', 'client')
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function loadMessages(): void
    {
        $this->messages = ChatMessage::where('chat_session_id', $this->record->id)
            ->with(['client', 'admin'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage(): void
    {
        if (trim($this->messageText) === '') {
            return;
        }

        ChatMessage::create([
            'chat_session_id' => $this->record->id,
            'admin_id' => Auth::id(),
            'client_id' => $this->record->client_id,
            'sender_type' => 'admin',
            'message' => $this->messageText,
            'is_read' => false,
        ]);

        $this->messageText = '';
        $this->loadMessages();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to List')
                ->url(route('filament.resources.chat-sessions.index'))
                ->color('secondary'),

            Actions\Action::make('closeChat')
                ->label('Close Chat')
                ->requiresConfirmation()
                ->hidden(fn () => $this->record->status === 'closed')
                ->color('danger')
                ->action(function () {
                    $this->record->status = 'closed';
                    $this->record->save();

                    $this->notification()->success(
                        title: 'Chat Closed',
                        body: 'The chat has been closed successfully.'
                    );

                    return redirect()->route('filament.resources.chat-sessions.index');
                }),
        ];
    }
}
