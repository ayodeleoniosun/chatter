<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name'    => 'chatter',
            'last_name'     => 'app',
            'email_address' => $this->faker->email,
            'phone_number'  => Str::random(11),
            'password'      => bcrypt('12345678')
        ];
    }
}
