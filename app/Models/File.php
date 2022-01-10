<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = ['filename'];

    public function chat()
    {
        return $this->hasOne(Chat::class, 'file_id');
    }
}
