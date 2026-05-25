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
                <li>Sample Single</li>
			</ul>
            </div>
            <div class="row clearfix">
				
				<!-- Content Column -->
				<div class="content-column col-lg-12 col-md-12 col-sm-12">
					<div class="inner-column">
						<div class="pattern-layer-one" style="background-image: url(images/main-slider/pattern-1.png)"></div>
						<div class="icon-layer" style="background-image: url(images/icons/icon-2.png)"></div>
						<div class="icon-layer-two" style="background-image: url(images/icons/icon-5.png)"></div>
						<section class="event-section-two pt-0 mt-0" style="background-image:url(images2/background/pattern-18.png)">
							<div class="icon-layer-one" style="background-image:url(images2/icons/icon-24.png)"></div>
							<div class="icon-layer-two" style="background-image:url(images2/icons/icon-25.png)"></div>
							<!-- Blog Detail Banner Section -->
    <section class="blog-detail-banner-section">
		<div class="pattern-layer-one" style="background-image: url(images/icons/icon-5.png)"></div>
		<div class="pattern-layer-two" style="background-image: url(images/icons/icon-6.png)"></div>
		<div class="pattern-layer-three" style="background-image: url(images/icons/icon-4.png)"></div>
		<div class="pattern-layer-four" style="background-image: url(images/icons/icon-8.png)"></div>
		<div class="auto-container">
			
			<div class="content-box pt-5">
				<div class="title">Education</div>
				<h2>{{$data['blog']['tittle']}}</h2>
				<div class="clearfix">
					<div class="pull-left">
						<div class="author-info">
							<div class="author-image">
								<img src="assets/media/avatars/assignment_logo.png" alt="" />
							</div>
							<strong>Post By Assignment In Need</strong>
							{{ \Carbon\Carbon::parse($data['blog']['updated_at'])->format('M d, Y') }}
						</div>
					</div>
					<div class="pull-right">
						<!-- Social Box -->
						<ul class="social-box">
							<span class="fa fa-share-alt"></span>
							<li class="twitter"><a target="_blank" href="http://twitter.com/" class="fa fa-twitter"></a></li>
							<li class="pinterest"><a target="_blank" href="http://pinterest.com/" class="fa fa-pinterest-p"></a></li>
							<li class="facebook"><a target="_blank" href="http://facebook.com/" class="fa fa-facebook-f"></a></li>
							<li class="dribbble"><a target="_blank" href="http://dribbble.com/" class="fa fa-dribbble"></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Blog Detail Banner Section -->
	
	<!--Sidebar Page Container-->
    <div class="sidebar-page-container style-two">
    	<div class="auto-container">
        	<div class="row clearfix">
				
				<!-- Content Side -->
                <div class="content-side col-lg-9 col-md-12 col-sm-12">
                	<div class="blog-detail">
						<div class="inner-box">
							
							<div class="blockquote-box">
								<div class="row clearfix">
									<div class="blockquote-column col-lg-6 col-md-6 col-sm-12">
										<div class="inner-column">
											<span class="quote-icon"><img src="images/icons/quote-icon-1.png" alt="" /></span>
											<p>{{$data['blog']['tittle']}} </p>
											<span class="quote-icon-two"><img src="images/icons/quote-icon-2.png" alt="" /></span>
										</div>
									</div>
									<div class="image-column col-lg-4 col-md-6 col-sm-12">
										<div class="inner-column">
											<div class="image">
												<img src="{{$data['blog']['images']}}" alt="" />
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="bold-text"><div class="bold-text">{!! $data['blog']['content'] !!}</div></div>
							
							<!--<div class="post-share-options">-->
							<!--	<ul class="tags">-->
							<!--		<li><span class="icon fa fa-tag"></span></li>-->
							<!--		<li><a href="#">Education</a></li>-->
							<!--		<li><a href="#">Course</a></li>-->
							<!--	</ul>-->
							<!--</div>-->
							
							<!-- Related Projects -->
						
							
							<!--<div class="comment-form">-->
									
							<!--	<div class="group-title"><h4>Leave A Comment</h4></div>-->
								
								<!--Comment Form-->
							<!--	<form method="post" action="https://themazine.com/html/Lebari_new/lebari/lebari/blog.html">-->
									
							<!--		<div class="form-group">-->
							<!--			<input type="text" name="username" placeholder="Your Name" required="">-->
							<!--		</div>-->
									
							<!--		<div class="form-group">-->
							<!--			<input type="email" name="email" placeholder="Email" required="">-->
							<!--		</div>-->
									
							<!--		<div class="form-group">-->
							<!--			<input type="text" name="topic" placeholder="Topic" required="">-->
							<!--		</div>-->
									
							<!--		<div class="form-group">-->
							<!--			<textarea class="" name="message" placeholder="Type your comment"></textarea>-->
							<!--		</div>-->
									
							<!--		<div class="form-group">-->
							<!--			<div class="check-box"><input type="checkbox" name="shipping-option" id="account-option"> &ensp; <label for="account-option">Save my name, email, and website in this browser for the next time I comment.</label></div>-->
							<!--		</div>-->
									
							<!--		<div class="form-group">-->
							<!--			<button class="theme-btn btn-style-one" type="submit" name="submit-form"><span class="txt">Post comment</span></button>-->
							<!--		</div>-->
									
							<!--	</form>-->
									
							<!--</div>-->
							
						</div>
					</div>
				</div>
				
				<!-- Sidebar Side -->
                <div class="sidebar-side col-lg-3 col-md-12 col-sm-12">
                	<aside class="sidebar sticky-top">
						
						<!-- Search -->
						<!-- <div class="sidebar-widget search-box">
							<form method="post" action="https://themazine.com/html/Lebari_new/lebari/lebari/contact.html">
								<div class="form-group">
									<input type="search" name="search-field" value="" placeholder="Search" required>
									<button type="submit"><span class="icon fa fa-search"></span></button>
								</div>
							</form>
						</div> -->
						
						<!-- Popular Post Widget -->
						<div class="sidebar-widget popular-posts">
							<div class="sidebar-title">
								<h4>Recent Post</h4>
							</div>
							<div class="widget-content">
                                @foreach( $data['recent_post'] as $post)
								<article class="post">
									<figure class="post-thumb"><img src="{{$post['images']}}" alt=""><a href="sample/{{$post['slug']}}" class="overlay-box"><span class="icon fa fa-link"></span></a></figure>
									<div class="post-info">{{ \Carbon\Carbon::parse($post['updated_at'])->format('M d, Y') }}</div>
									<div class="text"><a href="sample/{{$post['slug']}}">{{ Illuminate\Support\Str::limit($post['tittle'], 35) }}</a></div>
								</article>
                                @endforeach
								
							</div>
						</div>
						
						<!-- Links Widget -->
						<!--<div class="sidebar-widget links-widget">-->
						<!--	<div class="sidebar-title">-->
						<!--		<h4>Recent Post</h4>-->
						<!--	</div>-->
						<!--	<div class="widget-content">-->
						<!--		<ul class="blog-cat">-->
						<!--			<li><a href="#">Business (3)</a></li>-->
						<!--			<li><a href="#">Education  (03)</a></li>-->
						<!--			<li><a href="#">Technology (12)</a></li>-->
						<!--			<li><a href="#">News (6)</a></li>-->
						<!--			<li><a href="#">Design (4)</a></li>-->
						<!--			<li><a href="#">Lifestyle  News (6)</a></li>-->
						<!--		</ul>-->
						<!--	</div>-->
						<!--</div>-->
						
						<!-- Archive Widget -->
						<!--<div class="sidebar-widget archive-widget">-->
						<!--	<div class="sidebar-title">-->
						<!--		<h4>Archives Post</h4>-->
						<!--	</div>-->
						<!--	<div class="widget-content">-->
								<!-- Form Group -->
						<!--		<div class="form-group">-->
						<!--			<select name="country" class="custom-select-box">-->
						<!--				<option>February 2020</option>-->
						<!--				<option>February 2019</option>-->
						<!--				<option>February 2018</option>-->
						<!--				<option>February 2017</option>-->
						<!--				<option>February 2016</option>-->
						<!--			</select>-->
						<!--		</div>-->
								<!-- Form Group -->
						<!--		<div class="form-group">-->
						<!--			<select name="country" class="custom-select-box">-->
						<!--				<option>February 2019</option>-->
						<!--				<option>February 2018</option>-->
						<!--				<option>February 2017</option>-->
						<!--				<option>February 2016</option>-->
						<!--			</select>-->
						<!--		</div>-->
						<!--	</div>-->
						<!--</div>-->
						
						<!-- Popular Posts -->
						<!--<div class="sidebar-widget popular-tags">-->
						<!--	<div class="sidebar-title">-->
						<!--		<h4>Tags</h4>-->
						<!--	</div>-->
						<!--	<div class="widget-content">-->
						<!--		<a href="#">seafood</a>-->
						<!--		<a href="#">gyros</a>-->
						<!--		<a href="#">chicken</a>-->
						<!--		<a href="#">pizza</a>-->
						<!--		<a href="#">ecommerce</a>-->
						<!--		<a href="#">food</a>-->
						<!--	</div>-->
						<!--</div>-->
						
					</aside>
				</div>
				
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