<?php

namespace App\Livewire;

use App\Events\MessageSendEvent;
use App\Models\Message;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatComponent extends Component
{
    public $user_id;
    public $user_name;
    public $sender_id;
    public $receiver_id;
    public $message;
    public $messages;
    public function render()
    {
        return view('livewire.chat-component');
    }
    public function mount($user_id,$user_name,$sender_id,$receiver_id)
    {
        $this->user_id = $user_id;
        $this->user_name = $user_name;
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->messages = Message::where(function($query) {
            $query->where('sender_id', $this->sender_id)
                  ->where('receiver_id', $this->receiver_id);
        })->orWhere(function($query) {
            $query->where('sender_id', $this->receiver_id)
                  ->where('receiver_id', $this->sender_id);
        })->with('sender:id,name', 'receiver:id,name')
          ->get()
          ->map(function($message) {
              return [
                  'id' => $message->id,
                  'message' => $message->message,
                  'sender' => $message->sender->name,
                  'receiver' => $message->receiver->name
              ];
          })
          ->toArray();
        // dd($this->messages);
    }
    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|max:255',
        ]);
        $chatMessage = Message::create([
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'message' => $this->message,
        ]);
        $this->messages[] = [
            'id' => $chatMessage->id,
            'message' => $chatMessage->message,
            'sender' => $chatMessage->sender->name,
            'receiver' => $chatMessage->receiver->name
        ];
        $this->message = '';
        broadcast(new MessageSendEvent($chatMessage))->toOthers();
    }
    #[On('echo-private:chat.{sender_id},MessageSendEvent')]
    public function listenForMessage($event)
    {
        $message = Message::with('sender:id,name', 'receiver:id,name')
            ->find($event['message']['id']);
        
        $this->messages[] = [
            'id' => $message->id,
            'message' => $message->message,
            'sender' => $message->sender->name,
            'receiver' => $message->receiver->name
        ];
    }
}
