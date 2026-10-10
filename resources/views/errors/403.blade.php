<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied | AIN CRM</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body {
            background-color: #f5f8fa;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .error-card {
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 0.5rem 1.5rem 0.5rem rgba(0, 0, 0, 0.075);
            max-width: 600px;
            width: 100%;
            padding: 3rem 2.5rem;
            text-align: center;
        }
        .icon-circle {
            width: 90px;
            height: 90px;
            background: #fff5f8;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .icon-circle i {
            font-size: 42px;
            color: #f1416c;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="mb-4">
            <img src="{{ asset('assets/media/avatars/logo-white_11zon.png') }}" alt="Logo" style="height: 48px; background: #1e1e2d; padding: 6px 14px; border-radius: 8px;" />
        </div>
        <div class="icon-circle">
            <i class="fa fa-lock"></i>
        </div>
        <h1 class="fw-bolder text-gray-900 mb-2 fs-2x">Access Denied</h1>
        <div class="badge badge-light-danger fs-7 fw-bold px-4 py-2 mb-4">
            Error 403: Forbidden
        </div>
        <h3 class="fs-4 fw-bold text-gray-800 mb-3">
            {{ isset($exception) && $exception->getMessage() ? $exception->getMessage() : ($message ?? 'You do not have user rights to access this page.') }}
        </h3>
        <p class="text-muted fs-6 mb-6">
            Your current user account does not have permission to view or manage this section. If you believe this is an error, please reach out to your administrator to update your rights.
        </p>

        @if(auth()->check())
        <div class="bg-light-primary rounded p-3 mb-6 text-start d-flex justify-content-between align-items-center">
            <span class="text-gray-700 fs-7">
                Logged in as: <strong>{{ auth()->user()->name ?? auth()->user()->email }}</strong>
            </span>
            <span class="badge badge-primary fs-8">Role #{{ auth()->user()->role_id }}</span>
        </div>
        @endif

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('dashboard') }}" class="btn btn-primary fw-bold px-5">
                <i class="fa fa-home me-2"></i> Go to Dashboard
            </a>
            <button type="button" onclick="window.history.back()" class="btn btn-secondary fw-bold px-5">
                <i class="fa fa-arrow-left me-2"></i> Go Back
            </button>
        </div>
    </div>
</body>
</html>
