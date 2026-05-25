@extends('frontend-layouts.app')

@section('content')

    <section class="bg-hero">
        <div class="container-section">
            <div class="hero-content">
                <!-- Content Column -->
                <div class="content-column">
                    <h1 class="main-heading">
                        Top Dissertation Findings Writing Service UK: Accurate, Insightful Results
                    </h1>
                    <div class="content-paragraph">
                        <p>Achieve top marks with expertly structured dissertation findings crafted to meet the exacting standards of UK universities. Our professional service simplifies complex data presentation, delivering precise, well-organised findings that showcase your research effectively.</p>
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
                How Does Our Expert Online Dissertation Findings Writing UK Work?
            </h2>
            <p>We’ve created a streamlined three-step process to connect you with the best dissertation findings writing service UK, ensuring a seamless and stress-free path to producing accurate, high-quality research results.</p>
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

    <x-common-section.benifits-of-assignment-cards title="Guaranteed Excellence: Promises from the Leading Dissertation Findings Writing UK Experts"
            subtitle="When you choose our experts, you are not merely hiring a service; you are securing a comprehensive guarantee of accuracy, originality, and reliability. We are committed to providing the best dissertation findings writing support."
        :items="[
            [
                'icon' => 'fas fa-clock-o',
                'heading' => 'Fully Human-Crafted and Original Content',
                'paragraph' =>
                    'We reject automated writing entirely. Every dissertation findings chapter is meticulously written by our skilled researchers, guaranteeing authentic analysis and fully human-generated content.',
            ],

            [
                'icon' => 'fas fa-edit',
                'heading' => 'Verified Plagiarism-Free Work',
                'paragraph' =>
                    'All documents undergo thorough checks against trusted academic databases to ensure complete originality. Your dissertation findings UK section will meet the strictest academic integrity standards.',
            ],

            [
                'icon' => 'fas fa-headset',
                'heading' => 'Expert Analysts with Advanced Degrees',
                'paragraph' =>
                    'Interpreting and presenting research findings requires in-depth expertise. We assign only writers with Master’s and PhD qualifications, ensuring precise and methodologically sound dissertation results.',
            ],

            [
                'icon' => 'fas fa-ban',
                'heading' => 'Round-the-Clock Academic Assistance',
                'paragraph' =>
                    'Deadlines can be tight, and queries urgent. Our 24/7 support team provides immediate guidance, updates, and answers to help you stay on track with your dissertation findings UK.',
            ],

            [
                'icon' => 'fas fa-line-chart',
                'heading' => 'Complimentary Revisions for Perfect Alignment',
                'paragraph' =>
                    'We prioritise your academic satisfaction. Free revisions are offered to ensure your dissertation findings chapter matches your research goals, data analysis, and university expectations.',
            ],

            [
                'icon' => 'fas fa-lock',
                'heading' => 'Timely Delivery and Money-Back Guarantee',
                'paragraph' =>
                    'We respect your deadlines. Your dissertation findings will be delivered promptly, and our strong refund policy ensures protection if our service fails to meet promised quality standards.',
            ],
        ]" 
    />

    <section class="page-section testimonial-section-four">
        <div class="container">
            <h2 class="section-title">Meet Your UK-Verified Expert for Dissertation Findings Writing Help UK</h2>

            <p class="section-description">
                Our platform connects you with a carefully selected network of academic professionals holding Master’s and PhD degrees. We ensure you collaborate with the ideal expert to craft precise, high-quality dissertation findings, providing personalised support tailored to your research objectives and academic standards.
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

    <x-common-section.academic--writing-cards  heading="Beyond Findings: Explore Our Other Dissertation Writing Services UK"
        paragraph="While we specialise in precise dissertation findings writing UK, our academic experts provide comprehensive support across all other challenging dissertation sections. Expand your academic assistance with our tailored services below"
        :cards="[
            [
                'heading' => 'Dissertation Literature Review Writing Help UK',
                'href' => '/dissertation-literature-review-help-uk',
                'paragraph' =>
                    'Our service ensures a detailed, critical evaluation of existing research. We synthesise key studies, highlight gaps in the literature, and position your work within the current academic conversation, providing a robust foundation for your dissertation.',
            ],
            [
                'heading' => 'Dissertation Results Help UK',
                'href' => '/dissertation-results-help-uk',
                'paragraph' =>
                    'We assist in presenting your research data clearly and accurately. Our experts structure results logically, linking them seamlessly to your methodology, ensuring clarity and academic rigour.',
            ],

            [
                'heading' => 'Dissertation Topic Selection Services UK',
                'href' => '/dissertation-topic-selection-service-help-uk',
                'paragraph' =>
                    'Get support in choosing a compelling and feasible dissertation topic. We help you define research objectives, formulate questions, and establish a rationale, setting up a strong base for your project.',
            ],

            [
                'heading' => 'Master\'s Dissertation Help UK',
                'href' => '/masters-dissertation-help-uk',
                'paragraph' =>
                    'Transform raw research data into structured, insightful findings. Our team analyses qualitative and quantitative data, ensuring results are precise, well-presented, and aligned with your research objectives.',
            ],

            [
                'heading' => 'Case Study Dissertation Help UK',
                'href' => '/case-study-dissertation-help-uk',
                'paragraph' =>
                    'We craft conclusions that effectively summarise your research, offering actionable recommendations. This service ensures your dissertation demonstrates both academic thoroughness and practical applicability.',
            ],

            [
                'heading' => 'Dissertation Editing and Proofreading Service UK',
                'href' => '/dissertation-editing-and-proofreading-help-uk',
                'paragraph' =>
                    'Our meticulous editing and proofreading service guarantees flawless formatting, accurate referencing (Harvard, APA, MLA, OSCOLA), and polished academic style, safeguarding your dissertation from grade deductions.',
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
                        <h2>Excelling in Dissertation Findings: Expert Writing for Outstanding Academic Achievement UK</h2>
                        <p class="content-description">
                            Submitting a top-quality dissertation findings chapter represents a critical milestone in your academic journey within UK universities. Crafting this section demands not just data presentation but intellectual sophistication-interpreting results, linking them to research objectives, and demonstrating analytical rigour. The challenges of handling complex datasets, aligning findings with methodology, and maintaining clarity under strict deadlines can overwhelm students striving for distinction. Our <a href="/dissertation-writing-help-services">dissertation writing help uk</a> is specifically designed to deliver expertly structured, methodologically sound, and academically robust findings that impress examiners and elevate your final grade.
                        </p>
                        
                    </div>
                    <div class="content-box">
                        <h2>Expertise That Defines the Best Dissertation Findings Service UK</h2>
                        
                        <h3>The Authority of PhD and Master’s Writers</h3>
                        <p class="content-description">
                            We collaborate exclusively with scholars holding advanced degrees, ensuring that your dissertation findings UK section are handled by experts with deep understanding of research analysis. Their specialised knowledge guarantees precise interpretation and presentation of complex datasets, tailored to your academic discipline.
                        </p>
                        
                        <h3>Optimised for UK Assessment Standards</h3>
                        <p class="content-description">
                            Our writers understand the unique marking criteria of UK universities. Every findings chapter is structured to maximise assessment scores, demonstrating analytical precision, methodological coherence, and clear linkage to research questions.
                        </p>
                        
                        <h3>Discipline-Specific Clarity and Accuracy</h3>
                        <p class="content-description">
                            Your findings will employ appropriate, discipline-specific terminology and current, peer-reviewed sources. Whether your dissertation is in business, engineering, social sciences, or health studies, the results section is formatted to meet the highest scholarly expectations.
                        </p>
                                                                        
                    </div>                            
                    <div class="content-box">
                        <h2>Transforming Raw Data into Insightful Findings</h2>
                        
                        <h3>Meticulous Data Interpretation</h3>
                        <p class="content-description">
                            We go beyond simple reporting. Patterns, correlations, and trends are critically analysed and contextualised within your research objectives, ensuring the findings provide genuine scholarly insight.
                        </p>
                        
                        <h3>Integration with Methodology and Literature Review</h3>
                        <p class="content-description">
                            Each result is connected to the methodology chapter and supported by the literature review, ensuring continuity and reinforcing the academic argument.
                        </p>
                        
                        <h3>Visual and Textual Clarity</h3>
                        <p class="content-description">
                            Tables, graphs, and charts are professionally formatted, captioned, and referenced, presenting data clearly and enhancing readability for markers.
                        </p>
                        
                        <h3>Evidence-Driven Analysis</h3>
                        <p class="content-description">
                            Every interpretation is backed by verifiable academic reasoning, ensuring objectivity, credibility, and adherence to UK university standards.
                        </p>
                                                                        
                    </div>                            
                    <div class="content-box">
                        <h2>The Learning Value of Dissertation Findings Support</h2>
                        
                        <h3>High-Level Academic Blueprint</h3>
                        <p class="content-description">
                            By studying the expertly crafted findings chapter, students can learn to structure results, link analysis to objectives, and interpret data effectively, providing a blueprint for future research projects.
                        </p>                                                
                                                                        
                    </div>                            
                    <div class="content-box">
                        <h2>Ethical Standards and Secure Academic Support</h2>
                        
                        <h3>Human-Written, Original Content</h3>
                        <p class="content-description">
                            All dissertation findings are created from scratch by expert researchers, guaranteeing 100% human-written, AI-free content.
                        </p>                                                
                        
                        <h3>Confidentiality and Data Security</h3>
                        <p class="content-description">
                            We use state-of-the-art encryption and a strict non-disclosure policy, ensuring your personal data and research remain secure while using our online dissertation findings writing help UK.
                        </p>                                                
                                                                        
                    </div>                            
                    <div class="content-box">
                        <h2>Ensuring Exceptional Quality</h2>
                        
                        <h3>Professional Proofreading and Editing</h3>
                        <p class="content-description">
                            An independent QA editor reviews every chapter for linguistic clarity, logical flow, and academic consistency.
                        </p>                                                
                        
                        <h3>Verified Data and Academic Integrity</h3>
                        <p class="content-description">
                            All results are double-checked for accuracy, ensuring reliability and scholarly credibility throughout your dissertation findings chapter.
                        </p>                                                
                                                                        
                    </div>                            
                    <div class="content-box">
                        <h2>Building Confidence and Academic Mastery</h2>
                        
                        <h3>Replicable Model for Future Submissions</h3>
                        <p class="content-description">
                            Our expertly written findings chapter serves as a model, allowing students to replicate high standards independently in future projects.
                        </p>                                                
                        
                        <h3>Closing Academic Gaps</h3>
                        <p class="content-description">
                            We teach advanced structuring, evidence synthesis, and critical presentation of results, addressing skills that are often assumed but not explicitly taught.
                        </p>                                                
                        
                        <h3>Support Across All Disciplines</h3>
                        <p class="content-description">
                            Our service caters to diverse academic areas, ensuring that every dissertation findings chapter is written to the highest level of scholarly excellence, no matter the complexity of the research.
                        </p>                                                
                                                                        
                    </div>  
                </div>

                <div class="column">
                    <div class="content-box">
                        <h2>Elevate Your Research with Expert Online Dissertation Findings Help UK</h2>
                        <p class ="content-description">
                            Crafting the findings chapter of a dissertation is a delicate balance of accuracy, clarity, and analytical depth. Many students struggle to translate raw data into meaningful insights that align with research objectives and UK university standards. Our online dissertation findings writing help UK offers a strategic solution, supported by expert-led <a href="/">assignment help uk</a> that ensures your results are presented clearly, logically, and in full compliance with academic assessment criteria, while highlighting the significance of your research and maintaining rigorous scholarly quality.
                        </p>   
                        
                    </div>
                    <div class="content-box">
                        <h2>Why Choose Our Dissertation Findings Helping Service UK</h2>

                        <h3>Specialist Academic Writers at Your Service</h3>
                        <p class ="content-description">All chapters are created by postgraduate experts (Master’s and PhD holders) with hands-on experience in interpreting complex datasets. This ensures your dissertation findings UK section is precise, methodologically sound, and academically credible.</p>

                        <h3>Tailored to UK University Assessment Criteria</h3>
                        <p class ="content-description">We craft each chapter to align precisely with university marking rubrics, placing strong emphasis on clarity, analytical sophistication, and the effective integration of research objectives. Supported by our trusted <a href="/assignment-writing-help-services">assignment writing help services uk</a>, your findings chapter will demonstrate academic excellence, structured interpretation, and critical engagement with your data, ensuring it meets the highest standards of UK higher education.</p>

                        <h3>Discipline-Specific Insight</h3>
                        <p class ="content-description">Whether your dissertation is in social sciences, STEM, business, or healthcare, our experts ensure terminology, statistical interpretation, and literature integration are perfectly aligned with your field’s standards.</p>

                    </div>
                    <div class="content-box">
                        <h2>Our Methodology for High-Impact Dissertation Findings</h2>

                        <h3>Structured Data Analysis</h3>
                        <p class ="content-description">We convert raw datasets into logical, coherent results using advanced analytical techniques, ensuring that trends, correlations, and anomalies are clearly identified and explained.</p>

                        <h3>Integration with Research Framework</h3>
                        <p class ="content-description">Your results are consistently linked to your methodology and literature review, creating a seamless academic narrative that supports your dissertation’s overall argument.</p>

                        <h3>Visual Representation for Maximum Clarity</h3>
                        <p class ="content-description">Professional charts, tables, and graphs accompany the text, formatted according to UK academic conventions to enhance readability and highlight key findings.</p>

                        <h3>Evidence-Driven Interpretation</h3>
                        <p class ="content-description">Every conclusion drawn is supported by robust evidence and linked directly to research objectives, guaranteeing academic reliability and credibility.</p>

                    </div>
                    <div class="content-box">
                        <h2>Comprehensive Academic Support and Learning</h2>

                        <h3>Model for Future Research Excellence</h3>
                        <p class ="content-description">The findings chapter acts as a learning tool, showing students how to present results effectively, analyse data critically, and structure findings according to UK standards.</p>

                        <h3>Ethical and Original Work</h3>
                        <p class ="content-description">We ensure all content is fully human-written, AI-free, and plagiarism-checked, providing peace of mind and protecting your academic integrity.</p>

                        <h3>Secure and Confidential Service</h3>
                        <p class ="content-description">Your research and personal data are fully safeguarded with advanced encryption and strict privacy policies.</p>

                    </div>
                    <div class="content-box">
                        <h2>Enhancing Quality with Professional Standards</h2>

                        <h3>Editing and Proofreading Expertise</h3>
                        <p class ="content-description">Each chapter undergoes detailed QA checks by an independent editor, ensuring linguistic precision, logical coherence, and alignment with academic conventions.</p>

                        <h3>Verified Results and Source Documentation</h3>
                        <p class ="content-description">All findings are meticulously verified, and the supporting data is documented to enhance credibility, ensuring a high-quality dissertation results chapter.</p>

                    </div>
                    <div class="content-box">
                        <h2>Confidence and Academic Growth</h2>

                        <h3>Empowering Independent Submission</h3>
                        <p class ="content-description">Our expertly prepared dissertation findings provide a model for students to replicate in future research projects, instilling confidence and academic capability.</p>

                        <h3>Filling the Skills Gap</h3>
                        <p class ="content-description">We focus on practical skills such as data analysis, interpretation, linking results to objectives, and presenting findings professionally, critical skills often assumed but rarely taught in-depth.</p>

                        <h3>Comprehensive Support Across Disciplines</h3>
                        <p class ="content-description">Our service covers every research area, guaranteeing that your dissertation findings are written to the highest level, regardless of complexity or field.</p>

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
                <h2 class="section-title">Frequently Asked Questions for Dissertation Findings Writing UK</h2>

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