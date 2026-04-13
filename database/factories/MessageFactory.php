<?php

namespace Database\Factories;

use App\Models\CaseRecord;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Message> */
class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'case_record_id' => CaseRecord::factory(),
            'sender_id' => User::factory(),
            'body' => fake()->paragraph(),
            'read_at' => fake()->optional(0.6)->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
