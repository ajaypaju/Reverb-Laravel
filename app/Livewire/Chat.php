<?php

namespace App\Livewire;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Chat extends Component
{
    public $message = '';
    public $messages = [];

    public function mount()
    {
        // Load recent messages
        $this->messages = Message::with('user')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->toArray();
    }

    public function sendMessage()
    {
        if (empty($this->message)) {
            return;
        }

        $message = Message::create([
            'user_id' => Auth::id(),
            'content' => $this->message,
        ]);

        $this->reset('message');

        // Broadcast the message
        $this->dispatch('message-sent', [
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'created_at' => $message->created_at,
                'user' => [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                ],
            ],
        ])->to('chat');
    }

    #[On('echo:chat,message-sent')]
    public function handleMessageSent($event)
    {
        $this->messages[] = $event['message'];
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
