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
        $conversation = app(ConversationRepository::class)->getOrCreateConversation($data);

        return $this->message->create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $data['sender_id'],
            'message'         => $data['message'],
        ]);
    }
}
