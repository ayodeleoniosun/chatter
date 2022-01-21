<?php

namespace Database\Factories;

use App\Enums\ChatType;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Chat::class;

    public function definition()
    {
        return [
            'sender_id' => 1,
            'recipient_id' => 2,
            'content' => $this->faker->word,
            'content_type' => ChatType::TEXT
        ];
    }
}
