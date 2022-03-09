<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Collection;

class MessageRepository
{
    private Message $message;

    public function __construct(
        Message $message,
    )
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

    public function messages($user, $conversation): Collection
    {
        return Message::where('conversation_id', $conversation)->get();
    }

    public function find($message): Message|null
    {
        return Message::find($message);
    }

    public function delete($message): bool
    {
        return $message->delete();
    }

}
