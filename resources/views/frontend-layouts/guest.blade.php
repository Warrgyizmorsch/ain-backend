<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assignement In Need -> Assignment Help</title>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KXRV2HMX');</script>

        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KXRV2HMX"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    
    <base href="/public">
    <!-- Include CSS -->
    @include('frontend-layouts.css')
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    <!-- Include Scripts -->
   
</head>
<body class="hidden-bar-wrapper">
   
    <div class="page-wrapper">
        @yield('content')
    </div>
    
    <!-- Scroll to Top Button -->
 
    <!-- Include JavaScript -->
    @include('frontend-layouts.js')
    
    <!-- Include SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
</body>
</html>
