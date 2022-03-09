<?php

namespace App\Services;

use App\Events\Chats\MessageSent;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Jobs\SaveMessage;
use App\Models\User;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageService
{
    protected MessageRepository $messageRepository;

    protected ConversationRepository $conversationRepository;

    public function __construct(
        MessageRepository      $messageRepository,
        ConversationRepository $conversationRepository
    )
    {
        $this->messageRepository = $messageRepository;
        $this->conversationRepository = $conversationRepository;
    }

    public function conversations(string $user): ResourceCollection
    {
        return ConversationResource::collection($this->conversationRepository->conversations($user));
    }

    public function send(User $sender, array $data): void
    {
        if ($sender->id == $data['recipient_id']) {
            abort(403, 'You cannot send message to yourself');
        }

        $data['sender_id'] = $sender->id;
        broadcast(new MessageSent($data));
        SaveMessage::dispatch($data, $this->messageRepository);
    }

    public function messages(string $user, string $conversation): ResourceCollection
    {
        return MessageResource::collection($this->messageRepository->messages($conversation, $user));
    }
}
