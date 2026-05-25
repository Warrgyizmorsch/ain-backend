@extends('layouts.app')
@section('content')
<style>
	.shadow-sm {
    display: none;
}
.text-gray-700 {
    margin-top: revert;
}
</style>
<style>
    .container {
        text-align: center;
        margin-top: 50px;
        position: absolute;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        padding: 10px;
        cursor: move; /* Cursor set to move when hovering over the container */
        display: none; /* Initially hidden */
		width: 340px;
   		height: 181px;
    	border-radius: 26px;	
    }
    .button {
        padding: 10px 20px;
        
        color: white;
        border: none;
        border-radius: 5px;
        margin: 10px;
        cursor: pointer;
    }
    #duration {
        font-size: 24px;
        margin-top: 20px;
    }
</style>
	<div>
		
	</div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div id="kt_content_container" class="">
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Order
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need for MarketingTeamRole</small>
                    </div>
                    
                </div>
	        </div>

			<div class="col-xl-12">
				@include('order.filter.marketing')
			</div>
            
			<div class="card card-xl-stretch  mb-xl-">
				<div class="card-header border-0 pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bolder fs-3 mb-1">All User</span>
						<span class="text-muted mt-1 fw-bold fs-7"></span>
					</h3>
				</div>
				<div class="card-body py-3">
					<div class="card-header border-0 pt-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1">Orders</span>
						</h3>
						
					</div>
					<!-- <button id="initialCallButton">Initial Call</button> -->
						<div class="container" id="container">
							<div id="duration">00:00:00</div>
							<button style="background:#000000" id="muteButton" class="button"><i class="fa fa-microphone" style="font-size:24px"></i></button>
    						<button style="background:#000000;  display:none" id="UnmuteButton" class="button"><i class="fa fa-microphone-slash" style="font-size:24px"></i></button>
							<button style="background:red; " id="hangupButton" class="button">Hang Up</button>
							<button style="background:#000000;" id="holdButton" class="button"><i class="fa fa-pause" style="font-size:24px"></i></button>
							<button style="background:#000000;display:none" id="UnholdButton" class="button"><i class="fa fa-play" style="font-size:24px"></i></button>
						</div>
					<div class="card-body py-3">
						<div class="table-responsive">
							<table  class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
								<thead class="p-2">
									<tr class="fw-bolder text-muted bg-light">
										<th class="min-w-15px">SR</th>
										<th class="min-w-50px">Order Code</th>
										<th class="min-w-50px text-center">User</th>
										<th class="min-w-30px">O Date</th>
										<th class="min-w-30px">D Date</th>
										<th class="min-w-100px">Title</th>
										<th class="min-w-40px">Status</th>
										<th class="min-w-40px">Word</th>
										<th class="min-w-40px">Amount</th>
										<th class="min-w-40px">Received</th>
										<th class="min-w-40px" >Due</th>
										<th class="min-w-40px" >writer </th>
										<th class="min-w-100px text-center" >Action</th>
									</tr>
								</thead>
								<tbody style="display:none" id="content" class="searchData">
							
								</tbody>
								<tbody class="allData">

                                    @foreach($data['orders'] as $order)
									<tr id="order_{{ $order->id }}" class="{{ ($order->is_read == 1) ? 'bold-row' : '' }}" onclick="markAsRead('{{ $order->id }}')">										<td>
										{{ $loop->index + 1 }}
										</td>
										<td class="text-center">
											{{ $order->order_id }}
                                            @if($order->is_fail == 1)
												<span class="badge badge-light-danger fs-7 fw-bold">Fail Order</span>
											@endif
											@if ($order->resit == 'on')
                                                <span class="badge badge-light-danger fs-7 fw-bold">Resit Work</span>
                                            @endif
											@if($order->services == 'First Class Work')
												<span class="badge badge-light-info fs-7 fw-bold">First Class Work</span>
											@endif

                                        </td>
										<td  class="text-center">
										@if($order->user != null && ($order->user->name != '' || $order->user->name == null))
											{{ $order->user->name }} <br>	
											<span class="badge badge-light-danger fs-7 fw-bold">{{ $order->user->mobile_no}}</span>
										@else
											N/A
										@endif

										</td>
										<td>
											{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
											
										</td>
										<td>
										{{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
										@if( $order->draftrequired == 'Y')
                                            <span class="badge badge-light-success  fs-7 fw-bold">{{ \Carbon\Carbon::parse($order->draft_date)->format('d M Y') }} ({{ \Carbon\Carbon::parse($order->draft_time)->format('H:i') }})</span>	
                                            @endif	
										</td>
                                        <td style="width:50px">
                                          {{$order->title }}
										  	@if( $order->chapter != '' )
                                            <span class="badge badge-light-danger fs-7 fw-bold">{{$order->chapter}}</span>	
                                            @endif	

											@if( $order->tech == '1' )
                                            <span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>	
                                            @endif	
											@if ($order->module_code != '')
                                                <span class="badge badge-light-danger fs-7 fw-bold">{{$order->module_code}}</span>
                                            @endif
                                        </td>
										<td>
                                            @if($order->projectstatus == 'Other')
											<span class="badge badge-light-primary fs-7 fw-bold " style="background:#44f2e4; color:black">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Pending')
											<span class="badge badge-light-warning fs-7 fw-bold" style="background:pink; color:white">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'In Progress')
											<span class="badge badge-light-info fs-7 fw-bold">{{$order->projectstatus}}</span>
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
											<span class="badge badge-light-primary fs-7 fw-bold" style="background:pink; color:white">{{$order->projectstatus}}</span>
                                            @endif
										</td>
										<td style="width:50px">
										@if($order->pages != '')
										{{$order->pages }} 
										@else
											N/A
										@endif
                                        </td>
                                        <td style="width:50px">
                                           {{$order->amount }} £
                                        </td>
                                        <td style="width:50px">
                                           {{$order->received_amount }} £
                                        </td>
										<td style="width:50px">
											@if(is_numeric($order->amount) && is_numeric($order->received_amount))
												{{ $order->amount - $order->received_amount }} £
											@else
												<!-- Handle the case where one or both values are non-numeric -->
												N/A
											@endif
										</td>
										<td style="width:50px">
                                           {{$order->writer_name }} 
                                        </td>

										


                                      
										<td class="text-end">
											<a target="_blank" href="edit.{{ $order->id }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
												<span class="svg-icon svg-icon-3">
													<li class="fa fa-eye "></li>
												</span>
											</a>
											@include('order.edit.marketing')

											<a href="#"  data-bs-toggle="modal" data-bs-target="#kt_modal_create_money{{$order->id}}" id="kt_toolbar_primary_button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
												<span class="svg-icon svg-icon-3">
													<li class="fa fa-money"></li>
												</span>
											</a>
											@include('order.section.payment-edit')

											<a href="#" onclick="showConfirmation({{ $order->id }})"class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
													<span class="svg-icon svg-icon-3">
														<li class="fa fa-close"></li>
													</span>
											</a>
											@include('order.section.fail-order')

											
											<a href="#" data-kt-drawer-toggle="#kt_drawer_chat" id="kt_drawer_chat_toggle{{ $order->order_id }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
												<span class="svg-icon svg-icon-3">
													<i class="fa fa-comment"></i>
												</span>
											</a>
											@include('order.section.comment-order')

											<a href="#" onclick="showConfirmationclick('{{ $order->id }}')" id="clickToCallBtn{{$order->id}}" class="btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1">
												<span class="svg-icon svg-icon-3">
													<i class="fa fa-phone fa-lg"></i>
												</span>
											</a>

											<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
											<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

											<script>
												function showConfirmationclick(orderId) {
													var primarycountrycode = $("#country_primary").val(); // Assuming country_primary is an input field
													var primarynumber = $("#primary").val(); // Assuming primary is an input field
													@if ($order->user)
														@if ($order->user->countrycode && $order->user->mobile_no)
															var apiUrl = "/click2call?callerNumber={{ Auth::user()->sip }}&receiverNumber={{ $order->user->countrycode }}{{ $order->user->mobile_no }}&user=anil&key=jbti89692vc60b2o9nu%^7";
														@endif
													@endif

													$.get(apiUrl, function(data, status) {
														// Use SweetAlert to display data and status
														Swal.fire({
															icon: 'success',
															title: 'Call Transfer Check SoftPhone',
															html: 'Data: ' + data + '<br>Status: ' + status,
														});
													});
												};
											</script>
												<a href="#" id="clickToDownload{{$order->order_id}}" class="btn btn-icon btn-bg-danger btn-active-color-dark btn-sm me-1 download-btn{{$order->id}}" onclick="downloadFiles(this)">
													<span class="svg-icon svg-icon-3">
														<i class="fa fa-download fa-lg"></i>
													</span>
												</a>
												<a onclick="CallToWriter('{{ $order->id }}')"  class="btn btn-icon btn-bg-warning btn-active-color-dark btn-sm me-1 download-btn" >
													<span class="svg-icon svg-icon-3">
														<i class="fa fa-phone fa-lg"></i>
													</span>											
												</a>
										</td>
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

<script>
	function downloadFiles(element) {
		// Prevent the default anchor behavior
		event.preventDefault();
		// Get the order ID from the id attribute of the clicked <a> tag
		var orderId = element.id.replace('clickToDownload', '');
		// Send AJAX request to get file URLs for the order
		$.ajax({
			url: '/get-files-by-order', // Update the URL to your route endpoint
			method: 'GET',
			data: {order_id: orderId},
			success: function(response) {
				 container.style.display = "block";
    			startTime = new Date().getTime();
			},
			error: function(xhr, status, error) {
				// Handle error
				console.error(xhr.responseText);
			}
		});
	}
</script>

<script src="https://cdn.socket.io/3.0.3/socket.io.min.js"></script>
<!-- Your table structure -->
<script>
    function markAsRead(orderId) {
        // Assuming you're using jQuery
        $.ajax({
            type: 'POST',
            url: '/mark-as-read',
            data: {
                order_id: orderId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update the UI in real-time
                    $('#order_' + orderId).removeClass('bold-row');
                    // You may also update other information here if needed
                } else {
                    alert('Failed to mark as read');
                }
            },
            error: function() {
                alert('Failed to communicate with the server');
            }
        });
    }

    // Listen for real-time updates
    window.Echo.channel('orders')
        .listen('OrderMarkedAsRead', (event) => {
            // Update the UI in real-time
            $('#order_' + event.order_id).removeClass('bold-row');
            // You may also update other information here if needed
        });
</script>

<style>
	.bold-row {
		font-weight: bold !important;
	}
</style>
<script>
    function CallToWriter(orderId) {
        // Assuming you're using jQuery
        $.ajax({
            type: 'POST',
            url: '/appdialnumber',
            data: {
                order_id: orderId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                container.style.display = "block";
        		startTime = new Date().getTime();
            },
            error: function(xhr, status, error) {
                // Display error message with SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to communicate with the server. Please try again later.',
                });
            }
        });
    }
</script>
<script>
    let isMuted = false;
    let isOnHold = false;
    let startTime = 0; // Start time initially set to 0
    let container = document.getElementById("container");

    // Function to handle mouse down event on container (for dragging)
    container.addEventListener("mousedown", function(e) {
        let offsetX = e.clientX - container.offsetLeft;
        let offsetY = e.clientY - container.offsetTop;
        let isDragging = true;

        // Function to handle mouse move event while dragging
        document.addEventListener("mousemove", dragHandler);

        // Function to handle mouse up event
        document.addEventListener("mouseup", function() {
            isDragging = false;
            document.removeEventListener("mousemove", dragHandler);
        });

        function dragHandler(e) {
            if (isDragging) {
                container.style.left = (e.clientX - offsetX) + "px";
                container.style.top = (e.clientY - offsetY) + "px";
            }
        }
    });

   
		document.getElementById("hangupButton").addEventListener("click", function() {
		// Show pop-up message
		alert("Call hung up");

		// Hide the call interface and reset duration timer
		hideCallInterfaceAndResetTimer();
	});

			function hideCallInterfaceAndResetTimer() {
				// Assuming you're using jQuery
				$.ajax({
					type: 'GET',
					url: '/HangUp', // Corrected typo in URL

					success: function(response) {
						// Hide the call interface
						var container = document.getElementById("container");
						container.style.display = "none";

						// Reset duration timer
						startTime = 0;
						document.getElementById("duration").innerText = "00:00:00";
					},
					error: function(xhr, status, error) {
						// Display error message with SweetAlert
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Failed to communicate with the server. Please try again later.',
						});
					}
				});
			}

	
			document.getElementById("holdButton").addEventListener("click", HoldCall);
			function HoldCall() {
				$.ajax({
					type: 'GET',
					url: '/HoldCall', // Corrected typo in URL

					success: function(response) {
						// Display the Unhold button and hide the Hold button
						document.getElementById("UnholdButton").style.display = "inline";
						document.getElementById("holdButton").style.display = "none";
					},
					error: function(xhr, status, error) {
						// Display error message with SweetAlert
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Failed to communicate with the server. Please try again later.',
						});
					}
				});
			}


			document.getElementById("UnholdButton").addEventListener("click", UnholdCall);

			function UnholdCall() {
				// Assuming you're using jQuery
				$.ajax({
					type: 'GET',
					url: '/unhold', // Corrected typo in URL

					success: function(response) {
						// Display the Hold button and hide the Unhold button
						document.getElementById("holdButton").style.display = "inline";
						document.getElementById("UnholdButton").style.display = "none";
					},
					error: function(xhr, status, error) {
						// Display error message with SweetAlert
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Failed to communicate with the server. Please try again later.',
						});
					}
				});
			}



	document.getElementById("muteButton").addEventListener("click", function() {
        document.getElementById("UnmuteButton").style.display = "inline";
        document.getElementById("muteButton").style.display = "none";
    });

	document.getElementById("UnmuteButton").addEventListener("click", function() {
        document.getElementById("muteButton").style.display = "inline";
        document.getElementById("UnmuteButton").style.display = "none";
    });

    // Function to update duration every second
    setInterval(function() {
        if (startTime !== 0) {
            let currentTime = new Date().getTime();
            let duration = currentTime - startTime;
            let hours = Math.floor((duration / (1000 * 60 * 60)) % 24);
            let minutes = Math.floor((duration / (1000 * 60)) % 60);
            let seconds = Math.floor((duration / 1000) % 60);

            document.getElementById("duration").innerText = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
    }, 1000);
</script>


  @endsection
  