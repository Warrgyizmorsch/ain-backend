@extends('layouts.auth')

@section('content')
<main class="auth-shell">
    <aside class="auth-brand">
        <div class="auth-logo">
            <span class="auth-logo-mark">AIN</span>
            <span>Assignment In Need</span>
        </div>

        <div class="auth-brand-copy">
            <h1>Backend control panel for daily operations.</h1>
            <p>Manage leads, orders, writers, payments, follow-ups, and team activity from one focused workspace.</p>

            <div class="auth-meta">
                <div class="auth-meta-item">
                    <strong>Leads</strong>
                    <span>Fast tracking</span>
                </div>
                <div class="auth-meta-item">
                    <strong>Orders</strong>
                    <span>Live workflow</span>
                </div>
                <div class="auth-meta-item">
                    <strong>Teams</strong>
                    <span>Role based</span>
                </div>
            </div>
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

            <h2>Sign in</h2>
            <p class="auth-subtitle">Use your backend account to continue.</p>

            <form class="form w-100" novalidate="novalidate" action="{{ route('login') }}" method="post">
                @csrf

                <div class="fv-row mb-8">
                    <label class="form-label fs-6 fw-bold text-dark">Email</label>
                    <input
                        class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        type="email"
                        name="email"
                        autocomplete="username"
                        autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                            @if (strip_tags($message) === 'This account is already logged in elsewhere.')
                                <button type="button" class="btn btn-link p-0 takeover-trigger text-primary">Take Over Session?</button>
                            @endif
                        </span>
                    @enderror
                </div>

                <div class="fv-row mb-8">
                    <div class="d-flex flex-stack mb-2">
                        <label class="form-label fw-bold text-dark fs-6 mb-0">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link-primary fs-7 fw-bold">Forgot password?</a>
                        @endif
                    </div>
                    <input
                        class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                        type="password"
                        name="password"
                        autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-lg btn-primary w-100">
                    Continue
                </button>
            </form>

            @if (Route::has('register'))
                <div class="auth-link-row">
                    New user?
                    <a href="{{ route('register') }}" class="link-primary fw-bold">Create an account</a>
                </div>
            @endif
        </div>
    </section>

    <form id="takeoverForm" method="POST" action="{{ route('do-takeover') }}" style="display: none;">
        @csrf
    </form>
</main>

<script>
    document.addEventListener('click', function (event) {
        if (!event.target.classList.contains('takeover-trigger')) {
            return;
        }

        if (!window.Swal) {
            document.getElementById('takeoverForm').submit();
            return;
        }

        Swal.fire({
            title: 'Take over session?',
            text: 'This account is already logged in. Do you want to take over the session?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, take over',
            cancelButtonText: 'No, cancel'
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById('takeoverForm').submit();
            }
        });
    });
</script>
@endsection
