<?php

namespace App\Services;

use App\Jobs\SendForgotPasswordMail;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\PasswordResetRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountService
{
    protected $accountRepository;
    protected $passwordResetRepository;

    public function __construct(AccountRepository $accountRepository, PasswordResetRepository $passwordResetRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->passwordResetRepository = $passwordResetRepository;
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
            abort(401, 'Incorrect login credentials');
        }

        $token = $this->accountRepository->createToken($user);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function forgotPassword(array $data)
    {
        $user = $this->accountRepository->getUserByEmailAddress($data['email_address']);
        
        if (!$user) {
            abort(404, 'Email address does not exist');
        }

        SendForgotPasswordMail::dispatch($user);
    }

    public function resetPassword(array $data)
    {
        $token = $this->passwordResetRepository->validateToken($data['token']);
        
        if (!$token || $token->used) {
            abort(403, 'Invalid token');
        } else {
            $tokenExpiryMinutes = Carbon::parse($token->expires_at)->diffInMinutes(Carbon::now());
            $configExpiryMinutes = config('auth.passwords.users.expire');

            if ($tokenExpiryMinutes > $configExpiryMinutes) {
                abort(403, 'Token has expired. Kindly reset password again.');
            } else {
                $user = $this->accountRepository->getUserByEmailAddress($token->email);
                
                DB::transaction(function () use ($data, $user, $token) {
                    $this->accountRepository->updatePassword($data, $user->id);
                    $this->passwordResetRepository->invalidateToken($token);
                });
            }
        }
    }
}
