<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class File extends Model
{
    protected $table = 'files';

    protected $guarded = ['id'];

    public function profilePicture(): HasOne
    {
        return $this->hasOne(UserProfilePicture::class, 'file_id');
    }
}
