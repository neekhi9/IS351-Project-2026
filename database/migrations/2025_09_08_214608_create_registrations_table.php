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
        // If table doesn't exist, create with full schema
        if (!Schema::hasTable('registrations')) {
            Schema::create('registrations', function (Blueprint $table) {
                $table->id();

                // Applicant core fields
                $table->string('account_type'); // 'company' or 'individual'
                $table->string('organization_name')->nullable();
                $table->string('organization_type')->nullable();
                $table->string('designation_business')->nullable();
                $table->string('com_reg_num')->nullable();
                $table->string('tin_number')->nullable();

                $table->string('title')->nullable();
                $table->string('first_name')->nullable();
                $table->string('surname')->nullable();
                $table->string('address')->nullable();
                $table->string('street')->nullable();
                $table->string('suburb')->nullable();

                $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
                $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();

                $table->string('office_phone')->nullable();
                $table->string('mobile_phone')->nullable();
                $table->string('email')->index();
                $table->string('alt_email')->nullable();

                // File paths
                $table->string('roc_file_path')->nullable();
                $table->string('tin_letter_path')->nullable();

                // Individual wireman fields
                $table->string('wireman_l_num_ind')->nullable();
                $table->string('wireman_license_ind_path')->nullable();

                // Review workflow fields
                $table->string('status')->default('pending')->index(); // pending|approved|declined|invalid
                $table->text('admin_comments')->nullable();
                $table->json('invalid_fields')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();

                // Resubmission
                $table->string('resubmission_token')->nullable()->unique();
                $table->timestamp('resubmission_expires_at')->nullable();

                $table->timestamps();
            });
            return;
        }

        // Otherwise, table exists: add missing columns conditionally (idempotent)
        if (!Schema::hasColumn('registrations', 'account_type')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('account_type')->after('id');
            });
        }

        if (!Schema::hasColumn('registrations', 'organization_name')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('organization_name')->nullable()->after('account_type');
                $table->string('organization_type')->nullable()->after('organization_name');
                $table->string('designation_business')->nullable()->after('organization_type');
                $table->string('com_reg_num')->nullable()->after('designation_business');
                $table->string('tin_number')->nullable()->after('com_reg_num');
            });
        }

        if (!Schema::hasColumn('registrations', 'title')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('title')->nullable()->after('tin_number');
                $table->string('first_name')->nullable()->after('title');
                $table->string('surname')->nullable()->after('first_name');
                $table->string('address')->nullable()->after('surname');
                $table->string('street')->nullable()->after('address');
                $table->string('suburb')->nullable()->after('street');
            });
        }

        if (!Schema::hasColumn('registrations', 'city_id')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->foreignId('city_id')->nullable()->after('suburb')->constrained('cities')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('registrations', 'region_id')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->foreignId('region_id')->nullable()->after('city_id')->constrained('regions')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('registrations', 'office_phone')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('office_phone')->nullable()->after('region_id');
                $table->string('mobile_phone')->nullable()->after('office_phone');
                $table->string('email')->nullable()->after('mobile_phone')->index();
                $table->string('alt_email')->nullable()->after('email');
            });
        }

        if (!Schema::hasColumn('registrations', 'roc_file_path')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('roc_file_path')->nullable()->after('alt_email');
                $table->string('tin_letter_path')->nullable()->after('roc_file_path');
            });
        }

        if (!Schema::hasColumn('registrations', 'wireman_l_num_ind')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('wireman_l_num_ind')->nullable()->after('tin_letter_path');
                $table->string('wireman_license_ind_path')->nullable()->after('wireman_l_num_ind');
            });
        }

        if (!Schema::hasColumn('registrations', 'status')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('status')->default('pending')->after('wireman_license_ind_path')->index();
            });
        }

        if (!Schema::hasColumn('registrations', 'admin_comments')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->text('admin_comments')->nullable()->after('status');
            });
        }

        if (!Schema::hasColumn('registrations', 'invalid_fields')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->json('invalid_fields')->nullable()->after('admin_comments');
            });
        }

        if (!Schema::hasColumn('registrations', 'reviewed_by')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->foreignId('reviewed_by')->nullable()->after('invalid_fields')->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            });
        } elseif (!Schema::hasColumn('registrations', 'reviewed_at')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            });
        }

        if (!Schema::hasColumn('registrations', 'resubmission_token')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('resubmission_token')->nullable()->unique()->after('reviewed_at');
                $table->timestamp('resubmission_expires_at')->nullable()->after('resubmission_token');
            });
        } elseif (!Schema::hasColumn('registrations', 'resubmission_expires_at')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->timestamp('resubmission_expires_at')->nullable()->after('resubmission_token');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
