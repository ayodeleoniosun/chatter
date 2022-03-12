<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $table = 'files';

    protected $guarded = ['id'];

    public function profilePictures(): HasMany
    {
        return $this->hasMany(UserProfilePicture::class, 'file_id');
    }
}
