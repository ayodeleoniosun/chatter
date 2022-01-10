<?php

namespace App\Http\Repositories;

use App\Http\Models\User;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function save($data)
    {
        $user = $this->user->create($data);
        $user->createToken('auth_token')->plainTextToken;

        return $user->fresh();
    }

    public function getUserByEmailAddress($emailAddress)
    {
        return $this->user->where('email_address', $emailAddress)->first();
    }

    public function getUserByPhoneNumber($phoneNumber)
    {
        return $this->user->where('phone_number', $phoneNumber)->first();
    }
}
