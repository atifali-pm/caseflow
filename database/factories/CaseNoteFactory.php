<?php

namespace Database\Factories;

use App\Models\CaseNote;
use App\Models\CaseRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CaseNote> */
class CaseNoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'case_record_id' => CaseRecord::factory(),
            'user_id' => User::factory(),
            'body' => fake()->paragraph(),
        ];
    }
}
