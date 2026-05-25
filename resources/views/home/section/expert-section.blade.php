<section class="testimonial-section-four">
  <!-- ✅ EXACT HERO SHAPES -->
  <div class="shape shape-1"></div>
  <div class="shape shape-2"></div>
  <div class="shape shape-3"></div>

  <div class="page-section" style="background: none; padding: 0; position: relative; z-index: 1;">
    <h2 class="section-title">
      Hire Highly Qualified Academic Writers for Your Assignments

    </h2>

    <p class="section-description">
     Choose from 500+ UK assignment writers with proven academic expertise across multiple disciplines. All work meets first-class academic standards with zero AI involvement.

    <div class="inner-container my-4">
      <div class="swiper hm4-testimonial-swiper">
        <div class="swiper-wrapper">

          @foreach ($data['expert'] as $expert)
            <div class="swiper-slide">
              <div class="testimonial-block-four item">
                <div class="expert-card">

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

                  <div class="stars" style="text-align: right;">
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
            </div>
          @endforeach

        </div>
      </div>

      <div class="custom-nav-btn">
        <button class="custom-prev"><i class="fa fa-chevron-left"></i></button>
        <button class="custom-next"><i class="fa fa-chevron-right"></i></button>
      </div>
    </div>
  </div>

  <style>
    /* =========================
      ✅ EXACT HERO BACKGROUND
    ========================== */
    .testimonial-section-four {
      padding: 30px 0px !important;
      position: relative;
      overflow: hidden;

      /* EXACT HERO bg */
      background: linear-gradient(
        135deg,
        #e0e7ff 0%,
        #f3e8ff 50%,
        #fce7f3 100%
      );
    }

    /* EXACT HERO moving radial layer */
    .testimonial-section-four::before {
      content: "";
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background:
        radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.10) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(236, 72, 153, 0.10) 0%, transparent 50%),
        radial-gradient(circle at 40% 20%, rgba(139, 92, 246, 0.10) 0%, transparent 50%);
      animation: backgroundShift 20s ease-in-out infinite;
      z-index: 0;
      pointer-events: none;
    }

    @keyframes backgroundShift {
      0%, 100% { transform: translate(0, 0) rotate(0deg); }
      33% { transform: translate(30px, -30px) rotate(3deg); }
      66% { transform: translate(-20px, 20px) rotate(-3deg); }
    }

    /* EXACT HERO floating shapes */
    .shape {
      position: absolute;
      opacity: 0.10;
      z-index: 0;
      pointer-events: none;
    }

    .shape-1 {
      width: 300px;
      height: 300px;
      background: linear-gradient(135deg, #6366f1, #ec4899);
      border-radius: 50%;
      top: 10%;
      left: 5%;
      animation: float 15s ease-in-out infinite;
    }

    .shape-2 {
      width: 200px;
      height: 200px;
      background: linear-gradient(135deg, #8b5cf6, #ec4899);
      border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
      top: 60%;
      right: 10%;
      animation: float 12s ease-in-out infinite reverse;
    }

    .shape-3 {
      width: 150px;
      height: 150px;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      border-radius: 50%;
      bottom: 15%;
      left: 15%;
      animation: float 18s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-30px) rotate(10deg); }
    }

    /* =========================
      Your Existing Styles (same)
    ========================== */
    .expert-box {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin: 10px 0px 20px;
    }

    .expert-box-label { font-size: 17px; font-weight: 700; }

    .expert-box-value {
      font-size: 17px;
      font-weight: 700;
      color: #0E8FCE;
    }

    .expert-box-item { width: 100%; }

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
      border-radius: 50%;
    }

    .image {
      width: 80px;
      height: 80px;
      overflow: hidden;
      border-radius: 50%;
      background: white;
      padding: 2px;
      margin: 0 auto;
      border: 3px solid #3F159A;
      box-shadow: 0 0 0 5px rgba(14, 143, 206, 0.18);
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
      overflow-y: auto;
    }

    .expert-description-wrapper::-webkit-scrollbar { width: 2px; }
    .expert-description-wrapper::-webkit-scrollbar-thumb { background-color: #ccc; border-radius: 10px; }
    .expert-description-wrapper::-webkit-scrollbar-track { background: transparent; }

    .hm4-testimonial-swiper {
      padding: 10px 8px 30px;
      position: relative;
      z-index: 1;
    }

    .hm4-testimonial-swiper .swiper-slide {
      height: auto;
      transition: transform .35s ease, opacity .35s ease, filter .35s ease;
      opacity: 0.55;
      transform: scale(0.88);
      filter: blur(1.5px);
    }

    .hm4-testimonial-swiper .swiper-slide.swiper-slide-active {
      opacity: 1;
      transform: scale(1.06);
      filter: blur(0);
      z-index: 5;
    }

    .hm4-testimonial-swiper .swiper-slide.swiper-slide-prev,
    .hm4-testimonial-swiper .swiper-slide.swiper-slide-next {
      opacity: 0.75;
      transform: scale(0.94);
      filter: blur(0.6px);
      z-index: 3;
    }

    .hm4-testimonial-swiper .swiper-slide.swiper-slide-active .expert-card {
      box-shadow:
        0 14px 40px rgba(14, 143, 206, 0.28),
        0 6px 18px rgba(63, 21, 154, 0.16);
      border-color: rgba(14, 143, 206, 0.45);
    }

    .expert-card {
      background: rgba(255, 255, 255, 0.96);
      padding: 20px;
      position: relative;
      margin: 10px;
      border-radius: 14px;
      border: 1px solid rgba(72, 33, 195, 0.25);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      text-align: center;
      transition: .3s;
      z-index: 1;
    }

    .expert-card:hover { box-shadow: 0 10px 22px rgba(0, 0, 0, 0.18); }

    .expert-name {
      font-size: 1.2rem;
      font-weight: 800;
      margin-bottom: 0px;
      color: #3F159A;
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
      background: #3F159A;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    .hire-btn:hover {
      transform: translateY(-3px);
      background: linear-gradient(135deg, #3F159A, #0E8FCE);
      box-shadow: 0 8px 18px rgba(14, 143, 206, 0.20);
      color: white;
    }

    .about-btn {
      background-color: white;
      border: 2px solid #3F159A;
      color: #3F159A;
      padding: 8px 15px;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    .about-btn:hover { background-color: #3F159A; color: white; }

    .custom-nav-btn button {
      background-color: #3F159A;
      color: white;
      border: none;
      height: 40px;
      width: 40px;
      font-size: 16px;
      border-radius: 10px;
      margin: 0 10px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      position: relative;
      z-index: 2;
    }

    .custom-nav-btn button:hover { background-color: #0E8FCE; }

    @media (max-width: 768px) {
      .hm4-testimonial-swiper .swiper-slide,
      .hm4-testimonial-swiper .swiper-slide.swiper-slide-active,
      .hm4-testimonial-swiper .swiper-slide.swiper-slide-prev,
      .hm4-testimonial-swiper .swiper-slide.swiper-slide-next {
        transform: scale(1);
        opacity: 1;
        filter: none;
      }
    }

    @media (max-width: 480px) {
      .expert-card { padding: 15px; margin: 0 auto 15px; }
      .hire-btn, .about-btn { font-size: .7rem; padding: 6px; }
      .testimonial-section-four { padding: 0px 0px !important; }
    }
  </style>
</section>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const swiper = new Swiper(".hm4-testimonial-swiper", {
      slidesPerView: 3,
      spaceBetween: 20,
      centeredSlides: true,
      loop: true,
      speed: 700,

      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },

      breakpoints: {
        0: { slidesPerView: 1, centeredSlides: false },
        768: { slidesPerView: 2, centeredSlides: true },
        1024: { slidesPerView: 3, centeredSlides: true },
      },
    });

    document.querySelector(".custom-next")?.addEventListener("click", () => swiper.slideNext());
    document.querySelector(".custom-prev")?.addEventListener("click", () => swiper.slidePrev());
  });
</script>
