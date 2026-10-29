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
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="confirmationModalLabel">Update Order
					</h5>
					<a href="/edit.{{$order->id}}" class=""
						style="margin-left: 10px;">({{ $order->order_id }})</a>
				</div>
				<form id="kt_modal_new_target_form" class="form" action="{{ route('orderedit.update', ['id' => $order->id]) }}" method="POST">
					@csrf
				<div class="col-xl-12">
					<div class="card-body" id="kt_drawer_chat_messenger_body">
							<div class="mb-4 p-3 bg-light rounded text-start">
								<div class="text-muted fs-7 mb-1">Assignment Deadline:</div>
								<div class="d-flex align-items-center flex-wrap gap-2">
									<span class="fw-bolder fs-6 text-dark">
										@if(!empty($order->writer_deadline) && $order->writer_deadline != '0000-00-00')
											{{ \Carbon\Carbon::parse($order->writer_deadline)->format('d M Y') }}
											@if(!empty($order->writer_deadline_time) && $order->writer_deadline_time != '00:00:00')
												<span class="badge badge-light-danger fs-7 fw-bold ms-1">{{ date('g:i A', strtotime($order->writer_deadline_time)) }}</span>
											@endif
										@else
											<span class="text-muted">Not Set</span>
										@endif
									</span>
									@if($order->draftrequired == 'Y' && !empty($order->draft_date) && $order->draft_date != '0000-00-00')
										<span class="badge badge-light-info fs-7 fw-bold">
											Draft: {{ \Carbon\Carbon::parse($order->draft_date)->format('d M Y') }}
											@if(!empty($order->draft_time) && $order->draft_time != '00:00:00')
												{{ date('g:i A', strtotime($order->draft_time)) }}
											@endif
										</span>
									@endif
								</div>
							</div>
							<div class="row g-9 mb-8 text-start">
							<div class="col-md-12 fv-row" id="subwriterContainer">
								<select name="subwriterSelect[]" id="subwriterSelect" class="form-select form-select-solid" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true" multiple="multiple">
									<option value="">Select Subwriter</option>
									<!-- Options will be dynamically populated using JavaScript -->
								</select>
							</div>

							@foreach($data['ordersub'] as $subswriter)
								<input type="hidden" value="{{$subswriter->user_id}}" class="swid">
							@endforeach
							<input type="hidden" id="tlSelect" value="{{auth()->user()->id}}" class="swid">

							<script>
								function populateSubwriters() {
									var tlId = document.getElementById('tlSelect').value;
									var subwriterSelect = document.getElementById('subwriterSelect');
									var selectedSubwriters = document.querySelectorAll('.swid');
									var selectedSubwriterIds = [];
									selectedSubwriters.forEach(function(element) {
										selectedSubwriterIds.push(element.value);
									});
									subwriterSelect.innerHTML = '<option value="">Select Subwriter</option>';
									if (tlId !== '') {
										@foreach($data['SubWriter'] as $subWriter)
											if ("{{$subWriter->tl_id}}" === tlId) {
												var option = document.createElement('option');
												option.value = "{{$subWriter->id}}";
												option.text = "{{$subWriter->name}}";
												if (selectedSubwriterIds.includes("{{$subWriter->id}}")) {
													option.selected = true;
												}
												subwriterSelect.appendChild(option);
											}
										@endforeach
									}
								}
								document.getElementById('tlSelect').addEventListener('change', populateSubwriters);
								populateSubwriters();
							</script>



								<div class="col-md-12 fv-row">
									<select name="status" id="subwriter" class="form-select form-select-solid" placeholder="">
									   	@if( $order->writer_status != 'Draft Ready' || $order->writer_status == 'Complete file Ready' 	)
											<option value="Not Assigned" {{ $order->writer_status == $order->writer_status ? 'selected' : '' }}>{{$order->writer_status}}</option>
										@endif
										<option {{ $order->writer_status == '' ? 'selected' : '' }} value="">Not Assign</option>
									
										
										<option value="Draft Ready" {{ $order->writer_status == 'Draft Ready' ? 'selected' : '' }}>Draft Ready</option>
                                        <option value="Complete file Ready"{{ $order->writer_status == 'Complete file Ready' ? 'selected' : '' }}>Complete file Ready</option>
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
</div>

@endsection