<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\CaseRecord;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Task> */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(TaskStatus::cases());

        return [
            'provider_id' => User::factory(),
            'case_record_id' => CaseRecord::factory(),
            'assigned_to' => User::factory(),
            'title' => fake()->randomElement([
                'Review contract draft', 'File court motion', 'Schedule client meeting',
                'Prepare deposition', 'Send invoice', 'Follow up with witness',
                'Update case notes', 'Research precedent', 'Draft settlement offer',
                'Submit discovery request', 'Confirm hearing date', 'Email opposing counsel',
            ]),
            'description' => fake()->optional()->sentence(),
            'status' => $status,
            'due_date' => fake()->optional(0.8)->dateTimeBetween('-1 week', '+1 month'),
            'completed_at' => $status === TaskStatus::Done ? fake()->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
