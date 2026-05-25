<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FCCC - Your Registration Has Been Received</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #1f2937; }
        .container { max-width: 640px; margin: 0 auto; padding: 16px; }
        h1 { font-size: 20px; margin-bottom: 8px; }
        p { margin: 6px 0; }
        .meta { color: #6b7280; font-size: 12px; margin-bottom: 16px; }
        .section-title { margin-top: 16px; font-size: 14px; text-transform: uppercase; letter-spacing: .05em; color: #374151; }
        .box { border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; background: #fafafa; }
        ul { margin: 8px 0 0 18px; padding: 0; }
        li { margin: 4px 0; }
        .footer { margin-top: 20px; font-size: 12px; color: #9ca3af; }
        .badge { display: inline-block; background: #0A5C45; color: #fff; border-radius: 9999px; padding: 2px 8px; font-size: 11px; }
        .action { text-align: center; margin-top: 16px; }
        .button { background: #0A5C45; color: #ffffff !important; text-decoration: none; padding: 10px 16px; border-radius: 6px; display: inline-block; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h1>Thank you for your registration</h1>
    <div class="meta">
        Reference #{{ $registration->id ?? 'N/A' }} ·
        {{ optional($registration->created_at)->toDayDateTimeString() ?? now()->toDayDateTimeString() }}
    </div>

    <p>
        We have received your {{ $registration->account_type ? strtolower($registration->account_type) : 'account' }} registration.
        Our team will review your submission and contact you if any additional information is required.
    </p>

    <div class="section-title">Summary</div>
    <div class="box">
        <ul>
            <li><strong>Account Type:</strong> <span class="badge">{{ ucfirst($registration->account_type) }}</span></li>
            @if ($registration->account_type === 'company')
                <li><strong>Organization:</strong> {{ $registration->organization_name ?: '—' }}</li>
                <li><strong>Contact:</strong> {{ trim(($registration->title ?: '') . ' ' . ($registration->first_name ?: '') . ' ' . ($registration->surname ?: '')) ?: '—' }}</li>
            @else
                <li><strong>Name:</strong> {{ trim(($registration->title ?: '') . ' ' . ($registration->first_name ?: '') . ' ' . ($registration->surname ?: '')) ?: '—' }}</li>
            @endif
            <li><strong>Email:</strong> {{ $registration->email ?: '—' }}</li>
            <li><strong>Phone:</strong> {{ $registration->mobile_phone ?: $registration->office_phone ?: '—' }}</li>
            <li><strong>City:</strong> {{ optional($registration->city)->name ?? '—' }}</li>
            <li><strong>Region:</strong> {{ optional($registration->region)->name ?? '—' }}</li>
        </ul>
    </div>
    <div class="action">
        <a href="{{ route('registration.show', $registration->id) }}" class="button">Click to View the Submission</a>
    </div>

    <p class="footer">
        This is an automated confirmation. If you did not submit this registration, please ignore this email or contact support.
    </p>
</div>
</body>
</html>
