<section class="reviews-section">
  <div class="container">
    <h2 class="section-title">  <span>Trusted by Thousands</span> of Students   </h2>
    <p class="reviews-subtitle">
    Over 4.8★ average rating from 5000+ reviews across trusted platforms! Students trust Assignment In Need for reliable, fast, and high-quality assignment help.


    </p>

    <div class="reviews-content">
      <!-- Tabs -->
      <div class="review-tabs">
        <button class="tab-btn active" data-tab="allassignmenthelp">
          <img src="https://www.assignnmentinneed.com/images/icons/assignment_logo%202.png" alt="AssignmentInNeed Logo" />
        </button>
        <button class="tab-btn" data-tab="trustpilot">
          <img src="https://www.assignnmentinneed.com/images/icons/Trustpilot_Logo.png" alt="Trustpilot Logo" />
        </button>
        <button class="tab-btn" data-tab="sitejabber">
          <img src="https://www.assignnmentinneed.com/images/google.png" alt="Sitejabber Logo" />
        </button>
        <button class="tab-btn" data-tab="youtube">
          <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/YouTube_Logo_2017.svg" alt="YouTube Logo" />
        </button>
      </div>

      <!-- Review Display -->
      <div class="review-display">
        <!-- AllAssignmentHelp Reviews -->
        <div class="review-group active" id="allassignmenthelp">
          @foreach ($data['review'] as $review)
          <div class="review-card">
            <div class="review-card-header">
              <h3 class="subheading-h3">{{ $review->name }}</h3>
              <p>{{ $review->location }}</p>
            </div>
            <div class="stars">
              @for ($i = 1; $i <= 5; $i++)
                @if ($i <= $review->customer_rating)
                  <i class="fas fa-star" style="color: gold;"></i>
                @else
                  <i class="far fa-star" style="color: gold;"></i>
                @endif
              @endfor
            </div>
            <p class="review-text">{{ $review->description }}</p>
            <div class="review-details">
              <p>{{ \Carbon\Carbon::parse($review->submission_date)->format('F j, Y') }}</p>
              <p>{{ $review->deadline }}</p>
              <p>{{ $review->services_type }}</p>
            </div>
          </div>
          @endforeach
        </div>

        <!-- Trustpilot Reviews -->
        <div class="review-group" id="trustpilot">
          @for ($i = 1; $i <= 5; $i++)
          <div class="review-card">
            <img src="images/trust/review-{{ $i }}.jpg" alt="Trustpilot Review {{ $i }}" style="width: 100%;" />
          </div>
          @endfor
        </div>

        <!-- Sitejabber Reviews -->
        <div class="review-group" id="sitejabber">
          <div class="review-card modern-review">
            <div class="modern-review-header">
              <div class="review-avatar">A</div>
              <div>
                <h3 class="subheading-h3">Arshpreet Kaur</h3>
                <div class="stars">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
              </div>
              <span class="review-date">2 months ago</span>
            </div>
            <p class="review-text">You guys are the best. Thank you for your efforts. I really like your services and the way every staff member behaves 🥰.</p>
          </div>
        </div>

        <!-- YouTube Video and Text Review -->
       <!-- YouTube Reviews Section -->
<div class="review-group active" id="youtube">
  
  <!-- Review 1 -->
  <div class="youtube-review-wrapper">
    <div class="youtube-review-text">
      <h3 class="reviewer-name">Janissa</h3>
      <p class="reviewer-comment">
        "I'm truly in love with Assignment In Need. I've been using this service for almost two years and I cannot complain. 
        The staff are incredibly friendly, and the quality of the assignments — from resources to structure — is just amazing. 
        Presentations, reports, documentation — everything is top-notch. Thank you so much for all the support!"
      </p>
      <div class="reviewer-meta">
        <strong>— Dani</strong>
        <p>15 pages | 4 days | Bristol, UK</p>
      </div>
    </div>
    <div class="youtube-video">
      <iframe width="100%" height="315" src="https://www.youtube.com/embed/Mndzuh8f_Zo" 
        title="Janissa Review" frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
        allowfullscreen>
      </iframe>
    </div>
  </div>

  <!-- Review 2 -->
  <div class="youtube-review-wrapper">
    <div class="youtube-review-text">
      <h3 class="reviewer-name">Petrana</h3>
      <p class="reviewer-comment">
        "Hi, I'm Petrana. I want to say a huge thank you to Assignment In Need. 
        You helped me immensely with your high-quality service, ensuring I passed all my assignments on time with great results. 
        I highly recommend you to everyone. Thank you so much!"
      </p>
      <div class="reviewer-meta">
        <strong>— Petrana</strong>
        <p>8 pages | 5 days | Manchester, UK</p>
      </div>
    </div>
    <div class="youtube-video">
      <iframe width="100%" height="315" src="https://www.youtube.com/embed/deg5LjJ3Exc" 
        title="Petrana Review" frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
        allowfullscreen>
      </iframe>
    </div>
  </div>

  <!-- Review 3 -->
  <div class="youtube-review-wrapper">
    <div class="youtube-review-text">
      <h3 class="reviewer-name">Jules George</h3>
      <p class="reviewer-comment">
        "Hey everyone, I'm Jules George, studying in South Wales. 
        Assignment In Need helped me a lot during critical times. 
        If you ever need help, don't hesitate — contact them! 
        They will guide you through everything you need."
      </p>
      <div class="reviewer-meta">
        <strong>— Jules George</strong>
        <p>12 pages | 8 days | Birmingham, UK</p>
      </div>
    </div>
    <div class="youtube-video">
      <iframe width="100%" height="315" src="https://www.youtube.com/embed/UxneGvVjiZQ" 
        title="Jules George Review" frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
        allowfullscreen>
      </iframe>
    </div>
  </div>

  <!-- Review 4 -->
  <div class="youtube-review-wrapper">
    <div class="youtube-review-text">
      <h3 class="reviewer-name">Dani</h3>
      <p class="reviewer-comment">
        "Good evening, my name is Dani. For the past three years, Assignment In Need has helped me immensely. 
        Their service quality is exceptional, and the customer support team is always supportive and professional. 
        Without them, managing all my assignments on time would have been impossible. 
        Thank you so much for everything!"
      </p>
      <div class="reviewer-meta">
        <strong>— Dani</strong>
        <p>10 pages | 7 days | London, UK</p>
      </div>
    </div>
    <div class="youtube-video">
      <iframe width="100%" height="315" src="https://www.youtube.com/embed/4_llz5IpEdE" 
        title="Dani Review" frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
        allowfullscreen>
      </iframe>
    </div>
  </div>

</div>

      </div>
    </div>

    <!-- Navigation Arrows -->
    <div class="reviews-navigation">
      <button class="nav-btn" id="prev-btn">←</button>
      <button class="nav-btn" id="next-btn">→</button>
    </div>
  </div>
</section>

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<!-- CSS -->
<style>
.subheading-h3 {
  font-size: 20px;
  font-weight: bold;
  color: #333;
}

.reviews-section {
  background: #f3f7fc;
  padding: 60px 20px;
  text-align: center;
}

.section-title {
  font-size: 32px;
  font-weight: bold;
  margin-bottom: 10px;
  color: #333;
}

.reviews-subtitle {
  font-size: 16px;
  color: #666;
  margin-bottom: 40px;
}

.reviews-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.review-tabs {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
  margin-bottom: 30px;
  flex-wrap: nowrap;
  overflow-x: auto;
  width: 100%;
  padding-bottom: 10px;
}

.review-tabs::-webkit-scrollbar {
  display: none;
}

.tab-btn {
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 10px;
  border-radius: 10px;
  transition: 0.3s;
  flex: 0 0 auto;
}

.tab-btn.active {
  background: #fff;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  border: 1px solid #5e2ced;
}

.tab-btn img {
  width: 120px;
}

@media (max-width: 600px) {
  .tab-btn img {
    width: 80px;
  }
}

.review-display {
  flex: 1;
  min-width: 300px;
  position: relative;
  overflow: hidden;
}

.review-group {
  display: none;
  flex-direction: column;
  text-align: left;
}

.review-group.active {
  display: flex;
}

.review-card {
  background: #fff;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
  border: 2px solid blue;
}

.review-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.review-details {
  display: flex;
  justify-content: space-between;
  gap: 1rem;
  margin-top: 10px;
}

.review-text {
  font-size: 16px;
  color: #555;
}

.review-avatar {
  width: 50px;
  height: 50px;
  background: #5e2ced;
  color: white;
  font-weight: bold;
  font-size: 20px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modern-review-header {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 10px;
}

.review-date {
  margin-left: auto;
  font-size: 14px;
  color: #888;
}

.stars {
  color: #f7c948;
  font-size: 16px;
}

.reviews-navigation {
  margin-top: 30px;
}

.nav-btn {
  background: #fff;
  border: 2px solid #5e2ced;
  color: #5e2ced;
  font-size: 20px;
  font-weight: bold;
  padding: 8px 16px;
  margin: 0 10px;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}

.nav-btn:hover {
  background: #5e2ced;
  color: #fff;
}

/* YouTube review layout */
.youtube-review-wrapper {
  display: flex;
  flex-direction: column;
  gap: 20px;
  align-items: center;
}

.youtube-review-text {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  flex: 1;
}

.youtube-video iframe {
  border-radius: 12px;
  width: 100%;
}

.reviewer-name {
  font-size: 22px;
  color: #333;
  margin-bottom: 10px;
}

.reviewer-name span {
  font-size: 18px;
  color: #5e2ced;
}

.reviewer-comment {
  font-size: 16px;
  color: #555;
  margin-bottom: 15px;
}

.reviewer-meta {
  font-size: 14px;
  color: #777;
}

@media (min-width: 768px) {
  .youtube-review-wrapper {
    flex-direction: row;
    align-items: flex-start;
  }
  .youtube-review-text {
    order: 1;
  }
  .youtube-video {
    order: 2;
    flex: 1;
  }
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll(".tab-btn");
  const reviewGroups = document.querySelectorAll(".review-group");
  const prevBtn = document.getElementById("prev-btn");
  const nextBtn = document.getElementById("next-btn");

  let activeGroupIndex = 0;
  let activeReviewIndex = 0;

  function showGroup(index) {
    reviewGroups.forEach(group => group.classList.remove("active"));
    tabs.forEach(tab => tab.classList.remove("active"));
    reviewGroups[index].classList.add("active");
    tabs[index].classList.add("active");
    activeReviewIndex = 0;
    updateReviews();
  }

  function updateReviews() {
    const activeGroup = reviewGroups[activeGroupIndex];
    const reviews = activeGroup.querySelectorAll(".review-card, .youtube-review-wrapper");
    reviews.forEach((review, idx) => {
      review.style.display = idx === activeReviewIndex ? "block" : "none";
    });
  }

  tabs.forEach((tab, index) => {
    tab.addEventListener("click", () => {
      activeGroupIndex = index;
      showGroup(index);
    });
  });

  prevBtn.addEventListener("click", () => {
    const reviews = reviewGroups[activeGroupIndex].querySelectorAll(".review-card, .youtube-review-wrapper");
    activeReviewIndex = (activeReviewIndex - 1 + reviews.length) % reviews.length;
    updateReviews();
  });

  nextBtn.addEventListener("click", () => {
    const reviews = reviewGroups[activeGroupIndex].querySelectorAll(".review-card, .youtube-review-wrapper");
    activeReviewIndex = (activeReviewIndex + 1) % reviews.length;
    updateReviews();
  });

  showGroup(activeGroupIndex);
});
</script>
