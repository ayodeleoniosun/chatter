<?php

namespace App\Repositories;

use App\Models\Conversation;
use Illuminate\Support\Collection;

class ConversationRepository
{
    private Conversation $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    public function getOrCreateConversation(array $data): Conversation
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

        return $conversation;
    }

    public function conversations($user): Collection
    {
        return Conversation::where('sender_id', $user)->orWhere('recipient_id', $user)->get();
    }

    public function canViewConversation(string $user, string $conversationId): bool
    {
        $conversation = Conversation::find($conversationId);

        if (!$conversation) {
            return false;
        }

        $participants = [$conversation->sender_id, $conversation->recipient_id];

        return in_array($user, $participants) ? true : false;
    }
}
