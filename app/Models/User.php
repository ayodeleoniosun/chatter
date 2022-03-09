<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticable
{
    use Notifiable, HasApiTokens, HasFactory, SoftDeletes;

    protected $table = 'users';

    protected $hidden = ['password'];

    protected $guarded = ['id'];

    public function profilePicture(): BelongsTo
    {
        return $this->belongsTo(UserProfilePicture::class);
    }

    public function profilePictures(): HasMany
    {
        return $this->hasMany(UserProfilePicture::class, 'user_id');
    }

    public function getFullNameAttribute(): string
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }
}
