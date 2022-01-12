<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = json_decode($data);
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
            ->markdown('emails.passwords.forgot')->with([
                'first_name' => ucfirst($this->data->first_name),
                'url' => $this->data->forgot_password_link
            ]);
    }
}
