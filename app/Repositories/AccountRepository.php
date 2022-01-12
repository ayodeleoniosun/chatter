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

    public function getUserByEmailAddress($emailAddress)
    {
        return app(UserRepository::class)->getUserByEmailAddress($emailAddress);
    }

    public function getDuplicateUserByPhoneNumber($phoneNumber, $id)
    {
        return app(UserRepository::class)->getDuplicateUserByPhoneNumber($$phoneNumber, $id);
    }

    public function save($data)
    {
        $user = $this->user->create($data);
        $user->createToken('auth_token')->plainTextToken;

        return $user->fresh();
    }
}
