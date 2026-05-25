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
				</div>

			</div>
		</div>

		<div class="col-xl-12">
				@include('order.filter.writerTl_filter')
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
									<!-- <th>Writer Team Leader</th> -->
									<th>Writer</th>
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

									<td>{{$order->pages}}</td>
									<!-- <td>{{ $order['subwriter']['name'] ?? '' }}</td> -->
								<td>
										@if( $order['writer'] && !$order['writer']['name'] == "")
											@if ($order->mulsubwriter->isEmpty())
											    @if($order['subwriter'])
												{{$order['subwriter']['name']}}
												@endif
											@else
												{{-- Output email addresses --}}
												@foreach ($order->mulsubwriter as $writer)
													{{ $writer->user->name }} <br>
												@endforeach
											@endif
											<br>
											<span class="badge badge-light-info fs-7 fw-bold">({{ $order['writer']['name'] }})</span>
										@else
											Not Assign
										@endif
									</td>






									<td class="text-center">
										<a href="edit.{{ $order->id }}" 
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
																	<select name="subwriterSelect[]" id="subwriterSelect{{ $order->order_id }}" class="form-select form-select-solid" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true"  multiple="multiple">
																			<option value="">Select Writer</option>
																			@foreach($data['SubWriter'] as $SubWriter)
                                                                                @if($SubWriter->tl_id == Auth::user()->id)
																				<option {{ $order->swid == $SubWriter->id ? 'selected' : '' }} value="{{ $SubWriter->id }}">{{ $SubWriter->name }}</option>
                                                                                @endif
                                                                            @endforeach
																		</select>
																	</div>


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
						<div class="m-4">
							{{$data['orders']->links('pagination::bootstrap-4')}}
						</div>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>
<style>

</style>


@endsection