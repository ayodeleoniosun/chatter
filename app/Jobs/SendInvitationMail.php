<?php

namespace App\Jobs;

use App\Mail\InvitationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInvitationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected object $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $data)
    {
        $this->data = json_decode($data);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->data->invitee)->queue(new InvitationMail($this->data));
    }
}
