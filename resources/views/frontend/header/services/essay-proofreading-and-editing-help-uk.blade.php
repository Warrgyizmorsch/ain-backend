@extends('frontend-layouts.app')

@section('content')

    <section class="bg-hero">
        <div class="container-section">
            <div class="hero-content">
                <!-- Content Column -->
                <div class="content-column">
                    <h1 class="main-heading">
                        Premium Essay Proofreading & Editing Services UK for High-Scoring Academic Work
                    </h1>
                    <div class="content-paragraph">
                        <p>Producing a polished academic essay that matches UK university standards requires far more than
                            good ideas. Even well-researched papers lose marks due to inconsistent structure, unclear
                            arguments, weak grammar, or incorrect referencing.
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
                Why Students Choose Our Advanced Essay proofreading and editing Services UK
            </h2>
            <p>When students request essay editing services UK, they often face tight deadlines, pressure from multiple
                assignments, or difficulty presenting arguments clearly. Our editors take your draft and elevate it to a
                higher academic standard without changing your original ideas.</p>
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
        title="Assignment in Need of Guarantees for the Best  Essay Proofreading & Editing Service UK"
        subtitle="When students trust us with their academic work, we ensure they receive a service built on transparency, academic strength, and complete reliability. Our guarantees show exactly why we are recognised as one of the essay writing services UK for university-level submissions."
        :items="[
            [
                'icon' => 'fas fa-clock-o',
                'heading' => 'Original literature review essays crafted by Real Academic Experts',
                'paragraph' =>
                    'Every essay is produced by a human specialist - never by automated tools. Your paper is written through genuine academic reasoning, critical evaluation, and scholarly interpretation, ensuring the work reflects true intellectual depth suitable for UK universities.',
            ],

            [
                'icon' => 'fas fa-edit',
                'heading' => 'Verified Zero-Plagiarism Work With Authentic Reports',
                'paragraph' =>
                    'Your essay goes through advanced plagiarism screening before delivery. Once your document is confirmed original, we attach a detailed originality report to guarantee that your written critical analysis UK is unique, safe, and meets academic integrity standards.',
            ],

            [
                'icon' => 'fas fa-headset',
                'heading' => 'Subject-Matched Writers With Advanced UK Qualifications',
                'paragraph' =>
                    'Only writers holding UK-recognised Master’s or PhD degrees handle critical analysis projects. Your essay is developed by someone who understands theoretical frameworks, research standards, assessment criteria, and academic conventions specific to UK institutions.',
            ],

            [
                'icon' => 'fas fa-ban',
                'heading' => 'Constant Academic Support Available 24/7',
                'paragraph' =>
                    'Whether you need clarifications, updates, revisions, or new instructions, our support team remains available around the clock. You can reach us anytime - day or night - ensuring your work progresses smoothly right up to your deadline.',
            ],

            [
                'icon' => 'fas fa-line-chart',
                'heading' => 'Unlimited Adjustments Until the Essay Meets Your Expectations',
                'paragraph' =>
                    'We refine your essay for free until it fully aligns with your academic brief. From improved arguments to structural enhancements, we revise your document until you are completely satisfied with the final critical analysis.',
            ],

            [
                'icon' => 'fas fa-lock',
                'heading' => 'On-Time Delivery With Strong Refund Protection',
                'paragraph' =>
                    'Your deadlines are treated as a priority. Every order is delivered within the agreed timeframe. And if the service does not meet promised standards, you are protected by a clear refund policy designed to safeguard your investment and trust in our literature review essay writing services UK.',
            ],
        ]" />

    <section class="page-section testimonial-section-four">
        <div class="container">
            <h2 class="section-title">Connect with a UK-Verified Expert for Essay Editing and Formatting Services</h2>

            <p class="section-description">
                Our platform links you with top-tier UK academic professionals specialising in essay formatting service UK, essay editing services UK, and essay proofreading service UK.
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

    <x-common-section.academic--writing-cards
        heading="Enhance Your Academic Writing with Expert Essay Support UK"
        paragraph="Beyond providing top-notch essay editing services UK, our platform offers a wide range of academic assistance tailored for students across UK universities. Each service is designed to improve clarity, structure, and overall quality, helping you achieve your best academic results."
        :cards="[
            [
                'heading' => 'Comparative Essay Help UK',
                'href' => '/uk/compare-and-contrast-essay-writing-help',
                'paragraph' =>
                    'Managing multiple concepts, texts, or theories can be challenging. Our comparative essay help UK ensures your work is structured logically, highlighting meaningful similarities and differences while delivering a coherent, well-evaluated argument suitable for high-level assessment.',
            ],
            [
                'heading' => 'Descriptive Essay Writing UK',
                'href' => '/descriptive-essay-help-uk',
                'paragraph' =>
                    'Our descriptive essay writing UK service transforms ideas into clear, vivid, and academically precise content. Using detailed observation and expressive language, we help you craft essays that are engaging, original, and perfectly aligned with UK coursework expectations.',
            ],

            [
                'heading' => 'Personal Statement Essay Writing UK',
                'href' => '/personal-statement-essay-help-uk',
                'paragraph' =>
                    'Creating a compelling personal statement can make all the difference in university applications. Our personal statement essay writing UK service offers expert guidance, polishing your content for clarity, persuasive storytelling, and adherence to academic standards.',
            ],

            [
                'heading' => 'Extended Essay Help UK',
                'href' => '/extended-essay-help-uk',
                'paragraph' =>
                    'Tackling complex topics or in-depth research is easier with our extended essay help UK. We provide structured support, helping you analyse, synthesise, and present ideas logically while integrating evidence effectively to meet rigorous UK academic criteria.',
            ],

            [
                'heading' => 'Argumentative Essay Writing UK',
                'href' => '/argumentative-essay-help-uk',
                'paragraph' =>
                    'Our argumentative essay writing UK service helps you construct evidence-backed, persuasive essays. We focus on presenting clear claims, logical reasoning, and balanced counterarguments, ensuring your arguments are academically robust and tailored for UK university standards.',
            ],


        ]" />

    @include('components.common-section.whatsapp')

    {{-- scrolling content --}}
    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">
                <div class="column">
                    <div class="content-box">
                        <h2>Mastering Essay Editing and Formatting Services UK for Top Academic Performance</h2>
                        <p class="content-description">
                            Producing an essay that meets UK university standards is more than just writing-it is a skill that requires careful attention to structure, coherence, and presentation. The submission of a well-edited and perfectly formatted essay demonstrates not only mastery of the subject but also the ability to communicate ideas clearly and professionally. Many students face challenges in aligning their work with strict academic conventions, which can affect grades despite strong research or content. Our <a href="/essay-writing-help-services">essay writing help uk</a> are designed to bridge this gap, providing comprehensive support that enhances clarity, precision, and overall quality.
                        </p>

                        <h3>Expert Essay Editing Tailored to UK Academic Standards</h3>
                        <p class="content-description">
                            Our service ensures your essay is thoroughly refined to meet the requirements of UK universities. Every sentence is scrutinised for grammar, spelling, and punctuation accuracy, ensuring that your writing is professional and coherent. Beyond technical corrections, we focus on enhancing clarity and logical flow, making arguments compelling and easy to follow. Using our best online essay editor UK, we refine each paragraph to ensure smooth transitions and a consistent tone throughout the document.
                        </p>

                        <h3>Comprehensive Essay Formatting for Professional Presentation</h3>
                        <p class="content-description">
                            Proper formatting is vital in academic assessment. Our essay formatting service UK guarantees that your essay adheres to the required structure, referencing style, and layout. This includes:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Correct headings, subheadings, and paragraph alignment
                            </li>
                            <li class="list-group-item">Proper spacing and margin settings
                            </li>
                            <li class="list-group-item">Accurate application of referencing styles (Harvard, APA, MLA, OSCOLA)
                            </li>
                            <li class="list-group-item">Consistent font and formatting throughout
                            </li>
                        </ul>
                        <p class="content-description">
                            A well-formatted essay not only looks professional but also ensures that the content is easily accessible and visually organised for assessors, significantly contributing to better grades.
                        </p>

                        <h3>Enhanced Argumentation and Analytical Structure</h3>
                        <p class="content-description">
                            Editing is not only about grammar and format-it also involves improving content quality. Our experts evaluate your essay’s argumentation and logical structure, identifying areas where reasoning can be strengthened. With our essay proofreading service UK, we ensure that every claim is supported with credible evidence, counterarguments are effectively addressed, and your ideas flow in a structured and persuasive manner. This level of editing enhances critical thinking and ensures your essay meets the high standards expected in UK academic writing.
                        </p>

                        <h3>Personalised Feedback and Academic Support</h3>
                        <p class="content-description">
                            We go beyond basic corrections to provide personalised guidance. Each essay receives detailed feedback on structure, coherence, and style. Students learn how to improve their work systematically, from refining thesis statements to enhancing paragraph cohesion. Our essay service equips students with insights to independently improve future submissions, fostering long-term academic growth.
                        </p>

                        <h3>Deadline-Focused and Reliable Academic Assistance</h3>
                        <p class="content-description">
                            Meeting submission deadlines is critical for academic success. Our <a
                                href="https://www.assignnmentinneed.com/">assignment help uk</a> for online essay proofreading and editing ensures prompt delivery without compromising academic quality. Students receive regular updates throughout the process, providing transparency and peace of mind. This reliable support allows learners to focus on research and study, confident that their work will meet professional and university standards.
                        </p>

                        <h3>Intellectual Integrity and Academic Excellence</h3>
                        <p class="content-description">
                            All edits are completed with the utmost commitment to academic honesty. Our help me essay editing services UK process guarantees 100% human-written content that is plagiarism-free and AI-free. The final essay represents an authentic scholarly effort while serving as a learning tool for the student. This ensures ethical academic support, preserving your reputation and promoting genuine skill development.
                        </p>
                        <p class="content-description">
                            By combining meticulous editing, professional formatting, and critical enhancement, our essay services help students achieve their full academic potential. With our support, essays are not only submission-ready but also exemplars of high-quality UK academic writing.
                        </p>
                    </div>


                </div>

                <div class="column">
                    <div class="content-box">
                        <h2>Ethical Standards, Strategic Support, and Long-Term Academic Benefits
                        </h2>
                        <p class="content-description">Choosing professional essay editing services UK is not only about immediate grade improvement-it is also a strategic investment in long-term academic success. Our service ensures that every edited essay not only meets the expectations of UK universities but also provides students with actionable learning opportunities to enhance their writing skills permanently.
                        </p>

                        <h3>Commitment to Academic Integrity</h3>
                        <p class="content-description">
                            We uphold the highest ethical standards in every project. Every essay undergoes a thorough check for originality, ensuring 100% plagiarism-free results. Our editors provide authentic human guidance, maintaining intellectual integrity while helping students polish their work. This approach guarantees that students submit essays that are both professionally refined and academically honest.
                        </p>

                        <h3>Security, Privacy, and Confidentiality</h3>
                        <p class="content-description">
                            All student information and submitted essays are handled with complete confidentiality. Our secure platform ensures that personal details, project instructions, and communication logs are fully protected. Students can confidently use our essay proofreading service UK without concerns about privacy breaches or data leaks.
                        </p>

                        <h3>Strategic Integration of Editing and Formatting</h3>
                        <p class="content-description">
                            Our editing process goes beyond grammar and layout. We integrate advanced academic strategies to improve the overall quality of every submission. This includes:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Structural refinement to ensure logical flow
                            </li>
                            <li class="list-group-item">Clarification of arguments and evidence
                            </li>
                            <li class="list-group-item">Correction of stylistic inconsistencies
                            </li>
                            <li class="list-group-item">Verification of all references and citations
                            </li>
                        </ul>
                        <p class="content-description">
                            Such comprehensive attention guarantees a polished, professional essay that adheres to UK university standards, maximising assessment outcomes.
                        </p>

                        <h3>Developing Independent Academic Skills</h3>
                        <p class="content-description">
                            Each essay edited through our best online essay editing service UK serves as a learning tool. Students gain insight into how to structure content, integrate evidence effectively, and write clearly for academic audiences. By observing the refinements, students can apply these techniques to future assignments, building confidence and improving performance over time.
                        </p>

                        <h3>Discipline-Specific Expertise</h3>
                        <p class="content-description">
                            Our editors have specialised knowledge across diverse academic disciplines. Whether the essay relates to social sciences, business, literature, or STEM subjects, our essay editing services UK ensure content accuracy and adherence to field-specific conventions. This deep expertise guarantees that your essay communicates authority and competence, essential for top marks.
                        </p>

                        <h3>Timely Support and Assistance</h3>
                        <p class="content-description">
                            Academic workloads can be overwhelming, and deadlines often add pressure. Our team offers dedicated support throughout the editing process, ensuring clarity, responsiveness, and guidance. Students can rely on our best online essay editor UK to resolve queries, incorporate feedback, and deliver a submission-ready document without delays.
                        </p>

                        <h3>Long-Term Academic Value</h3>
                        <p class="content-description">
                            The benefits of professional essay editing extend beyond a single assignment. By working closely with our specialists, students internalise effective writing strategies, strengthen critical thinking, and develop transferable academic skills that support success across multiple modules. Our <a
                                href="/assignment-writing-help-services">assignment writing help services uk</a> transform a standard essay into a strong academic model that students can reference in future work, encouraging long-term academic growth.
                        </p>
                        <p class="content-description">
                            Through careful attention to detail, ethical academic practices, and personalised guidance, our essay editing and formatting support equips students with the confidence, clarity, and expertise required to excel in UK higher education. Each edited essay reflects quality, originality, and structured academic support, empowering students to reach their full academic potential.
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
                <h2 class="section-title">Frequently Asked Questions - Essay Editing & Formatting Services UK</h2>

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