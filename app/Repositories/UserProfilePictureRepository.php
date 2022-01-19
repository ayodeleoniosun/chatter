<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Enums\FileType;
use App\Models\{File, User, UserProfilePicture};
class UserProfilePictureRepository
{
    private UserProfilePicture $picture;

    public function __construct(UserProfilePicture $picture)
    {
        $this->picture = $picture;
    }

    public function save(string $filename, int $id): User
    {
        $file = app(FileRepository::class)->create([
            'filename'  => $filename,
            'type'      => FileType::PROFILE_PICTURE,
            'object_id' => $id
        ]);

        $data = [
            'user_id' => $id,
            'file_id' => $file->id
        ];

        $picture = $this->picture->create($data);

        $user = app(UserRepository::class)->getUser($id);
        $user->profile_picture_id = $picture->id;
        $user->save();

        return $user->fresh();
    }
}
