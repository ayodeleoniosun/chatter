<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $table = 'invitations';

    protected $guarded = ['id'];

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
