<div class="max-w-4xl mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-4 bg-gray-100 rounded-t-lg">
            <h2 class="text-xl font-bold">Live Chat</h2>
        </div>

        <div class="p-4 h-96 overflow-y-auto" id="chat-messages">
            @foreach($messages as $message)
                <div class="mb-4 {{ $message['user']['id'] === auth()->id() ? 'text-right' : 'text-left' }}">
                    <div class="inline-block rounded-lg px-4 py-2 {{ $message['user']['id'] === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                        <p class="font-bold">{{ $message['user']['name'] }}</p>
                        <p>{{ $message['content'] }}</p>
                        <p class="text-xs opacity-75">{{ \Carbon\Carbon::parse($message['created_at'])->format('g:i A') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="p-4 border-t">
            <form wire:submit="sendMessage">
                <div class="flex">
                    <input type="text" wire:model="message" class="flex-1 border rounded-l-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Type your message...">
                    <button type="submit" class="bg-blue-500 text-white rounded-r-lg px-4 py-2 hover:bg-blue-600">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-scroll to bottom when new messages arrive
    document.addEventListener('livewire:initialized', () => {
        const messagesContainer = document.getElementById('chat-messages');
        const scrollToBottom = () => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        };

        scrollToBottom();

        Livewire.on('message-sent', () => {
            setTimeout(scrollToBottom, 50);
        });
    });
</script>
