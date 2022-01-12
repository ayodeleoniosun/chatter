<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function updateProfile(array $data, int $userId): User
    {
        $userExists = $this->userRepository->getDuplicateUserByPhoneNumber($data['phone_number'], $userId);

        if ($userExists) {
            abort(403, __('Phone number belongs to another user'));
        }

        return $this->userRepository->updateProfile($data, $userId);
    }

    public function updatePassword(array $data, int $userId): User
    {
        return $this->userRepository->updatePassword($data, $userId);
    }
}
