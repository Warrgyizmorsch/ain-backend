@props([
    'section',   // ONLY this section (toa, popular_services, etc.)
    'sliderId'   // unique ID per instance
])

<section class="page-section academic-services">
    <div class="container">

        <h2 class="section-title">{!! $section['title'] !!}</h2>
        @if (!empty($section['description'] ?? null))
            <p class="section-description">{!! $section['description'] !!}</p>
        @endif

        <div class="services-slider-wrapper">
            <div class="owl-carousel services-slider-{{ $sliderId }} owl-theme m-auto">

                @foreach ($section['cards'] as $card)
                    <div class="service-item">
                         @if (!empty($card['url']))
                            <a href="{{ $card['url'] }}">
                                <h3>{{ $card['title'] }}</h3>
                            </a>
                        @else
                            <h3>{{ $card['title'] }}</h3>
                        @endif
                        <p>{!! $card['description'] !!}</p>
                    </div>
                @endforeach

            </div>

            <div class="custom-nav">
                <button class="custom-prev-{{ $sliderId }}">Prev</button>
                <button class="custom-next-{{ $sliderId }}">Next</button>
            </div>
        </div>

    </div>
</section>

@once
    <style>
        /* Wrapper */
        .services-slider-wrapper {
            position: relative;
            margin: 0 auto;
            max-width: 100%;
            padding: 10px 0 20px;
        }

        /* Service Item */
        .service-item {
            background: white;
            border-radius: 10px;
            border: 2px solid rgb(63, 21, 154);
            box-shadow: 0 4px 8px rgba(63, 21, 154, 0.2);
            padding: 20px;
            /* text-align: justify; */
            height: 350px;
            overflow-y: auto;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .service-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(94, 44, 237, 0.3);
        }

        /* Scrollbar (Firefox) */
        .service-item {
            scrollbar-width: thin;
            scrollbar-color: #5e2ced #f0f0f0;
        }

        /* Scrollbar (WebKit) */
        .service-item::-webkit-scrollbar {
            width: 8px;
        }

        .service-item::-webkit-scrollbar-track {
            background: #f0f0f0;
        }

        .service-item::-webkit-scrollbar-thumb {
            background-color: #5e2ced;
            border-radius: 10px;
            border: 1.5px solid #f0f0f0;
        }

        .service-item::-webkit-scrollbar-thumb:hover {
            background-color: #35189f;
        }

        /* Headings & Paragraphs */
        .service-item h3 {
            font-weight: bold;
            font-size: 20px;
            color: #5e2ced;
            margin-bottom: 10px;
        }

        .service-item p {
            text-align: left;
            font-weight: 500;
            font-size: 1rem;
        }

        /* Custom Nav Buttons */
        .custom-nav {
            text-align: center;
            margin-top: 20px;
        }

        .custom-nav button {
            background: linear-gradient(135deg, #5e2ced, #4821c3);
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s, transform 0.3s;
        }

        .custom-nav button:hover {
            background: linear-gradient(135deg, #4821c3, #35189f);
            transform: translateY(-2px);
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 15px 0 0 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* 2 columns */
            gap: 8px 12px;
        }

        .feature-list li {
            font-size: 14px;
            color: #333;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .feature-list i {
            color: #28a745;
            /* green check */
            margin-right: 6px;
            font-size: 14px;
        }

        /* Owl Carousel Nav Buttons – hide built-in if not used */
        .owl-nav {
            display: none !important;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .service-item {
                height: 330px;
            }
        }

        @media (max-width: 768px) {
            .service-item {
                height: 300px;
            }

            .service-item h3 {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .service-item {
                height: auto;
            }

            .service-item h3 {
                font-size: 16px;
            }

            .service-item p {
                font-size: 12px;
            }
        }
    </style>
@endonce

<script>
    $(document).ready(function () {
        let slider{{ $sliderId }} = $(".services-slider-{{ $sliderId }}").owlCarousel({
            loop: false,
            margin: 20,
            autoplay: true,
            nav: false,
            responsive: {
                0: { items: 1 },
                600: { items: 2 },
                1024: { items: 3 }
            }
        });

        $('.custom-next-{{ $sliderId }}').click(() => {
            slider{{ $sliderId }}.trigger('next.owl.carousel');
        });

        $('.custom-prev-{{ $sliderId }}').click(() => {
            slider{{ $sliderId }}.trigger('prev.owl.carousel');
        });
    });
</script>
