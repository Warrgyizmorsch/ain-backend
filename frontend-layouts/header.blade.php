<style>
    .logo-box {
        margin: 4px !important;
        padding: 0 !important;
        display: flex;
    }

    .logo-box .logo img {
        width: 70px !important;
    }

    @media (max-width: 768px) {
        .logo-box .logo img {
            width: 60px !important;
        }
    }

    @media (max-width: 1100px) {
        .outer-box {
            display: none !important;
        }
    }

    @media screen and (max-width: 1023px) {
        .logo-box {
            width: 100%;
            text-align: center;
            padding: 0 !important;
            margin: 4px !important;
        }

        .main-header.header-style-three .header-lower .logo-box {
            background-color: transparent;

        }

        .logo {
            display: inline-block;
        }

    }

    .main-header.header-style-three .header-lower .logo-box {
        position: relative;
    }

    .main-header.header-style-three .main-menu .navigation>li>a {
        padding: 10px 6px;
    }

    .main-header.header-style-three.fixed-header {
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
    }

    .navbar-collapse .navigation>li>ul>li>ul {
        max-height: 50vh;
        overflow-y: auto;
        overflow-x: hidden;
        scrollbar-width: none;
    }
    .navbar-collapse .navigation>li>ul>li>ul>li>a {
        padding: 5px 10px;
        font-size: 13px;
    }

    .navbar-collapse .navigation .subject-dropdown>ul {
        max-height: 50vh;
        overflow: auto;
        scrollbar-width: none;
    }

    .navbar-collapse .navigation .city-dropdown>ul {
        max-height: 50vh;
        overflow: auto;
        scrollbar-width: none;
    }

    .main-menu .navigation>li>ul>li>a {
        padding: 5px 10px;
        font-size: 13px;
    }
    

    
</style>
{{-- <style>
    .navbar-collapse .navigation>li>ul>li>ul {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: hidden;
        scrollbar-width: thin;
        scrollbar-color: #00c6ff #f1f1f1;
    }


    .navbar-collapse .navigation>li>ul>li>ul::-webkit-scrollbar {
        width: 12px;
        height: 30px;
    }


    .navbar-collapse .navigation>li>ul>li>ul::-webkit-scrollbar-track {
        background: #f9f9f9;
        border-radius: 10px;
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
    }


    .navbar-collapse .navigation>li>ul>li>ul::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #4facfe, #00f2fe);
        border-radius: 15px;
        min-height: 30px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        transition: background 0.3s ease, box-shadow 0.3s ease;
    }


    .navbar-collapse .navigation>li>ul>li>ul::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #00f2fe, #4facfe);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    }


    .navbar-collapse .navigation>li>ul>li>ul {
        scrollbar-width: thin;
        scrollbar-color: #4facfe #f9f9f9;
    }


    .navbar-collapse .navigation>li:nth-child(5)>ul {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: hidden;
        scrollbar-width: thin;
        scrollbar-color: #00c6ff #f1f1f1;
    }


    .navbar-collapse .navigation>li:nth-child(5)>ul::-webkit-scrollbar {
        width: 12px;
        height: 30px;
    }


    .navbar-collapse .navigation>li:nth-child(5)>ul::-webkit-scrollbar-track {
        background: #f9f9f9;
        border-radius: 10px;
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
    }


    .navbar-collapse .navigation>li:nth-child(5)>ul::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #4facfe, #00f2fe);
        border-radius: 15px;
        min-height: 30px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        transition: background 0.3s ease, box-shadow 0.3s ease;
    }


    .navbar-collapse .navigation>li:nth-child(5)>ul::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #00f2fe, #4facfe);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    }

    .main-header.header-style-three .main-menu .navigation>li>a {
        font-size: 17px !important;
    }
</style> --}}
<style>
    /* Top Bar Container */
    .top-bar {
        background: #3F159A;
        color: #fff;
        font-size: 14px;
        padding: 2px 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    /* Wrapper for content alignment */
    .content-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between !important;
        align-items: center;
        flex-wrap: wrap;
    }

    /* Contact Info Styling */
    .contact-item {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .contact-link {
        display: flex;
        align-items: center;
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        gap: 6px;
        transition: color 0.3s;
    }

    .contact-link:hover {
        color: #ffcc00;
    }

    .contact-link i {
        font-size: 16px;
    }

    .divider {
        color: rgba(255, 255, 255, 0.6);
        font-weight: bold;
    }

    /* Offer Section Styling */
    .offer-section {
        text-align: right;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .offer-section .offers a {
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        transition: color 0.3s;
    }

    .offer-section .offers a:hover {
        color: #ffe600;
    }

    .offer-text a {
        font-size: 16px;
        font-weight: 700;
        color: #ffe600;
        text-decoration: none;
        transition: color 0.3s;
    }

    .offer-text a:hover {
        color: #ffffff;
    }

    .offers {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 1rem;
    }

    .offer-button {
        display: inline-block;
        padding: 1px 8px;
        margin-top: 4px;
        margin-bottom: 4px;
        background-color: #e60023;
        /* red-like color */
        color: white;
        font-weight: bold;
        text-decoration: none;
        border-radius: 8px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        transition: transform 0.5s ease, box-shadow 0.3s ease;
    }

    /* Pop-in on load */
    .offer-button {
        animation: popIn 0.6s ease;
    }

    /* Pop-out on hover */
    .offer-button:hover {
        transform: scale(1.08);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }

    @keyframes popIn {
        0% {
            transform: scale(0.8);
            opacity: 0;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .content-wrapper {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 0 12px;
        }

        .contact-item {
            justify-content: space-between;
            width: 100%;
            gap: 8px;
        }

        .offer-section {
            display: none;
        }

        .main-header.header-style-three .inner-container {
            display: flex;
        }
    }

    @media (max-width: 480px) {
        .top-bar {
            font-size: 10px;
        }

        .contact-link i {
            font-size: 14px;
        }
    }

    /* Cart / Profile Dropdown */
    .cart-box {
        position: relative;
    }

    .cart-panel {
        position: absolute !important;
        top: 40px !important;
        right: 0 !important;
        width: 260px !important;
        background: #fff !important;
        border-radius: 10px;
        border: 1px solid #e0e0e0 !important;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        z-index: 1000 !important;
        display: none;
        padding: 16px !important;
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .cart-box:hover .cart-panel {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .cart-product .inner {
        text-align: center;
    }

    .cart-product .image img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .cart-product h3 {
        font-size: 16px;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }

    .quantity-text {
        font-size: 13px;
        color: #777;
        margin-bottom: 6px;
    }

    .btns-boxed {
        margin-top: 12px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 0;
    }

    .btns-boxed li {
        list-style: none;
    }

    .btns-boxed li a {
        display: block !important;
        text-align: center !important;
        padding: 12px 20px !important;
        border-radius: 10px !important;
        font-size: 15px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        transition: all 0.3s ease-in-out !important;
        min-width: 100px;
        background: linear-gradient(to right, #3F159A, #0E8FCE) !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(0, 86, 179, 0.15) !important;
        position: relative !important;
        overflow: hidden !important;
        z-index: 1 !important;
    }

    /* Swipe effect using ::before */
    .btns-boxed li a::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #0E8FCE, #3F159A);
        transition: left 0.4s ease;
        z-index: -1;
    }

    /* Hover Effect */
    .btns-boxed li a:hover::before {
        left: 0;
    }

    .btns-boxed li a:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 86, 179, 0.3);
    }

    .login-panel {
        background: #ffffff;
        padding: 24px 20px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        text-align: center;
        max-width: 320px;
        margin: 0 auto;
        transition: all 0.3s ease-in-out;
    }

    .login-panel h4 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: #333;
    }

    .login-panel p {
        font-size: 1rem;
        color: #666;
        margin-bottom: 20px;
    }

    .auth-buttons {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .auth-buttons a {
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        min-width: 100px;
        text-align: center;
    }

    /* Common button styles */
    .btn-login,
    .btn-signup {
        position: relative;
        overflow: hidden;
        z-index: 1;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Swipe effect using ::before */
    .btn-login::before,
    .btn-signup::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        z-index: -1;
        transition: left 0.4s ease;
    }

    /* Login button */
    .btn-login {
        position: relative;
        z-index: 1;
        color: #3F159A;
        /* replaced bg with text color */
        padding: 0.75rem 1.5rem;
        background-color: transparent;
        border: none;
        font-weight: 600;
        cursor: pointer;
        overflow: hidden;
        border-radius: 8px;
        transition: color 0.3s ease;
    }

    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #3F159A, #0056b3);
        /* border gradient */
        z-index: -2;
        border-radius: 8px;
        padding: 2px;
        /* makes room for inner fill */
        -webkit-mask:
            linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: destination-out;
        mask-composite: exclude;
    }

    .btn-login::after {
        content: '';
        position: absolute;
        inset: 0;
        background: transparent;
        z-index: -1;
        border-radius: 8px;
        transition: background 0.4s ease;
    }

    .btn-login:hover {
        color: #3F159A;
    }

    .btn-login:hover::before {
        left: 0;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 86, 179, 0.3);
        color: #3F159A;
    }

    .btn-signup {
        position: relative;
        display: inline-block;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(to right, #3F159A, #0E8FCE);
        border: none;
        border-radius: 6px;
        overflow: hidden;
        z-index: 1;
        cursor: pointer;
        transition: all 0.4s ease;
    }



    .btn-signup:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 86, 179, 0.3);
        color: white;
    }

    /* ================================
   TOP MOVING OFFER BAR (FIXED)
   ================================ */

    .top-offer-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        max-width: 360px;
        /* keeps it compact */
        overflow: hidden;
    }

    /* OFFER badge */
    .offer-badge {
        background: #e60023;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 6px;
        line-height: 1.2;
        white-space: nowrap;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.25);
    }

    /* Marquee container */
    .offer-marquee {
        position: relative;
        width: 240px;
        /* IMPORTANT: controls visibility */
        height: 22px;
        /* SMALL HEIGHT like reference */
        overflow: hidden;
    }

    /* Moving text */
    .offer-marquee-track {
        position: absolute;
        left: 100%;
        top: 0;
        white-space: nowrap;
        font-size: 13px;
        font-weight: 600;
        color: #ffffff;
        line-height: 22px;
        animation: offerScroll 8s linear infinite;
    }

    /* Animation */
    @keyframes offerScroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-160%);
        }
    }

    /* Pause on hover */
    .top-offer-bar:hover .offer-marquee-track {
        animation-play-state: paused;
    }

    /* Hide on mobile (matches your logic) */
    @media (max-width: 768px) {
        .top-offer-bar {
            display: none !important;
        }
    }

    @media (max-width: 768px) {
        .cart-box {
            margin-top: 0px !important;
        }

        .mobile-nav-toggler {
            padding: 0px !important;
        }

        .outer-container {
            padding: 0px 15px 0px 15px !important;
        }

    }

    /* HEADER ORDER NOW BUTTON (COMPACT) */
    .header-order a {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;

        padding: 8px 20px;
        height: 38px;
        min-width: 120px;

        font-size: 14px;
        font-weight: 600;

        color: #ffffff !important;
        background: linear-gradient(135deg, #5b22c6, #4a17a3) !important;

        border-radius: 10px;
        text-decoration: none !important;

        box-shadow: 0 6px 14px rgba(74, 23, 163, 0.3);
        transition: all 0.25s ease;
    }

    /* Hover effect */
    .header-order a:hover {
        background: linear-gradient(135deg, #4a17a3, #3d1288) !important;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(74, 23, 163, 0.4);
    }

    /* Mobile adjustment */
    @media (max-width: 992px) {
        .header-order a {
            padding: 7px 18px;
            height: 36px;
            font-size: 13px;
        }
    }

    @keyframes microSlide {
        0% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-3px);
        }

        100% {
            transform: translateY(0);
        }
    }

    .header-order a {
        animation: microSlide 2.8s ease-in-out infinite;
    }

    .header-order a:hover {
        animation-play-state: paused;
    }
</style>

<header class="main-header header-style-three fixed-header">
    <div class="top-bar">
        <div class="content-wrapper">
            <div class="contact-item">
                <a href="https://wa.me/+447917481696?text=Hi%20Dear,%20I%20need%20assignment%20help.%20I%20got%20your%20number%20from%20the%20website."
                    target="_blank" class="contact-link">
                    <i class="fa fa-whatsapp"></i>
                    <span class="contact-text">+44 7917481696</span>
                </a>
                <span class="divider">|</span>
                <a href="mailto:order@assignnmentinneed.com" target="_blank" class="contact-link">
                    <i class="fa fa-envelope"></i>
                    <span class="contact-text">order@assignnmentinneed.com</span>
                </a>
            </div>

            {{-- <div class="offer-section">
                <div class="offers">
                    <a href="Offers" class="offer-button">Offer</a>
                </div>
                <div style="font-size:14px; font-weight:600;"><a href="/upload-your-assignment" style=" color:white">40%
                        Off On Your Every Order</a></div>
            </div> --}}
            <div class="top-offer-bar">
                <div class="offer-badge">OFFER</div>

                <div class="offer-marquee">
                    <div class="offer-marquee-track">
                        🎁 Special Offer 🎁 40% Off On Every Order
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Header Lower -->
    <div class="header-lower ">
        <div class="lower-inner">
            <div class="outer-container">
                <div class="inner-container" style="display: flex; align-items: center;">

                    <div class="logo-box">
                        <div class="logo"><a href="/"><img src="/assets/media/layout/assignment_logo.webp"
                                    alt="assignment help services assignnmentinneed.com" title="" width="80px"></a>
                        </div>
                    </div>


                    <div class="nav-outer" style="margin-top: 0;">
                        <!-- Mobile Navigation Toggler -->
                        <div class="mobile-nav-toggler" style="margin-top: 6px;"><span class="icon flaticon-menu-2"></span></div>
                        <!-- Main Menu -->
                        <nav class="main-menu show navbar-expand-md">

                            <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
                                <ul class="navigation clearfix">
                                    <li><a href="/">Home</a>

                                    <li class="current dropdown assignment-dropdown">
                                        <a>Services</a>
                                        <ul>

                                            <li class="dropdown"><a href="assignment-writing-uk">Assignment</a>
                                                <ul>
                                                    <li>
                                                        <a href="/english-assignment-writing-help">English</a>
                                                    </li>

                                                    <li>
                                                        <a href="/economic-assignment-writing-help">Economics</a>
                                                    </li>

                                                    <li>
                                                        <a href="/chemistry-assignment-writing-help">Chemistry</a>
                                                    </li>

                                                    <li>
                                                        <a href="/history-assignment-writing-help">History</a>
                                                    </li>

                                                    <li>
                                                        <a href="/law-assignment-writing-help">Law</a>
                                                    </li>

                                                    <li>
                                                        <a href="/linguistic-assignment-writing-help">Linguistic</a>
                                                    </li>

                                                    <li>
                                                        <a href="/nursing-assignment-writing-help">Nursing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/physics-assignment-writing-help">Physics</a>
                                                    </li>

                                                    <li>
                                                        <a href="/sociology-assignment-writing-help">Sociology </a>
                                                    </li>

                                                    <li>
                                                        <a href="/philosophy-assignment-writing-help">Philosophy</a>
                                                    </li>

                                                    <li>
                                                        <a href="/statistics-assignment-writing-help">Statistics</a>
                                                    </li>

                                                    <li>
                                                        <a href="/accounting-assignment-writing-help">Accounting</a>
                                                    </li>

                                                    <li>
                                                        <a href="/marketing-assignment-writing-help">Marketing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/computer-science-assignment-writing-help">Computer Science</a>
                                                    </li>

                                                    <li>
                                                        <a href="/engineering-assignment-writing-help">Engineering</a>
                                                    </li>

                                                    <li>
                                                        <a href="/finance-assignment-writing-help">Finance</a>
                                                    </li>

                                                    <li>
                                                        <a href="/programming-assignment-writing-help">Programming</a>
                                                    </li>

                                                    <li>
                                                        <a href="/management-assignment-writing-help">Management</a>
                                                    </li>

                                                    <li>
                                                        <a href="/business-assignment-writing-help">Business</a>
                                                    </li>

                                                    <li>
                                                        <a href="/math-assignment-help">Maths</a>
                                                    </li>

                                                    <li>
                                                        <a href="/geography-assignment-writing-help">Geography</a>
                                                    </li>

                                                    <li>
                                                        <a href="/psychology-assignment-help-uk">Psychology </a>
                                                    </li>

                                                    <li>
                                                        <a href="/biology-assignment-help-uk">Biology</a>
                                                    </li>

                                                    <li>
                                                        <a href="/entrepreneurship-assignment-help-uk">Entrepreneurship</a>
                                                    </li>

                                                    <li>
                                                        <a href="/artificial-intelligence-assignment-help-uk">Artificial Intelligence</a>
                                                    </li>

                                                    <li>
                                                        <a href="/machine-learning-assignment-help-uk">Machine Learning</a>
                                                    </li>

                                                    <li>
                                                        <a href="/cybersecurity-assignment-help-uk">Cybersecurity</a>
                                                    </li>

                                                    <li>
                                                        <a href="/humanities-assignment-help-uk">Humanities</a>
                                                    </li>
                                                    {{-- <li><a href="assignment-writing-help-services">Assignment
                                                            Writing</a></li>
                                                    <li><a href="cheap-assignment-writing-help">Cheap Assignment Writing
                                                            help</a></li>
                                                    <li><a href="help-with-assignment-online">Help With Assignment
                                                            Online</a></li>
                                                    <li><a href="assignment-helper">Assignment Helper</a></li>
                                                    <li><a href="pay-for-assignment-help">Pay For Assignment Help</a>
                                                    </li>
                                                    <li><a href="best-online-assignment-writing-service">Best Online
                                                            Assignment Writing Service</a></li>
                                                    <li><a href="do-my-assignment-for-me">Do my Assignment for me</a>
                                                    </li>
                                                    <li><a href="academic-assignment-writing-help-service">Academic
                                                            Assignment Writing Help Service</a></li>
                                                    <li><a href="top-assignment-writing-help-service">Top Assignment
                                                            Writing Help Service</a></li> --}}
                                                </ul>
                                            </li>

                                            <li class="dropdown"><a href="dissertation-writing-help-services">Dissertation</a>
                                                <ul>
                                                    <li>
                                                        <a href="/dissertation-literature-review-help-uk">Literature Review Dissertation Writing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/dissertation-findings-help-uk">Findings Dissertation Writing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/dissertation-results-help-uk">Results Dissertation Writing </a>
                                                    </li>

                                                    <li>
                                                        <a href="/dissertation-topic-selection-service-help-uk">Topic Selection Dissertation Service</a>
                                                    </li>

                                                    <li>
                                                        <a href="/masters-dissertation-help-uk">Masters Dissertation Writing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/case-study-dissertation-help-uk">Case Study Dissertation Writing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/dissertation-editing-and-proofreading-help-uk">Editing & Proofreading Dissertation Writing</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="dropdown"><a href="essay-writing-help-services">Essay Writing</a>
                                                <ul>
                                                    <li>
                                                        <a href="/argumentative-essay-help-uk">Argumentative Essay Writing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/analytical-essay-help-uk">Analytical Essay Writing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/reflective-essay-help-uk">Reflective Essay Writing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/literature-review-essay-help-uk">Literature Review Essay Writing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/descriptive-essay-help-uk">Descriptive Essay Writing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/personal-statement-essay-help-uk">Personal Statement Essay Writing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/extended-essay-help-uk">Extended Essay Writing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/essay-proofreading-and-editing-help-uk">Essay Proofreading And Editing</a>
                                                    </li>

                                                    <li>
                                                        <a href="/persuasive-essay-help-uk">Persuasive Essay Writing</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="dropdown"><a href="research-paper-writing-services">Research Paper Writing</a>
                                                <ul>
                                                    <li>
                                                        <a href="/custom-research-paper-help-uk">Custom Research Paper Writing</a>
                                                    </li>
                                                    <li>
                                                        <a href="/masters-research-paper-help-uk">Masters Research Paper Writing</a>
                                                    </li>
                                                    <li>
                                                        <a href="/phd-research-paper-help-uk">Phd Research Paper Writing</a>
                                                    </li>
                                                </ul>
                                            </li>

                                            <li>
                                                <a href="homework-writing-help-services">Homework Writing</a>
                                            </li>

                                            <li>
                                                <a href="/thesis-writing-help">Thesis Writing</a>
                                            </li>

                                            <li>
                                                <a href="/summary-writing-help">Summary Writing</a>
                                            </li>

                                            <li>
                                                <a href="/personal-statement-writing-help">Personal Statement Writing</a>
                                            </li>

                                            <li>
                                                <a href="/coursework-writing-help">Coursework Writing</a>
                                            </li>

                                            <li>
                                                <a href="/term-paper-writing-help">Term Paper Writing</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="current dropdown subject-dropdown">
                                        <a>Subjects</a>
                                        <ul>
                                            <li>
                                                <a href="/math-assignment-help">Maths</a>
                                            </li>

                                            <li>
                                                <a href="/english-assignment-writing-help">English </a>
                                            </li>

                                            <li>
                                                <a href="/economic-assignment-writing-help">Eco</a>
                                            </li>

                                            <li>
                                                <a href="/chemistry-assignment-writing-help">Chemistry</a>
                                            </li>

                                            <li>
                                                <a href="/history-assignment-writing-help">History </a>
                                            </li>

                                            <li>
                                                <a href="/law-assignment-writing-help">Law</a>
                                            </li>

                                            <li>
                                                <a href="/linguistic-assignment-writing-help">Linguistic</a>
                                            </li>

                                            <li>
                                                <a href="/nursing-assignment-writing-help">Nursing</a>
                                            </li>

                                            <li>
                                                <a href="/physics-assignment-writing-help">Physics</a>
                                            </li>

                                            <li>
                                                <a href="/sociology-assignment-writing-help">Sociology </a>
                                            </li>

                                            <li>
                                                <a href="/philosophy-assignment-writing-help">Philosophy</a>
                                            </li>

                                            <li>
                                                <a href="/statistics-assignment-writing-help">Statistics</a>
                                            </li>

                                            <li>
                                                <a href="/accounting-assignment-writing-help">Accounting</a>
                                            </li>

                                            <li>
                                                <a href="/programming-assignment-writing-help">Programming</a>
                                            </li>

                                            <li>
                                                <a href="/marketing-assignment-writing-help">Marketing</a>
                                            </li>

                                            <li>
                                                <a href="/computer-science-assignment-writing-help">Computer Science</a>
                                            </li>

                                            <li>
                                                <a href="/engineering-assignment-writing-help">Engineering </a>
                                            </li>

                                            <li>
                                                <a href="/finance-assignment-writing-help">Finance </a>
                                            </li>

                                            <li>
                                                <a href="/management-assignment-writing-help">Management</a>
                                            </li>

                                            <li>
                                                <a href="/business-assignment-writing-help">Business</a>
                                            </li>

                                            <li>
                                                <a href="/geography-assignment-writing-help">Geography</a>
                                            </li>

                                            <li>
                                                <a href="/psychology-assignment-help-uk">Psychology </a>
                                            </li>

                                            <li>
                                                <a href="/biology-assignment-help-uk">Biology</a>
                                            </li>

                                            <li>
                                                <a href="/entrepreneurship-assignment-help-uk">Entrepreneurship</a>
                                            </li>

                                            <li>
                                                <a href="/artificial-intelligence-assignment-help-uk">Artificial Intelligence</a>
                                            </li>

                                            <li>
                                                <a href="/machine-learning-assignment-help-uk">Machine Learning</a>
                                            </li>

                                            <li>
                                                <a href="/cybersecurity-assignment-help-uk">Cybersecurity</a>
                                            </li>

                                            <li>
                                                <a href="/humanities-assignment-help-uk">Humanities</a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="current dropdown city-dropdown">
                                        <a>city</a>
                                        <ul>
                                            <li>
                                                <a href="/uk/london">London</a>
                                            </li>

                                            <li>
                                                <a href="/uk/manchester/assignment-help">Manchester</a>
                                            </li>

                                            <li>
                                                <a href="/uk/birmingham/assignment-help">Birmingham</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-leeds">Leeds</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-glasgow">Glasgow</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-edinburgh">Edinburgh</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-liverpool">Liverpool</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-nottingham">Nottingham</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-bristol">Bristol</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-sheffield">Sheffield</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-newcastle">Newcastle</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-coventry">Coventry</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-cardiff">Cardiff</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-oxford">Oxford</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-cambridge">Cambridge</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-belfast">Belfast</a>
                                            </li>

                                            <li>
                                                <a href="/uk/assignment-help-perth">Perth</a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="current dropdown">
                                        <a>Resources </a>
                                        <ul>
                                            <li>
                                                <a href="free-samples">Samples</a>
                                            </li>

                                            <li>
                                                <a href="blog">Blog</a>

                                            </li>
                                        </ul>
                                    </li>

                                    <li><a href="pricing">Pricing</a></li>
                                    <li><a href="review">Reviews</a></li>
                                    <li><a href="writers">Writer</a></li>

                                    <li><a href="what-we-are">About Us</a></li>

                                    <li><a href="contact-us">Contact Us</a></li>

                                    <li class="header-order">
                                        <a href="upload-your-assignment">Order Now</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                        <!-- Main Menu End-->

                        <div class="outer-box" style="padding: 10px 0;">
                            <div class="cart-box" style="margin-top: 0; margin-left: 0; border:none;">
                                <div class="dropdown user-dropdown">
                                    <button class="cart-box-btn dropdown-toggle" type="button" id="dropdownMenu2"
                                        data-toggle="dropdown">
                                        <i class="fas fa-user-alt" style="font-size: 26px;"></i>
                                    </button>

                                    <div class="dropdown-menu cart-panel" aria-labelledby="dropdownMenu2">
                                        @auth
                                        <div class="cart-product">
                                            <div class="inner">
                                                <div class="image">
                                                    <img src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset('assets/media/avatars/blank.png') }}"
                                                        alt="User Profile" />
                                                </div>
                                                <h3>{{ Auth::user()->name }}</h3>
                                                <div class="quantity-text">{{ Auth::user()->email }}</div>
                                                <div class="quantity-text">{{ Auth::user()->mobile }}</div>
                                            </div>

                                            <ul class="btns-boxed">
                                                <li><a href="/myProfile">View Profile</a></li>
                                                <li><a href="/MyOrders">My Orders</a></li>
                                                {{-- 👇 Wallet sirf login hone par hi dikhega --}}
                                                <li><a href="/wallet">Wallet</a></li>

                                                <li>
                                                    <a href="{{ route('logout') }}"
                                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                        Sign Out
                                                    </a>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                        class="d-none">
                                                        @csrf
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        @else
                                        <div class="login-panel">
                                            <h4>Welcome!</h4>
                                            <p>Please login or sign up to continue.</p>
                                            <div class="auth-buttons">
                                                <a href="/login" class="btn-login">Login</a>
                                                <a href="/register" class="btn-signup">Sign Up</a>
                                            </div>
                                        </div>
                                        @endauth
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End Header Upper-->

        <!-- Mobile Menu  -->
        <div class="mobile-menu">
            <div class="menu-backdrop"></div>
            <div class="close-btn"><span class="icon flaticon-multiply"></span></div>

            <nav class="menu-box mCustomScrollbar _mCS_1 mCS_no_scrollbar">
                <div id="mCSB_1" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0"
                    style="max-height: 1013px;">
                    <div id="mCSB_1_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y"
                        style="position:relative; top:0; left:0;" dir="ltr">
                        <div class="nav-logo" style="height: 50px; text-align: center;"><a href="/"><img
                                    src="/assets/media/layout/assignment_logo.webp"
                                    alt="assingment help assignnmentinneed.com" title="" class="mCS_img_loaded" width="60"></a></div>
                        <div class="menu-outer">
                            <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->

                            <div class="navbar-header">
                                <button class="navbar-toggler" type="button" data-toggle="collapse"
                                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>

                            <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
                                <ul class="navigation clearfix">
                                    @auth
                                    <li class="mobile-auth-user" style="padding:12px 15px;">
                                        <div style="display:flex; gap:10px; align-items:center;">
                                            <img style="width:40px; height:40px; border-radius:50%; object-fit:cover;"
                                                src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset('assets/media/avatars/blank.png') }}"
                                                alt="User Profile" />
                                            <div>
                                                <div style="font-weight:600; font-size:14px; line-height:1.2;">
                                                    {{ Auth::user()->name }}
                                                </div>
                                                <div style="font-size:12px; opacity:.8;">{{ Auth::user()->email }}</div>
                                                <div style="font-size:12px; opacity:.8;">{{ Auth::user()->mobile }}</div>
                                            </div>
                                        </div>
                                    </li>

                                    <li><a href="/myProfile">View Profile</a></li>
                                    <li><a href="/MyOrders">My Orders</a></li>
                                    <li><a href="/wallet">Wallet</a></li>

                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                                            Sign Out
                                        </a>
                                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                    @else
                                    <li><a href="/login">Login</a></li>
                                    <li><a href="/register">Sign Up</a></li>
                                    @endauth
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="mCSB_1_scrollbar_vertical"
                        class="mCSB_scrollTools mCSB_1_scrollbar mCS-light mCSB_scrollTools_vertical"
                        style="display: none;">
                        <div class="mCSB_draggerContainer">
                            <div id="mCSB_1_dragger_vertical" class="mCSB_dragger"
                                style="position: absolute; min-height: 30px; height: 0px; top: 0px;"
                                oncontextmenu="return false;">
                                <div class="mCSB_dragger_bar" style="line-height: 30px;"></div>
                            </div>
                            <div class="mCSB_draggerRail"></div>
                        </div>
                    </div>
                </div>
            </nav>
        </div> <!-- End Mobile Menu -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const header = document.querySelector(".main-header.header-style-three");
                if (!header) return;

                let headerHeight = header.offsetHeight;

                const refreshHeight = () => (headerHeight = header.offsetHeight);
                window.addEventListener("resize", refreshHeight);

                window.addEventListener("scroll", function() {
                    // ✅ Only apply on desktop
                    if (window.innerWidth < 768) return;

                    if (header.classList.contains("fixed-header")) {
                        document.body.style.paddingTop = headerHeight + "px";
                    } else {
                        document.body.style.paddingTop = "";
                    }
                }, {
                    passive: true
                });
            });
        </script>

</header>