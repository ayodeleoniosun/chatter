<?php

namespace App\Jobs;

use App\Mail\ForgotPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendForgotPasswordMail implements ShouldQueue
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
        $this->data->user->first_name = ucfirst($this->data->user->first_name);
        Mail::to($this->data->user->email_address)->queue(new ForgotPasswordMail($this->data));
    }
}
