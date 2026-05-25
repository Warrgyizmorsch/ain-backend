@props([
    'expert' => [],
    'title' => null,
    'description' => null,
])

@php
    $title = filled(trim(strip_tags($title ?? '')))
        ? $title
        : 'Our Top Writers For <span>Assignment Help Services</span>';

    $description = filled(trim(strip_tags($description ?? '')))
        ? $description
        : 'Connect with our extensive network of <a href="/writers">3,000+ PhD-level professional writers</a> dedicated to delivering assignments for UK and international students that precisely follow your instructions and meet the highest academic standards.';
@endphp

<section class="page-section testimonial-section-four">
    <div class="container">

        {{-- Title --}}
        <h2 class="section-title text-center mb-2">
            {!! $title !!}
        </h2>

        {{-- Description --}}
        <p class="section-description text-center mb-4">
            {!! $description !!}
        </p>

        {{-- Carousel --}}
        <div class="hm4-testimonial-carousel owl-carousel owl-theme">

            @foreach ($expert as $item)
                @php
                    $reviews = json_decode($item->customer_review ?? '[]', true);
                    $total = count($reviews);
                    $avg = $total ? round(array_sum(array_column($reviews,'rating'))/$total,1) : 0;
                    $full = floor($avg);
                    $half = ($avg - $full) >= 0.5;
                @endphp

                <div class="item">
                    <div class="expert-card">

                        <img
                            src="{{ $item->image ?? '/assets/media/avatars/blank.png' }}"
                            alt="{{ $item->name }}"
                            class="expert-img"
                        >

                        <h3 class="expert-name">{{ $item->name }}</h3>

                        <div class="stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $full)
                                    <i class="fa fa-star"></i>
                                @elseif ($half && $i == $full + 1)
                                    <i class="fa fa-star-half-o"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>

                        <p class="expert-description">
                            {!! \Illuminate\Support\Str::limit($item->content, 220) !!}
                        </p>

                        <div class="buttons">
                            <a href="{{ url('/upload-your-assignment') }}" class="hire-btn">Hire Writer</a>
                            <a href="{{ url('writers/'.$item->slug) }}" class="about-btn">About Writer</a>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>

        {{-- Navigation --}}
        <div class="custom-nav-btn">
            <button class="custom-prev"><i class="fa fa-chevron-left"></i></button>
            <button class="custom-next"><i class="fa fa-chevron-right"></i></button>
        </div>

    </div>
</section>


    <style>
        .testimonial-section-four {
            max-width: 100%;
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
            margin: 0 auto;
            height: 100px;
            width: 100px !important;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .expert-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #4821c3;
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
                font-size: 0.7rem !important;
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
        }

        .custom-nav-btn {
            text-align: center;
            margin-top: 20px;
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
    .video-section {
        background: none !important;
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
    }

    .video-box {
        flex: 1;
        min-width: 300px;
        max-width: 550px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .video-box:hover {
        transform: scale(1.02);
    }

    .video-box iframe {
        width: 100%;
        height: 315px;
        border: none;
        display: block;
    }

    @media (max-width: 768px) {
        .video-box iframe {
            height: 250px;
        }
    }

    @media (max-width: 500px) {
        .video-box iframe {
            height: 200px;
        }
    }

    /* Add this to your <style> block */
.owl-carousel.owl-centered .owl-stage {
    margin: 0 auto !important;
    display: flex;
    justify-content: center;
}
</style>


<!-- Owl Carousel CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

<!-- Optional theme -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const $carousel = $('.hm4-testimonial-carousel');

        // Destroy existing carousel if needed (prevents duplication)
        if ($carousel.hasClass('owl-loaded')) {
            $carousel.trigger('destroy.owl.carousel');
            $carousel.removeClass('owl-loaded owl-carousel owl-theme').find('.owl-stage-outer').children().unwrap();
        }

        const totalItems = $carousel.find('.item').length;
        // NOTE: maxVisible here is just for your nav logic, 
        // the carousel handles the responsive resizing automatically.
        const maxVisible = 3; 

        // Initialize carousel
        $carousel.addClass('owl-carousel owl-theme').owlCarousel({
            loop: false,
            rewind: true,
            margin: 20,
            nav: false,
            autoplay: false,
            // Hook the centering function to these events
            onInitialized: checkCentering,
            onResized: checkCentering,
            responsive: {
                0: { items: 1 },
                600: { items: 2 },
                1024: { items: maxVisible }
            }
        });

        // Function to center items if there are few (e.g. 1 or 2 on desktop)
        function checkCentering(event) {
            // 'event.page.size' tells us how many items are visible on the current screen width
            var itemsPerPage = event.page.size; 
            var totalItems = event.item.count;
            
            // If actual items are less than or equal to visible slots, center them
            if (totalItems <= itemsPerPage) {
                $(event.target).addClass('owl-centered');
            } else {
                $(event.target).removeClass('owl-centered');
            }
        }

        // Show/hide custom nav based on item count
        if (totalItems > maxVisible) {
            $('.custom-next, .custom-prev').show();

            $('.custom-next').off('click').on('click', function () {
                $carousel.trigger('next.owl.carousel');
            });

            $('.custom-prev').off('click').on('click', function () {
                $carousel.trigger('prev.owl.carousel');
            });
        } else {
            $('.custom-next, .custom-prev').hide();
        }
    });
</script>