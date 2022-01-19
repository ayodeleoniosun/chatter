<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $guarded = ['id'];

    public function chat()
    {
        return $this->hasOne(Chat::class, 'file_id');
    }

    public function profilePicture()
    {
        return $this->hasOne(UserProfilePicture::class, 'file_id');
    }
}
