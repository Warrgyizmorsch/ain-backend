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
                    <li>My Orders</li>
                </ul>
            </div>
            <div class="row clearfix">
                
                <!-- Content Column -->
                <div class="content-column col-lg-12 col-md-12 col-sm-12">
                    <div class="card mx-auto" style="width: 80%;">
                        <img src="images/Final Rev 1.png" alt="" srcset="" style="height:250px;">
                        
                        <div class="inner-column my-5 card-content">
                            <!-- <div class="pattern-layer-one" style="background-image: url(images/main-slider/pattern-1.png)"></div> -->
                            <div class="icon-layer" style="background-image: url(images/icons/contact-arrow.png)"></div>
                            <div class="icon-layer-two" style="background-image: url(images/icons/icon-5.png)"></div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 text-center mb-2">
                                    <button id="newOrdersBtn" type="button" class="btn btn-outline-info">
                                        Confirm orders
                                    </button>
                                    <button id="existingOrdersBtn" type="button" class="btn btn-outline-info active">Non-confirm orders</button>
                                </div>
                            </div>
                            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                                <script>
                                $(document).ready(function(){
                                    // Show Confirm Orders Div initially
                                    $("#confirmOrdersDiv").show();
                                    
                                    // Toggle between divs when buttons are clicked
                                    $("#newOrdersBtn").click(function(){
                                        $("#confirmOrdersDiv").show();
                                        $("#nonConfirmOrdersDiv").hide();
                                        $("#newOrdersBtn").addClass("active");
                                        $("#existingOrdersBtn").removeClass("active");
                                    });
                                    
                                    $("#existingOrdersBtn").click(function(){
                                        $("#confirmOrdersDiv").hide();
                                        $("#nonConfirmOrdersDiv").show();
                                        $("#newOrdersBtn").removeClass("active");
                                        $("#existingOrdersBtn").addClass("active");
                                    });
                                });
                                </script>
                            <div id="confirmOrdersDiv" class="col-lg-12 col-md-12 col-sm-12 mt-2 pl-0 pr-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="bg-info text-white">
                                            <tr>
                                                <th scope="col">Sr</th>
                                                <th scope="col">Order Code</th>
                                                <th scope="col">Order Date</th>
                                                <th scope="col">Delivery Date</th>
                                                <th scope="col">Title</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Word</th>
                                                <th scope="col">Total/Due Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($orders as $index => $order)
                                            <tr>
                                                <th scope="row">{{ $index + 1 }}</th>
                                                <td>{{ $order->order_id }}</td>
                                                <td>{{ $order->order_date }}</td>
                                                <td>{{ $order->delivery_date }}</td>
                                                <td>{{ $order->title }}</td>
                                                <td>
                                                    @if($order->projectstatus == 'Other')
                                                    <span class="badge badge-light-info fs-7 fw-bold  " style="background:#007f84; color:white">In Progress</span>
                                                    @elseif($order->projectstatus == 'Pending')
                                                    <span class="badge badge-light-warning fs-7 fw-bold" style="background:#007f84; color:white">In Progress</span>
                                                    @elseif($order->projectstatus == 'In Progress')
                                                    <span class="badge badge-light-info fs-7 fw-bold" style="background:#007f84; color:white">{{$order->projectstatus}}</span>
                                                    @elseif($order->projectstatus == 'Hold Work')
                                                    <span class="badge badge-light-danger fs-7 fw-bold">{{$order->projectstatus}}</span>
                                                    @elseif($order->projectstatus == 'Completed')
                                                    <span class="badge badge-light-warning fs-7 fw-bold" style="background:#eaea00; color:black">{{$order->projectstatus}}</span>
                                                    @elseif($order->projectstatus == 'Delivered')
                                                    <span class="badge badge-light-success fs-7 fw-bold" style="background:green; color:white">{{$order->projectstatus}}</span>
                                                    @elseif($order->projectstatus == 'Feedback')
                                                    <span class="badge badge-light-primary fs-7 fw-bold" style="background:black; color:white">{{$order->projectstatus}}</span>
                                                    @elseif($order->projectstatus == 'Feedback Delivered')
                                                    <span class="badge badge-light-danger fs-7 fw-bold" style="background:black; color:white">{{$order->projectstatus}}</span>
                                                    @elseif($order->projectstatus == 'Cancelled')
                                                    <span class="badge badge-light-danger fs-7 fw-bold">{{$order->projectstatus}}</span>
                                                    @elseif($order->projectstatus == 'Draft Ready')
                                                    <span class="badge badge-light-primary fs-7 fw-bold" style="background:#eaea00; color:black">{{$order->projectstatus}}</span>
                                                    @elseif($order->projectstatus == 'Draft Delivered')
                                                    <span class="badge badge-light-primary fs-7 fw-bold" style="background:green; color:white">{{$order->projectstatus}}</span>
                                                    @elseif($order->projectstatus == 'Initiated')
                                                    <span class="badge badge-light-primary fs-7 fw-bold" style="background:#007f84; color:white">In Progress</span>
                                                    @endif
                                                </td>
                                                <td>{{ $order->pages }}</td>
                                                <td>
                                                   {{ $order->amount }} / 
                                                    @if(is_numeric($order->amount) && is_numeric($order->received_amount))
                                                        {{ $order->amount - $order->received_amount }} 
                                                        @else
                                                            N/A
                                                        @endif    
                                                    </td>
                                              
                                              
                                            </tr>
                                        @endforeach
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="nonConfirmOrdersDiv"  class="col-lg-12 col-md-12 col-sm-12 mt-2 pl-0 pr-0 " style="display:none">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="bg-info text-white">
                                            <tr>
                                                <th scope="col">Sr</th>
                                                <th scope="col">Order Code</th>
                                                <th scope="col">Order Date</th>
                                                <th scope="col">Delivery Date</th>
                                                <th scope="col">Title</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Word</th>
                                                <th scope="col">amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leads as $index => $leads)
                                            <tr>
                                                <th scope="row">{{ $index + 1 }}</th>
                                                <td>{{ $leads->order_id }}</td>
                                                <td>{{ $leads->create_at }}</td>
                                                <td>{{ $leads->deadline }}</td>
                                                <td>{{ $leads->project_title }}</td>
                                                <td>Not Confirm </td>
                                                <td>{{ $leads->pages }}</td>
                                                <td>{{ $leads->price }}</td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
