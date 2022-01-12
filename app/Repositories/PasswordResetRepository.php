<?php

namespace App\Repositories;

use App\Models\PasswordReset;

class PasswordResetRepository
{
    protected $token;

    public function __construct(PasswordReset $token)
    {
        $this->token = $token;
    }

    public function validateToken(string $token)
    {
        return $this->token->find($token);
    }

    public function invalidateToken(PasswordReset $token): void
    {
        $token->used = true;
        $token->save();
    }
}
