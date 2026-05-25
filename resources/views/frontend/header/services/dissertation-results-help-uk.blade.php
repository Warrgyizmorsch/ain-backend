@extends('frontend-layouts.app')

@section('content')

    <section class="bg-hero">
        <div class="container-section">
            <div class="hero-content">
                <!-- Content Column -->
                <div class="content-column">
                    <h1 class="main-heading">
                        Reliable Dissertation Results Help UK - Methodical, Precise, and University Ready
                    </h1>
                    <div class="content-paragraph">
                        <p>Achieve top marks with expertly written dissertation results UK chapters, crafted specifically for the rigorous standards of UK universities.</p>
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
                How Our Online Dissertation Results Help UK Works
            </h2>
            <p>We provide a simple, secure, three-step process to connect you with the best dissertation results writing UK service, ensuring your results chapter is accurate, detailed, and delivered on time.</p>
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

    <x-common-section.benifits-of-assignment-cards title="Assured Excellence in Dissertation Results Writing UK"
            subtitle="Choosing our expert service means investing in precision, academic rigour, and reliability. Every dissertation results UK chapter we produce is carefully crafted to reflect your research data accurately, presented logically, and formatted according to UK university standards."
        :items="[
            [
                'icon' => 'fas fa-clock-o',
                'heading' => '100% Human-Crafted and AI-Free Content',
                'paragraph' =>
                    'All dissertation results sections are meticulously written from scratch by our qualified academic professionals, ensuring authentic analysis and fully human-generated content that meets the highest scholarly standards.',
            ],

            [
                'icon' => 'fas fa-edit',
                'heading' => 'Guaranteed Plagiarism-Free Work',
                'paragraph' =>
                    'We check every dissertation result UK submission against comprehensive academic databases to certify it plagiarism-free, safeguarding your academic integrity and giving you complete confidence in your work.',
            ],

            [
                'icon' => 'fas fa-headset',
                'heading' => 'Expertise You Can Depend On: PhD & Master’s Specialists',
                'paragraph' =>
                    'Our writers hold advanced postgraduate degrees relevant to your field, enabling precise interpretation, rigorous presentation, and accurate reporting of your research findings in line with UK academic expectations.',
            ],

            [
                'icon' => 'fas fa-ban',
                'heading' => 'Round-the-Clock Academic Assistance',
                'paragraph' =>
                    'With 24/7 live support, you can track the progress of your dissertation results writing UK project, clarify doubts, and receive expert guidance at any stage of the process to ensure a smooth experience.',
            ],

            [
                'icon' => 'fas fa-line-chart',
                'heading' => 'Free Revisions for Total Satisfaction',
                'paragraph' =>
                    'We prioritise your academic success. Free revisions are offered to ensure your dissertation results chapter aligns perfectly with your research methodology, data analysis, and overall academic requirements.',
            ],

            [
                'icon' => 'fas fa-lock',
                'heading' => 'Timely Delivery and Refund Guarantee',
                'paragraph' =>
                    'We respect your deadlines. Your dissertation results chapter will be delivered promptly, and our comprehensive refund policy protects you if our service does not meet the promised standards.',
            ],
        ]" 
    />

    <section class="page-section testimonial-section-four">
        <div class="container">
            <h2 class="section-title">Meet Your UK-Verified Expert for Dissertation Results Help UK</h2>

            <p class="section-description">
                Our platform connects you with a highly select network of PhD and Master’s level academic experts. You will receive tailored support to ensure your results section is comprehensive, accurate, and professionally presented.
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

    <x-common-section.academic--writing-cards  heading="Extending Support Across All Dissertation Sections: Expert UK Writing Services"
        paragraph="Our UK-based academic support assists students across every dissertation stage, from proposal development to final chapters, ensuring clarity, consistency, and strong alignment with university marking criteria."
        :cards="[
            [
                'heading' => 'Dissertation Topic Selection Services UK',
                'href' => '/dissertation-topic-selection-service-help-uk',
                'paragraph' =>
                    'We assist in selecting and refining your dissertation topic by critically evaluating existing research, identifying knowledge gaps, and positioning your study effectively within the wider academic discussion.',
            ],
            [
                'heading' => 'Master\'s Dissertation Help UK',
                'href' => '/masters-dissertation-help-uk',
                'paragraph' =>
                    'Our experts transform raw research data into well-structured, meaningful results. We ensure clarity, logical organisation, and strict adherence to your chosen methodology, delivering results that meet UK university standards.',
            ],

            [
                'heading' => 'Case Study Dissertation Help UK',
                'href' => '/case-study-dissertation-help-uk',
                'paragraph' =>
                    'We provide precise guidance for case study dissertations, integrating analysis with supporting evidence. Every section is crafted to reflect depth, relevance, and scholarly excellence.',
            ],

            [
                'heading' => 'Dissertation Editing and Proofreading Help UK',
                'href' => '/dissertation-editing-and-proofreading-help-uk',
                'paragraph' =>
                    'Our meticulous editing and proofreading service ensures flawless referencing, consistent formatting and polished academic style, safeguarding your dissertation from avoidable grade deductions.',
            ],

            [
                'heading' => 'Dissertation Literature Review Writing Help UK',
                'href' => '/dissertation-literature-review-help-uk',
                'paragraph' =>
                    'We integrate your literature review, results, and findings to create a coherent and logically structured dissertation. This service ensures that your research narrative is academically rigorous and easy to follow.',
            ],

            [
                'heading' => 'Dissertation Conclusions Writing UK',
                'href' => '/blog/how-to-write-the-thesis-or-dissertation-conclusion-the-step-by-step-guide',
                'paragraph' =>
                    'Our specialists craft actionable, evidence-based conclusions that highlight the significance of your findings. Each conclusion reflects academic rigour, providing practical insights and reinforcing the quality of your dissertation.',
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
                        <h2>Achieving Academic Mastery Through Expert Dissertation Results Writing UK</h2>
                        <p class="content-description">
                            Producing a high-quality dissertation results chapter is a pivotal step toward academic excellence in UK universities. This section reflects the analytical rigour of your research, the accuracy of your data interpretation, and your ability to present complex findings in a clear, structured manner. Students often find it challenging to convert raw data into meaningful insights while maintaining coherence and aligning results with research objectives. Our <a href="/dissertation-writing-help-services">dissertation writing help service uk</a> is designed to address these challenges, providing comprehensive support for crafting precise, academically rigorous results chapters that meet UK university standards.
                        </p>
                        
                    </div>
                    <div class="content-box">
                        <h2>Expertise That Defines the Best Dissertation Results Service UK</h2>
                        <p class="content-description">
                            Our service is built upon a team of highly qualified academics, each with in-depth knowledge of research methodologies and analytical techniques. Their expertise ensures that every dissertation results chapter is both accurate and insightful:
                        </p>
                                                
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Methodological Precision: </b>We carefully interpret datasets using advanced statistical tools and qualitative analysis methods, ensuring every figure, chart, and table communicates meaningful information.</li>
                            <li class="list-group-item"><b>Alignment With Research Objectives: </b>Your results are presented in direct correlation with your research questions, demonstrating a strong link between evidence and study aims.</li>
                            <li class="list-group-item"><b>Academic Integrity and Clarity: </b>Every results chapter is written with rigorous adherence to ethical research reporting and clear, logical flow for easy comprehension</li>
                        </ul>
                    </div>                            
                    <div class="content-box">                        
                        <h2>Our Structured Approach to Dissertation Results Writing</h2>                       
                        
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Comprehensive Data Analysis: </b>We transform complex raw data into understandable findings, highlighting patterns, trends, and anomalies.</li>
                            <li class="list-group-item"><b>Integration of Statistical and Qualitative Tools: </b>Our team employs both qualitative coding techniques and quantitative statistical analyses to ensure methodological rigour.</li>
                            <li class="list-group-item"><b>Visual Representation of Data: </b>Charts, tables, and graphs are presented in a clear, professional manner, improving readability and enhancing the overall impact of your results.</li>
                            <li class="list-group-item"><b>Ethical Reporting: </b>All findings are reported transparently, reflecting genuine outcomes without manipulation or bias.</li>
                            <li class="list-group-item"><b>Consistency Checks: </b>Every result is cross-verified against methodology and data collection procedures to ensure alignment and reliability.</li>
                            <li class="list-group-item"><b>Contextual Interpretation: </b>Results are contextualised within existing literature to demonstrate relevance and scholarly awareness.</li>
                        </ul>
                    </div>
                    <div class="content-box">
                        <h2>Why Choose Our Dissertation Results Writing UK Service</h2>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>PhD-Level Expertise: </b>Writers with postgraduate qualifications provide accurate interpretation and insightful presentation of your research.</li>
                            <li class="list-group-item"><b>Editing and Proofreading Excellence: </b>Every results chapter undergoes meticulous review to refine clarity, grammar, and academic style.</li>
                            <li class="list-group-item"><b>Deadline Assurance: </b>On-time delivery is guaranteed, ensuring you meet university submission deadlines without stress.</li>
                        </ul>
                        <p class ="content-description">
                            Our service ensures that your results chapter not only presents your data but also reflects the depth of your research expertise, giving you a competitive edge in academic evaluation.
                        </p>                                                                    
                    </div>

                    
                </div>

                <div class="column">
                    <div class="content-box">
                        <h2>Tailored Dissertation Results Support UK to Maximise Academic Success</h2>
                        <p class ="content-description">
                            The dissertation results chapter is more than a mere display of data; it is a showcase of your analytical capability, methodological rigour, and clarity of thought. Our online dissertation results help the UK ensure that students achieve academic excellence by converting raw data into insightful, examiner-ready results chapters that meet the stringent standards of UK universities. Through trusted <a href="/">assignment help uk</a>, students receive structured guidance that strengthens data presentation, enhances analytical accuracy, and aligns every section with university marking criteria, supporting learning while maintaining full academic integrity.
                        </p>   
                        
                    </div>
                    <div class="content-box">
                        <h2>Strategic Support for Academic Excellence</h2>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Tailored Results for Your Methodology: </b>Whether your research is experimental, survey-based, case study-oriented, or uses mixed methods, our experts adapt results presentation to suit your specific design.</li>
                            <li class="list-group-item"><b>Integration With Literature Review: </b>Your findings are framed in the context of current research, identifying key trends, gaps, and implications relevant to your study objectives.</li>
                        </ul>

                    </div>
                    <div class="content-box">                    
                        <h2>Structured and Methodical Writing Approach</h2>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Interpretation of Complex Data: </b>Raw numerical or qualitative data is meticulously analysed to produce coherent and meaningful results.</li>
                            <li class="list-group-item"><b>Evidence-Based Conclusions: </b>Each finding is supported by your research data, ensuring credibility and compliance with academic standards.</li>
                            <li class="list-group-item"><b>Logical Flow and Clarity: </b>We structure results chapters to guide the reader seamlessly through the research outcomes, maintaining clarity and logical progression.</li>
                            <li class="list-group-item"><b>Quality Assurance Checks: </b>Independent editors review every chapter to ensure flawless grammar, coherent argumentation, and adherence to academic conventions.</li>
                            <li class="list-group-item"><b>Use of Visual Aids: </b>Well-designed tables, graphs, and charts highlight key data points for easy comprehension.</li>
                            <li class="list-group-item"><b>Analytical Depth: </b>Our experts provide insights that demonstrate critical thinking, allowing examiners to appreciate the scholarly rigour of your work.</li>
                        </ul>                     
                    </div>    
                    <div class="content-box">
                        
                        <h2>Advantages of Our Online Dissertation Results Help UK</h2>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>PhD and Master’s Qualified Writers: </b>Your results chapter is handled by experts familiar with UK university expectations and grading rubrics.</li>
                            <li class="list-group-item"><b>Revision Support: </b>Free revisions ensure your results align with research objectives and methodological requirements.</li>
                            <li class="list-group-item"><b>Confidential and Secure: </b>All project details remain private, safeguarded by encryption and strict confidentiality policies.</li>
                        </ul>
                        <p class ="content-description">By leveraging our service, students gain not only a high-quality results chapter but also a learning model that demonstrates advanced academic structuring, accurate data interpretation, and strict methodological adherence. Through our reliable <a href="/assignment-writing-help-services">assignment writing help services uk</a>, students are guided to understand how academic findings are presented and evaluated, enabling them to replicate the process confidently in future submissions while fostering long-term academic growth and self-assurance.</p>
                        
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
                <h2 class="section-title">Frequently Asked Questions About Dissertation Results Help UK</h2>

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