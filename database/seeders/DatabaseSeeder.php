<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@caseflow.test',
        ]);

        User::factory()->provider()->create([
            'name' => 'Demo Provider',
            'email' => 'provider@caseflow.test',
        ]);
    }
}
