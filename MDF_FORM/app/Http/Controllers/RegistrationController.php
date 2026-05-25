<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Director;
use App\Models\WiremanLicense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Mail\PHPMailerService;

class RegistrationController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create()
    {
        return view('registration');
    }

    /**
     * Store a newly created registration.
     */
    public function store(RegistrationRequest $request)
    {
        // The request has already been validated at this point
        try {
            // Build data for Registration model
            $data = $request->account_type === 'company'
                ? $this->processCompanyRegistration($request)
                : $this->processIndividualRegistration($request);

            DB::beginTransaction();

            // Handle top-level file uploads and set paths on $data
            if ($request->account_type === 'company') {
                if ($request->hasFile('roc_file')) {
                    $file = $request->file('roc_file');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $stored = $file->storeAs('public/roc_certificates', $fileName);
                    $data['roc_file_path'] = Storage::url($stored);
                }
                if ($request->hasFile('tin_letter')) {
                    $file = $request->file('tin_letter');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $stored = $file->storeAs('public/tin_letters', $fileName);
                    $data['tin_letter_path'] = Storage::url($stored);
                }
            } else {
                if ($request->hasFile('tin_letter_ind')) {
                    $file = $request->file('tin_letter_ind');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $stored = $file->storeAs('public/tin_letters', $fileName);
                    $data['tin_letter_path'] = Storage::url($stored);
                }
            }

            // Create the registration record
            $registration = Registration::create($data);

            // Related data persistence
            if ($request->account_type === 'company') {
                // Directors
                foreach ((array) $request->input('directors', []) as $name) {
                    if ($name !== null && $name !== '') {
                        Director::create([
                            'registration_id' => $registration->id,
                            'name' => $name,
                            'created_at' => now(),
                        ]);
                    }
                }

                // Wireman licenses for company (numbers + files aligned by index)
                $numbers = (array) $request->input('wireman_l_num', []);
                $files = (array) $request->file('wireman_license', []);
                foreach ($numbers as $idx => $number) {
                    $filePath = null;
                    if (isset($files[$idx]) && $files[$idx] && $files[$idx]->isValid()) {
                        $file = $files[$idx];
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $stored = $file->storeAs('public/wireman_licenses', $fileName);
                        $filePath = Storage::url($stored);
                    }
                    WiremanLicense::create([
                        'registration_id' => $registration->id,
                        'license_number' => $number,
                        'license_file_path' => $filePath,
                        'created_at' => now(),
                    ]);
                }
            } else {
                // Individual wireman license
                $filePath = null;
                if ($request->hasFile('wireman_license_ind')) {
                    $file = $request->file('wireman_license_ind');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $stored = $file->storeAs('public/wireman_licenses', $fileName);
                    $filePath = Storage::url($stored);
                    $registration->wireman_license_ind_path = $filePath;
                }
                if ($request->filled('wireman_l_num_ind')) {
                    $registration->wireman_l_num_ind = $request->wireman_l_num_ind;
                    WiremanLicense::create([
                        'registration_id' => $registration->id,
                        'license_number' => $request->wireman_l_num_ind,
                        'license_file_path' => $filePath,
                        'created_at' => now(),
                    ]);
                }
                $registration->save();
            }

            DB::commit();

            // Send emails using PHPMailer: to admin and to registrant
            try {
                $mailer = app(PHPMailerService::class);
                // Admin notification (ADMIN_EMAIL or fallback to mail.from.address handled in service)
                $mailer->sendAdminNotification($registration);
                // Registrant confirmation
                $mailer->sendRegistrantConfirmation($registration);
            } catch (\Throwable $mailEx) {
                // Do not block user flow on mail failures
                // Optionally log: \Log::error('PHPMailer send failed', ['error' => $mailEx->getMessage()]);
            }

            return redirect()->back()->with('success', 'Registration submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'An error occurred while processing your registration: ' . $e->getMessage());
        }
    }
    
    protected function processCompanyRegistration(Request $request)
    {
        // Build company registration payload matching DB schema
        return [
            'account_type' => 'company',
            'organization_name' => $request->organization_name,
            'organization_type' => $request->organization_type,
            'designation_business' => $request->designation_business,
            'title' => $request->title,
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'address' => $request->address,
            'street' => $request->street,
            'suburb' => $request->suburb,
            'city_id' => (int) $request->city,
            'region_id' => (int) $request->region,
            'office_phone' => $request->office_phone,
            'mobile_phone' => $request->mobile_phone,
            'email' => $request->email,
            'alt_email' => $request->alt_email,
            'com_reg_num' => $request->com_reg_num,
            'tin_number' => $request->tin_number,
        ];
    }
    
    protected function processIndividualRegistration(Request $request)
    {
        // Build individual registration payload matching DB schema
        return [
            'account_type' => 'individual',
            'title' => $request->title_ind,
            'first_name' => $request->first_name_ind,
            'surname' => $request->surname_ind,
            'address' => $request->address_ind,
            'street' => $request->street_ind,
            'suburb' => $request->suburb_ind,
            'city_id' => (int) $request->city_ind,
            'region_id' => (int) $request->region_ind,
            'office_phone' => $request->office_phone_ind,
            'mobile_phone' => $request->mobile_phone_ind,
            'email' => $request->individualEmail,
            'alt_email' => $request->alt_email_ind,
            'tin_number' => $request->tin_number_ind,
        ];
    }
    
    public function show(Registration $registration)
    {
        // Load relations to display human-friendly names and related lists
        $registration->loadMissing(['city', 'region', 'directors', 'wiremanLicenses']);

        return view('registration_show', compact('registration'));
    }

    protected function handleFileUploads(Request $request)
    {
        // Handle file uploads for both company and individual
        $fileFields = [];
        
        if ($request->account_type === 'company') {
            $fileFields = [
                'roc_file' => 'roc_certificates',
                'tin_letter' => 'tin_letters',
                'wireman_license' => 'wireman_licenses',
            ];
        } else {
            $fileFields = [
                'tin_letter_ind' => 'tin_letters',
                'wireman_license_ind' => 'wireman_licenses',
            ];
        }
        
        foreach ($fileFields as $field => $folder) {
            if ($request->hasFile($field)) {
                if (is_array($request->file($field))) {
                    // Handle multiple file uploads
                    foreach ($request->file($field) as $file) {
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->storeAs("public/{$folder}", $fileName);
                        // Save file info to database
                    }
                } else {
                    // Handle single file upload
                    $file = $request->file($field);
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs("public/{$folder}", $fileName);
                    // Save file info to database
                }
            }
        }
    }

    /**
     * Show the authenticated user's submissions and their statuses.
     */
    public function mySubmissions()
    {
        $user = auth()->user();
        $registrations = Registration::query()
            ->where('email', $user->email)
            ->latest()
            ->get();

        return view('registration_my', compact('registrations'));
    }
}
