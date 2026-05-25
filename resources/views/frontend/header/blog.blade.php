@extends('frontend-layouts.app')

@section('content')
	<style>
		/* -----------------------
				   General Reset Overrides
				--------------------------*/
		img {
			display: inline-block !important;
			max-width: 100%;
			height: auto !important;
		}

		/* -----------------------
				   Blog Header
				--------------------------*/
		.blog-header {
			position: relative;
			text-align: center;
			color: black;
		}

		.blog-header .cover {
			background: url('/assets/bg/blog-bg.png') no-repeat center center;
			background-size: cover;
			min-height: 210px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 30px;
			font-weight: 800;
			text-transform: uppercase;
			letter-spacing: 1.5px;
		}

		.blog-header .cover h1 {
			color: white;
			font-weight: 600;
		}

		/* -----------------------
				   Auto Container Wrapper
				--------------------------*/
		.auto-container1 {
			background: #fff;
			padding: 30px 20px;
			border-radius: 15px;
			position: relative;
			top: -135px;
			max-width: 1220px;
			margin: 0 auto;
		}

		/* -----------------------
				   Featured Blog Card
				--------------------------*/
		.featured-blog-card {
			background: #fff;
			border-radius: 12px;
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
			overflow: hidden;
			transition: transform 0.3s ease-in-out;
		}

		.featured-blog-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 12px 24px rgba(63, 38, 166, 0.25);
		}

		.featured-blog-img {
			position: relative;
			height: auto;
		}

		.featured-blog-img img {
			width: 100%;
			object-fit: contain;
			display: block !important;
		}

		.featured-blog-img .badge {
			position: absolute;
			top: 10px;
			right: 10px;
			background: #007bff;
			color: #fff;
			padding: 6px 12px;
			font-size: 12px;
			font-weight: bold;
			border-radius: 5px;
			transition: transform 0.4s ease, background-color 0.3s ease;
			transform: scale(1);
		}


		.featured-blog-content {
			padding: 20px;
		}

		.featured-blog-content h3 {
			font-size: 22px;
			font-weight: bold;
			color: #333;
			margin-bottom: 10px;
		}

		.featured-blog-title a {
			font-size: 30px;
		}

		/* -----------------------
				   Blog Card Styles
				--------------------------*/
		.blog-card {
			background-color: #fff;
			border-radius: 16px;
			box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
			overflow: hidden;
			transition: transform 0.3s ease, box-shadow 0.3s ease;
			position: relative;
		}

		.blog-card:hover {
			transform: translateY(-8px);
			box-shadow: 0 12px 24px rgba(63, 38, 166, 0.25);
		}

		.blog-card img {
			width: 100%;
			height: 220px;
			object-fit: cover;
			border-top-left-radius: 16px;
			border-top-right-radius: 16px;
			display: block;
		}

		.blog-card .p-3 {
			padding: 20px;
		}

		.blog-meta {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 10px;
			color: #6c757d;
			font-size: 13px;
			font-weight: 500;
		}

		.blog-meta img {
			width: 16px;
			height: 16px;
			margin-right: 5px;
			vertical-align: middle;
		}

		.blog-title {
			font-size: 20px;
			color: #2a2a2a;
			font-weight: 700;
			line-height: 1.3;
			margin-bottom: 10px;
		}

		.blog-title a {
			color: #2a2a2a;
			text-decoration: none;
			transition: color 0.3s;
		}

		.blog-title a:hover {
			color: #3F26A6;
		}

		.blog-description {
			font-size: 14px;
			color: #555;
			line-height: 1.5;
			background: #fff5d1;
			border-left: 3px solid #3F26A6;
			padding: 10px 15px;
			border-radius: 8px;
		}

	.banner-img {
	width: 100%;
	height: 300px;
	object-fit: cover;
	border-radius: 10px;
	margin-top: 1.5rem;
	box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 
	            0 6px 6px rgba(0, 0, 0, 0.23);
	transition: box-shadow 0.4s ease, transform 0.4s ease;
}

.banner-img:hover {
	box-shadow: inset 0 0 10px rgba(63, 38, 166, 0.5), 
	            0 10px 40px rgba(63, 38, 166, 0.3);
	transform: scale(1.02);
}


		/*------------------------
				   Animated Gradient Border
				--------------------------*/
		/* Wrapper to hold the animated gradient border */
		.animated-border-wrapper {
			padding: 0px;
			/* border thickness */
			border-radius: 18px;
			background: linear-gradient(135deg, #00f, #4b0082, #8a2be2);
			background-size: 300% 300%;
			animation: gradientBorderAnim 6s linear infinite;
			transition: transform 0.3s ease;
		}

		/* Card inside wrapper */
		.animated-border-wrapper .blog-card,
		.animated-border-wrapper .featured-blog-card {
			background: #fff;
			border-radius: 16px;
			overflow: hidden;
			transition: transform 0.3s ease;
		}

		/* Hover lift effect */
		.animated-border-wrapper:hover {
			transform: translateY(-6px);
		}

		.animated-border-wrapper:hover .featured-blog-img .badge {
			transform: scale(1.2) rotate(5deg);
			background-color: #3F26A6;
		}

		/* Animate gradient movement */
		@keyframes gradientBorderAnim {
			0% {
				background-position: 0% 50%;
			}

			50% {
				background-position: 100% 50%;
			}

			100% {
				background-position: 0% 50%;
			}
		}


		/* -----------------------
				   Buttons
				--------------------------*/
		.btn-read-more,
		.btn-learn-more {
			display: inline-block;
			background-color: #3F26A6;
			color: #fff;
			padding: 10px 20px;
			border-radius: 30px;
			font-size: 13px;
			font-weight: 600;
			text-align: center;
			text-decoration: none;
			transition: all 0.3s ease;
		}

		.btn-read-more:hover,
		.btn-learn-more:hover {
			background-color: #fff;
			color: #3F26A6;
			border: 2px solid #3F26A6;
		}

		/* -----------------------
				   Featured Blog (alt section)
				--------------------------*/
		.featured-blog {
			position: relative;
			border-radius: 10px;
			overflow: hidden;
		}

		.featured-blog img {
			height: 250px;
			object-fit: cover;
		}

		.featured-blog .card-body {
			padding: 20px;
			background: #f9f9f9;
		}

		.featured-blog h3 {
			font-size: 24px;
		}

		/* -----------------------
				   Pricing Card
				--------------------------*/
		.pricing-card {
			height: 100%;
			display: flex;
			align-items: center;
			justify-content: center;
			background: linear-gradient(45deg, #4c00ff, #0099ff);
			border-radius: 10px;
		}

		/* -----------------------
				   Responsive Media Queries
				--------------------------*/
		@media (max-width: 768px) {
			.banner-section-three {
				padding-bottom: 0;
			}

			.featured-blog-img {
				height: 180px;
			}

			.featured-blog-title a {
				font-size: 22px;
			}

			.blog-card h3 {
				font-size: 20px;
			}

			.btn-learn-more {
				width: 100%;
				padding: 12px;
			}

			.blog-header .cover {
				font-size: 22px;
				min-height: 150px;
			}

			.auto-container1 {
				padding: 20px;
			}

			.col-8,
			.col-4 {
				width: 100%;
			}

			.blog-meta {
				flex-direction: column;
				align-items: flex-start;
			}

			.banner-img {
				width: 100%;
				max-width: 100%;
				height: auto;
				
			}

			.sticky {
				position: relative !important;
			}
		}

		@media (max-width: 767px) {
			.banner-section-three {
				padding-bottom: 0;
			}

			.sidebar-section {
				display: none;
			}

			.auto-container {
				margin-top: 0px !important;
			}

			.featured-blog-img,
			.blog-img {
				max-width: 100%;
				height: 250px;
			}
		}

		@media screen and (min-width: 300px) and (max-width: 550px) {
			.auto-container1 {
				background-color: white;
				padding: 0 15px;
			}

			.inner-container {
				max-width: 100%;
				margin: 0 auto;
				padding: 10px;
				box-sizing: border-box;
			}

			.row {
				display: revert-layer;
			}

			.featured-blog-img {
				height: 150px;
			}
		}
	</style>

	<section>
		<div class="blog-header">
			<div class="cover">
				<h1>Blogs : Assignment in Need </h1>
			</div>
		</div>
	</section>

	<section>
		<div class="auto-container1" style="margin-top: 100px;">
			<!-- Page Breadcrumb -->


			<div class="row clearfix">
				<div class="inner-column">
					<div class="auto-container">
						<div class="inner-container">
							<div class="row">
								<!-- Blog Section (Full Width on Mobile, 8 columns on Desktop) -->
								<div class="col-12 col-md-8">
									<div class="row">
										<div class="col-12">
											@if($blogs->first())
												<div class="animated-border-wrapper">
													<div class="featured-blog-card">
														<div class="featured-blog-img">
															<a href="{{ url('/blog', $blogs->first()->slug) }}">
																<img src="{{ asset($blogs->first()->images) }}"
																	alt="{{ $blogs->first()->tittle }}">
															</a>
															<span class="badge pill-badge">Latest Blog</span>
														</div>
														<div class="featured-blog-content">
															<div class="blog-meta">
																<div>
																	<img src="assets/icons/icons8-blog-32.png" alt="Blog Icon"
																		class="me-1"> Blog
																</div>
																<div>
																	<img src="assets/icons/icons8-date-32.png" alt="Date Icon"
																		class="me-1">
																	{{ $blogs->first()->created_at->format('F j, Y') }}
																</div>
															</div>
															<h3 class="featured-blog-title">
																<a
																	href="{{ url('/blog', $blogs->first()->slug) }}">{{ $blogs->first()->tittle }}</a>
															</h3>
															<p class="blog-description">
																{{ Str::limit(strip_tags($blogs->first()->content), 50, '...') }}
															</p>
															<a href="{{ url('/blog', $blogs->first()->slug) }}"
																class="btn-learn-more">Learn More</a>
														</div>
													</div>
												</div>
											@endif
										</div>

										<!-- @foreach ($blogs->skip(1) as $blog)
											<div class="col-12 col-md-6 mt-4">
												<div class="animated-border-wrapper">
													<div class="blog-card">
														<a href="{{ url('/blog', $blog->slug) }}">
															<img src="{{ asset($blog->images) }}" alt="{{ $blog->tittle }}"
																class="{{ $blog->tittle }}">
														</a>
														<div class="p-3">
															<div class="blog-meta">
																<div>
																	<img src="assets/icons/icons8-blog-16.png" alt="Blog Icon"
																		class="me-1"> Blog
																</div>
																<div>
																	<img src="assets/icons/icons8-date-16.png" alt="Date Icon"
																		class="me-1">
																	{{ $blog->created_at->format('F j, Y') }}
																</div>
															</div>
															<h3 class="blog-title">
																<a
																	href="{{ url('/blog', $blog->slug) }}">{{ $blog->tittle }}</a>
															</h3>
															<p class="blog-description">
																{{ Str::limit(strip_tags($blog->content), 50, '...') }}
															</p>
															<a href="{{ url('/blog', $blog->slug) }}"
																class="btn-learn-more">Learn More</a>
														</div>
													</div>
												</div>
											</div>
										@endforeach -->
									</div>
									<!-- Pagination -->
									<div class="row" id="blog-container">
										{{-- First 5 blogs --}}
										@foreach ($blogs->skip(1) as $blog)
											@include('frontend.header.blog-cards', ['blogs' => [$blog]])
										@endforeach
									</div>

									<div class="text-center mt-4">
										<button id="load-more-btn" class="place-order-btn">Show More</button>
									</div>


								</div>

								<!-- Sidebar Section (Hidden on Mobile, Visible on Desktop) -->
								<div class="col-12 col-md-4 d-none d-md-block sidebar-section">
									<div class="row">
										<div class="col-12">
											@include('components.pricing-card')
										</div>
									</div>
									<a href="https://api.whatsapp.com/send/?phone=%2B447826233106&text&type=phone_number&app_absent=0">
										<div class="row sticky">
										<div class="col-12">
											<img class="banner-img" src="{{ asset('/images/whatsapp-order-now.webp') }}" alt="">
										</div>
									</div>
									
									</a>
								</div>
							</div>
						</div>
					</div> <!-- row -->
				</div> <!-- auto-container -->
			</div>
	</section>


	<script>
		let offset = 8; // Initial load count must match
		let limit = 8;

		document.getElementById('load-more-btn').addEventListener('click', function () {
			fetch("{{ url('/load-more-blogs') }}?offset=" + offset)
				.then(response => response.json())
				.then(data => {
					if (data.html) {
						document.getElementById('blog-container').insertAdjacentHTML('beforeend', data.html);
						offset += limit;
						if (!data.hasMore) {
							document.getElementById('load-more-btn').style.display = 'none';
						}
					}
				})
			.catch(err => console.error('Error loading blogs:', err));
		});
	</script>

@endsection