<section class="how-it-works-lazy">
  <div class="auto-container">
    <h2 class="section-title">
      How Does Our Expert <span>Online Assignment</span> Help Works in UK?
    </h2>

    <div class="content-wrapper">
      <!-- Left Side: YouTube Lazy Video -->
      <div class="video-column">
        <div class="youtube-facade" >
          <img
            src="images/assignment-help-process-uk.webp"
            alt="How assignment help works in UK"
            loading="lazy"
          />
          <!-- <div class="play-button"></div> -->
        </div>
      </div>

      <!-- Right Side: All Steps -->
      <div class="steps-column">
        <div class="step">
          <h3 style="margin-bottom: 0;"><span class="step-number" style="margin: 0;">1. </span> Share Your Requirements</h3>
          <p>Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field</p>
        </div>

        <div class="step">
          <h3 style="margin: 0;"><span class="step-number" style="margin: 0;">2. </span> Make Payment</h3>
          <p>Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately.</p>
        </div>

        <div class="step">
          <h3 style="margin: 0;"><span class="step-number" style="margin: 0;">3. </span> Get Expert Help</h3>
          <p>Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download.</p>
        </div>

        <div class="step">
          <h3 style="margin: 0;"><span class="step-number" style="margin: 0;">4. </span> Receive Solution</h3>
          <p>Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period.</p>
        </div>

        <a href="https://www.assignnmentinneed.com/upload-your-assignment">
          <button class="place-order-btn">Get Our Assistance</button>
        </a>
      </div>
    </div>
  </div>
</section>

<style>
.how-it-works-lazy {
  padding: 2rem 1.5rem;
  background: #fff;
  text-align: center;
}

.content-wrapper {
  display: flex;
  gap: 20px;
  align-items: center;
  justify-content: space-around;
  flex-wrap: wrap;
}

/* Lazy Video Facade */
.video-column {
  flex: 1;
  min-width: 300px;
  max-width: 600px;
  text-align: center;
  position: relative;
}

.youtube-facade {
  position: relative;
  width: 100%;
  aspect-ratio: 16 / 9;
  border-radius: 12px;
  overflow: hidden;
  cursor: pointer;
}

.youtube-facade img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.play-button {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 64px;
  height: 64px;
  background: url('/assets/play-button.svg') no-repeat center center;
  background-size: contain;
  transform: translate(-50%, -50%);
  opacity: 0.9;
}

/* Steps section */
.steps-column {
  flex: 1;
  max-width: 600px;
  text-align: left;
}

.step {
  margin-bottom: 30px;
}

.step-number {
  font-size: 28px;
  color: #5e2ced;
  font-weight: bold;
  display: inline-block;
  margin-bottom: 8px;
}

.step h3 {
  font-size: 22px;
  margin: 8px 0;
  color: #333;
}

.step p {
  font-size: 16px;
  color: #555;
  line-height: 1.6;
}

/* Responsive */
@media (max-width: 768px) {
  .content-wrapper {
    flex-direction: column;
    align-items: stretch;
    gap: 20px;
  }
  .step-number { font-size: 24px; }
  .step h3 { font-size: 16px; }
  .step p { font-size: 12px; }
}

@media (max-width: 480px) {
  .step{
      margin-bottom: 0px;
  }
  .steps-column {
    max-height: 420px; /* adjust height as needed */
    /* overflow-y: auto; */
    padding-right: 10px; /* space for scrollbar */
  }

  /* Optional: smooth scrollbar styling */
  .steps-column::-webkit-scrollbar {
    width: 6px;
  }
  .steps-column::-webkit-scrollbar-thumb {
    background: #aaa;
    border-radius: 6px;
  } 

  .how-it-works-lazy {
    padding: 0 !important;
  }
  .how-it-works-lazy .auto-container {
    padding: 5px !important;
  }

  .content-wrapper {
    flex-direction: column;
    align-items: stretch;
    gap: 5px;
  }
  .step-number { font-size: 20px; }
  .step h3 { font-size: 16px; }
  .step p { 
    font-size: 11px; 
    text-align: justify;
  }

  /*.place-order-btn { width: 200px; font-size: 13px; padding: 8px; }*/
  .place-order-btn { 
    width: 200px; 
    font-size: 13px; 
    padding: 2px;
    
  }

  .place-order-btn:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
  }
}

</style>

<!-- <script>
function loadYoutube(el) {
  const videoId = el.getAttribute('data-video-id');
  const iframe = document.createElement('iframe');
  iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
  iframe.frameborder = "0";
  iframe.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
  iframe.allowFullscreen = true;
  iframe.style.width = '100%';
  iframe.style.height = '100%';
  el.innerHTML = '';
  el.appendChild(iframe);
}
</script> -->
