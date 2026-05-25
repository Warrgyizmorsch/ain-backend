
       
@extends('frontend-layouts.guest')

@section('content')
	<style>
		.inner-column {
			padding-top: 0px !important;
		}
		.banner-section-three .content-column .inner-column h2, h4 {
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
                                                <!-- <h5 class="text-black text-center mb-5">To keep connected with us please login with your personal info</h5> -->
												<div class="image">
													<img src="images/resource/instructor.png" alt="">
												</div>
											</div>
										</div>
										
										
										<!-- Form Column -->
										<div class="form-column col-lg-6 col-md-12 col-sm-12">
											<div class="inner-column">
												<div class="color-layer"></div>
												
												<div>
                                                    <h4 class="text-black text-center mb-5">Reset Your Password</h4>                                                        
                                                </div>
												<!-- Default Form -->
												<div class="">
                                                    
                                                    <form method="POST" action="{{ route('password.store') }}">
                                                        @csrf

                                                        <!-- Password Reset Token -->
                                                        <input type="hidden" name="token" value="{{ $request->route('token') }}" >

                                                        <!-- Email Address -->
                                                        <div>
                                                            <x-input-label for="email" value="Email" /> <br>
                                                            <x-text-input  style="width: -webkit-fill-available;" id="email" class="block mt-1 w-full " readonly type="email" name="email" value="{{$request->email}}"  required autofocus autocomplete="username" />
                                                            <x-input-error :messages="$errors->get('email')" class="mt-2" style="color:red" />
                                                        </div>

                                                        <!-- Password -->
                                                        <div class="mt-4">
                                                            <x-input-label for="password" :value="__('Password')" /> <br>
                                                            <x-text-input style="width: -webkit-fill-available;" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                                                            <x-input-error :messages="$errors->get('password')" class="mt-2" style="color:red"  />
                                                        </div>

                                                        <!-- Confirm Password -->
                                                        <div class="mt-4">
                                                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" /> <br>

                                                            <x-text-input style="width: -webkit-fill-available;" id="password_confirmation" class="block mt-1 w-full"
                                                                                type="password"
                                                                                name="password_confirmation" required autocomplete="new-password" />

                                                            <x-input-error :messages="$errors->get('password_confirmation')" style="color:red"  class="mt-2" />
                                                        </div>

                                                        
                                                        <div class="text-center">
                                                            <button type="submit"  class="btn btn-lg btn-primary w-100 mb-5 mt-5">
                                                                <span class="indicator-label">Reset Password</span>
                                                                
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


