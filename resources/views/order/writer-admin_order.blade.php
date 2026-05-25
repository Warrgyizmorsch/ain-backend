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
				<div data-kt-swapper="true" data-kt-swapper-mode="prepend"
					data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
					class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
					<h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Order
						<span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
						<small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need for Writer-Admin</small>
					</h1>
					<span id="orderCount" class="badge bg-primary mx-2"></span>
					<span id="wordCount" class="badge bg-success mx-2"></span>
				</div>

			</div>
		</div>

		<div class="col-xl-12">
				@include('order.filter.admin_filter')
			</div>
            

		<div class="card card-xl-stretch  mb-xl-">
			
			<div class="card-body py-3">

				<div class="card-body py-3">
					<div class="table-responsive">
						<table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
							<thead class="p-2">
								<tr class="fw-bolder text-muted bg-light">
									<th class="min-w-15px">SR</th>
									<th class="min-w-50px text-center">Order Code</th>
									<th class="min-w-70px">Delivery From - Upto</th>
									<th class="min-w-50px">Title</th>
									<th class="min-w-40px">Status</th>
									<th>Words</th>
									<th>Writer</th>
									<th class="min-w-40px">Deadline</th>
									<th class="min-w-50px text-center">Actions</th>

								</tr>
							</thead>
							<tbody style="display:none" id="content" class="searchData">

							</tbody>
							<tbody class="allData">

								@foreach($data['orders'] as $order)
								<tr id="order_{{ $order->id }}" class="{{ ($order->is_read == 1) ? 'bold-row' : '' }}"
									onclick="markAsRead('{{ $order->id }}')">
									<td>{{ $loop->index + 1 }}</td>
									<td class="text-center">
										{{ $order->order_id }}
										@if($order->is_fail == 1)
										<br><span class="badge badge-light-danger fs-7 fw-bold m-1">Fail Order</span>
										@endif
										@if ($order->resit == 'on')
										<br><span class="badge badge-light-danger fs-7 fw-bold m-1">Resit</span>
										@endif
										@if($order->services == 'First Class Work')
										<br><span class="badge badge-light-info fs-7 fw-bold m-1">First Class
											Work</span>
										@endif

									</td>
									<td class="min-w-170px">
										<div class="d-flex">
											@if($order->writer_fd != '0000-00-00' && $order->writer_fd != NULL && $order->writer_fd != '')
												{{ $order->writer_fd}}
												@if($order->writer_fd_h)
													<span class="badge badge-light-danger fs-7 fw-bold"> {{date('h:i A', strtotime($order->writer_fd_h))}}</span>
												@endif
											@endif
										</div>
									<br>
										<div class="d-flex">
											@if($order->writer_ud != '0000-00-00' && $order->writer_ud != NULL && $order->writer_ud != '')
												{{ $order->writer_ud}}
												@if($order->writer_ud_h)
													<span class="badge badge-light-danger fs-7 fw-bold"> {{date('h:i A', strtotime($order->writer_ud_h))}}</span>
												@endif
											
											@endif
										</div>
										@if($order->writer_fd == '0000-00-00' || $order->writer_fd == NULL || $order->writer_fd == '' || $order->writer_ud == '0000-00-00' || $order->writer_ud == NULL || $order->writer_ud == '')
											<div class="d-flex">
												<span class="badge badge-light-danger fs-7 fw-bold">Not Assigned</span>											
											</div>
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
									</td>

									<td>
										@if($order->writer_status == 'In progress')
											<span class="badge badge-light-info fs-7 fw-bold m-1">In Progress</span>
										@elseif($order->writer_status == 'Completed')
											<span class="badge badge-light-success fs-7 fw-bold m-1">Completed</span>
										@elseif($order->writer_status == 'Delivered')
											<span class="badge badge-light-info fs-7 fw-bold m-1">Delivered</span>
										@elseif($order->writer_status == 'Hold')
											<span class="badge badge-light-danger fs-7 fw-bold m-1">Hold</span>
										@elseif($order->writer_status == 'Feedback')
											<span class="badge badge-light-warning fs-7 fw-bold m-1">Feedback</span>
										@elseif($order->writer_status == 'Feedback Delivered')
											<span class="badge badge-success fs-7 fw-bold m-1">Feedback Delivered</span>
										@elseif($order->writer_status == 'Draft Delivered')
											<span class="badge badge-secondary fs-7 fw-bold m-1">Draft Delivered</span>
										@elseif($order->writer_status == 'Quality Accepted')
											<span class="badge badge-light-success fs-7 fw-bold m-1">Quality Accepted</span>
										@elseif($order->writer_status == 'Quality Rejected')
											<span class="badge badge-light-danger fs-7 fw-bold m-1">Quality Rejected</span>
										@elseif($order->writer_status == '')
											<span class="badge badge-light-danger fs-7 fw-bold m-1">Not Assign</span>
										@elseif($order->writer_status == 'Draft Ready')
											<span class="badge badge-light-primary fs-7 fw-bold m-1">Draft Ready</span>
										@elseif($order->writer_status == 'Draft Feedback')
											<span class="badge badge-success fs-7 fw-bold m-1">Draft Feedback</span>
										@elseif($order->writer_status == 'Attached to Email (Draft)')
											<span class="badge badge-light-warning fs-7 fw-bold m-1">Attached to Email (Draft)</span>
										@elseif($order->writer_status == 'Complete file Ready')
											<span class="badge badge-secondary fs-7 fw-bold m-1">Complete file Ready</span>
										@elseif($order->writer_status == 'Attached to Email (Complete file)')
											<span class="badge badge-light-primary fs-7 fw-bold m-1">Attached to Email (Complete file)</span>
										@else
											<!-- Handle other cases or provide a default badge -->
											<span class="badge badge-light-info fs-7 fw-bold m-1">{{$order->writer_status}}</span>
										@endif
									</td>

									<td>{{$order->pages}}</td>
									<!-- <td>{{ $order['subwriter']['name'] ?? '' }}</td> -->
									<td>
										@if( $order['writer'] && !$order['writer']['name'] == "")
											@if ($order->mulsubwriter)
												{{-- Output email addresses --}}
												@foreach ($order->mulsubwriter as $writer)
													{{ $writer->user->name }} <br>
												@endforeach
											@else
												No writers found for this order.
											@endif
											<br>
												<span class="badge badge-light-info fs-7 fw-bold">({{
											$order['writer']['name'] }})</span>

										@else
										Not Assign
										@endif


									</td>
									<td>
									    {{ \Carbon\Carbon::parse($order->writer_deadline)->format('d M Y') }}
									    @if($order->draftrequired == 'Y')
											<br>
											<span class="badge badge-light-info fs-7 fw-bold">
												( 
													@if($order->draft_date && $order->draft_date != '0000-00-00')
														{{ \Carbon\Carbon::parse($order->draft_date)->format('d M Y') }}
													@endif
													@if($order->draft_time && $order->draft_time != '00:00:00')
														{{ \Carbon\Carbon::parse($order->draft_time)->format('g:i A') }}
													@endif
												)
											</span>
										@endif
									</td>




									<td class="text-center" target="_blank">
										<a href="/edit.{{ $order->id }}" 
											class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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
														<div class="scroll-y me-n5 pe-5" data-kt-element="messages"
															data-kt-scroll="true" data-kt-scroll-activate="true"
															data-kt-scroll-height="auto"
															data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
															data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body"
															data-kt-scroll-offset="0px">
															<div class="row g-9 mb-8 text-start">
																<!-- Add an onchange event to the TL select box to trigger the JavaScript function -->
															


																<!-- Add an empty container for subwriters that will be populated dynamically -->
																<div class="col-md-12 fv-row">
																		<select name="tlSelect" id="tlSelect{{ $order->order_id }}" class="form-select form-select-solid" placeholder="">
																			<option value="">Select Writer</option>
																			<option {{ $order->wid == Auth::user()->id ? 'selected' : '' }} value="{{ Auth::user()->id }}">My Writer</option>
																			@foreach($data['writerTL'] as $tl)
																				<option {{ $order->wid == $tl->id ? 'selected' : '' }} value="{{ $tl->id }}">{{ $tl->name }}</option>
																			@endforeach
																		</select>
																	</div>

																	<div class="col-md-12 fv-row" id="subwriterContainer{{ $order->order_id }}">
																		<select name="subwriterSelect[]" id="subwriterSelect{{ $order->order_id }}" class="form-select form-select-solid" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true"  multiple="multiple">
																			<option></option>
																		</select>
																	</div>
																	

																	<input type="hidden" value="{{ $order->swid }}" id="swid{{ $order->order_id }}">

																	<script>
																		function populateSubwriters() {
																			var tlId = document.getElementById('tlSelect{{ $order->order_id }}').value;
																			var subwriterSelect = document.getElementById('subwriterSelect{{ $order->order_id }}');
																			var swid = document.getElementById('swid{{ $order->order_id }}').value;

																			subwriterSelect.innerHTML = '<option value="">Select Subwriter</option>';

																			if (tlId !== '') {
																				@foreach($data['SubWriter'] as $subWriter)
																					if ("{{ $subWriter->tl_id }}" === tlId) {
																						var option = document.createElement('option');
																						option.value = "{{ $subWriter->id }}";
																						option.text = "{{ $subWriter->name }}";
																						if ("{{ $subWriter->id }}" === swid) {
																							option.selected = true;
																						}
																						subwriterSelect.appendChild(option);
																					}
																				@endforeach
																			}
																		}

																		document.getElementById('tlSelect{{ $order->order_id }}').addEventListener('change', populateSubwriters);
																		populateSubwriters();
																	</script>



																<div class="col-md-12 fv-row">
																	<select name="status" id="subwriter" class="form-select form-select-solid" placeholder="">
																		<option {{ $order->writer_status == '' ? 'selected' : '' }} value="">Select Status</option>
																		<option {{ $order->writer_status == 'In progress' ? 'selected' : '' }} value="In progress">In Progress</option>
																		<option {{ $order->writer_status == 'Completed' ? 'selected' : '' }} value="Completed">Completed</option>
																		<option {{ $order->writer_status == 'Delivered' ? 'selected' : '' }} value="Delivered">Delivered</option>
																		<option {{ $order->writer_status == 'Hold' ? 'selected' : '' }} value="Hold">Hold</option>
																		<option {{ $order->writer_status == 'Draft Delivered' ? 'selected' : '' }} value="Draft Delivered">Draft Delivered</option>
																		<option {{ $order->writer_status == 'Feedback' ? 'selected' : '' }} value="Feedback">Feedback</option>
																		<option {{ $order->writer_status == 'Feedback Delivered' ? 'selected' : '' }} value="Feedback Delivered">Feedback Delivered</option>
																		<option {{ $order->writer_status == 'Quality Accepted' ? 'selected' : '' }} value="Quality Accepted">Quality Accepted</option>
																		<option {{ $order->writer_status == 'Quality Rejected' ? 'selected' : '' }} value="Quality Rejected">Quality Rejected</option>
																	</select>
																</div>

																<div class="col-md-6 fv-row">
																	@if($order->writer_fd != 0000-00-00)
																	<input type="date" name="fromdate" id=""
																		class="form-control form-control-solid"
																		placeholder="From  " value="{{ \Carbon\Carbon::parse($order->writer_fd)->format('Y-m-d') }}">
																	@else
																	<input type="date" name="fromdate" id=""
																		class="form-control form-control-solid"
																		placeholder="From  " >
																	@endif
																</div>
																<div class="col-md-6 fv-row">
																	<select name="writer_fd_half" id=""
																		class="form-select form-select-solid"
																		placeholder="">
																		<option {{ $order->writer_fd_h == '' ? 'selected' : '' }} value="">Select Status</option>
																		<option {{ $order->writer_fd_h == 'First Half' ? 'selected' : '' }} value="First Half">First Half</option>
																		<option {{ $order->writer_fd_h == 'Second Half' ? 'selected' : '' }} value="Second Half">Second Half</option>
																	</select>
																</div>
																<div class="col-md-6 fv-row">
																@if($order->writer_ud != 0000-00-00)
																		<input type="date" name="uptodate" id=""
																			class="form-control form-control-solid"
																			placeholder="Upto " value="{{ \Carbon\Carbon::parse($order->writer_ud)->format('Y-m-d') }}" >
																	@else
																	<input type="date" name="uptodate" id=""
																			class="form-control form-control-solid"
																			placeholder="From  " >
																@endif
																	</div>
																<div class="col-md-6 fv-row">
																	<select name="writer_ud_half" id="writer_status"
																		class="form-select form-select-solid"
																		placeholder="">
																		<option {{ $order->writer_ud_h == '' ? 'selected' : '' }} value="">Select Status</option>
																		<option {{ $order->writer_ud_h == 'First Half' ? 'selected' : '' }} value="First Half">First Half</option>
																		<option {{ $order->writer_ud_h == 'Second Half' ? 'selected' : '' }} value="Second Half">Second Half</option>
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
												</div>
												</form>
											</div>
										</div>
									</div>

									<!-- <td class="text-center">
											<a href="/edit.{{$order->id}}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
												<span class="svg-icon svg-icon-3">
													<li class="fa fa-edit"></li>
												</span>
											</a>
										</td> -->
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


@endsection