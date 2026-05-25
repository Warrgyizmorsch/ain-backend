<section class="page-section how-it-works">
  <div class="container">
    <!-- <h2 class="section-title">Our Multi-Step Quality Assurance Process</h2> -->

    <div class="row d-flex align-items-stretchr">
      <!-- LEFT SIDE: Steps -->
      <div class="col-md-6 steps-columns">
        <h2 class="section-title">Why Choose <span>Assignment in Need</span></h2>
        <div class="step">
          <!-- <h3><span class="step-number">1. </span> Subject-Matter Experts:</h3> -->
          <p>⮞ Pick your own expert based on subject and style that fits you best</p>
        </div>

        <div class="step">
          <!-- <h3><span class="step-number">2. </span> Expert Feedback Review:</h3> -->
          <p>⮞ Talk directly with your expert to share feedback or ask for updates anytime</p>
        </div>

        <div id="more-steps" class="collapsed-steps">
          <div class="step">
            <!-- <h3><span class="step-number">3. </span> Plagiarism Guidance:</h3> -->
            <p>⮞ Get help tailored to your university’s rules and format so your work always meets expectations</p>
          </div>
          <div class="step">
            <!-- <h3><span class="step-number">4. </span> Grading Standards:</h3> -->
            <p>⮞ Work with highly qualified experts, including PhD holders from top universities</p>
          </div>
          <div class="step">
            <!-- <h3><span class="step-number">5. </span> Unlimited Guidance Refinements:</h3> -->
            <p>⮞ Get support for 200+ subjects across all academic levels, from essays to research</p>
          </div>
          <div class="step">
            <!-- <h3><span class="step-number">6. </span> 24/7 Expert Support:</h3> -->
            <p>⮞ Learn something new, even on common topics, with unique insights from experts</p>
          </div>
          <div class="step">
            <p>⮞ Improve your writing and thinking skills while getting assignment help</p>
          </div>
          <div class="step">
            <p>⮞ Understand tough concepts clearly through easy-to-follow guidance</p>
          </div>
          <div class="step">
            <p>⮞ Learn research skills, how to find and use reliable sources</p>
          </div>
          <div class="step">
            <p>⮞ Get help to structure your assignments step by step for clear and professional results</p>
          </div>
          <div class="step">
            <p>⮞ Use free learning resources and samples as examples to learn how top-quality work looks</p>
          </div>
          <div class="step">
            <p>⮞ Build long-term skills, not just finish one task, perfect for future success</p>
          </div>
        </div>

        <div id="toggle-wrapper">
          <button class="btn btn-primary btn-sm mt-3" id="toggle-btn" onclick="toggleContent()">Know More</button>
        </div>
      </div>

      
       <div class="image-container">
                <img src="/images/uk-assignment-help-benefits.webp" alt=" UK assignment help benefits and services">
            </div>
      
    </div>
  </div>
</section>

<script>
function toggleContent() {
  const moreSteps = document.getElementById("more-steps");
  const btn = document.getElementById("toggle-btn");

  moreSteps.classList.toggle("expanded-steps");
  moreSteps.classList.toggle("collapsed-steps");

  if (moreSteps.classList.contains("expanded-steps")) {
    btn.innerText = "Show Less";
    moreSteps.scrollIntoView({ behavior: 'smooth' });
  } else {
    btn.innerText = "Know More";
  }
}

// OPTIONAL: Replace thumbnail with YouTube iframe on click
function loadYoutube(element) {
  const videoId = element.getAttribute("data-video-id");
  const iframe = document.createElement("iframe");
  iframe.setAttribute("src", `https://www.youtube.com/embed/${videoId}?autoplay=1`);
  iframe.setAttribute("frameborder", "0");
  iframe.setAttribute("allow", "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture");
  iframe.setAttribute("allowfullscreen", "true");
  iframe.style.width = "100%";
  iframe.style.height = "100%";
  iframe.style.objectFit = "cover";

  element.innerHTML = "";
  element.appendChild(iframe);
}
</script>
  <style>
    .how-it-works {
      padding: 2rem 1.5rem;
      background: #fff;
    }
    .section-h{
      font-size: 30px;
      font-weight: 700;
      margin-bottom: 1rem;
      color: #111 !important;
      text-align: left;
      margin:auto;
      margin-bottom: 1rem;
      position: relative;
      animation: fadeInDown 1s ease-in-out;
    }

    .section-h::after {
    content: "";
    border-bottom: 1px solid #5e2ced;
    display: block;
    width: 25%;
    margin: 8px auto 0;
  }

   .section-h::before {
    content: "";
    border-bottom: 6px solid #5e2ced;
    display: block;
    width: 5%;
    position: absolute;
    bottom: -2px;
    left: 50%;
    transform: translateX(-50%);
  }
    .steps-columns {
      text-align: justify;
    }

    .step {
      margin-bottom: 10px;
    }

    .step-number {
      font-size: 20px;
      color: #5e2ced;
      font-weight: bold;
      display: inline-block;
    }

    .step h3 {
      font-size: 20px;
      color: #333;
    }

    .step p {
      font-size: 15px;
      color: #555;
    }

    #more-steps.collapsed-steps {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.5s ease-out;
    }

    #more-steps.expanded-steps {
      max-height: 800px;
      transition: max-height 0.6s ease-in;
    }

    #toggle-wrapper {
      text-align: center;
      margin-top: 10px;
    }

    @media (min-width: 768px) {
      #more-steps {
        max-height: none !important;
        overflow: visible !important;
        display: block !important;
      }

      #toggle-wrapper {
        display: none;
         
      }
  }
     .video-column { height: 100%; display: flex; flex-direction: column; justify-content: space-between; }


    .video-grids {
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
       gap: 20px; 
    }

    .video-boxs {
      position: relative;
      aspect-ratio: 16 / 9;
      overflow: hidden;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      flex: 1;
       min-height: 0;
    }

    .video-boxs img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .play-button {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 70px;
      height: 70px;
      background: rgba(0, 0, 0, 0.6);
      border-radius: 50%;
      border: 3px solid white;
    }

    .play-button::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-40%, -50%);
      border-style: solid;
      border-width: 15px 0 15px 25px;
      border-color: transparent transparent transparent white;
    }
    .video-scroll-mobile {
      display: flex;
      overflow-x: auto;
      gap: 15px;
      scroll-snap-type: x mandatory;
      -webkit-overflow-scrolling: touch;
      padding-bottom: 10px;
    }
    .video-scroll-mobile .video-boxs {
      min-width: 280px;
      flex-shrink: 0;
      scroll-snap-align: center;
    }
    @media (max-width: 768px) {
    .video-scroll-mobile .video-boxs {
        min-width: 220px;
        width: 220px;
        aspect-ratio: 16 / 9;
    }
    .section-h {
          font-size: 1.0rem;
          margin-bottom: 0.25rem;
    }
    .how-it-works {
        padding: 0.5rem 0.5rem;
        background: #fff;
    }
    .step p {
      font-size: 12px;
      margin-bottom: 0.25rem;
      
    }
    .step h3 {
      font-size: 16px;
      
    }
    #toggle-wrapper {
       
         margin-top: 2px
      }

}

  </style>