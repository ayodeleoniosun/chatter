<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;

class UserRepository
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUsers()
    {
        return User::all();
    }

    public function getUser(int $id): ?User
    {
        return $this->user->find($id);
    }

    public function getUserByEmailAddress(string $emailAddress): ?User
    {
        return $this->user->where('email_address', $emailAddress)->first();
    }

    public function getDuplicateUserByPhoneNumber(string $phoneNumber, int $id): ?User
    {
        return $this->user->where('phone_number', $phoneNumber)->where('id', '<>', $id)->first();
    }

    public function updateProfile(array $data, int $id): User
    {
        $user = $this->getUser($id);
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->phone_number = $data['phone_number'];
        $user->update();

        return $user;
    }

    public function updatePassword(array $data, int $id): User
    {
        $user = $this->getUser($id);
        $user->password = bcrypt($data['new_password']);
        $user->update();

        return $user;
    }

    public function updateProfilePicture(array $data, int $userId): User
    {
        return app(UserProfilePictureRepository::class)->save($data, $userId);
    }

    public function inviteUser(string $email, int $id)
    {
        $data = [
            'invited_by' => $id,
            'invitee'    => $email,
            'token'      => Str::random(60),
        ];

        return app(InvitationRepository::class)->create($data);
    }
}
