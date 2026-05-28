<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google's OAuth consent screen.
     */
    public function redirect()
    {
        $callbackUrl = config('services.google.redirect') ?: route('google.callback', [], true);

        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->with([
                'prompt' => 'select_account',
            ])
            ->redirectUrl($callbackUrl)
            ->stateless()
            ->redirect();
    }

    /**
     * Handle Google callback and authenticate user.
     */
    public function callback()
    {
        if (request()->has('error')) {
            Log::warning('Google OAuth callback returned error', [
                'error' => request()->get('error'),
                'error_description' => request()->get('error_description'),
                'state' => request()->get('state'),
            ]);

            return redirect()->route('login')->withErrors([
                'email' => 'Google sign-in was cancelled or failed. Please try again.',
            ]);
        }

        $callbackUrl = config('services.google.redirect') ?: route('google.callback', [], true);

        try {
            $googleUser = Socialite::driver('google')
                ->scopes(['openid', 'profile', 'email'])
                ->redirectUrl($callbackUrl)
                ->stateless()
                ->user();
        } catch (\Throwable $e) {
            Log::error('Google OAuth callback exchange failed', [
                'message' => $e->getMessage(),
                'type' => get_class($e),
            ]);

            return redirect()->route('login')->withErrors([
                'email' => 'Unable to sign in with Google at the moment. Please try again.',
            ]);
        }

        $email = strtolower(trim((string) $googleUser->getEmail()));

        if ($email === '') {
            return redirect()->route('login')->withErrors([
                'email' => 'Unable to retrieve email from Google account.',
            ]);
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
                'password' => \Illuminate\Support\Facades\Hash::make(Str::random(40)),
                'email_verified_at' => now(),
            ]
        );

        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        if (!$user->hasRole($customerRole->name)) {
            $user->assignRole($customerRole);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
