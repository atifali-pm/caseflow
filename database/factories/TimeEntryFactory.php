<?php

namespace Database\Factories;

use App\Models\CaseRecord;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<TimeEntry> */
class TimeEntryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'provider_id' => User::factory(),
            'case_record_id' => CaseRecord::factory(),
            'user_id' => User::factory(),
            'started_at' => fake()->dateTimeBetween('-2 months', 'now'),
            'duration_minutes' => fake()->numberBetween(15, 240),
            'description' => fake()->sentence(),
            'billable' => fake()->boolean(85),
            'hourly_rate' => fake()->randomElement([100, 125, 150, 175, 200, 250]),
        ];
    }
}
