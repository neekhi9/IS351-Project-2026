@extends('layouts.app')

@section('content')
<div class="container" style="max-width:600px;">
    <h2 class="mb-3">Passwordless Login</h2>
    <p class="text-muted">Enter your email to receive a login link and one-time passcode (OTP).</p>

    @if (session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('otp.sendLink') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $email ?? '') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="you@example.com"
                        required
                        autofocus
                    />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">We will send a verification link and OTP to this address.</div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Send Login Link</button>
            </form>

            <div class="mt-3">
                <a class="btn btn-outline-secondary w-100" href="{{ route('login') }}">Back to Standard Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
