
       
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
						<div class="pattern-layer-one" style="background-image: url('{{ asset('images/main-slider/pattern-1.png') }}')"></div>
						<div class="icon-layer" style="background-image: url('{{ asset('images/icons/icon-2.png') }}')"></div>
						<div class="icon-layer-two" style="background-image: url('{{ asset('images/icons/icon-5.png') }}')"></div>
						<div>
							<section class="contact-section ">
								<div class="pattern-layer" style="background-image: url('{{ asset('images/background/pattern-19.png') }}')"></div>
								<div class="pattern-layer-two" style="background-image: url('{{ asset('images/background/pattern-20.png') }}')"></div>
								<div class="icon-layer-two" style="background-image: url('{{ asset('images/icons/icon-2.png') }}')"></div>
								<div class="icon-layer-three" style="background-image: url('{{ asset('images/icons/icon-2.png') }}')"></div>
								<div class="auto-container">
									<div class="row clearfix">
										<!-- Info Column -->
										<div class="info-column col-lg-6 col-md-12 col-sm-12">
											<div class="inner-column">
												<div class="icon-layer" style="background-image:url('{{asset('images/icons/icon-2.png')}}')"></div>

												<h2 class="text-black text-center mb-5">Welcome Back <br> to Assignment In Need!</h2>
												<h5 class="text-black text-center mb-5">To keep connected with us please login with your personal info</h5>
												<div class="image">
													<img src="{{asset('images/resource/instructor.png')}}" alt="">
												</div>
											</div>
										</div>



										<!-- Form Column -->
										<div class="form-column col-lg-6 col-md-12 col-sm-12">
											<div class="inner-column">
												<div class="color-layer"></div>


												<!-- Default Form -->
												<div class="default-form">
													@if (Route::has('login'))
													<form class="form w-100" novalidate="novalidate" id="" action="{{ route('login') }}" method="post">

													@csrf
														<div class="text-center mb-10 pt-5">
																@if(session()->has('warning'))
																<div class="alert alert-warning">
																	{{ session()->get('warning') }}
																</div>
															@endif
															<h1 class="text-dark mb-3">Sign In</h1>
															<div class="text-gray-400 fw-bold fs-4">New Here?
															<a href="{{ route('register') }}" class="link-primary fw-bolder">Create an Account</a></div>
														</div>
														<div class="fv-row mb-10">
															<label class="form-label fs-6 fw-bolder text-dark">Email</label>
															<input class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror" value="{{ old('email') }}" type="text" name="email" autocomplete="off" />
															@error('email')
																<span class="invalid-feedback" role="alert">
																	<strong>{!! $message !!}</strong>
																	@if (strip_tags($message) === 'This account is already logged in elsewhere.')
																		<button type="button" class="btn btn-link p-0 takeover-trigger text-primary">Take Over Session?</button>
																	@endif
																</span>
															@enderror
														</div>
														<div class="fv-row mb-10">
															<div class="d-flex flex-stack mb-2">
																<label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
																@if (Route::has('password.request'))
																	<a href="{{ route('password.request') }}" class="link-primary fs-6 fw-bolder ml-auto">Forgot Password ?</a>
																@endif
															</div>
															<input class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror" type="password" name="password" autocomplete="off" />
															@error('password')
																<span class="invalid-feedback" role="alert">
																	<strong>{!! $message !!}</strong>
																</span>
															@enderror
														</div>
														<div class="text-center">
															<button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5 mt-5">
																<span class="indicator-label">Continue</span>

															</button>
															<!-- <div class="text-center text-muted text-uppercase fw-bolder mb-5">or</div>
															<a href="" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
															Continue with OTP</a> -->

														</div>
													</form>
													@endif
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
		<div class="hotline">
			<div class="d-flex align-items-center fw-bold fs-6">
				<a href="/" class="text-muted text-hover-primary px-2">Home</a>
				<a href="/what-we-are" class="text-muted text-hover-primary px-2">About</a>
				<a href="/contact-us" class="text-muted text-hover-primary px-2">Contact</a>
			</div>
		</div>
		<form id="takeoverForm" method="POST" action="{{ route('do-takeover') }}" style="display: none;">
			@csrf
		</form>
	</section>

@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
	$(document).ready(function () {
		$(document).on('click', '.takeover-trigger', function () {
			Swal.fire({
				title: 'Take over session?',
				text: 'This account is already logged in. Do you want to take over the session?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes, take over',
				cancelButtonText: 'No, cancel'
			}).then((result) => {
				if (result.isConfirmed) {
					$('#takeoverForm').submit();
				}
			});
		});
	});
</script>