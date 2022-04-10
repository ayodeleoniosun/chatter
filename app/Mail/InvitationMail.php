<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
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
        return $this->from(config('mail.from.address', 'Invitation to chatter'))
            ->subject('Hey, you are invited')
            ->markdown('emails.invitation')
            ->with([
                'invited_by' => ucfirst($this->data->user->first_name),
                'url'        => $this->data->invitation_link,
            ]);
    }
}
