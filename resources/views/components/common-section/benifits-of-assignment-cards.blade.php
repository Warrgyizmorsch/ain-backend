{{-- Benefits Of Assignment Help For Students In the UK --}}

<div>
    @props(['title' => '', 'subtitle' => '', 'items' => []])

    <section class="page-section why-choose-us">
        <div class="container">
            @if ($title)
                <h2 class="section-title">{{ $title }}</h2>
            @endif
            @if ($subtitle)
                <p class="section-description">{{ $subtitle }}</p>
            @endif
            <div class="main-cards">
                {{-- <p class="section-description">{{ }}</p> --}}
                @foreach ($items as $item)
                    <div class="feature-box" data-aos="fade-up" data-aos-delay="100">
                        <div class="feature-icon">
                            <i class="{{ $item['icon'] ?? 'fas fa-star' }}"></i>
                        </div>
                        <div class="heading-text">
                            <h3>{{ $item['heading'] ?? '' }}</h3>
                        </div>
                        <div class="paragraph-text">
                            <p>{{ $item['paragraph'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ........mobile view  -->
    <div class="mobile-slider">
        <div class="slider-track">
            @foreach ($items as $item)
                <div class="feature-box card-mobile-view">
                    <div class="feature-icon">
                        <i class="{{ $item['icon'] ?? 'fas fa-star' }}"></i>
                    </div>
                    <h3>{{ $item['heading'] ?? '' }}</h3>
                    <p>{{ $item['paragraph'] ?? '' }}</p>
                </div>
            @endforeach
        </div>


        <!-- Navigation buttons -->
        <button class="slider-btn prev">&#10094;</button>
        <button class="slider-btn next">&#10095;</button>
    </div>
    {{-- <!-- ................................ --> --}}

    <style>
        .main-cards {
            display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 40px;
        }


        /* ......................... */

        /* Hide mobile slider by default */

        .mobile-slider {
            display: none;

        }

        /* Mobile-only styles */
        @media (max-width: 768px) {



            .features-grid {
                display: none;
                /* Hide desktop grid on mobile */
            }

            .mobile-slider {
                display: block;
                position: relative;
                width: 100%;
                height: 400px;
                padding: 8px;
                overflow: hidden;
                /* Changed to hidden to prevent cards from overflowing */
            }

            .slider-track {
                margin-top: 35px;
                display: flex;
                transition: transform 0.3s ease-in-out;
                width: 100%;
                /* Ensure track takes full width */
            }

            .mobile-slider .feature-box {
                flex: 0 0 calc(100% - 20px);
                /* Account for margins */
                box-sizing: border-box;
                padding: 20px;
                background: linear-gradient(135deg, #3F159A, #0E8FCE);
                margin: 0 10px;
                /* 10px margin on each side */
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            }

            .slider-btn {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background-color: rgba(0, 0, 0, 0.6);
                color: white;
                border: none;
                padding: 12px 16px;
                font-size: 18px;
                border-radius: 50%;
                cursor: pointer;
                z-index: 10;
            }

            .slider-btn.prev {
                left: 0px;
            }

            .slider-btn.next {
                right: 0px;
                /* Adjusted to positive value to stay within bounds */
            }

            /* .feature-icon {
                max-width: 80px;
                height: 75px;
                font-size: 50px;
            } */

            /* .feature-icon .fa {
                font-size: 45px;
            } */
            .feature-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    background: #fffafa;
    color: rgb(30, 0, 90);
    border-radius: 100%;
    width: 80px;
    height: 80px;
    border: 6px double #3F159A;
    margin: 0 auto;
    margin-bottom: 10px;
    position: relative;
    top: -40px;
    box-sizing: border-box;
    flex-shrink: 0;
}

.feature-icon i {
    font-size: 32px;
    line-height: 1;
}

        }

        /* .............................. */
        .feature-box h3 {
            font-size: 1.5em !important;
            /* margin-bottom: 10px; */
            font-weight: bold;
            color: white;
        }

        .feature-box p {
            margin-top: 10px;
    padding: 0;
    font-size: 16px !important;
    color: #fff;
    line-height: 1.6;
        }

        .heading-text {
            margin-top: -50px;
        }

        .paragraph-text {
            margin-top: 5px;
            /* text-align: justify; */

        }

        /* General Section Styling */

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1150px;
            margin: 0 auto;
        }

        /* Feature Box */
        .feature-box {
            width: 100%;
            background: linear-gradient(135deg, #3F159A, #0E8FCE);
            color: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            animation: fadeInUp 1s ease-in-out;
            margin-top: 20px;
        }

        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0px 15px 25px rgba(0, 0, 0, 0.2);
        }

        /* Feature Icon */
        .feature-icon {
            font-size: 30px;
            margin-bottom: 10px;
            background: white;
            color: rgb(30 0 90);
            display: inline-block;
            padding: 15px;
            border-radius: 100%;
            position: relative;
            top: -50px;
            width: 80px;
            height: 80px;
            background: #fffafa;
            border: 11px double;
        }

        /* Animations */


        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1024px) {

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .main-cards {
        display: none !important;
    }
            .page-section {
                padding: 2rem 1rem;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .feature-box {
                padding: 20px;
            }

            .feature-icon {
                font-size: 25px;
                padding: 5px;
                height: 80px;
                width: 80px;
            }

            .feature-box h3 {
                font-size: 18px;
            }

            .feature-box p {
                font-size: 14px;
            }
        }

        @media (max-width: 768px) {
    .feature-icon {
        font-size: 25px;
        padding: 12px !important;
        height: 80px;
        width: 80px;
    }
    .page-section {
        margin-top: 45px !important;
    }
}


        @media (max-width: 480px) {


                .feature-box {
                    padding: 15px;
                }

                .feature-box h3 {
                    margin-top:-71px;
                    padding: 15px;
                }

                .feature-icon {
                    font-size: 34px !important;

                }
            }

            .feature-box h3 {
                font-size: 16px;
            }

            .feature-box p {
                font-size: 12px;
            }
        }
    </style>

    <!-- Include AOS (Animate on Scroll) for additional smooth animations -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>


    <!-- mobile view card javascript -->
    <script>
        const sliderTrack = document.querySelector('.slider-track');
        const slides = document.querySelectorAll('.mobile-slider .feature-box');
        const nextBtn = document.querySelector('.slider-btn.next');
        const prevBtn = document.querySelector('.slider-btn.prev');

        let currentIndex = 0;

        function updateSlider() {
            if (slides.length === 0) return; // Prevent errors if no slides
            const slideWidth = slides[0].offsetWidth + 20; // Include margin (10px left + 10px right)
            sliderTrack.style.transform = `translateX(-${slideWidth * currentIndex}px)`;
        }

        // Initialize slider
        updateSlider();

        // Navigation
        nextBtn.addEventListener('click', () => {
            if (currentIndex < slides.length - 1) {
                currentIndex++;
                updateSlider();
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
        });

        // Recalculate on resize
        window.addEventListener('resize', updateSlider);
    </script>
    <!-- ...................................................... -->

    </section>

</div>
