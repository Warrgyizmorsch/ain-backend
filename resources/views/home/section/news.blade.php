 <!-- <section class="news-section">
  <div class="news-container">
    <div class="news-header">
      <h2 class="section-title">In News </h2>
        <p class="reviews-subtitle">
            See how Assignment in Need is making an impact in the academic world
        </p>
    </div>

    <div class="news-carousel">   -->
      <!-- Arrows -->
      <!-- <button class="news-arrow news-prev" aria-label="Previous">&#10094;</button>
      <button class="news-arrow news-next" aria-label="Next">&#10095;</button> -->

      <!-- Track -->
      <!-- <div class="news-track" id="newsTrack"> -->
        <!-- Slide 1 -->
        <!-- <div class="news-slide">
          <div class="news-card">
            <div class="news-logo">
              <img src="{{ asset('images/news1.jfif') }}" alt="APN News">
            </div>
            <div class="news-divider"></div>
            <p><a href="https://www.apnnews.com/how-to-write-a-research-paper-that-contributes-to-your-field/" class="plain-link">How To Write A Research Paper That Contributes To Your Field</a></p>
          </div>
        </div> -->

        <!-- Slide 2 -->
        <!-- <div class="news-slide">
          <div class="news-card">
            <div class="news-logo">
              <img src="{{ asset('images/news2.jfif') }}" alt="Time Business News">
            </div>
            <div class="news-divider"></div>
            <p><a href="https://timebusinessnews.com/how-to-stay-focused-and-avoid-procrastination-in-college/" class="plain-link">How To Stay Focused And Avoid Procrastination In College</a> To Stay Focused And Avoid Procrastination In College</p>
          </div>
        </div> -->

        <!-- Slide 3 -->
        <!-- <div class="news-slide">
          <div class="news-card">
            <div class="news-logo">
              <img src="{{ asset('images/news3.jfif') }}" alt="DGM News">
            </div>
            <div class="news-divider"></div>
            <p><a href="https://dgmnews.com/posts/sustaining-focus-during-extended-study-sessions/" class="plain-link">Sustaining Focus During Extended Study Sessions</a>: Student Strategies</p>
          </div>
        </div> -->

        <!-- Add more slides as needed -->
        <!-- <div class="news-slide">
          <div class="news-card">
            <div class="news-logo">
              <img src="{{ asset('images/news4.jfif') }}" alt="Financial Express">
            </div>
            <div class="news-divider"></div>
            <p><a href="https://sciotocountydailynews.com/the-benefits-of-using-a-study-app-to-track-your-progress/" class="plain-link">The Benefits of Using a Study App to Track Your Progress</a></p>
          </div>
        </div>

        <div class="news-slide">
          <div class="news-card">
            <div class="news-logo">
              <img src="{{ asset('images/news6.jfif') }}" alt="DGM News">
            </div>
            <div class="news-divider"></div>
            <p><a href="https://businessnewsthisweek.com/education/your-privacy-matters-how-assignment-in-need-protects-your-data/" class="plain-link">Your Privacy Matters: How Assignment In Need Protects Your Data </a></p>
          </div>
        </div>

        <div class="news-slide">
          <div class="news-card">
            <div class="news-logo">
              <img src="{{ asset('images/news7.jfif') }}" alt="DGM News">
            </div>
            <div class="news-divider"></div>
            <p><a href="https://www.newsanyway.com/2025/08/27/complete-academic-support-in-one-place-the-assignment-in-need-package/" class="plain-link">Complete Academic Support in One Place: The Assignment in Need Package</a></p>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- Minimal JS (no libraries) --}}
  <script>
(function () {
  const track = document.getElementById('newsTrack');
  const prev  = document.querySelector('.news-prev');
  const next  = document.querySelector('.news-next');

  function cardWidth() {
    const slide = track.querySelector('.news-slide');
    if (!slide) return track.clientWidth;
    const gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 20;
    return slide.getBoundingClientRect().width + gap;
  }

  prev.addEventListener('click', () => {
    track.scrollBy({ left: -cardWidth(), behavior: 'smooth' });
  });

  next.addEventListener('click', () => {
    track.scrollBy({ left: cardWidth(), behavior: 'smooth' });
  }); -->

  <!-- // Optional: snap to nearest slide after manual scroll -->
  <!-- let scrollTimer;
  track.addEventListener('scroll', () => {
    clearTimeout(scrollTimer);
    scrollTimer = setTimeout(() => {
      const gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 20;
      const slide = track.querySelector('.news-slide');
      if (!slide) return;
      const cw = slide.getBoundingClientRect().width + gap;
      const index = Math.round(track.scrollLeft / cw);
      const target = index * cw;
      track.scrollTo({ left: target, behavior: 'smooth' });
    }, 120);
  });
})();
</script> -->

<!-- <style>
    
    .news-section{
     padding: 70px 0;
     background: #fafafa;
    
    }
    .news-container {
     max-width: 1200px;
     margin: 0 auto;
     padding: 0 20px; }

    .news-header { 
        text-align: center; 
        margin-bottom: 36px;
     }
    .news-header h2 { 
        font-size: 32px; 
        font-weight: 700; 
        color: #222; 
        margin: 0 0 10px; 
    }
    .news-header p { 
        font-size: 18px; 
        color: #666; 
        line-height: 1.5; 
    }
    .news-header span { 
        font-weight: 700; 
        color: #a57400; 
    }

    /* carousel shell */
    .news-carousel { 
        position: relative; 
    }
    .news-track {
        display: grid;
        grid-auto-flow: column;
        grid-auto-columns: calc((100% - 40px) / 3); /* 3 per view (desktop) */
        gap: 20px;
        overflow: hidden;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
    }

    /* responsive slides per view */
    @media (max-width: 900px) {
    .news-track { grid-auto-columns: calc((100% - 20px) / 2); } /* 2 per view */
    }
    @media (max-width: 600px) {
    .news-track { grid-auto-columns: 100%; } /* 1 per view */
    }

    /* each slide snaps */
    .news-slide { scroll-snap-align: start; }

    /* card */
    .news-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 14px;
        padding: 22px;
        text-align: center;
        height: 100%;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        display: flex; flex-direction: column; align-items: center;
    }

    /* logo area: equal height so logos align */
    .news-logo {
        height: 64px;
        display: flex; 
        align-items: center; 
        justify-content: center;
    }
    .news-logo img { 
        max-height: 44px; 
        max-width: 100%; 
        object-fit: contain; 
    }

    /* the line between logo and text */
    .news-divider {
        width: 80%;
        height: 1px;
        background: #cfcfcf;
        margin: 14px auto 12px;
    }

    /* text */
    .news-card p { 
        margin: 0; 
        color: #444; 
        font-size: 15px; 
        line-height: 1.45; 
    }

    /* arrows */
    .news-arrow {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 42px; height: 42px; border-radius: 50%;
        border: 1px solid #ddd; background: #fff;
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        display: grid; place-items: center; cursor: pointer; z-index: 5;
    }
    .news-prev { 
        left: -18px; 
    }
    .news-next { 
        right: -18px; 
    }
    .news-arrow:hover { 
        background: #f8f8f8; 
    }

    /* hide native scrollbar inside track (optional) */
    .news-track::-webkit-scrollbar {
         display: none; 
        }
    .news-track {
         -ms-overflow-style: none; 
         scrollbar-width: none;
        
    }
    .plain-link {
      color: #444;  
      text-decoration: none; /* removes underline */
      font-weight: inherit;  /* keeps same weight as surrounding text */
    }

    .plain-link:hover {
      text-decoration: underline; /* optional hover effect */
    }
          

</style>
</section> -->
