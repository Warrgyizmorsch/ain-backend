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
										<th class="min-w-40px">Ticket Status</th>
										<th class="min-w-40px">Word</th>
										<th class="min-w-40px">Amount</th>
										<th class="min-w-40px">Received</th>
										<th class="min-w-40px" >Due</th>
										<th class="min-w-40px">Writer_name</th>
										<th class="min-w-100px text-center" >Action</th>
									</tr>
								</thead>
								<tbody style="display:none" id="content" class="searchData">
							
								</tbody>
								<tbody class="allData">

                                    @foreach($data['orders'] as $order)
									<tr @if( $order->user->is_fail == 1) style="color:blue"  @endif  id="order_{{ $order->id }}" class="{{ ($order->is_read == 1) ? 'bold-row' : '' }}" onclick="markAsRead('{{ $order->id }}')">										<td>
										{{ $loop->index + 1 }}
										</td>
										<td class="text-center">
											{{ $order->order_id }}
											@if($order->team?->team_name)
											<span class="badge badge-light-primary fs-7 fw-bold ">{{ $order->team->team_name }}</span>
											@endif
											<span class="badge badge-light-danger fs-7 fw-bold ">{{$order->feedback_ticket}}</span>
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
										<td onclick="updateDeliveryDate({{$order->id }})" >
										    @if($order->delivery_date != null)
												{{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
											@else
												Not Available
											@endif
										    @if( $order->draftrequired == 'Y')
                                            <span class="badge badge-light-success  fs-7 fw-bold">{{ \Carbon\Carbon::parse($order->draft_date)->format('d M Y') }} ({{ \Carbon\Carbon::parse($order->draft_time)->format('H:i') }})</span>	
                                            @endif	
										</td>
                                        <td style="width:50px">
                                          {{$order->title }}
                                           <br>
										 	@if( $order->semester != '' )
                                         	 	Semester :( {{$order->semester}})
                                            @endif	
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
										<td onclick="status('{{$order->id }}')" >
                                            @if($order->projectstatus == 'Other')
											<span class="badge badge-light-primary fs-7 fw-bold " style="background:#44f2e4; color:black">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Pending')
											<span class="badge badge-light-warning fs-7 fw-bold" style="background:pink; color:white">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'In Progress')
											<span class="badge badge-light-info fs-7 fw-bold">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Hold Work' || $order->projectstatus == 'Hold(writer query)')
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
											@elseif($order->projectstatus == 'Advance Assignment')
											<span class="badge badge-light-danger fs-7 fw-bold" style="background:#44f2e4; color:black">{{$order->projectstatus}}</span>
                                            @endif
										</td>
										<td>
											@if($order->status_issue == 'Issue Raised')
												<span class="badge badge-light-danger fs-7 fw-bold ">{{$order->status_issue}}</span>
												@elseif($order->status_issue == 'Client Discussion Done')
												<span class="badge badge-light-info fs-7 fw-bold" >{{$order->status_issue}}</span>
												@elseif($order->status_issue == "Writer discussion Done")
												<span class="badge badge-light-success fs-7 fw-bold" >{{$order->status_issue}}</span>
												@elseif($order->status_issue == 'Work in progress')
												<span class="badge badge-light-warning fs-7 fw-bold" >{{$order->status_issue}}</span>
												@elseif($order->status_issue == 'Case Resolved')
												<span class="badge badge-light-success fs-7 fw-bold" >{{$order->status_issue}}</span>
												@elseif($order->status_issue == 'Issues Raised Again')
												<span class="badge badge-light-danger fs-7 fw-bold" style="background:red; color:white">{{$order->status_issue}}</span>
												@else
												<span class="badge badge-light-warning fs-7 fw-bold" > </span>
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

										<td>
											@if($order->writer_name != null)
												{{ $order->writer_name }}
												<br>
												<span style="background-color: #f8f5ff;" class="badge badge-light-info fs-7 fw-bold">{{ \Carbon\Carbon::parse($order->writer_deadline)->format('d M Y') }}</span>	
											@else
                                            <span class="badge badge-light-danger fs-7 fw-bold">N/A</span>											
                                            @endif
										</td>


                                      
										<td class="text-end">
											<a target="_blank" href="edit.{{ $order->id }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
												<span class="svg-icon svg-icon-3">
													<li class="fa fa-eye "></li>
												</span>
											</a>
											@include('order.edit.marketing')

    										@php
    											$showQuestionMark = false;
    
    											if (count($order->payment) >= 1) {
    												foreach ($order->payment as $payment) {
    													if (empty($payment['payee_name']) || empty($payment['company_accounts'])) {
    														$showQuestionMark = true;
    														break;
    													}
    												}
    											}
    										@endphp
    
    										<a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_create_money{{$order->id}}" id="kt_toolbar_primary_button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 position-relative">
    											<span class="svg-icon svg-icon-3">
    												<li class="fa fa-money"></li>
    											</span>
    
    											@if($showQuestionMark)
    												<span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger animate__animated animate__flash animate__infinite" style="font-size: 10px; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center;">
    													?
    												</span>
    											@endif
    										</a>

											@include('order.section.payment-edit')

											<a href="#" onclick="showConfirmation({{ $order->id }})"class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
													<span class="svg-icon svg-icon-3">
														<i>F</i>
													</span>
											</a>
											@include('order.section.fail-order')

											
											<a href="#" data-kt-drawer-toggle="#kt_drawer_chat" id="kt_drawer_chat_toggle{{ $order->order_id }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
												<span class="svg-icon svg-icon-3">
													<i>T</i>
												</span>
											</a>
											@include('order.section.comment-order')
												<a   target="_blank" href="/call.{{$order->id}}" class="btn btn-icon btn-bg-warning btn-active-color-light btn-sm me-1">Call</a>

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
														// Check if response is empty
														if (response.length === 0) {
															// Show SweetAlert popup for no files to download
															Swal.fire({
																icon: 'warning',
																title: 'No Files Found',
																text: 'There are no files to download for this order.',
																confirmButtonColor: '#3085d6',
																confirmButtonText: 'OK'
															});
														} else {
															// Loop through each file URL and trigger download
															response.forEach(function(fileUrl) {
																// Create an anchor element for the file
																var link = document.createElement('a');
																link.href = fileUrl;
																link.download = fileUrl.substring(fileUrl.lastIndexOf('/') + 1);
																document.body.appendChild(link);

																// Simulate a click event to trigger the download
																link.click();

																// Clean up
																document.body.removeChild(link);
															});
														}
													},
													error: function(xhr, status, error) {
														// Handle error
														console.error(xhr.responseText);
													}
												});
											}
										</script>


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
<style>
	
</style>
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
    if (window.Echo?.channel) {
		window.Echo.channel('orders')
			.listen('OrderMarkedAsRead', (event) => {
				$('#order_' + event.order_id).removeClass('bold-row');
			});
	} else {
		console.warn('Echo is not available.');
	}
</script>

<style>
	.bold-row {
		font-weight: bold !important;
	}
</style>
<script>
	function status(orderId) {
		let data = <?php echo json_encode($data['Status']); ?>;
		let statusValues = Object.values(data).map(item => item.status);
		Swal.fire({
			title: 'Change Status',
			text: 'Select Status',
			icon: 'info',
			input: 'select',
			inputOptions: statusValues,
			inputPlaceholder: 'Select status',
			showCancelButton: true,
			confirmButtonText: 'OK',
			cancelButtonText: 'Cancel',
			preConfirm: (selectedStatus) => {
				if (!selectedStatus) {
					Swal.fire({
						title: 'Error!',
						text: 'Status cannot be empty!',
						icon: 'error'
					});
					return false; // Prevent further execution
				}
				
				let updateData = {
					orderId: orderId,
					status: selectedStatus,
					_token: '{{ csrf_token() }}'
				};
				$.ajax({
					type: 'POST',
					url: 'update_status',
					data: updateData,
					success: function(response) {
						if (response.warning) {
							Swal.fire({
								icon: 'warning',
								title: 'Warning',
								text: response.warning
							});
						} else {
							Swal.fire({
								icon: 'success',
								title: 'Success',
								text: 'Status updated successfully'
							}).then(() => {
								// Reload the page after showing the success message
								location.reload();
							});
						}
					},
					error: function(xhr, status, error) {
						console.log(updateData);
					}
				});
			}
		});
	}
</script>
<script>
    function updateDeliveryDate(orderId) {
        // Show date picker
        Swal.fire({
            title: 'Select Delivery Date',
            html: '<input type="date" id="deliveryDate" class="swal2-input">',
            confirmButtonText: 'Confirm',
            preConfirm: () => {
                const selectedDate = document.getElementById('deliveryDate').value;
                if (!selectedDate) {
                    Swal.showValidationMessage('Please select a delivery date');
                }
                return selectedDate;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Retrieve selected date
                const selectedDate = result.value;
                // Perform actions with the selected date (e.g., update status)
                console.log('Order ID:', orderId);
                console.log('Selected Delivery Date:', selectedDate);

                // Assuming you have the CSRF token available in a variable named csrfToken
                const csrfToken = '{{ csrf_token() }}';

                // Send AJAX request to update delivery date
                $.ajax({
                    url: 'update_date',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        orderId: orderId,
                        selectedDate: selectedDate
                    },
                    success: function(response) {
						if (response.Error) {
							// Show SweetAlert error message if there is an error in the response
							Swal.fire('Error!', response.Error, 'error');
						} else {
							// Reload the page if date updated successfully
							location.reload();
						}
					},
					error: function(xhr, status, error) {
						console.error(xhr.responseText);
						Swal.fire('Error!', 'An unexpected error occurred.', 'error');
					}
                });
            }
        });
    }
</script>

  @endsection
  