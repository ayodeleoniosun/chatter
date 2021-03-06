<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'invited_by' => 1,
            'invitee'    => $this->faker->email,
            'token'      => bcrypt(Str::random(10)),
        ];
    }
}
