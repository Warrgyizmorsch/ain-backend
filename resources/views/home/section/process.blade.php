<style>
  /* ===== HERO STYLE BACKGROUND ===== */
  .process-section {
    position: relative;
    overflow: hidden;
    padding: 40px 0;

    background: linear-gradient(135deg, #e0e7ff 0%, #f3e8ff 50%, #fce7f3 100%);
  }

  /* animated radial gradient layer */
  .process-section::before {
    content: "";
    position: absolute;
    inset: -50%;
    background:
      radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.12), transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(236, 72, 153, 0.12), transparent 50%),
      radial-gradient(circle at 40% 20%, rgba(139, 92, 246, 0.12), transparent 50%);
    animation: processBgMove 20s ease-in-out infinite;
    z-index: 0;
    pointer-events: none;
  }

  @keyframes processBgMove {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(30px, -30px) rotate(3deg); }
    66% { transform: translate(-20px, 20px) rotate(-3deg); }
  }

  /* floating bubbles */
  .process-shape {
    position: absolute;
    opacity: 0.12;
    z-index: 0;
    pointer-events: none;
  }

  .process-shape.shape-1 {
    width: 260px;
    height: 260px;
    background: linear-gradient(135deg, #6366f1, #ec4899);
    border-radius: 50%;
    top: 10%;
    left: 5%;
    animation: processFloat 15s ease-in-out infinite;
  }

  .process-shape.shape-2 {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
    top: 60%;
    right: 8%;
    animation: processFloat 12s ease-in-out infinite reverse;
  }

  .process-shape.shape-3 {
    width: 150px;
    height: 150px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 50%;
    bottom: 10%;
    left: 15%;
    animation: processFloat 18s ease-in-out infinite;
  }

  @keyframes processFloat {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-30px) rotate(10deg); }
  }

  /* ===== Keep your content above background ===== */
  .process-container {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 18px;
  }

  .process-wrap {
    position: relative;
    margin-top: 36px;
  }

  /* ===== Steps Layout ===== */
  .process-steps {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 26px;
    position: relative;
    margin-bottom: 26px;
    align-items: stretch;
  }

  /* ===== Arrow Connectors (desktop) ===== */
  .process-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 44px;
    color: #3F159A;
    opacity: 0.35;
    animation: processArrowPulse 2s ease-in-out infinite;
    z-index: 0;
    user-select: none;
    pointer-events: none;
    line-height: 1;
  }

  .process-arrow.arrow-1 { left: 29%; }
  .process-arrow.arrow-2 { right: 33%; }

  @keyframes processArrowPulse {
    0%, 100% { opacity: 0.35; transform: translateY(-50%) translateX(0); }
    50% { opacity: 0.75; transform: translateY(-50%) translateX(10px); }
  }

  /* ===== Card + animated border glow (THIS WAS MISSING) ===== */
  .process-card {
    background: rgba(255, 255, 255, 0.94);
    backdrop-filter: blur(14px);
    border-radius: 22px;
    padding: 34px 24px 26px;
    text-align: center;
    border: 1px solid rgba(63, 21, 154, 0.12);
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
    z-index: 1;

    transition:
      transform 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275),
      box-shadow 0.35s ease,
      border-color 0.35s ease;

    /* entrance animation */
    animation: processFadeInUp 0.85s ease-out backwards;
    min-height: 260px;
  }

  /* animated border layer */
  .process-card::before {
    content: "";
    position: absolute;
    inset: -2px;
    background: linear-gradient(135deg, #3F159A, #0E8FCE, #3F159A);
    border-radius: 22px;
    z-index: -2;
    opacity: 0;
    transition: opacity 0.45s ease;
  }

  /* inner glass layer */
  .process-card::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0.94);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    z-index: -1;
  }

  .process-card:hover::before {
    opacity: 1;
    animation: processRotateBorder 3s linear infinite;
  }

  @keyframes processRotateBorder {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  .process-card:hover {
    transform: translateY(-12px) scale(1.03);
    border-color: rgba(14, 143, 206, 0.26);
    box-shadow: 0 20px 50px rgba(63, 21, 154, 0.18);
  }

  /* ===== Step circle (rotate on hover) ===== */
  .process-step {
    width: 74px;
    height: 74px;
    margin: 0 auto 16px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3F159A, #0E8FCE);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    font-size: 26px;
    box-shadow: 0 10px 26px rgba(63, 21, 154, 0.24);
    transition: transform 0.55s ease, box-shadow 0.55s ease;
    position: relative;
    z-index: 1;
  }

  .process-card:hover .process-step {
    transform: scale(1.15) rotate(360deg);
    box-shadow: 0 14px 36px rgba(14, 143, 206, 0.30);
  }

  .process-card h3 {
    background: linear-gradient(to bottom, #3F159A, #0E8FCE);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 10px;
    position: relative;
    z-index: 1;
  }

  .process-card p {
    font-size: 0.98rem;
    line-height: 1.55;
    color: #1f2937;
    margin: 0;
    position: relative;
    z-index: 1;
    font-weight: 500;
  }

  /* entrance animation */
  @keyframes processFadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* stagger entrance */
  .process-steps .process-card:nth-of-type(1) { animation-delay: 0.15s; }
  .process-steps .process-card:nth-of-type(2) { animation-delay: 0.35s; }
  .process-steps .process-card:nth-of-type(3) { animation-delay: 0.55s; }

  @media (max-width: 1024px) {
    .process-steps { grid-template-columns: 1fr; }
    .process-arrow { display: none; }

    .process-card {
      text-align: left;
      padding: 24px 18px 18px;
      min-height: unset;
    }
    .process-step {
      margin: 0 0 12px;
      width: 64px;
      height: 64px;
      font-size: 22px;
    }
    .process-card h3 { font-size: 18px; }
    .process-card p { font-size: 0.92rem; }
  }
</style>

<section class="process-section" id="process">
  <!-- Floating bubbles -->
  <div class="process-shape shape-1"></div>
  <div class="process-shape shape-2"></div>
  <div class="process-shape shape-3"></div>

  <div class="process-container">
    <h2 class="section-title">
      Our Simple Process for UK Assignment Writing
    </h2>

    <div class="process-wrap">
      <div class="process-steps">

        <div class="process-card">
          <div class="process-step">01</div>
          <h3>Share Your Requirements</h3>
          <p>
            Submit your assignment details by uploading your draft or explaining your requirements.
            Mention the subject, deadline, word count, and any specific university guidelines.
          </p>
        </div>

        <div class="process-arrow arrow-1">→</div>

        <div class="process-card">
          <div class="process-step">02</div>
          <h3>Get Expert Academic Support</h3>
          <p>
            Our academic experts review your requirements and start working on your assignment
            aligned with UK academic standards.
          </p>
        </div>

        <div class="process-arrow arrow-2">→</div>

        <div class="process-card">
          <div class="process-step">03</div>
          <h3>Review, Revise & Submit</h3>
          <p>
            Receive your completed assignment on time, request revisions if needed, and submit confidently.
          </p>
        </div>

      </div>
    </div>
  </div>
</section>
