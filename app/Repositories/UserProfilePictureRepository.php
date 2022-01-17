<?php

namespace App\Repositories;

use App\Models\File;
use App\Models\UserProfilePicture;

class UserProfilePictureRepository
{
    protected $picture;

    public function __construct(UserProfilePicture $picture)
    {
        $this->picture = $picture;
    }

    public function save(string $filename, int $id) : void
    {
        $file = app(FileRepository::class)->create([
            'filename' => $filename,
            'type' => File::PROFILE_PICTURE,
            'object_id' => $id
        ]);

        $data = ['user_id' => $id, 'file_id' => $file->id];
        
        $picture = $this->picture->create($data);

        $user = app(UserRepository::class)->getUser($id);
        $user->profile_picture_id = $picture->id;
        $user->save();
    }
}
