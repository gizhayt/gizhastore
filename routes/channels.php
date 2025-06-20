<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ChatSession;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Private channel for admin
Broadcast::channel('chat.{chatSessionId}', function ($user, $chatSessionId) {
    // Admin can listen to any chat session
    if ($user->role === 'admin') {
        return true;
    }
    
    return false;
});

// Private channel for client
Broadcast::channel('client.chat.{chatSessionId}', function ($client, $chatSessionId) {
    // Client can only listen to their own chat sessions
    $chatSession = ChatSession::find($chatSessionId);
    
    return $chatSession && $chatSession->client_id === $client->id;
});