<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use InvalidArgumentException;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        if ($this->userRepository->getUserByPhoneNumber($data['phone_number'])) {
            abort(400, __('User exists.'));
        }
        
        return $this->userRepository->save($data);
    }
}
