@php
    use App\Models\Review;

    if (!isset($data['review'])) {
        $data['review'] = Review::orderBy('created_at', 'desc')->take(10)->get();
    }
@endphp

<section class="page-section reviews-section">
    <div class="container">
        <h2 class="section-title">
            {!! $pageTitle ?? 'Genuine Reviews From <span>Students Who Trust</span> Our UK Assignment Help' !!}
        </h2>
        <p class="reviews-subtitle">
            {!! $pageDescription ?? 'With an outstanding <a href="/review">4.4 average rating from over 5,126 reviews</a> across trusted platforms, students consistently choose Assignment In Need for reliable, fast, and high-quality academic help. See what they have to say:' !!}
        </p>

        <!-- Tabs -->
        <div class="review-tabs">
            <button class="tab-btn active" data-tab="assignnmentinneed">
                <img src="/images/icons/assignment_logo_2.avif" alt="assignnmentinneed">
            </button>
            <button class="tab-btn" data-tab="trustpilot">
                <img src="/images/icons/Trustpilot_Logo.avif" alt="Trustpilot">
            </button>
            <button class="tab-btn" data-tab="sitejabber">
                <img src="/images/icons/google-logo.avif" alt="Sitejabber">
            </button>
            <button class="tab-btn" data-tab="chat">
                <img src="/images/icons/chat.jfif" alt="chat">
            </button>
        </div>

        <!-- assignnment in need Tab -->
        <div class="tab-content active" id="assignnmentinneed">
            <div class="slider-containers">
                <div class="cards-wrapper" id="cardWrappers">
                    <!-- 8 Cards -->
                    @foreach ($data['review'] ?? [] as $review)
                        <div class="slider-card">
                            <p class="subheading-p">{{ $review->name }}</p>
                            <div class="review-header">
                                <span class="location-text">{{ $review->location }}</span>
                                <span
                                    class="review-date">{{ \Carbon\Carbon::parse($review->submission_date)->format('F j, Y') }}</span>
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
                            @if(!empty($review->review_reply))
                                <div class="review-reply">
                                    <div class="reply-header">
                                        <img src="{{ asset('assets/media/layout/assignment_logo.webp') }}" alt="Company Logo"
                                            class="reply-logo">
                                        <span class="reply-author">Assignment In Need </span>
                                    </div>
                                    <div class="reply-body">
                                        <span class="reply-arrow">↳</span>
                                        <span class="reply-text" id="reply-{{ $review->id }}">
                                            {{ Str::limit($review->review_reply, 100) }}
                                            @if(strlen($review->review_reply) > 100)
                                                <a href="javascript:void(0)" class="read-more"
                                                    data-full="{{ $review->review_reply }}" data-id="{{ $review->id }}">
                                                    Read More
                                                </a>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>
            </div>

            <div class="controls">
                <button onclick="moveSlid(-1)">Prev</button>
                <button onclick="moveSlid(1)">Next</button>
            </div>

        </div>

        <!-- Trustpilot Tab -->
        <div class="tab-content" id="trustpilot">
            <div class="slider-box">
                <div class="slider-track" id="trustSliderTrack">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="review-card">
                            <img src="images/trust/review-{{ $i }}.jpg" alt="Review {{ $i }}">
                        </div>
                    @endfor
                </div>
                <button class="slider-btns prev-btn" onclick="changeTrustSlide(-1)">&#10094;</button>

                <button class="slider-btns next-btn" onclick="changeTrustSlide(1)">&#10095;</button>
            </div>
        </div>

        <!-- Sitejabber (Google Slider Cards) -->
        <div class="tab-content" id="sitejabber">
            <div class="custom-slider-container">
                <div class="custom-slider-track-wrapper">
                    <div class="custom-slider-tracks" id="googleSliderTrack">
                        <div class="custom-cards">
                            <div class="first-letter-div">
                                <p class="subheading-p">Arshpreet Kaur</p>
                            </div>
                            <div class="star-google">

                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                    class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <div class="review">
                                <p class="review-date">19 November 2024</p>
                                <p class="review-para">You guys are the best. Thank you for your efforts. I really
                                    like your services and
                                    the way every staff member behaves 🥰.</p>
                            </div>
                        </div>
                        <div class="custom-cards">
                            <div class="first-letter-div">
                                <p class="subheading-p">Wajahat Afreen</p>
                            </div>
                            <div class="star-google">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                    class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <div class="review">
                                <p class="review-date">27 March 2024</p>
                                <p class="review-para">First of all Thank you great team . You have very professional
                                    team because of you
                                    lot of us achieve our dream . thanks alot for your help and support.</p>
                            </div>
                        </div>

                        <div class="custom-cards">
                            <div class="first-letter-div">
                                <p class="subheading-p">Satwinder Saini</p>
                            </div>
                            <div class="star-google">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                    class="fas fa-star"></i>
                            </div>
                            <div class="review">
                                <p class="review-date">12 January 2024</p>
                                <p class="review-para">First of all Thank you great team . You have very professional
                                    team because of you
                                    lot of us achieve our dream . thanks alot for your help and support.</p>
                            </div>
                        </div>

                        <div class="custom-cards">
                            <div class="first-letter-div">
                                <p class="subheading-p">Albena Kaneva</p>
                            </div>
                            <div class="star-google">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                    class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <div class="review">
                                <p class="review-date">22 July 2024</p>
                                <p class="review-para">So quick and reliable service! Almost 24 hours provides support!
                                    The best in prices and quality as well! Thank you guys!
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="google-nxt-pre-btn">
                    <button class="custom-slider-btn custom-prev-btn" onclick="moveGoogleSlider(-1)">&#10094;</button>
                    <button class="custom-slider-btn custom-next-btn" onclick="moveGoogleSlider(1)">&#10095;</button>
                </div>
            </div>


        </div>


        <div class="tab-content" id="chat">
            <div class="chat-reviews row g-4 p-4">
                <div class="custom-slider-container">
                    <div class="custom-slider-track-wrapper">
                        <div class="custom-slider-tracks" id="secondSliderTrack">
                            <!-- Review Item -->
                            <div class="slider-item">
                                <div class="chat-card">
                                    <img src="{{ asset('/images/chat1.webp') }}" alt="Review 1" class="chat-review-img">
                                    <h5 class="chat-user-name">Oliver Bennett</h5>
                                    <div class="star-google">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i>
                                    </div>
                                    <p class="chat-review">
                                        “The experts guided me step by step and made graphs so easy to understand. Their
                                        help made my report clear and professional.”
                                    </p>
                                </div>
                            </div>

                            <!-- Review Item -->
                            <div class="slider-item">
                                <div class="chat-card">
                                    <img src="{{ asset('/images/chat2.webp') }}" alt="Review 2" class="chat-review-img">
                                    <h5 class="chat-user-name">Sarah L</h5>
                                    <div class="star-google">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i>
                                    </div>
                                    <p class="chat-review">
                                        “Assignment In Need saved my semester! Super-fast service and top-notch quality
                                        – highly recommended for students.”
                                    </p>
                                </div>
                            </div>

                            <!-- Review Item -->
                            <div class="slider-item">
                                <div class="chat-card">
                                    <img src="{{ asset('/images/chat3.webp') }}" alt="Review 3" class="chat-review-img">
                                    <h5 class="chat-user-name">John Doe</h5>
                                    <div class="star-google">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i>
                                    </div>
                                    <p class="chat-review">
                                        “ I was panicking about my physics assignment, but Assignment In Need helped me
                                        last minute help”
                                    </p>
                                </div>
                            </div>
                            <div class="slider-item"">
                            <div class=" chat-card">
                                <img src="{{ asset('/images/chat4.webp') }}" alt="Review 3" class="chat-review-img">
                                <h5 class="chat-user-name">Amelia Harris</h5>
                                <div class="star-google">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                        class="fas fa-star"></i><i class="fas fa-star"></i>
                                </div>
                                <p class="chat-review">
                                    “ Got an A on my essay! Assignment In Need really helped me meet deadlines and made
                                    a tough week so much easier.”
                                </p>
                            </div>
                        </div>
                        <div class="slider-item">
                            <div class="chat-card">
                                <img src="{{ asset('/images/chat5.webp') }}" alt="Review 3" class="chat-review-img">
                                <h5 class="chat-user-name">Ethan Clarke</h5>
                                <div class="star-google">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                        class="fas fa-star"></i><i class="fas fa-star"></i>
                                </div>
                                <p class="chat-review">
                                    “ They suggested great research sources and articles that perfectly fit my topic.
                                    Really helped me structure my work.”
                                </p>
                            </div>
                        </div>
                        <div class="slider-item">
                            <div class="chat-card">
                                <img src="{{ asset('/images/chat6.webp') }}" alt="Review 3" class="chat-review-img">
                                <h5 class="chat-user-name">Sophie Mitchell</h5>
                                <div class="star-google">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                        class="fas fa-star"></i><i class="fas fa-star"></i>
                                </div>
                                <p class="chat-review">
                                    “Assignment In Need has been a lifesaver! Their team is professional, delivers
                                    original work on time, and has helped me improve my grades with less stress.”
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chat-nxt-pre-btn">
                <button class="custom-slider-btn custom-prev-btn" onclick="moveSecondSlider(-1)">&#10094;</button>
                <button class="custom-slider-btn custom-next-btn" onclick="moveSecondSlider(1)">&#10095;</button>
            </div>
        </div>
    </div>


    </div>
</section>


<style>
    .review-header {
        display: flex;
        gap: 8px;
        /* space between items */
        align-items: center;
        flex-wrap: wrap;
        /* allows wrapping on smaller screens */
        font-size: 14px;
        /* adjust as needed */
    }

    .review-header span::before {
        content: "|";
        margin: 0 4px;
        color: #666;
    }

    /* Remove the first '|' before the first item */
    .review-header span:first-child::before {
        content: "";
        margin: 0;
    }

    .review-reply {
        margin-top: 10px;
        padding-left: 20px;
        border-left: 2px solid #eee;
        font-size: 14px;
        color: #444;

    }

    .reply-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
    }

    .reply-logo {
        width: 40px;
        /* small logo */
        height: 40px;
        object-fit: contain;
    }

    .reply-author {
        font-weight: 600;
        color: #5e2ced;
        /* purple like your theme */
    }

    .reply-body {
        display: flex;
        gap: 6px;
        align-items: flex-start;
    }

    .reply-arrow {
        color: #5e2ced;
        font-weight: bold;
        margin-top: 2px;
    }

    .reply-text {
        margin: 0;
        line-height: 1.4;
        font-size: 16px;
        text-align: left;
    }

    .read-more {
        color: #5e2ced;
        font-weight: 600;
        cursor: pointer;
        margin-left: 5px;
        text-decoration: none;
    }

    .read-more:hover {
        text-decoration: underline;
    }

    .reply-inline {
        display: inline;
        white-space: nowrap;
        /* keeps text + Read More together */
    }

    .slider-containers {
        max-width: 1200px;
        margin: auto;
        overflow: hidden;
        position: relative;
    }

    .cards-wrapper {
        height: auto;
        display: flex;
        transition: transform 0.5s ease;
        gap: 20px;
    }

    .slider-card {
        /* background: #fff; */
        background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
        /* light gradient */
        border: 2px solid #7744e0;
        border-radius: 12px;
        padding: 20px;
        flex: 0 0 100%;
        max-width: 100%;
        width: 100%;
        height: auto;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
    }

    .slider-card p {
        text-align: left;
        /* border-bottom:2px solid rgb(30, 25, 25); */
        color: #5b21b6;
        font-size: 25px;
        margin-bottom: 12px;
    }

    .slider-card p {
        font-size: 16px;
        line-height: 1.5;
        text-align: justify;
        color: #333;

    }

    .stars {
        text-align: left;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    .review-text {
        height: 150px;
        overflow-y: scroll;
        /* scrollbar-width: none; */
        -ms-overflow-style: none;
        padding: 10px;

    }

    .review-text::-webkit-scrollbar {
        width: 4px;
    }

    .review-text::-webkit-scrollbar-thumb {
        background-color: #9995a7;
        /* blue */
        border-radius: 4px;
    }

    /* Scrollbar Track (background) */
    .review-text::-webkit-scrollbar-track {
        background-color: #f1f1f1;
    }

    .location-text {
        width: 100px;
        padding: 5px;
        border-radius: 10px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #3b1e9e;
        color: #fff !important;
    }


    .review-details {
        display: flex;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        background: rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 10px;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-start;
        align-items: center;
        font-size: 14px;
        color: #333;
        margin: 10px 0;
    }

    .review-details p {
        margin: 0;
        padding: 8px 12px;
        background-color: #f1f1f1;
        border-radius: 8px;
        flex: 1 1 auto;
        text-align: center;
        min-width: 100px;
        color: #3b1e9d;

    }

    .controls {
        text-align: center;
        margin-top: 20px;
    }

    .controls button {
        padding: 10px 20px;
        background: #5b21b6;
        color: white;
        border: none;
        border-radius: 25px;
        margin: 0 10px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s;
    }

    .controls button:hover {
        background-color: #3f168f;
    }

    /* Responsive card width */
    @media (min-width: 768px) {
        .slider-card {
            flex: 0 0 calc((100% - 20px) / 2);
            max-width: calc((100% - 20px) / 2);
        }
    }

    @media (min-width: 1024px) {
        .slider-card {
            flex: 0 0 calc((100% - 40px) / 3);
            max-width: calc((100% - 40px) / 3);
        }
    }
</style>


<style>
    .custom-slider-viewport {
        overflow: hidden;
        width: 100%;
    }

    .custom-slider-tracks {
        display: flex;
        gap: 2px;
        transition: transform 0.4s ease;
    }


    .custom-cards {
        flex: 0 0 100%;
        padding: 20px;
        margin-right: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px #ddd;
        background: linear-gradient(to right, #3d1c9d, #5046a6, #1382c8);
        color: #fff;
    }

    @media (min-width: 768px) {
        .custom-cards {
            flex: 0 0 calc(50% - 20px);
        }
    }

    @media (min-width: 1024px) {
        .custom-cards {
            flex: 0 0 calc(33.333% - 20px);
        }
    }

    .first-letter-div {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        justify-content: center;
    }

    .first-letter {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 3.5vw;
        min-width: 40px;
        height: auto;
        min-height: 40px;
        padding: 2px;
        font-size: clamp(1.5rem, 2vw, 2.5rem);
        font-weight: bold;
        color: #fff;
        border-radius: 50%;
        background: linear-gradient(to right, #3d1c9d, #1382c8);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .subheading-p {
        font-size: clamp(1rem, 2vw, 1.25rem) !important;

        font-weight: bold;
        color: #fff;
        font-weight: 600;
        margin: 0;
    }

    .star-google {
        width: 100%;
        font-size: 14px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
        color: #f1c40f;
    }

    .review {
        color: #fff;
    }

    .review-date {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .google-nxt-pre-btn {
        text-align: center;
        margin-top: 20px;
    }

    .custom-slider-btn {
        padding: 10px 15px;
        background: #5b21b6;
        color: white;
        border: none;
        border-radius: 50px;
        margin: 0 5px;
        font-weight: bold;
        cursor: pointer;
    }

    .review-tabs {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        margin-bottom: 30px;
        flex-wrap: nowrap;
        overflow-x: scroll;
        width: 100%;
        padding-bottom: 10px;
    }

    .review-tabs::-webkit-scrollbar {
        display: none;
    }

    .tab-btn {
        position: relative;
        overflow: hidden;
        border: 1px solid transparent;
        border-radius: 10px;
        padding: 10px;
        background: transparent;
        cursor: pointer;
        flex: 0 0 auto;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .tab-btn::before {
        content: "";
        position: absolute;
        inset: 0;
        background-color: #fff;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 0;
        border-radius: 10px;
    }

    .tab-btn.active {
        border-color: #5e2ced;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .tab-btn.active::before {
        opacity: 1;
    }

    .tab-btn img {
        width: 120px;
        position: relative;
        z-index: 1;
    }

    .tab-btn:hover {
        transform: scale(1.05);
    }

    .review-tabs button {
        background: none;
        border: none;
        margin: 0 10px;
        cursor: pointer;

    }

    .review-tabs button img {}

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .slider-containers,
    .custom-slider-container {
        max-width: 1200px;
        margin: auto;
        overflow: hidden;
        position: relative;
    }

    .cards-wrapper,
    .custom-slider-track {
        display: flex;
        gap: 20.2px;
        transition: transform 0.4s ease;
    }

    .slider-card,
    .custom-card {
        flex: 0 0 100%;
        background: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px #ddd;
    }

    @media (max-width: 600px) {
        .tab-btn img {
            width: 80px;
        }
    }

    @media (max-width: 600px) {
        .review-tabs {
            justify-content: space-between;
        }

        .tab-btn {
            width: 48%;
            margin-bottom: 10px;
        }

        .tab-btn img {
            width: 100%;
        }
    }



    @media (min-width: 768px) {

        /* .review-card img {
        width: 100%;
        height: auto;
        max-height: 400px;
        border-radius: 10px;
        border:7px solid #00ac65;
        object-fit: cover;
     } */
        .slider-card,
        .custom-card {
            flex: 0 0 50%;
        }
    }

    @media (min-width: 1024px) {

        .slider-card,
        .custom-card {
            flex: 0 0 33.33%;
        }
    }

    .controls {
        text-align: center;
        margin-top: 15px;
    }

    .controls button,
    .custom-slider-btn {
        padding: 10px 15px;
        background: #5b21b6;
        color: white;
        border: none;
        border-radius: 50px;
        margin: 0 5px;
        font-weight: bold;
        cursor: pointer;
    }

    .slider-box {
        position: relative;
        max-width: 800px;
        margin: auto;
        overflow: hidden;
    }

    .slider-track {
        display: flex;
        transition: transform 0.4s ease-in-out;
    }

    .review-card {
        min-width: 100%;
    }

    .review-card img {
        width: 100%;
        height: auto;
        /* ✅ Adjusts height automatically */
        max-height: 400px;
        /* padding:10px; */
        border-radius: 10px;
        border: 7px solid #00ac65;
        object-fit: cover;
    }

    .slider-btns {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: #00a26975;
        color: #fff;
        font-size: 24px;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 50%;
    }

    .prev-btn {
        left: 10px;
    }

    .next-btn {
        right: 10px;
    }
</style>


<style>
    .chat-card {
        background: #fff;
        border: 1px solid #e3e3e3;
        border-radius: 12px;
        padding: 15px;
        text-aign: center;
        transition: transform 0.3s ease;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05);
    }

    .chat-card:hover {
        transform: translateY(-5px);
    }

    .chat-review-img {
        width: 100%;
        height: 250px;
        object-fit: contain;
        border-radius: 8px;
        margin-bottom: 12px;
        background: #f4f4f4;
        padding: 5px;
    }

    .chat-user-name {
        font-weight: 600;
        margin-bottom: 6px;
        font-size: 1rem;
    }



    .chat-review {
        font-size: 0.9rem;
        color: #555;
        line-height: 1.5;
    }

    .chat-nxt-pre-btn {
        text-align: center;
        margin-top: 20px;
    }

    .custom-slider-tracks {
        display: flex;
        gap: 24px;
        /* space between cards */
        transition: transform 0.3s ease;
    }

    .slider-item {
        flex: 0 0 340px;
        /* each card is 300px wide */
    }
</style>


<script>
    // Tab Switch Logic
    const tabButtons = document.querySelectorAll(".tab-btn");
    const tabContents = document.querySelectorAll(".tab-content");

    tabButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            tabButtons.forEach(b => b.classList.remove("active"));
            tabContents.forEach(c => c.classList.remove("active"));
            btn.classList.add("active");
            const id = btn.getAttribute("data-tab");
            document.getElementById(id).classList.add("active");
            moveAssignmentSlider(0);
            moveGoogleSlider(0);
        });
    });

    // assignnmentinneed Slider

    const wrapper = document.getElementById("cardWrappers");
    const cards = wrapper.children;
    const totalCards = cards.length;
    let currentIndexs = 0;

    function getVisibleCount() {
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 768) return 2;
        return 1;
    }

    function moveSlid(direction) {
        const visible = getVisibleCount();
        const cardWidth = cards[0].offsetWidth + 20;
        const maxIndex = totalCards - visible;

        currentIndexs += direction;

        if (currentIndexs < 0) currentIndexs = 0;
        if (currentIndexs > maxIndex) currentIndexs = maxIndex;

        const offset = currentIndexs * cardWidth;
        wrapper.style.transform = `translateX(-${offset}px)`;
    }

    window.addEventListener("resize", () => {
        moveSlid(0);
    });


    // Trustpilot Slider
    let trustIndex = 0;

    function changeTrustSlide(dir) {
        const track = document.getElementById("trustSliderTrack");
        const total = track.children.length;
        trustIndex = (trustIndex + dir + total) % total;
        track.style.transform = `translateX(-${trustIndex * 100}%)`;
    }

    // Google Slider (Sitejabber)
    const googleSlider = document.getElementById("googleSliderTrack");
    const googleCards = googleSlider.children;
    let googleIndex = 0;

    function getVisibleCount() {
        const width = window.innerWidth;
        if (width >= 1024) return 3;
        if (width >= 768) return 2;
        return 1;
    }

    function moveGoogleSlider(direction) {
        const perView = getVisibleCount();
        const cardWidth = googleCards[0].offsetWidth + 20; // 20 is the gap
        const max = googleCards.length - perView;

        googleIndex += direction;
        if (googleIndex < 0) googleIndex = 0;
        if (googleIndex > max) googleIndex = max;

        googleSlider.style.transform = `translateX(-${googleIndex * cardWidth}px)`;
    }

    window.addEventListener("resize", () => moveGoogleSlider(0));

    document.addEventListener("DOMContentLoaded", () => {
        moveGoogleSlider(0);
    });

    document.addEventListener("DOMContentLoaded", function () {
        document.addEventListener("click", function (e) {
            if (e.target && e.target.classList.contains("read-more")) {
                let fullText = e.target.getAttribute("data-full");
                let id = e.target.getAttribute("data-id");
                let replyBox = document.getElementById("reply-" + id);

                if (e.target.innerText === "Read More") {
                    replyBox.innerHTML = fullText +
                        ' <a href="javascript:void(0)" class="read-more" data-full="' + fullText + '" data-id="' + id + '">Read Less</a>';
                } else {
                    let shortText = fullText.substring(0, 100) + "...";
                    replyBox.innerHTML = shortText +
                        ' <a href="javascript:void(0)" class="read-more" data-full="' + fullText + '" data-id="' + id + '">Read More</a>';
                }
            }
        });
    });

    let secondSliderIndex = 0;

    function moveSecondSlider(direction) {
        const track = document.getElementById("secondSliderTrack");
        const cards = track.querySelectorAll(".slider-item");

        const cardWidth = cards[0].offsetWidth + 24; // card width + gap
        const visibleCards = Math.floor(track.parentElement.offsetWidth / cardWidth);
        const maxIndex = cards.length - visibleCards;

        secondSliderIndex += direction;

        if (secondSliderIndex < 0) secondSliderIndex = 0;
        if (secondSliderIndex > maxIndex) secondSliderIndex = maxIndex;

        track.style.transform = `translateX(-${secondSliderIndex * cardWidth}px)`;
    }



</script>

{{-- youtube tab --}}