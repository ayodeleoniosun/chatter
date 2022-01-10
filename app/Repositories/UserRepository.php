<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserByEmailAddress($emailAddress)
    {
        return $this->user->where('email_address', $emailAddress)->first();
    }

    public function getDuplicateUserByPhoneNumber($phoneNumber, $id)
    {
        return $this->user->where('phone_number', $phoneNumber)->where('id', '<>', $id)->first();
    }

    public function save($data)
    {
        $user = $this->user->create($data);
        $user->createToken('auth_token')->plainTextToken;

        return $user->fresh();
    }

    public function updateProfile($data, $id)
    {
        $user = $this->user->find($id);
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->phone_number = $data['phone_number'];
        $user->update();

        return $user;
    }

    public function updatePassword($data, $id)
    {
        $user = $this->user->find($id);
        $user->password = bcrypt($data['new_password']);
        $user->update();

        return $user;
    }
}
