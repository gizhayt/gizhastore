<?php

namespace App\Filament\Resources\ChatSessionResource\Pages;

use App\Models\ChatSession;
use Filament\Resources\Pages\Page;

class CustomChattPage extends Page
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
}

}
