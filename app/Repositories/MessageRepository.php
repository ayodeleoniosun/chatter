<?php

namespace App\Repositories;

use App\Models\Message;

class MessageRepository
{
    private Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function save(array $data): Message
    {
        return $this->message->create([
            'conversation_id' => $data['conversation_id'],
            'sender_id'       => $data['sender_id'],
            'message'         => $data['message'],
        ]);
    }
}
