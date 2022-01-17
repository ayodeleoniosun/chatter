<?php

namespace App\Services;

use App\Http\Resources\UserResource;
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

    public function updateProfile(array $data, int $id): UserResource
    {
        $userExists = $this->userRepository->getDuplicateUserByPhoneNumber($data['phone_number'], $id);

        if ($userExists) {
            abort(403, 'Phone number belongs to another user');
        }

        return new UserResource($this->userRepository->updateProfile($data, $id));
    }

    public function profile(int $id): UserResource
    {
        return new UserResource($this->userRepository->getUser($id));
    }

    public function updateProfilePicture(object $image, int $id): array
    {
        $filename = time().'.'.$image->extension();
        Storage::disk('s3')->put($filename, file_get_contents($image->getRealPath()));
        $this->userRepository->updateProfilePicture($filename, $id);
        return ['filename' => $filename];
    }

    public function updatePassword(array $data, int $id): User
    {
        return $this->userRepository->updatePassword($data, $id);
    }

    public function inviteUser(string $emailAddress, int $id)
    {
    }
}
