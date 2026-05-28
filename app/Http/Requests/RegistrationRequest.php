<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    protected function sanitizeString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return trim(strip_tags($value));
    }

    protected function sanitizeArrayStrings(?array $values): array
    {
        if (!$values) {
            return [];
        }

        return array_map(function ($value) {
            return is_string($value) ? $this->sanitizeString($value) : $value;
        }, $values);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'account_type' => $this->sanitizeString($this->input('account_type')),
            'organization_name' => $this->sanitizeString($this->input('organization_name')),
            'organization_type' => $this->sanitizeString($this->input('organization_type')),
            'designation_business' => $this->sanitizeString($this->input('designation_business')),
            'title' => $this->sanitizeString($this->input('title')),
            'first_name' => $this->sanitizeString($this->input('first_name')),
            'surname' => $this->sanitizeString($this->input('surname')),
            'address' => $this->sanitizeString($this->input('address')),
            'street' => $this->sanitizeString($this->input('street')),
            'suburb' => $this->sanitizeString($this->input('suburb')),
            'email' => strtolower((string) $this->sanitizeString($this->input('email'))),
            'alt_email' => strtolower((string) $this->sanitizeString($this->input('alt_email'))),
            'com_reg_num' => preg_replace('/\D+/', '', (string) $this->input('com_reg_num')),
            'tin_number' => preg_replace('/\D+/', '', (string) $this->input('tin_number')),
            'wireman_l_num' => $this->sanitizeArrayStrings($this->input('wireman_l_num')),
            'directors' => $this->sanitizeArrayStrings($this->input('directors')),

            'title_ind' => $this->sanitizeString($this->input('title_ind')),
            'first_name_ind' => $this->sanitizeString($this->input('first_name_ind')),
            'surname_ind' => $this->sanitizeString($this->input('surname_ind')),
            'address_ind' => $this->sanitizeString($this->input('address_ind')),
            'street_ind' => $this->sanitizeString($this->input('street_ind')),
            'suburb_ind' => $this->sanitizeString($this->input('suburb_ind')),
            'individualEmail' => strtolower((string) $this->sanitizeString($this->input('individualEmail'))),
            'alt_email_ind' => strtolower((string) $this->sanitizeString($this->input('alt_email_ind'))),
            'tin_number_ind' => preg_replace('/\D+/', '', (string) $this->input('tin_number_ind')),
            'wireman_l_num_ind' => $this->sanitizeString($this->input('wireman_l_num_ind')),
        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $accountType = $this->input('account_type');
        
        if ($accountType === 'company') {
            return $this->companyRules();
        }
        
        return $this->individualRules();
    }
    
    protected function companyRules(): array
    {
        return [
            'organization_name' => 'required|string|max:255|regex:/^[^\d]+$/',
            'organization_type' => 'required|in:Sole Proprietor,Partnership,Limited Liability,Government Entity,NGO',
            'designation_business' => 'required|string|max:255|alpha_num',
            'title' => 'required|in:Mr,Mrs,Ms',
            'first_name' => 'required|string|max:255|regex:/^[^\d]+$/',
            'surname' => 'required|string|max:255|regex:/^[^\d]+$/',
            'address' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'suburb' => 'required|string|max:255',
            'city' => 'required|integer|exists:cities,id',
            'region' => 'required|integer|exists:regions,id',
            'office_phone' => 'required|digits:7',
            'mobile_phone' => 'required|digits:7',
            'email' => 'required|email|max:255',
            'alt_email' => 'nullable|email|max:255',
            'directors' => 'required|array|min:1',
            'directors.*' => 'required|string|max:255|regex:/^[^\d]+$/',
            'com_reg_num' => 'required|digits:9',
            'roc_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'tin_number' => 'required|digits:9',
            'tin_letter' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'wireman_l_num' => 'required|array|min:1',
            'wireman_l_num.*' => 'required|alpha_num|max:255',
            'wireman_license' => 'required|array|min:1',
            'wireman_license.*' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ];
    }
    
    protected function individualRules(): array
    {
        return [
            'title_ind' => 'required|in:Mr,Mrs,Ms',
            'first_name_ind' => 'required|string|max:255|regex:/^[^\d]+$/',
            'surname_ind' => 'required|string|max:255|regex:/^[^\d]+$/',
            'address_ind' => 'required|string|max:255',
            'street_ind' => 'required|string|max:255',
            'suburb_ind' => 'required|string|max:255',
            'city_ind' => 'required|integer|exists:cities,id',
            'region_ind' => 'required|integer|exists:regions,id',
            'office_phone_ind' => 'required|digits:7',
            'mobile_phone_ind' => 'required|digits:7',
            'individualEmail' => 'required|email|max:255',
            'alt_email_ind' => 'nullable|email|max:255',
            'tin_number_ind' => 'required|digits:9',
            'tin_letter_ind' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'wireman_l_num_ind' => 'required|alpha_num|max:255',
            'wireman_license_ind' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            '*.required' => 'The :attribute field is required.',
            '*.regex' => 'The :attribute field cannot contain numbers.',
            '*.digits' => 'The :attribute must be exactly :digits digits.',
            '*.alpha_num' => 'The :attribute must be alphanumeric.',
            '*.email' => 'The :attribute must be a valid email address.',
            '*.in' => 'The selected :attribute is invalid.',
            '*.mimes' => 'The :attribute must be a file of type: pdf, doc, docx.',
            '*.max' => 'The :attribute may not be greater than :max kilobytes.',
            'directors.*.required' => 'Each director name is required.',
            'wireman_l_num.*.required' => 'Each wireman license number is required.',
            'wireman_license.*.required' => 'Each wireman license file is required.',
        ];
    }
}