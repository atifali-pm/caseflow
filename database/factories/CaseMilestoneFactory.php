<?php

namespace Database\Factories;

use App\Models\CaseMilestone;
use App\Models\CaseRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CaseMilestone> */
class CaseMilestoneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'case_record_id' => CaseRecord::factory(),
            'title' => fake()->randomElement([
                'Initial Consultation', 'Document Collection', 'Filing Deadline',
                'Discovery Phase', 'Mediation Session', 'Court Hearing',
                'Settlement Review', 'Final Agreement', 'Case Closure',
                'Client Follow-up', 'Expert Consultation', 'Evidence Review',
            ]),
            'description' => fake()->optional()->sentence(),
            'due_date' => fake()->optional(0.7)->dateTimeBetween('now', '+2 months'),
            'completed_at' => fake()->optional(0.4)->dateTimeBetween('-2 months', 'now'),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
