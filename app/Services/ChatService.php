<?php

namespace App\Services;

use App\Events\Chats\MessageSent;
use App\Jobs\SaveChat;
use App\Models\User;
use App\Repositories\ChatRepository;

class ChatService
{
    protected ChatRepository $chatRepository;

    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    public function send(User $sender, array $data): void
    {
        $data['sender_id'] = $sender->id;
        broadcast(new MessageSent($data));
        SaveChat::dispatch($data, $this->chatRepository);
    }
}
