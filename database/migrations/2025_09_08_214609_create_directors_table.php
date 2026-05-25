<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create table if it doesn't exist; otherwise, add any missing columns idempotently.
        if (!Schema::hasTable('directors')) {
            Schema::create('directors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
                $table->string('name');
                $table->timestamps();
            });
            return;
        }

        // Table exists: ensure required columns are present
        if (!Schema::hasColumn('directors', 'registration_id')) {
            Schema::table('directors', function (Blueprint $table) {
                $table->foreignId('registration_id')->after('id')->constrained('registrations')->cascadeOnDelete();
            });
        }

        if (!Schema::hasColumn('directors', 'name')) {
            Schema::table('directors', function (Blueprint $table) {
                $table->string('name')->after('registration_id');
            });
        }

        // Ensure timestamps exist (optional)
        if (!Schema::hasColumn('directors', 'created_at') && !Schema::hasColumn('directors', 'updated_at')) {
            Schema::table('directors', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directors');
    }
};
