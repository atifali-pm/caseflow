<div>
    <div class="space-y-3 max-h-96 overflow-y-auto mb-4">
        @forelse ($messages as $message)
            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs {{ $message->sender_id === auth()->id() ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-900' }} rounded-2xl px-4 py-2">
                    <p class="text-sm">{{ $message->body }}</p>
                    <p class="text-xs {{ $message->sender_id === auth()->id() ? 'text-amber-100' : 'text-gray-500' }} mt-1">
                        {{ $message->sender->name }} · {{ $message->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        @empty
            <p class="text-center text-sm text-gray-500">No messages yet. Start the conversation.</p>
        @endforelse
    </div>

    <form wire:submit="send" class="flex gap-2">
        <textarea wire:model="body" rows="2" placeholder="Type a message..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"></textarea>
        <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg self-end">
            Send
        </button>
    </form>
    @error('body') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
</div>
