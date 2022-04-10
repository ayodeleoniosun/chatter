<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserProfilePicture;

class UserProfilePictureRepository
{
    private UserProfilePicture $picture;

    public function __construct(UserProfilePicture $picture)
    {
        $this->picture = $picture;
    }

    public function save(array $data, int $userId): User
    {
        $file = app(FileRepository::class)->create([
            'path' => $data['path'],
        ]);

        $picture = $this->picture->create([
            'user_id' => $userId,
            'file_id' => $file->id,
        ]);

        $user = app(UserRepository::class)->getUser($userId);
        $user->profile_picture_id = $picture->id;
        $user->save();

        return $user->fresh();
    }
}
