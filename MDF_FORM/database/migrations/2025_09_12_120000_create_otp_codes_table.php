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
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();

            // Optional link to users; OTP can be requested by email even before a user exists
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Target identity
            $table->string('email')->index();

            // 6-digit OTP code (or similar)
            $table->string('code');

            // One-time link token used to open the verify page
            $table->string('link_token')->unique();

            // Expiration and usage tracking
            $table->timestamp('expires_at')->index();
            $table->timestamp('used_at')->nullable();

            $table->timestamps();

            // Optional: prevent multiple active tokens for same email (soft uniqueness by app logic)
            // You can add a partial index in DBMS that supports it; otherwise enforce in application logic.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
