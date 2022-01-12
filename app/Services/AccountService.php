<?php

namespace App\Services;

use App\Jobs\SendForgotPasswordMail;
use App\Models\User;
use App\Repositories\AccountRepository;
use Illuminate\Support\Facades\Hash;

class AccountService
{
    protected $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function register(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        return $this->accountRepository->save($data);
    }

    public function login(array $data): array
    {
        $user = $this->accountRepository->getUserByEmailAddress($data['email_address']);
        
        if (!$user || !Hash::check($data['password'], $user->password)) {
            abort(401, __('Incorrect login credentials'));
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function forgotPassword(array $data)
    {
        $user = $this->accountRepository->getUserByEmailAddress($data['email_address']);
        
        if (!$user) {
            abort(404, __('Email address does not exist'));
        }

        SendForgotPasswordMail::dispatch($user);
    }
}
