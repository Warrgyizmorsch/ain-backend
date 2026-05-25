@props([
    'title' => 'Our Guarantees That Make <span>Assignment Help</span> Stress-Free and Reliable',
    'intro' => '',   
    'features' => []
])

<section class="page-section why-choose-us">
    <div class="container">
        <h2 class="section-title">{!! $title !!}</h2>

        @if(!empty($intro))
            <p class="section-intro">
                {!! $intro !!}
            </p>
        @endif

        <div class="features-grid">
            @foreach($features as $index => $feature)
                <div class="feature-box" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="feature-icon">
                        <i class="{{ $feature['icon'] }}"></i>
                    </div>

                    <div class="heading-text">
                        @if (!empty($feature['link']))
                            <a href="{{ $feature['link'] }}">
                                <h3>{{ $feature['title'] }}</h3>
                            </a>
                        @else
                            <h3>{{ $feature['title'] }}</h3>
                        @endif
                    </div>

                    <div class="paragraph-text">
                        <p>{{ $feature['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>      
</section>

{{-- Mobile Slider View --}}
<div class="mobile-slider">
    <div class="slider-track">
        @foreach($features as $feature)
            <div class="feature-box card-mobile-view">
                <div class="feature-icon">
                    <i class="{{ $feature['icon'] }}"></i>
                </div>

                <div class="heading-text">
                    <a href="{{ $feature['link'] ?? '#' }}">
                        <h3>{{ $feature['title'] }}</h3>
                    </a>
                </div>

                <div class="paragraph-text">
                    <p>{{ $feature['description'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <button class="slider-btn prev">&#10094;</button>
    <button class="slider-btn next">&#10095;</button>
</div>

<div class="upload-btn">
    <a href="/upload-your-assignment">
        <button class="place-order-btn">Upload your Assignment</button>
    </a>
</div>

<style>
    .upload-btn { 
        text-align: center; 
        margin: -37px 0 20px; 
    }

    /* Hide mobile slider by default */
    .mobile-slider { 
        display: none; 
    }

    /* Desktop Grid Styles */
    .features-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
        gap: 30px; 
        max-width: 1150px; 
        margin: 1.5rem auto; 
    }

    /* Feature Box Styling (Updated to match reference) */
    .feature-box { 
        background: linear-gradient(135deg, #3F159A, #0E8FCE); 
        color: white; 
        padding: 15px; 
        border-radius: 10px; 
        text-align: center; 
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out; 
        margin-top: 20px; 
        animation: fadeInUp 1s ease-in-out;
    }

    .feature-box:hover { 
        transform: translateY(-10px); 
        box-shadow: 0px 15px 25px rgba(0, 0, 0, 0.2); 
    }
    
    /* Feature Icon Styling (Updated to be BIG like reference) */
    .feature-icon { 
        font-size: 30px; /* Makes icon big */
        margin-bottom: 10px;
        background: #fffafa; 
        color: rgb(30 0 90); 
        display: inline-block; /* Helps centering */
        padding: 15px; 
        border-radius: 100%; 
        width: 80px; 
        height: 80px; 
        position: relative; 
        top: -50px; 
        border: 11px double; 
    }

    /* Headings & Text */
    .feature-box h3 { 
        font-size: 20px; 
        font-weight: bold; 
        color: #fff; 
        margin-bottom: 10px;
    }
    
    .feature-box p { 
        font-size: 14px; 
    }
    
    .heading-text { 
        margin-top: -50px; 
    }
    
    .paragraph-text { 
        margin-top: 5px; 
    }

    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Mobile Responsive Styles */
    @media (max-width: 767px) {
        .features-grid { display: none !important; }

        .mobile-slider {
            display: block;
            position: relative;
            width: 100%;
            height: 320px;
            padding: 8px;
            overflow: hidden;
        }

        .slider-track {
            display: flex;
            transition: transform .3s ease-in-out;
            margin-top: 35px;
            width: 100%;
        }

        .mobile-slider .feature-box {
            flex: 0 0 calc(100% - 20px);
            margin: 0 10px;
            padding: 20px;
            box-sizing: border-box;
            background: linear-gradient(135deg, #3F159A, #0E8FCE);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,.6);
            color: #fff;
            border: none;
            padding: 12px 16px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            font-size: 18px;
        }

        .slider-btn.prev { left: 10px; }
        .slider-btn.next { right: 10px; }

        .feature-icon {
            font-size: 25px;
            height: 80px;
            width: 80px;
        }
    }
    
    @media (max-width: 480px) { 
        .place-order-btn { margin-top: 50px !important; } 
        .feature-box { padding: 15px; } 
        .feature-icon { font-size: 30px !important; } 
        .feature-box h3 { font-size: 16px; } 
        .feature-box p { font-size: 12px; } 
    }
</style>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });

    document.addEventListener("DOMContentLoaded", function () {
        const track = document.querySelector('.slider-track');
        const slides = document.querySelectorAll('.mobile-slider .feature-box');
        const next = document.querySelector('.slider-btn.next');
        const prev = document.querySelector('.slider-btn.prev');
        let index = 0;

        function update() {
            if (!slides.length) return;
            const width = slides[0].offsetWidth + 20; // 20px for margins
            track.style.transform = `translateX(-${width * index}px)`;
        }

        if(next && prev && track) {
            next.addEventListener('click', () => {
                if (index < slides.length - 1) { index++; update(); }
            });

            prev.addEventListener('click', () => {
                if (index > 0) { index--; update(); }
            });

            window.addEventListener('resize', update);
            setTimeout(update, 100);
        }
    });
</script>