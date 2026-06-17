@extends('layouts.auth')

@section('content')
<main class="auth-shell">
    <aside class="auth-brand">
        <div class="auth-logo">
            <span class="auth-logo-mark">AIN</span>
            <span>Assignment In Need</span>
        </div>

        <div class="auth-brand-copy">
            <h1>Set a new password for your backend account.</h1>
            <p>Choose a strong password before returning to the operations dashboard.</p>
        </div>

        <div class="auth-footer">AIN Backend</div>
    </aside>

    <section class="auth-panel">
        <div class="auth-card">
            <h2>New password</h2>
            <p class="auth-subtitle">Confirm your email and set a secure password.</p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="fv-row mb-7">
                    <label class="form-label fs-6 fw-bold text-dark" for="email">Email</label>
                    <input
                        id="email"
                        class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                        type="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        required
                        readonly
                        autocomplete="username">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="fv-row mb-7">
                    <label class="form-label fs-6 fw-bold text-dark" for="password">Password</label>
                    <input
                        id="password"
                        class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="fv-row mb-8">
                    <label class="form-label fs-6 fw-bold text-dark" for="password_confirmation">Confirm password</label>
                    <input
                        id="password_confirmation"
                        class="form-control form-control-lg form-control-solid @error('password_confirmation') is-invalid @enderror"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password">
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-lg btn-primary w-100">
                    Reset password
                </button>
            </form>
        </div>
    </section>
</main>
@endsection
