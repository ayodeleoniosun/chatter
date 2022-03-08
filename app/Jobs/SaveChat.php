<?php

namespace App\Jobs;

use App\Events\Chats\MessageSent;
use App\Mail\ForgotPasswordMail;
use App\Models\Chat;
use App\Models\User;
use App\Repositories\ChatRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SaveChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    protected ChatRepository $chatRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data, ChatRepository $chatRepository)
    {
        $this->data = $data;
        $this->chatRepository = $chatRepository;
    }

    /**
     * Execute the job.
     *
     * @return Chat
     */
    public function handle()
    {
        return $this->chatRepository->save($this->data);
    }
}
