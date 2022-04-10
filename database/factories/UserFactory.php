<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name'    => $this->faker->name(),
            'last_name'     => $this->faker->name(),
            'email_address' => $this->faker->unique()->safeEmail,
            'phone_number'  => Str::random(11),
            'password'      => bcrypt('12345678'),
        ];
    }
}
