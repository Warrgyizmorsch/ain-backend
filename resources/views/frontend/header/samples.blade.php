@extends('frontend-layouts.app')

@section('content')
	<style>
		.banner-section-three .content-column .inner-column {
			padding-top: 0px;
		}
	</style>
	<section class="banner-section-three">
		<div class="pattern-layer-two" style="background-image: url(images/background/pattern-21.png)"></div>
		<div class="auto-container" style="margin-top: 100px;">
			<!-- Page Breadcrumb -->
			<div style="text-align: center;">
				<ul class="page-breadcrumb">
					<li><a href="/">Home</a></li>
					<li>Samples</li>
				</ul>
            </div>
            <div class="row clearfix">
				
				<!-- Content Column -->
				<div class="content-column col-lg-12 col-md-12 col-sm-12">
					<div class="inner-column">
						<div class="pattern-layer-one" style="background-image: url(images/main-slider/pattern-1.png)"></div>
						<div class="icon-layer" style="background-image: url(images/icons/icon-2.png)"></div>
						<div class="icon-layer-two" style="background-image: url(images/icons/icon-5.png)"></div>
						<section class="event-section-two mt-0 pt-0" style="background-image:url(images2/background/pattern-18.png)">
							<div class="icon-layer-one" style="background-image:url(images2/icons/icon-24.png)"></div>
							<div class="icon-layer-two" style="background-image:url(images2/icons/icon-25.png)"></div>
							<div class="auto-container">
							
								<!-- Sec Title Two -->
								<!-- <div class="sec-title-two centered">
									<div class="title">Our Event</div>
									<h2>Join Our Upcoming Event</h2>
								</div> -->
								<!-- End Sec Title Two -->
								
								<div class="inner-container">
									<div class="row clearfix">
										@foreach ($samples as $sample)
											<!-- Event Blog -->
											<div class="event-block col-lg-4 col-md-6 col-sm-12">
												<div class="inner-box wow fadeInLeft animated" data-wow-delay="0ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInLeft;">
													<ul class="post-meta">
														<li><span class="icon flaticon-map"></span>Sample</li>
														<li><span class="icon flaticon-timetable"></span>{{ $sample->created_at->format('F j, Y') }}</li>
													</ul>
													<h4><a href="{{ url('/sample', $sample->slug) }}">{{ substr(strip_tags($sample->tittle), 0, 20) }}....</a></h4>
													<!-- <div class="text">Affert nostro volumus id pri. Has purto mutat equidem ad. Istarum impulsu nostro volumus id pri. Has purto </div> -->
													<div class="text">{!! substr(strip_tags($sample->content), 0, 100) !!}...</div>

												    <div class="image" style="height:151px">
														<a a href="{{ url('/sample', $sample->slug) }}" ><img src="{{ asset($sample->images) }}" alt=""></a>
													</div>
												</div>
											</div>
										@endforeach
										
										
									</div>
									<div class="">
										{{ $samples->links('pagination::bootstrap-4')}}
									</div>
								</div>
							</div>
						</section>
					</div>
				</div>
				
				
				
			</div>
		</div>
	</section>
	
@endsection