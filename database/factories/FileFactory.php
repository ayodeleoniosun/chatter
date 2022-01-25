<?php

namespace Database\Factories;

use App\Enums\FileType;
use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'filename'  => $this->faker->file,
            'type'      => FileType::PROFILE_PICTURE,
            'object_id' => 1
        ];
    }
}
