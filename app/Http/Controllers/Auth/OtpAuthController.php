<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\Mail\PHPMailerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OtpAuthController extends Controller
{
    /**
     * Show a form to request an OTP login link.
     */
    public function requestForm(Request $request)
    {
        $prefillEmail = $request->query('email');

        return view('auth.otp.request', [
            'email' => $prefillEmail,
        ]);
    }

    /**
     * Generate and email an OTP login link and code.
     */
    public function sendLink(Request $request, PHPMailerService $mailer)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $email = strtolower(trim($validated['email']));

        // Try to find the user; do not disclose if missing
        $user = User::where('email', $email)->first();

        // Always pretend success to avoid user enumeration
        $genericResponse = redirect()->route('otp.verifyForm', ['token' => ''])
            ->with('status', 'If this email exists, a login link has been sent.');

        if (!$user) {
            // No user to log in; return generic success
            return $genericResponse;
        }

        // Invalidate older unused tokens for this email (optional cleanup)
        OtpCode::where('email', $email)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->update(['expires_at' => now()]); // force expire

        // Create OTP code and link token
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $token = (string) Str::uuid();
        $expiresAt = now()->addMinutes(10);

        $otp = OtpCode::create([
            'user_id' => $user->id,
            'email' => $email,
            'code' => $code,
            'link_token' => $token,
            'expires_at' => $expiresAt,
        ]);

        $verifyLink = route('otp.verifyForm', ['token' => $token]);

        // Send email with link + code
        if (method_exists($mailer, 'sendOtpLink')) {
            try {
                $mailer->sendOtpLink($email, $verifyLink, $code);
            } catch (\Throwable $e) {
                Log::error('Failed to send OTP email', ['email' => $email, 'error' => $e->getMessage()]);
            }
        } else {
            Log::info('OTP link generated (mailer method missing in environment)', [
                'email' => $email,
                'link' => $verifyLink,
                'code' => $code,
            ]);
        }

        return redirect()->route('otp.verifyForm', ['token' => $token])
            ->with('status', 'If this email exists, a login link has been sent.');
    }

    /**
     * Show the OTP verification form.
     */
    public function verifyForm(Request $request)
    {
        $token = (string) $request->query('token', '');

        $otp = null;
        if ($token !== '') {
            $otp = OtpCode::where('link_token', $token)->first();
        }

        if ($otp && ($otp->isExpired() || $otp->isUsed())) {
            $otp = null;
        }

        return view('auth.otp.verify', [
            'token' => $token,
            'email' => $otp?->email,
        ]);
    }

    /**
     * Validate OTP and log the user in.
     */
    public function verify(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'code' => 'required|string|size:6',
        ]);

        $otp = OtpCode::where('link_token', $validated['token'])->first();

        if (!$otp) {
            return back()->withErrors(['token' => 'Invalid or expired link.']);
        }
        if ($otp->isExpired()) {
            return back()->withErrors(['code' => 'OTP has expired. Please request a new one.']);
        }
        if ($otp->isUsed()) {
            return back()->withErrors(['code' => 'OTP already used. Please request a new one.']);
        }
        if (!hash_equals($otp->code, $validated['code'])) {
            return back()->withErrors(['code' => 'Incorrect OTP code.']);
        }

        // Find user by email and log in
        $user = User::where('email', $otp->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Account not found.']);
        }

        // Mark OTP as used
        $otp->used_at = now();
        $otp->save();

        Auth::login($user);

        return redirect()->intended(route('home'));
    }
}
