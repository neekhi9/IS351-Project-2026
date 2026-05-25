@extends('layouts.app')

@section('content')
<div class="container" style="max-width:600px;">
    <h2 class="mb-3">Enter OTP Code</h2>
    <p class="text-muted">We sent a one-time passcode (OTP) to your email. Enter it below to sign in.</p>

    @if (session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <input type="hidden" name="token" value="{{ old('token', $token ?? request('token')) }}"/>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input
                        type="email"
                        class="form-control"
                        value="{{ old('email', $email ?? '') }}"
                        readonly
                    />
                </div>

                <div class="mb-3">
                    <label for="code" class="form-label">One-Time Passcode (6 digits)</label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code') }}"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        maxlength="6"
                        class="form-control @error('code') is-invalid @enderror"
                        placeholder="Enter your 6-digit code"
                        required
                        autofocus
                    />
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Codes expire in about 10 minutes.</div>
                </div>

                <button type="submit" class="btn btn-success w-100">Verify &amp; Sign In</button>
            </form>

            <div class="mt-3">
                <a class="btn btn-outline-secondary w-100" href="{{ route('otp.request') }}">Request a New Code</a>
            </div>
        </div>
    </div>
</div>
@endsection
