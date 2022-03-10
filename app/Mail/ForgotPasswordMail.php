<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected object $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(object $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address', 'Reset Password'))
            ->subject('Hey, You forgot your password')
            ->markdown('emails.forgot-password')
            ->with([
                'first_name' => ucfirst($this->data->user->first_name),
                'url'        => $this->data->link
            ]);
    }
}
