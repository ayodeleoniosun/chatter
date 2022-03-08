<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\Message;

class ConversationRepository
{
    private Conversation $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    public function save(array $data): Message
    {
        $senderId = $data['sender_id'];
        $recipientId = $data['recipient_id'];

        $conversation = Conversation::where([
            ['sender_id', $senderId],
            ['recipient_id', $recipientId]
        ])->orWhere(function ($query) use ($senderId, $recipientId) {
            $query->where([
                ['sender_id', $recipientId],
                ['recipient_id', $senderId]
            ]);
        })->first();

        if (!$conversation) {
            $conversation = $this->conversation->create([
                'sender_id'    => $senderId,
                'recipient_id' => $recipientId,
            ]);
        }

        $data['conversation_id'] = $conversation->id;

        return app(MessageRepository::class)->save($data);
    }
}
