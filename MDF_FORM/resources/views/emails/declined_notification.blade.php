<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Application Has Been Declined</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.6;">
    <h2 style="margin-bottom: 0;">FCCC Application Update</h2>
    <p style="margin-top: 4px; color: #666;">Reference #{{ $registration->id }}</p>

    <p>Dear {{ trim(($registration->title ? $registration->title . ' ' : '') . ($registration->first_name ?? '') . ' ' . ($registration->surname ?? '')) ?: 'Applicant' }},</p>

    <p>We regret to inform you that your application has been declined.</p>

    @if(!empty($registration->admin_comments))
        <p><strong>Reason:</strong><br>
        {{ $registration->admin_comments }}</p>
    @endif

    <p>If you believe this decision was made in error, you may submit a new application with corrected details and supporting documents.</p>

    <p>Regards,<br>FCCC Team</p>
</body>
</html>
