<?php

namespace Database\Factories;

use App\Models\UserProfilePicture;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfilePictureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = UserProfilePicture::class;

    public function definition()
    {
        return [
            'user_id' => 1,
            'file_id' => 1
        ];
    }
}
