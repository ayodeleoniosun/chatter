<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfilePicture extends Model
{
    protected $table = 'user_profile_pictures';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
