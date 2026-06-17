@extends('layouts.auth')

@section('content')
<main class="auth-shell">
    <aside class="auth-brand">
        <div class="auth-logo">
            <span class="auth-logo-mark">AIN</span>
            <span>Assignment In Need</span>
        </div>

        <div class="auth-brand-copy">
            <h1>Recover access without slowing the team down.</h1>
            <p>Enter your registered email and we will send a secure password reset link.</p>
        </div>

        <div class="auth-footer">AIN Backend</div>
    </aside>

    <section class="auth-panel">
        <div class="auth-card">
            <h2>Reset link</h2>
            <p class="auth-subtitle">We will email instructions to your account.</p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="fv-row mb-8">
                    <label class="form-label fs-6 fw-bold text-dark" for="email">Email</label>
                    <input
                        class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        type="email"
                        name="email"
                        id="email"
                        autocomplete="username"
                        autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-lg btn-primary w-100">
                    Email reset link
                </button>
            </form>

            <div class="auth-link-row">
                Remembered it?
                <a href="{{ route('login') }}" class="link-primary fw-bold">Back to login</a>
            </div>
        </div>
    </section>
</main>
@endsection
