<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Chat;

class ChatRepository
{
    private Chat $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function save(array $data): Chat
    {
        return $this->chat->create([
            'sender_id'    => $data['sender_id'],
            'recipient_id' => $data['recipient_id'],
            'content'      => $data['message'],
        ]);
    }
}
