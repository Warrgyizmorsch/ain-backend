<div class="image-column topspaceheader2 col-lg-6 col-md-12 col-sm-12">
				<div class="inner-column mr-0 pb-0 pl-0"
					style="background-color: white; border-radius: 10px;box-shadow: 10px;box-shadow: 0 1rem 1rem 0 rgba(0, 0, 0, .08)">
					@if ($errors->any())
						<div id="errorAlert" class="alert alert-danger mt-5">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
						<script>
							setTimeout(function () {
								document.getElementById('errorAlert').style.display = 'none';
							}, 10000); // Hide the error message after 10 seconds (10000 milliseconds)
						</script>
					@endif
					<!--<div class="offer-badge"><img src="assets/media/avatars/offer.png" alt="" loading="lazy"></div>-->
					<form id="orderForm" action="/neworder-fromhome" method="POST" enctype="multipart/form-data">
						<div class=""  style="    margin: 4px 0px 0px -7px;">
							<!--<img style="margin-top: -68px; margin-bottom: -24px; height:122px" src="assets/media/homepage/Limited time (4).webp"alt="offer">-->
							<img 
                                src="assets/media/homepage/offer.png"
                                
                                alt="christmas-offer-50%-off-assignnmentninneed.com"
                                class="promo-img"
                                loading="lazy"
                            >

						</div>
						
						@csrf
						<div class="contact-form p-4">
							@if(Auth::user() == '')
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Name</label>
											<input type="text" name="user_name" class="form-control input-color"
												placeholder="Your Full Name*" required=""
												style="background-color:#f1f1f1; height: 45px;">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Email</label>

											<input type="email" name="email" class="form-control"
												placeholder="Your Email Address*" required=""
												style="background-color:#f1f1f1; height: 45px;">
										</div>
									</div>
								</div>
								<input type="hidden" name="countrycode" id="country_c">
								<div class="row">
									<div class="col-md-6" style="width:100%">
										<div class="form-group">
											<label for="">Number</label>
											<input type="tel" name="mobile" class="form-control" placeholder="Phone*"
												id="phone_number" style="background-color:#f1f1f1;height: 45px;" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">deadline</label>
											<input type="date" name="delivery_date" id="DeliveryDate" class="form-control"
												required="" placeholder="deadline"
												style="background-color:#f1f1f1;height: 45px;">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Upload Your Assignment</label>
											<input id="file-upload" type="file" class="form-control" name="doc[]" multiple style="background-color:#f1f1f1;height: 45px;" /> 
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="enterTopic">Enter Topic</label>
											<input type="text" name="topic" id="enterTopic" class="form-control" style="background-color:#f1f1f1; height: 45px;" placeholder="Enter Topic" required>
										</div>
									</div>
								</div>
							@else
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="">deadline</label>
											<input type="date" name="delivery_date" id="DeliveryDate" class="form-control"
												required="" placeholder="deadline"
												style="background-color:#f1f1f1;height: 45px;">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Upload Your Assignment</label>
											<input id="file-upload" type="file" class="form-control" name="doc[]" multiple style="background-color:#f1f1f1;height: 45px;" /> 
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label for="enterTopic">Enter Topic</label>
											<input type="text" name="topic" id="enterTopic" class="form-control" style="background-color:#f1f1f1; height: 45px;" placeholder="Enter Topic" required>
										</div>
									</div>
								</div>


							@endif

							<div class="row">

							</div>

							<div class="policy_agree">
								<input type="checkbox" required="">&nbsp;I agree with Privacy Policy and Terms &amp;
								Conditions (Recommended)
							</div>
							<div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
							<div class="col-md-12 text-center">
								<button class=" place-order" type="submit">Place Order</button>
							</div>
							 
						</div>
					</form>
				</div>
			</div>
            
<style>
	.blue {
  color: white;
  background:  #77CDFF;
  margin: 5px;
  margin-left: 30%;
  padding: 5px 10px;
 border-radius: 36px;
}

/* Base styles for default (Desktop) */
.promo-img {
    margin-top: -105px;
    margin-bottom: -83px;
    height: 270px;
    width: 118%;
    max-width: 119%;
    margin-right: -49px !important;
}

    .offer-badge {
        position: absolute;
        top: -77px;
        left: 458px;
        color: white;
        font-weight: bold;
        border-radius: 10%;
        font-size: 17px;
        z-index: 0;
        margin-right: -196px;
    }


/* Tablet Styles (screen width up to 1499px) */
@media (max-width: 1499px) {
    .promo-img {
        margin-top: -74px;
        margin-bottom: -48px;
		height: 167px;
        width: 117%;
        max-width: 120%;
        /* margin-right: -49px !important; */
        position: relative;
        right: 36px;
    }
}

/* Mobile Styles (screen width up to 600px) */
@media (max-width: 600px) {
    .promo-img {
        margin-top: -103px;
        margin-bottom: -88px;
        height: 147px;
        width: 120%;
        max-width: 119%;
        margin-right: -49px !important;
        position: absolute;
        left: -25px;
    }
}

/* Desktop Styles (above 1500px) */
@media (min-width: 1500px) {
    .promo-img {
        margin-top: -80px;
        margin-bottom: -52px;
        height: 161px;
        width: 117%;
        max-width: 146%;
        margin-right: -49px !important;
        position: relative;
        right: 35px;
    }
}

/* Styles for screen widths between 1020px and 1250px */
@media (min-width: 1020px) and (max-width: 1150px) {
	.promo-img {
        margin-top: -74px;
        margin-bottom: -49px;
        height: 161px;
        width: 117%;
        max-width: 120%;
        position: relative;
        right: 30px;
    }
}


@media (min-width: 828px) and (max-width: 900) {
    .promo-img {
        margin-top: -74px;
        margin-bottom: -48px;
        height: 193px;
        width: 119%;
        max-width: 117%;
        position: relative;
        right: 51px;
    }
}
@media (min-width: 1050px) and (max-width: 120px) {
    .promo-img {
        margin-top: -74px;
        margin-bottom: -48px;
        height: 213px;
        width: 117%;
        max-width: 120%;
        position: relative;
        right: 30px; /* Adjusted as requested */
    }
}
@media (min-width: 400px) and (max-width: 500px) {
	.promo-img {
        margin-top: -74px;
        margin-bottom: -48px;
        height: 164px;
        width: 117%;
        max-width: 120%;
        position: relative;
        left: -29px;
    }
}
@media (min-width: 500px) and (max-width: 600px) {
	.promo-img {
        margin-top: -74px;
        margin-bottom: -48px;
        height: 164px;
        width: 117%;
        max-width: 120%;
        position: relative;
        left: -29px;
    }
}



</style>
