<?php

namespace Database\Factories;

use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Invitation::class;

    public function definition()
    {
        return [
            'invited_by' => 1,
            'invitee' => $this->faker->email,
            'token' => bcrypt(Str::random(10))
        ];
    }
}
