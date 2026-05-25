@extends('frontend-layouts.app')

@section('content')

<style>
    @media screen and (max-width: 768px) {
        #testimonial-video {
            width: 100%;
            /* Set the width to 100% when div goes to col-12 */
            height: auto;
            /* Set the height to auto to maintain aspect ratio */
        }

    }

    .testimonial-block-four .video {
        margin-top: 15px;
    }

    .testimonial-block-four .video video {
        width: 80%;
        height: 250px;
        border-radius: 15px;
    }
</style>
<style>
    .featured-section .feature-block:nth-child(2n + 1) {
        margin-top: 0px;
    }

    .text {
        color: #0c0d24;
        line-height: 1.8em;
        font-size: 16px;
    }

    .text-black {
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
    .header-section {
        background: rgb(255, 255, 255);
        background: linear-gradient(170deg, rgba(255, 255, 255, 1) 6%, rgba(135, 166, 219, 0.4009978991596639) 72%, rgba(135, 166, 219, 0.5690651260504201) 91%, rgba(126, 137, 221, 0.865983893557423) 100%);
    }

    h1 {
        font-size: 35px;
        font-weight: 600;
        color: black
    }
    h2 {
        font-size: 30px !important; 
        line-height: 1.2em !important;
        font-weight: 600;
        color:black;
    }
    p {
        position: relative;
        line-height: 1.8em;
        font-size: large;
        color: black;
        text-align: justify;
    }

    .place-order {
        background: #d7f0fd;
        color: black;
        padding: 10px 20px;
        border-radius: 5%;
        margin: 10px;
    }

    .place-now {
        background: #77bfe5;
        color: black;
        padding: 20px 80px;
        border-radius: 3%;
        margin: 10px;
        font-weight: 500;
        font-size: 20px;
    }

    .place-order:hover {
        background: #7e89dd;
        color: white;

    }

    .place-now:hover {
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        color: white;
        transition: box-shadow 0.4s ease-in-out, color 0.3s ease-in-out;
        /* Smooth transition */

    }

    .order-now {
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

    .banner-stats-title {
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
        padding: 0 10px;
        /* Adjust the space between elements */
    }

    .banner-stats-text {
        font-size: 1em;
        margin-top: 5px;
    }

    ul {
        font-size: 17px;
        color: black;
    }

    h3 {
        font-size: 21px;
        font-weight: 600;
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

    .current_offer {
        font-weight: bold;
        font-size: 35px;
    }

    .offer-container {
        background: rgb(221, 245, 245);
        background: -moz-linear-gradient(275deg, rgba(221, 245, 245, 1) 0%, rgba(175, 214, 232, 0.23012955182072825) 10%, rgba(155, 205, 231, 0.43461134453781514) 68%, rgba(110, 186, 231, 1) 100%);
        background: -webkit-linear-gradient(275deg, rgba(221, 245, 245, 1) 0%, rgba(175, 214, 232, 0.23012955182072825) 10%, rgba(155, 205, 231, 0.43461134453781514) 68%, rgba(110, 186, 231, 1) 100%);
        background: linear-gradient(275deg, rgba(221, 245, 245, 1) 0%, rgba(175, 214, 232, 0.23012955182072825) 10%, rgba(155, 205, 231, 0.43461134453781514) 68%, rgba(110, 186, 231, 1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ddf5f5", endColorstr="#6ebae7", GradientType=1);
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

    .offer-badge-offer:hover {

        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        transition: box-shadow 0.4s ease-in-out, color 0.3s ease-in-out;
        /* Smooth transition */
    }

    .testimonial-section-three .owl-carousel .owl-stage-outer {
        padding: 0px 0px 0px;
    }

    .feature-section-three .blocks-column .feature-block-five:nth-child(2n + 0) {
        transform: translateY(0px);
    }

    .news-section-two .blocks-column .column:nth-child(1) .news-block-four {
        margin-top: 0;

    }

    .bg-gradient-1.rounded-box {
        padding: 20px;
        background-color: white;
        border-radius: 20px;
    }

    /* Style the list items with more space and consistent padding */
    .custom-list li {
        margin-bottom: 15px;
        /* Increase space between list items */
        padding-left: 10px;
        color: white
    }

    /* Style the image with rounded corners */
    .rounded-image {
        border-radius: 20px;
    }

    .subject-container {
        background-color: #fff;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;

        padding: 20px;
        border-radius: 10px;
    }

    .subject-image {
        border-radius: 50%;
        max-width: 100%;
        height: auto;
    }

    .subject-list-box {
        background: rgb(0, 127, 193);
        background: linear-gradient(281deg, rgba(0, 127, 193, 0.5718662464985995) 11%, rgba(71, 199, 204, 1) 60%, rgba(0, 127, 193, 1) 100%);
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
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
</style>
<style>
    .topspaceheader {
        padding-top: 10px;
    }

    .topspaceheader2 {
        padding-top: 50px;
    }

    @media (min-width: 992px) {
        .topspaceheader {
            padding-top: 70px;
        }

        .topspaceheader2 {
            padding-top: 70px;
        }
    }
</style>

<style>
    .scrollable-content {
        max-height: 500px; /* Set your desired height */
        overflow-y: auto;
        padding-right: 10px; /* Optional: to avoid scroll bar overlap with content */
    }

    /* Optional: Customize scrollbar */
    .scrollable-content::-webkit-scrollbar {
        width: 8px;
    }

    .scrollable-content::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 4px;
    }

    .scrollable-content::-webkit-scrollbar-thumb:hover {
        background-color: #888;
    }

</style>
            <section class="banner-section-three header-section">
                <div class="auto-container" style="margin-top: 100px;">
                  <div class="row clearfix">
                       <div class="content-column topspaceheader col-lg-6 col-md-12 col-sm-12">
                         <div class="inner-column">
                            <h1>Place Your Order and Receive Expert Online Assignment Help for Students!</h1>
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

                       <div class="image-column topspaceheader2 col-lg-6 col-md-12 col-sm-12">
                          <div class="inner-column mr-0 pb-0 pl-0"  style="background-color: white; border-radius: 10px;box-shadow: 10px;box-shadow: 0 1rem 1rem 0 rgba(0, 0, 0, .08)">
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
                                   <!--<div class="offer-badge"><img src="assets/media/avatars/offer.png" alt=""></div>-->
                                 <form id="orderForm" action="/neworder-fromhome" method="POST" enctype="multipart/form-data">
                                     <div class="text-center p-2">
                                         <img style="margin-top: -68px; margin-bottom: -24px; height:122px"  src="assets/media/avatars/Limited time (4).png" alt="">
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
                                                   <input id="file-upload" type="file" class="form-control" name="doc[]" multiple
                                                       style="background-color:#f1f1f1;height: 45px;" />
                                               </div>
                                           </div>
                                           <div class="col-md-6">
                                               <div class="form-group">
                                                   <label for="enterTopic">Enter Topic</label>
                                                   <input type="text" name="topic" id="enterTopic" class="form-control"
                                                       style="background-color:#f1f1f1; height: 45px;" placeholder="Enter Topic"
                                                       required>
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
                                                         <input id="file-upload" type="file" class="form-control" name="doc[]" multiple
                                                             style="background-color:#f1f1f1;height: 45px;" />
                                                     </div>
                                                 </div>
                                                 <div class="col-md-12">
                                                     <div class="form-group">
                                                         <label for="enterTopic">Enter Topic</label>
                                                         <input type="text" name="topic" id="enterTopic" class="form-control"
                                                             style="background-color:#f1f1f1; height: 45px;" placeholder="Enter Topic"
                                                             required>
                                                     </div>
                                                 </div>
                                             </div>
                                           @endif

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
                 </div>
          </div>
       </section>


<section class="news-section-two py-4" style="background-color: #fff; background-image: url(images2/background/pattern-23.png); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="auto-container">        
        <div class="sec-title-three my-2">
            <h2 class="my-2">Top Assignment Writing Service - Expert Writers + 40% Discount!</h2>
            <div class="inner-box">
                <p class="mb-0">
                    At Assignment in Need, we believe that no two assignments are the same. That's why our approach is all about customization! Our expert writers take the time to understand your specific requirements and create tailor-made solutions that align with your professor's expectations—without relying on AI tools.
                    <br>
                    We also get it—students often have tight budgets. Our affordable pricing ensures you get top-quality work without worrying about your finances. Whether you're balancing part-time jobs or managing expenses, we're here to make assignment help stress-free and budget-friendly.
                </p>
            </div>
        </div>
        <div class="sec-title-three my-2">
            <h2 class="my-2">Why Choose Our Assignment Writing Services?</h2>
            <div class="inner-box">
                <p class="mb-0">
                    1. Skilled Experts: Our team knows their subjects inside and out.
                </p>
                <p class="mb-0">
                    2. Global Reach: We provide support wherever you are—in the UK, Canada, Spain, Australia, Malaysia, UAE, and beyond!
                </p>
                <p class="mb-0">
                    3. Unique Work: Every assignment is crafted from scratch, so no worries about plagiarism.
                </p>
                <p class="mb-0">
                    4. Speedy Service: Deadlines? No problem—we deliver on time!
                </p>
                <p class="mb-0">
                    5. Always Available: Our friendly team is here 24/7 to assist you.
                </p>
            </div>
        </div>
        <div class="sec-title-three my-4">
            <h2>Types Of Assignment Services Provide By Assignment in Need</h2>            
        </div>
        <div class="row clearfix scrollable-content">            
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Homework Writing Help</h3>
                        <p class="mb-0">
                            Struggling with academic assignments? Our experienced team at Assignment in Need is here to assist you with all kinds of homework. Whether it's essays, research papers, case studies, lab reports, dissertations, or presentations, we're ready to help! Just let us know, and we'll deliver original, high-quality work tailored to your needs. You can count on us to meet your deadlines and help you achieve the grades you want.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Essay Writing Services</h3>
                        <p class="mb-0">
                            Need help with your essays? Trust our expert writers to deliver fast, plagiarism-free content crafted just for you. For seven years, we've helped over 45,000 students excel in their academics—now it's your turn!
                            <br>
                            Join over happy students who've trusted our skilled US and Canada-based writers for more than a decade. With expertise in 200+ subjects, we're ready to tackle even the toughest assignments with speed and precision.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Research Paper Writing Services</h3>
                        <p class="mb-0">
                            At Assignment in Need, we use advanced tools like Turnitin to ensure every research paper is completely original. Our writers work with authentic materials and never use publicly available sources.
                            <br>
                            We're proficient in multiple writing styles, including APA, MLA, Harvard, Turabian, OSCOLA, and more. Whether you need assistance with citations or in-depth research, we're available 24/7 to help.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">University Assignment Help</h3>
                        <p class="mb-0">
                            Balancing university assignments with your busy schedule can be challenging. That's where we come in! At Assignment in Need, we simplify the process by providing expert guidance and support to help you excel in your coursework. Want to know what sets us apart? Try our service and see why so many students trust us to deliver high-quality results.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Coursework Writing Services</h3>
                        <p class="mb-0">
                            Our coursework experts are here to make your life easier. You can choose a writer, communicate with them directly through online chat, and track the progress of your assignment every step of the way. We even offer unlimited revisions to ensure your satisfaction. With this personalized approach, we guarantee a stress-free experience and a polished final product.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Dissertation Writing Services</h3>
                        <p class="mb-0">
                            When it's time to tackle your dissertation, you don't have to go it alone. Our experienced dissertation writers are ready to guide you through the final step of your academic journey. With years of experience and over 3,000 PhD researchers assisted, we offer support, tutoring, and help with publishing in top journals.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Thesis Writing Help</h3>
                        <p class="mb-0">
                            No matter your field of study, our professional thesis writers are here to help. With over 7 years of experience, many of our writers hold PhDs and are dedicated to delivering personalized, high-quality theses. Whether you need assistance with writing, formatting, or meeting academic standards, we're here to make your journey smoother and more successful.
                        </p>
                    </div>
                </div>
            </div>														
            													
            														
        </div>
        <div class="sec-title-three my-4">
            <h2>Types of Subjects We Cover For Any Field of Assignments</h2>            
        </div>
        <div class="row clearfix scrollable-content">            
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Math Assignment Help</h3>
                        <p class="mb-0">
                            Got tricky math problems? From algebra to calculus and everything in between, our math experts are ready to guide you. We simplify complex concepts and provide solutions that are easy to understand, ensuring you score top grades.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Physics Assignment Help</h3>
                        <p class="mb-0">
                            Physics can be tough, but our skilled tutors make it manageable. Whether it's solving equations or tackling detailed college projects, we provide accurate and clear solutions to help you succeed.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Chemistry Assignment Help</h3>
                        <p class="mb-0">
                            Struggling with chemical formulas or theories? Our chemistry experts break down even the hardest topics into simple steps, delivering well-explained solutions that make your assignments a breeze.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Biology Assignment Help</h3>
                        <p class="mb-0">
                            From molecular biology to ecology, our experienced professionals craft detailed and well-researched biology assignments. They provide structured answers to help you understand the subject and excel in your coursework.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">English Assignment Help</h3>
                        <p class="mb-0">
                            Whether you need help with essays, grammar, or literary analysis, our English experts are here for you. We deliver clear, plagiarism-free work designed to match your academic needs.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">History Assignment Help</h3>
                        <p class="mb-0">
                            History assignments can be overwhelming, but our skilled writers simplify the process. With years of experience and advanced degrees, they create engaging and accurate content that meets top university standards.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Law Assignment Help</h3>
                        <p class="mb-0">
                            Law assignments require precision, and our legal experts are up for the task. With advanced qualifications and deep subject knowledge, they provide original, well-researched work that you can trust.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Sociology Assignment Help</h3>
                        <p class="mb-0">
                            Our sociology experts are available 24/7 to craft high-quality, plagiarism-free assignments. They focus on detailed research, proper referencing, and clear analysis to help you achieve top marks.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Marketing Assignment Help</h3>
                        <p class="mb-0">
                            Get a clear understanding of marketing concepts with help from our subject specialists. They follow all assignment guidelines to deliver accurate and insightful work that helps you stand out.
                        </p>
                    </div>
                </div>
            </div>														
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <div class="mt-0">  
                    <div class="inner-box">
                        <h3 style="margin-bottom:0px;">Finance Assignment Help</h3>
                        <p class="mb-0">
                            With thousands of finance experts ready to assist, we ensure on-time delivery and high-quality solutions. Plus, you can stay in touch with your expert for any last-minute changes or clarifications. 
                        </p>
                    </div>
                </div>
            </div>														
            													
            														
        </div>
    </div>
</section>








@endsection