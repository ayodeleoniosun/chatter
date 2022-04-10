<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Support\Collection;

class MessageRepository
{
    private Message $message;

    public function __construct(
        Message $message,
    ) {
        $this->message = $message;
    }

    public function save(array $data): Message
    {
        $attachment = $data['attachment'] ?? null;
        $file = null;

        if ($attachment) {
            $file = app(FileRepository::class)->create([
                'path' => $data['attachment'],
            ]);
        }

        $conversation = app(ConversationRepository::class)->getOrCreateConversation($data);

        return $this->message->create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $data['sender_id'],
            'message'         => $data['message'],
            'attachment_id'   => $file?->id,
        ]);
    }

    public function messages(int $authUserId, int $conversation): Collection
    {
        $this->readConversationUnreadMessages($authUserId, $conversation);

        return Message::where('conversation_id', $conversation)->get();
    }

    public function find(string $message): Message|null
    {
        return Message::find($message);
    }

    public function delete(Message $message): bool
    {
        return $message->delete();
    }

    public function getConversationUnreadMessages(int $authUserId, int $conversationId): Collection
    {
        return Message::where([
            ['conversation_id', $conversationId],
            ['sender_id', '<>', $authUserId],
            ['is_read', false],
        ])->get();
    }

    private function readConversationUnreadMessages(int $authUserId, int $conversationId)
    {
        $this->getConversationUnreadMessages($authUserId, $conversationId)->map(function ($message) {
            $message->is_read = true;
            $message->read_at = now();
            $message->save();
        });
    }
}
