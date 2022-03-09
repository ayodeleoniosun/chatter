<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordReset extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $table = 'password_resets';

    public $incrementing = false;

    protected $primaryKey = 'token';

    protected $keyType = 'string';

    protected $fillable = [
        'email',
        'token',
        'expires_at'
    ];
}
