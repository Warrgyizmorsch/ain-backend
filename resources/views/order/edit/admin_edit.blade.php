@extends('layouts.app')
@section('content')
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

		

		<div class="card card-xl-stretch  mb-xl-">
			
			<div class="card-body py-3">

							<div class="modal-header">
									<h5 class="modal-title" id="confirmationModalLabel">Update Order</h5>
									<a href="/edit.{{$order->id}}" class="" style="margin-left: 10px;">({{ $order->order_id }})</a>
							</div>
							<form id="kt_modal_new_target_form" class="form" action="{{ route('orderedit.update', ['id' => $order->id]) }}" method="POST">
									@csrf
								<div class="col-xl-12">
									<div class="card-body" id="kt_drawer_chat_messenger_body">
										<div class="scroll-y me-n5 pe-5" data-kt-element="messages"
											>
											<div class="row g-9 mb-8 text-start">
												<!-- Add an onchange event to the TL select box to trigger the JavaScript function -->
												<div class="col-md-12 fv-row">
													<select name="tlSelect" id="tlSelect" class="form-select form-select-solid" placeholder="">
														<option value="">Select Writer</option>
														<option {{ $order->wid == Auth::user()->id ? 'selected' : '' }} value="{{Auth::user()->id}}">My Writer</option>
														@foreach($data['writerTL'] as $tl)
															<option {{ $order->wid == $tl->id ? 'selected' : '' }} value="{{ $tl->id }}">{{ $tl->name }}</option>
														@endforeach
													</select>
												</div>
												<div class="col-md-12 fv-row" id="subwriterContainer">
													<select name="subwriterSelect[]" id="subwriterSelect" class="form-select form-select-solid" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true" multiple="multiple">
														<option value="">Select Subwriter</option>
														<!-- Options will be dynamically populated using JavaScript -->
													</select>
												</div>

													@foreach($data['ordersub'] as $subswriter)
														<input type="hidden" value="{{$subswriter->user_id}}" class="swid">
													@endforeach

													<script>
														// Function to fetch and populate subwriters based on the selected TL
														function populateSubwriters() {
															var tlId = document.getElementById('tlSelect').value;
															var subwriterSelect = document.getElementById('subwriterSelect');
															var selectedSubwriters = document.querySelectorAll('.swid');
															var selectedSubwriterIds = [];
															
															// Collect IDs of selected subwriters
															selectedSubwriters.forEach(function(element) {
																selectedSubwriterIds.push(element.value);
															});

															// Clear previous options
															subwriterSelect.innerHTML = '<option value="">Select Subwriter</option>';
															// Check if a TL is selected
															if (tlId !== '') {
																// Fetch subwriters based on the selected TL
																@foreach($data['SubWriter'] as $subWriter)
																	// Check if the subwriter has the same TL id
																	if ("{{$subWriter->tl_id}}" === tlId) {
																		var option = document.createElement('option');
																		option.value = "{{$subWriter->id}}";
																		option.text = "{{$subWriter->name}}";
																		// Pre-select if the subwriter is in the list of selected subwriters
																		if (selectedSubwriterIds.includes("{{$subWriter->id}}")) {
																			option.selected = true;
																		}
																		subwriterSelect.appendChild(option);
																	}
																@endforeach
															}
														}
														// Attach the function to the onchange event of the TL select box
														document.getElementById('tlSelect').addEventListener('change', populateSubwriters);
														// Initial population in case there's a TL selected on page load
														populateSubwriters();
													</script>


												<div class="col-md-12 fv-row">
													<select name="status" id="subwriter" class="form-select form-select-solid" placeholder="">
													        @if( $order->writer_status == 'Attached to Email (Draft)' || $order->writer_status == 'Feedback Delivered' || $order->writer_status == 'Attached to Email (Complete file)'	)
													        	<option value="Not Assigned" {{ $order->writer_status == $order->writer_status ? 'selected' : '' }}>{{$order->writer_status}}</option>

													        @endif
													
														    <option value="Not Assigned" {{ $order->writer_status == 'Not Assigned' ? 'selected' : '' }}>Not Assigned</option>
                                                            <option value="In Progress" {{ $order->writer_status == 'In progress' ||  $order->writer_status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                            <option value="Draft Ready" {{ $order->writer_status == 'Draft Ready' ? 'selected' : '' }}>Draft Ready</option>
                                                            <option value="Draft Delivered"{{ $order->writer_status == 'Draft Delivered' ? 'selected' : '' }}>Draft Delivered</option>
                                                            <option value="Complete file Ready" {{ $order->writer_status == 'Complete file Ready' ? 'selected' : '' }}>Complete file Ready</option>
                                                            <option value="Feedback"{{ $order->writer_status == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                                            <option value="Delivered" {{ $order->writer_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                                            <option value="Hold" {{ $order->writer_status == 'Hold' ? 'selected' : '' }}>Hold</option>
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
													<!-- <select name="writer_fd_half" id=""
														class="form-select form-select-solid"
														placeholder="">
														<option {{ $order->writer_fd_h == '' ? 'selected' : '' }} value="">Select Status</option>
														<option {{ $order->writer_fd_h == 'First Half' ? 'selected' : '' }} value="First Half">First Half</option>
														<option {{ $order->writer_fd_h == 'Second Half' ? 'selected' : '' }} value="Second Half">Second Half</option>
													</select> -->
													<input type="time" name="writer_fd_half" id="" value="{{ $order->writer_fd_h }}" class="form-control form-control-solid">
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
													<!-- <select name="writer_ud_half" id="writer_status"
														class="form-select form-select-solid"
														placeholder="">
														<option {{ $order->writer_ud_h == '' ? 'selected' : '' }} value="">Select Status</option>
														<option {{ $order->writer_ud_h == 'First Half' ? 'selected' : '' }} value="First Half">First Half</option>
														<option {{ $order->writer_ud_h == 'Second Half' ? 'selected' : '' }} value="Second Half">Second Half</option>
													</select> -->
													<input type="time" name="writer_ud_half" id="" value="{{ $order->writer_ud_h }}" class="form-control form-control-solid">
												</div>
											</div>
										<div class="card-footer">
										<button  type="submit" class="btn btn-lg btn-primary fw-bolder">
											Submit
										</button>
									</div>
								</div>
					        </form>
					    </div>
			</div>
        </div>
        </div>
    </div>
</div>

@endsection