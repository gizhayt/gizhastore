<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ChatBox extends Component
{
    public $chatSession;
    public $messages = [];
    public $newMessage = '';
    
    public $chatSessionId;

    public function mount($chatSessionId)
    {
        $this->chatSessionId = $chatSessionId;
        $this->chatSession = ChatSession::findOrFail($this->chatSessionId);
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = $this->chatSession->messages()->latest()->take(50)->get()->reverse();
    }

    public function sendMessage()
    {
        if (empty($this->newMessage)) {
            return;
        }

        // Get the current user
        $user = Auth::user();
        
        // Debug information
        Log::info('User ID: ' . $user->id);
        Log::info('User role: ' . $user->role);
        
        // Get the chat session
        $chatSession = ChatSession::findOrFail($this->chatSessionId);
        
        try {
            if ($user->role === 'admin') {
                // Admin sending message
                ChatMessage::create([
                    'chat_session_id' => $this->chatSessionId,
                    'client_id' => $chatSession->client_id, // Use the existing client_id from session
                    'admin_id' => $user->id,
                    'message' => $this->newMessage,
                    'sender_type' => 'admin',
                    'is_read' => false,
                ]);
                Log::info('Admin message created successfully');
            } else {
                // Check if the user exists in the clients table directly (based on your schema)
                // This is different from before - we're assuming client users log in through the clients table
                $client = Client::where('id', $user->id)->first();
                
                if (!$client) {
                    // Try to find if there's a client with user_id = current user's id
                    $client = Client::where('user_id', $user->id)->first();
                    
                    if (!$client) {
                        Log::error('No client found for user ID: ' . $user->id);
                        session()->flash('error', 'Error: Your account is not linked to a client profile.');
                        return;
                    }
                }
                
                Log::info('Client ID found: ' . $client->id);
                
                // Create the message with the correct client_id
                ChatMessage::create([
                    'chat_session_id' => $this->chatSessionId,
                    'client_id' => $client->id,
                    'admin_id' => $chatSession->admin_id,
                    'message' => $this->newMessage,
                    'sender_type' => 'client',
                    'is_read' => false,
                ]);
                Log::info('Client message created successfully');
            }
            
            $this->newMessage = '';
            $this->loadMessages();
            
        } catch (\Exception $e) {
            Log::error('Error creating message: ' . $e->getMessage());
            session()->flash('error', 'Message error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.chat-box', [
            'messages' => $this->messages,
        ]);
    }
}