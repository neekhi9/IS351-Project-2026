<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Application Has Been Approved</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.6;">
    <h2 style="margin-bottom: 0;">FCCC Application Approved</h2>

    <p>Dear {{ $user->name ?? 'Applicant' }},</p>

    <p>Good news! Your application has been approved. You can now access your account using our secure, passwordless login.</p>

    <p>To sign in, click the button below and enter the One-Time Passcode (OTP) sent to your email.</p>

    <p>
        <a href="{{ $loginLink }}" style="background:#198754;color:#fff;padding:10px 16px;text-decoration:none;border-radius:4px;display:inline-block;">
            Sign In via OTP
        </a>
    </p>

    <p>If clicking the button does not work, copy and paste this link into your browser:<br>
        <a href="{{ $loginLink }}">{{ $loginLink }}</a>
    </p>

    <p>You have been assigned a basic user role.</p>

    <p>Regards,<br>FCCC Team</p>
</body>
</html>
