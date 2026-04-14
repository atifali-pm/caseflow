<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Models\CaseMilestone;
use App\Models\CaseNote;
use App\Models\CaseRecord;
use App\Models\Client;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use App\Models\Message;
use App\Models\Scopes\ProviderScope;
use App\Models\Tag;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@caseflow.test',
        ]);

        $providers = User::factory()->provider()->count(3)->sequence(
            ['name' => 'Sarah Johnson', 'email' => 'sarah@caseflow.test', 'default_hourly_rate' => 200, 'currency' => 'USD'],
            ['name' => 'Michael Chen', 'email' => 'michael@caseflow.test', 'default_hourly_rate' => 175, 'currency' => 'USD'],
            ['name' => 'Amy Rodriguez', 'email' => 'amy@caseflow.test', 'default_hourly_rate' => 150, 'currency' => 'USD'],
        )->create();

        foreach ($providers as $provider) {
            $tagNames = ['VIP', 'Pro Bono', 'Litigation', 'Settlement', 'Urgent', 'New', 'Follow-up', 'Review'];
            $tagColors = ['gray', 'success', 'warning', 'danger', 'info', 'primary', 'success', 'info'];
            $tags = collect();
            foreach ($tagNames as $i => $name) {
                $tags->push(Tag::create([
                    'provider_id' => $provider->id,
                    'name' => $name,
                    'color' => $tagColors[$i],
                ]));
            }

            $clients = Client::factory()
                ->count(fake()->numberBetween(5, 12))
                ->for($provider, 'provider')
                ->create(['provider_id' => $provider->id]);

            foreach ($clients as $client) {
                $caseCount = fake()->numberBetween(1, 4);

                for ($i = 0; $i < $caseCount; $i++) {
                    $case = CaseRecord::factory()->create([
                        'provider_id' => $provider->id,
                        'client_id' => $client->id,
                    ]);

                    $case->tags()->attach($tags->random(fake()->numberBetween(0, 3))->pluck('id'));

                    CaseMilestone::factory()
                        ->count(fake()->numberBetween(2, 5))
                        ->sequence(fn ($seq) => ['sort_order' => $seq->index])
                        ->create(['case_record_id' => $case->id]);

                    Task::factory()
                        ->count(fake()->numberBetween(2, 6))
                        ->create([
                            'provider_id' => $provider->id,
                            'case_record_id' => $case->id,
                            'assigned_to' => $provider->id,
                        ]);

                    CaseNote::factory()
                        ->count(fake()->numberBetween(1, 4))
                        ->create([
                            'case_record_id' => $case->id,
                            'user_id' => $provider->id,
                        ]);

                    Document::factory()
                        ->count(fake()->numberBetween(0, 3))
                        ->create([
                            'documentable_type' => CaseRecord::class,
                            'documentable_id' => $case->id,
                            'uploaded_by' => $provider->id,
                        ]);

                    $messageCount = fake()->numberBetween(2, 8);
                    for ($m = 0; $m < $messageCount; $m++) {
                        Message::factory()->create([
                            'case_record_id' => $case->id,
                            'sender_id' => $provider->id,
                            'read_at' => fake()->optional(0.7)->dateTimeBetween('-1 month', 'now'),
                        ]);
                    }

                    TimeEntry::factory()
                        ->count(fake()->numberBetween(2, 8))
                        ->create([
                            'provider_id' => $provider->id,
                            'case_record_id' => $case->id,
                            'user_id' => $provider->id,
                            'hourly_rate' => $provider->default_hourly_rate,
                        ]);

                    Expense::factory()
                        ->count(fake()->numberBetween(0, 4))
                        ->create([
                            'provider_id' => $provider->id,
                            'case_record_id' => $case->id,
                            'user_id' => $provider->id,
                        ]);
                }

                $invoiceCount = fake()->numberBetween(0, 3);
                for ($n = 0; $n < $invoiceCount; $n++) {
                    $invoice = Invoice::factory()->create([
                        'provider_id' => $provider->id,
                        'client_id' => $client->id,
                    ]);

                    $itemCount = fake()->numberBetween(1, 5);
                    for ($k = 0; $k < $itemCount; $k++) {
                        $qty = fake()->randomFloat(2, 0.5, 8);
                        $rate = fake()->randomElement([100, 150, 200, 250]);
                        InvoiceLineItem::create([
                            'invoice_id' => $invoice->id,
                            'type' => 'manual',
                            'description' => fake()->randomElement([
                                'Legal consultation', 'Document review', 'Court appearance',
                                'Research and analysis', 'Drafting agreement', 'Client meeting',
                            ]),
                            'quantity' => $qty,
                            'rate' => $rate,
                            'amount' => round($qty * $rate, 2),
                        ]);
                    }

                    $invoice->refresh()->recalculate();
                }
            }
        }

        $demoClient = Client::withoutGlobalScope(ProviderScope::class)->first();
        $demoClient->update(['email' => 'client@caseflow.test']);
        $demoClientUser = User::factory()->client($demoClient->provider_id)->create([
            'name' => $demoClient->full_name,
            'email' => 'client@caseflow.test',
        ]);
        $demoClient->update(['user_id' => $demoClientUser->id]);

        $this->command->info('');
        $this->command->info('Demo accounts created:');
        $this->command->info('Admin:    admin@caseflow.test    / password');
        $this->command->info('Provider: sarah@caseflow.test    / password');
        $this->command->info('Client:   client@caseflow.test   / password');
    }
}
