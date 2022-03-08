<?php

namespace App\Jobs;

use App\Models\Conversation;
use App\Repositories\ConversationRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveConversation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    protected ConversationRepository $conversationRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data, ConversationRepository $conversationRepository)
    {
        $this->data = $data;
        $this->conversationRepository = $conversationRepository;
    }

    /**
     * Execute the job.
     *
     * @return Conversation
     */
    public function handle()
    {
        return $this->conversationRepository->save($this->data);
    }
}
