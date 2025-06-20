<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'admin_id',
        'status',
    ];

    /**
     * Get the client that owns the chat session.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the admin assigned to this chat session.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the messages for this chat session.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at');
    }

    /**
     * Check if the chat session has unread messages for admin.
     */
    public function hasUnreadMessagesForAdmin(): bool
    {
        return $this->messages()
            ->where('sender_type', 'client')
            ->where('is_read', false)
            ->exists();
    }

    /**
     * Check if the chat session has unread messages for client.
     */
    public function hasUnreadMessagesForClient(): bool
    {
        return $this->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->exists();
    }
}
