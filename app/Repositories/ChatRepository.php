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

    public function save(int $senderId, array $data): Chat
    {
        return $this->chat->create([
            'sender_id'    => $senderId,
            'recipient_id' => $data['recipient_id'],
            'content'      => $data['message'],
        ]);
    }
}
