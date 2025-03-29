<x-layouts.app :title="__('Chat')">
    @livewire('chat-component',['user_id' => $id,'user_name' => $name,'sender_id' => $sender_id,'receiver_id' => $receiver_id])
</x-layouts.app>
