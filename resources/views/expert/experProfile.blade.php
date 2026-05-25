@extends('frontend-layouts.app')

@section('content')



    <style>
    .banner-section-three {
    position: relative;
    overflow: hidden;
    padding-bottom: 0px;
    box-shadow: 0 20px 20px rgba(0, 0, 0, 0.10);
}
        h1, h2, h3, h4, h5, h6,p{
            color: black;
        }
        p{
            font-size: 15px;
            color: black;
            text-align: justify;
        }
        h2{
            font-size: 2rem;
            font-weight: 500;
        }
        h6{
            font-weight: 500;
            font-size: 1rem;
            text-align: center;
        }
        main {
            max-width: 1350px;
            margin: 3rem auto;
            padding: 1rem;
            display: flex;
            gap: 2.5rem;

        }
        .wrapper-section{
            position: relative;
            z-index: 1;
            top:-65px
        }
        .profile-section
        {
            background-color: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: rgba(17, 17, 26, 0.1) 0px 4px 16px, rgba(17, 17, 26, 0.1) 0px 8px 24px, rgba(17, 17, 26, 0.1) 0px 16px 56px;
        }

        .profile-section {
            flex: 1;
        }

        .img-head {
            border: solid .2rem #5b21b6;
            border-radius: 50%;
            padding: .2rem;
        }

        .profile-card1{
            gap: 1rem;
        }
        .profile-top .stars{
            color:#fbbf24;
        }
        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1rem;
        }

        .profile-header img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }

        .profile-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: black;
        }

        .profile-header .stars {
            display: flex;
            gap: 0.25rem;
            color: #fbbf24;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            color: white !important;
            align-items: center;
            background: linear-gradient(180deg, hsla(262, 100%, 70%, 1) 0%, hsla(213, 100%, 66%, 1) 100%);
            max-width: 550px;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            margin: auto;
        }

        .skill-section1 {
            border: solid .2rem #5b21b6;
            border-radius: 1rem;
            padding: 1rem;
            margin: 2rem 0 !important;
        }
        .skill-section1 h2{
            font-size: 27px;
        }

        .stats div {
            text-align: center;
        }

        .stats div .number {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .helps-with,
        .about,
        .research,
        .reviews {
            margin-top: 1rem;
        }

        .skills .tags,
        .helps-with .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .skills .tags span,
        .helps-with .tags span {
            background-color: #5b21b6;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }

        .helps-with .tags span {
            background-color: #1d4ed8;
            color: white;
        }

        
        .reviews .review {
            margin-top: 0.5rem;
            color: #4b5563;
        }

        .research ul {
            list-style-type: disc;
            padding-left: 1.5rem;
        }

        .review-profile {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .reviews .review {
            border: solid .2rem #5b21b6;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }

        .reviews .review .stars {
            display: flex;
            gap: 0.25rem;
            color: #fbbf24;
        }

        .reviews .review .title {
            font-weight: bold;
        }
hr{
    background-color: #5b21b6;
}
        .sidebar {
            width: 100%;
            align-items: flex-end;
        }

        /* .sidebar .profile-card,
        .sidebar .ad-card {
            margin-top: 0rem;
        } */
        .profile-card
        {
            background: linear-gradient(180deg, #E2CDFF 0%, #D7F0FF 100%);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            margin-bottom: 1rem;
            border: solid .3rem #5b21b6;
            max-width: 95%;
        }
        .profile-top{
            display: flex;
            align-items: center;
            column-gap:1rem;
            margin-bottom: 1rem;
        }

        .profile-card img{
            border-radius: 50%;
            height: 72px;
            width: 72px;
            border:solid .2rem #5b21b6;
            padding: .2rem;
            row-gap: 1rem;
        }
        .profile-card h5{
            position: relative;
            text-align: center;
            color: black;
            font-weight: bold;
            margin: 0px;
            background: none;
        }
        .name-rating{
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .ad-card{
            max-width: 100%;
        }
        .sidebar .ad-card img {
            border-radius: 1rem;
            min-width: 95%;
        }

        .sidebar .profile-card button {
    width: 100%;
    background-color: #5b21b6;
    color: white;
    font-weight: bold;
    font-size: 1rem;
    padding: 0.5rem;
    border: none;
    border-radius: 0.5rem;
    margin-top: 1rem;
    cursor: pointer;
    transition: background 0.5s ease-in-out, transform 0.2s ease-in-out;
}

.sidebar .profile-card button:hover {
    background: linear-gradient(to bottom, #5b21b6, #1d4ed8);
    transform: scale(1.05);
}


        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 1rem auto;
            align-items: center;
        }
        .benefits-list li {
            display: flex;
            font-weight: 400;
            align-items: center;
            font-size: 17px;
            color:rgb(0, 0, 0);
            margin-bottom: 8px;
        }
        .benefits-list li i {
            color: #10b981;
            margin-right: 8px;
        }

        @media (min-width: 1024px) {
            .main {
                flex-direction: row;
            }

            .sidebar {
                width: 30%;
            }
        }
        /* Default styles remain unchanged, adding responsive tweaks */

@media (max-width: 1024px) {
    main {
        flex-direction: column;
        gap: 1rem;
    }
 
    .stats {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
    }
}

@media (max-width: 768px) {
    .profile-header h1 {
        font-size: 2rem;
        text-align: center;
    }
    h2 {
        font-size: 1.5rem;
    }
    p {
        font-size: 14px;
    }
    .profile-header img {
        width: 80px;
        height: 80px;
    }
    .stats div .number {
        font-size: 1rem;
    }
    .profile-card img {
        width: 60px;
        height: 60px;
    }
    .ad-card{
            display: none;
        }
}

@media (max-width: 480px) {
    .profile-section {
        padding: 1rem;
    }
    .sidebar .profile-card, .sidebar .ad-card {
        max-width: 100%;
    }
    .profile-card button {
        font-size: 0.9rem;
        padding: 0.4rem;
    }
    .benefits-list li {
        font-size: 14px;
    }
    .ad-card{
            display: none;
        }
}

@media (max-width: 320px) {
    .profile-header h1 {
        font-size: 1.5rem;
        text-align: center;
    }
    h2 {
        font-size: 1.2rem;
    }
    p {
        font-size: 12px;
    }
    .profile-header img {
        width: 70px;
        height: 70px;
    }
    .stats {
        align-items: center;
        margin: auto;
    }
    .profile-card img {
        width: 65px;
        height: 65px;
    }
    .profile-card button {
        font-size: 0.8rem;
        padding: 0.3rem;
    }
    .benefits-list li {
        font-size: 14px;
    }
    .wrapper-section {
    
    top: -50px;
}
.profile-card h5{
    font-size: 1rem;
}



}

@media (min-width: 300px) and (max-width: 1024px) {
    .banner-section-three {
        display: none !important;
    }
     .stats {
            display: flex;
            justify-content: space-between;
            color: white !important;
            align-items: center;
            background: linear-gradient(180deg, hsla(262, 100%, 70%, 1) 0%, hsla(213, 100%, 66%, 1) 100%);
            max-width: 550px;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            margin: auto;
        }
}





     
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />


    <section class="banner-section-three header-section">
        <div class="auto-container"">

                </div>
            </section>
            <main class=" main">
           
            <section class="profile-section">
            <div class="wrapper-section">
                <div class="profile-header">
                    <div class="img-head">
                        
                      <img src="{{ $expert->image ?? '/assets/media/avatars/blank.png' }}" 
                   alt="{{ $expert->name }}" />

                    </div>
                    <h1>{{$expert->name}}</h1>
                   @php
                        $reviews = json_decode($expert->customer_review, true) ?? [];
                        $totalReviews = count($reviews);
                        $averageRating = $totalReviews > 0 ? round(array_sum(array_column($reviews, 'rating')) / $totalReviews, 1) : 0;
                        $fullStars = floor($averageRating);
                        $halfStar = ($averageRating - $fullStars) >= 0.5 ? true : false;
                    @endphp
                    
                    <div class="stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $fullStars)
                                <i class="fas fa-star filled"></i>
                            @elseif ($halfStar && $i == $fullStars + 1)
                                <i class="fas fa-star-half-alt filled"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        <!--<span class="small text-muted">({{ $averageRating }}/5)</span>-->
                    </div>

                </div>
                <div class="stat-contain">
                    <div class="stats">
                        <div>
                            <div class="number">{{$expert->finish_order}}</div>
                            <div>Orders</div>
                        </div>
                        <div>
                            <div class="number">{{$expert->inprogress_order}}</div>
                            <div>In Progress</div>
                        </div>
                        <div>
                            <div class="number">{{$totalReviews}}</div>
                            <div>Reviews</div>
                        </div>
                         <div>
                            <div class="number">{{$expert->location}}</div>
                            <div>Location</div>
                        </div>
                          <div>
                            <div class="number">{{$expert->success}} %</div>
                            <div>Success Rate</div>
                        </div>
                    </div>
                    <div class="skill-section1">
                        <div class="skills">
                            <h2>Skills:</h2>
                            <div class="tags">
                                @foreach($skills as $skills)
                                <span>{{$skills}}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="helps-with">
                            <h2>Helps with:</h2>
                            <div class="tags">
                                @foreach($helpus as $helpus)
                                <span>{{$helpus}}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="about">
                        <h2>{{$expert->name}}</h2>
                        <p>{!!$expert->content!!}</p>
                    </div>

                    <div class="reviews">

                        <h2>Customer Reviews</h2>
                       @foreach($customerReview as $review)
                            <div class="review">
                                <div class="review-profile">
                                    <div class="title">{{ $review['name'] }}</div>
                        
                                   <div class="stars">
                                    @for($i = 1; $i <= $review['rating']; $i++)
                                        <i class="fas fa-star {{ $i <= $review['rating'] ? 'filled' : '' }}"></i>
                                    @endfor
                                </div>

                                </div>
                                <hr>
                                <p>{{ $review['review'] }}</p>
                            </div>
                        @endforeach

                        
                    </div>
                    </div>
            </section>
            <aside class="sidebar sticky-top">
                <div class="profile-card">
                    <div class="profile-header1">
                        <div class="profile-top">
                        <img src="{{$expert->image}}"
                            alt="{{$expert->name}}" />
                            <div class="name-rating">
                        <h5>{{$expert->name}}</h5>
                            @php
                            $reviews = json_decode($expert->customer_review, true) ?? [];
                            $totalReviews = count($reviews);
                            $averageRating = $totalReviews > 0 ? round(array_sum(array_column($reviews, 'rating')) / $totalReviews, 1) : 0;
                            $fullStars = floor($averageRating);
                            $halfStar = ($averageRating - $fullStars) >= 0.5 ? true : false;
                        @endphp
                        
                        <div class="stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $fullStars)
                                    <i class="fas fa-star filled"></i>
                                @elseif ($halfStar && $i == $fullStars + 1)
                                    <i class="fas fa-star-half-alt filled"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                        @endfor
                        <!--<span class="small text-muted">({{ $averageRating }}/5)</span>-->
                    </div>
                        </div>
                        </div>
                        <h6>Take advantage of these benefits at zero cost!</h6>
                    </div>
                    <div class="card-details">
                        
                    <ul class="benefits-list">
            <li><i class="fas fa-check-circle"></i>Qualified Writers</li>
            <li><i class="fas fa-check-circle"></i>Quality Writing with Zero AI</li>
            <li><i class="fas fa-check-circle"></i>Plagiarism Report</li>
            <li><i class="fas fa-check-circle"></i>Unlimited Revisions and Reworks</li>
        </ul>

                    </div>
                    <button>Order Now</button>
                </div>
                <div class="ad-card">
                    <img src="{{ asset('/images/side-banner.jpeg') }}"
                        alt="Advertisement for top-quality, 100% original assignments" />
                </div>
            </aside>
            </main>

@endsection