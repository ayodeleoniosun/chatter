<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

class AccountRepository
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserByEmailAddress(string $email): ?User
    {
        return app(UserRepository::class)->getUserByEmailAddress($email);
    }

    public function getDuplicateUserByPhoneNumber(string $phone, int $id): User
    {
        return app(UserRepository::class)->getDuplicateUserByPhoneNumber($$phone, $id);
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
