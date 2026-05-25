@extends('frontend-layouts.app')

@section('content')

    <section class="bg-hero">
        <div class="container-section">
            <div class="hero-content">
                <!-- Content Column -->
                <div class="content-column">
                    <h1 class="main-heading">
                        Crafting Your Perfect Dissertation Topic: Expert Guidance for UK Students
                    </h1>
                    <div class="content-paragraph">
                        <p>Selecting a strong, research-worthy dissertation topic is the first step toward achieving top marks. Our dissertation topic selection services UK are designed to help students find original, feasible, and academically rigorous topics tailored to their subject, ensuring a smooth start to their dissertation journey.</p>
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
                How Our Dissertation Topic Selection Helps UK Supports You
            </h2>
            <p>We follow a systematic approach to guide you from brainstorming to final topic approval</p>
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

    <x-common-section.benifits-of-assignment-cards title="Unique Advantages of Our Dissertation Topic Selection help UK"
            subtitle="Selecting the right dissertation topic is the foundation of academic success. Our Dissertation Topic Selection Help UK is built to provide clarity, academic relevance, and confidence at the very first stage of your research journey. Every topic we propose is carefully evaluated to meet UK university standards, marking criteria, and research feasibility."
        :items="[
            [
                'icon' => 'fas fa-clock-o',
                'heading' => 'Original, Research-Driven Topic Development',
                'paragraph' =>
                    'Each dissertation topic is developed from scratch based on current academic trends, existing research gaps, and your course requirements. We never recycle or reuse topics, ensuring your research begins with a genuinely original idea.',
            ],

            [
                'icon' => 'fas fa-edit',
                'heading' => 'Plagiarism-Free Topic Assurance',
                'paragraph' =>
                    'All suggested topics are checked against academic databases to ensure originality. This protects your academic credibility and ensures your topic is suitable for approval by UK supervisors.',
            ],

            [
                'icon' => 'fas fa-headset',
                'heading' => 'Subject-Specific Academic Expertise',
                'paragraph' =>
                    'Your dissertation topic is handled by experts with advanced qualifications (Master’s or PhD) in your discipline. This guarantees subject relevance, theoretical depth, and alignment with UK research frameworks.',
            ],

            [
                'icon' => 'fas fa-ban',
                'heading' => 'Supervisor-Friendly Topic Alignment',
                'paragraph' =>
                    'We design topics that are realistic, researchable, and clearly defined, making them easier to justify, defend, and gain approval from UK university supervisors.',
            ],

            [
                'icon' => 'fas fa-line-chart',
                'heading' => 'Flexible Revisions Until Approval',
                'paragraph' =>
                    'If your supervisor requests refinements or adjustments, we offer free revisions to reshape the topic scope, focus, or research direction until it meets academic expectations.',
            ],

            [
                'icon' => 'fas fa-lock',
                'heading' => 'On-Time Delivery with Full Transparency',
                'paragraph' =>
                    'We understand proposal deadlines matter. Your dissertation topic will be delivered within the agreed timeframe, and our clear refund policy ensures fairness if expectations are not met.',
            ],
        ]" 
    />

    <section class="page-section testimonial-section-four">
        <div class="container">
            <h2 class="section-title">Meet Your Academic Experts for Dissertation Topic Selection UK</h2>

            <p class="section-description">
                Our platform connects students with UK-verified academic professionals who specialise in various disciplines. They guide you through the topic selection process, offering practical, evidence-based advice that ensures your dissertation starts on a solid, research-ready foundation.
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

    <x-common-section.academic--writing-cards  heading="Your Dissertation Journey Made Easier: Complete UK Academic Support"
        paragraph="We support UK students throughout their dissertation journey by offering clear guidance, academic structure, and reliable writing assistance, helping reduce stress while improving confidence and overall academic performance."
        :cards="[
            [
                'heading' => 'Dissertation Results Help UK',
                'href' => '/dissertation-results-help-uk',
                'paragraph' =>
                    'Our specialists assist in transforming raw research data into meaningful, logically structured results chapters. We help you interpret findings accurately, highlight key trends, and position your results in the context of your research objectives, ensuring clarity and academic rigour throughout.',
            ],
            [
                'heading' => 'Dissertation Literature Review Help UK',
                'href' => '/dissertation-literature-review-help-uk',
                'paragraph' =>
                    'We craft comprehensive literature reviews that critically evaluate existing studies, identify gaps, and integrate evidence seamlessly. This ensures that your research questions are grounded in scholarly discourse, providing a strong theoretical and methodological foundation for your dissertation.',
            ],

            [
                'heading' => 'Master\'s Dissertation Help UK',
                'href' => '/masters-dissertation-help-uk',
                'paragraph' =>
                    'Our experts guide you in converting research insights into well-organised, coherent chapters. From conceptual frameworks to detailed analysis, we ensure that every section reflects professional academic standards and logical flow, enhancing readability and examiner impact.',
            ],

            [
                'heading' => 'Case Study Dissertation Help UK',
                'href' => '/case-study-dissertation-help-uk',
                'paragraph' =>
                    'When your dissertation involves practical applications, we provide targeted case study support. Our team ensures actionable recommendations, evidence-based conclusions, and robust analysis, highlighting the relevance and real-world significance of your findings.',
            ],

            [
                'heading' => 'Dissertation Editing and Proofreading Help UK',
                'href' => '/dissertation-editing-and-proofreading-help-uk',
                'paragraph' =>
                    'We offer meticulous editing and proofreading to guarantee flawless formatting, accurate referencing and polished academic style. This ensures your submission is professional, coherent, and fully compliant with UK university standards.',
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
                        <h2>Discover Expert Dissertation Topic Selection Services UK</h2>
                        <p class="content-description">
                            Choosing the right dissertation topic is the foundation of academic success. Our dissertation topic selection services UK provide students with a carefully guided, research-focused process to identify topics that are original, feasible, and aligned with their study discipline. A strong topic ensures a smoother dissertation journey, maximises research potential, and impresses supervisors with its academic relevance.
                            <br> Our online <a href="/dissertation-writing-help-services">dissertation writing help services uk</a> focuses on delivering personalised support. We analyse your academic background, research interests, and course requirements to propose topics that are not only innovative but also practically researchable within your timeframe and methodology.
                        </p>
                        
                    </div>
                    <div class="content-box">
                        <h2>How Our Dissertation Topic Selection Helps the UK Work</h2>
                        <p class="content-description">
                            We follow a strategic approach to ensure you secure a topic that meets UK university expectations:
                        </p>
                                                
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">
                                <b>Understanding Your Subject and Goals</b> <br>
                                We start by exploring your academic interests, previous coursework, and desired research outcomes. This ensures that the proposed topic aligns with your strengths and ambitions.
                            </li>
                            <li class="list-group-item">
                                <b>Research Trend Analysis</b> <br>
                                Our experts scan the latest literature to identify gaps, emerging trends, and underexplored areas. This ensures your topic is academically relevant and contributes to ongoing scholarly discussions.
                            </li>
                            <li class="list-group-item">
                                <b>Topic: Feasibility and Scope Evaluation</b> <br>
                                We assess whether the proposed topic can be realistically researched, considering data availability, research methodology, and timeline constraints.
                            </li>
                            <li class="list-group-item">
                                <b>Detailed Topic Proposal</b> <br>
                                You receive a clear topic recommendation with supporting rationale, potential research questions, and scope. This structured approach gives you confidence when presenting the topic to your supervisor.
                            </li>
                        </ul>
                    </div>                            
                    <div class="content-box">                        
                        <h2>Unique Benefits of Using Our Dissertation Topic Selection Services</h2>                       
                        
                        <h3>Expert Academic Guidance</h3>
                        <p class ="content-description">
                            Our team comprises postgraduate scholars with hands-on experience in dissertation supervision. They provide tailored advice, helping you navigate complex research areas and choose a topic that aligns with academic expectations.
                        </p>
                        
                        <h3>Original and Researchable Topics</h3>
                        <p class ="content-description">
                            We guarantee topics that are unique, researchable, and academically significant. Each recommendation is crafted to avoid generic suggestions, ensuring your dissertation stands out.
                        </p>
                        
                        <h3>Evidence-Based Topic Justification</h3>
                        <p class ="content-description">
                            Every topic comes with a clear justification, including research objectives, potential methodologies, and positioning within current literature. This helps you defend your topic confidently.
                        </p>
                        
                        <h3>Continuous Feedback and Support</h3>
                        <p class ="content-description">
                            Our support team is available 24/7, enabling you to refine your topic, discuss supervisor feedback, and adjust proposals as needed.
                        </p>
                        
                        <h3>Confidential and Secure Service</h3>
                        <p class ="content-description">
                            We ensure complete confidentiality. All project information and communications are protected with advanced encryption, keeping your academic work safe.
                        </p>
                        
                        <h3>Strategic Integration With Other Dissertation Services</h3>
                        <p class ="content-description">
                            Our topic selection support integrates seamlessly with other services, providing a comprehensive academic support system:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Dissertation Literature Review Writing Help UK: </b>Position your topic within scholarly discourse.</li>
                            <li class="list-group-item"><b>Dissertation Results Help UK: </b>Align your findings with your chosen topic.</li>
                            <li class="list-group-item"><b>Masters Dissertation Help UK: </b>Transform research objectives into structured, coherent chapters.</li>
                            <li class="list-group-item"><b>Case Study Dissertation Writing Services UK: </b>Ensure practical relevance and actionable insights.</li>
                            <li class="list-group-item"><b>Dissertation Editing and Proofreading Service UK: </b>Achieve perfect formatting, referencing, and style.</li>
                        </ul>
                        <p class ="content-description">
                            By linking topic selection with these services, we ensure that every dissertation stage is coherent, academically rigorous, and high-scoring.
                        </p>
                        
                    </div>
                    <div class="content-box">
                        <h2>Why Students Prefer Our Online Dissertation Topic Selection Help UK</h2>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>PhD & Master's-Level Experts: </b>Receive guidance from professionals experienced with UK university standards.</li>
                            <li class="list-group-item"><b>Innovative Topic Ideas: </b>Topics are unique, academically valuable, and aligned with research feasibility.</li>
                            <li class="list-group-item"><b>Step-by-Step Support: </b>We guide you from initial brainstorming to final topic approval.</li>
                            <li class="list-group-item"><b>Secure & Confidential: </b>Your ideas and personal details remain fully protected.</li>
                            <li class="list-group-item"><b>Revision Support: </b>Topics can be refined based on supervisor feedback or evolving research focus.</li>
                        </ul>
                                                                                               
                    </div>

                    
                </div>

                <div class="column">
                    <div class="content-box">
                        <h2>Master Your Dissertation With the Best Topic Selection Services UK</h2>
                        <p class ="content-description">
                            Finding a dissertation topic that is original, researchable, and academically relevant is a critical challenge for many students. Our best dissertation topic selection services UK are specifically designed to provide UK students with expert academic guidance, forming a reliable part of our broader <a href="/">assignment help uk</a> support that helps learners start their research journey with confidence and clarity.
                            <br> By leveraging our online dissertation topic selection help, students gain access to proven research strategies commonly used within professional <a href="/assignment-writing-help-services">assignment writing help services uk</a>. These strategies focus on identifying genuine gaps in existing literature, analysing current academic trends, and proposing dissertation topics that are innovative, feasible, and fully aligned with UK university expectations and subject requirements.
                        </p>   
                        
                    </div>
                    <div class="content-box">
                        <h2>Our Step-by-Step Approach for Dissertation Topic Selection</h2>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Initial Consultation and Idea Generation: </b>We start by discussing your interests, strengths, and academic requirements. This allows our experts to generate tailored topic ideas that resonate with your study goals.</li>
                            <li class="list-group-item"><b>Comprehensive Literature Exploration: </b>Our team investigates relevant literature to highlight under-researched areas. This guarantees your dissertation topic is both academically significant and contributes to the field.</li>
                            <li class="list-group-item"><b>Evaluation of Research Scope and Feasibility: </b>We assess your proposed ideas against practical research constraints, such as available data, methodology, and timeline, ensuring the topic is viable.</li>
                            <li class="list-group-item"><b>Customised Topic Recommendation: </b>You receive a clear, structured topic suggestion, including research objectives, key questions, and justification, helping you confidently present your proposal to supervisors.</li>
                        </ul>

                    </div>
                    <div class="content-box">                    
                        <h2>Key Features of Our Dissertation Topic Selection Services</h2>

                        <h3>Tailored Academic Expertise</h3>
                        <p class ="content-description">
                            Our experts provide personalised recommendations based on your subject, level of study, and research aspirations, ensuring every topic is a perfect fit for your academic journey.
                        </p>                        

                        <h3>Originality and Academic Rigour</h3>
                        <p class ="content-description">
                            Topics are meticulously crafted to be unique, avoiding repetition or generic ideas, while meeting the high standards of UK universities.
                        </p>                        

                        <h3>Evidence-Backed Justification</h3>
                        <p class ="content-description">
                            We provide a comprehensive rationale for each topic, detailing objectives, methodology, and alignment with current literature, enabling confident topic defence.
                        </p>                        

                        <h3>Continuous Guidance and Adjustments</h3>
                        <p class ="content-description">
                            Our support doesn’t end with the recommendation. Students can seek ongoing feedback, adjust proposals, and ensure full alignment with supervisor expectations.
                        </p>                        

                        <h3>Confidentiality Guaranteed</h3>
                        <p class ="content-description">
                            All communications, data, and project details are encrypted and secure, protecting your ideas and academic work from disclosure.
                        </p>                        
                    </div>    
                    <div class="content-box">
                        
                        <h2>Complementary Dissertation Services to Maximise Success</h2>
                        <p class ="content-description">We complement topic selection with services that streamline the dissertation process:</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Dissertation Literature Review Writing Help UK: </b>Frame your topic in the context of academic discourse.</li>
                            <li class="list-group-item"><b>Dissertation Results Help UK: </b>Ensure findings support your chosen topic and research questions.</li>
                            <li class="list-group-item"><b>Masters Dissertation Help UK: </b>Organise your research into coherent, structured chapters.</li>
                            <li class="list-group-item"><b>Case Study Dissertation Writing Services UK: </b>Highlight practical implications and actionable insights.</li>
                            <li class="list-group-item"><b>Dissertation Editing and Proofreading Service UK: </b>Guarantee flawless formatting, citation, and style compliance.</li>
                        </ul>
                        
                    </div>
                    <div class="content-box">
                        
                        <h2>Why Choose Our Help Me Dissertation Topic Selection Services UK</h2>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Expert Academic Team: </b>PhD and Master's-level scholars provide precise, evidence-based topic guidance.</li>
                            <li class="list-group-item"><b>Original, Researchable Topics: </b>Innovative topics tailored for UK academic standards.</li>
                            <li class="list-group-item"><b>Stepwise Guidance: </b>From brainstorming to final topic approval, every step is carefully monitored.</li>
                            <li class="list-group-item"><b>Secure and Confidential Service: </b>Complete privacy for your research ideas and personal details.</li>
                            <li class="list-group-item"><b>Revision Support: </b>Topics can be refined based on feedback or research evolution, ensuring the perfect start.</li>
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
                <h2 class="section-title">Frequently Asked Questions - Dissertation Topic Selection UK</h2>

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