@extends('frontend-layouts.app')

@section('content')

    <section class="bg-hero">
        <div class="container-section">
            <div class="hero-content">
                <!-- Content Column -->
                <div class="content-column">
                    <h1 class="main-heading">
                        Top Literature Review Essay Writing Service UK - Expert Evaluation with Authentic, Original Work
                    </h1>
                    <div class="content-paragraph">
                        <p>Scoring well in UK universities demands sharp analysis and a clear academic argument. Our
                            literature review essay writing service UK provides fully original, well-structured essays
                            tailored to British academic standards</p>
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
                How Our Online Literature Review Essay Help UK Works
            </h2>
            <p>Getting expert support from our literature review essay writing service UK is simple. We follow a quick,
                secure three-step process that connects you with a qualified writer and ensures your paper is delivered
                smoothly</p>
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
        title="Our Commitment to Quality in Literature Review Essay Writing Service UK"
        subtitle="Choosing our team means you receive more than a written paper-our guarantees protect your originality, grades, and academic confidence."
        :items="[
            [
                'icon' => 'fas fa-clock-o',
                'heading' => '100% Human-Written Work',
                'paragraph' =>
                    'Every essay is created manually by subject specialists. No AI, no automation-only real academic writing.',
            ],

            [
                'icon' => 'fas fa-edit',
                'heading' => 'Certified Plagiarism-Free Essays',
                'paragraph' =>
                    'Your written critical analysis UK is checked through reliable plagiarism tools to ensure complete originality.',
            ],

            [
                'icon' => 'fas fa-headset',
                'heading' => 'Qualified Master’s & PhD Writers',
                'paragraph' =>
                    'Experts with postgraduate qualifications handle your work, ensuring strong arguments and accurate academic depth.',
            ],

            [
                'icon' => 'fas fa-ban',
                'heading' => '24/7 Support Team',
                'paragraph' =>
                    'You can reach us anytime for updates, questions, or assistance throughout your order.',
            ],

            [
                'icon' => 'fas fa-line-chart',
                'heading' => 'Free Revisions',
                'paragraph' =>
                    'We revise your essay for free until it aligns perfectly with your academic instructions.',
            ],

            [
                'icon' => 'fas fa-lock',
                'heading' => 'Deadline Safety & Refund Assurance',
                'paragraph' =>
                    'We always deliver on time. If our literature review essay writing service UK does not meet the promised standard, you are protected by our refund policy.',
            ],
        ]" />

    <section class="page-section testimonial-section-four">
        <div class="container">
            <h2 class="section-title">Meet Your Expert for Literature Review Essay Writing UK</h2>

            <p class="section-description">
                Our platform links you with top UK-based academic writers who specialise in producing expertly crafted literature review essays, combining thorough research, deep subject knowledge, and precise structure to meet the rigorous standards of British universities.
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
        heading="Expanding Your Academic Success: Comprehensive Essay Writing Support UK"
        paragraph="Alongside literature review essays, our experts provide a range of UK-focused academic writing support to help students excel across disciplines."
        :cards="[
            [
                'heading' => 'Comparative Essay Writing UK',
                'href' => '/uk/compare-and-contrast-essay-writing-help',
                'paragraph' =>
                    'We provide clear, structured comparisons of theories, texts, or concepts, highlighting similarities, differences, and integrated conclusions for maximum academic impact.',
            ],
            [
                'heading' => 'Argumentative Essay Writing UK',
                'href' => '/argumentative-essay-help-uk',
                'paragraph' =>
                    'We craft persuasive, evidence-based arguments that meet UK university standards, addressing counterpoints effectively and presenting well-reasoned conclusions.',
            ],

            [
                'heading' => 'Analytical Essay Writing UK',
                'href' => '/analytical-essay-help-uk',
                'paragraph' =>
                    'Our service breaks down complex ideas and structures, offering clear, detailed analysis that demonstrates deep understanding and critical evaluation.',
            ],

            [
                'heading' => 'Reflective Essay Writing UK',
                'href' => '/reflective-essay-help-uk',
                'paragraph' =>
                    'We help transform personal experience and professional learning into academically structured, critically reflective essays suitable for UK courses.',
            ],


        ]" />

    @include('components.common-section.whatsapp')

    {{-- scrolling content --}}
    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">
                <div class="column">
                    <div class="content-box">
                        <h2>Mastering the Art of Literature Review Essay Writing UK for Top Grades and Distinction</h2>
                        <p class="content-description">
                            Submitting a high-quality literature review essay in UK universities is one of the most demanding academic tasks. It requires more than summarising information-it demands intellectual evaluation, critical thinking, and the ability to synthesise multiple scholarly perspectives. Students often face pressure from deadlines, heavy reading, and complex theoretical frameworks, which can make achieving top marks difficult.
                        </p>
                        <p class="content-description">
                            Our literature review <a href="/essay-writing-help-services">essay writing help uk</a> service provides expertly written essays that combine thorough research, structured argumentation, and high-level analysis. By choosing our service, you receive a coherent, original, and well-researched essay designed to meet the rigorous standards of British universities.
                        </p>

                        <h3>Why Choose Our Literature Review Essay Writing UK Service?</h3>
                        <p class="content-description">Our commitment to academic excellence is rooted in the expertise of our writers. Every essay benefits from:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>PhD and Master’s Level Writers – </b>Experts with advanced degrees and deep knowledge in your subject area.
                            </li>
                            <li class="list-group-item"><b>Alignment with UK Assessment Standards – </b>Structured to maximise scores in critical thinking, coherence, and evidence use.
                            </li>
                            <li class="list-group-item"><b>Accurate Academic Language – </b>Uses discipline-specific terminology and peer-reviewed sources to demonstrate mastery.
                            </li>
                        </ul>

                        <h3>How We Deliver Excellence</h3>
                        <p class="content-description">Our methodology ensures each essay is logically structured and academically rigorous:</p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Critical Evaluation: </b>We examine research methods, theoretical frameworks, and potential biases in sources.
                            </li>
                            <li class="list-group-item"><b>Innovative Synthesis: </b>Insights from multiple scholars are blended to create original perspectives.
                            </li>
                            <li class="list-group-item"><b>Logical Flow & Counter-Argument Integration: </b>Arguments are structured clearly, with counterpoints critically addressed.
                            </li>
                            <li class="list-group-item"><b>Evidence-Based Support: </b>All claims are backed by credible, peer-reviewed sources.
                            </li>
                        </ul>

                        <h3>Ethical Excellence, Security, and Quality Assurance</h3>
                        <p class="content-description">Choosing our online literature review essay help UK ensures your work is secure, original, and academically sound:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>100% Human-Written & Plagiarism-Free – </b>No AI content, fully authentic.
                            </li>
                            <li class="list-group-item"><b>Data Privacy & Confidentiality – </b>Your information is protected with strict security measures.
                            </li>
                            <li class="list-group-item"><b>Quality Assurance & Proofreading – </b>Multi-stage checks ensure flawless language, referencing, and formatting.
                            </li>
                            <li class="list-group-item"><b>Verified Sources & Referencing – </b>Properly cited, peer-reviewed sources strengthen credibility.
                            </li>
                        </ul>

                        <h3>Empowering Academic Growth</h3>
                        <p class="content-description">Our essays are designed not just for submission but as learning tools:</p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Boost Confidence: </b>Understand expert structuring and argumentation.
                            </li>
                            <li class="list-group-item"><b>Cover Skill Gaps: </b>Learn advanced essay structuring, literature review synthesis, and critical framing.
                            </li>
                            <li class="list-group-item"><b>Support Across Disciplines: </b>High-quality essays for all academic fields.
                            </li>
                        </ul>
                    </div>


                </div>

                <div class="column">
                    <div class="content-box">
                        <h2>Achieving Excellence in Literature Review Essay Writing UK</h2>
                        <p class="content-description">Producing a high-standard literature review essay in UK universities goes far beyond summarising information. It requires critical analysis, evaluation of multiple sources, and the ability to synthesise scholarly perspectives into a coherent argument. Tight deadlines, extensive reading, and complex academic frameworks often make this challenging for students.</p>
                        <p class="content-description">Our <a href="/assignment-writing-help-services">assignment writing help uk</a> for literature review essays delivers thoroughly researched, carefully structured work that meets rigorous university standards. Each essay reflects analytical clarity, originality, and strong subject understanding, supporting coursework, dissertations, and research projects while helping students strengthen their academic skills.</p>

                        <h3>Why Choose Our Literature Review Essay Service UK?</h3>
                        <p class="content-description">Our service is founded on a commitment to excellence, precision, and the highest academic standards. Each essay benefits from:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Highly Qualified Academic Writers – </b>Our team consists of PhD and Master’s degree holders with extensive experience in their respective fields. This ensures your essay is crafted by a specialist with deep subject knowledge.</li>
                            <li class="list-group-item"><b>UK University Assessment Expertise – </b>Every essay is written to comply with the marking criteria of UK universities, maximising the potential for high scores.</li>
                            <li class="list-group-item"><b>Professional Academic Language – </b>Essays are written using precise, discipline-specific terminology, supported by authoritative, peer-reviewed sources.</li>
                        </ul>

                        <h3>Our Proven Methodology for Exceptional Essays</h3>
                        <p class="content-description">We follow a structured and systematic process to ensure every essay is academically rigorous, logically organised, and well-researched:</p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Comprehensive Critical Evaluation: </b>We analyse each source for methodology, theoretical approach, and potential bias. This ensures a sophisticated understanding of the literature.
                            </li>
                            <li class="list-group-item"><b>Innovative Synthesis of Ideas: </b>Multiple perspectives are integrated to create unique insights that go beyond simple description, showcasing originality and intellectual depth.
                            </li>
                            <li class="list-group-item"><b>Logical Argumentation and Counterpoints: </b>Each essay is carefully structured, with arguments presented clearly and counter-arguments addressed critically to demonstrate analytical maturity.
                            </li>
                            <li class="list-group-item"><b>Evidence-Based Support: </b>All claims and conclusions are backed by reliable, peer-reviewed sources, ensuring credibility and academic rigour.
                            </li>
                        </ul>

                        <h3>Ethical Excellence, Security, and Quality Assurance</h3>
                        <p class="content-description">Selecting our online literature review essay help UK guarantees an ethical, secure, and academically robust experience. We prioritise integrity, transparency, and the protection of your academic reputation:</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Plagiarism-Free and Human-Written Content – </b>Every essay is original, crafted entirely by experts, and rigorously checked to ensure zero plagiarism.
                            </li>
                            <li class="list-group-item"><b>Confidentiality and Data Protection – </b>All personal and academic information is safeguarded with advanced encryption protocols, ensuring complete privacy.
                            </li>
                            <li class="list-group-item"><b>Multi-Stage Quality Assurance – </b>Independent editors review each essay for grammar, coherence, formatting, and referencing accuracy to guarantee the highest standards.
                            </li>
                            <li class="list-group-item"><b>Verified, Peer-Reviewed Sources – </b>Only credible and authoritative references are used, strengthening the reliability and academic value of your essay.
                            </li>
                        </ul>

                        <h3>Supporting Learning and Long-Term Academic Success</h3>
                        <p class="content-description">Our service is designed not only to provide ready-to-submit essays but also to serve as a <b>learning resource</b> for students:</p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Skill Development: </b>By studying the structure, argumentation, and referencing of our essays, students can improve their own critical writing skills.
                            </li>
                            <li class="list-group-item"><b>Future Academic Preparedness: </b>Our essays provide a model of academic excellence, enabling students to confidently tackle future <a href="https://www.assignnmentinneed.com/">assignment help uk</a> independently.
                            </li>
                            <li class="list-group-item"><b>Cross-Disciplinary Support: </b>Whether your subject is literature, social sciences, business, or STEM, our essays are tailored to meet the specific academic expectations of your discipline.
                            </li>
                            <li class="list-group-item"><b>Enhanced Research and Analysis Abilities: </b>Exposure to rigorous analysis and synthesis methods helps students develop advanced research skills applicable to dissertations and higher-level projects.
                            </li>
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
                <h2 class="section-title">Frequently Asked Questions - Literature Review Essay Writing UK</h2>

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