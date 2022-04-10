<?php

namespace Database\Factories;

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
            'expires_at' => $this->faker->dateTime(),
        ];
    }

    /**
     * Indicate that a token has been used
     */
    public function used(): Factory
    {
        return $this->state(function () {
            return [
                'used' => true,
            ];
        });
    }
}
