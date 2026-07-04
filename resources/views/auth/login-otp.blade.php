@extends('layouts.auth')

@section('content')
<main class="auth-shell">
    <aside class="auth-brand">
        <div class="auth-logo">
            <span class="auth-logo-mark">AIN</span>
            <span>Assignment In Need</span>
        </div>

        <div class="auth-brand-copy">
            <h1>Admin approval required.</h1>
            <p>Your login request is waiting for the admin OTP shown in the admin panel.</p>
        </div>

        <div class="auth-footer">AIN Backend</div>
    </aside>

    <section class="auth-panel">
        <div class="auth-card">
            @if(session()->has('warning'))
                <div class="alert alert-warning auth-alert">
                    {{ session()->get('warning') }}
                </div>
            @endif

            @if(($notification->purpose ?? '') === 'admin_email_login')
                <h2>Enter email OTP</h2>
                <p class="auth-subtitle">
                    Admin OTP has been sent to {{ $notification->email_to }}.
                </p>
            @else
                <h2>Enter admin OTP</h2>
                <p class="auth-subtitle">
                    Request for {{ $notification->user->name }} from IP {{ $notification->ip_address ?? 'N/A' }}.
                </p>
            @endif

            <form class="form w-100" action="{{ route('login.otp.verify') }}" method="post">
                @csrf

                <div class="fv-row mb-8">
                    <label class="form-label fs-6 fw-bold text-dark">6 digit OTP</label>
                    <input
                        class="form-control form-control-lg form-control-solid text-center fs-2 fw-bold @error('otp_code') is-invalid @enderror"
                        type="text"
                        name="otp_code"
                        inputmode="numeric"
                        maxlength="6"
                        autocomplete="one-time-code"
                        autofocus>
                    @error('otp_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-lg btn-primary w-100">
                    Verify OTP
                </button>
            </form>

            <div class="auth-link-row">
                <a href="{{ route('login') }}" class="link-primary fw-bold">Back to login</a>
            </div>
        </div>
    </section>
</main>
@endsection
