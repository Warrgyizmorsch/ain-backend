@extends('layouts.app')

@section('content')
<div class="min-vh-100">
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Lead Edit
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need</small>
            </div>
            <div class="d-flex align-items-center py-1">
                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app" id="kt_toolbar_primary_button">Create</a>
            </div>
        </div>
    </div>
    <div id="kt_content_container" class="container-xxl">
        <div class="modal-content rounded">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                        </svg> -->
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
                        <select name="qc_status" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                       
                            <option value="" {{ old('qc_status', $order->qc_status) == '' ? 'selected' : '' }} data-select2-id="select2-data-18-e9lh12"></option>
                        @if(auth()->user()->role_id == 1)
                            <option value="Not Assigned" {{ old('qc_status', $order->qc_status) == 'Not Assigned' ? 'selected' : '' }}>Not Assigned</option>
                            <option value="In Progress" {{ old('qc_status', $order->qc_status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Draft Ready" {{ old('qc_status', $order->qc_status) == 'Draft Ready' ? 'selected' : '' }}>Draft Ready</option>
                            <option value="Draft Feedback" {{ old('qc_status', $order->qc_status) == 'Draft Feedback' ? 'selected' : '' }}>Draft Feedback</option>
                            <option value="Attached to Email (Draft) " {{ old('qc_status', $order->qc_status) == 'Attached to Email (Draft) ' ? 'selected' : '' }}>Attached to Email (Draft) </option>
                            <option value="Draft Delivered" {{ old('qc_status', $order->qc_status) == 'Draft Delivered' ? 'selected' : '' }}>Draft Delivered</option>
                            <option value="Complete file Ready" {{ old('qc_status', $order->qc_status) == 'Complete file Ready' ? 'selected' : '' }}>Complete file Ready</option>
                            <option value="Feedback" {{ old('qc_status', $order->qc_status) == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                            <option value="Attached to Email (Complete file) " {{ old('qc_status', $order->qc_status) == 'Attached to Email (Complete file) ' ? 'selected' : '' }}>Attached to Email (Complete file) </option>
                            <option value="Delivered" {{ old('qc_status', $order->qc_status) == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="Hold" {{ old('qc_status', $order->qc_status) == 'Hold' ? 'selected' : '' }}>Hold</option>
                        @endif
                        @if(auth()->user()->role_id == 3)
                            <option value="Draft Feedback" {{ old('qc_status', $order->qc_status) == 'Draft Feedback' ? 'selected' : '' }}>Draft Feedback</option>
                            <option value="Attached to Email (Draft) " {{ old('qc_status', $order->qc_status) == 'Attached to Email (Draft) ' ? 'selected' : '' }}>Attached to Email (Draft) </option>
                            <option value="Feedback" {{ old('qc_status', $order->qc_status) == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                            <option value="Attached to Email (Complete file) " {{ old('qc_status', $order->qc_status) == 'Attached to Email (Complete file) ' ? 'selected' : '' }}>Attached to Email (Complete file) </option>
                        @endif
                        @if(auth()->user()->role_id == 8)
                            <option value="Not Assigned" {{ old('qc_status', $order->qc_status) == 'Not Assigned' ? 'selected' : '' }}>Not Assigned</option>
                            <option value="In Progress" {{ old('qc_status', $order->qc_status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Draft Ready" {{ old('qc_status', $order->qc_status) == 'Draft Ready' ? 'selected' : '' }}>Draft Ready</option>
                            <option value="Draft Delivered" {{ old('qc_status', $order->qc_status) == 'Draft Delivered' ? 'selected' : '' }}>Draft Delivered</option>
                            <option value="Complete file Ready" {{ old('qc_status', $order->qc_status) == 'Complete file Ready' ? 'selected' : '' }}>Complete file Ready</option>
                            <option value="Feedback" {{ old('qc_status', $order->qc_status) == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                            <option value="Delivered" {{ old('qc_status', $order->qc_status) == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="Hold" {{ old('qc_status', $order->qc_status) == 'Hold' ? 'selected' : '' }}>Hold</option>
                        @endif
                        @if(auth()->user()->role_id == 6)
                            <option value="Draft Ready" {{ old('qc_status', $order->qc_status) == 'Draft Ready' ? 'selected' : '' }}>Draft Ready</option>
                            <option value="Complete file Ready" {{ old('qc_status', $order->qc_status) == 'Complete file Ready' ? 'selected' : '' }}>Complete file Ready</option>
                        @endif
                        </select>
                        
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-bold mb-2">Qc standard</label>
                            <select name="qc_standard" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
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
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-bold mb-2">Writer Date</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->writer_deadline)->format('Y-m-d') }}" name="writer_date" onchange="showSelectedDate(this)">
                        </div>
                        @else
                         <input type="hidden" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->writer_deadline)->format('Y-m-d') }}" name="writer_date" onchange="showSelectedDate(this)">
                        @endif
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-bold mb-2">Ai Score (%)</label>
                        <input name="ai_score" value="{{$order->ai_score}}" type="number" class="form-control form-control-solid">
                  
                    </div>
                    <div class="row g-9 mb-8 text-start">
                      
                        
                    </div>
            
               
                <div class="row g-9 mb-8 text-start">
                    <div class="col-md-12 fv-row">
                        <label class="fs-6 fw-bold mb-2">Comment</label>
                        <textarea name="comment" value="" class="form-control form-control-solid" id="" cols="30" rows="3">{{$order->qc_comment}}</textarea>
                    </div>
                    
                </div>
            </div>
                                                                
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
            
</div>
@endsection