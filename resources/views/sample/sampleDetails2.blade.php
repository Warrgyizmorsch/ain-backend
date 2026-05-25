@extends('frontend-layouts.app')

@section('content')
    <style>
        .html{
            scroll-behavior: smooth;
        }
        a {
            text-decoration: none;
            cursor: pointer;
            color: #007bff;
        }

        .form-header h3 {
            color: white;
            font-size: 30px !important;
        }

        .blog-detail-banner-section {
            padding: 15px 10px;
            position: relative;
            overflow: hidden;
            padding-bottom: 34px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }

        .banner-section-three .content-column .inner-column {
            padding-top: 0px;
        }

        h1,
        h2,
        h3 {
            font-weight: 500;
            color: black;
        }

        h1 {
            font-size: 45px;
            font-weight: bolder;
        }

        h4 {
            font-size: 16px;
        }

        .sidebar-page-container.style-two {
            padding-top: 35px;
        }

        h2 {
            font-size: 31px !important;
        }

        @media only screen and (max-width: 479px) {
            h1 {
                font-size: 24px !important;
                line-height: 1.4em;
                font-weight: bolder;
            }

            h2 {
                font-size: 25px !important;
            }
        }

        h2 span {
            font-size: 25px;
            font-weight: 500;
            margin: 20px 0px 20px 0px;
            color: blue;
        }

        h2 span {
            font-size: 25px;
        }

        h3 {
            font-size: 20px !important;
        }



        h3 span {
            font-size: 20px !important;
        }

        .place-order {
            background: #d7f0fd;
            color: black;
            padding: 10px 20px;
            border-radius: 5%;
            margin: 10px;
        }


        .place-order:hover {
            background: #7e89dd;
            color: white;

        }

        .section h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
        }

        .links {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .links a {
            background: #0056b3;
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s, transform 0.3s;
        }

        .links a:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .links a:active {
            background: #004080;
            transform: translateY(0);
        }

        .image {
            width: 100%;
            height: 100%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        p {
            text-align: justify;
        }

        /*============ Social Section ============*/
        .social-section {
            padding-top: 2.5rem;
            border-radius: 12px;
            text-align: center;
        }

        .social-heading {
            font-size: 1.2rem;
            font-weight: 600;
            text-align: center;
            color: #333;
        }

        .social-section h2 {
            font-size: 2rem;
            color: #1a2a42;
            margin-bottom: 10px;
            animation: fadeInDown 1s ease-in-out;
        }

        .social-section p {
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 25px;
            animation: fadeInUp 1s ease-in-out;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            animation: fadeIn 1.2s ease-in-out;
        }

        .social-icons a {
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.3s ease, opacity 0.3s ease;
            opacity: 0.9;
        }

        .social-icons a:hover {
            transform: scale(1.1);
            opacity: 1;
        }

        .facebook {
            color: #1877f2;
        }

        .instagram {
            color: #e4405f;
        }

        .twitter {
            color: #1da1f2;
        }

        .pinterest {
            color: #bd081c;
        }

        .youtube {
            color: #ff0000;
        }

        .sample-guidance-section {
            background: linear-gradient(white, white) padding-box,
                linear-gradient(135deg, #6e40c9, #007fff) border-box;
            border: 3px solid transparent;
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            animation: fadeInUp 1.2s ease-in-out;
        }

        .sample-guidance-section h2 {
            font-size: 1.5rem;
            font-weight: 700;
            text-align: left;
            color: #3a3a5a !important;
            margin-bottom: .5rem;
        }

        .sample-heading {
            font-size: 1.6rem;
            font-weight: 700;
            text-align: center;
            color: #3a3a5a;
            margin-bottom: 1rem;
        }

        .sample-description {
            font-size: 1rem;
            line-height: 1.7;
            color: #444;
            text-align: justify;
            margin-bottom: 1rem;
            animation: fadeIn 1.4s ease-in-out;
        }

        .sample-description em {
            color: #6e40c9;
            font-style: italic;
            font-weight: 400;
        }

        .sample-description strong {
            color: #1a73e8;
        }

        /* Responsive Tweaks */
        @media (max-width: 600px) {
            .sample-guidance-section {
                padding: 1.2rem;
            }

            .sample-heading {
                font-size: 1.3rem;
            }

            .sample-description {
                font-size: 0.95rem;
            }
        }


        .blog-detail-banner-section .content-box {
            position: relative;
            max-width: 100%;
            /* css */
            background: linear-gradient(135deg, #2e40ad, #0776c6);
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            color: white;
            margin-bottom: 40px;
        }

        .content-box h1 {
            color: #ffff;
            font-size: 2.0rem;
            font-weight: 100;
            margin: 0;
            line-height: 1.3;
            letter-spacing: 0.5px;
        }

        /* table subject */
        .tablePractice {
            background-color: #fff;
            padding: 20px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
            /* max-width: 800px; */
            margin: 30px auto;
        }

        .tablePractice .table-title {
            font-size: 1.2rem;
            color: #2e40ad;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
        }

        .tablePractice ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 12px;
            white-space: nowrap;
        }

        .tablePractice li {
            background: #fff;
            padding: 12px 16px;
            border: 1px solid #dbe4f5;
            border-left: 4px solid #1e67bd !important;
            border-right: 4px solid #1e67bd;
            background-color: #f5f9ff;
            border: 1px solid #dde6f3;
            padding: 10px 15px;
            border-radius: 6px;

        }

        /* .tablePractice li:hover {
                            background-color: #eaf2ff;
                        } */

        .tablePractice li span {
            color: #2e40ad;
            font-weight: 600;
            margin-right: 8px;
            white-space: nowrap;
        }

        /* intro section  */
        .intro-section {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-align: justify;
            font-family: 'Segoe UI', sans-serif;
            font-size: 1rem;
            line-height: 1.8;
            color: #333;
            max-width: 900px;
            margin: 0 auto;
        }

        .intro-title {
            color: white;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 25px;
        }


        .popular-tags {
            background: #f5f5f5 !important;
            padding: 15px !important;
            border-radius: 15px !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
        }

        .popular-tags .sidebar-title h4 {
            font-size: 20px !important;
            font-weight: 600 !important;
            margin-bottom: 10px !important;
            color: #3F26A6 !important;
            border-bottom: 2px solid #3F26A6 !important;
            padding-bottom: 5px !important;
        }

        .popular-tags .widget-content {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 8px !important;
            display: inline-block !important;
        }

        /* Bootstrap button resets */
        .popular-tags a {
            display: inline-block !important;
            padding: 8px 14px !important;
            font-size: 14px !important;
            font-weight: bold !important;
            text-decoration: none !important;
            color: white !important;
            background: #3F26A6 !important;
            border-radius: 15px !important;
            border: none !important;
            transition: all 0.3s ease-in-out !important;
        }

        /* Prevent Bootstrap button hover effect */
        .popular-tags a:hover {
            background: rgb(34, 20, 90) !important;
            transform: scale(1.05) !important;
        }

        /* Remove Bootstrap’s focus outline */
        .popular-tags a:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        /* Categories Widget with Outline Design */
        .links-widget {
            padding: 15px !important;
            border-radius: 15px !important;
        }

        .links-widget .sidebar-title h4 {
            font-size: 20px !important;
            margin-bottom: 10px !important;
            font-weight: 600 !important;
            color: #3F26A6 !important;
            border-bottom: 2px solid #3F26A6 !important;
            padding-bottom: 5px !important;

        }

        .blog-cat {
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .blog-cat li {
            margin-bottom: 8px !important;
        }

        .blog-cat a {
            display: block !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
            font-weight: bold !important;
            text-decoration: none !important;
            color: #3F26A6 !important;
            background: transparent !important;
            border-radius: 10px !important;
            transition: all 0.3s ease-in-out !important;
            border: 2px solid #3F26A6 !important;
        }

        .blog-cat a:hover {
            background: #1365c0 !important;
            color: #fff !important;
        }

        .sticky-top {
            z-index: 1;
            top: 10px;
        }

        .content-side {
            height: 1100px;
            overflow-y: scroll;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .content-column::webkit-scrollbar {

            display: none;
        }

         /*============ Swiper Section ============*/
  .sample-section {
    margin-bottom: 30px;
    padding: 4rem 2rem;
    background: #f9f9f9;
    border-radius: 12px;
  }

  .swiper-slide {
    max-width:350px;
    height:auto;
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    height: auto;
  }

  .swiper-slide h4 {
    font-size: 1.2rem;
    color: #222;
    margin-bottom: 1rem;
  }

  .swiper-slide p {
    flex-grow: 1;
    color: #666;
    font-size: 0.95rem;
    margin-bottom: 1rem;
  }

  .swiper-slide .link {
    color: #5e2ced;
    font-weight: bold;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .swiper-slide .link:hover {
    color: #0056b3;
  }

  .swiper-button-next,
  .swiper-button-prev {
    color: #5e2ced;
  }

  .swiper-pagination-bullet {
    background: #ccc;
    opacity: 1;
  }

  .swiper-pagination-bullet-active {
    background: #5e2ced;
  }



  /* ============ Desktops ============ */
  @media (min-width: 992px) and (max-width: 1199px) {
    h1 {
      font-size: 36px;
    }

    h2 {
      font-size: 30px;
    }

    .grid-layout {
      grid-template-columns: 2fr 1fr;
    }
  }

  /* ============ Tablets (Landscape and Portrait) ============ */
  @media (min-width: 768px) and (max-width: 991px) {
    h1 {
      font-size: 32px;
    }

    h2 {
      font-size: 26px;
    }

    .grid-layout {
      grid-template-columns: 1fr;
    }

    .offer-overlay {
      padding: 1.5rem;
    }

    .info-grid {
      grid-template-columns: 1fr 1fr;
    }

    .swiper-slide {
      padding: 1rem;
    }
  }

  /* ============ Phones ============ */
  @media (max-width: 767px) {
    h1 {
      font-size: 28px;
    }

    h2 {
      font-size: 24px;
    }

    h3 {
      font-size: 20px;
    }

    .container {
      padding: 1rem;
    }

    .grid-layout {
      grid-template-columns: 1fr;
      gap: 1rem;
    }

    .offer-overlay {
      padding: 1rem;
      width: 90%;
    }

    .swiper-slide {
      padding: 1rem;
    }

    .info-grid {
      grid-template-columns: 1fr;
    }

    .content-container,
    .side-content {
      text-align: left;
    }

    .swiper-slide p {
      font-size: 0.9rem;
    }

    .btn {
      width: 100%;
      font-size: 0.9rem;
    }
  }

  /* ============ Extra Small Phones ============ */
  @media (max-width: 480px) {
    h1 {
      font-size: 24px;
    }

    h2 {
      font-size: 20px;
    }

    .offer-overlay h2 {
      font-size: 1.5rem;
    }

    .social-icons {
      flex-direction: column;
      align-items: center;
      gap: 10px;
    }

    .swiper-slide p {
      font-size: 0.85rem;
    }

    .container {
      padding: 0.75rem;
    }


}

    </style>

<style>
    .total-sample {
        background-color: #1e3a8a;
        color: white;
        margin-bottom: 0px;
        font-size: 12px;
        font-weight: bold;
        padding: 2px 10px;
        border-radius: 22px;
        display: inline-block;
        text-align: center;
    }
    </style>
    <div class=" clearfix">
        <div class="content-column col-lg-12 col-md-12 col-sm-12">
            <div class="inner-column">
                <div class="icon-layer" style="background-image: url(images/icons/icon-2.png)"></div>
                <div class="sidebar-page-container style-two mb-0 pb-0">
                    <div class="auto-container mt-3">
                        <div class="row clearfix">
                            <div class="content-side col-lg-8 col-md-12 col-sm-12">
                                <div class="auto-container blog-detail-banner-section">
                                    <div class="content-box ">
                                        <h1>{{ $sample->title ?? '' }}</h1>
                                    </div>
                                    <div class="tablePractice">
                                        <p class="table-title"><span>University</span></p>
                                        <ul>

                                            <li><span>Unit No:</span> 0</li>
                                            <li><span>Level:</span> High School</li>
                                            <li><span>Pages:</span> {{ str_word_count(strip_tags($sample->content)) }}</li>
                                            <li><span>Paper type:</span>{{ $sample->category }} </li>
                                            <li><span>Course Code:</span></li>
                                            <li><span>Downloads:</span> 63838</li>
                                        </ul>
                                    </div>

                                    <div class="intro-section mt-3">
                                        <h2 class="intro-title"><span
                                                style="background-color: rgb(255, 255, 255); color: rgb(6, 20, 45);">Introduction</span>
                                        </h2>
                                        @if (auth()->user())
                                            {!! $sample->content ?? '' !!}
                                        @else
                                            {!! Str::limit($sample->content, 1000, '...') !!}
                                        @endif
                                    </div>
                                    <div class=" social-section ">
                                        <h4 class="social-heading">Share with Your Friends</h4>
                                        <div class="social-icons py-2">
                                            <a href="https://www.facebook.com/people/Assignmentinneedofficial/61564613120071/"
                                                target="_blank" style="color: #1877f2; ">
                                                <i class="fab fa-facebook-square"></i> Facebook
                                            </a>
                                            <a href="https://www.instagram.com/assignmentinneedofficial/" target="_blank"
                                                style="color: #e4405f;">
                                                <i class="fab fa-instagram"></i> Instagram
                                            </a>
                                            <a href="https://x.com/assignment_in" target="_blank" style="color: #1da1f2; ">
                                                <i class="fab fa-twitter"></i> Twitter
                                            </a>
                                            <a href="https://in.pinterest.com/assignnmentinneed66/" target="_blank"
                                                style="color: #bd081c;">
                                                <i class="fab fa-pinterest"></i> Pinterest
                                            </a>
                                            <a href="https://www.youtube.com/@assignmentinneed1169" target="_blank"
                                                style="color: #ff0000; ">
                                                <i class="fab fa-youtube"></i> YouTube
                                            </a>
                                        </div>




                                <section class="sample-guidance-section">
                                    <h2>Struggling with Assignments? Our Free Samples Can Guide You!</h2>
                                    <p class="sample-description">
                                        It’s common for students to feel overwhelmed and wonder, <a
                                            href="https://www.assignnmentinneed.com/do-my-assignment-for-me"><em>“Can
                                                someone do my assignment for me?”</em></a>
                                        That’s exactly why we’ve built this sample section — to give you a clear look at the
                                        quality we deliver through real, all subject-specific examples. From <a
                                            href="https://www.assignnmentinneed.com/essay-writing-help-services"><strong>essays
                                                help</strong></a> and <a
                                            href="https://www.assignnmentinneed.com/uk/case-study-writing-help"><strong>case
                                                studies</strong></a>, to <a
                                            href="https://www.assignnmentinneed.com/dissertation-writing-help-services"><strong>dissertation
                                                help</strong></a>, project reports, <a
                                            href="https://www.assignnmentinneed.com/research-paper-writing-services"><strong>research
                                                paper help</strong></a>, and <a
                                            href="https://www.assignnmentinneed.com/homework-writing-help-services"><strong>homework
                                                help</strong></a>, our work showcases depth, clarity, and expert precision.
                                    </p>
                                    <p class="sample-description">
                                        At <a href="https://www.assignnmentinneed.com"><strong>Assignment In
                                                Need</strong></a>, we go beyond basic writing services with plagiarism-free
                                        content, timely submissions, qualified experts, 24/7 support, affordable prices, and
                                        free unlimited revisions. These samples aren’t just academic models — they’re proof
                                        of the excellence we bring to every project.
                                    </p>
                                </section>



                            </div>

                        </div>

                                {{--
                                <div class="blog-detail">
                                    <div class="inner-box">
                                        <div class="blockquote-box mt-0">
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="inner-column">
                                                        <div class="image">
                                                            <img src="" alt="" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bold-text">
                                            <div class="bold-text"></div>


                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="sidebar-side col-lg-4 col-md-12 col-sm-12">
                                <aside class="sidebar sticky-top">
                                    @include('components.pricing-card')

                                    <div class="sidebar-widget links-widget">
                                        <div class="sidebar-title">
                                            <h4>Our Popular Subjects</h4>
                                        </div>
                                        <ul class="blog-cat">
                                            @foreach ($categoryDetails as $cat)
                                                    <li><a href="/homework-writing-help-services" >
                                                        <div style="display: flex; justify-content: space-between; align-items:center;">
                                                            <p style="margin-bottom: 0px;"> {{ $cat['subject'] }}</p>
                                                        <p class="total-sample">{{ $cat['total_samples'] }}</p>
                                                        </div>
                                                    </a></li>
                                            @endforeach
                                        </ul>
                                    </div>

                            </div>
                        </div>
                        <section class="sample-section">
                            <div class="auto-container">
                              <h2 class="section-title">Latest Related Free Samples (1000+ Free Samples)</h2>

                              <!-- Swiper Container -->
                              <div class="swiper mySwiper">
                                <div class="swiper-wrapper">

                                  @foreach ($samplesuggestion as $obj)
                                  <div class="swiper-slide">
                                    <h4>{!! Str::limit($obj->title, 30, '...') !!}</h4>
                                    <p>{!! Str::limit($obj->content, 300, '...') !!}</p>
                                    <a href="free-samples/{{$categoryName}}/{{$obj->slug}}" class="link">View or Download →</a>
                                  </div>
                                  @endforeach

                                </div>

                                <!-- Navigation -->
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-pagination"></div>
                              </div>
                            </div>
                          </section>
                          <script>
                            const swiper = new Swiper(".mySwiper", {
                              loop: true, // Infinite scroll
                              autoplay: {
                                delay: 3000, // 3 seconds per slide
                                disableOnInteraction: false, // Keep autoplay even after interaction
                              },
                              slidesPerView: 1,
                              spaceBetween: 20,
                              navigation: {
                                nextEl: ".swiper-button-next",
                                prevEl: ".swiper-button-prev",
                              },
                              pagination: {
                                el: ".swiper-pagination",
                                clickable: true,
                              },
                              breakpoints: {
                                640: {
                                  slidesPerView: 1,
                                },
                                768: {
                                  slidesPerView: 2,
                                },
                                1024: {
                                  slidesPerView: 3,
                                },
                              },
                            });
                          </script>

                          <script>
                            // Select slider and navigation buttons
                            const slider = document.querySelector('.card-slider');
                            const prevBtn = document.getElementById('prevBtn');
                            const nextBtn = document.getElementById('nextBtn');

                            // Get the width of a single card dynamically
                            const cardWidth = slider.querySelector('.card').offsetWidth + 20; // Including gap

                            // Scroll to the previous set of cards
                            prevBtn.addEventListener('click', () => {
                              if (slider.scrollLeft <= 0) {
                                // If at the first card, scroll to the last card
                                const maxScrollLeft = slider.scrollWidth - slider.clientWidth;
                                slider.scrollTo({
                                  left: maxScrollLeft,
                                  behavior: 'smooth'
                                });
                              } else {
                                // Scroll to the previous card
                                slider.scrollBy({
                                  left: -cardWidth,
                                  behavior: 'smooth'
                                });
                              }
                            });

                            // Scroll to the next set of cards
                            nextBtn.addEventListener('click', () => {
                              const maxScrollLeft = slider.scrollWidth - slider.clientWidth;

                              if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth) {
                                // If at the last card, scroll to the first card
                                slider.scrollTo({
                                  left: 0,
                                  behavior: 'smooth'
                                });
                              } else {
                                // Scroll to the next card
                                slider.scrollBy({
                                  left: cardWidth,
                                  behavior: 'smooth'
                                });
                              }
                            });
                          </script>
                        </div>
                      </section>
                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection
