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
    --shadow: 0 5px 3px 1px rgba(0, 0, 0, 0.10);
  }

  .trust-slider-section {
    background: #fbf7f6;
    padding: 30px 0;
  }

  .trust-slider-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 18px;
    text-align: center;
  }

  .trust-slider-subtitle {
    max-width: 980px;
    margin: 0 auto 34px;
    font-size: 16px;
    line-height: 1.65;
    color: #3e465b;
  }

  /* Swiper */
  .trust-swiper {
    position: relative;
    padding: 18px 6px 0;
  }

  .trust-slide {
    height: auto;
  }

  /* ✅ Card more premium + hover */
  .trust-card {
    background: rgba(255, 255, 255, 0.96);
    border-radius: 18px;
    border: 1px solid rgba(63, 21, 154, 0.14);
    box-shadow: 0 12px 26px rgba(16, 24, 40, 0.10);
    padding: 30px 24px 24px;
    text-align: center;
    position: relative;
    min-height: 270px;
    height: 100%;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    backdrop-filter: blur(6px);
  }

  /* soft premium glow */
  .trust-card:before {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 18px;
    background:
      radial-gradient(180px 180px at 12% 0%, rgba(63, 21, 154, 0.12), transparent 65%),
      radial-gradient(220px 220px at 100% 0%, rgba(14, 143, 206, 0.12), transparent 65%),
      radial-gradient(240px 240px at 50% 120%, rgba(99, 102, 241, 0.10), transparent 60%);
    pointer-events: none;
  }

  .trust-card:after {
    content: "";
    position: absolute;
    inset: 1px;
    border-radius: 17px;
    border: 1px solid rgba(209, 213, 219, 0.55);
    pointer-events: none;
  }

  .trust-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 40px rgba(16, 24, 40, 0.14);
    border-color: rgba(14, 143, 206, 0.28);
  }

  /* icon */
  .trust-icon {
    width: 58px;
    height: 58px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    position: relative;
    z-index: 1;

    background: linear-gradient(
      135deg,
      rgba(63, 21, 154, 0.12),
      rgba(14, 143, 206, 0.12)
    );
    border: 1px solid rgba(63, 21, 154, 0.10);
  }

  .trust-icon i {
    font-size: 34px;
    color: #3F159A;
    transition: all 0.25s ease;
  }

  .trust-card:hover .trust-icon i {
    color: #0E8FCE;
    transform: scale(1.12);
  }

  .trust-card h3 {
    background: linear-gradient(to bottom, #3F159A, #0E8FCE);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;

    font-size: 20px;
    font-weight: 800;
    line-height: 1.2;
    margin: 8px 0 10px;
    position: relative;
    z-index: 1;
  }

  .trust-card-description {
    margin: 0;
    font-size: 1.05rem;
    line-height: 1.3;
    color: #000;
    font-weight: 500;
    position: relative;
    z-index: 1;

    height: calc(1.3em * 3);
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 10px;
  }

  .trust-card-description::-webkit-scrollbar { width: 2px; }
  .trust-card-description::-webkit-scrollbar-track { background: #bdbdbd; }
  .trust-card-description::-webkit-scrollbar-thumb { background: #818589; border-radius: 999px; }
  .trust-card-description::-webkit-scrollbar-thumb:hover { background: gray; }

  /* Swiper arrows */
  .trust-nav {
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
    transition: 0.2s ease;
  }

  .trust-nav:hover {
    transform: translateY(-50%) scale(1.05);
    border-color: rgba(110, 64, 201, 0.35);
  }

  .trust-prev { left: -10px; }
  .trust-next { right: -10px; }

  .trust-nav.swiper-button-disabled {
    opacity: 0.45;
    cursor: not-allowed;
    transform: translateY(-50%);
  }

  .trust-nav span {
    font-size: 26px;
    line-height: 1;
    color: #58607a;
    user-select: none;
  }

  .trust-cta {
    display: inline-block;
    margin-top: 18px;
    padding: 8px 30px;
    border-radius: 10px;
    font-weight: 600;
    letter-spacing: 0.6px;
    background: #3F159A;
    color: #fff;
    text-decoration: none;
    transition: 0.2s ease;
  }

  .trust-cta:hover { transform: translateY(-2px); color: #fff; }

  @media (max-width: 1024px) {
    .trust-prev { left: -6px; }
    .trust-next { right: -6px; }
  }

  @media (max-width: 768px) {
    .trust-card h3 { font-size: 18px; }
    .trust-card-description {
      font-size: 0.85rem;
      line-height: 1.3;
      height: calc(1.3em * 3);
    }
  }

  .section-title {
    background: linear-gradient(to bottom, #3F159A, #0E8FCE);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;

    font-size: 2rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 10px;
  }

  @media (max-width: 768px) { .section-title { font-size: 2rem; } }
  @media (max-width: 600px) { .section-title { font-size: 1.4rem; } }

  @media (max-width: 640px) {
    .trust-nav { width: 42px; height: 42px; }
  }

  @media (prefers-reduced-motion: reduce){
    .trust-card, .trust-icon i { transition: none !important; }
  }
</style>

<section class="trust-slider-section">
  <div class="trust-slider-wrap">
    <h2 class="section-title">What Sets Our <span>Assignment Writing Services</span> in the UK Apart</h2>

    <p class="section-description">
      UK students often struggle with tight deadlines and academic pressure. Assignment In need provides reliable
      assignment help in the UK through expert guidance, original research, and strict adherence to university
      standards. With timely delivery, plagiarism-free work, and 24/7 student support, we help students stay
      confident throughout their academic journey.
    </p>

    <div class="trust-swiper">
      <div class="swiper trustSwiper">
        <div class="swiper-wrapper">

          <a href="/GuaranteedPolicy" class="swiper-slide trust-slide">
            <div class="trust-card">
              <div class="trust-icon"><i class="fa fa-shield"></i></div>
              <h3>100% Plagiarism-Free</h3>
              <p class="trust-card-description">
                Every assignment is written from scratch by subject experts. We ensure complete originality with proper
                plagiarism checks before delivery.
              </p>
            </div>
          </a>

          <a href="/upload-your-assignment" class="swiper-slide trust-slide">
            <div class="trust-card">
              <div class="trust-icon"><i class="fa fa-clock-o"></i></div>
              <h3>On-Time Delivery</h3>
              <p class="trust-card-description">
                We strictly follow your submission deadlines without compromising on quality. Urgent and last-minute
                assignments are handled with equal care and accuracy.
              </p>
            </div>
          </a>

          <a href="/GuaranteedPolicy" class="swiper-slide trust-slide">
            <div class="trust-card">
              <div class="trust-icon"><i class="fa fa-pencil-square-o"></i></div>
              <h3>Free Unlimited Revisions</h3>
              <p class="trust-card-description">
                We offer unlimited revisions until you are fully satisfied with the final work. Your feedback is
                carefully reviewed and implemented without extra charges. Your personal details and academic information
                are kept completely secure. We never share your data or assignment details with any third party.
              </p>
            </div>
          </a>

          <a href="/upload-your-assignment" class="swiper-slide trust-slide">
            <div class="trust-card">
              <div class="trust-icon"><i class="fa fa-refresh"></i></div>
              <h3>Money-Back Satisfaction</h3>
              <p class="trust-card-description">
                If the delivered work does not meet the agreed requirements, you are eligible for a refund. Our refund
                policy is transparent and student-friendly.
              </p>
            </div>
          </a>

          <a href="/contact-us" class="swiper-slide trust-slide">
            <div class="trust-card">
              <div class="trust-icon"><i class="fa fa-headphones"></i></div>
              <h3>24/7 Customer Support</h3>
              <p class="trust-card-description">
                Our support team is available round the clock to assist you at any time. Get instant updates,
                clarifications, and guidance whenever you need help.
              </p>
            </div>
          </a>

          <a href="/writers" class="swiper-slide trust-slide">
            <div class="trust-card">
              <div class="trust-icon"><i class="fa fa-graduation-cap"></i></div>
              <h3>Verified Expert Writers</h3>
              <p class="trust-card-description">
                Assignments are handled by qualified and experienced academic professionals. Each expert is verified and
                familiar with UK university standards.
              </p>
            </div>
          </a>

          <a href="/PrivacyPolicy" class="swiper-slide trust-slide">
            <div class="trust-card">
              <div class="trust-icon"><i class="fa fa-book"></i></div>
              <h3>In-Depth Research & Proper Referencing</h3>
              <p class="trust-card-description">
                We utilize credible academic sources to ensure that our content is well-researched. All assignments
                follow the required referencing style as per university guidelines.
              </p>
            </div>
          </a>

        </div>
      </div>

      <div class="trust-nav trust-prev"><span>‹</span></div>
      <div class="trust-nav trust-next"><span>›</span></div>
    </div>

    <a href="/upload-your-assignment" class="trust-cta">GET MORE DISCOUNTS</a>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const swiper = new Swiper(".trustSwiper", {
      slidesPerView: 3,
      spaceBetween: 26,
      speed: 700,
      loop: true,                 // ✅ loop on
      centeredSlides: false,

      autoplay: {                 // ✅ auto slider
        delay: 2500,
        disableOnInteraction: false,
        pauseOnMouseEnter: true
      },

      navigation: {
        nextEl: ".trust-next",
        prevEl: ".trust-prev",
      },

      breakpoints: {
        0: { slidesPerView: 1 },
        640: { slidesPerView: 1 },
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
      },
    });

    // keep autoplay running after manual nav
    document.querySelector(".trust-next")?.addEventListener("click", () => swiper.autoplay.start());
    document.querySelector(".trust-prev")?.addEventListener("click", () => swiper.autoplay.start());
  });
</script>
