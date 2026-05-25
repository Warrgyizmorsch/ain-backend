@extends('frontend-layouts.app')

@section('content')

    <section class="bg-hero">
        <div class="container-section">
            <div class="hero-content">
                <!-- Content Column -->
                <div class="content-column">
                    <h1 class="main-heading">
                        Masters Research Paper Help UK - Ensuring Academic Distinction and 100% Original Work
                    </h1>
                    <div class="content-paragraph">
                        <p>Achieve top grades with deeply researched, academically sound master's research papers tailored to meet the demanding standards of UK universities.</p>
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
                How Does Our Expert Online Master's Research Paper Help UK Work
            </h2>
            <p>We provide a secure, streamlined three-step process connecting you with the best master's research paper service UK, ensuring a smooth, stress-free experience.</p>
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

    <x-common-section.benifits-of-assignment-cards title="Our Commitment to Deliver the Best Master's Research Paper Service UK"
            subtitle="When you choose our experts, you’re investing in a service that prioritises academic integrity, originality, and exceptional quality. We provide the best master's research paper help writing UK, backed by guarantees designed to ensure your academic success."
        :items="[
            [
                'icon' => 'fas fa-clock-o',
                'heading' => 'Completely Human-Written and AI-Free Papers',
                'paragraph' =>
                    'We do not use AI-generated content. Every research paper is crafted entirely by experienced human writers, ensuring authentic critical thinking, comprehensive analysis, and true academic depth. When you request master\'s research paper help UK, you receive a paper developed with genuine scholarly expertise.',
            ],

            [
                'icon' => 'fas fa-edit',
                'heading' => 'Guaranteed Plagiarism-Free Work',
                'paragraph' =>
                    'All submissions are checked against extensive academic databases and are certified 100% plagiarism-free. This ensures that your Master\'s research paper is fully original and meets the highest standards of academic integrity in UK universities.',
            ],

            [
                'icon' => 'fas fa-headset',
                'heading' => 'Work by Qualified PhD and Master’s Professionals',
                'paragraph' =>
                    'Advanced research requires specialised knowledge. Our team consists exclusively of Master’s and PhD-qualified experts capable of handling complex methodologies, theoretical frameworks, and literature synthesis. When you say Help me masters research paper service UK, your project is handled by a true academic authority.',
            ],

            [
                'icon' => 'fas fa-ban',
                'heading' => 'Round-the-Clock Academic Assistance',
                'paragraph' =>
                    'Academic challenges don’t follow a schedule. Our support team is available 24/7 to provide guidance, updates, and answers to your queries whenever you need assistance.',
            ],

            [
                'icon' => 'fas fa-line-chart',
                'heading' => 'Free Revisions to Ensure Complete Satisfaction',
                'paragraph' =>
                    'Your satisfaction is our priority. We offer unlimited free revisions to ensure your Master\'s research paper aligns perfectly with your instructions, university standards, and personal expectations.',
            ],

            [
                'icon' => 'fas fa-lock',
                'heading' => 'On-Time Delivery with Refund Protection',
                'paragraph' =>
                    'We understand the importance of deadlines. Every paper is delivered on schedule, and our robust refund policy protects your investment if our service does not meet the guaranteed standards. This reliability is why students trust us for online master\'s research paper help UK.',
            ],
        ]" 
    />

    <section class="page-section testimonial-section-four">
        <div class="container">
            <h2 class="section-title">Meet Your UK-Verified Expert for Master's Research Paper Help Writing UK</h2>

            <p class="section-description">
                Our platform connects you with a carefully selected group of Master’s and PhD-qualified academic writers who specialise in producing high-quality Master's research papers.
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

    <x-common-section.academic--writing-cards  heading="Beyond Research: Explore Our Other Types of Master's Research Paper Help Service UK"
        paragraph="While we specialise in delivering high-level Master's research papers, our academic experts also assist with a wide range of university-level writing tasks required across UK institutions."
        :cards="[
            [
                'heading' => 'Argumentative Research Paper Writing UK',
                'href' => '',
                'paragraph' =>
                    'Get a well-argued, rigorously supported research paper that presents a strong thesis and defends it using credible academic sources. Our specialists craft persuasive, logically structured arguments that meet the expectations of UK marking criteria. Every paper offers balanced reasoning, strong counter-argument handling, and clear academic justification.',
            ],
            [
                'heading' => 'Analytical Research Paper Help UK',
                'href' => '',
                'paragraph' =>
                    'Perfect for scientific, social science, and technical coursework, this service breaks down complex subject matter into clear, structured analysis. We go beyond description, providing deep insights into processes, frameworks, and conceptual relationships-ideal for students seeking detailed analytical depth in their Master\'s research projects.',
            ],

            [
                'heading' => 'Reflective Research Paper Help UK',
                'href' => '',
                'paragraph' =>
                    'Transform your personal academic or professional experience into polished reflective research. Using established models like Gibbs’ and Schon’s frameworks, our writers turn raw insight into structured academic reflection. This ensures your reflective research paper aligns with the standards expected at the Master\'s level across the UK.',
            ],

            [
                'heading' => 'Comparative Research Paper Help UK',
                'href' => '',
                'paragraph' =>
                    'Struggling to evaluate two theories, texts, or research approaches? Our service delivers well-structured comparative research papers that highlight key similarities, differences, and implications. We ensure a coherent, academically synthesised conclusion that demonstrates advanced critical thought.',
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
                        <h2>Mastering the Art of Master's Research Paper Help Writing UK for Exceptional Academic Performance</h2>
                        <p class="content-description">
                            Producing a distinction-level Master's research paper is one of the toughest academic expectations placed on postgraduate students in UK universities. The depth of research, the precision of academic writing, and the analytical sophistication required often overwhelm students who are already managing professional responsibilities, deadlines, and the complexities of advanced scholarship. For this reason, our master's <a href="/masters-research-paper-help-uk">research paper help uk</a> writing UK is strategically designed to support you with targeted academic guidance that elevates your work to the highest level of excellence.
                            <br> Our service focuses on strengthening the intellectual foundation of your entire project. Every paper we assist with is structured to reflect the principles that UK markers look for, ensuring that your research is not only academically sound but also demonstrates advanced critical engagement. With experts who hold Master’s and PhD qualifications, we provide specialised support that covers every aspect of your research, from refining your topic to shaping your final discussions and recommendations.
                            <br> Students prefer our master's research paper help UK because we offer:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Specialised academic researchers familiar with UK marking criteria</li>
                            <li class="list-group-item">Precise structuring of chapters and sections</li>
                            <li class="list-group-item">Evidence-driven argumentation</li>
                            <li class="list-group-item">Accurate referencing across all citation styles</li>
                            <li class="list-group-item">Support in theoretical positioning, methodology, and data analysis</li>
                        </ul>
                        <p class="content-description">Our approach follows a systematic research-writing framework that helps students understand not just what to write, but how to build a coherent academic narrative. This method ensures that your final submission demonstrates intellectual maturity and high-level academic reasoning.</p>

                        <h3>Our Writing Framework Includes</h3>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Topic Mapping & Argument Positioning</b> <br>We begin by clarifying your research aim, identifying gaps in existing literature, and constructing a clear rationale for your study.</li>
                            <li class="list-group-item"><b>Comprehensive Literature Structuring</b> <br>Instead of listing sources, we create thematic connections and scholarly debates that provide a strong conceptual base.</li>
                            <li class="list-group-item"><b>Methodological Precision</b> <br>We help align your research questions with the appropriate methods, ensuring clarity, reliability, and academic legitimacy.</li>
                            <li class="list-group-item"><b>Critical Data Interpretation</b> <br>Whether qualitative or quantitative, we help transform raw data into meaningful insights that strengthen your argument.</li>
                            <li class="list-group-item"><b>Clear, Logical, and Academic Writing Style</b> <br>Every section is written to meet UK academic expectations of coherence, accuracy, and analytical sophistication.</li>
                        </ul>
                                                
                        <p class="content-description">
                            For students needing flexible support, our online master's research paper help UK offers 24/7 academic guidance through a secure digital platform. Whether you are stuck in the literature review, confused about your methodology, or struggling with analysis, our experts provide immediate, professional assistance.
                           <br> Through our guidance, you gain not just a strong paper but a long-term academic advantage. Each research project we support becomes a model that helps you learn advanced academic writing techniques for your future postgraduate work.
                        </p>
                    </div>

                    
                </div>

                <div class="column">
                    <div class="content-box">
                        <h2>Ethical Integrity, Research Accuracy & Strategic Support in Our Master's Research Paper Service UK</h2>
                        <p class ="content-description">
                            Choosing online master’s research paper help UK is a significant academic decision. You’re trusting a service not only to guide your writing but also to provide reliable <a href="/">assignment help uk</a> that upholds academic integrity, confidentiality, and high scholarly value. Our service is built on principles that protect your reputation, your grades, and your long-term learning goals.
                            <br> We prioritise complete originality. Every paper is 100% human-written, ensuring depth of analysis, conceptual accuracy, and scholarly reasoning that reflect genuine academic expertise. This commitment is reinforced through thorough plagiarism checks, guaranteeing your paper is fully original and submission-ready.
                        </p>
                        
                        <h2>Why Students Choose Our Best Master's Research Paper Service UK</h2>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Guaranteed authentic, plagiarism-free research</li>
                            <li class="list-group-item">Subject specialists with advanced degrees</li>
                            <li class="list-group-item">Full confidentiality and secured document handling</li>
                            <li class="list-group-item">Professional editing and quality assurance</li>
                            <li class="list-group-item">Accurate formatting and citation management</li>
                        </ul>
                        <p class ="content-description">To ensure exceptional submission quality, we follow a multi-stage refinement model:</p>
                        
                        <h3>Expert Research & Drafting</h3>
                        <p class ="content-description">Your assigned writer builds the research narrative chapter-by-chapter, ensuring clarity, coherence, and academic depth.</p>
                        
                        <h3>Independent Editing Review</h3>
                        <p class ="content-description">A secondary editor examines the entire research paper to refine:</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Sentence clarity</li>
                            <li class="list-group-item">Academic tone</li>
                            <li class="list-group-item">Chapter flow</li>
                            <li class="list-group-item">Referencing accuracy</li>
                            <li class="list-group-item">Structural alignment with UK academic norms</li>
                        </ul>
                        
                        <h3>Final Quality Assurance Check</h3>
                        <p class ="content-description">We verify that your paper aligns with:</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Your university’s guidelines</li>
                            <li class="list-group-item">Your supervisor's instructions</li>
                            <li class="list-group-item">Theoretical and methodological standards</li>
                            <li class="list-group-item">UK postgraduate writing expectations</li>
                        </ul>                                                
                        <p class ="content-description">This layered system ensures your paper meets the benchmark for distinction.</p>

                        <p class ="content-description">
                            Ethics are central to our service. We operate on a transparent academic support model that emphasises learning, not misuse. Through responsible <a href="/assignment-writing-help-services">assignment writing help uk</a>, your research paper functions as a detailed academic example that strengthens your understanding of scholarly writing, structure, theory application, and critical analysis, supporting long-term academic skill development.
                            <br>We also offer complete confidentiality for students who request help me masters research paper service UK. Your personal details, academic documents, and communications are protected through advanced security protocols. No information is ever shared with third parties, ensuring full privacy and academic integrity.
                            <br>Our goal extends beyond submission. We focus on building your confidence as a postgraduate researcher by providing expert guidance, structured academic support, and ethical practices that help you navigate complex university expectations and perform at the highest academic level.
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
                <h2 class="section-title">Frequently Asked Questions for masters-research-paper</h2>

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