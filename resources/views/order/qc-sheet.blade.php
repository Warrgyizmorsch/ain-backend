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
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Qc-Sheets
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need</small>
                    </div>
                    
                </div>
	        </div>

			<div class="col-xl-12">
            <div class="card card-xxl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Filter</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <form action="{{ route('Qc-Sheets') }}" method="GET">
                        <div class="row mb-3">
                            <div class="col-md-3 fv-row">
                                <input type="search"   name="search" id="search" class="form-control form-control-solid" placeholder="Serach By OrderCode " >
                            </div>
                            <!-- <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select name="writer" id="writer"     data-placeholder="Search By writer Name " class="form-select form-select-solid form-select-lg " >
                                    <option value="" data-select2-id="select2-data-18-e9lhs"></option>
                                        @foreach($data['writer'] as $writer)
                                     <option value="{{$writer->id}}">{{$writer->name}}</option>
                                    @endforeach
                                </select>
                            </div> -->
                            <!-- -------------- -->
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select name="writer" id="writer" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By writer Name" class="form-select form-select-solid form-select-lg" tabindex="-1">
                                    <option value=""></option>
                                    @foreach($data['writer'] as $tl)
                                        <option value="{{ $tl->id }}">{{ $tl->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select name="SubWriter" id="SubWriter" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By Sub Writer" class="form-select form-select-solid form-select-lg" tabindex="-1">
                                    <option value=""></option>
                                    @foreach($data['SubWriter'] as $Sub)
                                        <option value="">{{ $Sub->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                             

                            

                           <!-- ----------------------- -->
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="qc_standard" id="qc_standard"aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                        <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                        <option value="poor" >poor</option>
                                        <option value="moderate" >moderate</option>
                                        <option value="Good">Good</option>
                                    </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                           
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select  name="status" id="status"  aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" >
                                        <option value="" >Select Status</option>
                                        <option value="Not Assigned" >Not Assigned</option>
                                        <option value="In Progress" >In Progress</option>
                                        <option value="Draft Ready" >Draft Ready</option>
                                        <option value="Draft Feedback" >Draft Feedback</option>
                                        <option value="Attached to Email (Draft) " >Attached to Email (Draft) </option>
                                        <option value="Draft Delivered">Draft Delivered</option>
                                        <option value="Complete file Ready">Complete file Ready</option>
                                        <option value="Feedback">Feedback</option>
                                        <option value="Feedback Delivered">Feedback Delivered</option>
                                        <option value="Attached to Email (Complete file) ">Attached to Email (Complete file) </option>
                                        <option value="Delivered" >Delivered</option>
                                        <option value="Hold">Hold</option>
                                </select>
                            </div>
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="edited_on" id="edited_on"aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                       
                                        <option value="Order-date" >deadline</option>
                                        <option value="Qc-date" >Qc date</option>
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                           
                            <div class="col-md-3 fv-row">
                                <input type="date" name="fromDate" id="fromDate" class="form-control form-control-solid" placeholder="Search By OrderCode">
                            </div>
                            <div class="col-md-3 fv-row">
                                <input type="date" name="toDate" id="toDate" class="form-control form-control-solid" placeholder="Search By OrderCode">
                            </div>
                            
                            @if(Auth::user()->role_id ==1)
                            
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select name="OldSubWriter"  aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By OLD Sub writer Name" class="form-select form-select-solid form-select-lg" tabindex="-1">
                                    <option value=""></option>
                                    @foreach($data['SubWriter'] as $Sub)
                                        <option value="{{ $Sub->id }}">{{ $Sub->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            
                               

                            
                            <div class="col-md-3 fv-row fv-plugins-icon-container">
                                <!-- <a  id="resetFiltersBtn" class="btn btn-sm btn-danger">Reset</a>
                               <a  onclick="applyFilters()" class="btn btn-sm btn-primary">Search</a> -->
                                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                <a id="resetFiltersBtn" class="btn btn-sm btn-danger" href="{{ route('Qc-Sheets') }}">Reset</a>
                                
                            </div>
                        </div>
                    </form>
                </div>
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
                                    @if( Auth::user()->role_id == 3 || Auth::user()->role_id == 8)
										<th class="min-w-20px text-center">Checked</th>
                                    @endif
										<th class="min-w-20px text-center">SR</th>
										<th class="min-w-50px">Order Code</th>
										<th class="min-w-30px">D Date</th>
										<th class="min-w-40px">Status</th>
										<th class="min-w-40px">Quality standard</th>
										<th class="min-w-40px" >Ai Score</th>
										<th class="min-w-40px">Writer </th>
										<th class="min-w-150px text-center" >Comment</th>
										<th class="min-w-150px text-center" >Action</th>
									</tr>
								</thead>
                                <div id="loadind" style="display:none"></div>
								<tbody style="display:none" id="content" class="searchData">
							
								</tbody>
								<tbody class="allData">
                                    @foreach($data['orders'] as $order)
                                        <tr>
                                        @if( Auth::user()->role_id == 3 || Auth::user()->role_id == 8)
                                            <td>
                                            

                                            @if($order->qc_checked == 1 )
                                            <div class="form-check form-check-sm form-check-custom form-check-solid m-5">
                                                <input onchange="toggleCheck(this, {{ $order->id }})" class="form-check-input widget-13-check" type="checkbox" checked value="1">
                                            </div>

                                            @else
                                            <div class="form-check form-check-sm form-check-custom form-check-solid m-5">
                                                <input onchange="toggleCheck(this, {{ $order->id }})" class="form-check-input widget-13-check" type="checkbox"  value="1">
                                            </div>
                                            @endif

                                            <script>
                                                function toggleCheck(checkbox, leadId) {
                                                    // Toggle checkbox state
                                                    checkbox.checked ? uncheckLead(leadId) : checkLead(leadId);
                                                }

                                                function uncheckLead(leadId) {
                                                    $.ajax({
                                                        url: 'qcchecked/' + leadId,
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                        },
                                                        success: function(response) {
                                                            console.log("Lead with ID " + leadId + " unchecked successfully.");
                                                            // Reload the page
                                                            location.reload();
                                                        },
                                                        error: function(xhr, status, error) {
                                                            console.error("Error: " + error);
                                                        }
                                                    });
                                                }
                                            </script>


                                            </td>
                                        @endif
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>
                                            {{ $order->order_id }}
                                            <span class="badge badge-light-info fs-7 fw-bold" >  {{ $order->qc_admin }}</span>

                                            </td>
                                            <td>
                                            {{ \Carbon\Carbon::parse($order->writer_deadline)->format('d M ') }}
                                            <!-- <span class="badge badge-light-danger fs-7 fw-bold" >  {{ \Carbon\Carbon::parse($order->qc_date)->format('d M ') }}</span> -->
                                                @if($order->qc_date != null)
                                                    <span class="badge badge-light-danger fs-7 fw-bold">{{ \Carbon\Carbon::parse($order->qc_date)->format('d M ') }}</span>
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
                                            
                                           
                                            <td>
                                                @if($order->qc_standard == 'poor')
                                                    <span class="badge badge-light-danger fs-7 fw-bold" style="background:#795548; color:white"> {{ $order->qc_standard }}</span>
                                                @elseif($order->qc_standard == 'Good')
                                                    <span class="badge badge-light-danger fs-7 fw-bold" style="background:#eaea00; color:black"> {{ $order->qc_standard }}</span>
                                                @elseif($order->qc_standard == 'moderate')
                                                    <span class="badge badge-light-danger fs-7 fw-bold" style="background:#4caf50; color:white"> {{ $order->qc_standard }}</span>
                                                @endif
                                            </td>
                                            <td id="ai-score" class="editable " data-order-id="{{ $order->id }}">
                                                @if($order->ai_score != null)
                                                    {{ $order->ai_score }} %
                                                @endif
                                            </td>

                                            <td>
                                            @if( $order['writer'] && !$order['writer']['name'] == "")
											@if ($order->mulsubwriter)
												{{-- Output email addresses --}}
												@foreach ($order->mulsubwriter as $writer)
												@if($writer->user)
													{{ $writer->user->name }} <br>
												@endif
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
                                                
                                                @if($order->subwriter && Auth::user()->role_id==1)
                                                    
                                            
                                                    <span class="badge badge-light-danger fs-7 fw-bold">
                                                      Old Sub Writer :  {{ $order->subwriter->name }}
                                                    </span>
                                                    
                                                    @endif
                                            </td>
                                            <td>{{ $order->qc_comment }}</td>
                                            <td class="text-center"> 
                                                <div class="card-toolbar">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_create_appaa_newLeads{{$order->id}}" id="kt_toolbar_primary_button" class="btn btn-sm btn-light-primary">
                                                        
                                                        <li class="fa fa-edit"> </li>
                                                    </a>
                                                </div>

                                                <div class="modal fade" id="kt_modal_create_appaa_newLeads{{$order->id}}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered mw-950px">
                                                        <div class="modal-content rounded">
                                                            <div class="modal-header pb-0 border-0 justify-content-end">
                                                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                                    <span class="svg-icon svg-icon-1">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                                                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                                                                        </svg>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                                     <form id="kt_modal_new_target_form" class="form" method="POST" action="{{ route('qc.update', ['id' =>  $order->id]) }}">
                                                                    @csrf
                                                                    <div class="mb-13 text-center">
                                                                        <h1 class="mb-3">Qc ORDER EDIT</h1>
                                                                        <div class="text-muted fw-bold fs-5">{{$order->order_id}}</div>
                                                                    </div>
                                                                    
                                                                    <div class="row g-9 mb-8 text-start">
                                                                        <div class="col-md-6 fv-row">
                                                                            <label class="fs-6 fw-bold mb-2">Qc Status</label>
                                                                            <select required="" name="qc_status" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                                                                <option value="" {{ old('writer_status', $order->writer_status) == '' ? 'selected' : '' }} data-select2-id="select2-data-18-e9lh12"></option>
                                                                                @if(auth()->user()->role_id == 1)
                                                                                    <option value="Not Assigned" {{ old('writer_status', $order->writer_status) == 'Not Assigned' ? 'selected' : '' }}>Not Assigned</option>
                                                                                    <option value="In Progress" {{ old('writer_status', $order->writer_status) == 'In progress' || old('writer_status', $order->writer_status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                                                    <option value="Draft Ready" {{ old('writer_status', $order->writer_status) == 'Draft Ready' ? 'selected' : '' }}>Draft Ready</option>
                                                                                    <option value="Draft Feedback" {{ old('writer_status', $order->writer_status) == 'Draft Feedback' ? 'selected' : '' }}>Draft Feedback</option>
                                                                                    <option value="Attached to Email (Draft) " {{ old('writer_status', $order->writer_status) == 'Attached to Email (Draft) ' ? 'selected' : '' }}>Attached to Email (Draft) </option>
                                                                                    <option value="Draft Delivered" {{ old('writer_status', $order->writer_status) == 'Draft Delivered' ? 'selected' : '' }}>Draft Delivered</option>
                                                                                    <option value="Complete file Ready" {{ old('writer_status', $order->writer_status) == 'Complete file Ready' ? 'selected' : '' }}>Complete file Ready</option>
                                                                                    <option value="Feedback" {{ old('writer_status', $order->writer_status) == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                                                                    <option value="Feedback Delivered" {{ old('writer_status', $order->writer_status) == 'Feedback Delivered' ? 'selected' : '' }}>Feedback Delivered</option>
                                                                                    <option value="Attached to Email (Complete file) " {{ old('writer_status', $order->writer_status) == 'Attached to Email (Complete file)' ? 'selected' : '' }}>Attached to Email (Complete file) </option>
                                                                                    <option value="Delivered" {{ old('writer_status', $order->writer_status) == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                                                                    <option value="Hold" {{ old('writer_status', $order->writer_status) == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                                                @endif
                                                                                @if(auth()->user()->role_id == 3)
                                                                                    <option value="Draft Feedback" {{ old('writer_status', $order->writer_status) == 'Draft Feedback' ? 'selected' : '' }}>Draft Feedback</option>
                                                                                    <option value="Attached to Email (Draft) " {{ old('writer_status', $order->writer_status) == 'Attached to Email (Draft) ' ? 'selected' : '' }}>Attached to Email (Draft) </option>
                                                                                    <option value="Feedback" {{ old('writer_status', $order->writer_status) == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                                                                    <option value="Attached to Email (Complete file) " {{ old('writer_status', $order->writer_status) == 'Attached to Email (Complete file) ' ? 'selected' : '' }}>Attached to Email (Complete file) </option>
                                                                                @endif
                                                                                @if(auth()->user()->role_id == 8)
                                                                                    <option value="Not Assigned" {{ old('writer_status', $order->writer_status) == 'Not Assigned' ? 'selected' : '' }}>Not Assigned</option>
                                                                                    <option value="In Progress" {{ old('writer_status', $order->writer_status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                                                    <option value="Draft Ready" {{ old('writer_status', $order->writer_status) == 'Draft Ready' ? 'selected' : '' }}>Draft Ready</option>
                                                                                    <option value="Draft Delivered" {{ old('writer_status', $order->writer_status) == 'Draft Delivered' ? 'selected' : '' }}>Draft Delivered</option>
                                                                                    <option value="Complete file Ready" {{ old('writer_status', $order->writer_status) == 'Complete file Ready' ? 'selected' : '' }}>Complete file Ready</option>
                                                                                    <option value="Feedback" {{ old('writer_status', $order->writer_status) == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                                                                    <option value="Feedback Delivered" {{ old('writer_status', $order->writer_status) == 'Feedback Delivered' ? 'selected' : '' }}>Feedback Delivered</option>
                                                                                    <option value="Delivered" {{ old('writer_status', $order->writer_status) == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                                                                    <option value="Hold" {{ old('writer_status', $order->writer_status) == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                                                @endif
                                                                                @if(auth()->user()->role_id == 6)
                                                                                    <option value="Draft Ready" {{ old('writer_status', $order->writer_status) == 'Draft Ready' ? 'selected' : '' }}>Draft Ready</option>
                                                                                    <option value="Complete file Ready" {{ old('writer_status', $order->writer_status) == 'Complete file Ready' ? 'selected' : '' }}>Complete file Ready</option>
                                                                                @endif
                                                                            </select>

                                                                            
                                                                        </div>
                                                                        <div class="col-md-6 fv-row">
                                                                            <label class="fs-6 fw-bold mb-2">Qc standard</label>
                                                                                <select required="" name="qc_standard" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                                                                    <option value="" {{ old('qc_standard', $order->qc_standard) == '' ? 'selected' : '' }} data-select2-id="select2-data-18-e9lh12"></option>
                                                                                    <option value="poor" {{ old('qc_standard', $order->qc_standard) == 'poor' ? 'selected' : '' }}>poor</option>
                                                                                    <option value="moderate" {{ old('qc_standard', $order->qc_standard) == 'moderate' ? 'selected' : '' }}>moderate</option>
                                                                                    <option value="Good" {{ old('qc_standard', $order->qc_standard) == 'Good' ? 'selected' : '' }}>Good</option>
                                                                                </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row g-9 mb-8 text-start">
                                                                        <div class="col-md-6 fv-row">
                                                                           @if(Auth::user()->role_id == 1)
                                                                            <div class="col-md-12 fv-row">
                                                                                <label class="fs-6 fw-bold mb-2">Writer Date</label>
                                                                                <input type="date" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->writer_deadline)->format('Y-m-d') }}" name="writer_date" onchange="showSelectedDate(this)">
                                                                            </div>
                                                                            @else
                                                                             <input type="hidden" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->writer_deadline)->format('Y-m-d') }}" name="writer_date" onchange="showSelectedDate(this)">
                                                                            @endif
                                                                        </div>
                                                                        
                                                                        
                                                                    </div>

                                                                    

                                                                   
                                                                    <div class="row g-9 mb-8 text-start">
                                                                        <div class="col-md-12 fv-row">
                                                                            <label class="fs-6 fw-bold mb-2">Comment</label>
                                                                            <textarea required="" name="comment" value="" class="form-control form-control-solid" id="" cols="30" rows="3">{{$order->qc_comment}}</textarea>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

									
								</tbody>
							</table>
                            {{ $data['orders']->links() }}
						</div>
					</div>

				</div>
			</div>

        </div>
    </div>
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .loading-container {
            position: relative;
            height: 100%; /* Adjust this value according to your layout */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error {
            color: red;
        }

        /* Additional styling can be added here */
    </style>
   <!-- <script>
    // Function to apply filters
    function applyFilters() {
        var $search = $('#search').val();
        var $status = $('#status').val();
        var $writer = $('#writer').val();
        var $SubWriter = $('#SubWriter').val();
        var $dateStatus = $('#date_status').val();
        var $fromDate = $('#fromDate').val();
        var $toDate = $('#toDate').val();
        var $admin = $('#admin').val();
        var $qc_standard = $('#qc_standard').val();
        var $college = $('#college').val();
        var $extra = $('#extra').val();
        var $secondaryMobile = $('#secondary_mobile').val();
        var $edited_on = $('#edited_on').val(); // Capture edited on value

        // Store filter values in localStorage
        var filters = {
            search: $search,
            status: $status,
            writer: $writer,
            SubWriter: $SubWriter,
            dateStatus: $dateStatus,
            fromDate: $fromDate,
            toDate: $toDate,
            admin: $admin,
            qc_standard: $qc_standard,
            college: $college,
            extra: $extra,
            secondaryMobile: $secondaryMobile,
            edited_on: $edited_on // Include edited on value in filters
        };
        localStorage.setItem('filters', JSON.stringify(filters));

        // Check if any filter is set
        var filtersExist = Object.values(filters).some(value => value);

        if (filtersExist) {
            $('.allData').hide();
            $('.searchData').show();
        } else {
            $('.allData').show();
            $('.searchData').hide();
        }

        // Use CSRF token for security
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        // Show loading while waiting for response
        $('#content').html('<div class="loading-container">Please Wait Data Is Loading......<div class="loading-spinner"></div></div>');

        // AJAX request
        $.ajax({
            type: 'get',
            url: '{{ url('search-qc') }}',
            data: {
                '_token': CSRF_TOKEN,
                ...filters
            },
            success: function (data) {
                console.log(data);
                $('#content').html(data);
            },
            error: function (xhr, status, error) {
                console.log('Error:', error);
            }
        });
    }

    // Function to reset filters
    function resetFilters() {
        localStorage.clear(); // Clear all stored filters

        // Clear input values
        $('input[type=search], input[type=date] , input[type=text] , input[type=hidden]').val('');
        $('select').val('').trigger('change');

        // Apply filters
        applyFilters();
    }

    // Click event for the reset button
    $('#resetFiltersBtn').on('click', function() {
        resetFilters();
    });

    // Load stored filters on page load
    $(document).ready(function() {
        var storedFilters = JSON.parse(localStorage.getItem('filters'));

        if (storedFilters) {
            $.each(storedFilters, function(key, value) {
                $('#' + key).val(value);
            });
        }

        // Apply filters
        applyFilters();
    });
</script> -->




<script>
    $(document).ready(function () {
    // Function to handle both search and filter

    // Function to populate SubWriter dropdown based on the selected Writer TL
    function populateSubwriters() {
        var tlId = $('#writer').val();
        var subwriterSelect = $('#SubWriter');

        // Store the currently selected SubWriter value
        var selectedSubWriter = subwriterSelect.val();

        // Clear previous options
        subwriterSelect.empty();

        // Check if a TL is selected
        if (tlId !== '') {
            // Fetch subwriters based on the selected TL
            $.ajax({
                type: 'get',
                url: '/fetch-subwriters', // Use the correct URL here
                data: {
                    'tlId': tlId
                },
                success: function (data) {
                    // Populate SubWriter dropdown with fetched data
                    $.each(data, function (key, value) {
                        subwriterSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                    // Set the selected option back to its original value
                    subwriterSelect.val(selectedSubWriter);
                },
                error: function (data) {
                    console.log('Error fetching SubWriters:', data);
                }
            });
        } else {
            // If no TL is selected, show all sub-writers
            subwriterSelect.append('<option value=""></option>');
            @foreach($data['SubWriter'] as $Sub)
                subwriterSelect.append('<option value="{{ $Sub->id }}">{{ $Sub->name }}</option>');
            @endforeach
        }
    }

    // Use event delegation for dynamically populated elements
    $(document).on('change', '#writer', populateSubwriters);

    // Populate SubWriter dropdown on page load
    populateSubwriters();
});

</script>

<script>
    $(document).ready(function() {
        $('.editable').click(function() {
            var orderId = $(this).data('order-id');

            Swal.fire({
                title: 'Enter new AI Score',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Ok',
                showLoaderOnConfirm: true,
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('Please enter a value');
                    }
                    return value;
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    var newScore = result.value;
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'POST',
                        url: '/update-ai-score/' + orderId,
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        data: {
                            _token: CSRF_TOKEN,
                            newScore: newScore
                        },
                        success: function(response) {
                            // Update the value in the <td> element
                            $('#ai-score[data-order-id="' + orderId + '"]').text(newScore + ' %');
                            Swal.fire('Updated!', 'AI Score has been updated.', 'success');
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error!', 'Failed to update AI Score.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>

@endsection