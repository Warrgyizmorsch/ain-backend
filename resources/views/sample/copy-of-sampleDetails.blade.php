@extends('frontend-layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
  table,
  th,
  td {
    margin-top: 10px;
    border: 1px solid black;
    border-collapse: collapse;
  }

  td {
    padding: 2px;
  }

  .tablePractice {
    margin-top: 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 5px;
    width: 100%;
    font-family: Arial, sans-serif;
    /* box-sizing: border-box; */
  }

  /* Title styling */
  .tablePractice p {
    text-align: center;
    font-weight: bold;
    font-size: 16px;
    margin-bottom: 1px;
  }

  /* List styling */
  .tablePractice ul {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    /* Three columns */
    gap: 5px;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  /* List item styling */
  .tablePractice ul li {
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 3px;
    font-size: 14px;
    text-align: left;
  }

  .tablePractice ul li span {
    font-weight: bold;
  }

  /* Responsive styling */
  @media (max-width: 768px) {
    .tablePractice ul {
      grid-template-columns: repeat(2, 1fr);
      /* Two columns */
    }
  }

  @media (max-width: 480px) {
    .tablePractice ul {
      grid-template-columns: 1fr;
      /* Single column */
    }
  }




  @media (max-width: 768px) {
    table {
      width: 60% display: block;
      border: none;
    }

    td {
      padding: 5px;
    }
  }

  .place-order {
    background: #d7f0fd;
    color: black;
    padding: 10px 20px;
    border-radius: 5%;
    margin: 10px;

  }


  .place-order:hover {
    background: #7e89dd;
    color: white;
    cursor: pointer;

  }

  .order-button {
    display: block;
    background-color: #007BFF;
    color: white;
    padding: 10px 20px;
    text-align: center;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    margin: 10px auto;
    transition: background-color 0.3s;
  }

  .offerPara {
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    margin: 10px 0;
    color: #333;
  }

  .banner-section-three {
    font-size: 16px;
  }

  h4 {
    color: black;
  }

  .content-container {
    margin-top: 3rem;
    text-align: center;
    /* Center-aligns content inside */
    padding: 20px;
    font-family: Arial, sans-serif;
    /* Clean, modern font */
    max-width: 600px;
    /* Restrict width for better readability */
    margin-left: auto;
    margin-right: auto;
  }

  .content-container button {

    font-size: 1rem;
    font-weight: bold;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  .content-container p {
    margin-top: 20px;
    font-size: 16px;
    line-height: 1.6;
    color: #333;
    /* Darker text for better readability */
  }


  /* Base styles (Desktop) */
  .slider-container {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    margin: 50px auto;
    max-width: 100%;
  }

  .card-slider {
    display: flex;
    gap: 20px;
    overflow-x: hidden;
    /* Allow horizontal scrolling */
    scroll-behavior: smooth;
    width: 91%;
  }

  .card {
    flex: 0 0 calc(33.33% - 20px);
    /* 3 cards per row on desktop */
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
  }

  .card h4 {
    font-size: 1.2rem;
    margin-bottom: 10px;
    font-weight: bold;
  }

  .card p {
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 20px;
    color: #555;
  }

  .link {
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
  }

  .link:hover {
    text-decoration: underline;
  }

  .arrow {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: #333;
    padding: 10px;
    transition: color 0.3s ease;
  }

  .arrow:hover {
    color: #ff5722;
  }

  .left-arrow {
    position: absolute;
    left: 0;
  }

  .right-arrow {
    position: absolute;
    right: 0;
  }

  /* Mobile view adjustments */
  @media (max-width: 768px) {
    .card {
      flex: 0 0 calc(50% - 20px);
      /* 2 cards per row on tablet */
    }

    .card h4 {
      font-size: 1rem;
      /* Slightly smaller font */
    }

    .card p {
      font-size: 0.85rem;
      /* Adjust paragraph font size */
    }

    .arrow {
      display: none;
      /* Hide arrows if swipe navigation is preferred */
    }
  }

  @media (max-width: 480px) {
    .card {
      flex: 0 0 calc(100% - 20px);
      /* 1 card per row on mobile */
    }

    .card h4 {
      font-size: 0.9rem;
      /* Further reduce font size on small screens */
    }

    .card p {
      font-size: 0.8rem;
      /* Adjust paragraph font size for smaller screens */
    }
    h1 {
        font-size: 30px !important;
        line-height: 1.4em;
    }

    h2 {
        font-size: 27px !important;
       
    }
    h1 {
        font-size: 24px !important;
       
    }
  }
</style>

<style>
  /* Base styles for the container */


  /* Adjustments for mobile view */
  @media (max-width: 768px) {
    .d-flex {
      flex-direction: column;
      gap: 10px;
      /* Keep spacing consistent */
      padding: 8px;
      width: 100%;
      /* Full-width container */
    }

    .btn {
      font-size: 0.85rem;
      /* Slightly smaller text */
      padding: 6px 12px;
      /* Adjust padding for smaller buttons */
    }

    .btn i {
      font-size: 0.9rem;
      /* Adjust icon size */
    }
  }

  @media only screen and (max-width: 1140px) {
    h2 {
      font-size: 27px;
    }
    h2 {
      font-size: 24px;
    }
  }

  h2 {
    font-size: 29px;
  }
  h1 {
    font-size: 36px;
  }
  h3 {
    font-size: 25px;
}
</style>
<section class="banner-section-three header-section">
  <div class="auto-container" style="margin-top: 100px;">

  </div>
</section>
<section class="banner-section-three">
  <div class=container>
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="row">

        <div class="col-lg-8 col-md-12 col-sm-12">
          <h1 style="font-weight: 500; color: black;" class="mb-2 mt-5">{{$sample->title ?? ''}}</h1>
          <div class="tablePractice">
            <p style="text-align:center"> <span>University</span></p>

            <ul>
              <li><span>Unit No:</span>0</li>
              <li><span>Level: </span>High School</li>
              <li><span>Pages:</span>6 / Words 1593</li>
              <li><span>Paper type:</span>Minor Case Study</li>
              <li><span>Course Code: </span></li>
              <li><span>Downloads:</span>63838</li>
            </ul>
          </div>
          <div class=mt-3 style="text-align: justify;">
          <h2 class="mt-3 mb-3"><span style="background-color: rgb(255, 255, 255); color: rgb(6, 20, 45);">Introduction</span></h2> 
            @if(auth()->user())
            {!!$sample->content ?? ''!!}
          @else

         {!! Str::limit($sample->content, 1000, '...') !!}
      @endif
          </div>
          @if(!auth()->user())
        <div class="offer-section" style="position: relative; text-align: justify; margin-top: 3rem;">
        <!-- Blurred Content -->
        <div class="blurred-content"
          style="filter: blur(4px); padding: 1rem; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
          {!! Str::limit($sample->content, 1000, '...') !!}
        </div>
        <!-- Offer Overlay -->
        <div class="offer-overlay"
          style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; background-color: rgba(0, 0, 0, 0.7); color: white; padding: 2rem; border-radius: 12px; width: 80%; box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);">
          <h2 style="font-size: 1.7rem; font-weight: bold; margin-bottom: 1rem;">Exclusive Offer On Assignment Help </h2>
          <p style="font-size: 1.2rem; margin-bottom: 1.5rem; text-align:justify">Unlock the full content and get <span
            style="font-weight: bold; color: #FFD700;">40% OFF</span> And Free <span 
            style="font-weight: bold; color: #FFD700;">CV</span> On Every Assignment Order</p>
          <a href="{{ route('register') }}"
          style="background-color: #007BFF; color: white; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-size: 1rem; font-weight: bold;">Sign
          Up Now</a>
        </div>
        </div>
      @endif

        </div>

       <!-- <div class="sidebar-side col-lg-4 col-md-12 col-sm-12">
          <aside class="sidebar sticky-top">

            
            <div class="sidebar-widget search-box" style="background-color: aliceblue;">
              <form id="orderForm" action="/neworder-fromhome" method="POST" enctype="multipart/form-data">
                <div class="form-header text-center p-2">
                  <h2 class="order-now">Pricing</h2>
                </div>
                @csrf
                <div class="contact-form p-4">

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="serviceSelect">Services</label>
                        <select required id="serviceSelect" style="background-color:#f1f1f1; height: 45px;"
                          class="form-control">
                          <option value="">Select Services</option>
                          <option value="Assignment">Assignment</option>
                          <option value="Dissertation">Dissertation</option>
                          <option value="Thesis">Thesis</option>
                          <option value="Research Project">Research Project</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12 d-none">
                      <div class="form-group">
                        <label for="workTypeSelect">Work Type</label>
                        <select id="workTypeSelect" style="background-color:#f1f1f1; height: 45px;"
                          class="form-control">
                          <option value="">Select Work Type</option>
                          <option selected value="Standard">Standard</option>
                          <option value="FirstClass">First Class Work</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12" id="subjectContainer">
                      <div class="form-group">
                        <label for="subjectSelect">Subject</label>
                        <select required id="subjectSelect" style="background-color:#f1f1f1; height: 45px;"
                          class="form-control">
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

                    <div class="col-md-6" id="selectUrgency">
                      <div class="form-group">
                        <label for="urgencySelect">Select Urgency</label>
                        <select required id="urgencySelect" style="background-color:#f1f1f1; height: 45px;"
                          class="form-control">
                          <option value="">Select Urgency</option>
                          <option value="1">1 Day</option>
                          <option value="2">2 Days</option>
                          <option value="3">3 Days</option>
                          <option value="4">4 Days</option>
                          <option value="5">5 Days</option>
                          <option value="6">6 Days</option>
                          <option value="7">7 Days</option>
                          <option value="8">8 Days</option>
                          <option value="9">9 Days</option>
                          <option value="10">10 Days</option>
                          <option value="11">11 Days</option>
                          <option value="12">12 Days</option>
                          <option value="13">13 Days</option>
                          <option value="14">14 Days</option>
                          <option value="15">15 Days</option>
                          <option value="16 to 20">16-20 Days</option>
                          <option value="21+">21+ Days</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6" id="wordCountContainer">
                      <div class="form-group">
                        <label id="wordLabel" for="wordCount">Word Count</label>
                        <input required type="number" id="wordCount" min="250" value="250"
                          style="background-color:#f1f1f1; height: 45px;" class="form-control"
                          placeholder="Enter Count">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="Estimate-price" style="text-align: center; position: relative; z-index: 1;">
                        <label style="font-size: 16px; line-height: 18px; color: #434343;">Estimated
                          Price</label>
                        <span class="basePrice"
                          style="text-decoration: line-through; color: #616161; font-size: 18px; line-height: 20px; margin: 0 10px;">£
                          100</span>
                        <span class="priceoff"
                          style="font-size: 18px; font-family: robotomedium; text-transform: uppercase; background: #5c7a36; border-radius: 3px; padding: 3px; color: #fff;">40%
                          OFF</span>
                        <p class="symbole text-center"
                          style="color: #f58e0e; font-size: 36px; line-height: 40px; font-family: robotomedium; margin: 8px 0 15px;">
                          £ <span
                            style="color: #f58e0e; font-size: 36px; line-height: 40px; font-family: robotomedium; padding: 8px 0 15px;"
                            class="value offerPrice">75</span></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 text-center mt-2">
                    <button class=" place-order" type="submit">Place Order</button>
                   
                  </div>
                </div>
              </form>
            </div>

            <img src="/images/order-strip-mobile-GAH.svg?v=6" alt="Seasonal Offer" />
            <p class="offerPara">UPTO 40% OFF<br>Save your grades & pockets too!</p>
            <a href="#" class="order-button">Order Now</a>

          </aside>
       </div> -->

       
       <div class="sidebar-side col-lg-4 col-md-12 col-sm-12">
											<aside class="sidebar sticky-top">
												
												<!-- Search -->
												<div class="sidebar-widget search-box" style="background-color: aliceblue;">
													<form id="orderForm"  enctype="multipart/form-data">
														<div class="form-header text-center p-2">
															<h2 class="order-now">Pricing</h2>
														</div>
														@csrf
														<div class="contact-form p-4">
																
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label for="serviceSelect">Services</label>
																		<select required id="serviceSelect" style="background-color:#f1f1f1; height: 45px;" class="form-control">
																			<option value="">Select Services</option>
																			<option value="Assignment">Assignment</option>
																			<option value="Dissertation">Dissertation</option>
																			<option value="Thesis">Thesis</option>
																			<option value="Research Project">Research Project</option>
																		</select>
																	</div>
																</div>
																<div class="col-md-12 d-none">
																	<div class="form-group">
																		<label for="workTypeSelect">Work Type</label>
																		<select id="workTypeSelect" style="background-color:#f1f1f1; height: 45px;" class="form-control">
																			<option value="">Select Work Type</option>
																			<option selected value="Standard">Standard</option>
																			<option value="FirstClass">First Class Work</option>
																		</select>
																	</div>
																</div>
																<div class="col-md-12" id="subjectContainer">
																	<div class="form-group">
																		<label for="subjectSelect">Subject</label>
																		<select required id="subjectSelect" style="background-color:#f1f1f1; height: 45px;" class="form-control">
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
																
																<div class="col-md-6" id="selectUrgency">
																	<div class="form-group">
																		<label for="urgencySelect">Select Urgency</label>
																		<select required id="urgencySelect" style="background-color:#f1f1f1; height: 45px;" class="form-control">
																			<option value="">Select Urgency</option>
																			<option value="1">1 Day</option>
																			<option value="2">2 Days</option>
																			<option value="3">3 Days</option>
																			<option value="4">4 Days</option>
																			<option value="5">5 Days</option>
																			<option value="6">6 Days</option>
																			<option value="7">7 Days</option>
																			<option value="8">8 Days</option>
																			<option value="9">9 Days</option>
																			<option value="10">10 Days</option>
																			<option value="11">11 Days</option>
																			<option value="12">12 Days</option>
																			<option value="13">13 Days</option>
																			<option value="14">14 Days</option>
																			<option value="15">15 Days</option>
																			<option value="16 to 20">16-20 Days</option>
																			<option value="21+">21+ Days</option>
																		</select>
																	</div>
																</div>
																<div class="col-md-6" id="wordCountContainer">
																	<div class="form-group">
																		<label id="wordLabel" for="wordCount">Word Count</label>
																		<input required type="number" id="wordCount" min="250" value="250" style="background-color:#f1f1f1; height: 45px;" class="form-control" placeholder="Enter Count">
																	</div>
																</div>
																<div class="col-md-12">
																	<div class="Estimate-price" style="text-align: center; position: relative; z-index: 1;">
																		<label style="font-size: 16px; line-height: 18px; color: #434343;">Estimated Price</label>
																		<span class="basePrice" style="text-decoration: line-through; color: #616161; font-size: 18px; line-height: 20px; margin: 0 10px;">£ 100</span>
																		<span class="priceoff" style="font-size: 18px; font-family: robotomedium; text-transform: uppercase; background: #5c7a36; border-radius: 3px; padding: 3px; color: #fff;">40% OFF</span>
																		<p class="symbole text-center" style="color: #f58e0e; font-size: 36px; line-height: 40px; font-family: robotomedium; margin: 8px 0 15px;">£ <span style="color: #f58e0e; font-size: 36px; line-height: 40px; font-family: robotomedium; padding: 8px 0 15px;" class="value offerPrice">75</span></p>
																	</div>
																</div>
															</div>

															<script>
																$(document).ready(function() {
																	function calculatePrice() {
																		var service = $('#serviceSelect').val();
																		var type = $('#workTypeSelect').val();
																		var urgency = $('#urgencySelect').val();
																		var wordCount = Math.max($('#wordCount').val(), 250);

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

																		var serviceMultiplier = service === 'Dissertation' || service === 'Thesis' || service == 'Research Project' ? 1.1 : 1;
																		var typeMultiplier = type === 'FirstClass' ? 1.3 : 1;
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

																		basePrice *= serviceMultiplier * typeMultiplier * urgencyMultiplier;

																		var discountPercentage = parseFloat($('.priceoff').text().replace('% OFF', '').trim()) / 100;
																		var discount = basePrice * discountPercentage;

																		var finalPrice = basePrice - discount;

																		// Update the price quotation and estimated price
																		$('.offerPrice').text(finalPrice.toFixed(2));
																		$('.basePrice').text('£ ' + basePrice.toFixed(2));
																	}

																	// Trigger price calculation when any input changes
																	$('#serviceSelect, #workTypeSelect, #urgencySelect, #wordCount').on('change keyup', function() {
																		calculatePrice();
																	});

																	// Trigger initial calculation
																	calculatePrice();
																});
															</script>
															

															<!--<div class="policy_agree">-->
															<!--	<input type="checkbox" required="">&nbsp;I agree with Privacy Policy and Terms &amp;-->
															<!--	Conditions (Recommended)-->
															<!--</div>-->
															<!--<div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>-->
															<div class="col-md-12 text-center mt-2">
																 <button class=" place-order" type="submit">Place Order</button> 
																<!--<a class=" place-order" href="/upload-your-assignment" class="place-order">Place Order</a>-->
															</div>
														</div>
													</form>
													<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
													<script>
														document.getElementById('orderForm').addEventListener('submit', function(event) {
															event.preventDefault();

															// Define encryption key and initialization vector
															var encryptionKey = "your-encryption-key"; // Replace with a strong key
															var iv = CryptoJS.enc.Hex.parse('1234567890123456'); // Example IV

															// Collect form data
															var service = document.getElementById('serviceSelect').value;
															var workType = document.getElementById('workTypeSelect').value;
															var subject = document.getElementById('subjectSelect').value;
															var urgency = document.getElementById('urgencySelect').value;
															var wordCount = document.getElementById('wordCount').value;
															var offerPrice = document.querySelector('.offerPrice').innerText.replace('£', '').trim();
															var basePrice = document.querySelector('.basePrice').innerText.replace('£', '').trim();

															// Extract discount percentage
															var discountPercentageText = document.querySelector('.priceoff').innerText.replace('% OFF', '').trim();
															var discountPercentage = parseFloat(discountPercentageText); // Discount percentage as a number

															// Encrypt each parameter
															var encryptedParams = {
																service: CryptoJS.AES.encrypt(service, encryptionKey, { iv: iv }).toString(),
																workType: CryptoJS.AES.encrypt(workType, encryptionKey, { iv: iv }).toString(),
																subject: CryptoJS.AES.encrypt(subject, encryptionKey, { iv: iv }).toString(),
																urgency: CryptoJS.AES.encrypt(urgency, encryptionKey, { iv: iv }).toString(),
																wordCount: CryptoJS.AES.encrypt(wordCount, encryptionKey, { iv: iv }).toString(),
																price: CryptoJS.AES.encrypt(offerPrice, encryptionKey, { iv: iv }).toString(),
																basePrice: CryptoJS.AES.encrypt(basePrice, encryptionKey, { iv: iv }).toString(),
																discount: CryptoJS.AES.encrypt(discountPercentage.toString(), encryptionKey, { iv: iv }).toString(), // Encrypt discount percentage
															};

															// Log encrypted data
															console.log('Encrypted Params:', encryptedParams);

															// Construct query parameters
															var queryParams = new URLSearchParams(encryptedParams);

															// Redirect to the new order page with the encrypted data
															window.location.href = '/upload-your-assignment?' + queryParams.toString();
														});
													</script>
												</div>
												
												 
                        <img src="/images/order-strip-mobile-GAH.svg?v=6" alt="Seasonal Offer" />
                        <p class="offerPara">UPTO 40% OFF<br>Save your grades & pockets too!</p>
                        <a href="#" class="order-button">Order Now</a>
											 
												
											</aside>
										</div>
      </div>


      <div>
        <h4 class=mb-2>Share with Your Friends</h4>
      </div>

      <div class="d-flex space-x-4 p-2 rounded" style="background-color: #e4e4e4; width: fit-content;">
        <!-- Facebook -->
        <a href="https://facebook.com" target="_blank" class="btn text-white m-2 px-2 py-1 rounded-pill"
          style="background-color: #3b5998;">
          <i class="fab fa-facebook-f me-2"></i> Facebook
        </a>

        <!-- WhatsApp -->
        <a href="https://wa.me" target="_blank" class="btn text-white px-2 py-1 m-2 rounded-pill"
          style="background-color: #25d366;">
          <i class="fab fa-whatsapp me-2"></i> WhatsApp
        </a>

        <!-- X (formerly Twitter) -->
        <a href="https://x.com" target="_blank" class="btn text-white px-2 py-1 m-2 rounded-pill"
          style="background-color: #1da1f2;">
          <i class="fab fa-twitter me-2"></i> Twitter
        </a>

        <!-- LinkedIn -->
        <a href="https://linkedin.com" target="_blank" class="btn text-white px-2 py-1 m-2 rounded-pill"
          style="background-color: #0077b5; r">
          <i class="fab fa-linkedin-in me-2"></i> LinkedIn
        </a>
      </div>


      <div class="content-container mt-4">
        @if(auth()->user())
        <a href="downloads-sample/{{$sample->slug}}">  <button href="">Download Full Sample</button></a>
        @else
        <a href="register">  <button href="">Login And Download Full Sample</button></a>
          @endif
          
        </div>
        
         
      <p>
  
        Often times, students feel unable to work on assignments and think, “Who will do my assignment?” By providing
        "Sample" on various subjects, we are trying to help them understand all the intricacies of writing. We have the
        most qualified and skilled assignment writers who can provide the best assignment writing services, essay
        writing services, dissertation
        writing services, and more at the most affordable rates. Place your order now for surprising discounts.
      </p>

      <h4>Latest Related Free-samples (1000+ Free-samples )</h4>
      <div class="slider-container">
        <button class="arrow left-arrow" id="prevBtn">←</button>
        <div class="card-slider">
          @foreach ($samplesuggestion as $obj)
            <div class="card">
              <h4>{!! Str::limit($obj->title, 30, '...') !!}</h4>
              <p>{!! Str::limit($obj->content, 300, '...') !!}</p>
              <a href="free-samples/{{$categoryName}}/{{$obj->slug}}" class="link">View or Download Sample →</a>
            </div>
          @endforeach
        </div>
        <button class="arrow right-arrow" id="nextBtn">→</button>
      </div>
      <script>


        // Select slider and navigation buttons
        const slider = document.querySelector('.card-slider');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        // Get the width of a single card dynamically
        const cardWidth = slider.querySelector('.card').offsetWidth + 20; // Including gap

        // Scroll to the previous set of cards
        prevBtn.addEventListener('click', () => {
          if (slider.scrollLeft <= 0) {
            // If at the first card, scroll to the last card
            const maxScrollLeft = slider.scrollWidth - slider.clientWidth;
            slider.scrollTo({ left: maxScrollLeft, behavior: 'smooth' });
          } else {
            // Scroll to the previous card
            slider.scrollBy({ left: -cardWidth, behavior: 'smooth' });
          }
        });

        // Scroll to the next set of cards
        nextBtn.addEventListener('click', () => {
          const maxScrollLeft = slider.scrollWidth - slider.clientWidth;

          if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth) {
            // If at the last card, scroll to the first card
            slider.scrollTo({ left: 0, behavior: 'smooth' });
          } else {
            // Scroll to the next card
            slider.scrollBy({ left: cardWidth, behavior: 'smooth' });
          }
        });


      </script>

    </div>
  </div>
</section>


@endsection