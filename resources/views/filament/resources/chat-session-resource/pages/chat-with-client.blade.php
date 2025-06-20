<x-filament::page>
    <div class="space-y-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-medium">Chat with {{ $record->client->name }}</h2>
                    <p class="text-sm text-gray-500">Status: 
                        <span class="
                            @if($record->status === 'active') text-green-600 @endif
                            @if($record->status === 'pending') text-yellow-600 @endif
                            @if($record->status === 'closed') text-red-600 @endif
                        ">
                            {{ ucfirst($record->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="h-96 overflow-y-auto p-4 space-y-4" id="chat-messages">
                @forelse($messages as $message)
                    <div class="flex {{ $message->sender_type === 'admin' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-md px-4 py-2 rounded-lg {{ $message->sender_type === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100' }}">
                            <div class="text-xs text-gray-500">
                                {{ $message->sender_type === 'admin' ? $message->sender->name : $record->client->name }} • 
                                {{ $message->created_at->format('M d, Y H:i') }}
                            </div>
                            <div class="mt-1">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        No messages yet.
                    </div>
                @endforelse
            </div>

            @if($record->status !== 'closed')
                <div class="p-4 border-t">
                    <form wire:submit.prevent="sendMessage">
                        <div class="flex space-x-2">
                            <div class="flex-1">
                                <textarea 
                                    wire:model.defer="messageText" 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                    placeholder="Type your message..."
                                    rows="2"
                                ></textarea>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                    Send
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="p-4 border-t text-center text-gray-500">
                    This chat is closed. You cannot send new messages.
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const messagesContainer = document.getElementById('chat-messages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            
            // Auto-scroll when new messages arrive
            Livewire.hook('message.processed', () => {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            });
        });
    </script>
</x-filament::page>