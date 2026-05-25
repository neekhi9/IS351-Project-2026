<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Mockery;

class RegistrationNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure required reference data exists for validation rules
        DB::table('cities')->insert([
            ['id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('regions')->insert([
            ['id' => 1, 'created_at' => now(), 'updated_at' => now()], // Central
            ['id' => 2, 'created_at' => now(), 'updated_at' => now()], // Eastern
            ['id' => 3, 'created_at' => now(), 'updated_at' => now()], // Western
            ['id' => 4, 'created_at' => now(), 'updated_at' => now()], // Northern
        ]);

        // Set fallback admin email via mail.from.address if ADMIN_EMAIL not present
        config(['mail.from.address' => 'neekhilkissun@gmail.com']);
        config(['mail.from.name' => 'Energy Licensing Portal']);
    }

    public function test_company_registration_sends_notifications_and_persists_data()
    {
        Storage::fake('public');

        // Expect two log-only emails via PHPMailer service: one to admin and one to registrant
        $expectedAdmin = env('ADMIN_EMAIL', config('mail.from.address'));
        Log::shouldReceive('info')->once()->with(
            Mockery::on(fn($msg) => str_contains($msg, 'PHPMailerService LOG-ONLY email')),
            Mockery::on(function ($context) use ($expectedAdmin) {
                return isset($context['to']) && $context['to'] === $expectedAdmin;
            })
        );
        Log::shouldReceive('info')->once()->with(
            Mockery::on(fn($msg) => str_contains($msg, 'PHPMailerService LOG-ONLY email')),
            Mockery::on(function ($context) {
                return isset($context['to']) && $context['to'] === 'company@example.com';
            })
        );

        $payload = [
            'account_type' => 'company',

            'organization_name' => 'Acme Power',
            'organization_type' => 'Limited Liability',
            'designation_business' => 'ABC123',
            'title' => 'Mr',
            'first_name' => 'John',
            'surname' => 'Doe',
            'address' => 'Building 1',
            'street' => 'Main St',
            'suburb' => 'CBD',
            'city' => 1,
            'region' => 1,
            'office_phone' => '1234567',
            'mobile_phone' => '7654321',
            'email' => 'company@example.com',
            'alt_email' => 'alt@example.com',

            'directors' => ['Director One', 'Director Two'],

            'com_reg_num' => '123456789',
            'tin_number' => '987654321',
            'wireman_l_num' => ['WM001', 'WM002'],
        ];

        // Files
        $files = [
            'roc_file' => UploadedFile::fake()->create('roc.pdf', 12, 'application/pdf'),
            'tin_letter' => UploadedFile::fake()->create('tin.pdf', 12, 'application/pdf'),
            'wireman_license' => [
                UploadedFile::fake()->create('wm1.pdf', 10, 'application/pdf'),
                UploadedFile::fake()->create('wm2.pdf', 10, 'application/pdf'),
            ],
        ];

        $response = $this->post(route('registration.store'), array_merge($payload, $files));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // DB assertions
        $this->assertDatabaseHas('registrations', [
            'account_type' => 'company',
            'organization_name' => 'Acme Power',
            'email' => 'company@example.com',
            'alt_email' => 'alt@example.com',
            'city_id' => 1,
            'region_id' => 1,
        ]);

        // Find created registration
        $registration = DB::table('registrations')
            ->where('email', 'company@example.com')
            ->first();
        $this->assertNotNull($registration);

        // Directors created
        $this->assertDatabaseHas('directors', [
            'registration_id' => $registration->id,
            'name' => 'Director One',
        ]);
        $this->assertDatabaseHas('directors', [
            'registration_id' => $registration->id,
            'name' => 'Director Two',
        ]);

        // Wireman licenses created
        $this->assertDatabaseHas('wireman_licenses', [
            'registration_id' => $registration->id,
            'license_number' => 'WM001',
        ]);
        $this->assertDatabaseHas('wireman_licenses', [
            'registration_id' => $registration->id,
            'license_number' => 'WM002',
        ]);

        // Email logs asserted via Log::shouldReceive above
    }

    public function test_individual_registration_sends_notifications_and_persists_data()
    {
        Storage::fake('public');

        // Expect two log-only emails via PHPMailer service: one to admin and one to registrant
        $expectedAdmin = env('ADMIN_EMAIL', config('mail.from.address'));
        Log::shouldReceive('info')->once()->with(
            Mockery::on(fn($msg) => str_contains($msg, 'PHPMailerService LOG-ONLY email')),
            Mockery::on(function ($context) use ($expectedAdmin) {
                return isset($context['to']) && $context['to'] === $expectedAdmin;
            })
        );
        Log::shouldReceive('info')->once()->with(
            Mockery::on(fn($msg) => str_contains($msg, 'PHPMailerService LOG-ONLY email')),
            Mockery::on(function ($context) {
                return isset($context['to']) && $context['to'] === 'individual@example.com';
            })
        );

        $payload = [
            'account_type' => 'individual',

            'title_ind' => 'Ms',
            'first_name_ind' => 'Jane',
            'surname_ind' => 'Smith',
            'address_ind' => 'Lot 2',
            'street_ind' => 'High St',
            'suburb_ind' => 'Suburbia',
            'city_ind' => 2,
            'region_ind' => 3, // Western
            'office_phone_ind' => '2233445',
            'mobile_phone_ind' => '5544332',
            'individualEmail' => 'individual@example.com',
            'alt_email_ind' => 'alt2@example.com',

            'tin_number_ind' => '111222333',
            'wireman_l_num_ind' => 'WMIND001',
        ];

        // Files
        $files = [
            'tin_letter_ind' => UploadedFile::fake()->create('tin_ind.pdf', 12, 'application/pdf'),
            'wireman_license_ind' => UploadedFile::fake()->create('wm_ind.pdf', 10, 'application/pdf'),
        ];

        $response = $this->post(route('registration.store'), array_merge($payload, $files));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // DB assertions
        $this->assertDatabaseHas('registrations', [
            'account_type' => 'individual',
            'email' => 'individual@example.com',
            'alt_email' => 'alt2@example.com',
            'city_id' => 2,
            'region_id' => 3,
        ]);

        $registration = DB::table('registrations')
            ->where('email', 'individual@example.com')
            ->first();
        $this->assertNotNull($registration);

        // Individual wireman license created
        $this->assertDatabaseHas('wireman_licenses', [
            'registration_id' => $registration->id,
            'license_number' => 'WMIND001',
        ]);

        // Email logs asserted via Log::shouldReceive above
    }
}
