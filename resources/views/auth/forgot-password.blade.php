

       
@extends('frontend-layouts.guest')

@section('content')
	<style>
		.inner-column {
			padding-top: 0px !important;
		}
		.banner-section-three .content-column .inner-column h4 ,h2{
    		padding-top: 100px;
		}
		.text-black {
			color: black;
		}
		.contact-section {
            position: relative;
            padding: 20px 0px 0px;
        }
		.btn-outline-primary:hover {
            color: #fff;
            background-color: #00ffe521;
            border-color: #007bff;
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
												
												<h2 class="text-black text-center mb-5">Assignment In Need!</h2>
                                                <div class="text-right">
                                                <button type="button" class="btn btn-outline-primary">
                                                    <a href="/login" style="text-decoration: none;">
                                                        <i class="fa fa-arrow-left me-2"></i> Back to Login
                                                    </a>
                                                </button>
                                                </div>

												<div class="image">
													<img src="images/resource/instructor.png" alt="">
												</div>
											</div>
										</div>
										
										
										<!-- Form Column -->
										<div class="form-column col-lg-6 col-md-12 col-sm-12">
											<div class="inner-column">
												<div class="color-layer"></div>
												
												
												<!-- Default Form -->
												<div class="default-form">
                                                    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                                        <h4>Forgot your password?</h4> <br>
                                                        <div class="text">No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</div>
                                                        
                                                        
                                                    </div>

                                                    <!-- Session Status -->
                                                    <x-auth-session-status class="mb-4" :status="session('status')" />

                                                    <form method="POST" action="{{ route('password.email') }}">
                                                        @csrf

                                                        <!-- Email Address -->
                                                        <div>
                                                            <label class="form-label fs-6 fw-bolder text-dark" for="email" :value="__('Email')">Email</label>
                                                            <input class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror" value="{{ old('email') }}" type="email" name="email" id="email" autocomplete="off" />
                                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />

                                                        </div>

                                                        
                                                        <div class="text-center">
															<button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5 mt-5">
																<span class="indicator-label">Email Password Reset Link</span>
																
															</button>
															
															
														</div>
                                                    </form>
												</div>
												
											</div>
										</div>
										
									</div>
								</div>
							</section>
						</div>
					</div>
				</div>
				
				
				
			</div>
		</div>
		
	</section>
	
@endsection


