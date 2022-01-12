<?php

namespace App\Repositories;

use App\Models\User;
use phpDocumentor\Reflection\Types\Nullable;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserByEmailAddress(string $emailAddress): User
    {
        return $this->user->where('email_address', $emailAddress)->first();
    }

    public function getDuplicateUserByPhoneNumber(string $phoneNumber, int $id)
    {
        return $this->user->where('phone_number', $phoneNumber)->where('id', '<>', $id)->first();
    }

    public function updateProfile(array $data, int $id): User
    {
        $user = $this->user->find($id);
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->phone_number = $data['phone_number'];
        $user->update();

        return $user;
    }

    public function updatePassword(array $data, int $id): User
    {
        $user = $this->user->find($id);
        $user->password = bcrypt($data['new_password']);
        $user->update();

        return $user;
    }
}
