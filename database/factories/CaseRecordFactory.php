<?php

namespace Database\Factories;

use App\Enums\CasePriority;
use App\Enums\CaseStage;
use App\Enums\CaseStatus;
use App\Models\CaseRecord;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CaseRecord> */
class CaseRecordFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(CaseStatus::cases());
        $openedAt = fake()->dateTimeBetween('-6 months', '-1 week');

        return [
            'provider_id' => User::factory(),
            'client_id' => Client::factory(),
            'title' => fake()->randomElement([
                'Initial Consultation', 'Contract Review', 'Property Settlement',
                'Custody Arrangement', 'Document Filing', 'Mediation Prep',
                'Court Appearance', 'Asset Division', 'Support Agreement',
                'Emergency Motion', 'Discovery Review', 'Settlement Negotiation',
            ]) . ' - ' . fake()->lastName(),
            'description' => fake()->paragraph(),
            'status' => $status,
            'stage' => fake()->randomElement(CaseStage::cases()),
            'priority' => fake()->randomElement(CasePriority::cases()),
            'opened_at' => $openedAt,
            'closed_at' => $status === CaseStatus::Closed ? fake()->dateTimeBetween($openedAt, 'now') : null,
            'due_date' => fake()->optional(0.7)->dateTimeBetween('now', '+3 months'),
        ];
    }
}
