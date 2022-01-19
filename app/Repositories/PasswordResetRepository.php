<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\PasswordReset;

class PasswordResetRepository
{
    private PasswordReset $token;

    public function __construct(PasswordReset $token)
    {
        $this->token = $token;
    }

    public function create(array $data): PasswordReset
    {
        return $this->token->create($data);
    }

    public function validateToken(string $token): ?PasswordReset
    {
        return $this->token->find($token);
    }

    public function invalidateToken(PasswordReset $token): void
    {
        $token->used = true;
        $token->save();
    }
}
