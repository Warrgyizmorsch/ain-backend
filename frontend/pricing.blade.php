@extends('frontend-layouts.app') {{-- If you have a main layout --}}

@section('content')
<style>
	.perks-section {
		background: #ffffff;
		padding: 50px 20px 100px 20px;
		text-align: center;
	}

	.perks-title {
		font-size: 32px;
		font-weight: 700;
		position: relative;
		margin-bottom: 50px !important;
	}



	.perks-cards {
		display: flex;
		justify-content: center;
		gap: 30px;
		flex-wrap: wrap;
	}

	.perk-card {
		background: #fff;
		border: 2px solid transparent;
		border-radius: 12px;
		width: 312px;
		padding: 30px 20px;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
		transition: 0.3s ease;

		background-image:
			linear-gradient(#fff, #fff),
			linear-gradient(90deg, #3F159A, #007FFF);

		background-origin: border-box;
		background-clip: padding-box, border-box;
	}

	.perk-card:hover {
		transform: translateY(-6px);
	}

	.perk-icon {
		width: 100px;
		height: 100px;
		margin: 0 auto 20px;
	}

	.perk-icon img {
		width: 100%;
		height: 100%;
		object-fit: contain;
	}

	.circle-bg {
		background: linear-gradient(180deg, #FFFFFF 0%, #F1EFFF 100%);
		border-radius: 50%;
	}

	.perk-card h4 {
		font-family: 'Poppins', sans-serif;
		font-weight: 700;
		font-size: 24px;
		margin-bottom: 8px;
		color: #1D6ABF;
	}

	.old-price {
		color: red;
		text-decoration: line-through;
		font-size: 20px;
	}

	.free-box {
		margin: 40px auto 30px;
		width: 1023px;
		max-width: 90%;
		padding: 15px;
		border-radius: 12px;
		font-size: 22px;
		font-weight: 700;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 10px;

		border: 2px solid transparent;

		background-image:
			linear-gradient(#fff, #fff),
			linear-gradient(#007FFF, #3F159A);

		background-origin: border-box;
		background-clip: padding-box, border-box;
	}

	.free-text {
		color: #003A75;
		font-size: 40px;
		line-height: 38.4px;
	}

	.free-old {
		color: red;
		text-decoration: line-through;
		font-weight: 500;
		font-size: 24px;
		margin-left: 10px;
	}

	.order-btn button {
		background: linear-gradient(90deg, #3F159A, #0E8FCED9);
		width: 282px;
		border: none;
		padding: 12px 40px;
		border-radius: 8px;
		color: white;
		font-size: 24px;
		font-weight: 600;
		cursor: pointer;
		transition: 0.3s;
	}

	.order-btn button:hover {
		opacity: 0.9;
	}

	.detail-rating {
		padding: clamp(28px, 4vw, 52px) 0;
		background: #fff;
	}

	.detail-rating__container {
		max-width: 1200px;
		margin: 0 auto;
		padding: 0 16px;
	}

	.detail-rating__head {
		text-align: center;
		margin-bottom: 18px;
		opacity: 0;
		transform: translateY(10px);
		animation: drFade .6s ease-out .05s forwards;
	}

	.detail-rating__title {
		margin: 0;
		font-weight: 900;
		color: #1f1b3a;
		font-size: clamp(18px, 2.2vw, 26px);
	}

	.detail-rating__line {
		width: 64px;
		height: 3px;
		margin: 10px auto 8px;
		border-radius: 999px;
		background: #6d4bff;
	}

	.detail-rating__sub {
		margin: 0;
		color: #6b6787;
		font-weight: 600;
		font-size: 13px;
	}

	.detail-rating__grid {
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

	.rating-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 14px 26px rgba(15, 10, 60, 0.08);
	}

	.rating-card__left {
		display: flex;
		align-items: center;
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

	.rating-card__stars {
		color: #f6b300;
		font-size: 20px;
		letter-spacing: 1px;
		flex: 0 0 auto;
	}

	@keyframes drFade {
		to {
			opacity: 1;
			transform: none;
		}
	}

	@keyframes drCard {
		to {
			opacity: 1;
			transform: none;
		}
	}

	/* Mobile */
	@media (max-width: 720px) {
		.detail-rating__grid {
			grid-template-columns: 1fr;
		}

		.rating-card__label {
			font-size: 12.5px;
		}

		.rating-card__stars {
			font-size: 15px;
		}
	}

	@media (prefers-reduced-motion: reduce) {

		.detail-rating__head,
		.rating-card {
			animation: none !important;
			opacity: 1 !important;
			transform: none !important;
		}

		.rating-card {
			transition: none !important;
		}
	}

	.section-title {
		background: linear-gradient(to bottom, #3F159A, #0E8FCE);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		font-size: 2rem;
		font-weight: 700;
		line-height: 1.2;
		margin-bottom: 10px;
	}

	/* Floating Success Banner */

	.floating-success {
		margin: 20px auto;
		margin-bottom: 50px;
		background: linear-gradient(90deg, #D3D3FD, #E9D6F9, #F8D9EF);
		padding: 40px 20px;
		position: relative;
		text-align: center;
		overflow: hidden;
	}

	/* Center Text */

	.floating-success h2 {
		font-size: 40px;
		font-weight: 500;
		line-height: 140%;
		color: #111;
		margin-bottom: 15px;
	}

	.floating-success h2 span {
		background: linear-gradient(90deg, #3F159A 0%, #007FFF 100%);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
	}

	.floating-success button {
		background: linear-gradient(90deg, rgba(63, 21, 154, 0.85) 0%, rgba(14, 143, 206, 0.85) 100%);
		border: none;
		padding: 18px 80px;
		border-radius: 12px;
		color: #FFFFFF;
		font-weight: 600;
		font-size: 24px;
		cursor: pointer;
		border: 1px solid #ECECEC;
	}

	/* Floating Icons */

	.floating-icon {
		position: absolute;
		width: 120px;
		height: 120px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.floating-icon img {
		width: 100%;
		height: 100%;
		border-radius: 50%;
		object-fit: cover;
	}

	/* LEFT SIDE */

	.icon-l1 {
		top: 20px;
		left: 0px;
	}
	

	.icon-l2 {
		top: 25px;
		left: 100px;
	}

	.icon-l3 {
		top: 30px;
		left: 190px;
	}

	.icon-l4 {
		top: 80px;
		left: 260px;
	}

	.icon-l5{
		top: 145px;
		left: 0px;
	}
	
	.icon-l6{
		width: 150px;
		height: 150px;
		top: 110px;
		left: 80px;
	}
	.icon-l7{
		top: 140px;
		left: 180px;
	}


	/* RIGHT SIDE */

	.icon-r1 {
		top: 15px;
		right: 230px;
	}

	.icon-r2 {
		top: 15px;
		right: 120px;
	}

	.icon-r3 {
		top: 15px;
		right: 0px;
	}

	.icon-r4 {
		top: 130px;
		right: 260px;
	}

	.icon-r5 {
		top: 130px;
		right: 140px;
	}
	.icon-r6 {
		top: 130px;
		right:30px;
	}

@media (max-width: 1200px) {

  .floating-icon{
    width: 90px;
    height: 90px;
  }
  .floating-success h2 {
		font-size: 32px;
		font-weight: 500;
		line-height: 140%;
		color: #111;
		margin-bottom: 15px;
	}

	.floating-success button {
		background: linear-gradient(90deg, rgba(63, 21, 154, 0.85) 0%, rgba(14, 143, 206, 0.85) 100%);
		border: none;
		padding: 14px 60px;
		border-radius: 12px;
		color: #FFFFFF;
		font-weight: 600;
		font-size: 18px;
		cursor: pointer;
		border: 1px solid #ECECEC;
	}

  /* LEFT side icons thoda bahar shift */
  .icon-l1{ left:0px; }
  .icon-l2{ left:80px; }
  .icon-l3{ left:150px; }
  .icon-l4{ left:180px; }
  .icon-l5{ left:0px; }
  .icon-l6{ left:50px; }
  .icon-l7{ left:120px; }

  /* RIGHT side icons */
  .icon-r1{ right:150px; }
  .icon-r2{ right:80px; }
  .icon-r3{ right:0px; }
  .icon-r4{ right:150px; }
  .icon-r5{ right:70px; }
  .icon-r6{ right:0px; }

}


/* Mobile screens */
@media (max-width: 768px){

  .floating-icon{
    display:none;
  }

  .floating-success h2{
    font-size:40px !important;
  }

  .floating-success button{
    font-size:18px;
    padding:18px 80px;
  }

}

@media (max-width: 1268px){
 .floating-success h2 {
		font-size: 28px;
		font-weight: 500;
		line-height: 140%;
		color: #111;
		margin-bottom: 15px;
	}
}

	/* Tablet */
@media (max-width: 992px){

.floating-success h2 {
		font-size: 22px;
		font-weight: 500;
		line-height: 140%;
		color: #111;
		margin-bottom: 15px;
	}

	 .perks-title{
        font-size: 26px;
    }

    .perk-card{
        width: 280px;
        padding: 25px 18px;
    }

    .perk-card h4{
        font-size: 20px;
    }

    .old-price{
        font-size: 18px;
    }

    .free-text{
        font-size: 32px;
    }

}


/* Mobile */
@media (max-width: 768px){

   

	 .perks-section{
        padding: 40px 15px 70px;
    }

    .perks-title{
        font-size: 22px;
        margin-bottom: 35px !important;
    }

    .perks-cards{
        gap: 20px;
    }

    .perk-card{
        width: 100%;
        max-width: 320px;
        margin: auto;
    }

    .perk-icon{
        width: 80px;
        height: 80px;
    }

    .perk-card h4{
        font-size: 18px;
    }

    .old-price{
        font-size: 16px;
    }


    .free-text{
        font-size: 28px;
    }

    .free-old{
        font-size: 18px;
        margin-left: 0;
    }

    .order-btn button{
        width: 100%;
        max-width: 260px;
        font-size: 18px;
        padding: 10px 20px;
    }

}

@media (max-width: 600px){
.floating-success h2 {
		font-size: 30px !important;
		font-weight: 500;
		line-height: 140%;
		color: #111;
		margin-bottom: 15px;
	}

}


/* Small Mobile */
@media (max-width: 480px){

	  .perks-title{
        font-size: 20px;
    }

    .perk-card{
        padding: 20px 15px;
    }

    .perk-card h4{
        font-size: 16px;
    }

    .perk-icon{
        width: 70px;
        height: 70px;
    }

    .free-text{
        font-size: 24px;
    }

    .order-btn button{
        font-size: 16px;
        padding: 10px 16px;
    }
	.free-box{
		padding: 10px;
	}

}
</style>
@include('new-component.personalized-hero', [
'title' => 'Personalized assignment plan, made for you',
'subtitle' => 'We design pricing around your subject, word count, deadline, and unique assignment requirements.'
])

@include('components.review-section')



<section class="detail-rating">
	@include('home.section.process')
</section>

<section class="perks-section">
	<div class="container">

		<h2 class="perks-title section-title">
			Premium Perks, On the House
		</h2>

		<div class="perks-cards">

			<div class="perk-card">
				<div class="perk-icon circle-bg" style="display: flex; align-items:center; justify-content:center">
					<div style="height: 89px; width: 74px;">
						<img src="{{ asset('images/revision.png') }}" alt="Unlimited Revisions">
					</div>
				</div>
				<h4>Unlimited Revisions</h4>
				<span class="old-price">£11.53</span>
			</div>

			<div class="perk-card">
				<div class="perk-icon circle-bg" style="display: flex; align-items:center; justify-content:center">
					<div style="height: 89px; width: 74px;">
						<img src="{{ asset('images/search-icon.png') }}" alt="Unlimited Revisions">
					</div>
				</div>
				<h4>Editing/Proofreading</h4>
				<span class="old-price">£9.99</span>
			</div>

			<div class="perk-card">
				<div class="perk-icon circle-bg" style="display: flex; align-items:center; justify-content:center">
					<div style="height: 64px; width: 74px;">
						<img src="{{ asset('images/plagiarism.png') }}" alt="Unlimited Revisions" style="object-fit: fill;">
					</div>
				</div>
				<h4>Plagiarism Report</h4>
				<span class="old-price">£6.99</span>
			</div>

		</div>

		<div class="free-box">
			<span class="free-text">FREE</span>
			<span class="free-old">£29.53</span>
		</div>

		<div class="order-btn">
			<button> <a href="upload-your-assignment" style="text-decoration: none; color: inherit;">Order Now</a></button>
		</div>

	</div>
</section>

<section class="floating-success">

	<!-- Left Icons -->
	<div class="floating-icon icon-l1">
		<img src="{{ asset('images/floating-images/floating-image-1.png') }}">
	</div>

	<div class="floating-icon icon-l2">
		<img src="{{ asset('images/floating-images/floating-image-2.png') }}">
	</div>

	<div class="floating-icon icon-l3">
		<img src="{{ asset('images/floating-images/floating-image-3.png') }}">
	</div>

	<div class="floating-icon icon-l4">
		<img src="{{ asset('images/floating-images/floating-image-5.png') }}">
	</div>

	<div class="floating-icon icon-l5">
		<img src="{{ asset('images/floating-images/floating-image-4.png') }}">
	</div>

	<div class="floating-icon icon-l6">
		<img src="{{ asset('images/floating-images/floating-image-6.png') }}">
	</div>

	<div class="floating-icon icon-l7">
		<img src="{{ asset('images/floating-images/floating-image-7.png') }}">
	</div>


	<!-- Right Icons -->
	<div class="floating-icon icon-r1">
		<img src="{{ asset('images/floating-images/floating-image-8.png') }}">
	</div>

	<div class="floating-icon icon-r2">
		<img src="{{ asset('images/floating-images/floating-image-9.png') }}">
	</div>

	<div class="floating-icon icon-r3">
		<img src="{{ asset('images/floating-images/floating-image-10.png') }}">
	</div>

	<div class="floating-icon icon-r4">
		<img src="{{ asset('images/floating-images/floating-image-11.png') }}">
	</div>

	<div class="floating-icon icon-r5">
		<img src="{{ asset('images/floating-images/floating-image-12.png') }}">
	</div>
	<div class="floating-icon icon-r6">
		<img src="{{ asset('images/floating-images/floating-image-13.png') }}">
	</div>


	<!-- Center Content -->

	<h2>
		Become Part of Our <span>10,000+</span><br>
		Strong Student Success Story.
	</h2>

	<button>Hire An Expert</button>

</section>

@include('home.section.testimonial-section')


@endsection