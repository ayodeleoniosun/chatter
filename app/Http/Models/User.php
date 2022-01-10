<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticable
{
    use Notifiable, HasApiTokens;

    protected $table = 'users';

    protected $hidden = ['password'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email_address',
        'phone_number',
        'password',
        'bearer_token'
    ];

    public function inboxes()
    {
        return $this->hasMany(PrivateMessage::class, 'recipient_id');
    }

    public function outboxes()
    {
        return $this->hasMany(PrivateMessage::class, 'sender_id');
    }
}
