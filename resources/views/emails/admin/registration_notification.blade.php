<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Registration Submitted</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #1f2937; }
        .container { max-width: 720px; margin: 0 auto; padding: 16px; }
        h1 { font-size: 20px; margin-bottom: 8px; }
        p { margin: 6px 0; }
        .meta { color: #6b7280; font-size: 12px; margin-bottom: 16px; }
        .section-title { margin-top: 16px; font-size: 14px; text-transform: uppercase; letter-spacing: .05em; color: #374151; }
        .box { border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; background: #fafafa; }
        ul { margin: 8px 0 0 18px; padding: 0; }
        li { margin: 4px 0; }
        .badge { display: inline-block; background: #0A5C45; color: #fff; border-radius: 9999px; padding: 2px 8px; font-size: 11px; }
        .muted { color: #6b7280; }
        .code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; background: #f3f4f6; padding: 1px 4px; border-radius: 4px; }
        .footer { margin-top: 20px; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="container">
    <h1>New Registration Submitted</h1>
    <div class="meta">
        Ref #{{ $registration->id ?? 'N/A' }}
        · {{ optional($registration->created_at)->toDayDateTimeString() ?? now()->toDayDateTimeString() }}
    </div>

    <div class="section-title">Overview</div>
    <div class="box">
        <ul>
            <li>
                <strong>Account Type:</strong>
                <span class="badge">{{ isset($registration->account_type) ? ucfirst($registration->account_type) : 'N/A' }}</span>
            </li>

            @if (($registration->account_type ?? null) === 'company')
                <li><strong>Organization:</strong> {{ $registration->organization_name ?: '—' }}</li>
                <li>
                    <strong>Contact:</strong>
                    {{ trim(($registration->title ?: '') . ' ' . ($registration->first_name ?: '') . ' ' . ($registration->surname ?: '')) ?: '—' }}
                </li>
                <li><strong>Designation of Business:</strong> {{ $registration->designation_business ?: '—' }}</li>
                <li><strong>Company Reg. No.:</strong> <span class="code">{{ $registration->com_reg_num ?: '—' }}</span></li>
            @else
                <li>
                    <strong>Name:</strong>
                    {{ trim(($registration->title ?: '') . ' ' . ($registration->first_name ?: '') . ' ' . ($registration->surname ?: '')) ?: '—' }}
                </li>
            @endif

            <li><strong>Email:</strong> {{ $registration->email ?: '—' }}</li>
            <li><strong>Alt Email:</strong> {{ $registration->alt_email ?: '—' }}</li>
            <li><strong>Phone:</strong> {{ $registration->mobile_phone ?: $registration->office_phone ?: '—' }}</li>

            <li><strong>City:</strong> {{ optional($registration->city)->name ?? '—' }}</li>
            <li><strong>Region:</strong> {{ optional($registration->region)->name ?? '—' }}</li>
            <li><strong>TIN Number:</strong> <span class="code">{{ $registration->tin_number ?? $registration->tin_number_ind ?? '—' }}</span></li>
        </ul>
    </div>

    <div class="section-title">Addresses</div>
    <div class="box">
        <ul>
            <li><strong>Address:</strong> {{ $registration->address ?: $registration->address_ind ?: '—' }}</li>
            <li><strong>Street:</strong> {{ $registration->street ?: $registration->street_ind ?: '—' }}</li>
            <li><strong>Suburb:</strong> {{ $registration->suburb ?: $registration->suburb_ind ?: '—' }}</li>
        </ul>
    </div>

    @if (($registration->account_type ?? null) === 'company')
        <div class="section-title">Company Extras</div>
        <div class="box">
            <ul>
                <li><strong>ROC Certificate:</strong> {{ $registration->roc_file_path ?? '—' }}</li>
                <li><strong>TIN Letter:</strong> {{ $registration->tin_letter_path ?? '—' }}</li>
            </ul>
        </div>
    @else
        <div class="section-title">Individual Extras</div>
        <div class="box">
            <ul>
                <li><strong>Wireman License No. (Individual):</strong> <span class="code">{{ $registration->wireman_l_num_ind ?? '—' }}</span></li>
                <li><strong>Wireman License File (Individual):</strong> {{ $registration->wireman_license_ind_path ?? '—' }}</li>
                <li><strong>TIN Letter (Individual):</strong> {{ $registration->tin_letter_path ?? '—' }}</li>
            </ul>
        </div>
    @endif

    <p class="footer muted">
        This message was generated automatically by the system for administrators.
    </p>
</div>
</body>
</html>
