@extends('frontend-layouts.app')

@section('content')

    <section class="bg-hero">
        <div class="container-section">
            <div class="hero-content">
                <!-- Content Column -->
                <div class="content-column">
                    <h1 class="main-heading">
                        Best Personal Statement Essay Writing Service UK: Guaranteed Expertise & Originality
                    </h1>
                    <div class="content-paragraph">
                        <p>Achieve your academic goals with expertly crafted personal statements designed specifically for
                            UK universities</p>
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
                How Our Expert Online Personal Statement Essay Help UK Works
            </h2>
            <p>We’ve designed a simple and secure three-step process to connect you with the best personal statement essay
                writing services UK:</p>
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

    <x-common-section.benifits-of-assignment-cards title="Assured Excellence in Personal Statement Essay Writing UK"
        subtitle="Choosing our service means more than just submitting an essay-it’s a commitment to top-quality, original, and professionally crafted personal statements tailored for UK university admissions."
        :items="[
            [
                'icon' => 'fas fa-clock-o',
                'heading' => 'Fully Human-Crafted Essays, No AI Involvement',
                'paragraph' =>
                    'We strictly avoid automated writing tools. Every personal statement essay is carefully composed by our expert writers, ensuring genuine expression, clear storytelling, and a personal touch that resonates with admission tutors.',
            ],

            [
                'icon' => 'fas fa-edit',
                'heading' => 'Guaranteed Plagiarism-Free Content',
                'paragraph' =>
                    'Each essay is meticulously checked with advanced plagiarism detection software. You can submit your personal statement with confidence in its originality and authenticity, fully meeting UK academic standards.',
            ],

            [
                'icon' => 'fas fa-headset',
                'heading' => 'Experienced Writers With Academic Credentials',
                'paragraph' =>
                    'Our team consists of writers with Master’s and PhD qualifications, all well-versed in UK university admission criteria. They craft essays that highlight your strengths, achievements, and academic potential convincingly.',
            ],

            [
                'icon' => 'fas fa-ban',
                'heading' => 'Around-the-Clock Student Support',
                'paragraph' =>
                    'We understand deadlines and last-minute concerns. Our 24/7 support team is always ready to provide updates, answer questions, or offer guidance throughout the writing process.',
            ],

            [
                'icon' => 'fas fa-line-chart',
                'heading' => 'Complimentary Revisions for Perfect Results',
                'paragraph' =>
                    'Your satisfaction is our priority. We offer free revisions to ensure your personal statement reflects your unique experiences, goals, and personality perfectly.',
            ],

            [
                'icon' => 'fas fa-lock',
                'heading' => 'Timely Delivery and Refund Assurance',
                'paragraph' =>
                    'We respect your deadlines. Every essay is delivered on schedule, and if our high standards aren’t met, our clear refund policy guarantees your investment is protected.',
            ],
        ]" />

    <section class="page-section testimonial-section-four">
        <div class="container">
            <h2 class="section-title">Meet Your UK-Verified Personal Statement Essay Expert</h2>

            <p class="section-description">
                Our platform connects you with a carefully selected network of UK-qualified writers. Every writer has experience crafting compelling personal statements that capture their unique experiences, achievements, and aspirations.
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

    <x-common-section.academic--writing-cards heading="Beyond Personal Statements: Explore Our Other Academic Writing Services UK"
        paragraph="While personal statements are our speciality, we also provide support for a variety of academic papers across UK universities:"
        :cards="[
            [
                'heading' => 'Argumentative Essay Writing UK',
                'href' => '/argumentative-essay-help-uk',
                'paragraph' =>
                    'Craft well-researched and persuasive essays that clearly present your arguments, supported by credible evidence and critical analysis, tailored for UK academic standards.',
            ],
            [
                'heading' => 'Literature Review Essay Writing Help UK',
                'href' => '/literature-review-essay-help-uk',
                'paragraph' =>
                    'Receive expertly structured literature review essays that highlight your analytical skills and intellectual depth, perfect for scholarship applications or competitive programs.',
            ],

            [
                'heading' => 'Analytical Essay Writing UK',
                'href' => '/analytical-essay-help-uk',
                'paragraph' =>
                    'Transform your ideas into cohesive, logically organised essays that meet strict UK university criteria while demonstrating a thorough understanding of the subject.',
            ],

            [
                'heading' => 'Descriptive Essay Writing UK',
                'href' => '/descriptive-essay-help-uk',
                'paragraph' =>
                    'Create vivid, engaging essays that effectively communicate your experiences and academic goals, giving your application a distinct and memorable edge.',
            ],

            [
                'heading' => 'Reflective Essay Help UK',
                'href' => '/reflective-essay-help-uk',
                'paragraph' =>
                    'Produce well-structured reflective essays showcasing your personal growth, skills, and suitability for your chosen program, written to impress admission committees.',
            ],


        ]" />

    @include('components.common-section.whatsapp')

    {{-- scrolling content --}}
    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">
                <div class="column">
                    <div class="content-box">
                        <h2>Advanced Personal Statement Essay Writing UK for High-Impact Applications</h2>
                        <p class="content-description">
                            In today’s competitive academic environment, a high-quality personal statement can be the defining element of your university application. It is your opportunity to present your academic journey, personal experiences, and professional ambitions in a compelling way that aligns with UK university standards. Many students struggle to articulate their story effectively, resulting in essays that fail to reflect their potential. Our help me personal statement <a href="/essay-writing-help-services">essay writing help uk</a> service offers a complete solution, providing expertly written essays tailored to each individual’s goals.
                        </p>
                        <p class="content-description">
                            A successful personal statement requires:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Deep understanding of the course and university requirements.
                            </li>
                            <li class="list-group-item">Ability to present achievements and experiences convincingly.
                            </li>
                            <li class="list-group-item">A narrative that is both authentic and engaging.
                            </li>
                            <li class="list-group-item">Clear articulation of future goals and career aspirations.
                            </li>
                        </ul>
                        <p class="content-description">
                            Our writing personal essay help UK experts ensure that your essay demonstrates these qualities, giving you the best chance of acceptance.
                        </p>

                        <h3>Expertise Behind Our Personal Statement Essays</h3>
                        <p class="content-description">Our team of professional writers includes specialists with Master’s and PhD-level qualifications, familiar with the nuances of UK university admissions. Each writer understands what universities value in applicants and tailors essays to highlight unique skills, achievements, and potential. By collaborating with our personal statement essay writers UK, students receive:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">In-depth analysis of their academic and extracurricular experiences.
                            </li>
                            <li class="list-group-item">Strategic guidance on how to structure and present their essays.
                            </li>
                            <li class="list-group-item">Essays that balance creativity, professionalism, and clarity.
                            </li>
                        </ul>

                        <h3>What Our Service Offers</h3>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Personalised Storytelling: </b>We transform your achievements and experiences into a cohesive, engaging essay.
                            </li>
                            <li class="list-group-item"><b>Structured Approach: </b>Essays are organised to ensure a logical flow and effective communication of ideas.
                            </li>
                            <li class="list-group-item"><b>Academic and Career Alignment: </b>Every statement is tailored to demonstrate suitability for your chosen course.
                            </li>
                            <li class="list-group-item"><b>Reflective Writing: </b>Essays showcase growth, learning, and critical reflection on experiences.
                            </li>
                            <li class="list-group-item"><b>UK University Standards: </b>All essays comply with word count, tone, and formatting expectations.
                            </li>
                        </ul>

                        <h3>How We Enhance Your Personal Statement</h3>
                        <p class="content-description">Our online personal statement essay writing help UK service goes beyond writing to provide:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Guidance on showcasing strengths without exaggeration.
                            </li>
                            <li class="list-group-item">Strategies for highlighting academic achievements and extracurricular involvement.
                            </li>
                            <li class="list-group-item">Advice on aligning personal goals with program expectations.
                            </li>
                            <li class="list-group-item">Review and editing to ensure clarity, coherence, and high impact.
                            </li>
                            <li class="list-group-item">Techniques for making essays memorable and distinctive.
                            </li>
                        </ul>

                        <h3>The Value of Professional Assistance</h3>
                        <p class="content-description">Students using our best personal statement essay writing services UK gain multiple advantages:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Essays that make a lasting impression on admission committees.
                            </li>
                            <li class="list-group-item">Reduction in stress and workload during the application process.
                            </li>
                            <li class="list-group-item">Exposure to best practices in academic and reflective writing.
                            </li>
                            <li class="list-group-item">Access to expert guidance for adapting essays to multiple applications.
                            </li>
                        </ul>

                        <h3>Key Advantages of Our Approach</h3>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Customised Support: </b>Each essay is designed to reflect the student’s unique academic journey and aspirations.
                            </li>
                            <li class="list-group-item"><b>Expertise in UK Admissions: </b>Our writers understand the expectations of UK universities and tailor content accordingly.
                            </li>
                            <li class="list-group-item"><b>Comprehensive Editing: </b>All essays undergo multiple rounds of review for clarity, structure, and impact.
                            </li>
                            <li class="list-group-item"><b>Accessible Guidance: </b>Students receive ongoing support and feedback throughout the writing process.
                            </li>
                            <li class="list-group-item"><b>Skill Development: </b>Students learn how to structure and craft essays for future academic or professional use.
                            </li>
                        </ul>

                        <h3>Achieve Academic Excellence</h3>
                        <p class="content-description">Securing admission to a leading UK university requires a personal statement that highlights achievements while presenting a compelling academic and career narrative. Our <a href="https://www.assignnmentinneed.com/">assignment help uk</a> for personal statement writing delivers content that blends creativity, precision, and strategic presentation. By working with our team, students can submit applications confidently, knowing their personal statements are persuasive, well-structured, and aligned with UK university expectations.
                        </p>
                    </div>


                </div>

                <div class="column">
                    <div class="content-box">
                        <h2>Crafting a Winning Personal Statement Essay UK</h2>
                        <p class="content-description">A compelling personal statement is often the decisive factor in securing a place at a UK university. Unlike other components of an application, a personal statement allows students to showcase their academic capabilities, personality, career ambitions, and personal growth in a single, coherent narrative. Many students struggle with presenting their experiences effectively, leading to generic essays that fail to impress admission committees. Our personal statement essay writing UK service is specifically designed to address this challenge, providing expertly crafted essays that highlight your strengths and potential.
                        </p>
                        <p class="content-description">Writing a persuasive personal statement requires careful planning and the ability to translate life experiences into a clear academic narrative. Our <a
                                href="/assignment-writing-help-services">assignment writing help uk</a> ensures each essay balances personal storytelling with professional structure, demonstrating critical thinking and academic insight while aligning fully with UK university expectations.
                        </p>

                        <h3>Why a Professional Personal Statement Matters</h3>
                        <p class="content-description">A strong personal statement should:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Reflect your personal journey, highlighting both academic and extracurricular achievements.
                            </li>
                            <li class="list-group-item"><b>Plagiarism-Free Assurance: </b>All essays undergo strict
                                plagiarism checks, giving you peace of mind and maintaining the highest academic integrity.
                            </li>
                            <li class="list-group-item">Showcase skills such as problem-solving, analytical thinking, and leadership.
                            </li>
                            <li class="list-group-item">Communicate your future aspirations and alignment with the chosen program.
                            </li>
                        </ul>
                        <p class="content-description">Our writing personal essay help UK team ensures that each essay achieves these objectives while remaining engaging, authentic, and unique.
                        </p>

                        <h3>Features of Our Personal Statement Essay Writing UK Service</h3>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Tailored Content: </b>We create essays that reflect your individual story, ensuring originality and a strong personal voice.
                            </li>
                            <li class="list-group-item"><b>Structured Flow: </b>Essays are carefully organised, with compelling introductions, coherent body sections, and memorable conclusions.
                            </li>
                            <li class="list-group-item"><b>Emphasis on Achievements and Goals: </b>Academic, extracurricular, and personal achievements are highlighted in a cohesive narrative.
                            </li>
                            <li class="list-group-item"><b>UK Admission Focus: </b>Each essay adheres to word limits, formatting requirements, and the specific expectations of UK universities.
                            </li>
                            <li class="list-group-item"><b>Professional and Engaging Style: </b>We balance academic professionalism with creativity, making your essay memorable.
                            </li>
                        </ul>

                        <h3>How We Enhance Your Personal Statement</h3>
                        <p class="content-description">Our online personal statement essay writing help UK approach focuses on:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Transforming accomplishments into impactful narratives without exaggeration.
                            </li>
                            <li class="list-group-item">Demonstrating reflection and learning from experiences.
                            </li>
                            <li class="list-group-item">Align your skills and achievements with your desired course.
                            </li>
                            <li class="list-group-item">Crafting a unique voice that resonates with admissions tutors.
                            </li>
                            <li class="list-group-item">Using evidence-based examples to support claims and illustrate personal growth.
                            </li>
                        </ul>
                        <p class="content-description">Through this process, your essay becomes a persuasive document that reflects your individuality while meeting academic standards.
                        </p>

                        <h3>Benefits of Using Our Service</h3>
                        <p class="content-description">Students who use our best personal statement essay writing services UK experience:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Increased Acceptance Potential: </b>Well-crafted essays stand out in competitive admissions.
                            </li>
                            <li class="list-group-item"><b>Time Efficiency: </b>Students can focus on other parts of the application while we handle the essay.
                            </li>
                            <li class="list-group-item"><b>Skill Development: </b>By reviewing expert essays, students learn techniques to improve their own writing.
                            </li>
                            <li class="list-group-item"><b>Reduced Stress: </b>Professional support alleviates anxiety related to personal statement writing.
                            </li>
                        </ul>

                        <h3>Supporting Your Academic Journey</h3>
                        <p class="content-description">Our service is more than just writing essays; it is a learning experience. Students gain:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Insights into structuring essays effectively.
                            </li>
                            <li class="list-group-item">Knowledge of how to express ideas clearly and persuasively.
                            </li>
                            <li class="list-group-item">Strategies for highlighting achievements and goals without overstatement.
                            </li>
                            <li class="list-group-item">Guidance on adapting essays for scholarships, internships, or other applications.
                            </li>
                        </ul>
                        <p class="content-description">By collaborating with our personal statement essay writers UK, students gain confidence, clarity, and a strategic advantage in their applications.
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
                <h2 class="section-title">Frequently Asked Questions - Personal Statement Essay Writing UK</h2>

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