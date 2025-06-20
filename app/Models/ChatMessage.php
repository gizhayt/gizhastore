<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';


    protected $fillable = [
        'chat_session_id',
        'client_id',
        'admin_id',
        'message',
        'sender_type', // 'client' or 'admin'
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Get the client who sent or owns the message.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the admin who sent or is related to the message.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the chat session that this message belongs to.
     */
    public function chatSession(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class);
    }
}
