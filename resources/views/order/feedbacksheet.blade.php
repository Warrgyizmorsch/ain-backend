@extends('layouts.app')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div id="kt_content_container" class="">
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">FeedBackSheet
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need</small>
                    </div>
                    <div class="d-flex align-items-center py-1">
                    </div>
                </div>
	        </div>

			<div class="col-xl-12">
			@include('order.section.feedbackfilter')
			</div>
            
			<div class="card card-xl-stretch  mb-xl-">
				<div class="card-header border-0 pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bolder fs-3 mb-1">All FeedBack Comment</span>
						<span class="text-muted mt-1 fw-bold fs-7"></span>
					</h3>
				</div>
				<div class="card-body py-3">
					
					<div class="card-body py-3">
						<div class="table-responsive">
							<table  class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
								<thead class="p-2">
									<tr class="fw-bolder text-muted bg-light">
										<th class="min-w-15px">SR</th>
										<th class="min-w-50px">Order Code</th>
										@if(auth()->user()->role_id != 4)
										<th class="min-w-30px">WriterTeam</th>
										@endif
										<th class="min-w-90px">Order Date</th>
										<th class="min-w-30px">Feddback Date</th>
										<th class="min-w-30px">Status</th>
										<th class="min-w-100px">Comments</th>
										<th class="min-w-40px">Action</th>
									
									</tr>
								</thead>
                                @foreach($orders as $order)
								<tbody>
                                  
                                    <td>{{ $loop->index +1}}</td>
                                    <td>
    									{{ $order->order_id}}<br>
    									<span class="badge badge-light-danger fs-7 fw-bold text-nowrap">TN-{{ $order->feedback_ticket }}</span>
										 @if($order->team?->team_name)
										<span class="badge badge-light-primary fs-7 fw-bold mb-1">{{ $order->team->team_name }}</span><br>
										@endif
									</td>
									@if(auth()->user()->role_id != 4)
										<td>{{  $order->writer_name}}</td>
									@endif
                                    <td>
										@if($order->order_date)
										{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
										@endif
									</td>

                                    <td>{{ \Carbon\Carbon::parse($order->feedback_date)->format('d M Y h:i a') }}</td>
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
											@elseif($order->status_issue == 'Retention')
											<span class="badge badge-light-danger fs-7 fw-bold" style="background:red; color:white">{{$order->status_issue}}</span>
											@else
											<span class="badge badge-light-success fs-7 fw-bold" >{{$order->status_issue}}</span>
										@endif
									</td>
									
									<td>
										{{ $order->comment }}
										<a href="#" id="{{ $order->order_id }}" data-bs-toggle="modal" data-bs-target="#confirmationModal{{ $order->order_id }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
											More...
										</a>
									</td>

									<div class="modal fade" id="confirmationModal{{ $order->order_id }}" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered mw-650px" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="confirmationModalLabel">Feedback - {{ $order->order_id }}</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												
												<div class="modal-body scroll-y mh-400px">
													<div class="timeline-label">
														@foreach($order->feedback->sortByDesc('created_at') as $feedback)
															@if($feedback->comment != '')
																<div class="timeline-item d-flex align-items-start mb-7">
																	<div class="timeline-line border-start-2 border-gray-300 ms-2 me-4" style="height: 100%; position: absolute; left: 8px;"></div>
																	
																	<div class="timeline-icon me-4" style="z-index: 1;">
																		<i class="fa fa-genderless text-{{ $feedback->created_by == Auth::user()->id ? 'primary' : 'success' }} fs-1"></i>
																	</div>

																	<div class="timeline-content flex-grow-1">
																		<div class="d-flex justify-content-between align-items-start mb-1">
																			<div>
																				<span class="fs-6 fw-bolder text-gray-800 me-2">{{ $feedback->user->name ?? 'System' }}</span>
																				<span class="text-muted fw-bold fs-8">{{ $feedback->created_at->format('d M Y, h:i a') }}</span>
																			</div>
																			
																			<div class="status-badge">
																				@php
																					$status = $feedback->status; // Assuming feedback table has 'status' field
																					$badgeStyle = "";
																				@endphp
																				
																				@if($status == 'Pending')
																					<span class="badge fs-9 fw-bold" style="background:pink; color:white">{{ $status }}</span>
																				@elseif($status == 'Completed' || $status == 'Draft Ready')
																					<span class="badge fs-9 fw-bold" style="background:#eaea00; color:black">{{ $status }}</span>
																				@elseif($status == 'Delivered' || $status == 'Draft Delivered')
																					<span class="badge fs-9 fw-bold" style="background:green; color:white">{{ $status }}</span>
																				@elseif($status == 'Feedback' || $status == 'Feedback Delivered')
																					<span class="badge fs-9 fw-bold" style="background:black; color:white">{{ $status }}</span>
																				@elseif($status == 'In Progress')
																					<span class="badge badge-light-info fs-9 fw-bold">{{ $status }}</span>
																				@elseif($status == 'Cancelled' || $status == 'Hold Work')
																					<span class="badge badge-light-danger fs-9 fw-bold">{{ $status }}</span>
																				@else
																					<span class="badge badge-light-primary fs-9 fw-bold">{{ $order->projectstatus ?? 'N/A' }}</span>
																				@endif
																			</div>
																		</div>

																		<div class="p-3 bg-light rounded text-gray-700 fw-bold border border-gray-200">
																			{{ $feedback->comment }}
																		</div>
																	</div>
																</div>
															@endif
														@endforeach
													</div>
													</div>
												
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</div>

                                    <td class="icon-container my-auto d-flex">
                                        <a href="#"  style="background-color:black" data-kt-drawer-toggle="#kt_drawer_chat{{ $order->order_id }}" id="kt_drawer_chat_toggle{{ $order->order_id }}" class="btn btn-icon btn-bg-secondary btn-active-color-primary btn-sm me-1">
                                            <span class="svg-icon svg-icon-3">
                                            <li style="color:white" class="fa fa-edit"></li>
                                            </span>
                                        </a>

										
										@include('order.section.order-action-edit')
										       

										<a href="#" data-bs-toggle="modal" data-bs-target="#statusModal{{$order->id}}" id="{{ $order->order_id }}" class="btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1">
                                            <span class="svg-icon svg-icon-3" style="color:white">
                                              S
                                            </span>
                                        </a>
										
										@include('order.section.feedback-status')

                                        <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_create_appaa" id="kt_toolbar_primary_button" class="btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1">
                                            <span class="svg-icon svg-icon-3">
                                                <li style="color:white" class="fa fa-phone fa-lg"></li>
                                            </span>
                                        </a>
                                        </div>
                                    </td>
								</tbody>
                                @endforeach
							</table>
					
								@if ($orders instanceof \Illuminate\Pagination\AbstractPaginator)
									{{ $orders->links() }}
								@else
								
									{{ $orders->onEachSide(1)->links() }}
								@endif					
						</div>
					</div>

				</div>
			</div>

        </div>
    </div>
</div>
<style>
	.shadow-sm {
    display: none;
}
.text-gray-700 {
    margin-top: revert;
}

.timeline-label { position: relative; }
    .timeline-item { position: relative; }
    .timeline-label::before {
        content: "";
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #eff2f5;
        z-index: 0;
    }
    .timeline-icon i {
        background-color: #ffffff;
        padding: 2px;
    }
</style>
<script>
    $(document).ready(function() {
        // --- Team Alpha Click ---
        $('#teamAlphaBtn').on('click', function() {
            $('#filter_team_id').val('1'); // Alpha ki ID 1 hai
            // Form ko programmatically submit kar do taaki search start ho jaye
            $('#searchForm').submit(); 
        });

        // --- Team Giga Click ---
        $('#teamGigaBtn').on('click', function() {
            $('#filter_team_id').val('2'); // Giga ki ID 2 hai
            $('#searchForm').submit();
        });
    });
</script>
@endsection