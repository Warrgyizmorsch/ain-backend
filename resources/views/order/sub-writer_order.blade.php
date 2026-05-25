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
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need for Sub-Writer</small>
                    </div>
                    
                </div>
	        </div>

			<div class="col-xl-12">
				@include('order.filter.subwriter_filter')
			</div>
            
			<div class="card card-xl-stretch  mb-xl-">
				
				<div class="card-body py-3">
					
					<div class="card-body py-3">
						<div class="table-responsive">
							<table  class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
								<thead class="p-2">
									<tr class="fw-bolder text-muted bg-light">
										<th class="min-w-15px">SR</th>
										<th class="min-w-50px text-center">Order Code</th>
										
										<th class="min-w-50px">Delivery From - Upto</th>

										<th class="min-w-100px">Title</th>
										<th class="min-w-40px">Status</th>

										<th>Words</th>
										
										<th class="min-w-100px text-center" >Actions</th>
									</tr>
								</thead>
								<tbody style="display:none" id="content" class="searchData">
							
								</tbody>
								<tbody class="allData">

                                    @foreach($data['orders'] as $order)
									<tr id="order_{{ $order->id }}" class="{{ ($order->is_read == 1) ? 'bold-row' : '' }}" onclick="markAsRead('{{ $order->id }}')">										
										<td>
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
										
										<td class="min-w-170px">
											<div class="d-flex">
												@if($order->writer_fd != '0000-00-00')
													{{ \Carbon\Carbon::parse($order->writer_fd)->format('d M ') }}
													<span class="badge badge-light-danger fs-7 fw-bold"> {{$order->writer_fd_h}}</span>
												@endif
											</div>
										<br>
											<div class="d-flex">
												@if($order->writer_ud != '0000-00-00')
													{{ \Carbon\Carbon::parse($order->writer_ud)->format('d M ') }}
													<span class="badge badge-light-danger fs-7 fw-bold"> {{$order->writer_ud_h}}</span>
												@endif
											</div>
										</td>
                                        <td style="width:50px">
                                          {{$order->title }}
										  	@if( $order->chapter != '' )
                                            <span class="badge badge-light-danger fs-7 fw-bold">{{$order->chapter}}</span>	
                                            @endif	

											@if( $order->tech == '1' )
                                            <span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>	
                                            @endif	
                                        </td>
    									<td>
    									  @if($order->writer_status == 'Not Assigned')
                                             <span class="badge badge-light-danger fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @elseif($order->writer_status == 'In Progress')
                                                 <span class="badge badge-light-info fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @elseif($order->writer_status == 'Draft Ready')
                                                 <span class="badge badge-light-danger fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @elseif($order->writer_status == 'Draft Feedback' )
                                                 <span class="badge badge-light-warning fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @elseif($order->writer_status == 'Attached to Email (Draft)' )
                                                 <span class="badge badge-light-danger fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @elseif($order->writer_status == 'Draft Delivered' )
                                                 <span class="badge badge-light-success fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @elseif($order->writer_status == 'Complete file Ready' )
                                                 <span class="badge badge-light-warning fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @elseif($order->writer_status == 'Feedback' )
                                                 <span class="badge badge-light-warning fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @elseif($order->writer_status == 'Feedback Delivered' )
                                                 <span class="badge badge-light-warning fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @elseif($order->writer_status == 'Attached to Email (Complete file)' )
                                                 <span class="badge badge-light-info fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @elseif($order->writer_status == 'Delivered' )
                                                 <span class="badge badge-light-success fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @elseif($order->writer_status == 'Hold' )
                                                 <span class="badge badge-light-danger fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @elseif($order->writer_status === '' || $order->writer_status === null)
                                                 <span class="badge badge-light-danger fs-7 fw-bold" >Not Assigned</span>
                                             @else
                                                 <span class="badge badge-light-danger fs-7 fw-bold" > {{ $order->writer_status }}</span>
                                             @endif
    									</td>

										<!-- <td>{{$order->writer_status}}</td> -->
                                        

										<td>{{$order->pages}}</td>

                                      
										<td class="text-center">
										<a href="#" id="{{ $order->order_id }}" data-bs-toggle="modal"
											data-bs-target="#confirmationModal{{ $order->order_id }}"
											class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"">
												<span class=" svg-icon svg-icon-3">
											<li class="fa fa-edit"></li>
											</span>
										</a>
									</td>


									<div class="modal fade" id="confirmationModal{{ $order->order_id }}" tabindex="-1"
										role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="confirmationModalLabel">Update Order
													</h5>

													<a href="/edit.{{$order->id}}" class=""
														style="margin-left: 10px;">({{ $order->order_id }})</a>
													<button type="button" class="btn-close" data-bs-dismiss="modal"
														aria-label="Close"></button>
												</div>
												<form id="kt_modal_new_target_form" class="form" action="{{ route('orderedit.update', ['id' => $order->id]) }}" method="POST">
													@csrf
												<div class="col-xl-12">
													<div class="card-body" id="kt_drawer_chat_messenger_body">
														
															<div class="row g-9 mb-8 text-start">
																<div class="col-md-12 fv-row">
																	<select name="status" id="subwriter" class="form-select form-select-solid" placeholder="">
																		<option {{ $order->writer_status == '' ? 'selected' : '' }} value="">Select Status</option>
																		<option {{ $order->writer_status == 'Completed' ? 'selected' : '' }} value="Completed">Completed</option>
																		<option {{ $order->writer_status == 'Hold' ? 'selected' : '' }} value="Hold">Hold</option>
																	</select>
																</div>
															</div>

															<div class="card-footer">
                                                                <button  type="submit" class="btn btn-lg btn-primary fw-bolder">
                                                                    Submit
                                                                </button>
															</div>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
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

  @endsection
  