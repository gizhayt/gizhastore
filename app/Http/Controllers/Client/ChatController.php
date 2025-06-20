<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\ChatMessageSent;

class ChatController extends Controller
{
    /**
     * Display the client's chat interface.
     */
    public function index()
    {
        $client = Auth::guard('client')->user();

        // Get active chat session or create a new one
        $chatSession = ChatSession::where('client_id', $client->id)
            ->where('status', '!=', 'closed')
            ->first();

        if (!$chatSession) {
            $chatSession = ChatSession::create([
                'client_id' => $client->id,
                'status' => 'pending',
            ]);
        }

        // Get all messages
        $messages = $chatSession->messages()->with(['admin'])->get();

        // Mark unread admin messages as read
        $chatSession->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('client.chat.index', [
            'chatSession' => $chatSession,
            'messages' => $messages,
        ]);
    }

    /**
     * Send a new message from the client.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_session_id' => 'required|exists:chat_sessions,id',
            'message' => 'required|string|max:1000',
        ]);

        $client = Auth::guard('client')->user();
        $chatSession = ChatSession::findOrFail($request->chat_session_id);

        if ($chatSession->client_id !== $client->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($chatSession->status === 'closed') {
            return response()->json(['error' => 'This chat session is closed'], 400);
        }

        $message = ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'client_id' => $client->id,
            'admin_id' => $chatSession->admin_id,
            'message' => $request->message,
            'sender_type' => 'client',
            'is_read' => false,
        ]);

        // Ensure chat session status is "active"
        if ($chatSession->status !== 'active') {
            $chatSession->update(['status' => 'active']);
        }

        event(new ChatMessageSent($message));

        return response()->json([
            'success' => true,
            'message' => $message->load('client'),
            'html' => view('client.chat.partials.message', ['message' => $message])->render(),
        ]);
    }

    /**
     * Fetch new messages for the client.
     */
    public function getNewMessages(Request $request)
    {
        $request->validate([
            'chat_session_id' => 'required|exists:chat_sessions,id',
            'last_message_id' => 'required|integer',
        ]);

        $client = Auth::guard('client')->user();
        $chatSession = ChatSession::findOrFail($request->chat_session_id);

        if ($chatSession->client_id !== $client->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $newMessages = $chatSession->messages()
            ->where('id', '>', $request->last_message_id)
            ->with(['admin'])
            ->get();

        // Mark admin messages as read
        $chatSession->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messagesHtml = '';
        foreach ($newMessages as $message) {
            $messagesHtml .= view('client.chat.partials.message', ['message' => $message])->render();
        }

        return response()->json([
            'success' => true,
            'messages' => $newMessages,
            'html' => $messagesHtml,
            'session_status' => $chatSession->status,
        ]);
    }
}
