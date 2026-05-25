<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Login Link and OTP Code</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.6;">
    <h2 style="margin-bottom: 0;">FCCC Passwordless Login</h2>

    <p>Use the link and one-time passcode (OTP) below to sign in securely.</p>

    <p>
        <a href="{{ $verifyLink }}" style="background:#0d6efd;color:#fff;padding:10px 16px;text-decoration:none;border-radius:4px;display:inline-block;">
            Open Login Page
        </a>
    </p>

    <p>If the button does not work, copy and paste this link into your browser:<br>
        <a href="{{ $verifyLink }}">{{ $verifyLink }}</a>
    </p>

    <p><strong>Your OTP Code:</strong></p>
    <div style="font-size: 22px; font-weight: bold; letter-spacing: 4px; padding: 8px 12px; background: #f5f5f5; display: inline-block; border-radius: 4px;">
        {{ $code }}
    </div>

    <p style="margin-top:16px;">This code expires in approximately 10 minutes. For your security, do not share it with anyone.</p>

    <p>Regards,<br>FCCC Team</p>
</body>
</html>
