<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'content',
        'content_type'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
