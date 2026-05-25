@extends('frontend-layouts.app')

@section('content')

    <section class="bg-hero">
        <div class="container-section">
            <div class="hero-content">
                <!-- Content Column -->
                <div class="content-column">
                    <h1 class="main-heading">
                        Top UK Persuasive Essay Writing Service: Skilled Argumentation, Academic Excellence
                    </h1>
                    <div class="content-paragraph">
                        <p>Secure excellent grades with meticulously crafted, persuasive essays designed for the high
                            standards of UK universities. Our professional team offers specialised persuasive essay writing
                            help UK, producing papers that combine logical structure, strong evidence, and impactful
                            arguments to impress academic evaluators.
                        </p>
                    </div>

                    <div class="content-list-item">
                        <ul class="content-list">
                            <li>AI Free Content </li>
                            <li>Ph.D. Writes </li>
                            <li>24/7 Live Support</li>
                        </ul>
                        <ul class="content-list">
                            <li>Free Revisions</li>
                            <li>Plagiarism Free</li>
                            <li>Refund Guarantee</li>
                        </ul>
                    </div>

                    <div class="Trusted-paragraph">
                        <p>Trusted by <b>5,126+&nbsp;students </b>- Rated 4.8/5 Across All Platforms</p>
                    </div>

                    @include('components.review-section')
                </div>

                <!-- Form Column -->
                <div class="form-column">
                    <div class="form-container">
                        @include('new-form')
                    </div>
                </div>
            </div>
        </div>

        <style>
            .bg-hero {
                background: url(/images/background/home-bg.avif) no-repeat center center;
                background-size: cover;
                padding: 3rem 2rem;
                box-shadow: rgba(0, 0, 0, 0.06) 0px 2px 4px inset;
            }

            .container-section {
                max-width: 1200px;
                margin: auto;
                padding: 0 0.5rem;
            }

            .hero-content {
                display: flex;
                flex-direction: row;
                gap: 2rem;
                opacity: 0;
                transform: translateY(40px);
                animation: fadeSlideIn 0.8s ease-out 0.2s forwards;
            }

            .form-container {
                margin: auto;
                display: flex;
                justify-content: center;
            }

            .main-heading {
                background: linear-gradient(to bottom, #3F159A, #0E8FCE);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                font-size: 2.5rem;
                font-weight: 900;
                line-height: 1.3;
                margin-bottom: 0.25rem;
            }

            .content-paragraph p {
                font-size: 1.1rem;
                color: #000;
                font-weight: 600;
                margin-bottom: 1rem;
                text-align: justify;
            }

            .content-list-item {
                display: flex;
                flex-wrap: wrap;
                gap: 1.2rem;
            }

            .content-list {
                padding-left: 20px;
                margin: 0;
            }

            .content-list li {
                color: #333;
                list-style-type: square;
                font-size: 1rem;
                margin-bottom: 0.25rem;
            }

            .Trusted-paragraph {
                margin-top: 1rem;
                font-size: 1rem;
                font-weight: 500;
            }

            /* Lightweight compositor-friendly animation */
            @keyframes fadeSlideIn {
                0% {
                    opacity: 0;
                    transform: translateY(40px);
                }

                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsive styles */
            @media (max-width: 768px) {
                .hero-content {
                    flex-direction: column;
                }

                .main-heading {
                    font-size: 2rem;
                }

                .content-paragraph p {
                    font-size: 0.70rem;
                    text-align: left;
                    margin-bottom: 0.25rem;
                }

                .content-list li {
                    font-size: 0.70rem;
                }

                .content-list-item {
                    gap: 0.25rem;
                }

                .Trusted-paragraph {
                    margin-top: 0.20rem;
                    font-size: 0.70rem;
                }
            }

            @media (max-width: 600px) {
                .bg-hero {
                    padding: 0.25rem 0.25rem;
                }

                .main-heading {
                    font-size: 1.4rem;
                }

                .content-list-item {
                    /* flex-direction: column; */
                    gap: 0.25rem;
                }


            }

            @media (max-width: 1154px) {
                .content-list-item {
                    gap: 0;
                }
            }
        </style>
    </section>

    <section class="how-it-works-lazy">
        <div class="auto-container">
            <h2 class="section-title">
                How Our Professional Online Persuasive Essay Help UK Functions
            </h2>
            <p>Our streamlined, easy-to-follow process ensures you receive the best persuasive essay writing services UK
                with minimal stress. Through a secure, step-by-step approach, we connect you with experienced writers who
                specialise in crafting persuasive essays tailored to UK university standards.</p>
            <div class="content-wrapper">
                <!-- Left Side: YouTube Lazy Video -->
                <div class="video-column">
                    <div class="youtube-facade">
                        <img src="images/assignment-help-process-uk.webp" alt="How assignment help works in UK"
                            loading="lazy" />
                        <!-- <div class="play-button"></div> -->
                    </div>
                </div>

                <!-- Right Side: All Steps -->
                <div class="steps-column">
                    <div class="step">
                        <h3 style="margin-bottom: 0;"><span class="step-number" style="margin: 0;">1. </span> Share Your
                            Requirements</h3>
                        <p>Share your assignment details—subject, topic, word count, and deadline—or upload your requirement
                            file. We use these precise instructions to match your order with the most qualified expert in
                            your field</p>
                    </div>

                    <div class="step">
                        <h3 style="margin: 0;"><span class="step-number" style="margin: 0;">2. </span> Make Payment</h3>
                        <p>Complete your secure payment through your preferred method. Once payment is confirmed, your
                            dedicated expert will begin working on your assignment immediately.</p>
                    </div>

                    <div class="step">
                        <h3 style="margin: 0;"><span class="step-number" style="margin: 0;">3. </span> Get Expert Help</h3>
                        <p>Monitor your order in real time through your personalised dashboard, from the initial draft stage
                            to the final quality check. You will be notified the moment your completed document is ready for
                            download.</p>
                    </div>

                    <div class="step">
                        <h3 style="margin: 0;"><span class="step-number" style="margin: 0;">4. </span> Receive Solution</h3>
                        <p>Receive your crafted document by your specified deadline. Review the content and, if necessary,
                            request any minor refinements or revisions for free within the specified revision period.</p>
                    </div>

                    <a href="https://www.assignnmentinneed.com/upload-your-assignment">
                        <button class="place-order-btn">Get Our Assistance</button>
                    </a>
                </div>
            </div>
        </div>
        <style>
            .how-it-works-lazy {
                padding: 2rem 1.5rem;
                background: #fff;
                text-align: center;
            }

            .content-wrapper {
                display: flex;
                gap: 20px;
                align-items: center;
                justify-content: space-around;
                flex-wrap: wrap;
            }

            /* Lazy Video Facade */
            .video-column {
                flex: 1;
                min-width: 300px;
                max-width: 600px;
                text-align: center;
                position: relative;
            }

            .youtube-facade {
                position: relative;
                width: 100%;
                aspect-ratio: 16 / 9;
                border-radius: 12px;
                overflow: hidden;
                cursor: pointer;
            }

            .youtube-facade img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .play-button {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 64px;
                height: 64px;
                background: url('/assets/play-button.svg') no-repeat center center;
                background-size: contain;
                transform: translate(-50%, -50%);
                opacity: 0.9;
            }

            /* Steps section */
            .steps-column {
                flex: 1;
                max-width: 600px;
                text-align: left;
            }

            .step {
                margin-bottom: 30px;
            }

            .step-number {
                font-size: 28px;
                color: #5e2ced;
                font-weight: bold;
                display: inline-block;
                margin-bottom: 8px;
            }

            .step h3 {
                font-size: 22px;
                margin: 8px 0;
                color: #333;
            }

            .step p {
                font-size: 16px;
                color: #555;
                line-height: 1.6;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .content-wrapper {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 20px;
                }

                .step-number {
                    font-size: 24px;
                }

                .step h3 {
                    font-size: 16px;
                }

                .step p {
                    font-size: 12px;
                }
            }

            @media (max-width: 480px) {
                .step {
                    margin-bottom: 0px;
                }

                .steps-column {
                    max-height: 420px;
                    /* adjust height as needed */
                    /* overflow-y: auto; */
                    padding-right: 10px;
                    /* space for scrollbar */
                }

                /* Optional: smooth scrollbar styling */
                .steps-column::-webkit-scrollbar {
                    width: 6px;
                }

                .steps-column::-webkit-scrollbar-thumb {
                    background: #aaa;
                    border-radius: 6px;
                }

                .how-it-works-lazy {
                    padding: 0 !important;
                }

                .how-it-works-lazy .auto-container {
                    padding: 5px !important;
                }

                .content-wrapper {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 5px;
                }

                .step-number {
                    font-size: 20px;
                }

                .step h3 {
                    font-size: 16px;
                }

                .step p {
                    font-size: 11px;
                    text-align: justify;
                }

                /*.place-order-btn { width: 200px; font-size: 13px; padding: 8px; }*/
                .place-order-btn {
                    width: 200px;
                    font-size: 13px;
                    padding: 2px;

                }

                .place-order-btn:hover {
                    transform: translateY(-5px) scale(1.05);
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
                }
            }
        </style>
    </section>

    <x-common-section.benifits-of-assignment-cards
        title="Our Commitments When You Choose Our Persuasive Essay Writing Services UK"
        subtitle="Selecting our experts means choosing a service that values quality, originality, and academic trust. When you rely on our persuasive essay writing services UK, you receive far more than a simple essay - you receive a complete set of guarantees designed to support your academic success."
        :items="[
            [
                'icon' => 'fas fa-clock-o',
                'heading' => 'Authentic, Human-Written Arguments – No AI Used',
                'paragraph' =>
                    'Your persuasive essay is crafted entirely by real academic writers. We do not use AI tools or automated writing systems. Every paper is written from scratch, ensuring strong logic, credible reasoning, and genuine argumentative depth. This makes our online persuasive essay UK service dependable and academically safe.',
            ],

            [
                'icon' => 'fas fa-edit',
                'heading' => 'Verified Plagiarism-Free Essays',
                'paragraph' =>
                    'Before delivery, your essay is checked through trusted plagiarism scanners and confirmed 100% original. Whether you order persuasive essay help UK or request a full rewrite, your paper comes with complete originality assurance - perfect for safe submission within UK universities.',
            ],

            [
                'icon' => 'fas fa-headset',
                'heading' => 'Expert Writers with Advanced Qualifications',
                'paragraph' =>
                    'A strong persuasive essay requires solid research and a refined argument structure. That’s why your order is handled only by writers with postgraduate credentials. When you choose our persuasive essay writing UK, you’re supported by professionals who understand UK academic expectations deeply.',
            ],

            [
                'icon' => 'fas fa-ban',
                'heading' => 'Continuous Support - Available 24/7',
                'paragraph' =>
                    'Need an update? Last-minute question? Our support team never closes. We provide 24/7 assistance to ensure your experience with our persuasive essay writing services UK stays smooth and stress-free at all times.',
            ],

            [
                'icon' => 'fas fa-line-chart',
                'heading' => 'Free Revisions Until You’re Fully Satisfied',
                'paragraph' =>
                    'Your satisfaction is central to our service. If you want improvements or refinements, we offer free revisions until the essay perfectly reflects your instructions. This makes our platform the best persuasive essay help UK for students seeking precision and reliability.',
            ],

            [
                'icon' => 'fas fa-lock',
                'heading' => 'On-Time Delivery + Strong Refund Protection',
                'paragraph' =>
                    'Deadlines are non-negotiable in the academic world. We guarantee delivery before your submission date, no matter how urgent your request. And with our refund protection policy, your investment in online persuasive essay writing services UK remains completely secure.',
            ],
        ]" />

    <section class="page-section testimonial-section-four">
        <div class="container">
            <h2 class="section-title">Meet Your UK-Verified Expert for Persuasive Essay Writing Services UK</h2>

            <p class="section-description">
                Finding the right academic specialist makes all the difference in achieving a high-scoring persuasive essay. Our platform connects you with UK-verified, highly qualified writers, each holding a Master’s or PhD degree.
            </p>

            <div class="inner-container my-4">
                <div class="hm4-testimonial-carousel owl-carousel owl-theme">
                    @foreach ($data['expert'] as $expert)
                        <div class="testimonial-block-four item">
                            <div class="expert-card">
                                <!-- <img src="{{ '/images/expert-default.png' }}" alt="Expert {{ $expert['name'] }}"
                                                                                                                                                                                                                                                                                                                                                                                                        class="expert-img"> -->
                                <div class="image-outer">
                                    <div class="image">
                                        <img src="{{ $expert['image'] ?? '/images/default-expert.jpg' }}"
                                            alt="Expert {{ $expert['name'] }}" class="">
                                    </div>
                                </div>
                                <h3 class="expert-name">{{ $expert['name'] ?? 'Dr. Expert' }}</h3>

                                @php
                                    $reviews = json_decode($expert->customer_review, true) ?? [];
                                    $totalReviews = count($reviews);
                                    $averageRating = $totalReviews > 0 ? round(array_sum(array_column($reviews, 'rating')) / $totalReviews, 1) : 0;
                                    $fullStars = floor($averageRating);
                                    $halfStar = ($averageRating - $fullStars) >= 0.5;
                                @endphp

                                <div class="stars " style="text-align: right;">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $fullStars)
                                            <i style="color:gold" class="fa fa-star"></i>
                                        @elseif ($halfStar && $i == $fullStars + 1)
                                            <i style="color:gold" class="fa fa-star-half-o"></i>
                                        @else
                                            <i style="color:gold" class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>

                                <div style="text-align: right;">
                                    <a href="/free-samples">View Sample</a>
                                </div>

                                <div class="expert-description-wrapper" style="margin: 15px 0px 8px;">
                                    <p class="expert-description">{!! $expert->content !!}</p>
                                </div>

                                <div class="expert-box">
                                    <div class="expert-box-item">
                                        <span class="expert-box-value">{{$expert->finish_order}}</span>
                                        <div class="expert-box-details">
                                            <div class="expert-box-label">Completed</div>
                                        </div>
                                    </div>
                                    <div class="expert-box-divider"></div>
                                    <div class="expert-box-item">
                                        <span class="expert-box-value">{{$expert->inprogress_order}}</span>
                                        <div class="expert-box-details">
                                            <div class="expert-box-label">In Progress</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="buttons">
                                    <a href="{{ url('/upload-your-assignment') }}" class="hire-btn">Hire Writer</a>
                                    <a href="{{ url('writers/' . $expert->slug) }}" class="about-btn">About Writer</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="custom-nav-btn">
                    <button class="custom-prev"><i class="fa fa-chevron-left"></i></button>
                    <button class="custom-next"><i class="fa fa-chevron-right"></i></button>
                </div>
            </div>
        </div>

        <style>
            .expert-box {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin: 10px 0px 20px;
            }

            .expert-box-label {
                font-size: 17px;
                font-weight: 700;
            }

            .expert-box-value {
                font-size: 17px;
                font-weight: 400;
                color: blue;
                font-weight: 700;
            }

            .expert-box-item {
                width: 100%;
            }

            .expert-box-divider {
                width: 3px;
                height: 30px;
                background-color: #e0e0e0;
            }

            .image-outer {
                position: absolute;
                top: 18px;
                left: 40px;
                width: 80px;
                height: 80px;
                overflow: hidden;
                border-radius: 50%
            }

            .image {
                width: 80px;
                height: 80px;
                overflow: hidden;
                border-radius: 50%;
                background: white;
                padding: 2px;
                margin: 0 auto;
                border: 3px solid #7f3ffb;
                box-shadow: 0 0 0 5px #e4d9ff;
            }

            .image img {
                width: 100%;
                height: 100%;
                border-radius: 50%;
                object-fit: cover;
            }

            .expert-description-wrapper {
                line-height: 24px;
                max-height: 80px;
                /* 3 lines */
                overflow-y: auto;
            }

            .expert-description-wrapper::-webkit-scrollbar {
                width: 2px;
            }

            .expert-description-wrapper::-webkit-scrollbar-thumb {
                background-color: #ccc;
                border-radius: 10px;
            }

            .expert-description-wrapper::-webkit-scrollbar-track {
                background: transparent;
            }

            .testimonial-section-four {
                max-width: 100%;
                padding: 20px 0px !important;
            }

            .star-rating {
                font-size: 20px;
                margin: 5px auto;
                color: gold;
            }

            .expert-slider {
                overflow: hidden;
                position: relative;
                max-width: 1000px;
                margin: auto;
            }

            .slider-container {
                display: flex;
                width: 200%;
                animation: slide 20s infinite linear;
            }

            .expert-card {
                background: white;
                padding: 20px;
                position: relative;
                margin: 10px;
                border-radius: 10px;
                border: 2px solid #4821c3;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                flex: 0 0 20%;
                text-align: center;
                transition: .3s;
            }

            .expert-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            }

            .expert-img {
                margin: -10px auto;
                height: 100px;
                width: 100px !important;
                border-radius: 50%;
                margin-bottom: 5px;
            }

            .expert-name {
                font-size: 1.2rem;
                font-weight: bold;
                margin-bottom: 0px;
                color: #4821c3;
                text-align: right;
            }

            .expert-card p {
                font-size: .9rem !important;
                line-height: 1.5;
                color: #333;
                text-align: justify;
                margin-bottom: 15px;
            }

            .buttons {
                display: flex;
                margin: auto;
                font-size: .9rem;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }

            .hire-btn {
                background: #4821c3;
                color: white;
                padding: 10px 15px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: 0.3s;
            }

            .hire-btn:hover {
                background: #35189f;
                transform: translateY(-5px);
                background: linear-gradient(135deg, #4821c3, #35189f);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
                color: white;
            }

            .about-btn {
                background-color: white;
                border: 3px solid #4821c3;
                color: #4821c3;
                padding: 8px 15px;
                border-radius: 5px;
                cursor: pointer;
                transition: 0.3s;
            }

            .about-btn:hover {
                background-color: #4821c3;
                color: white;

            }

            @keyframes slide {
                0% {
                    transform: translateX(0);
                }

                100% {
                    transform: translateX(-50%);
                }
            }



            @media (max-width: 768px) {
                .expert-card {
                    flex: 0 0 90%;
                    margin: 0 auto 20px;
                }

                .expert-name {
                    font-size: 1.2rem;
                    margin-bottom: 10px;
                }

                .expert-card p {
                    line-height: 1.5;
                }

                .buttons {
                    flex-direction: row;
                    gap: 10px;
                }

                .hire-btn,
                .about-btn {
                    width: 100%;
                    font-size: 1rem;
                    padding: 10px;
                }
            }

            @media (max-width: 480px) {
                .expert-card {
                    flex: 0 0 100%;
                    padding: 15px;
                    margin: 0 auto 15px;
                }

                .expert-img {
                    height: 70px;
                    width: 70px !important;
                }

                .hire-btn,
                .about-btn {
                    font-size: .7rem;
                    padding: 6px;
                }

                .custom-nav-btn {
                    text-align: center;
                    margin-top: -0.5rem;
                }

                .testimonial-section-four {
                    padding: 0px 0px !important;
                }

            }

            .custom-nav-btn button {
                background-color: #6C63FF;
                /* Purple */
                color: white;
                border: none;
                padding: 10px 20px;
                font-size: 16px;
                border-radius: 8px;
                margin: 0 10px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .custom-nav-btn button:hover {
                background-color: #5a52d1;
                /* Darker purple on hover */
            }
        </style>

        <style>
            .play-button {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 70px;
                height: 70px;
                background: rgba(0, 0, 0, 0.6);
                border-radius: 50%;
                border: 3px solid white;
            }

            .play-button::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-40%, -50%);
                border-style: solid;
                border-width: 15px 0 15px 25px;
                border-color: transparent transparent transparent white;
            }

            @media (max-width: 480px) {
                .video-grid {
                    padding: 10px 0px;
                    gap: 10px;
                }
            }
        </style>

        <!-- Owl Carousel CSS -->
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

        <!-- Optional theme -->

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Owl Carousel JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>


        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Destroy any existing Owl Carousel instances first
                const $carousel = $('.hm4-testimonial-carousel');

                if ($carousel.hasClass('owl-loaded')) {
                    $carousel.trigger('destroy.owl.carousel');
                    $carousel.removeClass('owl-loaded owl-carousel owl-theme').find('.owl-stage-outer').children().unwrap();
                }

                const totalItems = $carousel.find('.item').length;
                const maxVisible = 3;

                // Reinitialize with forced loop=false
                $carousel.addClass('owl-carousel owl-theme').owlCarousel({
                    loop: false,                   // Force no loop
                    rewind: true,                 // Scrolls back to start at the end
                    margin: 20,
                    nav: false,
                    autoplay: false,              // Disable autoplay for full control
                    responsive: {
                        0: { items: 1 },
                        768: { items: 2 },
                        1024: { items: maxVisible }
                    }
                });

                // Custom navigation
                $('.custom-next').off('click').on('click', function () {
                    $carousel.trigger('next.owl.carousel');
                });

                $('.custom-prev').off('click').on('click', function () {
                    $carousel.trigger('prev.owl.carousel');
                });
            });
        </script>
    </section>

    @include('home.section.ticket')

    @include('home.section.numbers')

    <x-common-section.academic--writing-cards heading="Expand Your Academic Support Options with Our UK Essay Assistance Services"
        paragraph="While our focus is on crafting compelling, persuasive essays, many students also require wider academic help throughout their studies. To support every stage of your coursework, we provide multiple specialised services beyond persuasive essay writing services UK - each designed to match strict UK university expectations."
        :cards="[
            [
                'heading' => 'Essay Proofreading and Editing Services UK',
                'href' => '/essay-proofreading-and-editing-help-uk',
                'paragraph' =>
                    'If your draft needs stronger clarity, structure, or academic tone, our editors will refine your argument, correct logical gaps, and strengthen your evidence. This ensures your essay meets UK assessment standards with improved readability and academic precision.',
            ],
            [
                'heading' => 'Extended Essay Writing UK',
                'href' => '/extended-essay-help-uk',
                'paragraph' =>
                    'Tackling complex subjects can be overwhelming. Our extended essay writers break down intricate themes, analyse theoretical frameworks, and present well-developed arguments suitable for higher-level coursework across science, humanities, business, and social sciences.',
            ],

            [
                'heading' => 'Personal Statement Essay Writers UK',
                'href' => '/personal-statement-essay-help-uk',
                'paragraph' =>
                    'Whether applying for university, postgraduate study, or professional training, we transform your personal experiences into a persuasive, well-structured narrative. This service ensures your personal statement is coherent, reflective, and aligned with UK academic expectations.',
            ],

            [
                'heading' => 'Descriptive Essay Writing UK',
                'href' => '/descriptive-essay-help-uk',
                'paragraph' =>
                    'If your task requires vivid explanation or detailed academic description, our experts craft descriptive essays with strong imagery, clarity, and accuracy. We ensure each section communicates ideas clearly while maintaining the formal standards of UK university writing.',
            ],

            [
                'heading' => 'Comparative Essay Help UK',
                'href' => '/uk/compare-and-contrast-essay-writing-help',
                'paragraph' =>
                    'When comparing theories, texts, or case studies, our specialists create clear, structured comparative essays that highlight meaningful contrasts and connections. Each paper moves beyond simple comparison to deliver insightful, academically sound conclusions - ideal for high-level UK coursework.',
            ],


        ]" />

    @include('components.common-section.whatsapp')

    {{-- scrolling content --}}
    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">
                <div class="column">
                    <div class="content-box">
                        <h2>Mastering the Art of Persuasive Essay Writing UK for Top Academic Performance</h2>
                        <p class="content-description">
                            Crafting a high-quality online persuasive essay UK is not just about presenting information; it is about constructing arguments that influence, engage, and meet UK academic expectations. Writing persuasive essays requires intellectual precision, research acumen, and a clear understanding of logical structures. The pressure of tight deadlines, complex topics, and the need for evidence-based reasoning often makes this task challenging. Our persuasive <a href="/essay-writing-help-services">essay writing help uk</a> is designed to address these challenges, ensuring that every essay achieves superior academic standards and maximises your grades.
                        </p>

                        <h3>Expertise that Defines the Best Persuasive Essay Writing Service UK</h3>
                        <p class="content-description">
                            Our team consists of highly qualified writers, each with strong UK academic credentials:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Professional Academic Writers: </b>Every essay is crafted by writers with postgraduate qualifications, ensuring subject-matter expertise. When you seek persuasive essay writing UK, you are guaranteed work that meets your specific discipline’s expectations.
                            </li>
                            <li class="list-group-item"><b>Tailored Approach for UK Assessment Standards: </b>Our experts are familiar with UK marking rubrics and construct essays targeting high scores in argument strength, evidence use, coherence, and critical engagement.
                            </li>
                            <li class="list-group-item"><b>Precision in Scholarly Expression: </b>Every essay utilises appropriate terminology and is supported by current, authoritative references, signalling a professional command of the topic.
                            </li>
                        </ul>

                        <h3>Constructing a Persuasive Argument with Clarity</h3>
                        <p class="content-description">
                            Developing a persuasive essay requires a systematic approach:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Strategic Thesis Formation: </b>Your essay begins with a precise and debatable thesis, forming the foundation of the argument.
                            </li>
                            <li class="list-group-item"><b>Logical Paragraph Structure: </b>Each paragraph presents a claim, supporting evidence, and reasoning linked to the main argument.
                            </li>
                            <li class="list-group-item"><b>Effective Counter-Argument Integration: </b>We anticipate opposing views and refute them convincingly, demonstrating critical thinking and analytical depth.
                            </li>
                            <li class="list-group-item"><b>Evidence-Based Support: </b>Claims are backed by peer-reviewed sources, official reports, and credible statistics, maintaining academic integrity.
                            </li>
                        </ul>

                        <h3>The Benefits of Using Online Persuasive Essay Writing Services UK</h3>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Time Efficiency: </b>With expert assistance, you can meet tight deadlines while ensuring essay quality.
                            </li>
                            <li class="list-group-item"><b>Academic Confidence: </b>Knowing your essay has been crafted professionally provides reassurance and reduces stress.
                            </li>
                            <li class="list-group-item"><b>Skill Development: </b>Observing expert structures and argumentation improves your own writing techniques.
                            </li>
                            <li class="list-group-item"><b>Customisation: </b>Essays are tailored to your topic, referencing style, and course requirements.
                            </li>
                        </ul>

                        <h3>Why Students Seek Persuasive Essay Help UK</h3>
                        <p class="content-description">
                            Many students find it challenging to combine logical reasoning, emotional appeal, and credible evidence in a single essay. This is especially true in disciplines requiring critical thinking, such as business ethics, social sciences, politics, and environmental studies. Seeking help me persuasive essay writing UK ensures that:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Arguments are coherent and persuasive.
                            </li>
                            <li class="list-group-item">Evidence is relevant, high-quality, and cited correctly.
                            </li>
                            <li class="list-group-item">The essay aligns with UK academic conventions.
                            </li>
                        </ul>

                        <h3>Commitment to Ethical and Quality Standards</h3>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Plagiarism-Free Guarantee: </b>Every essay is original and written from scratch.
                            </li>
                            <li class="list-group-item"><b>AI-Free Assurance: </b>All content is human-written to maintain nuance and intellectual depth.
                            </li>
                            <li class="list-group-item"><b>Data Security: </b>All personal details and essay requirements remain strictly confidential.
                            </li>
                        </ul>

                        <h3>Final Value for Academic Success</h3>
                        <p class="content-description">
                            By choosing our persuasive essay writing services UK, students receive more than a written paper-they gain:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">A structured, high-quality essay designed for maximum grades.
                            </li>
                            <li class="list-group-item">Insights into academic writing strategies for future assignments.
                            </li>
                            <li class="list-group-item">Confidence to tackle complex essay topics independently.
                            </li>
                        </ul>
                        <p class="content-description">
                            Whether you need guidance on thesis creation, paragraph development, evidence integration, or final proofreading, our best persuasive essay help UK guarantees an academic edge, helping you excel in UK university assessments.
                        </p>
                    </div>


                </div>

                <div class="column">
                    <div class="content-box">
                        <h2>Achieving Excellence in Online Persuasive Essay Writing UK
                        </h2>
                        <p class="content-description">Producing a high-quality persuasive essay for UK universities requires more than basic writing skills-it demands strategic thinking, credible evidence, and a clear understanding of how to influence the reader academically. Many students find it challenging to combine logical reasoning, persuasive language, and academic rigour into a single cohesive piece. Our <a
                                href="https://www.assignnmentinneed.com/">assignment help uk</a> for persuasive essays is designed to bridge this gap, ensuring each submission meets the highest standards expected in UK university assessments.
                        </p>

                        <h3>Expert Guidance that Elevates Your Essay</h3>
                        <p class="content-description">
                            Our writers are not only highly qualified but also specialise in persuasive writing techniques, providing you with an academic advantage:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Highly Qualified Writers: </b>Each essay is handled by writers with Master’s and PhD degrees from recognised UK institutions. Their experience in research and argumentation ensures your essay is both credible and compelling.
                            </li>
                            <li class="list-group-item"><b>UK University Alignment: </b>Every essay is structured to meet UK assessment criteria, focusing on argument clarity, critical reasoning, and the integration of authoritative sources.
                            </li>
                            <li class="list-group-item"><b>Professional Academic Standards: </b>From vocabulary precision to the proper use of evidence, our essays demonstrate discipline-specific command and scholarly tone suitable for UK universities.
                            </li>
                        </ul>

                        <h3>The Structure of a High-Impact Persuasive Essay</h3>
                        <p class="content-description">
                            Crafting a persuasive essay requires systematic organisation and clear logic:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Defining a Strong Thesis: </b>The essay opens with a concise, debatable thesis that forms the backbone of the argument.
                            </li>
                            <li class="list-group-item"><b>Logical Paragraph Flow: </b>Each paragraph begins with a claim, followed by evidence and analysis, creating a seamless progression of ideas.
                            </li>
                            <li class="list-group-item"><b>Counter-Argument Integration: </b>To demonstrate intellectual maturity, opposing views are presented and effectively refuted using factual, research-based evidence.
                            </li>
                            <li class="list-group-item"><b>Evidence-Based Approach: </b>All arguments are backed with peer-reviewed sources, ensuring reliability, academic credibility, and high marks.
                            </li>
                        </ul>

                        <h3>Why Choose Our Persuasive Essay Writing Services UK</h3>
                        <p class="content-description">
                            Students rely on our services for multiple reasons:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Deadline Efficiency: </b>Urgent essays are completed on time without compromising quality.
                            </li>
                            <li class="list-group-item"><b>Academic Confidence: </b>Professionally written essays instil confidence in submission and evaluation.
                            </li>
                            <li class="list-group-item"><b>Skill Enhancement: </b>Analysing expert writing helps students understand persuasive structures and argumentation strategies.
                            </li>
                            <li class="list-group-item"><b>Customised Essays: </b>Each essay is tailored to specific course requirements, word counts, and referencing styles.
                            </li>
                        </ul>

                        <h3>Core Features of Our Online Persuasive Essay UK Service</h3>
                        <p class="content-description">
                            
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Originality Guaranteed: </b>Every essay is written from scratch with zero plagiarism.
                            </li>
                            <li class="list-group-item"><b>Human-Written Content: </b>We never rely on AI, ensuring nuanced arguments and authentic intellectual engagement.
                            </li>
                            <li class="list-group-item"><b>Confidentiality: </b>Personal data and essay details remain completely secure.
                            </li>
                        </ul>

                        <h3>Advanced Argumentation Techniques</h3>
                        <p class="content-description">
                            Our experts apply advanced techniques to elevate the persuasive quality of your essay:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Critical Evidence Evaluation: </b>Evidence is carefully analysed for credibility, relevance, and impact on the argument.
                            </li>
                            <li class="list-group-item"><b>Sophisticated Counter-Arguments: </b>We anticipate objections and address them logically, demonstrating critical thinking and depth.
                            </li>
                            <li class="list-group-item"><b>Logical Progression: </b>Ideas flow naturally, supporting the thesis with clarity and coherence.
                            </li>
                            <li class="list-group-item"><b>Academic Style Mastery: </b>Essays follow UK conventions, including formal tone, correct referencing, and structured presentation.
                            </li>
                        </ul>

                        <h3>Strategic Benefits of Using Our Persuasive Essay Help UK</h3>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Stress Reduction: </b>Delegating your essay to experts allows you to focus on revision or other assignments.
                            </li>
                            <li class="list-group-item"><b>High-Quality Results: </b>Professionally written essays meet and exceed UK university standards.
                            </li>
                            <li class="list-group-item"><b>Learning Opportunity: </b>Students gain insight into effective persuasive techniques, enhancing their own writing skills.
                            </li>
                            <li class="list-group-item"><b>Customisable Services: </b>Whether you need help with drafting, editing, or full essay writing, our team adapts to your requirements.
                            </li>
                        </ul>

                        <h3>Ethical Standards and Academic Integrity</h3>
                        <p class="content-description">
                            We prioritise ethical and transparent academic support:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Plagiarism-Free Guarantee: </b>Every essay is original, verified, and properly cited.
                            </li>
                            <li class="list-group-item"><b>AI-Free Writing Assurance: </b>All essays are human-written for intellectual depth and authenticity.
                            </li>
                            <li class="list-group-item"><b>Confidentiality Protocols: </b>All student information is secured with industry-standard encryption.
                            </li>
                            <li class="list-group-item"><b>Ethical Guidance Model: </b>Essays are designed as high-quality learning tools, empowering students to improve their own writing skills.
                            </li>
                        </ul>

                        <h3>Enhanced Quality Through Proofreading and Editing</h3>
                        <p class="content-description">
                            Every essay undergoes rigorous checks before submission:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Quality Assurance Review: </b>Independent editors verify linguistic accuracy, coherence, and adherence to UK standards.
                            </li>
                            <li class="list-group-item"><b>Flawless Formatting: </b>We apply precise referencing styles, including Harvard, APA, MLA, and OSCOLA, ensuring your essay meets all academic guidelines.
                            </li>
                            <li class="list-group-item"><b>Verified Sources: </b>Peer-reviewed and credible references are documented to support your argument effectively.
                            </li>
                        </ul>

                        <h3>Empowering Students for Long-Term Academic Success</h3>
                        <p class="content-description">
                            Our <b>best persuasive essay help UK</b> aims to create a lasting impact:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Future Academic Advantage: </b>Students gain exposure to structured, professional essays that serve as models for future work.
                            </li>
                            <li class="list-group-item"><b>Skill Development: </b>Our service teaches advanced argumentation, literature review integration, and critical thinking.
                            </li>
                            <li class="list-group-item"><b>Cross-Disciplinary Support: </b>Essays are crafted for any subject area, whether business, law, humanities, social sciences, or STEM, ensuring relevance and academic rigour.
                            </li>
                        </ul>
                        <p class="content-description">
                           By choosing online persuasive essay writing services UK, students gain a comprehensive, reliable, and strategic solution supported by trusted <a
                                href="/assignment-writing-help-services">assignment writing help services uk</a>. This approach not only helps secure top 
                        </p>
                    </div>


                </div>
            </div>
    </section>

    @if(!empty($data['faqs']))
        <div>
            @php
                $total = count($data['faqs']);
                $half = ceil($total / 2);
                $leftFaqs = array_slice($data['faqs'], 0, $half);
                $rightFaqs = array_slice($data['faqs'], $half);
            @endphp

            <section class="faq-section">
                <h2 class="section-title">Frequently Asked Questions – Persuasive Essay Writing UK</h2>

                <div class="faq-container">
                    <div class="faq-column">
                        @foreach ($leftFaqs as $item)
                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>{!! $item['question'] ?? '' !!}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>{!! $item['answer'] !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="faq-column">
                        @foreach ($rightFaqs as $item)
                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>{!! $item['question'] ?? '' !!}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>{{ $item['answer'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>
                <div class="title-box text-center mb-5">
                    <a href="/faqs" class="place-order-btn">View More FAQs</a>
                </div>
            </section>


            <style>
                .faq-section {
                    text-align: center;
                    padding: 50px 20px;
                }

                .faq-container {
                    display: flex;
                    justify-content: center;
                    gap: 20px;
                    max-width: 1200px;
                    margin: 1rem auto;
                }

                .faq-column {
                    width: 45%;
                }

                .faq-item {
                    margin-bottom: 10px;
                    border: 2px solid #5e2ced;
                    border-radius: 5px;
                    overflow: hidden;
                }

                .faq-question {
                    width: 100%;
                    text-align: left;
                    background: white;
                    padding: 15px;
                    cursor: pointer;
                    font-size: 16px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border: none;
                    font-weight: 500;
                }

                .faq-question i {
                    transition: transform 0.3s ease-in-out;
                    color: #5e2ced;
                }

                .faq-answer {
                    max-height: 0;
                    overflow: hidden;
                    transition: max-height 0.4s ease-out, padding 0.4s ease-out;
                    padding: 0 15px;
                    background: #f9f9f9;
                    text-align: justify;
                }

                .faq-answer p {
                    font-size: 14px;
                    color: #555;
                }

                .faq-item.active .faq-answer {
                    max-height: 150px;
                    padding: 10px 15px;
                }

                .faq-item.active .faq-question i {
                    transform: rotate(180deg);
                }

                @media (max-width: 768px) {
                    .faq-container {
                        flex-direction: column;
                        align-items: center;
                        width: 100%;
                        gap: 0px;
                    }

                    .faq-column {
                        width: 100%;
                    }

                    .section-title {
                        margin-left: 0px;
                    }
                }
            </style>

            <script>
                document.querySelectorAll(".faq-question").forEach((button) => {
                    button.addEventListener("click", () => {
                        const faqItem = button.parentElement;
                        const faqAnswer = faqItem.querySelector(".faq-answer");

                        // Close other open FAQs
                        document.querySelectorAll(".faq-item").forEach((item) => {
                            if (item !== faqItem) {
                                item.classList.remove("active");
                                item.querySelector(".faq-answer").style.maxHeight = null;
                            }
                        });

                        // Toggle current FAQ
                        faqItem.classList.toggle("active");
                        if (faqItem.classList.contains("active")) {
                            faqAnswer.style.maxHeight = faqAnswer.scrollHeight + "px";
                        } else {
                            faqAnswer.style.maxHeight = null;
                        }
                    });
                });
            </script>

        </div>

    @endif




@endsection