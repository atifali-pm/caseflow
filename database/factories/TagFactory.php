<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Tag> */
class TagFactory extends Factory
{
    public function definition(): array
    {
        return [
            'provider_id' => User::factory(),
            'name' => fake()->randomElement(['VIP', 'Pro Bono', 'Litigation', 'Settlement', 'Urgent', 'New', 'Follow-up', 'Review']),
            'color' => fake()->randomElement(['gray', 'success', 'warning', 'danger', 'info', 'primary']),
        ];
    }
}
