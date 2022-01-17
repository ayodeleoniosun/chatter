<?php

namespace App\Repositories;

use App\Models\File;
use App\Models\UserProfilePicture;

class UserProfilePictureRepository
{
    protected $profilePicture;

    public function __construct(UserProfilePicture $profilePicture)
    {
        $this->profilePicture = $profilePicture;
    }

    public function save(string $filename, int $userId) : void
    {
        $file = app(FileRepository::class)->create([
            'filename' => $filename,
            'type' => File::PROFILE_PICTURE,
            'object_id' => $userId
        ]);

        $data = ['user_id' => $userId, 'file_id' => $file->id];
        
        $profilePicture = $this->profilePicture->create($data);

        $user = app(UserRepository::class)->getUser($userId);
        $user->profile_picture_id = $profilePicture->id;
        $user->save();
    }
}
