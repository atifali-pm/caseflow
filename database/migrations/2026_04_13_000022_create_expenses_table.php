<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('case_record_id')->constrained('case_records')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->date('incurred_on');
            $table->boolean('billable')->default(true);
            $table->foreignId('invoice_id')->nullable();
            $table->timestamps();

            $table->index(['provider_id', 'case_record_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
