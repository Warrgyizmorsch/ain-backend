<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
  :root {
    --theme-purple: #6e40c9;
    --theme-blue: #007fff;
    --text-dark: #1f2333;
    --text-muted: #5b6275;
    --soft-bg: #f6f2ff;
    --card-bg: #ffffff;
    --card-border: rgba(40, 127, 218, 0.18);
    --shadow: 0 5px 3px 1px rgba(0, 0, 0, .10);
  }

  /* match your trust section wrapper */
  .reviews-slider-section {
    background: #fbf7f6;
    padding: 30px 0;
  }

  .reviews-slider-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0;
    text-align: left;
  }

  /* Swiper wrapper */
  .reviews-swiper {
    position: relative;
    padding: 18px 6px 24px;
  }

  .swiper.reviewsSwiper {
    padding: 10px !important;
  }

  .reviews-slide {
    height: auto;
  }

  /* Card UI (same look as screenshot + your theme touch) */
  .reviews-card {
    background: #fff;
    border-radius: 22px;
    padding: 26px 26px 24px;
    border: 1px solid rgba(15, 27, 61, 0.06);
    box-shadow: var(--shadow);
    height: 100%;
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
  }

  .reviews-card:hover {
    border-color: rgba(110, 64, 201, 0.18);
  }

  /* Header row (avatar + name/meta) */
  .reviews-head {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 15px;
  }

  .reviews-avatar {
    width: 44px;
    height: 44px;
    border-radius: 999px;
    overflow: hidden;
    flex: 0 0 44px;
    background: #e9edf7;
    border: 2px solid rgba(110, 64, 201, 0.20);
  }

  .reviews-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .reviews-name {
    font-size: 20px;
    font-weight: 600;
    color: #0f1b3d;
    margin: 0;
    line-height: 1.1;
  }

  .reviews-meta {
    margin: 3px 0 0;
    font-size: 12px;
    color: #6b7280;
    line-height: 1.1;
    font-weight: 400;
  }

  .reviews-text {
    font-size: .9rem !important;
    line-height: 1.5;
    color: #333;
    text-align: justify;
    margin-bottom: 15px;
  }

  /* Nav buttons (same as your trust slider) */
  .reviews-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 46px;
    height: 46px;
    border-radius: 50%;
    border: 1px solid rgba(0, 0, 0, 0.12);
    background: rgba(255, 255, 255, 0.85);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.10);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: .2s ease;
    user-select: none;
  }

  .reviews-nav:hover {
    transform: translateY(-50%) scale(1.05);
    border-color: rgba(110, 64, 201, .35);
  }

  .reviews-prev {
    left: -10px;
  }

  .reviews-next {
    right: -10px;
  }

  .reviews-nav.swiper-button-disabled {
    opacity: .45;
    cursor: not-allowed;
    transform: translateY(-50%);
  }

  .reviews-nav span {
    font-size: 26px;
    line-height: 1;
    color: #58607a;
    user-select: none;
  }

  /* Responsive tweaks */
  @media (max-width:1024px) {
    .reviews-text {
      font-size: 18px;
    }

    .reviews-prev {
      left: -6px;
    }

    .reviews-next {
      right: -6px;
    }
  }

  @media (max-width:640px) {
    .reviews-text {
      font-size: 17px;
    }

    .reviews-nav {
      width: 42px;
      height: 42px;
    }
  }
</style>

<section class="reviews-slider-section" id="student-reviews">
  <div class="reviews-slider-wrap">

    <h2 class="section-title">What UK Students Say About Our Services</h2>

    @php
      $studentReviews = [
        [
          'name' => 'Liam S.',
          'meta' => 'Liverpool, Programming',
          'text' => 'The step-by-step guidance helped me organise my thoughts clearly. I felt confident submitting my assignment on time.',
          'image' => '/assets/media/layout/testimonial/testimonial1.webp',
        ],
        [
          'name' => 'Emma W.',
          'meta' => 'Oxford, Law',
          'text' => 'The personalised feedback was excellent-it taught me how to analyse my work rather than just correct mistakes critically.',
          'image' => '/assets/media/layout/testimonial/testimonial2.webp',
        ],
        [
          'name' => 'Amelia C.',
          'meta' => 'Sheffield, Computer Science',
          'text' => 'I struggled with referencing styles before. Their guidance made it straightforward and saved me hours of stress.',
          'image' => '/assets/media/layout/testimonial/testimonial3.webp',
        ],
        [
          'name' => 'Chloe D.',
          'meta' => 'Bristol, Nursing',
          'text' => 'The suggestions were practical and easy to implement. It felt like having a personal tutor on hand.',
          'image' => '/assets/media/layout/testimonial/testimonial4.webp',
        ],
        [
          'name' => 'Oliver M.',
          'meta' => 'Glasgow, Engineering',
          'text' => 'I was worried about my dissertation writing skills. Their constructive feedback helped me improve structure and clarity.',
          'image' => '/assets/media/layout/testimonial/testimonial5.webp',
        ],
        [
          'name' => 'Sophia R.',
          'meta' => 'Edinburgh, Marketing',
          'text' => 'They didn’t just correct mistakes-they explained why. It really enhanced my understanding of academic writing',
          'image' => '/assets/media/layout/testimonial/testimonial6.webp',
        ],
      ];
    @endphp

    <div class="reviews-swiper">
      <div class="swiper reviewsSwiper">
        <div class="swiper-wrapper">
          @foreach($studentReviews as $r)
            <div class="swiper-slide reviews-slide">
              <div class="reviews-card">
                <div class="reviews-head">
                  <div class="reviews-avatar">
                    <img src="{{ $r['image'] }}" alt="{{ $r['name'] }}">
                  </div>
                  <div>
                    <p class="reviews-name">{{ $r['name'] }}</p>
                    <p class="reviews-meta">{{ $r['meta'] }}</p>
                  </div>
                </div>

                <p class="reviews-text">{{ $r['text'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Custom arrows (same style as trust slider) -->
      <div class="reviews-nav reviews-prev"><span>‹</span></div>
      <div class="reviews-nav reviews-next"><span>›</span></div>
    </div>

  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    new Swiper(".reviewsSwiper", {
      slidesPerView: 3,
      spaceBetween: 26,
      speed: 500,
      loop: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true
      },
      navigation: {
        nextEl: ".reviews-next",
        prevEl: ".reviews-prev",
      },
      breakpoints: {
        0: { slidesPerView: 1, spaceBetween: 16 },
        640: { slidesPerView: 1, spaceBetween: 18 },
        768: { slidesPerView: 2, spaceBetween: 22 },
        1024: { slidesPerView: 3, spaceBetween: 26 },
      }
    });
  });
</script>