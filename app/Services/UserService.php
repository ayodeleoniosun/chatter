<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        return $this->userRepository->save($data);
    }

    public function login(array $data): array
    {
        $user = $this->userRepository->getUserByEmailAddress($data['email_address']);
        
        if (!$user || !Hash::check($data['password'], $user->password)) {
            abort(401, __('Incorrect login credentials'));
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
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
