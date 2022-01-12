<?php

namespace App\Jobs;

use App\Mail\ForgotPasswordMail;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendForgotPasswordMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $token = Str::random(60);
        $forgotPasswordLink = config('app.url').'/reset-password?token='.$token;
        $nextTenMinutes = Carbon::now()->addMinutes(10)->toDateTimeString();
        
        $data = json_encode([
            'first_name' => $this->user->first_name,
            'forgot_password_link' => $forgotPasswordLink
        ]);

        Mail::to($this->user->email_address)->queue(new ForgotPasswordMail($data));
    
        PasswordReset::create([
            'email' => $this->user->email_address,
            'token' => $token,
            'expires_at' => $nextTenMinutes
        ]);
    }
}
