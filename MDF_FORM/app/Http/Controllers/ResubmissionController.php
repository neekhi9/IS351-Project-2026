<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ResubmissionController extends Controller
{
    /**
     * Show the resubmission form for a given token.
     */
    public function edit(Request $request, string $token)
    {
        $registration = Registration::where('resubmission_token', $token)->first();

        if (!$registration) {
            abort(404, 'Invalid resubmission link.');
        }

        if ($registration->resubmission_expires_at && now()->greaterThan($registration->resubmission_expires_at)) {
            abort(410, 'This resubmission link has expired.');
        }

        // Ensure invalid_fields is an array
        $invalidFields = is_array($registration->invalid_fields) ? $registration->invalid_fields : [];

        // Load relations for display if needed
        $registration->loadMissing(['city', 'region', 'directors', 'wiremanLicenses']);

        return view('resubmission.edit', [
            'registration' => $registration,
            'invalidFields' => $invalidFields,
            'token' => $token,
        ]);
    }

    /**
     * Accept updates for invalid fields only and move status back to pending.
     */
    public function update(Request $request, string $token)
    {
        $registration = Registration::where('resubmission_token', $token)->first();

        if (!$registration) {
            return redirect()->route('welcome')->with('error', 'Invalid resubmission link.');
        }

        if ($registration->resubmission_expires_at && now()->greaterThan($registration->resubmission_expires_at)) {
            return redirect()->route('welcome')->with('error', 'This resubmission link has expired.');
        }

        $invalidFields = is_array($registration->invalid_fields) ? $registration->invalid_fields : [];

        if (empty($invalidFields)) {
            return redirect()->route('welcome')->with('error', 'No fields were marked invalid.');
        }

        // Build a minimal validation rule set for just the invalid fields.
        // Adjust as needed; here we add basic validation for common fields and allow files where applicable.
        $rules = [];
        foreach ($invalidFields as $field) {
            switch ($field) {
                case 'email':
                    $rules[$field] = 'required|email';
                    break;
                case 'mobile_phone':
                case 'office_phone':
                    $rules[$field] = 'nullable|string|max:50';
                    break;
                case 'tin_number':
                case 'com_reg_num':
                case 'organization_name':
                case 'organization_type':
                case 'designation_business':
                case 'title':
                case 'first_name':
                case 'surname':
                case 'address':
                case 'street':
                case 'suburb':
                    $rules[$field] = 'nullable|string|max:255';
                    break;
                case 'city_id':
                case 'region_id':
                    $rules[$field] = 'nullable|integer';
                    break;
                case 'roc_file_path':
                case 'tin_letter_path':
                case 'wireman_license_ind_path':
                    // For file path fields, expect a file upload input with mapped name
                    $rules[$field] = 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120';
                    break;
                case 'wireman_l_num_ind':
                    $rules[$field] = 'nullable|string|max:255';
                    break;
                default:
                    // By default allow string
                    $rules[$field] = 'nullable';
            }
        }

        $validated = $request->validate($rules);

        // Handle file uploads for specific file fields by mapping input name to storage
        $fileFields = [
            'roc_file_path' => 'roc_certificates',
            'tin_letter_path' => 'tin_letters',
            'wireman_license_ind_path' => 'wireman_licenses',
        ];

        foreach ($fileFields as $column => $folder) {
            if (in_array($column, $invalidFields, true) && $request->hasFile($column)) {
                $file = $request->file($column);
                if ($file && $file->isValid()) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $stored = $file->storeAs("public/{$folder}", $fileName);
                    $validated[$column] = Storage::url($stored);
                }
            } else {
                // If field is invalid but no file uploaded, keep existing value by removing from $validated
                unset($validated[$column]);
            }
        }

        // Only update fields that were marked invalid and present in validated input
        $updateData = Arr::only($validated, $invalidFields);

        // Persist changes and reset status back to pending
        $registration->fill($updateData);
        $registration->status = 'pending';
        $registration->admin_comments = null; // clear previous comments optionally
        $registration->invalid_fields = null; // clear invalid markers
        $registration->resubmission_token = null;
        $registration->resubmission_expires_at = null;
        $registration->save();

        return redirect()->route('registration.show', $registration)->with('success', 'Your corrections were submitted successfully and are pending review.');
    }
}
