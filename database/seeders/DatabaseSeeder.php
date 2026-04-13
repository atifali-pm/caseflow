<?php

namespace Database\Seeders;

use App\Enums\CaseStage;
use App\Enums\CaseStatus;
use App\Models\CaseMilestone;
use App\Models\CaseRecord;
use App\Models\Client;
use App\Models\Document;
use App\Models\Message;
use App\Models\Scopes\ProviderScope;
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
            ['name' => 'Sarah Johnson', 'email' => 'sarah@caseflow.test'],
            ['name' => 'Michael Chen', 'email' => 'michael@caseflow.test'],
            ['name' => 'Amy Rodriguez', 'email' => 'amy@caseflow.test'],
        )->create();

        foreach ($providers as $provider) {
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

                    CaseMilestone::factory()
                        ->count(fake()->numberBetween(2, 5))
                        ->sequence(fn ($seq) => ['sort_order' => $seq->index])
                        ->create(['case_record_id' => $case->id]);

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
                }
            }
        }

        $demoClient = Client::withoutGlobalScope(ProviderScope::class)->first();
        $demoClientUser = User::factory()->client($demoClient->provider_id)->create([
            'name' => $demoClient->full_name,
            'email' => $demoClient->email,
        ]);
        $demoClient->update(['user_id' => $demoClientUser->id]);

        $this->command->info('');
        $this->command->info('Demo accounts created:');
        $this->command->info('Admin:    admin@caseflow.test    / password');
        $this->command->info('Provider: sarah@caseflow.test    / password');
        $this->command->info('Client:   ' . $demoClient->email . ' / password');
    }
}
