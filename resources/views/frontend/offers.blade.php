@extends('frontend-layouts.app')

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

        .contact-list li a {
            font-size: 14px;
            /* Adjust the font size as needed */
        }

        .card {
            background-color: #fff;
            /* Background color */
            border-radius: 15px;
            /* Rounded corners */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            /* Increased shadow for 3D effect */
            padding: 20px;
            /* Padding for content */
        }

        .card-content {
            /* Add styles for card content if needed */
        }
    </style>
    
    <section class="banner-section-three">
        <div class="pattern-layer-two" style="background-image: url(images/bubble.png); background-size: contain; background-position: center; background-repeat: no-repeat;"></div>
        <div class="auto-container" style="margin-top: 100px;">
            <!-- Page Breadcrumb -->
            <div style="text-align: center;">
                <ul class="page-breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li>My Offers</li>
                </ul>
            </div>
            <div class="row clearfix">
                
                <!-- Content Column -->
                <div class="content-column col-lg-12 col-md-12 col-sm-12">
                    <div class="card mx-auto" style="width: 80%;">
                        <img src="images/Final Rev 1.png" alt="" srcset="" style="height:250px;">
                        
                        
                    </div>
                </div>
            </div>
            <div class="row clearfix mt-5">
                
                <!-- Content Column -->
                <div class="content-column col-lg-12 col-md-12 col-sm-12">
                    <div class="card mx-auto" style="width: 80%;">
                        <img src="images/FIVE ORDER ONE FREE.png" alt="" srcset="" style="height:250px;">
                        
                        
                    </div>
                </div>
            </div>
            <div class="row clearfix mt-5">
                
                <!-- Content Column -->
                <div class="content-column col-lg-12 col-md-12 col-sm-12">
                    <div class="card mx-auto" style="width: 80%;">
                        <img src="images/forty percent discount.png" alt="" srcset="" style="height:250px;">
                        
                        
                    </div>
                </div>
            </div>
            <div class="row clearfix mt-5">
                
                <!-- Content Column -->
                <div class="content-column col-lg-12 col-md-12 col-sm-12">
                    <div class="card mx-auto" style="width: 80%;">
                        <img src="images/recommend and earn money.pextends('frontend-layouts.app')

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

    
        .inner-column {
            padding-top: 0px !important;
        }

        .banner-section-three .content-column .inner-column h2 {
            padding-top: 100px;
        }

        .text-black {
            color: black;
        }

        .contact-list li a {
            font-size: 14px;
            /* Adjust the font size as needed */
        }

        .card {
            background-color: #fff;
            /* Background color */
            border-radius: 15px;
            /* Rounded corners */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            /* Increased shadow for 3D effect */
            padding: 20px;
            /* Padding for content */
        }

        .card-content {
            /* Add styles for card content if needed */
        }

    </style>
    <div class="head-container mt-6 px-4 py-6">
        <div class="header-content">
            <img src="assets/media/avatars/assignment_logo.png" alt="Assignment Logo" class="logo-img" />
            <h1 class="section-title"> Unlock Exclusive Assignment Help Offers & Student Discounts</h1>
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
	
    <section class="banner-section-three">
        <div class="pattern-layer-two" style="background-image: url(images/bubble.png); background-size: contain; background-position: center; background-repeat: no-repeat;"></div>
        <div class="auto-container" style="margin-top: 100px;">
            <!-- Page Breadcrumb -->
            <div style="text-align: center;">
                <ul class="page-breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li>My Offers</li>
                </ul>
            </div>
            <div class="row clearfix">
                
                <!-- Content Column -->
                <div class="content-column col-lg-12 col-md-12 col-sm-12">
                    <div class="card mx-auto" style="width: 80%;">
                        <img src="images/Final Rev 1.png" alt="" srcset="" style="height:250px;">
                        
                        
                    </div>
                </div>
            </div>
            <div class="row clearfix mt-5">
                
                <!-- Content Column -->
                <div class="content-column col-lg-12 col-md-12 col-sm-12">
                    <div class="card mx-auto" style="width: 80%;">
                        <img src="images/FIVE ORDER ONE FREE.png" alt="" srcset="" style="height:250px;">
                        
                        
                    </div>
                </div>
            </div>
            <div class="row clearfix mt-5">
                
                <!-- Content Column -->
                <div class="content-column col-lg-12 col-md-12 col-sm-12">
                    <div class="card mx-auto" style="width: 80%;">
                        <img src="images/forty percent discount.png" alt="" srcset="" style="height:250px;">
                        
                        
                    </div>
                </div>
            </div>
            <div class="row clearfix mt-5">
                
                <!-- Content Column -->
                <div class="content-column col-lg-12 col-md-12 col-sm-12">
                    <div class="card mx-auto" style="width: 80%;">
                        <img src="images/recommend and earn money.png" alt="" srcset="" style="height:230px;">
                        
                        
                    </div>
                </div>
            </div>
            <div class="row clearfix mt-5">
                
                <!-- Content Column -->
                <div class="content-column col-lg-12 col-md-12 col-sm-12">
                    <div class="card mx-auto" style="width: 80%;">
                        <img src="images/refer friend ten percent amount.png" alt="" srcset="" style="height:250px;">
                        
                        
                    </div>
                </div>
            </div>
            
            

            

        </div>

    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#existingOrdersBtn').addClass('active');
            $('#existingorders').show();

            $('#newOrdersBtn').click(function() {
                $(this).addClass('active');
                $('#existingOrdersBtn').removeClass('active');
                $('#neworders').show();
                $('#existingorders').hide();
            });

            $('#existingOrdersBtn').click(function() {
                $(this).addClass('active');
                $('#newOrdersBtn').removeClass('active');
                $('#existingorders').show();
                $('#neworders').hide();
            });
        });
    </script>
@endsection
ng" alt="" srcset="" style="height:230px;">
                        
                        
                    </div>
                </div>
            </div>
            <div class="row clearfix mt-5">
                
                <!-- Content Column -->
                <div class="content-column col-lg-12 col-md-12 col-sm-12">
                    <div class="card mx-auto" style="width: 80%;">
                        <img src="images/refer friend ten percent amount.png" alt="" srcset="" style="height:250px;">
                        
                        
                    </div>
                </div>
            </div>
            
            

            

        </div>

    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#existingOrdersBtn').addClass('active');
            $('#existingorders').show();

            $('#newOrdersBtn').click(function() {
                $(this).addClass('active');
                $('#existingOrdersBtn').removeClass('active');
                $('#neworders').show();
                $('#existingorders').hide();
            });

            $('#existingOrdersBtn').click(function() {
                $(this).addClass('active');
                $('#newOrdersBtn').removeClass('active');
                $('#existingorders').show();
                $('#neworders').hide();
            });
        });
    </script>
@endsection
