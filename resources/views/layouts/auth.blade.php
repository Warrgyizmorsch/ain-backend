<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AIN Backend') }}</title>
    @include('layouts.css')
    <style>
        html,
        body {
            min-height: 100%;
            background: #f4f7fb;
        }

        .auth-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(360px, 0.9fr) minmax(420px, 1.1fr);
            background:
                linear-gradient(135deg, rgba(28, 43, 88, 0.95), rgba(16, 24, 40, 0.98)),
                #101828;
        }

        .auth-brand {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
            color: #fff;
            overflow: hidden;
        }

        .auth-brand::after {
            content: "";
            position: absolute;
            inset: auto -20% -22% 10%;
            height: 360px;
            background: radial-gradient(circle, rgba(80, 205, 137, 0.26), rgba(80, 205, 137, 0));
            pointer-events: none;
        }

        .auth-logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 18px;
            letter-spacing: 0;
            color: #fff;
        }

        .auth-logo-mark {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #50cd89;
            color: #0b1b2a;
            font-weight: 800;
        }

        .auth-brand-copy {
            position: relative;
            z-index: 1;
            max-width: 460px;
        }

        .auth-brand-copy h1 {
            color: #fff;
            font-size: 38px;
            line-height: 1.15;
            margin-bottom: 18px;
            font-weight: 800;
        }

        .auth-brand-copy p {
            color: rgba(255, 255, 255, 0.72);
            font-size: 15px;
            line-height: 1.7;
            margin: 0;
        }

        .auth-meta {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 36px;
        }

        .auth-meta-item {
            border: 1px solid rgba(255, 255, 255, 0.13);
            border-radius: 8px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(8px);
        }

        .auth-meta-item strong {
            display: block;
            color: #fff;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .auth-meta-item span {
            color: rgba(255, 255, 255, 0.62);
            font-size: 12px;
        }

        .auth-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 44px 28px;
            background: #f4f7fb;
        }

        .auth-card {
            width: 100%;
            max-width: 460px;
            background: #fff;
            border: 1px solid #e4e7ec;
            border-radius: 8px;
            box-shadow: 0 22px 48px rgba(16, 24, 40, 0.12);
            padding: 34px;
        }

        .auth-card h2 {
            color: #101828;
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .auth-card .auth-subtitle {
            color: #667085;
            margin-bottom: 28px;
        }

        .auth-link-row {
            color: #667085;
            font-size: 13px;
            margin-top: 18px;
            text-align: center;
        }

        .auth-alert {
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .auth-footer {
            position: relative;
            z-index: 1;
            color: rgba(255, 255, 255, 0.48);
            font-size: 12px;
        }

        @media (max-width: 992px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }

            .auth-brand {
                padding: 28px;
            }

            .auth-brand-copy {
                margin-top: 42px;
            }

            .auth-brand-copy h1 {
                font-size: 30px;
            }

            .auth-meta {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .auth-panel {
                padding: 18px;
            }

            .auth-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    @yield('content')
    @include('layouts.js')
</body>
</html>
