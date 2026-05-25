@extends('frontend-layouts.app') {{-- If you have a main layout --}}

@section('content')
        <style>
            .detail-rating{
              padding: clamp(28px, 4vw, 52px) 0;
              background: #fff;
            }
            .detail-rating__container{
              max-width: 1200px;
              margin: 0 auto;
              padding: 0 16px;
            }
            .detail-rating__head{
              text-align: center;
              margin-bottom: 18px;
              opacity: 0;
              transform: translateY(10px);
              animation: drFade .6s ease-out .05s forwards;
            }
            .detail-rating__title{
              margin: 0;
              font-weight: 900;
              color: #1f1b3a;
              font-size: clamp(18px, 2.2vw, 26px);
            }
            .detail-rating__line{
              width: 64px;
              height: 3px;
              margin: 10px auto 8px;
              border-radius: 999px;
              background: #6d4bff;
            }
            .detail-rating__sub{
              margin: 0;
              color: #6b6787;
              font-weight: 600;
              font-size: 13px;
            }

            .detail-rating__grid{
              display: grid;
              grid-template-columns: 1fr 1fr;
              gap: 14px 18px;
            }

            .rating-card {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 40px;
                padding: 13px 25px;
                border-radius: 14px;
                border: 1.6px solid rgba(109, 75, 255, 0.55);
                background: #fff;
                box-shadow: 0 10px 20px rgba(15, 10, 60, 0.05);
                opacity: 0;
                transform: translateY(12px);
                animation: drCard .65s cubic-bezier(.2, .9, .2, 1) calc(.10s + var(--d, 0ms)) forwards;
                transition: transform .18s ease, box-shadow .18s ease;
            }
            .rating-card:hover{
              transform: translateY(-2px);
              box-shadow: 0 14px 26px rgba(15, 10, 60, 0.08);
            }

            .rating-card__left{
              display:flex;
              align-items:center;
              gap: 12px;
              min-width: 0;
            }
            .rating-card__icon {
                width: 40px;
                height: 40px;
                border-radius: 999px;
                display: grid;
                place-items: center;
                color: #3F159A;
                /* background: rgba(109, 75, 255, 0.12); */
                border: 1px solid rgba(109, 75, 255, 0.20);
                flex: 0 0 auto;
                background: #E8DCFF;
            }
            .rating-card__label {
                font-weight: 700;
                color: #3F159A;
                font-size: 18px;
                line-height: 27px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .rating-card__stars{
              color: #f6b300;
              font-size: 20px;
              letter-spacing: 1px;
              flex: 0 0 auto;
            }

            @keyframes drFade{ to{opacity:1; transform:none;} }
            @keyframes drCard{ to{opacity:1; transform:none;} }

            /* Mobile */
            @media (max-width: 720px){
              .detail-rating__grid{ grid-template-columns: 1fr; }
              .rating-card__label{ font-size: 12.5px; }
              .rating-card__stars{ font-size: 15px; }
            }

            @media (prefers-reduced-motion: reduce){
              .detail-rating__head, .rating-card{
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
              }
              .rating-card{ transition: none !important; }
            }
        </style>
        @include('new-component.hero', [
            'title' => 'Student Reviews for Assignment in need – Rated & Trusted by Learners',
            'subtitle' => 'See what real students say about our assignment help services. Honest feedback, proven results, and support you can count on when it matters most.'
        ])

        @include('components.review-section')


        <section class="detail-rating">
          <div class="detail-rating__container">
            <div class="detail-rating__head">
              <h2 class="section-title">Our <span>Detailed</span> Ratings</h2>
              <p class="section-description">
                The quality of our service is reflected in the satisfaction of our students.
              </p>
            </div>
            <div class="detail-rating__grid">
              <div class="rating-card" style="--d:0ms">
                <div class="rating-card__left">
                  <span class="rating-card__icon" aria-hidden="true">
                        <li class="far fa-edit"></li>
                   </span>
                  <div class="rating-card__label">Written Excellence Score</div>
                </div>
                <div class="rating-card__stars " aria-label="4.5 stars">
                    <span>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star-half-o"></li>  
                    </span>
                </div>
              </div>

              <div class="rating-card" style="--d:80ms">
                <div class="rating-card__left">
                  <span class="rating-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                      <path d="M12 3 20 7v6c0 5-3.2 8.6-8 10-4.8-1.4-8-5-8-10V7l8-4Z" stroke="currentColor" stroke-width="1.8"/>
                      <path d="m9 12 2 2 4-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </span>
                  <div class="rating-card__label">Brand Reliability Rating</div>
                </div>
                <div class="rating-card__stars " aria-label="4.5 stars">
                    <span>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star-half-o"></li>              
                    </span>
                </div>
              </div>
              <div class="rating-card" style="--d:160ms">
                <div class="rating-card__left">
                  <span class="rating-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                      <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" stroke="currentColor" stroke-width="1.8"/>
                      <path d="M8.5 14.5c1 1.2 2.2 1.8 3.5 1.8s2.5-.6 3.5-1.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                      <path d="M9 10h.01M15 10h.01" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                    </svg>
                  </span>
                  <div class="rating-card__label">Product Satisfaction Rating</div>
                </div>
                 <div class="rating-card__stars " aria-label="4.5 stars">
                    <span>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star-half-o"></li>  
                    </span>
                </div>
              </div>

              <div class="rating-card" style="--d:240ms">
                <div class="rating-card__left">
                  <span class="rating-card__icon" aria-hidden="true">
                    <!-- wallet -->
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                      <path d="M4 7a3 3 0 0 1 3-3h11a2 2 0 0 1 2 2v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                      <path d="M4 9v10a3 3 0 0 0 3 3h13a2 2 0 0 0 2-2V11a2 2 0 0 0-2-2H4Z" stroke="currentColor" stroke-width="1.8"/>
                      <path d="M18 15h2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                  </span>
                  <div class="rating-card__label">Value for Money Rating</div>
                </div>
                <div class="rating-card__stars " aria-label="4.5 stars">
                    <span>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star-half-o"></li>              
                    </span>
                </div>
              </div>

              <!-- Row 3 -->
              <div class="rating-card" style="--d:320ms">
                <div class="rating-card__left">
                  <span class="rating-card__icon" aria-hidden="true">
                    <!-- sparkle/service -->
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                      <path d="M12 2l1.2 5.2L18 9l-4.8 1.8L12 16l-1.2-5.2L6 9l4.8-1.8L12 2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                      <path d="M4 14l.7 2.8L7.5 18l-2.8.7L4 21l-.7-2.3L.5 18l2.8-1.2L4 14Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" opacity=".8"/>
                    </svg>
                  </span>
                  <div class="rating-card__label">Service Experience Rating</div>
                </div>
                <div class="rating-card__stars " aria-label="4.5 stars">
                    <span>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star-half-o"></li>          
                    </span>
                </div>
              </div>

              <div class="rating-card" style="--d:400ms">
                <div class="rating-card__left">
                  <span class="rating-card__icon" aria-hidden="true">
                    <!-- globe -->
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                      <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" stroke="currentColor" stroke-width="1.8"/>
                      <path d="M3 12h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                      <path d="M12 3c2.5 2.8 2.5 15.2 0 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                      <path d="M12 3c-2.5 2.8-2.5 15.2 0 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".9"/>
                    </svg>
                  </span>
                  <div class="rating-card__label">Global Verification</div>
                </div>
                <div class="rating-card__stars " aria-label="4.5 stars">
                    <span>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star"> </li>
                        <li class="fa fa-star-half-o">    </li>          
                    </span>
                </div>
              </div>
            </div>
          </div>

        </section>
         @include('new-component.review')

            @include('home.section.subjects')
            <?php 
            $faqs = [
        [
            'question' => 'How soon will my assignment be completed and delivered?',
            'answer' => 'Assignment timelines vary depending on your specific needs, such as the
                                topic complexity, academic level, and word
                                count. In most cases, assignments are delivered within 48 to 72 hours. However, if you’re
                                working with a tight deadline,
                                we also offer urgent delivery options that can ensure your work is completed within 24 hours,
                                depending on writer
                                availability. We aim to meet your academic deadlines without compromising on the quality.'
        ],
        [
            'question' => 'Is there any chance my assignment will be shared?',
            'answer' => 'No, once your assignment is completed and delivered, it is never
                                reused, shared, or repurposed for any other student. At
                                Assignment in Need, we follow a strict policy against content reuse or duplication. Each task is
                                crafted individually to
                                suit your unique instructions and remains your intellectual property. This commitment ensures
                                the confidentiality and
                                originality of every assignment we produce.'
        ],
        [
            'question' => 'Will I be able to check before final payment?',
            'answer' => 'The assignments we provide are 100% original and designed to meet
                                academic standards, but they are not meant to be
                                submitted directly to your university. We do not support or promote the submission of our work
                                as your own. Instead, we
                                strongly advise students to use our content as a learning resource to understand complex topics,
                                structure their
                                arguments effectively, and develop their own ideas. You can use our materials to draft your own
                                assignment, gain
                                confidence in your subject, and improve the overall quality of your submission, all while
                                staying within your
                                institution’s academic integrity policies.'
        ],
        [
            'question' => 'Will I be able to check my assignment before completing the final
                                payment?',
            'answer' => 'Yes, you will have an opportunity to review your assignment before
                                making the final payment. A preview or draft version
                                is usually provided so you can confirm that the work aligns with your original brief. This
                                process helps ensure
                                transparency, reduces the risk of dissatisfaction, and allows for revisions if needed.
                                Assignment in Need prioritises
                                student confidence at every stage of the order process.'
        ],
        [
            'question' => 'What if my teacher suddenly changes the topic after I place the order?',
            'answer' => 'No worries at all. We know that university guidelines change-sometimes
                                even after you’ve already started writing. If
                                your topic gets changed after you’ve placed an order, we’ll allow one free topic change. Even if
                                the writing has
                                started, we’ll try to adjust your assignment to match the new topic as closely as possible. Our
                                aim is to stay flexible
                                with your academic needs and reduce your stress, not add to it.'
        ],
    ];
            ?>

            @include('new-component.faq', ['faqs' => $faqs])

           <!-- FAQ Section -->


@endsection