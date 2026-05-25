<style>
  .stats-strip-section{
    padding: 50px 0;
  }

  .stats-strip-wrap{
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 14px;
  }

  .stats-strip{
    background: #fff;
    border: 1px solid rgba(17, 24, 39, 0.18);
    border-radius: 22px;
    box-shadow: 0px 2px 6px 0px rgba(0, 0, 0, 0.32);
    padding: 18px 10px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    align-items: center;
    overflow: hidden;
  }

  .stats-item{
    text-align: center;
    padding: 12px 16px;
    position: relative;
  }

  /* vertical dividers */
  .stats-item:not(:last-child)::after{
    content: "";
    position: absolute;
    right: 0;
    top: 14px;
    bottom: 14px;
    width: 1px;
    background: rgba(17, 24, 39, 0.25);
  }

  .stats-number{
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -0.5px;
    color: #3F159A;
    margin: 0 0 8px;
  }

  .stats-accent{
    color: #0E8FCE;
    font-weight: 800;
  }

  .stats-label{
    font-size: 0.9rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
  }

  /* small entrance animation */
  .stats-item{
    opacity: 0;
    transform: translateY(12px);
    animation: statsItemIn .6s ease-out forwards;
  }
  .stats-item:nth-child(1){ animation-delay: .10s; }
  .stats-item:nth-child(2){ animation-delay: .20s; }
  .stats-item:nth-child(3){ animation-delay: .30s; }
  .stats-item:nth-child(4){ animation-delay: .40s; }

  @keyframes statsItemIn{
    to{ opacity:1; transform: translateY(0); }
  }

  /* responsive */
  @media (max-width: 900px){
    .stats-strip{
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
      padding: 12px 10px;
    }
    .stats-item:not(:last-child)::after{ display:none; }
    .stats-item{
      border: 1px solid rgba(17, 24, 39, 0.10);
      border-radius: 16px;
    }
    .stats-number{ font-size: 38px; }
    .stats-label{ font-size: 16px; }
  }

  @media (max-width: 480px){
    .stats-strip{
      grid-template-columns: 1fr;
    }
    .stats-number{ font-size: 34px; }
  }

  /* accessibility */
  @media (prefers-reduced-motion: reduce){
    .stats-item{ animation: none !important; opacity: 1; transform: none; }
  }
</style>

<section class="stats-strip-section">
  <div class="stats-strip-wrap">
    <div class="stats-strip">

      <!-- ✅ Delivered Orders (count up to 45000+) -->
      <div class="stats-item">
        <div class="stats-number">
          <span class="stat-count" data-target="45000" data-suffix="+">0</span>
        </div>
        <p class="stats-label">Delivered Orders</p>
      </div>

      <!-- ✅ Writers (count up to 3000+) -->
      <div class="stats-item">
        <div class="stats-number">
          <span class="stat-count" data-target="3000" data-suffix="+">0</span>
        </div>
        <p class="stats-label">UK Academic Writers</p>
      </div>

      <!-- ✅ Returning Students (count up to 98%) -->
      <div class="stats-item">
        <div class="stats-number">
          <span class="stat-count" data-target="98" data-suffix="%">0</span>
        </div>
        <p class="stats-label">Returning Students</p>
      </div>

      <!-- ✅ Cases (count up to 9/10) -->
      <div class="stats-item">
        <div class="stats-number">
          <span class="stat-count" data-target="9" data-prefix="" data-suffix="/10">0</span>
        </div>
        <p class="stats-label">Cases of A+ Grades</p>
      </div>

    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const counters = document.querySelectorAll(".stat-count");
    if (!counters.length) return;

    const prefersReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    const formatNumber = (n) => {
      // 45000 -> 45,000
      return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };

    const animateCount = (el) => {
      const target = parseFloat(el.getAttribute("data-target") || "0");
      const prefix = el.getAttribute("data-prefix") || "";
      const suffix = el.getAttribute("data-suffix") || "";

      if (prefersReduced) {
        el.textContent = `${prefix}${formatNumber(target)}${suffix}`;
        return;
      }

      const duration = 1400; // ms
      const start = 0;
      const startTime = performance.now();

      const step = (now) => {
        const progress = Math.min((now - startTime) / duration, 1);

        // easeOutCubic
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = Math.round(start + (target - start) * eased);

        el.textContent = `${prefix}${formatNumber(current)}${suffix}`;

        if (progress < 1) requestAnimationFrame(step);
      };

      requestAnimationFrame(step);
    };

    // Run only when section is visible
    const observer = new IntersectionObserver(
      (entries, obs) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;

          const el = entry.target;
          if (el.dataset.animated === "1") return;

          el.dataset.animated = "1";
          animateCount(el);
        });
      },
      { threshold: 0.35 }
    );

    counters.forEach((el) => observer.observe(el));
  });
</script>
