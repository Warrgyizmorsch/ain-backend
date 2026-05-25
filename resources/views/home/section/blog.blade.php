<section class="new-blog-section">
    <style>
        .new-blog-section {
            padding: 30px 18px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .new-blog-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 8px 0;
        }

        /* Card */
        .new-blog-card {
            background: #fff;
            border-radius: 25px;
            overflow: hidden
        }

        .new-blog-card .media {
            position: relative
        }

        .new-blog-card .media img {
            display: block;
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 25px;
        }

        .new-blog-card .date {
            position: absolute;
            right: 14px;
            bottom: -26px;
            background: #fff;
            font-size: 12px;
            color: #555
        }

        .new-blog-card .content {
            padding: 30px 18px 20px;
        }

        .new-blog-card h3 {
            font-size: 24px;
            line-height: 1.2;
            font-weight: 600;
            margin: 10px 0 12px
        }

        .new-blog-card p {
            color: #444;
            line-height: 1.55;
            margin: 0 0 16px
        }

        .new-blog-btn {
            display: inline-block;
            margin-top: 20px;
            background: #1C2C59;
            color: #fff;
            padding: 10px 18px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 400;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: none;
        }

        .new-blog-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.25);
            transform: skewX(-20deg);
            transition: 0.6s;
        }

        .new-blog-btn:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 8px 18px rgba(28, 44, 89, 0.6);
            background-position: right center;
        }

        .new-blog-btn:hover::before {
            left: 200%;
        }

        
        /* Swiper basics */
        .swiper {
            padding: 6px 6px 25px !important;
        }

        .swiper-slide {
            height: auto !important;
        }

        /* Nav */
        .blog-nav {
            display: flex;
            gap: 10px
        }

        .blog-nav button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid #e5e7eb;
            background: #fff;
            cursor: pointer
        }

        .blog-nav svg {
            width: 18px;
            height: 18px
        }

        .swiper-pagination-bullet {
            background: #cfd5ff;
            opacity: 1
        }

        .swiper-pagination-bullet-active {
            background: #0d3bff
        }

        /* Responsive */
        @media (max-width:480px) {
            .new-blog-card h3 {
                font-size: 22px
            }

            .new-blog-card .media img {
                height: 190px
            }

            .new-blog-section {
                padding: 20px 17px
            }
        }

        .new-blog-card h3,
        .new-blog-card p {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* limit to 2 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    <div class="blog-wrap">

        <h2 class="section-title">Academic Writing Tips & Guides</h2>
        <p class="section-description">Browse expert blogs on assignments, dissertations, university admissions, and effective academic writing.</p>

        <!-- Swiper -->
        <div class="swiper" id="blogSwiper">
            <div class="swiper-wrapper">
                @foreach ($data['blog'] as $blog)
                    <div class="swiper-slide">
                        <article class="new-blog-card">
                            <div class="media">
                                <img src="{{ asset($blog->images) }}" alt="{{ $blog->tittle }}">
                                <div class="date">{{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</div>
                            </div>
                            <div class="content">
                                <h3 title="{{ $blog->tittle }}">{{ $blog->tittle }}</h3>
                                <p title="{{ strip_tags($blog->content) }}">
                                    {{ Str::limit(strip_tags($blog->content), 150, '...') }}
                                </p>

                                <a href="{{ url('/blog/' . $blog->slug) }}" class="trust-cta">Read More</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <!-- <div class="new-blog-header">
            <div class="blog-nav">
                <button id="prevBtn" aria-label="Previous">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 6l-6 6 6 6" stroke="#111" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
                <button id="nextBtn" aria-label="Next">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M9 6l6 6-6 6" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div> -->

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper('#blogSwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: false
                },
                pagination: { el: '.swiper-pagination', clickable: true },
                navigation: { nextEl: '#nextBtn', prevEl: '#prevBtn' },
                breakpoints: {
                    640: { slidesPerView: 2, spaceBetween: 22 },
                    1024: { slidesPerView: 3, spaceBetween: 24 }
                }
            });
        });
    </script>
</section>