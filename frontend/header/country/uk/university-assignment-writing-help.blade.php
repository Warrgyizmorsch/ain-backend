@extends('frontend-layouts.app')

@section('content')
   <style>
		.text {
			color: #0c0d24;
			line-height: 1.8em;
			font-size: 16px;
		}
		.text-black{
			color: black;
			
		}
		.banner-section-three .content-column .inner-column {
			padding-top: 5px;
		}
		
	</style>
      <style>
		    .iti {
			position: relative;
			display: inline-block;
			width: 100%;
		   }
	</style>

	<style>
		.header-section 
		{
			background: rgb(255,255,255);
			background: linear-gradient(170deg, rgba(255,255,255,1) 6%, rgba(135,166,219,0.4009978991596639) 72%, rgba(135,166,219,0.5690651260504201) 91%, rgba(126,137,221,0.865983893557423) 100%);
		}

		h1 {
			font-size: 35px;
			font-weight: 600;
			color: black
			}

		p {
			position: relative;
			line-height: 1.8em;
			font-size: large;
			color:black;
			text-align: justify;
		}
		.place-order 
		{
			background:#d7f0fd; 
			color:black;
			padding: 10px 20px;
			border-radius: 5%;
			margin: 10px;
		}
		.place-now
		{
			background:#77bfe5; 
			color:black;
			padding: 20px 80px;
			border-radius: 3%;
			margin: 10px;
			font-weight: 500;
			font-size: 20px;
		}
		.place-order:hover
		{
			background:#7e89dd;
			color:white ;

		}
		.place-now:hover
		{
			box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;			
			color: white;
			transition: box-shadow 0.4s ease-in-out, color 0.3s ease-in-out; /* Smooth transition */

		}
		.order-now		
		{
			font-size: 25px;
			font-weight: 500;	
			color: black;
		}

		.offer-badge {
		    position: absolute;
			top: -65px;
			right: -30px;
			color: white;
			font-weight: bold;
			border-radius: 10%;
			font-size: 17px;
			z-index: 0;
		}
		.banner-stats-title
		{
			font-size: 30px;
			font-weight: 600;
			color: black;
		}
		.banner-stats-container {
			display: flex;
			justify-content: space-between;
			text-align: center;
		}

		.banner-stat {
			flex: 1;
			padding: 0 10px; /* Adjust the space between elements */
		}

		.banner-stats-text {
			font-size: 1em;
			margin-top: 5px;
		}
		
		ul 
		{
			font-size: 17px;
			color: black;
		}

		h3 
		{
			font-size: 21px;
			font-weight: 500;
			color: black;
		}

		@media (min-width: 768px) {
		.content {
			flex-direction: row;
			align-items: flex-start;
			justify-content: center;
			text-align: left;
		}

			.image-container {
				width: 50%;
			}

			.text-content {
				width: 50%;
				margin-left: 40px;
			}

			.text-content h2 {
				font-size: 2rem;
			}
		}

		.current_offer
		{
			font-weight: bold;
			font-size: 35px;
		}

		.offer-container
		{
			background: rgb(221,245,245);
			background: -moz-linear-gradient(275deg, rgba(221,245,245,1) 0%, rgba(175,214,232,0.23012955182072825) 10%, rgba(155,205,231,0.43461134453781514) 68%, rgba(110,186,231,1) 100%);
			background: -webkit-linear-gradient(275deg, rgba(221,245,245,1) 0%, rgba(175,214,232,0.23012955182072825) 10%, rgba(155,205,231,0.43461134453781514) 68%, rgba(110,186,231,1) 100%);
			background: linear-gradient(275deg, rgba(221,245,245,1) 0%, rgba(175,214,232,0.23012955182072825) 10%, rgba(155,205,231,0.43461134453781514) 68%, rgba(110,186,231,1) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ddf5f5",endColorstr="#6ebae7",GradientType=1);
						background-color: white; 
			border-radius: 
			5px; 
			box-shadow: 0 1rem 1rem 0 rgba(0, 0, 0, .08); 
			padding: 20px;
		}

		@media (min-width: 1200px) {
    .container {
        max-width: 1199px;
    }
}
	.offer-badge-offer {
		position: absolute;
		top: -8px;
		right: 23px;
		color: white;
		font-weight: bold;
		border-radius: 10%;
		font-size: 17px;
		z-index: 0;
	}
	.offer-badge-offer:hover{

		box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
				transition: box-shadow 0.4s ease-in-out, color 0.3s ease-in-out; /* Smooth transition */
	}

	.testimonial-section-three .owl-carousel .owl-stage-outer {
    padding: 0px 0px 0px;
}

      
	  /* newcode */
         /* Style the image with rounded corners */
	  /* .rounded-image {
		border-radius: 20px;
	} */

	.subject-container {
		background-color: #fff;
		/* box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px; */

		padding: 20px;
		border-radius: 10px;
		margin-bottom: 20px;
		 
	}

	.subject-image {
		border-radius: 50%;
		max-width: 100%;
		height: auto;
	}

	.subject-list-box {
		background: rgb(0, 127, 193);
		background: linear-gradient(281deg, rgba(0, 127, 193, 0.5718662464985995) 11%, rgba(71, 199, 204, 1) 60%, rgba(0, 127, 193, 1) 100%);
		
		/* box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px; */
		padding: 20px;
		border-radius: 15px;

	}

    

	.subject-list {
		list-style: none;
		padding-left: 0;
	}

	.subject-list li {
		margin-bottom: 10px;
		font-size: 16px;
		color: white;
	}

	.subject-list li i {
		color: white;
		margin-right: 8px;
	}

	/* end new code */


	</style>
 
 
<!-- title -->
<section class="banner-section-three header-section">
	<div class="auto-container" style="margin-top: 100px;">
		<div style="text-align: center;">
			<ul class="page-breadcrumb">
				<!-- <li><a href="/">Home</a></li>
				<li>Expert Homework Help for UK Students</li> -->
			</ul>
		</div>
		<div class="row clearfix">
			<div class="content-column col-lg-6 col-md-12 col-sm-12">
				<div class="inner-column">
					<h1>Get All Types of University Assignment Writing Help For UK Students</h1>
				</div>
				<div>
				</div>
				<div class="mt-2" style="padding: 20px;">
					<div class="banner-stats-container">
						<div class="banner-stat">
							<div class="banner-stats-title">98.2%</div>

							<div class="banner-stats-text"><i class="fa fa-star"></i> Orders Arrive Timely</div>
						</div>
						<div class="banner-stat">
							<div class="banner-stats-title">9/10</div>

							<div class="banner-stats-text"><i class="fa fa-graduation-cap"> </i> Report Better Grades
							</div>
						</div>
					</div>
				</div>


				<div class="mt-2"
					style="background-color: white; border-radius: 5px; box-shadow: 0 1rem 1rem 0 rgba(0, 0, 0, .08); padding: 20px;">
					<div style="display: flex; align-items: center;">
						<div style="width: 50px;">
							<img src="assets/media/avatars/assignment_logo.png" alt="Client Logo"
								style="max-width: 100%;">
						</div>
						<!-- Second Section: Review Banner -->
						<div style="flex-grow: 1; margin-left: 20px;">
							<div style="display: flex; align-items: center;">
								<div style="flex-grow: 1;">
									<span style="font-size: 20px; font-weight: bold;">Client Reviews </span>
								</div>
								<div style="display: flex; align-items: center;">
									<!-- Star Rating -->
									<span style="font-size:20px; margin-right: 10px;">
										<i style="color:gold" class="fa fa-star"></i>
										<i style="color:gold" class="fa fa-star"></i>
										<i style="color:gold" class="fa fa-star"></i>
										<i style="color:gold" class="fa fa-star"></i>
										<i style="color:gold" class="fa fa-star-half-o"></i> <!-- Half-active star -->
									</span>
									<!-- Rating Number -->
									<span style="font-size: 20px; font-weight: bold; color: #333;">4.5 / 5
										Ratting</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			@include('formsection')

</section>
<!-- Our Procedure -->
<section class="news-section-two py-3 mt-3">
	<div class="auto-container">
		<div class="row clearfix">
			<div class="col-md-12 col-md-offset-2">
				<h2 style="font-weight:500; font-size: 30px;; color:black" class="text-center my-4">Our Procedure</h2>
				<p class="textCommon text-center">How Our Quality Assignment Writing Services Work in Assignment ?</p>
			</div>
			<div class="blocks-column col-lg-12 col-md-12 col-sm-12 mt-2">
				<div class="inner-column">
					<div class="row clearfix p-2">
						<div class="column col-lg-4 col-md-4 col-sm-12 ">
							<div class="news-block-four mt-0">
								<div class="inner-box wow fadeInUp animated animated" data-wow-delay="0ms"
									data-wow-duration="1500ms"
									style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
									<span style="display: inline-block; width: auto; height: 150px;">
										<img src="images/shopping-list.png" alt="" style="width: 100%; height: 100%;">
									</span>
									<h3><a>Submit Your Order</a></h3>
									<div class="text">Fill in the 'order now' form, mention your basic information and
										specific requirements that you want us to meet.</div>

								</div>
							</div>
						</div>
						<div class="column col-lg-4 col-md-4 col-sm-12">
							<div class="news-block-four">
								<div class="inner-box wow fadeInUp animated animated" data-wow-delay="150ms"
									data-wow-duration="1500ms"
									style="visibility: visible; animation-duration: 1500ms; animation-delay: 150ms; animation-name: fadeInUp;">
									<span style="display: inline-block; width: auto; height: 150px;">
										<img src="images/secure-payment.png" alt="" style="width: 100%; height: 100%;">
									</span>
									<h3><a>Make Secure Payment</a></h3>
									<div class="text">Pay an affordable price for the assignment help provided to you
										via our secure payment gateway that is fully protected from privacy
										infringements.</div>
								</div>
							</div>

						</div>
						<div class="column col-lg-4 col-md-4 col-sm-12">
							<div class="news-block-four">
								<div class="inner-box wow fadeInUp animated animated" data-wow-delay="300ms"
									data-wow-duration="1500ms"
									style="visibility: visible; animation-duration: 1500ms; animation-delay: 300ms; animation-name: fadeInUp;">
									<span style="display: inline-block; width: auto; height: 150px;">
										<img src="images/sharing.png" alt="" style="width: 100%; height: 100%;">
									</span>
									<h3><a>Receive Your Paper</a></h3>
									<div class="text">
										Get a high-quality assignment writing services by our expert writers within the
										given deadline and score better than your expectations.
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<!-- Claim Your Offer -->
<section class=" pt-3 pb-3">
	<div class="content-section-white">

		<div class="container">

			<div class="offer-container row pb-0">

				<div class="col-md-4 col-xs-12 mb-3">
				</div>
				<div class="col-md-6 col-xs-12 mb-3 test">

					<h2 style="font-weight:500; font-size: 30px;; color:black">Claim Your Offer</h2>
				</div>
				<div class="col-md-4 col-xs-12">
					<div class="offer-badge-offer"><img src="assets/media/avatars/offer.png" alt=""></div>
					<div style="heught:100px" class="offer-text hidden-xs"><img
							src="assets/media/avatars/fashionable-young-man-with-stubble-has-surprised-expression.png"
							alt=""></div>
				</div>
				<div class="col-md-6 col-xs-12 mt-3">

					<p class="offer-title">Type your whatsapp number to get an exclusive code. </p>
					<div class="clearfix"></div>
					<span id="offerWhatsappSuccessMsg2"></span>
					<div class="offer-input-box">
						<form class="onload-offer-form" id="offerWhatsappForm2"
							onkeydown="return event.key != 'Enter';">
							<div class="contact-right-container">
								<div class="form-group d-flex">
									<div class="col-sm-2" style="padding-left:0;padding-right:0">
										<input type="text" id="isdCode2" style="border-left: 2px solid #ccc;"
											placeholder="+1" class="form-control">
									</div>
									<div class="col-sm-10" style="padding-left:0;padding-right:0">
										<input type="text" name="offerWhatsappNumber" id="offerWhatsappNumber2"
											class="form-control" placeholder="Enter Your Whats App No.">
									</div>
									<button type="button" id="offerWhatsappBtn2" class="btn btn-secondary btn-bg-red"
										style="background:#33b533"><i class="fa fa-whatsapp"></i> </button>
								</div>
							</div>
						</form>
						<div style="text-center">
							<a href="/Offers"><button class=" place-now">View More Offer</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Our Writer -->
!-- Our Writer -->
 @if($data['expert']->isEmpty())
    
@else
    <!-- Case: Experts are available -->
    <section class="testimonial-section-three py-0">
        <div class="color-layer"></div>
        <div class="auto-container">
            <div class="sec-title">
                <div class="section-color-layer"></div>
                <h2 style="font-weight:500; font-size: 30px; color:black">
                Our Writer For University Assignment help
                </h2>
            </div>
            <div class="testimonial-carousel-three owl-carousel owl-theme">
                @foreach($data['expert'] as $expert)
                    <!-- Testimonial Block Three -->
                    <div class="testimonial-block-three">
                        <div class="inner-box">
                            <div class="image-outer">
                                <div class="image">
                                    <img src="{{$expert->image}}" alt="Expert Image" />
                                </div>
                                <div class="quote flaticon-left-quote"></div>
                            </div>
                            <h6>{{$expert->name}}</h6>
                            <div class="designation" style="
                                color: blue !important;
                                margin: 0 auto;
                                text-align: center;
                                width: auto;
                                padding: 5px 15px;
                                border-radius: 20px;
                                font-size: 20px;
                                font-weight: 700;">
                                {{$expert->service}}
                            </div>
                            <div class="text" style="text-align: justify; margin-top: 10px;">
                                <span class="short-content">
                                    {{ Str::limit($expert->content, 150) }}
                                </span>
                                <span class="full-content" style="display: none;">
                                    {{$expert->content}}
                                </span>
                                <button class="btn-toggle" onclick="toggleContent(this)" style="
                                    margin-top: 5px;
                                    border: none;
                                    background-color: transparent;
                                    color: #007BFF;
                                    cursor: pointer;">
                                    ...
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="lower-text">Click the button for slide</div>
        </div>
    </section>
@endif

	<script>
    function toggleContent(button) {
        const textContainer = button.parentElement;
        const shortContent = textContainer.querySelector('.short-content');
        const fullContent = textContainer.querySelector('.full-content');

        if (shortContent.style.display === 'none') {
            shortContent.style.display = '';
            fullContent.style.display = 'none';
            button.textContent = '...';
        } else {
            shortContent.style.display = 'none';
            fullContent.style.display = '';
            button.textContent = 'Less';
        }
    }
</script>


<!-- Online University Writing Help Services  -->
<section class=" pt-3 pb-3">
	<div class="auto-container ">
		<div class="row clearfix">
			<div class="title-column col-lg-12 col-md-12 col-sm-12">
				<div class="inner-column">
					<div class="title-box">
						<div class="section-color-layer"></div>
						<h2 style="font-weight:500; font-size: 30px;; color:black" class="  my-4">Online University Writing Help Services </h2>
						 <P>University assignments can feel like a maze. You want to keep those grades high in various subjects. At Assignment In Need, we are here to lighten your load. We truly know the ins and outs of student struggles. Providing solid  <a href="https://www.assignnmentinneed.com/uk"><b>assignment help</b></a> to students in UK universities is our focus. For those seeking <b>university essay writing help services in UK</b> , cities like Coventry, Peterborough, and Preston trust us to deliver. Each service gets tailored to fit your specific needs. Our aim? To help you navigate the academic world smoothly while reaching for your goals.</P>
                        </div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- order box -->
<section class=" pt-3 pb-3">
	<div class="auto-container ">
		<div class="my-5 images-container"
			style="position: relative; border-radius: 5px; overflow: hidden; box-shadow: 0 1rem 1rem 0 rgba(0, 0, 0, 0.08);">
			<div
				style="background: linear-gradient(275deg, rgba(221,245,245,1) 0%, rgba(175,213,232,0.23012955182072825) 10%, rgba(155,205,231,0.43461134453781514) 21%, rgba(110,186,231,1) 100%); background-size: cover; background-position: center; height: 100%; width: 100%; position: absolute; top: 0; left: 0; z-index: 1;">
			</div>
			<div
				style="background: linear-gradient(to bottom, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.9)); position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 2;">
			</div>
			<div class="container" style="position: relative; z-index: 3;">
				<div class="row">
					<div class="col-md-4">
						<img src="assets/media/avatars/books-with-graduation-cap-digital-art-style-education-day-removebg-preview.png"
							alt="Client Logo" class="img-fluid">
					</div>
					<div class="col-md-6 mt-4">
						<h2
							style="font-size: 30px; font-weight: 600; color: black; margin-bottom: 10px; align-items:justify">
							Order our assignment service today and enjoy a special discount!</h2>
						<p>Get help with your assignments easily and stress-free with our expert helpers!</p>
						<div style="text-center">
							<a href="/upload-your-assignment"><button class=" place-now">Order Now</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Understanding What Is University Assignment -->
 <section>
    <div class="auto-container">
        <div class="row clearfix" >
            <div class="title-column col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <div class="section-color-layer"></div>
                        <h2 style="font-weight:500; font-size: 30px;; color:black" class="  my-4"></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
 <!-- Understanding What Is University Assignment -->
 <section>
    <div class="auto-container">
        <div class="row clearfix" >
            <div class="title-column col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <div class="section-color-layer"></div>
                        <h2 style="font-weight:500; font-size: 30px;; color:black" class="my-4">Understanding What Is University Assignment</h2>
                        <p>University assignments are structured tasks that require a good amount of research. Critical thinking is key in completing them effectively. You encounter everything from short essays to lengthy dissertations. Each type tests your knowledge and analytical skills. If you need assistance, hiring a <b>university assignment writer in UK</b> can make a significant difference. We see the real struggle behind these tasks. Assignments play a vital part in shaping your future, often causing stress. For those seeking <b>university help in UK</b> , trusting us with your workload frees up your time. While you focus on education or life, we manage tasks with care. Professionals who value quality and originality take over your assignments. You can finally breathe easy knowing experts have your back.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>

 <!-- Expert University Assignment Help: Achieve Your UK University Goals with Ease -->
 <section>
    <div class="auto-container">
        <div class="row clearfix" >
            <div class="title-column col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <div class="section-color-layer"></div>
                        <h2 style="font-weight:500; font-size: 30px;; color:black" class="my-4">Expert University Assignment Help: Achieve Your UK University Goals with Ease</h2>
                        <p>The expert team at <a href="https://www.assignnmentinneed.com"><b>Assignment In Need</b></a> is here to help you reach your academic goals. Each assignment plays a vital role for UK university students. You need high-quality work to succeed in this environment. If you're looking for <b>help writing university assignments in UK</b>, our support makes tackling academic requirements less daunting. We aim to make your journey smooth and productive. No need to worry about the details—experts handle that. If you want to <b>pay someone to do uni assignments in UK</b> , you can stand out from your peers by submitting outstanding assignments. These tasks get well-researched and analysed while following university guidelines. Achieving an advantage in academia feels possible when you have the right help.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
 <!-- Get Top-Quality University Assignment Help for Outstanding Results in UK -->
 <section>
    <div class="auto-container">
        <div class="row clearfix" >
            <div class="title-column col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <div class="section-color-layer"></div>
                        <h2 style="font-weight:500; font-size: 30px;; color:black" class="my-4">Get Top-Quality University Assignment Help for Outstanding Results in UK</h2>
                        <p>Our team's focus on quality drives everything we do. Collaborating with skilled writers brings you peace of mind. If you're looking to <b>write my uni assignment in UK</b> , rest assured that your assignments will absolutely meet top-notch academic standards. Each task goes through a careful review process to ensure clarity and structure. It's all about making your work shine while reflecting your goals. If you need <b>university assignment help in UK</b> , outstanding results are what we aim for with every assignment you trust us with. Building a strong academic record becomes easier with our help. Your future career can benefit greatly from the high-quality work you submit.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
   <!-- Pay Someone to Complete Your University Assignments in UK -->
 <section>
    <div class="auto-container">
        <div class="row clearfix" >
            <div class="title-column col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <div class="section-color-layer"></div>
                        <h2 style="font-weight:500; font-size: 30px;; color:black" class="my-4">Pay Someone to Complete Your University Assignments in UK</h2>
                        <p>Students often ask, "Can I really <b>pay someone to do uni assignments in UK?</b> " At Assignment In Need, the answer is a loud yes. Offering a reliable service allows you to outsource your tasks effectively. Professionals handle your academic work, so you can focus on other things. If you need <b>help writing university assignments in UK</b> , under pressure from tight deadlines? Our service has your back, ensuring you get help when needed. By delivering high-quality assignments on time, we help ease your stress. Each task meets your requirements so you won't worry about quality. Academic demands may weigh you down, but we are here to lighten that load.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
    <!-- Affordable University Essay and Dissertation Writing Services in the UK -->
 <section>
    <div class="auto-container">
        <div class="row clearfix" >
            <div class="title-column col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <div class="section-color-layer"></div>
                        <h2 style="font-weight:500; font-size: 30px;; color:black" class="  my-4">Affordable University Essay and Dissertation Writing Services in the UK</h2>
                        <p>High-quality academic support doesn't have to cost a fortune. Our rates fit any student budget. You can find essay and dissertation writing services that do not cut corners on quality. For those seeking <b>help writing university assignments in UK</b> , whether you need help with one essay or a full dissertation, our commitment lies in offering cost-effective solutions for every situation. Each service delivers excellent academic support while remaining accessible. If you're looking to <b>pay for uni assignments in UK,</b> there's no need to sacrifice quality for your finances when you choose us. Students deserve affordable help, and we take that seriously at Assignment In Need. Achieving your academic goals becomes feasible without overspending.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
 <!-- Why UK Students Choose Our University Writing Support Online? -->
 <section>
    <div class="auto-container">
        <div class="row clearfix" >
            <div class="title-column col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <div class="section-color-layer"></div>
                        <h2 style="font-weight:500; font-size: 30px;; color:black" class="my-4">Why UK Students Choose Our University Writing Support Online?</h2>
                        <p>UK students turn to Assignment In Need for our commitment to quality and reliability. Why? Our service features experienced writers ready to tackle your tasks. If you're looking to <b>write my uni assignment in UK,</b> you can count on fast turnaround times that always meet deadlines. Fear not; exceptional customer support is just a click away. Choosing us means getting personalised help tailored to your needs. Each student receives attention that aligns with their academic goals. You don't have to navigate complex assignments alone anymore. If you want to <b>pay someone to do uni assignments in UK,</b> stress and uncertainty fade when you let professionals handle your academic challenges. Meeting university requirements becomes much easier with our reliable support by your side.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
 <!-- Subjects We Cover in Our University Assignment Services in UK -->
 <section>
    <div class="auto-container">
        <div class="row clearfix" >
            <div class="title-column col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <div class="section-color-layer"></div>
                        <h2 style="font-weight:500; font-size: 30px;; color:black" class="my-4">Subjects We Cover in Our University Assignment Services in UK</h2>
                        <p>Services at Assignment In Need cover many subjects, making us a one-stop academic support provider. Are you studying Political Science, Human Resources, or Law? No problem at all; we can help with uni assignments in UK. Marketing, Accounting, Criminology, and Economics are also on our list. Our experts are prepared to assist you across various disciplines with ease. Got a question about Business, English, or History? </p>
                         <p>We got your back there too. Maths, Science, Nursing, Sociology, and Engineering? Yes, we handle those subjects as well. Every student can find tailored support for their academic field here. With us, help is always available when you need it most.</p>
                         <div class="subject-container card bg-light">
		                              <div class="row justify-content-center align-items-center">
										<div class="col-lg-12">
	                                      <div class="subject-list-box">
	                                        <div class="row">
													<div class="col-md-4">
				                                       <ul class="subject-list">
				                                        <li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Political Science University </b></a></li>
							                    		<li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Human Resources University </b></a></li>
							                    		<li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Law University</b></a></li>
							                    		<li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Marketing  University</b></a></li>
							                    		<li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Accounting University</b></a></li>
							                    	 
									                  </ul>
													</div>
												<div class="col-md-4">
														<ul class="subject-list">
                                                            <li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Criminology University</b></a></li>
							                    		    <li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Economics University</b></a></li>
															<li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Business University</b></a></li>
															<li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>English University</b></a></li>
															<li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>History University</b></a></li>
															 

														</ul>
			                                   </div>
			                                    <div class="col-md-4">
				                           	      <ul class="subject-list">
			                                             	<li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Maths University</b></a></li>
                                                            <li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Science University </b></a></li>
															<li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Nursing University</b></a></li>
															<li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Sociology University</b></a></li>
															<li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Engineering University</b></a></li>
                                                            <li><i class="fas fa-caret-right"></i><a class="text-white" href="#"><b>Architecture University</b></a></li>
                                                </ul>
			                                  </div>
		                                    </div>
	                                     </div>
                                       </div>
					                	</div>
                                   </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
 <!-- Types of Assignment We Handle in UK -->
 <section>
    <div class="auto-container">
        <div class="row clearfix" >
            <div class="title-column col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <div class="section-color-layer"></div>
                        <h2 style="font-weight:500; font-size: 30px;; color:black" class="my-4">Types of Assignment We Handle in UK</h2>
                        <p>Our team at Assignment In Need is equipped to handle various types of assignments, each tailored to meet university standards and your academic needs.</p>
                        <h3>Essay Writing Help</h3>
                        <p>Writing essays is essential in university, but mastering it can be tough for students. At Assignment In Need, tailored support makes expressing your ideas a breeze. If you need <b>help with uni assignments in UK</b> , our professional writers specialise in crafting well-structured essays to meet UK university standards. Understanding your unique essay requirements is part of our process. Whether it's argumentative, descriptive, or a critical analysis, we can handle it all. Originality and clarity are our focus because you deserve authentic work. Each essay undergoes thorough research to show your individual perspective clearly. For those seeking <b>university assignment help in UK</b> , meeting academic standards is key, but understanding the subject matters even more. Whether you face complex theories or simple prompts, we've got your back. Count on our expert writers for help in various subjects since we've got you covered. Let us assist you in submitting essays that shine and improve your grades.</p>
                        <h3>Homework Help</h3>
                        <p>University homework piles up fast, creating stress and pressure in your busy academic life. We offer quick homework help to keep deadlines in check. For those seeking <b>university homework help in UK</b> , submitting quality work becomes a breeze when you choose us. Coverage includes many subjects and assignment types, from quick quizzes to larger projects. Each task gets attention from a qualified professional experienced with UK universities. For those looking for <b>online university homework help in UK</b> , collaborative support ensures your solutions are accurate and organised to meet your course needs. Speed and quality blend perfectly in our homework service. Keep up with your coursework while still enjoying some free time. By choosing us, you confidently manage multiple tasks without the overload. For those seeking <b>university coursework writing services in UK</b> , let us ease your homework burden with our reliable and supportive service.</p>
                        <h3>Research Paper</h3>
                        <p>Research papers need careful planning, mixing deep analysis and strong arguments. Our research paper writing service helps you craft comprehensive and insightful papers. For those looking for <b>university research writing help services in UK</b> , we provide extensive literature reviews and critical analysis as part of the process. Understanding academic citation styles is crucial for success in your writing. Our team of skilled writers is here to create research papers with originality in mind. You collaborate with us to find relevant sources and build a clear structure. Presenting findings clearly has a big impact on your grades. Whether you focus on social sciences, humanities, or STEM fields, we cover it all. UK university professors expect high standards, and we help you meet them. When you partner with us for <b>university assignment help in UK</b> , expert guidance follows you through every stage. From the initial research to final edits, we are with you. Achieve great grades while deepening your understanding of the subject matter through our service.</p>
                        <h3>Dissertation</h3>
                        <p>Writing a dissertation stands out as one of the toughest challenges in university. Months of dedication, detailed research, and strong writing skills are all required. At Assignment In Need,  <b>university dissertation writing help services in UK</b> provide comprehensive dissertation support to help you produce a high-quality piece. Expect to meet UK academic standards with our assistance. Our team guides you through each step of the dissertation journey. Whether it's topic selection or proposal writing, we cover it all. Data analysis and final editing? We're on it; no need to worry. For those seeking <b>university help in UK</b> , the unique demands of dissertation work can weigh you down; we aim to lift that burden. Focus on your research while we handle formatting and citations for you. Each dissertation reflects the specific needs of your university and subject area. We want you to produce work that earns top marks and impacts your field</p>
                        <h3>Thesis</h3>
                        <p>Creating a strong thesis is vital for students who want to create an academic impact. Expert help is available to make your thesis stand out. If you're looking to  <b>pay for uni assignments in UK,</b> expect each thesis we work on to showcase originality and deep analysis. This process involves extensive research and a clear argument that connects ideas. Our writers grasp the details of thesis development, ready to guide you at every step. If you need <b>help with uni assignments in UK</b> , you start with a strong thesis statement, and we help structure each chapter. Proper citation is key, and we ensure every detail meets UK university standards. If you're looking for a <b>university assignment writer in UK</b> , our support covers a wide range of subjects, from humanities to STEM fields. By collaborating with us, you receive personalised guidance to submit a confident thesis. Know that your work is thorough, accurate, and meets academic expectations.</p>
                        <h3>Coursework</h3>
                        <p>Coursework plays a key role in university education, measuring your grasp of the subject. At Assignment In Need, <b>university coursework writing services in UK</b> offer dedicated coursework help to boost your chances to excel in this area. Multiple tasks, like essays, projects, and quizzes, can pile up quickly and stress you out. Our expert team manages your coursework across various subjects, keeping everything in line with your course requirements. Tailored solutions show a clear understanding of the material and impress your professors. Collaborating with you ensures that we deliver accurate and useful answers every time. For those seeking <b>online university homework help in UK</b> , structured support helps you navigate through deadlines while maintaining high academic standards. Manage your coursework stress with us and keep your academic goals in sight. Let our reliable service assist you in staying organised and achieving success.</p>
                        <h3>Case Study</h3>
                        <p>Case studies demand critical thinking and analytical skills, focusing on real-life scenarios in your field. At We provide professional support for case studies to enhance your work. For those in need of a <b>university assignment writer in UK</b> , understanding the need for real-world examples is crucial in presenting effective case studies. Our team helps ensure that your study identifies key issues and applies theoretical concepts wisely. Each case study is customised to meet the expectations of UK academic standards. Whether your focus is business, law, healthcare, or social sciences, we cover it all. For those seeking <b>university help in UK</b> , thorough research and strategic analysis means each study shows a solid grasp of your topic. Let us assist you in creating case studies that display your skills and knowledge clearly. Stand out in your field by submitting well-crafted and insightful case studies with our help.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
 <!-- University Coursework and Homework Help Online in the UK -->
 <section>
    <div class="auto-container">
        <div class="row clearfix" >
            <div class="title-column col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <div class="section-color-layer"></div>
                        <h2 style="font-weight:500; font-size: 30px;; color:black" class="my-4">University Coursework and Homework Help Online in the UK</h2>
                        <p>Online support services for coursework and homework at Assignment In Need aim to help students everywhere. A digital platform links you to qualified professionals ready to assist with various tasks. For those looking for <b>university essay writing help services in UK,</b> we cover everything from simple homework to complex projects. With 24/7 access, help is always available at your fingertips. No need to stress about tight deadlines or location issues; we've got your back. For those seeking <b>university homework help in UK</b> , get the assistance you need to succeed, regardless of your schedule. Your academic journey can go smoothly with just a click. Trust us to connect you with the right expert for your situation.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
 <!-- Done Your All University Work with Qualified Writers in UK -->
 <section>
    <div class="auto-container">
        <div class="row clearfix" >
            <div class="title-column col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box">
                        <div class="section-color-layer"></div>
                        <h2 style="font-weight:500; font-size: 30px;; color:black" class="my-4">Done Your All University Work with Qualified Writers in UK</h2>
                        <p>Our qualified writers help you with all your university tasks. Each team member has a strong academic background, ready to handle demanding assignments. If you're looking to <b>pay for uni assignments in UK</b> , you can count on us to tackle every challenge that comes your way. Expect each piece of work to show dedication and a commitment to quality. If you're looking to <b>write my uni assignment in UK</b> , supporting your academic success is our main goal. Challenges in university can feel overwhelming, but you don’t have to face them alone. Trust us to help you navigate through your assignments with ease. Together, we can help you achieve the grades you want.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
   
                 <!-- FAQs Question  section     -->
				  <section class="faq-section">
					<div class="auto-container">
						<div class="row">
							<div class="column col-lg-12 col-md-12 col-sm-12">
								<div class="title-box">
								<h2 style="font-weight:500; font-size: 30px;; color:black" class="  my-4">Frequently asked questions </h2>
								</div>
							     <ul class="accordion-box">
									<li class="accordion block">
										<div class="acc-btn">1. How can someone help me with my university assignment?  <div class="icon fa fa-angle-down"></div></div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
                                                    <p>You can get help with any part of your assignment, like homework, essays, research papers, dissertations, and more. Our experts make the process simpler by giving you exactly the support you want.</p>
													 </div>
											</div>
										</div>
									</li>
                                    <li class="accordion block">
										<div class="acc-btn">2. What types of tasks can someone help with on my university assignment?  <div class="icon fa fa-angle-down"></div></div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
                                                    <p>You can get assistance for every stage of your assignment, whether it's research, writing, editing, and formatting. Our team is here to make sure your work meets all your academic help.</p>
													 </div>
											</div>
										</div>
									</li>
                                    <li class="accordion block">
										<div class="acc-btn">3. How can I find reliable assignment help? <div class="icon fa fa-angle-down"></div></div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
                                                    <p>Look for services with experienced writers, positive reviews, and a commitment to quality. At Assignment In Need, we ensure that every project is handled by experts delivering the best quality and reliable work.</p>
													 </div>
											</div>
										</div>
									</li>
                                    <li class="accordion block">
										<div class="acc-btn">4. What types of assignments can I get help with?  <div class="icon fa fa-angle-down"></div></div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
                                                    <p>Our Expert is available for all kinds of assignments Help, including essays, research papers, lab reports, case studies, presentations, dissertations and more.</p>
													 </div>
											</div>
										</div>
									</li>
                                    <li class="accordion block">
										<div class="acc-btn">5. What types of university homework help can I get?  <div class="icon fa fa-angle-down"></div></div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
                                                    <p>Whether you need help with math problems, science concepts, literature analysis, or any other specific subject, you can get homework help support to make studying more engaging and simple.</p>
													 </div>
											</div>
										</div>
									</li>
                                    <li class="accordion block">
										<div class="acc-btn">6. Is it ethical to seek university essay writing help?  <div class="icon fa fa-angle-down"></div></div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
                                                    <p>Yes! Using Essay writing services as a learning aid or for extra guidance is a very good choice. Think of it as having a tutor who helps you understand concepts better, so you're more confident in your work.</p>
													 </div>
											</div>
										</div>
									</li>
                                    <li class="accordion block">
										<div class="acc-btn">7. Can university assignments help improve my grades?  <div class="icon fa fa-angle-down"></div></div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
                                                    <p>Absolutely! Getting expert feedback and support can help you create high-quality assignments, which can lead to better grades and a deeper understanding of the material.</p>
													 </div>
											</div>
										</div>
									</li>
                                    <li class="accordion block">
										<div class="acc-btn">8. Will someone do my university assignment for me?  <div class="icon fa fa-angle-down"></div></div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
                                                    <p>If you're short on time or need extra help, you can find services to assist you from start to finish. At Assignment In Need, our experts work closely with you to deliver assignments that meet your requirements exactly.</p>
													 </div>
											</div>
										</div>
									</li>
                                    <li class="accordion block">
										<div class="acc-btn">9. How much does university dissertation help usually cost?  <div class="icon fa fa-angle-down"></div></div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
                                                    <p>The cost of dissertation help depends on the length, subject, and deadline. Assignment in Need offer flexible pricing to fit different budgets, so you can get the assistance you need without breaking the bank.</p>
													 </div>
											</div>
										</div>
									</li>
                                    <li class="accordion block">
										<div class="acc-btn">10. What if my university research paper is on a specific subject?  <div class="icon fa fa-angle-down"></div></div>
										<div class="acc-content">
											<div class="content">
												<div class="text">
                                                    <p>No worries! We have specialists across all research paper fields. Just let us know your topic about research paper, and we'll match you with an expert who's experienced in your research paper topic, so your paper is in the best hands.</p>
													 </div>
											</div>
										</div>
									</li>
								 </ul>
							</div>
						</div>
					</div>
                        </section>


           


 
@endsection