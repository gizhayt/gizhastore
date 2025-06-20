<div class="flex {{ $message->sender_type === 'client' ? 'justify-end' : 'justify-start' }} mb-4">
    <div class="max-w-xs sm:max-w-md p-3 rounded-lg {{ $message->sender_type === 'client' ? 'bg-blue-100 text-blue-900' : 'bg-gray-100 text-gray-900' }}">
        <div class="flex items-center mb-1">
            <span class="font-medium {{ $message->sender_type === 'client' ? 'text-blue-700' : 'text-gray-700' }}">
                {{ $message->sender_type === 'client' ? 'You' : ($message->admin->name ?? 'Admin') }}
            </span>
            <span class="ml-2 text-xs text-gray-500">
                {{ $message->created_at->format('M d, H:i') }}
            </span>
        </div>
        <p class="whitespace-pre-wrap break-words">{{ $message->message }}</p>
    </div>
</div>