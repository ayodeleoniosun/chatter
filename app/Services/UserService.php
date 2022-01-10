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

    public function profile(User $user)
    {
        return $user;
    }
}
