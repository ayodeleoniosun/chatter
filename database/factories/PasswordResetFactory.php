<?php

namespace Database\Factories;

use App\Models\PasswordReset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PasswordResetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email'      => $this->faker->email,
            'token'      => bcrypt(Str::random(10)),
            'expires_at' => $this->faker->dateTime()
        ];
    }
}
