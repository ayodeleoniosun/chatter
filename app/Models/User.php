<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticable
{
    use Notifiable, HasApiTokens;

    protected $table = 'users';

    protected $hidden = ['password'];

    protected $guarded = ['id'];

    public function inboxes()
    {
        return $this->hasMany(PrivateMessage::class, 'recipient_id');
    }

    public function outboxes()
    {
        return $this->hasMany(PrivateMessage::class, 'sender_id');
    }

    public function profilePicture()
    {
        return $this->belongsTo(UserProfilePicture::class);
    }

    public function profilePictures()
    {
        return $this->hasMany(UserProfilePicture::class, 'user_id');
    }
}
