<?php

namespace App\Services;

use App\Events\Chats\MessageSent;
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
        $chat = $this->chatRepository->save($sender->id, $data);
        broadcast(new MessageSent($chat));
    }
}
