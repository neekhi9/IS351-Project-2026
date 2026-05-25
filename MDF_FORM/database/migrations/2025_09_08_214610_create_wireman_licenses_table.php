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
        // Create table if it doesn't exist; otherwise add missing columns idempotently.
        if (!Schema::hasTable('wireman_licenses')) {
            Schema::create('wireman_licenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
                $table->string('license_number')->nullable();
                $table->string('license_file_path')->nullable();
                $table->timestamps();
            });
            return;
        }

        if (!Schema::hasColumn('wireman_licenses', 'registration_id')) {
            Schema::table('wireman_licenses', function (Blueprint $table) {
                $table->foreignId('registration_id')->after('id')->constrained('registrations')->cascadeOnDelete();
            });
        }
        if (!Schema::hasColumn('wireman_licenses', 'license_number')) {
            Schema::table('wireman_licenses', function (Blueprint $table) {
                $table->string('license_number')->nullable()->after('registration_id');
            });
        }
        if (!Schema::hasColumn('wireman_licenses', 'license_file_path')) {
            Schema::table('wireman_licenses', function (Blueprint $table) {
                $table->string('license_file_path')->nullable()->after('license_number');
            });
        }
        // Ensure timestamps exist
        if (!Schema::hasColumn('wireman_licenses', 'created_at') && !Schema::hasColumn('wireman_licenses', 'updated_at')) {
            Schema::table('wireman_licenses', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wireman_licenses');
    }
};
