@extends('layouts.auth')

@section('content')
<main class="auth-shell">
    <aside class="auth-brand">
        <div class="auth-logo">
            <span class="auth-logo-mark">AIN</span>
            <span>Assignment In Need</span>
        </div>

        <div class="auth-brand-copy">
            <h1>Create a backend account for assigned work.</h1>
            <p>Register only when access has been approved by the operations team.</p>
        </div>

        <div class="auth-footer">AIN Backend</div>
    </aside>

    <section class="auth-panel">
        <div class="auth-card">
            <h2>Create account</h2>
            <p class="auth-subtitle">Fill in your details to request backend access.</p>

            <form class="form w-100" novalidate="novalidate" id="registerForm" action="{{ route('register') }}" method="post">
                @csrf

                <div class="fv-row mb-7">
                    <label class="form-label fs-6 fw-bold text-dark">Name</label>
                    <input
                        class="form-control form-control-lg form-control-solid @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        type="text"
                        name="name"
                        autocomplete="name"
                        autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="fv-row mb-7">
                    <label class="form-label fs-6 fw-bold text-dark">Email</label>
                    <input
                        class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        type="email"
                        name="email"
                        autocomplete="username">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="fv-row mb-7">
                    <label class="form-label fw-bold text-dark fs-6">Password</label>
                    <input
                        class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                        type="password"
                        name="password"
                        autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="fv-row mb-7">
                    <label class="form-label fw-bold text-dark fs-6">Confirm password</label>
                    <input
                        class="form-control form-control-lg form-control-solid @error('password_confirmation') is-invalid @enderror"
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password">
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="g-recaptcha mb-7" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>

                <button type="submit" class="btn btn-lg btn-primary w-100">
                    Register
                </button>
            </form>

            <div class="auth-link-row">
                Already have an account?
                <a href="{{ route('login') }}" class="link-primary fw-bold">Sign in</a>
            </div>
        </div>
    </section>
</main>

<script>
    document.getElementById('registerForm')?.addEventListener('submit', function (event) {
        if (!window.grecaptcha) {
            return;
        }

        var recaptchaResponse = grecaptcha.getResponse();
        if (recaptchaResponse) {
            return;
        }

        event.preventDefault();
        if (window.Swal) {
            Swal.fire({
                icon: 'error',
                title: 'Verification needed',
                text: 'Please complete the reCAPTCHA.'
            });
        } else {
            alert('Please complete the reCAPTCHA.');
        }
    });
</script>
@endsection
