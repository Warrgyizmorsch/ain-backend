<div class="review-container">
    <!-- Google -->
    <a href="https://www.google.com/search?kgmid=/g/11h5lkq603&hl=en-IN&q=Assignment+In+Need&shndl=30&shem=lcuae&source=sh/x/loc/osrp/m5/1&kgs=a5fd0582696972f7"
        style="color: black; text-decoration: none;">
        <div class="review-box google-review">
            <img src="/assets/media/layout/google_logo.png" alt="Google Logo" class="review-logo">
            <div>
                <p style="margin:0; font-size: 14px; font-weight: 600;">Google Review</p>
                <div class="star-rating-box">
                    <span class="star filled"><i class="fa fa-star"></i></span>
                    <span class="star filled"><i class="fa fa-star"></i></span>
                    <span class="star filled"><i class="fa fa-star"></i></span>
                    <span class="star filled"><i class="fa fa-star"></i></span>
                    <span class="star half"><i class="fa fa-star-half-o"></i></span>
                </div>
            </div>
            <span class="rating-number">4.4/5</span>
        </div>
    </a>

    <!-- Assignment In Need -->
    <a href="/review" style="color: black; text-decoration: none;">
        <div class="review-box ain-review">
            <img src="/assets/media/layout/assignment_logo.webp" alt="Assignment In Need Logo" class="review-logo">
            <div>
                <p style="margin:0; font-size: 14px; font-weight: 600;">AIN Review</p>
                <div class="star-rating-box">
                    <span class="star filled"><i class="fa fa-star"></i></span>
                    <span class="star filled"><i class="fa fa-star"></i></span>
                    <span class="star filled"><i class="fa fa-star"></i></span>
                    <span class="star filled"><i class="fa fa-star"></i></span>
                    <span class="star half"><i class="fa fa-star-half-o"></i></span>
                </div>
            </div>
            <span class="rating-number">4.6/5</span>
        </div>
    </a>

    <!-- Trustpilot -->
    <a href="https://www.trustpilot.com/review/assignnmentinneed.com" style="color: black; text-decoration: none;">
        <div class="review-box trustpilot-review">
            <img src="/assets/media/layout/trustpilot_logo.svg" alt="Trustpilot Logo" class="review-logo">
            <div>
                <p style="margin:0; font-size: 14px; font-weight: 600;">Trustpilot</p>
                <div class="star-rating-box">
                    <span class="star filled"><i class="fa fa-star"></i></span>
                    <span class="star filled"><i class="fa fa-star"></i></span>
                    <span class="star filled"><i class="fa fa-star"></i></span>
                    <span class="star filled"><i class="fa fa-star"></i></span>
                    <span class="star half"><i class="fa fa-star-half-o"></i></span>
                </div>
            </div>
            <span class="rating-number">4.2/5</span>
        </div>
    </a>
</div>
<style>
  .Trusted-paragraph {
    margin-top: 1rem;
    font-size: 1rem;
    font-weight: 500;
  }

  /* container */
  .review-container{
    max-width: 1200px;
    margin: 16px auto 0;
    padding: 0 0.5rem 8px;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 12px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
  }

  /* card */
  .review-box{
    background: rgba(255,255,255,0.95);
    border: 1px solid rgba(15, 23, 42, 0.08);
    padding: 10px 12px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    scroll-snap-align: start;
    box-shadow: 0 10px 25px rgba(2, 6, 23, 0.08);
    transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    min-width: 240px;
  }

  .review-box:hover{
    transform: translateY(-2px);
    box-shadow: 0 16px 34px rgba(2, 6, 23, 0.12);
    border-color: rgba(15, 23, 42, 0.14);
  }

  /* logo */
  .review-logo{
    width: 38px;
    height: 38px;
    object-fit: contain;
    flex: 0 0 38px;
    margin: 0;
    transition: transform .18s ease;
  }
  .review-box:hover .review-logo{
    transform: scale(1.05);
  }

  /* middle text block */
  .review-box > div{
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 120px;
  }

  /* stars */
  .star-rating-box{
    display: flex;
    align-items: center;
    gap: 5px;
    flex-wrap: nowrap;
  }

  /* fontawesome safety */
  .fa{
    font-family: 'FontAwesome' !important;
  }

  .star{
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    border-radius: 5px;
    color: #fff;
    box-shadow: inset 0 -1px 0 rgba(0,0,0,0.12);
  }

  .star.half{
    background: #cbd5e1;
    color: #fff;
  }

  /* rating number */
  .rating-number{
    font-size: 1.25rem;
    font-weight: 800;
    margin-left: auto;
    padding-left: 6px;
    color: #0f172a;
    white-space: nowrap;
  }

  /* brand colors (same as yours, refined) */
  .trustpilot-review .star.filled{ background: #00b67a; }
  .trustpilot-review .rating-number{ color: #00b67a; }

  .ain-review .star.filled{ background: #6B2FBF; }
  .ain-review .rating-number{ color: #6B2FBF; }

  .google-review .star.filled{ background: #4285F4; }
  .google-review .rating-number{ color: #4285F4; }

  /* Mobile */
  @media (max-width: 600px){
    .review-container{
      justify-content: flex-start;
      flex-wrap: nowrap;              /* ✅ mobile me nice horizontal cards */
      gap: 10px;
      padding-bottom: 10px;
    }

    .review-box{
      min-width: 220px;
      padding: 9px 10px;
      border-radius: 12px;
      box-shadow: 0 8px 18px rgba(2, 6, 23, 0.10);
    }

    .review-logo{
      width: 34px;
      height: 34px;
      flex-basis: 34px;
    }

    .rating-number{
      font-size: 1.15rem;
    }

    .star{
      width: 16px;
      height: 16px;
      font-size: 10px;
      border-radius: 4px;
    }
  }
</style>
