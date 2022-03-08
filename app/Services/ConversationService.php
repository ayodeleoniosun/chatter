<?php

namespace App\Services;

use App\Events\Chats\MessageSent;
use App\Jobs\SaveConversation;
use App\Models\User;
use App\Repositories\ConversationRepository;

class ConversationService
{
    protected ConversationRepository $conversationRepository;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function send(User $sender, array $data): void
    {
        if ($sender->id == $data['recipient_id']) {
            abort(403, 'You cannot send message to yourself');
        }

        $data['sender_id'] = $sender->id;
        broadcast(new MessageSent($data));
        SaveConversation::dispatch($data, $this->conversationRepository);
    }
}
