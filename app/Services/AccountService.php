<?php

namespace App\Services;

use App\Models\{User, PasswordReset};
use App\Repositories\{AccountRepository, PasswordResetRepository, InvitationRepository};
use App\Jobs\SendForgotPasswordMail;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\{DB, Hash};
use Illuminate\Support\Str;

class AccountService
{
    protected AccountRepository $accountRepository;
    protected PasswordResetRepository $passwordResetRepository;
    protected InvitationRepository $invitationRepository;

    public function __construct(
        AccountRepository       $accountRepository,
        PasswordResetRepository $passwordResetRepository,
        InvitationRepository    $invitationRepository
    )
    {
        $this->accountRepository = $accountRepository;
        $this->passwordResetRepository = $passwordResetRepository;
        $this->invitationRepository = $invitationRepository;
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
            'user'  => new UserResource($user),
            'token' => $token
        ];
    }

    public function forgotPassword(array $data): ?PasswordReset
    {
        $user = $this->accountRepository->getUserByEmailAddress($data['email_address']);

        if (!$user) {
            abort(404, 'Email address does not exist');
        }

        $token = Str::random(60);
        $forgotPasswordLink = config('app.url') . '/reset-password?token=' . $token;
        $expiration = Carbon::now()->addMinutes(10)->toDateTimeString();

        $data = json_encode([
            'user'       => $user,
            'token'      => $token,
            'link'       => $forgotPasswordLink,
            'expiration' => $expiration
        ]);

        SendForgotPasswordMail::dispatch($data);

        return app(PasswordResetRepository::class)->create([
            'email'      => $user->email_address,
            'token'      => $token,
            'expires_at' => $expiration
        ]);
    }

    public function resetPassword(array $data): void
    {
        $token = $this->passwordResetRepository->getToken($data['token']);

        if (!$token) {
            abort(403, 'Invalid token');
        }

        $user = $this->accountRepository->getUserByEmailAddress($token->email);

        if (!$user) {
            abort(404, 'User not found.');
        }

        $tokenExpiryMinutes = Carbon::parse($token->expires_at)->diffInMinutes(Carbon::now());
        $configExpiryMinutes = config('auth.passwords.users.expire');

        if ($tokenExpiryMinutes > $configExpiryMinutes) {
            abort(403, 'Token has expired. Kindly reset password again.');
        }

        DB::transaction(function () use ($data, $user, $token) {
            $this->accountRepository->updatePassword($data, $user->id);
            $this->passwordResetRepository->invalidateToken($token);
        });
    }

    public function acceptInvitation(array $data): void
    {
        $token = $this->invitationRepository->getToken($data['token']);

        if (!$token || $token->invitee != $data['email_address']) {
            abort(403, 'Invalid token');
        }

        DB::transaction(function () use ($data, $token,) {
            $this->invitationRepository->invalidateToken($token);
            $this->register($data);
        });
    }
}
