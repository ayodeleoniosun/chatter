<?php

namespace App\Repositories;

use App\Models\User;

class AccountRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserByEmailAddress(string $emailAddress): User
    {
        return app(UserRepository::class)->getUserByEmailAddress($emailAddress);
    }

    public function getDuplicateUserByPhoneNumber(string $phoneNumber, int $id): User
    {
        return app(UserRepository::class)->getDuplicateUserByPhoneNumber($$phoneNumber, $id);
    }

    public function save(array $data): User
    {
        return $this->user->create($data);
    }

    public function createToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function updatePassword(array $data, int $id): User
    {
        return app(UserRepository::class)->updatePassword($data, $id);
    }
}
