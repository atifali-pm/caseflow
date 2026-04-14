<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('case_record_id')->constrained('case_records')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('started_at');
            $table->integer('duration_minutes');
            $table->text('description')->nullable();
            $table->boolean('billable')->default(true);
            $table->decimal('hourly_rate', 8, 2);
            $table->foreignId('invoice_id')->nullable();
            $table->timestamps();

            $table->index(['provider_id', 'case_record_id']);
            $table->index('billable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};
