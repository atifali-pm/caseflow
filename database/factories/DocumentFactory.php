<?php

namespace Database\Factories;

use App\Models\CaseRecord;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Document> */
class DocumentFactory extends Factory
{
    public function definition(): array
    {
        $filename = fake()->randomElement([
            'contract', 'agreement', 'filing', 'motion', 'affidavit',
            'deposition', 'exhibit', 'correspondence', 'invoice', 'receipt',
        ]) . '_' . fake()->numerify('###') . '.' . fake()->randomElement(['pdf', 'docx', 'jpg', 'png']);

        return [
            'documentable_type' => CaseRecord::class,
            'documentable_id' => CaseRecord::factory(),
            'filename' => $filename,
            'path' => 'documents/' . $filename,
            'disk' => 'local',
            'mime_type' => fake()->mimeType(),
            'size' => fake()->numberBetween(10240, 5242880),
            'uploaded_by' => User::factory(),
        ];
    }
}
