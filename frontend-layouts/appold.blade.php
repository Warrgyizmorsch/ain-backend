<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    
  
    <link rel="preconnect" href="https://www.googletagmanager.com">
    
    <meta charset="utf-8">
    <title>{{$data['title'] ?? ''}}</title>
	<meta name="description" content="{{$data['description'] ?? ''}}" />
    <meta name="keywords" content="{{$data['keyword'] ?? ''}}" />
    @if(isset($data['canonicalPage']))
        <link rel="canonical" href="{{ $data['canonicalPage'] }}" />
    @else
    
    
        <link rel="canonical" href="{{ env('CANONICAL_URL', url()->current()) }}" />
    @endif

    @if(isset($data['artical']))
        <script type="application/ld+json">
            {!!$data['artical']!!}
        </script>
    @endif
    @if(isset($data['schema']))
        <script type="application/ld+json">
            {!!$data['schema']!!}
        </script>
    @endif
    
    @if(isset($data['org']))
        <script type="application/ld+json">
            {!!$data['org']!!}
        </script>
    @endif
    
    @if(isset($data['website']))
        <script type="application/ld+json">
            {!!$data['website']!!}
        </script>
    @endif
    
    @if(isset($data['Faqschema']))
        <script type="application/ld+json">
            {!!$data['Faqschema']!!}
        </script>
    @endif
    
   @if(isset($data['BreadcrumbList']))
    <script type="application/ld+json">
        {!! $data['BreadcrumbList'] !!}
    </script>
    
  
@endif
 @if(isset($data['flag']))
  {!! $data['flag'] !!}
 @endif
    
    <base href="/public">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    
   
    <!-- Include CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" media="print" onload="this.media='all'"/>
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">
    <link rel="preload" href="/fonts/fontawesome-webfonte0a5.woff2?v=4.3.0" as="font" type="font/woff2" crossorigin>
    @include('frontend-layouts.css')
    <script async src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    
    <!-- Include Scripts -->
    <style>
        @media screen and (max-width: 768px) {
            .whatsapp_float {
                position: fixed;
                text-align: left;
                bottom: 25px;
                z-index: 25;
                pointer-events: none;
            }
            
        }
        @media screen and (min-width: 768px) {
            .whatsapp_float {
                position: fixed;
                text-align: left;
                bottom: 25px;
                z-index: 25;
                pointer-events: none;
            }
            
        }

       
    </style>
    
   
   <style>
        body {
            font-family: 'Arial', sans-serif;
            font-display: swap;
        }
    
        .banner-section-three h1 {
            font-size: 35px; /* Default for desktop */
            font-weight: 600;
            color: #000;
            padding: 5px 0px 15px;
            text-transform: uppercase;
            display: block;
            opacity: 1;
            transition: font-size 0.3s ease;
        }
    
        /* Media Query for Below Tablet Size (max-width: 768px) */
        @media screen and (max-width: 768px) {
            .banner-section-three h1 {
                font-size: 24px; /* Reduce font size */
                opacity: 0.9; /* Slightly lower opacity */
                padding: 3px 0px 10px; /* Adjust padding */
                font-weight: 500;
                color: #000;
                text-transform: uppercase;
                display: block;
            }
        }
    
        /* Media Query for Mobile Devices (max-width: 480px) */
        @media screen and (max-width: 480px) {
            .banner-section-three h1 {
                font-size: 24px; /* Further reduce font size */
                opacity: 0.8; /* Lower opacity to reduce impact */
                padding: 2px 0px 8px; /* Minimal padding */
                font-weight: 500;
                color: #000;
                text-transform: uppercase;
                display: block;
            }
        }
    </style>
    

    <style>
        /* Default Styles */
        .promo-img {
            margin-top: -68px;
            margin-bottom: -24px;
            height: 122px;
            width: auto;
            max-width: 100%;
        }
        
        /* Optimize for Mobile Devices */
        @media only screen and (max-width: 767px) {
            .promo-img {
                /* Hide or reduce size on mobile to improve LCP */
                height: 80px; /* Smaller height for faster loading */
                margin-top: -50px;
                margin-bottom: -10px;
            }
        }
        
        /* Optional - Defer image visibility on small screens if not in the viewport */
        @media only screen and (max-width: 768px) {
            .promo-img {
                height: 100px; /* Adjust for tablet */
                margin-top: -40px;
                margin-bottom: -15px;
            }
        }

    </style>
  
</head>
<body class="hidden-bar-wrapper">
    <div class="page-wrapper">
        
        @include('frontend-layouts.header')
        @yield('content')
        @include('frontend-layouts.footer')
    </div>
      <!-- Include JavaScript -->
    @include('frontend-layouts.js')
    
    <div class="whatsapp_float">
        <a href="https://wa.me/+447435256433" target="_blank" style="pointer-events: auto;"><i class="fa fa-whatsapp" style="font-size:50px; color: green; margin-left: 40px;"></i></a>
    </div>
  
   
    <!--<script src="https://www.google.com/recaptcha/api.js" defer></script>-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script>
        document.getElementById('orderForm').addEventListener('focusin', loadReCaptcha, { once: true });
    
        function loadReCaptcha() {
            const script = document.createElement('script');
            script.src = "https://www.google.com/recaptcha/api.js";
            script.async = true;
            script.defer = true;
            document.body.appendChild(script);
        }
    </script>
    
    <script>
        document.getElementById('orderForm').addEventListener('submit', function(event) {
            event.preventDefault();
    
            var recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please complete the reCAPTCHA.',
                });
                return false;
            }
    
            // If reCAPTCHA is completed, allow form submission
            this.submit();
        });
    </script>
    <script>
        $(function(){
            var dtToday = new Date();

            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();
            
            var maxDate = year + '-' + month + '-' + day;
            // alert(maxDate);
            $('#DeliveryDate').attr('min', maxDate);
        });
    </script>
</body>
</html>
