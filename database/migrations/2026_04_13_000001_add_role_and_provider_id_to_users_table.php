<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('provider')->after('name');
            $table->foreignId('provider_id')->nullable()->after('role')->constrained('users')->nullOnDelete();

            $table->index('role');
            $table->index('provider_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['provider_id']);
            $table->dropIndex(['provider_id']);
            $table->dropIndex(['role']);
            $table->dropColumn(['role', 'provider_id']);
        });
    }
};
