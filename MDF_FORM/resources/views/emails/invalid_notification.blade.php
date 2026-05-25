<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Action Required: Fix Your Application</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.6;">
    <h2 style="margin-bottom: 0;">FCCC Application Requires Updates</h2>
    <p style="margin-top: 4px; color: #666;">Reference #{{ $registration->id }}</p>

    <p>Dear {{ trim(($registration->title ? $registration->title . ' ' : '') . ($registration->first_name ?? '') . ' ' . ($registration->surname ?? '')) ?: 'Applicant' }},</p>

    <p>Thank you for your submission. After review, we found that your application contains some issues that need to be corrected before it can proceed.</p>

    @if(!empty($registration->admin_comments))
        <p><strong>Reviewer Comments:</strong><br>
        {{ $registration->admin_comments }}</p>
    @endif

    @php
        $fields = is_array($registration->invalid_fields) ? $registration->invalid_fields : [];
    @endphp

    @if(count($fields))
        <p><strong>Fields/Documents to Correct:</strong></p>
        <ul>
            @foreach($fields as $field)
                <li>{{ ucfirst(str_replace('_', ' ', $field)) }}</li>
            @endforeach
        </ul>
    @endif

    <p>You can correct only the highlighted fields/documents using the secure link below:</p>
    <p>
        <a href="{{ $link }}" style="background:#0d6efd;color:#fff;padding:10px 16px;text-decoration:none;border-radius:4px;display:inline-block;">
            Fix My Application
        </a>
    </p>

    @if(!empty($registration->resubmission_expires_at))
        <p style="color:#a00">
            Note: This link expires on {{ \Carbon\Carbon::parse($registration->resubmission_expires_at)->format('Y-m-d H:i') }}.
        </p>
    @endif

    <p>If you did not initiate this request, please ignore this email.</p>

    <p>Regards,<br>FCCC Team</p>
</body>
</html>
