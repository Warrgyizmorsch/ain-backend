@extends('frontend-layouts.guest')

@section('content')
	<style>
		.inner-column {
			padding-top: 0px !important;
		}
		.banner-section-three .content-column .inner-column h2 {
    		padding-top: 100px;
		}
		.text-black {
			color: black;
		}
		
		.hotline {
			position: absolute;
			left: 20%;
			bottom: 70px;
			text-align: center;
			padding: 35px 30px;
			border-radius: 55px;
			background-color: #8cede7;
			animation-name: float_up_down;
			animation-duration: 5s;
			animation-iteration-count: infinite;
			animation-timing-function: linear;
			-webkit-animation-name: float_up_down;
			-webkit-animation-duration: 5s;
			-webkit-animation-iteration-count: infinite;
			-webkit-animation-timing-function: linear;
		}

	</style>
	<section class="banner-section-three">
		<div class="pattern-layer-two" style="background-image: url(images/background/pattern-21.png)"></div>
		<div class="auto-container" >
			<!-- Page Breadcrumb -->
			
            <div class="row clearfix">
				
				<!-- Content Column -->
				<div class="content-column col-lg-12 col-md-12 col-sm-12">
					<div class="inner-column">
						<div class="pattern-layer-one" style="background-image: url(images/main-slider/pattern-1.png)"></div>
						<div class="icon-layer" style="background-image: url(images/icons/icon-2.png)"></div>
						<div class="icon-layer-two" style="background-image: url(images/icons/icon-5.png)"></div>
                        <div>
							<section class="contact-section ">
								<div class="pattern-layer" style="background-image:url(images/background/pattern-19.png)"></div>
								<div class="pattern-layer-two" style="background-image:url(images/background/pattern-20.png)"></div>
								<div class="icon-layer-two" style="background-image:url(images/icons/icon-2.png)"></div>
								<div class="icon-layer-three" style="background-image:url(images/icons/icon-2.png)"></div>
								<div class="auto-container">
									<div class="row clearfix">
										
										<!-- Info Column -->
										<div class="info-column col-lg-6 col-md-12 col-sm-12">
											<div class="inner-column">
												<div class="icon-layer" style="background-image:url(images/icons/icon-2.png)"></div>
												
												<h2 class="text-black text-center mb-5">Welcome to Assignment In Need!</h2>
                                                <h5 class="text-black text-center mb-5">Enter your personal details and start journey with us</h5>
												<div class="image">
													<img src="images/resource/contact.png" alt="">
												</div>
											</div>
										</div>
										
										<!-- Form Column -->
										<div class="form-column col-lg-6 col-md-12 col-sm-12">
											<div class="inner-column">
												<div class="color-layer"></div>
												
												
												<!-- Default Form -->
												<div class="default-form">
                                                <form class="form w-100" novalidate="novalidate" id="registerForm" action="{{ route('register') }}" method="post">
                                                    @csrf
                                                        <div class="text-center mb-10 pt-5">
                                                            <h1 class="text-dark mb-3">Sign Up</h1>
                                                            <div class="text-gray-400 fw-bold fs-4">Already have account?
                                                            <a href="{{ route('login') }}" class="link-primary fw-bolder">Login</a></div>
                                                        </div>
                                                        <div class="fv-row mb-10">
                                                            <label class="form-label fs-6 fw-bolder text-dark">Name</label>
                                                            <input class="form-control form-control-lg form-control-solid @error('name') is-invalid @enderror" value="{{ old('name') }}" type="text" name="name" autocomplete="off" />
                                                            @error('name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="fv-row mb-10">
                                                            <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                                                            <input class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror" value="{{ old('email') }}" type="text" name="email" autocomplete="off" />
                                                            @error('email')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="fv-row mb-10">
                                                            <div class="d-flex flex-stack mb-2">
                                                                <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                                                                
                                                            </div>
                                                            <input class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror" type="password" name="password" autocomplete="off" />
                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="fv-row mb-10">
                                                            <div class="d-flex flex-stack mb-2">
                                                                <label class="form-label fw-bolder text-dark fs-6 mb-0">Confirm Password</label>
                                                                
                                                            </div>
                                                            <input class="form-control form-control-lg form-control-solid @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" autocomplete="off" />
                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="g-recaptcha mt-2" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                                        <div class="text-center">
                                                            <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5 mt-5">Register</button>
                                                            <!-- <div class="text-center text-muted text-uppercase fw-bolder mb-5">or</div>
                                                            <a href="" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
                                                            Continue with OTP</a> -->
                                                            
                                                        </div>
                                                    </form>
												</div>
												
											</div>
										</div>
										
									</div>
								</div>
								<script>
    								document.getElementById('registerForm').addEventListener('submit', function(event) {
    									event.preventDefault(); // Prevent the form from submitting
    
    									var recaptchaResponse = grecaptcha.getResponse();
    									if (!recaptchaResponse) {
    										Swal.fire({
    											icon: 'error',
    											title: 'Oops...',
    											text: 'Please complete the reCAPTCHA.',
    										});
    										return false; // Prevent form submission
    									}
    
    									// If reCAPTCHA is completed, allow form submission
    									this.submit();
    								});
    							</script>
							</section>
						</div>
					</div>
				</div>
				
				
				
			</div>
		</div>
		<div class="hotline">
            <div class="d-flex align-items-center fw-bold fs-6">
                <a href="/" class="text-muted text-hover-primary px-2">Home</a>
                <a href="/what-we-are" class="text-muted text-hover-primary px-2">About</a>
                <a href="/contact-us" class="text-muted text-hover-primary px-2">Contact</a>
            </div>
		</div>
	</section>
	
@endsection