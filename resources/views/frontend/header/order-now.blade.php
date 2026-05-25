@extends('frontend-layouts.app')

@section('content')



		<style>
			.head-container {
            background: linear-gradient(135deg, rgba(214, 216, 255, 0.9), rgba(227, 195, 255, 0.9));
            /* background: linear-gradient(white, white) padding-box,
                  linear-gradient(135deg, #6e40c9, #007fff) border-box;
      border-bottom: 4px solid transparent; */
            border-radius: 0 0 1.5rem 1.5rem;
            padding: 2rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.09);
            animation: fadeIn 1s ease-in-out;
            text-align: center;
        }

        .header-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }



        .logo-img {
            position: absolute;
            right: 20px;
            width: 80px;
            height: 80px;
            mix-blend-mode: multiply;
            animation: fadeInUp 1s ease-in-out;
        }
		.primary-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(to right, #6e40c9, #007fff);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 1.2s ease-in-out;
        }

        .primary-button:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .icon-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            animation: fadeInUp 1.4s ease-in-out;
        }

        .icon-item {
            background: linear-gradient(white, white) padding-box,
                linear-gradient(135deg, #6e40c9, #007fff) border-box;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.09);
            transition: transform 0.3s ease;
            text-align: center;
        }

        .icon-item:hover {
            transform: scale(1.05);
            box-shadow: 2px 8px 18px rgba(0, 0, 0, 0.15);
        }

        .icon-item img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            mix-blend-mode: multiply;
            margin-bottom: 0.5rem;
        }

        .icon-item p {
            font-size: 0.8rem;
            background: linear-gradient(135deg, #6e40c9, #007fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
            margin: 0;
        }



			.form-container {
				max-width: 100%;
				border-radius: 1.2rem;
				padding: 1.2rem;
				background:
					linear-gradient(white, white) padding-box,
					linear-gradient(180deg, #45119E, #0182CB) border-box;
				border: 0.3rem solid transparent;
				box-shadow:
					rgba(50, 50, 93, 0.25) 0px 6px 12px -2px,
					rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
				color: #222;
			}

			label {
				font-size: .9rem;
				font-weight: 600;
				color: #333;
			}

			.form-control,
			textarea {
				background-color: #f9f9f9;
				height: 55px !important;
				color: #333;
				border-radius: 0.8rem;
				border: 2px solid #ccc;
				width: 100%;
				padding: 0.75rem 1rem;
				font-size: .9rem;
				outline: none;
				transition: border-color 0.3s, box-shadow 0.3s;
			}

			textarea.form-control {
				height: auto;
				min-height: 120px;
				resize: vertical;
			}

			.form-control:focus {
				border-color: #45119E;
				box-shadow: 0 0 0 4px rgba(69, 17, 158, 0.2);
				background-color: #fff;
			}


			.form-row {
				display: flex;
				gap: 1rem;
				flex-wrap: wrap;
			}

			.form-field {
				margin-bottom: 1.2rem;
				width: 100%;
			}

			.form-field.half {
				flex: 1 1 48%;
			}

			.custom-file-upload {
				display: flex;
				align-items: center;
				gap: 0.6rem;
				font-weight: 600;
				cursor: pointer;
				padding: 0.75rem 1rem;
				background-color: #f9f9f9;
				border-radius: 0.8rem;
				border: 2px dashed #ccc;
				color: #555;
			}

			.file-input {
				display: none;
			}

			.upload-text {
				font-size: 0.95rem;
			}

			.order-btn {
				width: 100% !important;
				background-color: #f1c40f;
				color: black;
				font-weight: 600;
				border-radius: 0.8rem;
				padding: 0.8rem 1.2rem;
				border: none;
				margin: auto;
				font-size: 1.2rem;
				transition: all .3s ease;
			}

			.order-btn:hover {
				background-color: #d4ac0d;
				color: black;
			}

			h2.form-heading {
				font-weight: 700;
				font-size: 1.8rem;
				color: #3f159a;
				text-align: center;
			}

			/* Base Card Style */
			.estimate-card,
			.order-summary-card
			 {
				background-color: #fff;
				box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
				border-radius: 10px;
				margin-bottom: 1.5rem;
				padding: 1.5rem;
				font-family: 'Roboto', sans-serif;
			}

			/* Estimated Price Card */
			.estimate-content {
				text-align: center;
			}

			.estimate-price {
				font-size: 1.5rem;
				color: #434343;
				margin-bottom: 1rem;
				display: block;
				color: #45119E;
			}

			.price-details {
				display: flex;
				justify-content: center;
				align-items: center;
				gap: 0.5rem;
			}

			.basePrice {
				text-decoration: line-through;
				color: red;
				font-size: 1.1rem;
				font-weight: 600;
			}

			.priceoff {
				font-size: 1rem;
				font-weight: 600;
				text-transform: uppercase;
				background: #38bd47;
				border-radius: 4px;
				padding: 2px 8px;

				color: #fff;
			}

			.final-price {
				color: #FFBD23;
				font-size: 3rem;
				font-weight: 800;
				margin: 0;
			}

			.offer-price {
				padding-left: 4px;
			}

			/* Order Summary Card */
			.order-summary-title {
				background-color: #FFBD23;
				color: #111;
				text-align: center;
				font-weight: 600;
				padding: 10px;
				margin: -1.5rem -1.5rem 1rem -1.5rem;
				border-top-left-radius: 10px;
				border-top-right-radius: 10px;
			}

			.order-summary-content {
				background-color: #f0f8ff;
				padding: 10px;
				border-radius: 5px;
			}

			/* Promo Card */
			.promo-image {
				width: 100%;
				height: auto;
				border-radius: 10px;
				box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
			}

			.order-web{
				border-radius: 10px;
			}

			/* Ensure mobile responsiveness for split fields */
			@media (max-width: 768px) {
				.form-field.half {
					flex: 1 1 100%;
				}
			}

			.price-summary {
		margin-top: 1rem;
		overflow-x: auto;
	}

	.price-table {
		width: 100%;
		border-collapse: separate;
		border-spacing: 0;
		background: #f9f9f9;
		border-radius: 12px;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
		overflow: hidden;
	}

	.price-table th,
	.price-table td {
		padding: 12px 16px;
		text-align: left;
		border-bottom: 1px solid #e0e0e0;
	}


	.price-table th {
		background-color: #eef1f5;
		color: #333;
		font-weight: 800;
		width: 40%;
	}
	.price-table td {
		background-color: #fff;
		color: #444;
		font-size: 0.9rem;
		font-weight: 600;
	}

	.price-table tr:last-child td {
		border-bottom: none;
	}

	.price-table td strong {
		color: #45119E;
		font-weight: 800;
		font-size: 1rem;
	}


			/* Section Container */
			.order-benefits {
				padding: 20px;
				background-color: #ae8ce952;
				border-radius: ;
				10px;
			}

			/* Inner Card */
			.benefits-container {
				max-width: 800px;
				margin: auto;
				background: #ffffff;
				padding: 30px;
				box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.08);
				border-radius: 10px;
			}

			/* Title */
			.benefits-title {
				font-size: 1.5em;
				color: #222;
				margin-bottom: 20px;
				font-weight: 600;
				text-align: center;
			}

			/* Benefit List */
			.benefits-list {
				list-style: none;
				color: #444;
				font-size: 1em;
				line-height: 1.7;
			}

			/* Each List Item */
			.benefits-list li {
				position: relative;
				padding-left: 30px;
				margin-bottom: 15px;
			}

			/* Green Checkmark */
			.checkmark {
				position: absolute;
				left: 0;
				top: 0;
				color: #4caf50;
				font-weight: bold;
				font-size: 1.2em;
			}
		</style>
		<div class="head-container mt-6 px-4 py-6">
        <div class="header-content">
            <img src="assets/media/avatars/assignment_logo.png" alt="Assignment Logo" class="logo-img" />
            <h1 class="section-title">Upload Your Assignment for Instant Expert Assistance</h1>
        </div>
		<a href="/free-samples" class="primary-button mt-4">Get 100% Original Assignments</a>

        <div class="icon-section mt-6">
            <div class="icon-item">
                <img src="/images2/certificate.png" alt="100% Original Document" />
                <p>100% Original</p>
            </div>
            <div class="icon-item">
                <img src="/images2/sketch-book.png" alt="AI Free Content" />
                <p>AI Free Content</p>
            </div>
            <div class="icon-item">
                <img src="/images2/personal-data.png" alt="Free CV" />
                <p>Free CV on Every Order</p>
            </div>
            <div class="icon-item">
                <img src="/images2/24-hours.png" alt="24/7 Availability" />
                <p>24×7 Available</p>
            </div>
            <div class="icon-item">
                <img src="/images2/role-model.png" alt="Experienced Writers" />
                <p>Experienced Writers</p>
            </div>
            <div class="icon-item">
                <img src="/images2/article.png" alt="Plagiarism Report" />
                <p>Plagiarism Report</p>
            </div>
        </div>
		</div>
	
		<section style="padding-top: 20px;">
			<div class="container py-4">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="cristmas" style="box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.10); background-color: #ffffff;">
							<a href="Offers">
								<img src="/images/offer-banner-landscape.png" width="100%" height="100%" alt="login-popup"
									class="order-web">
								<!-- <img src="/images/order-strip-mobile-GAH.svg?v=6" width="100%" height="100%" alt="login-popup"
									class="order-mobile"> -->
							</a>
						</div>
					</div>

				</div>
				<div class="row mt-3">
					<div class="col-lg-8 col-md-12 col-sm-12">
						<div class="">
							<form id="placeOrder" method="POST" enctype="multipart/form-data"
								class="form-container">
								@csrf
								<h2 class="form-heading">Place Your Order</h2>

								<div class="form-field">
									<label for="serviceSelect">Services</label>
									<select id="serviceSelect" name="service" required class="form-control">
										<option value="">Select Services</option>
										<option value="Assignment">Assignment</option>
										<option value="Dissertation">Dissertation</option>
										<option value="Thesis">Thesis</option>
										<option value="Research Project">Research Project</option>
									</select>
								</div>

								<div class="form-row">
									<div class="form-field half">
										<label for="workTypeSelect">Work Type</label>
										<select id="workTypeSelect" name="workType" required class="form-control">
											<option value="">Select Work Type</option>
											<option value="Standard">Standard</option>
											<option value="FirstClass">First Class Work</option>
										</select>
									</div>

									<div class="form-field half">
										<label for="subjectSelect">Subject</label>
										<select id="subjectSelect" name="subject" required class="form-control">
											<option value="">Select Subject</option>
											<option value="Matlab">Matlab</option>
											<option value="Data Science">Data Science</option>
											<option value="Engineering">Engineering</option>
											<option value="App Development">App Development</option>
											<option value="Web Development">Web Development</option>
											<option value="Exam">Exam</option>
											<option value="Public Health">Public Health</option>
											<option value="Presentation">Presentation (PPT)</option>
											<option value="Portfolio">Portfolio</option>
											<option value="Research Report">Research Report</option>
											<option value="Business Management">Business Management</option>
											<option value="Project Management">Project Management</option>
											<option value="Essay">Essay</option>
											<option value="HRM">HRM</option>
											<option value="Economic">Economic</option>
											<option value="Other">Other</option>
										</select>
									</div>
								</div>

								<div class="form-row">
									<div class="form-field half">
										<label for="urgencySelect">Select Urgency</label>
										<select id="urgencySelect" name="urgency" required class="form-control">
											<option value="">Select Urgency</option>
											@for ($i = 1; $i <= 15; $i++)
												<option value="{{ $i }}">{{ $i }} Day{{ $i > 1 ? 's' : '' }}</option>
											@endfor
											<option value="16 to 20">16-20 Days</option>
											<option value="21+">21+ Days</option>
										</select>
									</div>

									<div class="form-field half">
										<label for="wordCount">Word Count</label>
										<input type="number" name="wordCount" id="wordCount" min="250" value="250" required
											class="form-control" placeholder="Enter Count">
									</div>
								</div>

								@if(Auth::user() == '')
									<div class="form-row">
										<div class="form-field half">
											<label for="email">Enter Your Email Here</label>
											<input type="email" name="email" required class="form-control"
												placeholder="Your Email Address*">
										</div>
										<div class="form-field half">
											<label for="phone_number_input">Enter Your Contact Details Here</label>
											<input type="hidden" name="countrycode" id="hidden_country_code">
											<input type="tel" name="mobile" id="phone_number_input" required class="form-control"
												placeholder="Phone*">
											<input type="hidden" name="full_phone_number" id="hidden_phone_number">
										</div>
									</div>
								@endif

								<div class="form-field">
									<label for="enterTopic">Enter Topic</label>
									<input type="text" name="topic" id="enterTopic" class="form-control"
										placeholder="Enter Topic" required>
								</div>

								<div class="form-field">
									<label for="requirements">Specify Your Requirements Here</label>
									<textarea name="requirements" id="requirements" class="form-control"
										placeholder="The more instructions you provide, the better our writers can craft a paper that meets your needs."
										required></textarea>
								</div>

								<div class="form-field">
									<label for="fileUpload" class="custom-file-upload">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
											stroke-width="2">
											<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
											<polyline points="17 8 12 3 7 8"></polyline>
											<line x1="12" y1="3" x2="12" y2="15"></line>
										</svg>
										<span class="upload-text">Drop file here or Click to upload</span>
									</label>
									<input type="file" name="fileUpload[]" id="fileUpload" class="file-input" multiple>
								</div>

								<script>
									document.getElementById('fileUpload').addEventListener('change', function (event) {
										var fileName = event.target.files.length > 0 ? event.target.files[0].name : 'Drop file here or Click to upload';
										document.querySelector('.upload-text').textContent = fileName;
									});
								</script>

								<input type="hidden" name="estimatedPrice" id="estimatedPrice" value="">
								<input type="hidden" name="discount" id="discount" value="">
								<input type="hidden" name="finalPrice" id="finalPrice" value="">

								<div class="form-field">
									<label><input type="checkbox" id="agreeCheckbox" required> I agree with Privacy Policy and
										Terms & Conditions</label>
								</div>

								<div class="form-field">
									<div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
								</div>

								<button id="placeOrderBtn" class="btn order-btn" type="submit">Place Order</button>
							</form>

						</div>

					</div>
					<div class="col-lg-4 col-md-12 col-sm-12">
						<!-- Estimated Price Card -->
						<div class="estimate-card">
							<div class="estimate-content">
								<label class="estimate-price">Estimated Price</label>
								<div class="price-details">
									<span class="basePrice">£100</span>
									<span class="priceoff">40% OFF</span>
								</div>
								<p class="final-price">£<span class="value offerPrice">75</span></p>
							</div>
						</div>

						<!-- Order Summary Card -->
						<div class="order-summary-card">
							<h6 class="order-summary-title">Order Summary</h6>
							<div id="orderSummary" >
								<!-- Summary details will be dynamically added here -->
							</div>
						</div>

						<!-- Image Banner Card -->

							<a>
								<img src="/images/offer-banner-vertical.png" alt="promo" class="promo-image" />
							</a>

					</div>
					<div class="col-lg-8 col-md-12 col-sm-12 pt-4">
						<section class="order-benefits">
							<div class="benefits-container">
								<h2 class="benefits-title">With Every Order, You Will Receive These Benefits:</h2>
								<ul class="benefits-list">
									<li><span class="checkmark">✔</span>120% money-back guarantee if we fail to deliver.</li>
									<li><span class="checkmark">✔</span>On-time delivery guaranteed.</li>
									<li><span class="checkmark">✔</span>Free plagiarism report with every order.</li>
									<li><span class="checkmark">✔</span>24/7 customer support for any queries.</li>
									<li><span class="checkmark">✔</span>Complete ownership of your work.</li>
									<li><span class="checkmark">✔</span>Privacy of your confidential information.</li>
									<li><span class="checkmark">✔</span>Unlimited free revisions if needed.</li>
									<li><span class="checkmark">✔</span>Exclusive offers and discounts for clients.</li>
								</ul>
							</div>
						</section>
					</div>
				</div>
			</div>
		</section>


		<section class="courses-section-three pt-4">
			<div class="auto-container">
				<div class="row clearfix">
					<div class="feature-block-six col-lg-12 col-md-12 col-sm-12">
						<div class="inner-box">
							<a class="overlay-box"></a>
							<h4>Why Students Love Us</h4>
							<div class="text">My Assignment Services is dedicated towards Making Assignments Simpler for
								students. <br> Our PhD experts ensure HD Quality work within <br>deadline for higher grades.
							</div>
						</div>
					</div>
					<!-- Feature Block Six -->
					<div class="feature-block-six col-lg-3 col-md-6 col-sm-12">
						<div class="inner-box">
							<a class="overlay-box"></a>
							<div class="icon fa fa-comments"></div>
							<h4>Customer <br> Feedback</h4>
						</div>
					</div>

					<!-- Feature Block Six -->
					<div class="feature-block-six col-lg-3 col-md-6 col-sm-12">
						<div class="inner-box">
							<a class="overlay-box"></a>
							<div class="icon flaticon-team"></div>
							<h4>200+ <br> Ph.d Experts </h4>
						</div>
					</div>

					<!-- Feature Block Six -->
					<div class="feature-block-six col-lg-3 col-md-6 col-sm-12">
						<div class="inner-box">
							<a class="overlay-box"></a>
							<div class="icon fa fa-clock-o"></div>
							<h4>Delivery Before <br> The Deadline</h4>
						</div>
					</div>

					<!-- Feature Block Six -->
					<div class="feature-block-six col-lg-3 col-md-6 col-sm-12">
						<div class="inner-box">
							<a class="overlay-box"></a>
							<div class="icon fa fa-edit"></div>
							<h4>Unlimited <br> Modification</h4>
						</div>
					</div>

					<!-- Feature Block Six -->
					<div class="feature-block-six col-lg-3 col-md-6 col-sm-12">
						<div class="inner-box">
							<a class="overlay-box"></a>
							<div class="icon fa fa-lock" style="color: #0f52b4;"></div>
							<h4>Privacy <br> Protection</h4>
						</div>
					</div>

					<!-- Feature Block Six -->
					<div class="feature-block-six col-lg-3 col-md-6 col-sm-12">
						<div class="inner-box">
							<a class="overlay-box"></a>
							<div class="icon flaticon-check" style="color: #00ddb7;"></div>
							<h4>Plagiarism <br> Free Work</h4>
						</div>
					</div>

					<!-- Feature Block Six -->
					<div class="feature-block-six col-lg-3 col-md-6 col-sm-12">
						<div class="inner-box">
							<a class="overlay-box"></a>
							<div class="icon fa fa-money" style="color: #d500ff;"></div>
							<h4>Easy <br> Refunds</h4>
						</div>
					</div>

					<!-- Feature Block Six -->
					<div class="feature-block-six col-lg-3 col-md-6 col-sm-12">
						<div class="inner-box">
							<a class="overlay-box"></a>
							<div class="icon fa fa-key" style="color: #ff0000;"></div>
							<h4>Authentic <br> Resources</h4>
						</div>
					</div>
				</div>
			</div>
		</section>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>

		<script>
			document.addEventListener('DOMContentLoaded', function () {
				var phoneInput = document.querySelector("#phone_number_input");
				var phoneInputInstance = window.intlTelInput(phoneInput, {
					separateDialCode: true,
					preferredCountries: ["gb"],
					utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
				});

				function updatePhoneNumber() {
					var fullNumber = phoneInputInstance.getNumber();
					var countryData = phoneInputInstance.getSelectedCountryData();
					var countryCode = countryData.dialCode;

					document.querySelector("#hidden_phone_number").value = fullNumber;
					document.querySelector("#hidden_country_code").value = countryCode;
				}

				updatePhoneNumber();

				phoneInput.addEventListener('input', function () {
					updatePhoneNumber();
				});

				phoneInput.addEventListener('countrychange', function () {
					updatePhoneNumber();
				});
			});
		</script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				var encryptionKey = "your-encryption-key"; // Replace with a strong key
				var iv = CryptoJS.enc.Hex.parse('1234567890123456'); // Example IV

				function decryptData(encryptedData) {
					try {
						var bytes = CryptoJS.AES.decrypt(encryptedData, encryptionKey, {
							iv: iv,
							mode: CryptoJS.mode.CBC,
							padding: CryptoJS.pad.Pkcs7
						});
						return bytes.toString(CryptoJS.enc.Utf8);
					} catch (error) {
						console.error('Decryption failed:', error);
						return 'Decryption error';
					}
				}

				// Retrieve encrypted data from query parameters
				var urlParams = new URLSearchParams(window.location.search);
				var encryptedService = urlParams.get('service');
				var encryptedWorkType = urlParams.get('workType');
				var encryptedSubject = urlParams.get('subject');
				var encryptedUrgency = urlParams.get('urgency');
				var encryptedWordCount = urlParams.get('wordCount');
				var encryptedPrice = urlParams.get('price');
				var encryptedBasePrice = urlParams.get('basePrice');
				var encryptedDiscount = urlParams.get('discount');

				// Check if any query parameters are present
				if (encryptedService || encryptedWorkType || encryptedSubject || encryptedUrgency || encryptedWordCount || encryptedPrice || encryptedBasePrice || encryptedDiscount) {
					// Decrypt data
					var decryptedService = decryptData(encryptedService);
					var decryptedWorkType = decryptData(encryptedWorkType);
					var decryptedSubject = decryptData(encryptedSubject);
					var decryptedUrgency = decryptData(encryptedUrgency);
					var decryptedWordCount = decryptData(encryptedWordCount);
					var decryptedPrice = decryptData(encryptedPrice);
					var decryptedBasePrice = decryptData(encryptedBasePrice);
					var decryptedDiscount = decryptData(encryptedDiscount);

					// Populate form with decrypted data
					function populateFormWithDecryptedData() {
						$('#serviceSelect').val(decryptedService);
						$('#workTypeSelect').val(decryptedWorkType);
						$('#subjectSelect').val(decryptedSubject);
						$('#urgencySelect').val(decryptedUrgency);
						$('#wordCount').val(decryptedWordCount);
						$('#enterTopic').val(decryptedTopic);
						$('#requirements').val(decryptedRequirements);
					}

					populateFormWithDecryptedData();
				} else {
					// If no query parameters, clear the form or set placeholders
					function clearForm() {
						$('#serviceSelect').val('').attr('placeholder', 'Please select');
						$('#workTypeSelect').val('').attr('placeholder', 'Please select');
						$('#subjectSelect').val('').attr('placeholder', 'Please select');
						$('#urgencySelect').val('').attr('placeholder', 'Please select');
						$('#wordCount').val('').attr('placeholder', 'Please enter');
						$('#enterTopic').val('');
						$('#requirements').val('');
					}

					clearForm();
				}
			});
		</script>
		<script>
			$(document).ready(function () {
				function calculatePrice() {
					var service = $('#serviceSelect').val();
					var type = $('#workTypeSelect').val();
					var urgency = $('#urgencySelect').val();
					var wordCount = Math.max($('#wordCount').val(), 250);
					var subject = $('#subjectSelect').val();

					var basePricePerWord = 0.03;
					var basePrice = basePricePerWord * wordCount;
					// Apply word count multipliers
					if (wordCount >= 250 && wordCount < 500) {
						basePrice *= 2.67;
					} else if (wordCount >= 500 && wordCount < 1000) {
						basePrice *= 2.22;
					} else if (wordCount >= 1000 && wordCount < 2000) {
						basePrice *= 1.94;
					} else if (wordCount >= 2000 && wordCount < 3000) {
						basePrice *= 1.67;
					} else if (wordCount >= 3000 && wordCount < 4000) {
						basePrice *= 1.30;
					} else if (wordCount >= 4000 && wordCount < 5000) {
						basePrice *= 1.13;
					} else if (wordCount >= 5000) {
						basePrice *= 1.17;
					}
					var serviceMultiplier = (service === 'Dissertation' || service === 'Thesis' || service === 'Research Project') ? 1.1 : 1;
					var typeMultiplier = (type === 'FirstClass') ? 1.3 : 1;
					var urgencyMultiplier = 1;

					switch (urgency) {
						case '1': urgencyMultiplier = 2.0; break;
						case '2': urgencyMultiplier = 1.5; break;
						case '3': urgencyMultiplier = 1.4; break;
						case '4': urgencyMultiplier = 1.3; break;
						case '5': urgencyMultiplier = 1.2; break;
						case '6': urgencyMultiplier = 1.15; break;
						case '7': urgencyMultiplier = 1.1; break;
						case '8': urgencyMultiplier = 1.09; break;
						case '9': urgencyMultiplier = 1.07; break;
						case '10': urgencyMultiplier = 1.05; break;
						case '11': urgencyMultiplier = 1.04; break;
						case '12': urgencyMultiplier = 1.03; break;
						case '13': urgencyMultiplier = 1.02; break;
						case '14': urgencyMultiplier = 1.01; break;
						case '15': urgencyMultiplier = 1.0; break;
						case '16 to 20': urgencyMultiplier = 0.95; break;
						case '21+': urgencyMultiplier = 0.9; break;
					}

					var estimatedPrice = basePrice * serviceMultiplier * typeMultiplier * urgencyMultiplier;

					var discountPercentage = parseFloat($('.priceoff').text().replace('% OFF', '').trim()) / 100;
					var discount = estimatedPrice * discountPercentage;

					var finalPrice = estimatedPrice - discount;

					// Update the price quotation and estimated price
					$('.offerPrice').text(finalPrice.toFixed(2));
					$('.basePrice').text('£ ' + estimatedPrice.toFixed(2));

					updateSummary(service, type, subject, urgency, wordCount, estimatedPrice, finalPrice, discountPercentage, discount);
				}

				function updateSummary(service, type, subject, urgency, wordCount, estimatedPrice, finalPrice, discountPercentage, discount) {
					var summaryHtml = `
								<div class="price-summary">
		<table class="price-table">
			<tbody>
				<tr><th>Service</th><td>${service}</td></tr>
				<tr><th>Work Type</th><td>${type}</td></tr>
				<tr><th>Subject</th><td>${subject || 'N/A'}</td></tr>
				<tr><th>Urgency</th><td>${urgency} Days</td></tr>
				<tr><th>Word Count</th><td>${wordCount}</td></tr>
				<tr><th>Estimated Price</th><td>£${estimatedPrice.toFixed(2)}</td></tr>
				<tr><th>Discount</th><td>${(discountPercentage * 100).toFixed(0)}% (£${discount.toFixed(2)})</td></tr>
				<tr><th>Final Price</th><td><strong>£${finalPrice.toFixed(2)}</strong></td></tr>
			</tbody>
		</table>
	</div>

							`;
					$('#orderSummary').html(summaryHtml);

					// Update the hidden input fields
					$('#estimatedPrice').val(estimatedPrice.toFixed(2));
					$('#discount').val(`${(discountPercentage * 100).toFixed(0)}% (£${discount.toFixed(2)})`);
					$('#finalPrice').val(finalPrice.toFixed(2));
				}

				// Trigger price calculation and summary update when any input changes
				$('#serviceSelect, #workTypeSelect, #subjectSelect, #urgencySelect, #wordCount').on('change keyup', function () {
					calculatePrice();
				});

				// Trigger initial calculation and summary update
				calculatePrice();

			});

		</script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				const form = document.getElementById('placeOrder');
				const submitBtn = document.getElementById('placeOrderBtn');

				// Optional: Create a toast element like your previous code
				const showToast = (msg, success = false) => {
					Swal.fire({
						icon: success ? 'success' : 'error',
						title: success ? 'Success' : 'Error',
						text: msg
					});
				};

				form.addEventListener('submit', async function (event) {
					event.preventDefault();

					const formData = new FormData(form);

					// Add hidden phone fields (if intlTelInput is being used)
					const phoneInput = document.querySelector("#phone_number_input");
					if (phoneInput && window.intlTelInput) {
						const iti = window.intlTelInputGlobals.getInstance(phoneInput);
						formData.set('full_phone_number', iti.getNumber());
						formData.set('countrycode', iti.getSelectedCountryData().dialCode);
						console.log('window intltelinput')
					}

					submitBtn.disabled = true;
					submitBtn.textContent = 'Placing Order...';

					try {
						const response = await fetch("{{ route('upload-from-order-now') }}", {
							method: 'POST',
							headers: {
								'X-CSRF-TOKEN': "{{ csrf_token() }}"
							},
							body: formData
						});

						const data = await response.json();

						if (response.ok && data.success) {
							form.reset();
							showToast(data.message || 'Order placed successfully!', true);
							// Redirect to thank-you page after a delay
							setTimeout(() => {
								window.location.href = "{{ route('thank-you') }}";
							}, 2000);
						} else {
							showToast(data.message || 'Something went wrong.');
						}
					} catch (error) {
						console.error(error);
						showToast('An error occurred while placing your order.');
					} finally {
						submitBtn.disabled = false;
						submitBtn.textContent = 'Place Order';
					}
				});
			});
		</script>

@endsection