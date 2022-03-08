<?php

namespace App\Jobs;

use App\Models\Message;
use App\Repositories\MessageRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    protected MessageRepository $messageRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data, MessageRepository $messageRepository)
    {
        $this->data = $data;
        $this->messageRepository = $messageRepository;
    }

    /**
     * Execute the job.
     *
     * @return Message
     */
    public function handle()
    {
        return $this->messageRepository->save($this->data);
    }
}
