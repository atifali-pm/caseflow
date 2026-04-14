<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Invoice> */
class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(InvoiceStatus::cases());
        $issuedAt = fake()->dateTimeBetween('-3 months', 'now');

        return [
            'provider_id' => User::factory(),
            'client_id' => Client::factory(),
            'status' => $status,
            'issued_at' => $issuedAt,
            'due_at' => (clone $issuedAt)->modify('+30 days'),
            'sent_at' => in_array($status, [InvoiceStatus::Sent, InvoiceStatus::Paid, InvoiceStatus::Overdue]) ? $issuedAt : null,
            'paid_at' => $status === InvoiceStatus::Paid ? fake()->dateTimeBetween($issuedAt, 'now') : null,
            'subtotal' => 0,
            'tax' => 0,
            'total' => 0,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
