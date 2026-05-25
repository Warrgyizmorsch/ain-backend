@extends('frontend-layouts.app')

@section('content')

  <!-- style for header section  -->
  <style>
    .head-container {
      background: linear-gradient(135deg, rgba(214, 216, 255, 0.9), rgba(227, 195, 255, 0.9));
      border-radius: 0 0 1.5rem 1.5rem;
      padding: 2rem;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.09);
      animation: fadeIn 1s ease-in-out;
      text-align: center;
    }

    .header-content {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
    }

    .section-title {
      font-size: 1.8rem;
      font-weight: 700;
      color: #2c2c3c;
      margin: 0;
      animation: fadeInDown 1s ease-in-out;
    }

    .logo-img {
      position: absolute;
      right: 20px;
      width: 80px;
      height: 80px;
      mix-blend-mode: multiply;
      animation: fadeInUp 1s ease-in-out;
    }

    .primary-button {
      display: inline-block;
      padding: 0.75rem 1.5rem;
      background: linear-gradient(to right, #6e40c9, #007fff);
      color: white;
      font-weight: 600;
      border-radius: 8px;
      text-decoration: none;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      animation: fadeIn 1.2s ease-in-out;
    }

    .primary-button:hover {
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .icon-section {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 1.5rem;
      margin-top: 2rem;
      animation: fadeInUp 1.4s ease-in-out;
    }

    .icon-item {
      background: linear-gradient(white, white) padding-box,
        linear-gradient(135deg, #6e40c9, #007fff) border-box;
      border-left: 4px solid transparent;
      border-right: 4px solid transparent;
      padding: 1rem;
      border-radius: 12px;
      box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.09);
      transition: transform 0.3s ease;
      text-align: center;
    }

    .icon-item:hover {
      transform: scale(1.05);
      box-shadow: 2px 8px 18px rgba(0, 0, 0, 0.15);
    }

    .icon-item img {
      width: 50px;
      height: 50px;
      object-fit: contain;
      mix-blend-mode: multiply;
      margin-bottom: 0.5rem;
    }

    .icon-item p {
      font-size: 0.8rem;
      background: linear-gradient(135deg, #6e40c9, #007fff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-weight: 700;
      margin: 0;
    }


    /* Animations */
    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsive Tweaks */
    @media (max-width: 600px) {
      .header-content img {
        display: none;
      }

      .section-title {
        font-size: 1.4rem;
      }

      .logo-img {
        width: 60px;
        height: 60px;
      }

      .primary-button {
        font-size: 0.9rem;
        padding: 0.6rem 1.2rem;
      }

      .icon-item img {
        width: 35px;
        height: 35px;
      }
    }
  </style>

  <!-- Header Section HTML  -->
  <div class="head-container mt-6 px-4 py-6">
    <div class="header-content">
      <img src="assets/media/avatars/assignment_logo.png" alt="Assignment Logo" class="logo-img" />
      <h1 class="section-title">We Have Experts To Help You!</h1>
    </div>

    <a href="/free-samples" class="primary-button mt-4">Get 100% Original Assignments</a>

    <div class="icon-section mt-6">
      <div class="icon-item">
        <img src="/images2/certificate.png" alt="100% Original Document" />
        <p>100% Original</p>
      </div>
      <div class="icon-item">
        <img src="/images2/sketch-book.png" alt="AI Free Content" />
        <p>AI Free Content</p>
      </div>
      <div class="icon-item">
        <img src="/images2/personal-data.png" alt="Free CV" />
        <p>Free CV on Every Order</p>
      </div>
      <div class="icon-item">
        <img src="/images2/24-hours.png" alt="24/7 Availability" />
        <p>24×7 Available</p>
      </div>
      <div class="icon-item">
        <img src="/images2/role-model.png" alt="Experienced Writers" />
        <p>Experienced Writers</p>
      </div>
      <div class="icon-item">
        <img src="/images2/article.png" alt="Plagiarism Report" />
        <p>Plagiarism Report</p>
      </div>
    </div>
  </div>

  <x-common-section.services h1="How Do Our Best Expert <span>Assignment Writing Help</span> Services Work Worldwide?"
    step1="Submit Your Request"
    step1Content="Complete the 'Order Now' form, providing your details and all necessary instructions for your assignment to ensure personalized results."
    step2="Confirm Your Payment"
    step2Content="Pay an affordable price for your assignment using our safe and trusted payment gateway, guaranteeing full protection of your data and privacy."
    step3="Receive Your Assignment"
    step3Content="Our expert writers will deliver a well-researched, high-quality assignment before the deadline, helping you achieve top-notch academic results." />

  @include('components.common-section.promo-banner')

  <section class="page-section scrollables">
    <div class="scrollable-section">
      <div class="scrollable-container">
        <div class="column">
          <div class="content-box">
            <h2>Online Expert Assignment Writing Help Services for Students
            </h2>
            <p class="content-description">
              If you're looking for professional academic support, Assignment in Need is here to guide you with reliable
              assignment writing help tailored to your needs. We work with students across the globe and provide clear,
              structured, and high-quality academic assistance for essays, reports, research papers, and technical
              projects.
            </p>

            <p class="content-description">
              Our platform is recognised among the <a
                href="https://www.assignnmentinneed.com/best-online-assignment-writing-service"><b>best assignment writing
                  services</b></a> because we match every student with a
              subject-specialized writer who understands their academic requirements and delivers fully customized work.
            </p>

          </div>

          <div class="content-box">
            <h2>Who Are Our Expert Assignment Writers?
            </h2>
            <p class="content-description">
              Our strength lies in our team of expert writers. Each writer holds advanced academic qualifications and
              extensive experience in academic writing. We carefully select only the most knowledgeable, skilled, and
              committed assignment helpers to join our team.
            </p>

            <p class="content-description">
              What sets our <a href="https://www.assignnmentinneed.com/assignment-writing-help-services"><b>assignment
                  writing
                  help</b></a> because we match every student with a
              subject-specialized writer who understands their academic requirements and delivers fully customized work.
            </p>

          </div>

          <div class="content-box">
            <h2>How Our Expert Assignment Writers Work</h2>
            <p class="content-description">
              Our process ensures that every assignment is tailored to your needs:
            </p>
            <ul class="custom-ordered-list">
              <li class="list-group-item"><b>Understanding Your Requirements</b><br>We begin by discussing your topic,
                instructions, sources, and expectations. This helps us fully understand what you need before starting the
                writing process.</li>
              <li class="list-group-item"><b>Conducting In-Depth Research</b><br>Our writers gather information from
                credible academic sources, journals, and updated research to build a strong foundation for your
                assignment.</li>
              <li class="list-group-item"><b>Crafting Your Assignment</b><br>After researching, your writer structures
                your assignment clearly and logically. Whether it’s a report, essay, or technical project, every section
                is written to meet academic standards.</li>
              <li class="list-group-item"><b>Reviewing & Final Editing</b><br>Before delivery, your assignment goes
                through proofreading to ensure clarity, accuracy, and consistency. Revisions are provided to guarantee
                your satisfaction.</li>
            </ul>
          </div>

          <div class="content-box">
            <h2>How to Choose the Right Online Assignment Expert</h2>
            <p class="content-description">
              Selecting the right expert matters. Here’s what to look for:
            </p>
            <ul class="custom-bullet-list">
              <li class="list-group-item"><b>Subject Expertise</b><br>The writer must understand your topic thoroughly.
                For engineering, choose an engineering expert; for management, choose someone with business knowledge.
                Subject accuracy is crucial.</li>
              <li class="list-group-item"><b>Quality & Academic Standards</b><br>Look for writers who follow formatting
                styles (APA, MLA, Harvard), structure ideas logically, and present clear, high-quality content.</li>
              <li class="list-group-item"><b>Deadline Reliability</b><br>The writer should always deliver on time, giving
                you room to review and request changes if needed.</li>
              <li class="list-group-item"><b>Personalized Writing</b><br>Every assignment should be tailored to your
                academic level — undergraduate, postgraduate, or PhD.</li>
              <li class="list-group-item"><b>Clear Communication</b><br>A good writer listens, clarifies doubts, and keeps
                you updated throughout the writing process.</li>
            </ul>
          </div>


        </div>

        <div class="column">

          <div class="content-box">
            <h2>Get Our Expert Academic Assignment Writing Help Services</h2>
            <p class="content-description">
              Finding the right writer can be challenging — especially for technical subjects. Assignment in Need connects
              you directly with experienced online experts to ensure your assignment is handled exactly the way you want.
            </p>
            <h3>Guaranteed Privacy</h3>
            <p class="content-description">
              Your personal information is always confidential. We take your privacy seriously.
            </p>
            <h3>24/7 Support</h3>
            <p class="content-description">
              Need help anytime? Our support team is always available to answer your questions and assist you.
            </p>
          </div>

          <div class="content-box">
            <h2>Expert Assignment Helpers for All University Subjects</h2>
            <p class="content-description">
              Every university has its own academic standards. Our dedicated writing teams understand these requirements
              and tailor your assignment accordingly.
            </p>
            <h3>Business & Management</h3>
            <p class="content-description">
              Writers with MBAs deliver accurate, industry-based insights.
            </p>
            <h3>Engineering & Technology</h3>
            <p class="content-description">
              Degree-qualified engineering experts handle complex technical work.
            </p>
            <h3>Humanities & Social Sciences</h3>
            <p class="content-description">
              Writers with backgrounds in literature, sociology, history, psychology, and related fields ensure strong,
              research-driven assignments.
            </p>
            <p class="content-description">
              Each of our <a href="https://www.assignnmentinneed.com/homework-writing-help-services"><b>assignment
                  helpers</b></a> ensures your work meets the exact academic expectations of your course.
            </p>
          </div>

          <div class="content-box">
            <h2>How We Make Your Academic Life Easier</h2>
            <h3>Focus on Learning While We Handle the Writing</h3>
            <p class="content-description">
              Let us manage the complex writing tasks so you can focus on understanding your subjects.
            </p>
            <h3>Save Time Without Affecting Grades</h3>
            <p class="content-description">
              Enjoy more free time — while still maintaining high academic performance.
            </p>
            <h3>Boost Your Grades</h3>
            <p class="content-description">
              Our experts don’t just help you meet deadlines — they help you excel. We ensure your assignments are
              well-researched, clearly written, and academically strong.
            </p>
          </div>

          <div class="content-box">
            <h2>Why Assignment in Need Offers the Best Assignment Experts</h2>
            <h3>Writers Who Understand Your Needs</h3>
            <p class="content-description">
              Our writers take the time to understand your academic level, specific requirements, and challenges.
            </p>
            <h3>Deep Subject Knowledge</h3>
            <p class="content-description">
              Each writer is a specialist in their field, ensuring accuracy and high-quality academic content.
            </p>
            <h3>Commitment to Quality</h3>
            <p class="content-description">
              Every assignment goes through research, structured writing, and careful proofreading to meet the highest
              standards.
            </p>
          </div>

          <div class="content-box">
            <h2>Top Features of Our Assignment Expert Services</h2>
            <h3>Tailored Solutions for Every Student</h3>
            <p class="content-description">
              We customize your assignment based on your university guidelines, academic level, and learning style.
            </p>
            <h3>Comprehensive Support for Every Topic</h3>
            <p class="content-description">
              We explore all relevant sources and conduct thorough research to ensure your assignment is insightful and
              academically strong.
            </p>
            <h3>Clear Communication</h3>
            <p class="content-description">
              You receive timely updates, and our team is always ready to make changes based on your feedback.
            </p>
            <p class="content-description">
              With Assignment in Need, you're not just completing assignments — you're receiving support that boosts your
              learning, confidence, and academic success.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Expert Section  -->
  <section class="expert-section">

    <div class="expert-container">
      <div class="expert-row" id="expert-list">
        {{-- First 9 experts --}}
        @foreach ($expert->take(9) as $expert)
          @include('expert._expert_card_ajax', ['experts' => [$expert]])
        @endforeach
      </div>

      <div class="button-wrapper">
        <button class="show-more-btn" id="show-more">Show More</button>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      let offset = 9;
      const showMoreBtn = document.getElementById('show-more');
      const expertList = document.getElementById('expert-list');

      showMoreBtn?.addEventListener('click', function () {
        // Set loading state
        showMoreBtn.disabled = true;
        showMoreBtn.textContent = 'Loading...';

        fetch(`/load-experts?offset=${offset}`)
          .then(response => response.json())
          .then(data => {
            expertList.insertAdjacentHTML('beforeend', data.html);
            offset += 9;

            if (data.count < 9) {
              showMoreBtn.style.display = 'none';
            } else {
              // Reset button if more data is available
              showMoreBtn.disabled = false;
              showMoreBtn.textContent = 'Show More';
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showMoreBtn.disabled = false;
            showMoreBtn.textContent = 'Show More';
          });
      });
    });
  </script>


  <!-- Hover Effect on Expert Cards Script  -->
  <script>
    const expertCards = document.querySelectorAll(".inner-box-expert")
    expertCards.forEach((card) => {
      card.addEventListener("mouseenter", function () {
        const stars = this.querySelectorAll(".star.checked")
        stars.forEach((star, index) => {
          setTimeout(() => {
            star.classList.add("star-pulse")
            setTimeout(() => {
              star.classList.remove("star-pulse")
            }, 300)
          }, index * 100)
        })
      })
    })

    // Add CSS for additional animations
    const style = document.createElement("style")
    style.textContent = `
                                                                                                                                                                                                                        @keyframes starPulse {
                                                                                                                                                                                                                          0% { transform: scale(1); }
                                                                                                                                                                                                                          50% { transform: scale(2); }
                                                                                                                                                                                                                          100% { transform: scale(1); }
                                                                                                                                                                                                                          }

                                                                                                                                                                                                                          .star-pulse {
                                                                                                                                                                                                                            animation: starPulse 0.3s ease;
                                                                                                                                                                                                                            display: inline-block;
                                                                                                                                                                                                                            }
                                                                                                                                                                                                                            `
    document.head.appendChild(style)
  </script>

  <!-- styling for Expert Section Cards  -->
  <style>
    .expert-section {
      padding: 3rem 0px;
      margin: auto;
    }

    .expert-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px 20px;
    }

    .expert-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
    }

    .expert-item {
      margin: auto;
    }

    .testimonial-block-three-expert {
      padding: 5px 5px;
      padding-top: 20px;
      width: 100%;
      max-width: 340px;
      text-align: center;
      transition: all 1.0s ease;
    }

    .testimonial-block-three-expert .inner-box-expert {
      position: relative;
      text-align: center;
      padding: 0 15px 20px
    }

    .testimonial-block-three-expert .inner-box-expert:before {
      position: absolute;
      content: '';
      left: 0;
      top: 45px;
      right: 0;
      bottom: 0;
      border-radius: 8px;
      background-color: #e3dbff;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.10)
    }

    .testimonial-block-three-expert:hover .inner-box-expert:before {
      /* border: solid .2rem #5b21b6; */
      background: linear-gradient(white, white) padding-box, linear-gradient(135deg, #6e40c9, #007fff) border-box;
      border: 2px solid transparent;
      box-shadow: 0 8px 24px rgba(60, 70, 200, 0.2);
    }

    .testimonial-block-three-expert .inner-box-expert .image-outer {
      position: relative;
      margin-bottom: 22px;
      display: inline-block
    }

    .testimonial-block-three-expert .inner-box-expert .image-outer .image {
      position: relative;
      width: 80px;
      height: 80px;
      overflow: hidden;
      border-radius: 50%
    }

    .image-outer {
      margin-bottom: 15px;
    }

    .image {
      width: 100px;
      height: 100px;
      overflow: hidden;
      border-radius: 50%;
      background: white;
      padding: 2px;
      margin: 0 auto;
      border: 3px solid #7f3ffb;
      box-shadow: 0 0 0 5px #e4d9ff;
    }

    .image img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
    }

    h6 {
      font-size: 1.2rem;
      font-weight: 700;
      color: #2c2c51;
      margin: 10px 0 5px;
    }

    .designation {
      font-size: 0.8rem;
      color: #6c757d;
      margin-bottom: 15px;
    }

    /* Stats Container */
    .stats-container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 -5px 15px;
      padding: 10px;
      background-color: #f8f9ff;
      border-radius: 12px;
      position: relative;
      z-index: 1;
    }

    .stat-item {
      display: flex;
      align-items: center;
      padding: 0 10px;
    }

    .stat-icon {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 10px;
      font-size: 14px;
      font-weight: bold;
    }

    .stat-icon.completed {
      background-color: rgba(46, 204, 113, 0.15);
      color: #2ecc71;
    }

    .stat-icon.progress {
      background-color: rgba(127, 63, 251, 0.15);
      color: var(--primary);
    }

    .stat-details {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .stat-value {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--text-dark);
      line-height: 1.2;
    }

    .stat-label {
      font-size: 0.75rem;
      color: var(--text-light);
    }

    .stat-divider {
      width: 1px;
      height: 30px;
      background-color: #e0e0e0;
    }

    /* Rating */
    .rating {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 15px;
      position: relative;
      z-index: 1;
    }

    .stars {
      display: flex;
      margin-right: 8px;
    }

    .star {
      font-size: 1.2rem;
      color: #ccc;
      margin-right: 2px;
    }

    .star.checked {
      color: #ffc107;
    }

    .rating-score {
      font-size: 0.9rem;
      color: black;
      margin: 0;
    }

    .content {
      font-size: 1rem;
      color: #333;
      margin-top: 10px;
      min-height: 60px;
    }

    .visible {
      display: block;
    }

    .hidden {
      display: none;
    }

    .button-wrapper {
      text-align: center;
      margin-top: 40px;
    }

    .show-more-btn {
      padding: 12px 28px;
      background: linear-gradient(to right, #682ccc, #287fda);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .show-more-btn:hover {
      background: linear-gradient(135deg, #7f3ffb, #5a1fc4);
      transform: translateY(-2px);
    }

    .show-more-btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .inner-box-expert:hover .image {
      transform: scale(1.1);
      box-shadow: 0 0 10px 2px rgba(127, 63, 251, 0.5);
      border-color: #7f3ffb;
      transition: transform 0.4s ease, box-shadow 0.4s ease, border-color 0.4s ease;
    }

    .expert-item:hover .testimonial-block-three-expert {
      transform: translateY(-8px);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
  </style>

@endsection