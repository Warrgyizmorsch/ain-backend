@props([
    'title' => '',
    'description' => '',

    'title1' => null, 'desc1' => null, 'link1' => null, 'features1' => [],
    'title2' => null, 'desc2' => null, 'link2' => null, 'features2' => [],
    'title3' => null, 'desc3' => null, 'link3' => null, 'features3' => [],
    'title4' => null, 'desc4' => null, 'link4' => null, 'features4' => [],
    'title5' => null, 'desc5' => null, 'link5' => null, 'features5' => [],
    'title6' => null, 'desc6' => null, 'link6' => null, 'features6' => [],
    'title7' => null, 'desc7' => null, 'link7' => null, 'features7' => [],
    'title8' => null, 'desc8' => null, 'link8' => null, 'features8' => [],
    'title9' => null, 'desc9' => null, 'link9' => null, 'features9' => [],
    'title10' => null, 'desc10' => null, 'link10' => null, 'features10' => [],
])

<section class="page-section academic-services">
    <div class="container">

        {{-- Section Title --}}
        @if ($title)
            <h2 class="section-title">{!! $title !!}</h2>
        @endif

        {{-- Section Description --}}
       @if ($description)
            <p class="section-description">{!! $description !!}</p>
        @endif

        <div class="services-slider-wrapper">
            <div class="owl-carousel services-slider owl-theme m-auto">

                @for ($i = 1; $i <= 8; $i++)
                    @php
                        $serviceTitle    = ${"title$i"};
                        $serviceDesc     = ${"desc$i"};
                        $serviceLink     = ${"link$i"};
                        $serviceFeatures = ${"features$i"};
                    @endphp

                    @if ($serviceTitle && $serviceDesc)
                        <div class="service-item">

                            @if ($serviceLink)
                                <a href="{{ $serviceLink }}">
                                    <h3>{{ $serviceTitle }}</h3>
                                </a>
                            @else
                                <h3>{{ $serviceTitle }}</h3>
                            @endif

                            <p>{!! $serviceDesc !!}</p>

                            @if (!empty($serviceFeatures))
                                <ul class="feature-list">
                                    @foreach ($serviceFeatures as $feature)
                                        <li>
                                            <i class="fas fa-check-circle"></i> {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                        </div>
                    @endif
                @endfor

            </div>
        </div>
        
        {{-- Custom Navigation Buttons --}}
        <div class="custom-nav">
        <button class="custom-prev">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="custom-next">
            <i class="fas fa-chevron-right"></i>
        </button>
</div>

    </div>
</section>


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

    /* Custom Nav Buttons Container */
    .custom-nav {
        position: relative;
        /* z-index: 9999; */
        text-align: center;
        margin-top: 20px;
        display: flex;
        justify-content: center;
        gap: 20px;
        padding-bottom: 20px;
        background-color: transparent; /* Ensure no background covers it */

    }

    /* Style for the Arrow Buttons */
    .custom-nav button {
        background: linear-gradient(135deg, #5e2ced, #4821c3);
        color: #fff;
        border: none;
        width: 50px;       /* Fixed width */
        height: 50px;      /* Fixed height */
        border-radius: 50%; /* Makes it a circle */
        cursor: pointer;
        font-size: 18px;   /* Icon size */
        display: flex !important;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        outline: none;
    }

    .custom-nav button:hover {
        background: linear-gradient(135deg, #4821c3, #35189f);
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(94, 44, 237, 0.4);
    }

    .custom-nav button i {
        pointer-events: none; /* Ensures clicking icon still clicks button */
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


<script>
    $(document).ready(function () {
        // Initialize Owl Carousel
        var serviceSlider = $(".services-slider").owlCarousel({
            loop: false,
            margin: 20,
            nav: false, // Disable default owl nav since using custom
            dots: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1024: {
                    items: 3
                }
            }
        });

        // Custom navigation
        $('.custom-next').click(function () {
            serviceSlider.trigger('next.owl.carousel');
        });

        $('.custom-prev').click(function () {
            serviceSlider.trigger('prev.owl.carousel');
        });
    });
</script>