@extends('frontend-layouts.app')

@section('content')


  <style>
    .main-aside-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    /* Main content 2/3, aside 1/3 */
    gap: .5rem;
    max-width: 1300px;
    margin: auto;
    padding: 1rem;
    }

    @media (max-width: 768px) {
    .main-aside-container {
      grid-template-columns: 1fr;
      /* Stack content and aside */
    }
    }

    .main-content {
    /* flex: 1; */
    width: 100%;
    }

    /* Filters Styling */
    .filters {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
    padding: 10px 0;
    border-radius: 12px;
    margin-bottom: 20px;
    }

    .filters select,
    .search-bar input {
    padding: 12px;
    font-size: 12px;
    height: 45px;
    border: 1.5px solid #ccc;
    border-radius: 8px;
    background-color: #f0f4ff;
    flex-grow: 1;
    }

    .search-bar {
    display: flex;
    flex-grow: 1;
    max-width: 400px;
    }

    .search-bar button {
    padding: 8px 16px;
    border: none;
    background: linear-gradient(to right, #6a5af9, #3f9aff);
    color: white;
    cursor: pointer;
    border-radius: 0 8px 8px 0;
    }

    .search-bar input {
    border-radius: 8px 0 0 8px;
    border-right: none;
    }

    .search-bar button i {
    font-size: 16px;
    }

    /* Sample Cards */
    .sample-card {
    background: #ffffff;
    border: 2px solid #eee;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    color: inherit;
    text-decoration: none;
    }

    .sample-card:hover {
    background: linear-gradient(white, white) padding-box,
      linear-gradient(135deg, #6e40c9, #007fff) border-box;
    border: 2px solid transparent;
    box-shadow: 0 8px 24px rgba(60, 70, 200, 0.2);
    transform: translateY(-4px);
    }


    .sample-card .sample-title {
    margin: 0 0 10px;
    font-size: 22px;
    font-weight: bold;
    color: black;
    }


    .sample-info {
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
    }

    .download-link {
    /* display: inline-block; */
    margin-top: 10px;
    color: #45119E;
    font-weight: 600;
    text-decoration: none !important;
    }

    .download-link:hover {
    text-decoration: underline;
    }

    .modern-banner {
    padding: 1rem;
    border-radius: 12px;
    }

    .offer-strip {
    text-align: center;

    img{
      max-width: 100%;
      border-radius: 10px;
      margin-bottom: 1rem;
    }

    .offer-text h3 {
      font-size: 1.5rem;
      font-weight: 700;
      color: #ff5722;
    }

    .offer-text span {
      color: #d32f2f;
    }

    .offer-text p {
      color: #555;
      font-weight: 500;
    }

    .btn-primary {
      background: linear-gradient(90deg, #ff7043, #ff5722);
      border: none;
      padding: 10px 20px;
      color: white;
      font-weight: 600;
      border-radius: 6px;
      cursor: pointer;
    }
    }

    .expert-highlight {
    margin-top: 2rem;
    background: linear-gradient(white, white) padding-box,
      linear-gradient(180deg, #45119E, #0182CB) border-box;
    border: 0.2rem solid transparent;
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
    animation: fadeIn 0.5s ease-in-out;

    h3 {
      font-size: 1.25rem;
      font-weight: 600;
      color: #000;
    }

    .subtext {
      font-size: 0.7rem;
      color: #333;
      margin-bottom: 1rem;
    }

    .expert-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .expert-card {
      display: flex;
      gap: 1rem;
      align-items: center;
      background: #f9f9f9;
      padding: 0.75rem;
      border-radius: 8px;
    }

    img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }

    .info h4 {
      font-size: 1rem;
      font-weight: 600;
      margin: 0;
    }

    .rating {
      color: #fbbc05;
      font-size: 0.9rem;
    }

    p {
      font-size: 0.8rem;
      margin: 0.25rem 0;
    }

    .btn-secondary {
      background: #6e40c9;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 0.8rem;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-secondary:hover {
      background: #5a33a8;
      transform: translateY(-2px);
    }
    }


    .bonus-offer {
    margin-top: 2rem;
    text-align: center;

    .highlight {
      font-weight: 600;
      color: #2e7d32;
      margin-bottom: 0.5rem;
    }

    .btn-outline {
      border: 1px solid #2e7d32;
      background: transparent;
      color: #2e7d32;
      padding: 8px 16px;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
    }
    }

    aside.banner {
    width: 100%;
    order: -1;
    }
    }
  </style>


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





    /* Medium devices (large phones / small tablets, 600px - 767px) */
    @media (max-width: 767px) {
    .logo-img {
      width: 70px;
      height: 70px;
    }

    .primary-button {
      font-size: 1rem;
      padding: 0.7rem 1.3rem;
    }

    .icon-item img {
      width: 50px;
      height: 50px;
    }

    .icon-item p {
      font-size: 0.8rem;
    }
    }

    /* Small devices (phones, 400px - 599px) */
    @media (max-width: 599px) {
    .header-content img {
      display: none;
    }

    .logo-img {
      width: 60px;
      height: 60px;
    }

    .primary-button {
      font-size: 0.9rem;
      padding: 0.6rem 1.2rem;
    }


    .icon-item p {
      font-size: 0.75rem;
    }
    }

    /* Extra small devices (phones, < 400px) */
    @media (max-width: 399px) {
    .sample-card h2 {
      font-size: 1rem;
    }

    .sample-info {
      font-size: 0.7rem;
    }

    .primary-button {
      font-size: 0.9rem;
      padding: 0.5rem 1rem;
    }

    .logo-img {
      width: 50px;
      height: 50px;
      right: 10px;
    }



    .icon-item p {
      font-size: 0.7rem;
    }
    }
  </style>



  <div class="head-container mt-6 px-4 py-6">
    <div class="header-content">
    <img src="assets/media/avatars/assignment_logo.png" alt="Assignment Logo" class="logo-img" />
    <h1 class="section-title">Free {{ $categoryName }} Accounting Assignment Samples</h1>
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


  <section class="section-sample">
    <div class="main-aside-container">
    <!-- Main Content -->
    <div class="main-content">
      <!-- Filters -->
      <div class="filters">
      <select>
        <option>Choose the Academic Paper Type</option>
        <option>Case Study</option>
        <option>Research Paper</option>
        <option>Essay</option>
      </select>
      <div class="search-bar">
        <input type="text" placeholder="Enter a keyword">
        <button><i class="fas fa-search"></i></button>
      </div>
      </div>


      <!-- Sample Cards (Laravel loop) -->
      <div class="sample-list" id="sample-list">
       @include('sample.partials.sample-list')
      </div>
      @if ($totalCount > 5)
  <div class="text-center mt-4">
    <button id="loadMore" class="primary-button mt-4">Show More</button>
  </div>
@endif


    </div>

    <!-- Aside Banner Section -->
    <aside class="modern-banner">
      <div class="offer-strip">
      <img src="/images/offer-banner-vertical.png" alt="Seasonal Offer" />
      <div class="offer-text">
        <h3>UP TO <span>40% OFF</span></h3>
        <p>Save your grades & your wallet!</p>
        <a href="upload-your-assignment" class="btn-primary">Order Now</a>
      </div>
      </div>

      <div class="expert-highlight">
      <h3>{{ $expert->count() }}+ Expert Writers</h3>
      <p class="subtext">Top Professionals in Accounting Help</p>

      <div class="expert-list">
        @foreach ($expert->take(3) as $exp)
        @php
      $reviews = json_decode($exp->customer_review, true);
      $totalReviews = is_array($reviews) ? count($reviews) : 0;
      $averageRating = $totalReviews > 0 ? round(array_sum(array_column($reviews, 'rating')) / $totalReviews, 1) : 0;
      @endphp
        <div class="expert-card">
        <img src="{{ $exp->image ? asset($exp->image) : asset('assets/media/avatars/blank.png') }}"
        alt="{{ $exp->name }}">
        <div class="info">
        <h4>{{ $exp->name }}</h4>
        <div class="rating">
        @for ($i = 1; $i <= 5; $i++)
        <span class="star {{ $i <= $averageRating ? 'checked' : '' }}">★</span>
      @endfor
        </div>
        <p>{{ $exp->finish_order }} - Completed Orders</p>
        </div>
        </div>
        <a href="{{ url('writers/' . $exp->slug) }}" class="btn-secondary">
        Hire Now
        </a>

      @endforeach
      </div>
      </div>


    </aside>

    </div>
  </section>


<script>
  let offset = 5;
  const loadMoreBtn = document.getElementById('loadMore');
  const sampleList = document.getElementById('sample-list');

  loadMoreBtn?.addEventListener('click', function () {
    fetch(`${window.location.pathname}?offset=${offset}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.text();
    })
    .then(html => {
      sampleList.insertAdjacentHTML('beforeend', html);
      offset += 5;

      // Hide button if all items are loaded
      if (offset >= {{ $totalCount }}) {
        loadMoreBtn.style.display = 'none';
      }
    })
    .catch(error => {
      console.error('Failed to load more samples:', error);
    });
  });
</script>
@endsection