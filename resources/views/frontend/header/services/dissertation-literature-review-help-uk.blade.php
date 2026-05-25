@extends('frontend-layouts.app')

@section('content')

    <section class="bg-hero">
        <div class="container-section">
            <div class="hero-content">
                <!-- Content Column -->
                <div class="content-column">
                    <h1 class="main-heading">
                        Best Dissertation Literature Review Writing Help UK: Expert Assistance for Top Marks
                    </h1>
                    <div class="content-paragraph">
                        <p>Get the highest marks with carefully structured dissertation literature reviews tailored for UK universities. Our dissertation literature review writing help UK ensures that each review is methodically researched, critically analysed, and presented with academic precision</p>
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
                How Our Dissertation Literature Review Helps UK Works
            </h2>
            <p>We’ve created a straightforward, reliable three-step process to provide the best dissertation literature review writing services UK, making it easy for you to get expert support and submit a high-quality, well-researched literature review with confidence.</p>
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

                    <a href="/upload-your-assignment">
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

    <x-common-section.benifits-of-assignment-cards title="Promises for the Best Dissertation Literature Review Help UK"
            subtitle="When you choose our experts, you’re not just ordering a literature review; you’re securing a complete guarantee of excellence, authenticity, and academic reliability. Our service provides the best dissertation literature review writing services UK."
        :items="[
            [
                'icon' => 'fas fa-clock-o',
                'heading' => 'Original, Human-Crafted Content',
                'paragraph' =>
                    'Every dissertation literature review is created entirely by a skilled academic, ensuring nuanced insights, critical evaluation, and 100% AI-free content tailored to your topic.',
            ],

            [
                'icon' => 'fas fa-edit',
                'heading' => 'Verified Plagiarism-Free Assurance',
                'paragraph' =>
                    'Your literature review is checked against extensive databases to ensure authenticity. Submit your dissertation literature review UK with confidence, knowing every source is properly cited and original.',
            ],

            [
                'icon' => 'fas fa-headset',
                'heading' => 'Specialist Writers with Doctoral Expertise',
                'paragraph' =>
                    'We match your project with writers holding Master’s or PhD degrees in relevant disciplines. This guarantees a scholarly perspective that meets UK university standards for high-level academic writing.',
            ],

            [
                'icon' => 'fas fa-ban',
                'heading' => 'Round-the-Clock Guidance and Support',
                'paragraph' =>
                    'Academic pressures don’t follow a schedule. Our 24/7 support team is always available to answer questions, provide progress updates, or guide you through help writing a dissertation literature review UK.',
            ],

            [
                'icon' => 'fas fa-line-chart',
                'heading' => 'Unlimited Revisions for Academic Precision',
                'paragraph' =>
                    'Your satisfaction is our priority. We offer revisions until your literature review perfectly aligns with your instructions, ensuring it meets UK assessment criteria for clarity, coherence, and critical depth.',
            ],

            [
                'icon' => 'fas fa-lock',
                'heading' => 'Deadline Reliability and Risk-Free Refund',
                'paragraph' =>
                    'We guarantee on-time delivery for your dissertation literature review. Plus, if our services fall short of your expectations, our refund policy protects your investment, demonstrating the reliability of our online dissertation literature review help UK.',
            ],
        ]" 
    />

    <section class="page-section testimonial-section-four">
        <div class="container">
            <h2 class="section-title">Meet Your UK-Verified Expert for Dissertation Literature Review Support UK</h2>

            <p class="section-description">
                We link you to a handpicked network of UK-based academic specialists, all holding Master’s or PhD qualifications. Each expert is thoroughly vetted to ensure they can provide precise, high-quality assistance for your dissertation literature review writing help UK.
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

    <x-common-section.academic--writing-cards  heading="Beyond Literature Review: Explore Our Other Dissertation Writing Services UK"
        paragraph="While we specialise in comprehensive dissertation literature review writing help UK, our academic experts also provide professional support across a wide range of dissertation components. Expand your academic assistance with our tailored services below:"
        :cards="[
            [
                'heading' => 'Dissertation Findings Writing UK',
                'href' => '/dissertation-findings-help-uk',
                'paragraph' =>
                    'Get a well-structured and compelling findings chapter tailored to UK university standards. Our specialists help you present research outcomes clearly, linking them seamlessly to your research objectives and methodology.',
            ],
            [
                'heading' => 'Dissertation Results Help UK',
                'href' => '/dissertation-results-help-uk',
                'paragraph' =>
                    'We assist in crafting precise and rigorous results sections. Our experts analyse qualitative or quantitative data, presenting your findings logically and academically to meet UK dissertation requirements.',
            ],

            [
                'heading' => 'Dissertation Topic Selection Services UK',
                'href' => '/dissertation-topic-selection-service-help-uk',
                'paragraph' =>
                    'Struggling to choose the perfect dissertation topic? Our service provides expert guidance to select innovative and researchable topics, ensuring your project is both original and academically relevant.',
            ],

            [
                'heading' => 'Master\'s Dissertation Help UK',
                'href' => '/masters-dissertation-help-uk',
                'paragraph' =>
                    'Our team helps interpret results in the context of your literature review and research questions. We ensure conclusions are insightful, academically sound, and aligned with the expectations of UK universities.',
            ],

            [
                'heading' => 'Case Study Dissertation Help UK',
                'href' => '/case-study-dissertation-help-uk',
                'paragraph' =>
                    'Receive expertly written case study chapters that critically examine real-world scenarios. We focus on analysis, evaluation, and practical implications to showcase advanced research skills and academic rigour.',
            ],

            [
                'heading' => 'Dissertation Editing and Proofreading Help UK',
                'href' => '/dissertation-editing-and-proofreading-help-uk',
                'paragraph' =>
                    'We guarantee meticulous editing and proofreading, including flawless formatting and referencing according to UK standards (Harvard, APA, MLA, or OSCOLA). This ensures your dissertation is polished, academically compliant, and ready for submission.',
            ],
            
        ]" 
    />

    @include('components.common-section.whatsapp')

    {{-- scrolling content  --}}
    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">
                <div class="column">
                    <div class="content-box">
                        <h2>Excelling in Dissertation Literature Review Writing UK: Your Pathway to Outstanding Academic Achievement</h2>
                        <p class="content-description">
                            Submitting a top-quality dissertation literature review UK is one of the most critical milestones for postgraduate and doctoral students. It demands rigorous evaluation of existing research, synthesis of diverse scholarly perspectives, and careful articulation of research gaps. The complexity of managing extensive literature, aligning it with research questions, and ensuring academic coherence can overwhelm even the most diligent students. Our <a href="/dissertation-writing-help-services">dissertation writing help uk</a> provides a tailored solution to embed expert analysis, clarity, and critical depth into your dissertation, guaranteeing superior academic outcomes.
                        </p>
                        
                    </div>
                    <div class="content-box">
                        <h2>Expertise that Defines the Best Dissertation Literature Review Writing Services UK</h2>
                        <p class="content-description">
                            We employ only academic professionals with advanced degrees (Master’s and PhD) from top-tier UK universities. This ensures your literature review is handled by specialists who understand nuanced research methodologies and possess subject-specific expertise, delivering reliable <a href="/assignment-writing-help-services">assignment writing help uk</a> that meets the academic expectations of UK higher education.
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>PhD-Qualified Writers: </b>Every project is assigned to a writer with relevant doctoral-level experience, ensuring authoritative engagement with your topic.</li>
                            <li class="list-group-item"><b>UK Academic Assessment Knowledge: </b>Our writers understand the precise marking criteria for literature reviews in UK universities, maximising clarity, critical evaluation, and coherence.</li>
                            <li class="list-group-item"><b>Discipline-Specific Terminology: </b>From law and business to social sciences and STEM fields, our writers use precise terminology and current peer-reviewed sources to reflect deep academic understanding.</li>
                        </ul>                                              
                    </div>                            
                    <div class="content-box">
                        <h2>Deconstructing Literature Complexity into Structured, Insightful Reviews</h2>
                        <p class="content-description">
                            Our process transforms large volumes of research into a coherent, critically analysed literature review:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Systematic Literature Mapping: </b>Identify, categorise, and critically evaluate relevant studies to establish a comprehensive academic framework.</li>
                            <li class="list-group-item"><b>Synthesis of Scholarly Perspectives: </b>Combine insights from multiple sources to highlight research gaps and conceptual debates, creating a unique analytical perspective.</li>
                            <li class="list-group-item"><b>Logical Flow and Critical Coherence: </b>Arrange themes and arguments to ensure a clear, academic narrative that meets UK university standards.</li>
                            <li class="list-group-item"><b>Evidence-Based Evaluation: </b>Support every claim with robust, verifiable, peer-reviewed references to ensure credibility and academic integrity.</li>
                        </ul>                                              
                    </div>                            
                    <div class="content-box">
                        <h2>Learning Value and Academic Empowerment</h2>
                        <p class="content-description">
                            Our dissertation literature reviews are more than a finished product-they are a blueprint for your academic growth. By examining our expertly written reviews, you gain:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Understanding of how top-tier literature reviews are structured.</li>
                            <li class="list-group-item">Insights into synthesising research findings for maximum impact.</li>
                            <li class="list-group-item">Skills to independently craft coherent, critical, and high-scoring literature chapters in the future.</li>
                        </ul>                                              
                    </div>                            
                    <div class="content-box">
                        <h2>Ethical Standards and Trust in Our Dissertation Literature Review Services UK</h2>
                        <p class="content-description">
                            We prioritise ethical excellence and confidentiality:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Plagiarism-Free and AI-Free Content: </b>Every review is crafted from scratch by human experts, ensuring originality and intellectual authenticity.</li>
                            <li class="list-group-item"><b>Confidentiality and Data Security: </b>Your project details and personal information are protected by strict encryption and non-disclosure policies.</li>
                            <li class="list-group-item"><b>Transparent Revision Policies: </b>Free revisions ensure the final document meets your exact requirements and academic standards.</li>
                        </ul>                                              
                    </div>                            
                    <div class="content-box">
                        <h2>Strategic Support to Maximise Dissertation Quality</h2>
                        <p class="content-description">
                            Our comprehensive services integrate essential academic elements:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Professional Proofreading and Editing: </b>Every literature review undergoes multi-layer editing for grammar, coherence, and academic tone.</li>
                            <li class="list-group-item"><b>Formatting and Referencing Excellence: </b>We guarantee correct application of UK citation styles (Harvard, APA, MLA, OSCOLA), preventing grade deductions.</li>
                            <li class="list-group-item"><b>Verified Sources: </b>All references are from current, authoritative, peer-reviewed journals to reinforce the credibility of your work.</li>
                        </ul>                                              
                        <h3>Empowering Your Academic Journey</h3>
                        <p class="content-description">
                            By using our online dissertation literature review help UK, students benefit from:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">A clear, replicable model for future dissertation chapters.</li>
                            <li class="list-group-item">Coverage of complex academic skills is often assumed but rarely taught.</li>
                            <li class="list-group-item">Discipline-specific guidance to ensure high-level scholarly writing.</li>
                        </ul>                                              
                    </div>  
                </div>

                <div class="column">
                    <div class="content-box">
                        <h2>Online Dissertation Literature Review Help UK: Expert Guidance for Postgraduate Success</h2>
                        <p class ="content-description">
                            Crafting a dissertation literature review is a demanding task, requiring careful selection, critical evaluation, and synthesis of numerous academic sources. Our dissertation literature review helps UK services simplify this challenge by offering structured, insightful, and academically sound reviews, supported by reliable <a href="/">assignment help uk</a> that aligns with the rigorous standards of UK universities.
                        </p>   
                        
                    </div>
                    <div class="content-box">
                        <h2>Why Choose Our Dissertation Literature Review Writing Services UK</h2>
                        <p class ="content-description">We combine expertise, precision, and ethical academic practice:</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Specialised Academic Writers: </b>Every review is handled by UK-verified Master’s or PhD-qualified professionals.</li>
                            <li class="list-group-item"><b>Assessment Criteria Knowledge: </b>We write to maximise scores, addressing coherence, criticality, and analytical depth.</li>
                            <li class="list-group-item"><b>Advanced Research Skills: </b>Our experts locate and synthesise high-quality peer-reviewed sources, ensuring credibility.</li>
                        </ul>
                    </div>
                    <div class="content-box">
                        <h2>Structured Approach to Literature Review Excellence</h2>
                        <p class ="content-description">Our methodology ensures clarity and critical depth:</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Comprehensive Literature Survey: </b>Identify relevant research, categorise findings, and establish thematic coherence.</li>
                            <li class="list-group-item"><b>Critical Evaluation: </b>Analyse methodologies, theoretical frameworks, and outcomes of each study, highlighting gaps and contradictions.</li>
                            <li class="list-group-item"><b>Synthesis of Evidence: </b>Combine insights to construct an original perspective that situates your research within the broader academic conversation.</li>
                            <li class="list-group-item"><b>Logical and Coherent Flow: </b>Structure chapters to ensure a smooth narrative, connecting theory to your research objectives.</li>
                        </ul>
                        
                    </div>
                    <div class="content-box">
                        <h2>Learning-Oriented Reviews for Long-Term Academic Growth</h2>
                        <p class ="content-description">Every literature review we provide doubles as a learning tool:</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Demonstrates advanced structuring techniques.</li>
                            <li class="list-group-item">Shows effective synthesis and critical evaluation of research.</li>
                            <li class="list-group-item">Provides a model for independent writing of future dissertation chapters.</li>
                        </ul>
                        
                    </div>
                    <div class="content-box">
                        <h2>Ethics, Confidentiality, and Originality</h2>
                        <p class ="content-description">Our service guarantees academic integrity:</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>100% Original Content: </b>Human-written, AI-free, and plagiarism-free reviews.</li>
                            <li class="list-group-item"><b>Secure Handling of Personal Data: </b>All order information is confidential, backed by encryption and strict privacy policies.</li>
                            <li class="list-group-item"><b>Free Revisions: </b>Adjustments are available until the review fully meets your expectations.</li>
                        </ul>
                        
                    </div>
                    <div class="content-box">
                        <h2>Supporting Every Aspect of Dissertation Writing UK</h2>
                        <p class ="content-description">Beyond literature review support, we provide:</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Dissertation Findings & Results Writing: </b>Transform raw data into coherent, academically robust chapters.</li>
                            <li class="list-group-item"><b>Topic Selection Assistance: </b>Guidance to select innovative, researchable dissertation topics.</li>
                            <li class="list-group-item"><b>Editing & Proofreading Services: </b>Ensure grammar, structure, and references adhere strictly to UK standards.</li>
                            <li class="list-group-item"><b>Case Study & Masters Dissertation Support: </b>Expert writing for all critical components of your postgraduate research.</li>
                        </ul>

                    </div>
                    <div class="content-box">
                        <h2>Empowering Academic Excellence with Online Dissertation Literature Review Services UK</h2>
                        <p class ="content-description">By choosing our service, students benefit from:</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Expert guidance across all dissertation stages.</li>
                            <li class="list-group-item">Replicable, high-quality models for independent academic work.</li>
                            <li class="list-group-item">Assurance of top-quality, evidence-backed, and critically evaluated literature reviews.</li>
                        </ul>

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
                <h2 class="section-title">Frequently Asked Questions - Expert Dissertation Literature Review Help UK</h2>

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