@extends('frontend-layouts.app')

@section('content')
   <style>
    .text {
        color: #0c0d24;
        line-height: 1.8em;
        font-size: 16px;
    }

    .text-black {
        color: black;

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
.place-order {
    background: #d7f0fd;
    color: black;
    padding: 10px 20px;
    border-radius: 5%;
    margin: 10px;
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

    p {
        position: relative;
        line-height: 1.8em;
        font-size: large;
        color: black;
        text-align: justify;
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

    .faq-container {
        max-width: 100%;
        margin: 0 auto;
        font-family: Arial, sans-serif;
    }

    .faq-item {
        border: 1px solid #ddd;
        margin-bottom: 8px;
        border-radius: 4px;
        overflow: hidden;
        color: black;
    }

    .faq-question {
        background-color: #f9f9f9;
        padding: 10px 15px;
        display: flex;
        justify-content: space-between;
        cursor: pointer;
    }

    .faq-question:hover {
        background-color: #f1f1f1;
    }

    .icon {
        font-weight: bold;
        font-size: 18px;
        color: #555;
    }

    .faq-answer {
        display: none;
        padding: 10px 15px;
        background-color: #fff;
        border-top: 1px solid #ddd;
    }

    .faq-answer p,
    .faq-answer ul {
        margin: 0;
        padding: 0;
        list-style: disc;
        margin-left: 20px;
        font-size: 14px;
    }

   .news-block-four
   {
    border: 2px solid gray;
    padding: 11px;
    border-radius: 10px;
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
   }
   .news-block-four:hover {
    border: 3px solid #007BFF;
    padding: 12px;
    border-radius: 10px;
    box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
    transition: all 0.4s ease; /* Apply transition for all properties */
}

.news-block-four:hover .icon-circle {
    background-color: #007BFF !important; /* Change background color of .icon-circle */
    transition: background-color 0.4s ease; /* Smooth transition for background-color */
}


    
</style>

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
                        <h1>Got Questions? Find All the Answers You Need Instantly</h1>
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
                                        Rating</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

       @include('formsection')

</section>

<section class="faq-section bg-light py-0">
    <div class="auto-container">
        <div class="row ">
            <div class="titel-column col-lg-12 col-md-12 col-sm-12">
                <div class="title-box text-center">
                    <h2 style="font-weight:600 !important; font-size: 30px;; color:black" class="my-4"><b>Frequently Asked
                            Questions For Assignment Help Services</b></h2>
                </div>
                <div class="row">

                    <div class="column col-lg-5 col-md-12 col-sm-12 ">
                        
                    <div onclick="toggleFaq('faq1')" class="news-block-four mt-0"  style="display: flex; align-items: center; gap: 10px; font-family: Arial, sans-serif; border:">
                            <div class="icon-circle" style="display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; border-radius: 50%; background-color: gray; color: white; font-size: 18px; font-weight: bold;">
                                <i class="fa fa-question"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 18px; color: #333;">
                                General Information Related
                            </h3>
                        </div>
                        <div onclick="toggleFaq('faq2')" class="news-block-four mt-0" style="display: flex; align-items: center; gap: 10px; font-family: Arial, sans-serif;">
                            <div class="icon-circle" style="display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; border-radius: 50%; background-color: gray; color: white; font-size: 18px; font-weight: bold;">
                                <i class="fa fa-book"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 18px; color: #333;">
                                Subjects and Topics Related
                            </h3>
                        </div>
                        <div onclick="toggleFaq('faq3')" class="news-block-four mt-0" style="display: flex; align-items: center; gap: 10px; font-family: Arial, sans-serif;">
                            <div class="icon-circle" style="display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; border-radius: 50%; background-color: gray; color: white; font-size: 18px; font-weight: bold;">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 18px; color: #333;">
                                 Security and Privacy-Related 
                            </h3>
                        </div>
                        <div onclick="toggleFaq('faq4')" class="news-block-four mt-0" style="display: flex; align-items: center; gap: 10px; font-family: Arial, sans-serif;">
                            <div class="icon-circle" style="display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; border-radius: 50%; background-color: gray; color: white; font-size: 18px; font-weight: bold;">
                                <i class="fa fa-comments"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 18px; color: #333;">
                                 Communication with Experts
                            </h3>
                        </div>
                        <div onclick="toggleFaq('faq5')" class="news-block-four mt-0" style="display: flex; align-items: center; gap: 10px; font-family: Arial, sans-serif;">
                            <div class="icon-circle" style="display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; border-radius: 50%; background-color: gray; color: white; font-size: 18px; font-weight: bold;">
                                <i class="fa fa-redo"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 18px; color: #333;">
                                Satisfaction, Revisions, and Quality Related
                            </h3>
                        </div>
                        <div onclick="toggleFaq('faq6')" class="news-block-four mt-0" style="display: flex; align-items: center; gap: 10px; font-family: Arial, sans-serif;">
                            <div class="icon-circle" style="display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; border-radius: 50%; background-color: gray; color: white; font-size: 18px; font-weight: bold;">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 18px; color: #333;">
                                Deadlines and Urgent Orders Related
                            </h3>
                        </div>

                        <div onclick="toggleFaq('faq7')" class="news-block-four mt-0" style="display: flex; align-items: center; gap: 10px; font-family: Arial, sans-serif;">
                            <div class="icon-circle" style="display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; border-radius: 50%; background-color: gray; color: white; font-size: 18px; font-weight: bold;">
                                <i class="fa fa-money"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 18px; color: #333;">
                                Pricing, Payments, and Discounts 
                            </h3>
                        </div>
                        <div onclick="toggleFaq('faq8')" class="news-block-four mt-0" style="display: flex; align-items: center; gap: 10px; font-family: Arial, sans-serif;">
                            <div class="icon-circle" style="display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; border-radius: 50%; background-color: gray; color: white; font-size: 18px; font-weight: bold;">
                                <i class="fa fa-shield"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 18px; color: #333;">
                                 Plagiarism and Quality Assurance-Related
                            </h3>
                        </div>
                        <div   onclick="toggleFaq('faq9')" class="news-block-four mt-0" style="display: flex; align-items: center; gap: 10px; font-family: Arial, sans-serif;">
                            <div class="icon-circle" style="display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; border-radius: 50%; background-color: gray; color: white; font-size: 18px; font-weight: bold;">
                                <i class="fa fa-undo"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 18px; color: #333;">
                                Refunds and Cancellations Related Queries

                            </h3>
                        </div>
                        <div onclick="toggleFaq('faq10')" class="news-block-four mt-0" style="display: flex; align-items: center; gap: 10px; font-family: Arial, sans-serif;">
                            <div class="icon-circle" style="display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; border-radius: 50%; background-color: gray; color: white; font-size: 18px; font-weight: bold;">
                                <i class="fa fa-user"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 18px; color: #333;">
                                Account and Login Related Queries
                            </h3>
                        </div>
                        <div onclick="toggleFaq('faq11')" class="news-block-four mt-0" style="display: flex; align-items: center; gap: 10px; font-family: Arial, sans-serif;">
                            <div class="icon-circle" style="display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; border-radius: 50%; background-color: gray; color: white; font-size: 18px; font-weight: bold;">
                                <i class="fa fa-info-circle"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 18px; color: #333;">
                               Miscellaneous Information Related Queries
                            </h3>
                        </div>
                    </div>

                    <div class="column col-lg-7 col-md-12 col-sm-12">

                        <ul class="accordion-box" id="faq1">

                            <li class="accordion block" >
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">1. How does Assignment In Need work?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                             Our approach is simple and easy to work with for students. Submit your assignment details through our website, make a safe payment, and our experts will deliver quality assignments right before your deadline.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">2. Why should I consider Assignment In Need?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                               Assignment In Need offers plagiarism-free original work completed by a PhD expert. We have a money refund in case you are not satisfied.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">3. How is Assignment In Need any better than others?
                                    <div
                                        class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                                Our qualified experts, personalized support, on-time delivery, and affordable price set us apart in the market.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">4. How long is Assignment In Need in Service?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>We have been rendering high-quality academic writing for 7+ years which helped, 45000+ students in achieving their academic goals.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">5. Is there customer support that operates 24/7?

                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>Yes, our customer support team is available 24/7 round-the-clock to assist you with any type of assignment and inquiries or concerns.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>


                        </ul>
                        <ul class="accordion-box d-none" id="faq2">
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">6. Which subjects and topics do you tutor?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            We offer support across hundreds of subjects, from Business to Engineering, Literature to almost all subjects. If your topic isn't listed, send us a message—we want to help!
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">7. Do your writers cover any subject?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Yes! From every academic discipline imaginable, we have experts ready to ace your assignments in any of these subjects.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">8. Do you cover specialized subjects?

                                    <div
                                        class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Absolutely! Our team consists of expert writers in niche subjects, so never hesitate to ask if you need help with anything.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">9. Can I get help with multiple subjects at once?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>Yes! Whether you need help in many subjects or related areas, our writers can handle difficult assignments with start.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">10. Can I request a tutor for a specific subject?

                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>Yes, if you need assistance and help in an any types of subject, we can hire an expert specializing in the same field to tutor you.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="accordion-box d-none" id="faq3">
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">11. Is my personal data safe?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Of course! We respect your privacy, and all personal information is kept confidential.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">12. Can anybody view my identity?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            No, because your identity is fully secured. We guarantee that all your personal details are safe and kept private.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">13. How do you protect my payment details?

                                    <div
                                        class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            We use secure encryption to safeguard your payment information during the transaction process
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">14. Will my tutor or writer have access to my personal information?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>No, your personal information is not revealed to the writer or tutor unless it is absolutely necessary for the assignment.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">5. Can I request a tutor for a specific subject?

                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>Yes, if you need assistance and help in an any types of subject, we can hire an expert specializing in the same field to tutor you.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="accordion-box d-none" id="faq4">
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">15. May I communicate directly with the expert working on my paper?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Yes, we offer one-on-one communication with our writers to ensure that your assignments are meets academic standard efficiently.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">16. How do I reach out to my writer?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            You are allowed to reach out to our writer via our website's communication tool for easy communication during your assignments process and after the completing assignments too.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">17. Can I request a particular writer to do my assignments?

                                    <div
                                        class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            If you have previously worked with a writer and wish to continue working with the same writer, you can request them again for your future assignments
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">18. Can I request my writer to give me progress updates?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>Yes, you can always request progress updates to ensure that the work is being done according to your academic requirements.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">19. How do I ensure my tutor understands my requirements clearly?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>When submitting your assignment or project, please make sure to provide as much detail as possible. You may also discuss your requirements directly with the tutor before they begin working
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="accordion-box d-none" id="faq5">
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">20. What happens if I am not satisfied with the work?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            We allow unlimited revisions until you are satisfied. In case your expectations are not met after revisions, our refund policy will have your back.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">21. Do I get corrections if my solution is incorrect?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Absolutely! If any part of your assignment needs correction, we will address it promptly through revisions.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">22. How many revisions are included?

                                    <div
                                        class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            We provide unlimited revisions until you are satisfied with the quality of the work delivered.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">23. Will you modify my assignment if I want to change something after submitting it?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>Yes, we will change it according to your request so that the final product meets your needs.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="accordion-box d-none" id="faq6">
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">24. Do you guarantee meeting deadlines?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Yes, meeting deadlines is our priority. We also provide urgent help for last-minute needs.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">25. If I need my paper urgently?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            We offer urgent assignment help with quick turnaround times—just let us know your deadline, and we'll make it happen.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">26. Can you deliver an assignment in 24 hours or less?

                                    <div
                                        class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Yes, we can deliver assignments within 24 hours, depending on the complexity of the work.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">27. What happens if I miss my deadline or do not submit the order on time?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>If you are unable to deliver the details on time, please contact us and we will do our best to accommodate your request.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="accordion-box d-none" id="faq7">
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">28. How much do your services cost?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Our prices are competitive and vary depending on your assignment's complexity and deadline. We also offer a discount to make our service more affordable.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">29. How do I pay?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            You can safely pay through various modes, including credit/debit cards and online wallets.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">30. What modes of payment do we accept?

                                    <div
                                        class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            We accept credit/debit card payments, PayPal, and other forms of online payment for your ease.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">31. Can I pay in easy installments?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>We offer flexible payment options for you. Call us to know more in case you want to pay through instalment.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">32. Do you have student discounts?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>Yes! We have student discounts so that our services can be affordable. You also can use coupons to save more
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">33. Is there scope for negotiation of price while ordering in bulk?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>Sure, if you have bulk assignments, we can definitely discuss discounts or a personalized plan for the bulk.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="accordion-box d-none" id="faq8">
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">34. Does your company offer plagiarism-free assignments?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Absolutely! It uses advanced tools to ensure the assignments are 100% free of plagiarism.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">35. Who judges the quality standard of the work I ordered?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Our expert writers are experienced in maintaining high-quality standards. You'll receive work that meets or exceeds your expectations.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">36. How do I know that your work will meet your guarantees?

                                    <div
                                        class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            We ensure plagiarism-free, original work based on your instructions. You may also request revisions in case you need them.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">37. How do you respond to plagiarism allegations?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>Plagiarism is something that we take very seriously and ensure 100% original work. In case of some sort of plagiarism is detected, we will rectify that and give a refund too.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="accordion-box d-none" id="faq9">
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">38. What is your refund policy?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            If we cannot deliver the promised quality, or if we fail to complete it within the deadline, then you can ask for a refund of your order based on our guarantee.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">39. Is there a cancellation of the order?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Yes, cancellations occur when the project is being worked on or has been completed. To get details, contact our representatives.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">40. But the work is done on time, but I could not receive it. Should I get a refund for it?

                                    <div
                                        class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            We shall adjust this delivery issue immediately and return your money back.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">41. But what shall I do when I demand for a refund?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>If you're not satisfied with the work, contact our support team and we will ensure that the refund process is done as fast as possible.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="accordion-box d-none" id="faq10">
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">42. How do I sign up?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Signing up is quite easy! Click on the “Sign Up” link and fill in the requested details to get started with our services immediately.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">43. What if I forgot my password?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            You can reset it right away by clicking “Forgot Password” on the sign-in page and follow through the instructions received via email for resetting.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">44. How can I change my account details?

                                    <div
                                        class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            You can change your account settings, including email, password, and other details, by accessing your account dashboard.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">45. How can I change my EMAIL, PASSWORD & USERNAME?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>Login to your account and update your email, password, or username via your account settings.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">46. Can I delete my account?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>Of course, yes. You can delete the account any time by contacting our support team.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="accordion-box d-none" id="faq11">
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">47. Will I receive my assignment in my mailbox?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            Yes, your assigned work will be sent through email before the deadline is set.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">48. What is the time for receiving the work?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            We make it sure to submit assignments timely according to the agreed date of submission. Your work is surely going to reach you by then.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">49. How would I know that your service will promote plagiarism?

                                    <div
                                        class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>
                                            We strictly adhere to academic integrity, ensuring that all assignments are 100% original and plagiarism-free.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">50. How can I track my order status?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>You can track your order status by logging into your account and accessing the project details at any time.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn bg-white  border"
                                    style="font-weight:500; font-size: 20px;; color:black">51. Can I delete my account?
                                    <div class="icon fa fa-angle-down"></div>
                                </div>
                                <div class="acc-content" style="">
                                    <div class="content">
                                        <div class="text">
                                            <p>Of course, yes. You can delete the account any time by contacting our support team.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>


                </div>
              

            </div>

        </div>

    </div>

    <script>
        function toggleFaq(faqId) 
        {
            const faqSections = document.querySelectorAll('.accordion-box');
            faqSections.forEach(section => section.classList.add('d-none')); // Hide all FAQ sections
            document.getElementById(faqId).classList.remove('d-none'); // Show the selected FAQ section
        }
    </script>
</section>
<section class="py-0 ">
	<div class="auto-container ">
		<div class="my-5 images-container" style="position: relative; border-radius: 5px; overflow: hidden; box-shadow: 0 1rem 1rem 0 rgba(0, 0, 0, 0.08);">
			<div style="background: linear-gradient(275deg, rgba(221,245,245,1) 0%, rgba(175,213,232,0.23012955182072825) 10%, rgba(155,205,231,0.43461134453781514) 21%, rgba(110,186,231,1) 100%); background-size: cover; background-position: center; height: 100%; width: 100%; position: absolute; top: 0; left: 0; z-index: 1;">
			</div>
			<div style="background: linear-gradient(to bottom, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.9)); position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 2;">
			</div>
			<div class="container" style="position: relative; z-index: 3;">
				<div class="row">
					<div class="col-md-4">
						<img src="assets/media/avatars/books-with-graduation-cap-digital-art-style-education-day-removebg-preview.png" alt="book your assignment" class="img-fluid" loading="lazy">
					</div>
					<div class="col-md-6 mt-4">
						<h2 style="font-size: 30px; font-weight: 600; color: black; margin-bottom: 10px; align-items:justify">
							Order our assignment help service today and enjoy a special discount!</h2>
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
<section class="resume-services py-0">
    <div class="container">
            <div class="title-box text-center">
                <h2 style="font-weight:600 !important; font-size: 30px;; color:black" class="my-4">
                    Questions About Our Assignments Services? We've Got the Answers Right Here</h2>
            </div>
        <div class="row">
               
        <div class="col-lg-12 col-md-12">
            <div class="row">
                <!-- Service 1 -->
                <div class="col-lg-3 col-md-3 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-file-alt"></i>
                        <h4><a href="assignment-writing-help-services">Assignment Help</a></h4>
                    </div>
                </div>
                <!-- Service 2 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-graduation-cap"></i>
                        <h4><a href="dissertation-writing-help-services">Dissertation Help</a></h4>
                    </div>
                </div>
                <!-- Service 3 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-pencil"></i>
                        <h4><a href="essay-writing-help-services">Essay Help</a></h4>
                    </div>
                </div>
                <!-- Service 4 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-book"></i>
                        <h4><a href="homework-writing-help-services">Homework Help</a></h4>
                    </div>
                </div>
                <!-- Service 5 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-flask"></i>
                        <h4><a href="research-paper-writing-services">Research Paper Help</a></h4>
                    </div>
                </div>
                <!-- Service 6 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fas fa-file-alt"></i>
                        <h4><a href="academic-assignment-writing-help-service">Academic Writing Help</a></h4>
                    </div>
                </div>
                <!-- Service 7 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-tasks"></i>
                        <h4><a href="coursework-writing-help">Coursework Writing Help</a></h4>
                    </div>
                </div>
                <!-- Service 8 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-university"></i>
                        <h4><a href="university-assignment-writing-help">University Writing Help</a></h4>
                    </div>
                </div>
                <!-- Service 9 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fas fa-book"></i>
                        <h4><a href="thesis-writing-help">Thesis Writing Help</a></h4>
                    </div>
                </div>
                <!-- Service 10 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-list-alt"></i>
                        <h4><a href="summary-writing-help">Summary Writing Help</a></h4>
                    </div>
                </div>
                <!-- Service 11 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-file"></i>
                        <h4><a href="term-paper-writing-help">Term Paper Writing Help</a></h4>
                    </div>
                </div>
                <!-- Service 12 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-user"></i>
                        <h4><a href="personal-statement-writing-help">Personal Statement Writing</a></h4>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
</section>

<section class="resume-services py-0">
    <div class="container">
            <div class="title-box text-center">
                <h2 style="font-weight:600 !important; font-size: 30px;; color:black" class="my-4">
                    Stuck on Assignment Subject Queries? Get All the Answers You Need Fast!</h2>
            </div>
        <div class="row">
               
        <div class="col-lg-12 col-md-12">
            <div class="row">
                <!-- Service 1 -->
                <div class="col-lg-3 col-md-3 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-file-alt"></i>
                        <h4><a href="math-assignment-help">Math Assignment</a></h4>
                    </div>
                </div>
                <!-- Service 2 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-graduation-cap"></i>
                        <h4><a href="chemistry-assignment-writing-help">Chemistry Assignment</a></h4>
                    </div>
                </div>
                <!-- Service 3 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-pencil"></i>
                        <h4><a href="economic-assignment-writing-help">Economic Assignment</a></h4>
                    </div>
                </div>
                <!-- Service 4 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-book"></i>
                        <h4><a href="english-assignment-writing-help">English Assignment</a></h4>
                    </div>
                </div>
                <!-- Service 5 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-flask"></i>
                        <h4><a href="history-assignment-writing-help">History Assignment</a></h4>
                    </div>
                </div>
                <!-- Service 6 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fas fa-file-alt"></i>
                        <h4><a href="geography-assignment-writing-help">Geography Assignment</a></h4>
                    </div>
                </div>
                <!-- Service 7 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-tasks"></i>
                        <h4><a href="law-assignment-writing-help">Law Assignment</a></h4>
                    </div>
                </div>
                <!-- Service 8 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-university"></i>
                        <h4><a href="linguistic-assignment-writing-help">Linguistic Assignment</a></h4>
                    </div>
                </div>
                <!-- Service 9 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fas fa-book"></i>
                        <h4><a href="nursing-assignment-writing-help">Nursing Assignment</a></h4>
                    </div>
                </div>
                <!-- Service 10 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-list-alt"></i>
                        <h4><a href="physics-assignment-writing-help">Physics Assignment</a></h4>
                    </div>
                </div>
                <!-- Service 11 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-file"></i>
                        <h4><a href="sociology-assignment-writing-help">Sociology Assignment</a></h4>
                    </div>
                </div>
                <!-- Service 12 -->
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-user"></i>
                        <h4><a href="philosophy-assignment-writing-help">Philosophy Assignment</a></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-user"></i>
                        <h4><a href="accounting-assignment-writing-help">Accounting Assignment</a></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-user"></i>
                        <h4><a href="marketing-assignment-writing-help">Marketing Assignment</a></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-user"></i>
                        <h4><a href="computer-science-assignment-writing-help">Computer Science Assignment</a></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-user"></i>
                        <h4><a href="engineering-assignment-writing-help">Engineering Assignment</a></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-user"></i>
                        <h4><a href="finance-assignment-writing-help">Finance Assignment</a></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-user"></i>
                        <h4><a href="programming-assignment-writing-help">Programming Assignment</a></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-user"></i>
                        <h4><a href="management-assignment-writing-help">Management Assignment</a></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2 col-sm-12 mb-4">
                    <div class="service-box">
                        <i class="fa fa-user"></i>
                        <h4><a href="business-assignment-writing-help">Business Assignment</a></h4>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
</section>
<style>/* Basic Section Styling */
.resume-services {
    background-color: #fff;
    padding: 40px 0;
}

.resume-services h5 {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 10px;
}

.resume-services h2 {
    font-size: 32px;
    line-height: 1.4;
}

.service-box {
    text-align: left;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.service-box i {
    font-size: 28px;
    color: #007bff;
    margin-bottom: 15px;
    display: block;
}

.service-box h4 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: black;
}

.service-box a {
   
    color: black;
}

.service-box p {
    font-size: 14px;
    line-height: 1.6;
    color: #666;
}

/* Hover Effect for Services */
.service-box:hover {
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
    transform: translateY(-3px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .resume-services h2 {
        font-size: 24px;
    }
    .service-box {
        text-align: center;
    }
    .service-box i {
        margin-bottom: 10px;
    }
}
</style>
@endsection