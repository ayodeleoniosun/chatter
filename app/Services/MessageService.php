<?php

namespace App\Services;

use App\Events\Chats\MessageSent;
use App\Jobs\SaveMessage;
use App\Models\User;
use App\Repositories\MessageRepository;

class MessageService
{
    protected MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
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
}
