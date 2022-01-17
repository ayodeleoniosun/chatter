<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function updateProfile(array $data, int $userId): User
    {
        $userExists = $this->userRepository->getDuplicateUserByPhoneNumber($data['phone_number'], $userId);

        if ($userExists) {
            abort(403, __('Phone number belongs to another user'));
        }

        return $this->userRepository->updateProfile($data, $userId);
    }

    public function updateProfilePicture(object $image, int $userId): array
    {
        $filename = time().'.'.$image->extension();
        Storage::disk('s3')->put($filename, file_get_contents($image->getRealPath()));
        $this->userRepository->updateProfilePicture($filename, $userId);
        return ['filename' => $filename];
    }

    public function updatePassword(array $data, int $userId): User
    {
        return $this->userRepository->updatePassword($data, $userId);
    }
}
