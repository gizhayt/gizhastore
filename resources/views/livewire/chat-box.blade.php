<div class="chat-component">
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div wire:poll.5s class="max-h-96 overflow-y-auto p-4 border rounded bg-gray-50 space-y-4" id="chatContainer">
        @foreach ($messages as $msg)
            <div class="chat-message {{ $msg->sender_type }}">
                <div class="chat-bubble {{ $msg->sender_type }}">
                    {{ $msg->message }}
                    <div class="chat-time">{{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="sendMessage" class="mt-4 flex gap-2">
        <input type="text" wire:model.defer="newMessage" placeholder="Ketik pesan..."
            class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-purple-400" />
            <button type="submit" class="btn-kirim">Kirim</button>
        </form>

    <style>
        .chat-message {
            margin-bottom: 15px;
            display: flex;
        }

        .chat-message.client {
            justify-content: flex-start;
        }

        .chat-message.admin {
            justify-content: flex-end;
        }

        .chat-bubble {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 12px;
            font-size: 0.95rem;
            line-height: 1.4;
            position: relative;
        }

        .chat-bubble.client {
            background-color: #f3f4f6;
            color: #111827;
        }

        .chat-bubble.admin {
            background-color: #e0e7ff;
            color: #1e3a8a;
        }

        .chat-time {
            font-size: 0.75rem;
            color: #6b7280;
            text-align: right;
            margin-top: 4px;
        }

        .btn-kirim {
            background-color: rgba(140, 0, 255, 0.7); /* Ungu dengan transparansi */
            border: 1px solid #a5b4fc; /* Warna border ungu muda */
            color: #ffffff; /* Warna teks ungu tua */
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-kirim:hover {
            background-color: rgba(128, 90, 213, 0.9); /* Lebih pekat saat hover */
        }

    </style>
</div>