<?php

namespace Database\Factories;

use App\Models\CaseRecord;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Expense> */
class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'provider_id' => User::factory(),
            'case_record_id' => CaseRecord::factory(),
            'user_id' => User::factory(),
            'amount' => fake()->randomFloat(2, 5, 500),
            'description' => fake()->randomElement([
                'Court filing fee', 'Postage', 'Photocopies', 'Travel',
                'Expert witness fee', 'Notary fee', 'Records request',
                'Process server', 'Court reporter', 'Mediator fee',
            ]),
            'incurred_on' => fake()->dateTimeBetween('-2 months', 'now'),
            'billable' => fake()->boolean(80),
        ];
    }
}
