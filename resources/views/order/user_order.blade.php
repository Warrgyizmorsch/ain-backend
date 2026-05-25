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
    <div class="toolbar" id="kt_toolbar">
		<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
			<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
				<h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Orders
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Assingment In Need</small>
                </h1>
			</div>
			<div class="d-flex align-items-center py-1">
				<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app" id="kt_toolbar_primary_button">New Order</a>
			</div>
		</div>
	</div>
    <!-- New Order modal content start here -->
    <div class="modal fade" id="kt_modal_create_app" tabindex="-1" role="dialog" aria-labelledby="kt_modal_create_app_label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kt_modal_create_app_label">Create New Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/neworder" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="project_title" class="form-label">Project Title</label>
                            <input type="text" class="form-control" id="project_title" name="project_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="delivery_date" class="form-label">Delivery Date</label>
                            <input type="date" class="form-control" id="delivery_date" name="delivery_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- New Order modal content end here -->
    <div id="kt_content_container" class="container-xxl">
        <div class="card">
            <div class="p-10">
                <div class="col-xl-12">
                    <div class="card card-xl-stretch mb-5 mb-xl-12">
                        @if (count($data['orders']) > 0)
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Orders</span>
                            </h3>
                        </div>
                        <hr>
                        <div class="card-body py-3">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bolder text-muted">
                                            <th class="min-w-150px">Order Id /Delivery Date/ Title</th>
                                            <th class="min-w-140px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $data['leads'] as $leads)
                                        <td>
                                            <a href="#"
                                                class="text-dark fw-bolder text-hover-primary d-block fs-6 d-flex">
                                                {{$leads->order_id}}
                                                <span class="badge badge-light-primary fs-7 fw-bold "
                                                    style="background:#44f2e4; color:black">Not Confirm Order</span>
                                            </a>
                                            <b><span style="color: blue" class="">{{ \Carbon\Carbon::parse($leads->crete_at)->format('Y-m-d') }}"</span></b>
                                            <span
                                                class="text-muted fw-bold text-muted d-block fs-7">{{$leads->project_title}}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-shrink-0">
                                                <a href="#"
                                                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 "
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_new_target{{ $leads['id'] }}">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path
                                                                d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                                                fill="black"></path>
                                                            <path opacity="0.3"
                                                                d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                                                fill="black"></path>
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="modal fade" id="kt_modal_new_target{{ $leads['id'] }}"
                                                tabindex="-1" aria-hidden="true" style="display: none;">
                                                <!--begin::Modal dialog-->
                                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                                    <!--begin::Modal content-->
                                                    <div class="modal-content rounded">
                                                        <!--begin::Modal header-->
                                                        <div class="modal-header pb-0 border-0 justify-content-end">
                                                            <!--begin::Close-->
                                                            <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                                data-bs-dismiss="modal">
                                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                                <span class="svg-icon svg-icon-1">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                                        <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                                            height="2" rx="1"
                                                                            transform="rotate(-45 6 17.3137)"
                                                                            fill="black"></rect>
                                                                        <rect x="7.41422" y="6" width="16" height="2"
                                                                            rx="1" transform="rotate(45 7.41422 6)"
                                                                            fill="black"></rect>
                                                                    </svg>
                                                                </span>
                                                                <!--end::Svg Icon-->
                                                            </div>
                                                            <!--end::Close-->
                                                        </div>
                                                        <!--begin::Modal header-->
                                                        <!--begin::Modal body-->
                                                        <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                                                            <!--begin:Form-->
                                                            <form id="kt_modal_new_target_form" class="form" action="#">
                                                                <!--begin::Heading-->
                                                                <div class="mb-13 text-center">

                                                                    <!--begin::Title-->
                                                                    <h1 class="mb-3">Order View</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Description-->
                                                                    <div class="text-muted fw-bold fs-5">
                                                                        <a href="#"
                                                                            class="text-dark fw-bolder text-hover-primary d-block fs-6 d-flex justify-content-center align-items-center">
                                                                            {{ $leads['order_id'] }}
                                                                            <span class="badge badge-light-primary fs-7 fw-bold "
                                                                            style="background:#44f2e4; color:black">Other</span>
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Description-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Input group-->
                                                                <div class="d-flex flex-column mb-8 fv-row">
                                                                    <!--end::Label-->
                                                                    <label class=" fs-6 fw-bold mb-2">Title</label>
                                                                    <input type="text" readonly=""
                                                                        class="form-control form-control-solid"
                                                                        placeholder="" value="{{ $leads['project_title'] }}"
                                                                        name="target_title">
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div class="row g-9 mb-8">
                                                                    <!--begin::Col-->

                                                                    <div class="col-md-6 fv-row">
                                                                        <label class=" fs-6 fw-bold mb-2">Order
                                                                            Date</label>
                                                                        <input type="text" readonly=""
                                                                            class="form-control form-control-solid"
                                                                            placeholder=""
                                                                            value="{{ \Carbon\Carbon::parse($leads->crete_at)->format('Y-m-d') }}"
                                                                            name="target_title">

                                                                    </div>
                                                                    <!--end::Col-->
                                                                    <!--begin::Col-->
                                                                    <div class="col-md-6 fv-row">
                                                                        <label class=" fs-6 fw-bold mb-2">Delivery
                                                                            Date</label>
                                                                        <input type="text" readonly=""
                                                                            class="form-control form-control-solid"
                                                                            placeholder=""
                                                                            value="{{ \Carbon\Carbon::parse($leads->deadline)->format('Y-m-d') }}"
                                                                            name="target_title">

                                                                    </div>
                                                                    <!--end::Col-->
                                                                </div>



                                                                <div class="row g-9 mb-8">
                                                                    <!--begin::Col-->
                                                                    <div class="col-md-6 fv-row">
                                                                        <label class=" fs-6 fw-bold mb-2">Pages</label>
                                                                        <input type="text" readonly=""
                                                                            class="form-control form-control-solid"
                                                                            placeholder="" value="{{ $leads->pages }}"
                                                                            name="target_title">

                                                                    </div>
                                                                    <!--end::Col-->
                                                                    <!--begin::Col-->
                                                                    <div class="col-md-6 fv-row">
                                                                        <label class=" fs-6 fw-bold mb-2">Total
                                                                            Amount</label>
                                                                        <input type="text" readonly=""
                                                                            class="form-control form-control-solid"
                                                                            placeholder="" value="{{ $leads->price }}"
                                                                            name="target_title">

                                                                    </div>
                                                                    <!--end::Col-->
                                                                </div>

                                                                <div class="row g-9 mb-8">
                                                                    <!--begin::Col-->
                                                                    <div class="col-md-6 fv-row">
                                                                        <label class=" fs-6 fw-bold mb-2">Paid
                                                                            Amount</label>
                                                                        <input type="text" readonly=""
                                                                            class="form-control form-control-solid"
                                                                            placeholder=""
                                                                            value="00"
                                                                            name="target_title">

                                                                    </div>
                                                                    <!--end::Col-->
                                                                    <!--begin::Col-->
                                                                    <div class="col-md-6 fv-row">
                                                                        <label class=" fs-6 fw-bold mb-2">Due
                                                                            amount</label>
                                                                        <input readonly="" type="text"
                                                                            class="form-control form-control-solid"
                                                                            placeholder=""
                                                                            value=" {{ $leads->price }}"
                                                                            name="target_title">

                                                                    </div>
                                                                    <!--end::Col-->
                                                                </div>


                                                            </form>
                                                            <!--end:Form-->
                                                        </div>
                                                        <!--end::Modal body-->
                                                    </div>
                                                    <!--end::Modal content-->
                                                </div>
                                                <!--end::Modal dialog-->
                                            </div>


                                           
                                        </td>
                                        @endforeach
                                        @foreach ($data['orders'] as $order)
                                        <tr>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bolder text-hover-primary d-block fs-6 d-flex">
                                                    {{ $order['order_id'] }}
                                                    @if ($order->projectstatus == 'Other')
                                                    <span class="badge badge-light-primary fs-7 fw-bold "
                                                        style="background:#44f2e4; color:black">{{ $order->projectstatus
                                                        }}</span>
                                                    @elseif($order->projectstatus == 'Pending')
                                                    <span class="badge badge-light-warning fs-7 fw-bold"
                                                        style="background:pink; color:white">{{ $order->projectstatus
                                                        }}</span>
                                                    @elseif($order->projectstatus == 'In Progress')
                                                    <span class="badge badge-light-info fs-7 fw-bold">{{
                                                        $order->projectstatus }}</span>
                                                    @elseif($order->projectstatus == 'Hold Work')
                                                    <span class="badge badge-light-danger fs-7 fw-bold">{{
                                                        $order->projectstatus }}</span>
                                                    @elseif($order->projectstatus == 'Completed')
                                                    <span class="badge badge-light-warning fs-7 fw-bold"
                                                        style="background:#eaea00; color:black">{{ $order->projectstatus
                                                        }}</span>
                                                    @elseif($order->projectstatus == 'Delivered')
                                                    <span class="badge badge-light-success fs-7 fw-bold"
                                                        style="background:green; color:white">{{ $order->projectstatus
                                                        }}</span>
                                                    @elseif($order->projectstatus == 'Feedback')
                                                    <span class="badge badge-light-primary fs-7 fw-bold"
                                                        style="background:black; color:white">{{ $order->projectstatus
                                                        }}</span>
                                                    @elseif($order->projectstatus == 'Feedback Delivered')
                                                    <span class="badge badge-light-danger fs-7 fw-bold"
                                                        style="background:black; color:white">{{ $order->projectstatus
                                                        }}</span>
                                                    @elseif($order->projectstatus == 'Cancelled')
                                                    <span class="badge badge-light-danger fs-7 fw-bold">{{
                                                        $order->projectstatus }}</span>
                                                    @elseif($order->projectstatus == 'Draft Ready')
                                                    <span class="badge badge-light-primary fs-7 fw-bold"
                                                        style="background:#eaea00; color:black">{{ $order->projectstatus
                                                        }}</span>
                                                    @elseif($order->projectstatus == 'Draft Delivered')
                                                    <span class="badge badge-light-primary fs-7 fw-bold"
                                                        style="background:green; color:white">{{ $order->projectstatus
                                                        }}</span>
                                                    @elseif($order->projectstatus == 'Initiated')
                                                    <span class="badge badge-light-primary fs-7 fw-bold"
                                                        style="background:pink; color:white">{{ $order->projectstatus
                                                        }}</span>
                                                    @endif
                                                </a>
                                                <b><span style="color: blue" class="">{{ $order['delivery_date']
                                                        }}</span></b>
                                                <span class="text-muted fw-bold text-muted d-block fs-7">{{
                                                    $order['title'] }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-shrink-0">
                                                    <a href="#"
                                                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 "
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#kt_modal_new_target{{ $order['order_id'] }}">
                                                        <span class="svg-icon svg-icon-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path
                                                                    d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                                                    fill="black"></path>
                                                                <path opacity="0.3"
                                                                    d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                                                    fill="black"></path>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                                <div class="modal fade" id="kt_modal_new_target{{ $order['order_id'] }}"
                                                    tabindex="-1" aria-hidden="true" style="display: none;">
                                                    <!--begin::Modal dialog-->
                                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                                        <!--begin::Modal content-->
                                                        <div class="modal-content rounded">
                                                            <!--begin::Modal header-->
                                                            <div class="modal-header pb-0 border-0 justify-content-end">
                                                                <!--begin::Close-->
                                                                <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                                    data-bs-dismiss="modal">
                                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                                    <span class="svg-icon svg-icon-1">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24" viewBox="0 0 24 24"
                                                                            fill="none">
                                                                            <rect opacity="0.5" x="6" y="17.3137"
                                                                                width="16" height="2" rx="1"
                                                                                transform="rotate(-45 6 17.3137)"
                                                                                fill="black"></rect>
                                                                            <rect x="7.41422" y="6" width="16"
                                                                                height="2" rx="1"
                                                                                transform="rotate(45 7.41422 6)"
                                                                                fill="black"></rect>
                                                                        </svg>
                                                                    </span>
                                                                    <!--end::Svg Icon-->
                                                                </div>
                                                                <!--end::Close-->
                                                            </div>
                                                            <!--begin::Modal header-->
                                                            <!--begin::Modal body-->
                                                            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                                                                <!--begin:Form-->
                                                                <form id="kt_modal_new_target_form" class="form"
                                                                    action="#">
                                                                    <!--begin::Heading-->
                                                                    <div class="mb-13 text-center">

                                                                        <!--begin::Title-->
                                                                        <h1 class="mb-3">Order View</h1>
                                                                        <!--end::Title-->
                                                                        <!--begin::Description-->
                                                                        <div class="text-muted fw-bold fs-5">
                                                                            <a href="#"
                                                                                class="text-dark fw-bolder text-hover-primary d-block fs-6 d-flex justify-content-center align-items-center">
                                                                                {{ $order['order_id'] }}
                                                                                @if ($order->projectstatus == 'Other')
                                                                                <span
                                                                                    class="badge badge-light-primary fs-7 fw-bold "
                                                                                    style="background:#44f2e4; color:black">{{
                                                                                    $order->projectstatus }}</span>
                                                                                @elseif($order->projectstatus ==
                                                                                'Pending')
                                                                                <span
                                                                                    class="badge badge-light-warning fs-7 fw-bold"
                                                                                    style="background:pink; color:white">{{
                                                                                    $order->projectstatus }}</span>
                                                                                @elseif($order->projectstatus == 'In
                                                                                Progress')
                                                                                <span
                                                                                    class="badge badge-light-info fs-7 fw-bold">{{
                                                                                    $order->projectstatus }}</span>
                                                                                @elseif($order->projectstatus == 'Hold
                                                                                Work')
                                                                                <span
                                                                                    class="badge badge-light-danger fs-7 fw-bold">{{
                                                                                    $order->projectstatus }}</span>
                                                                                @elseif($order->projectstatus ==
                                                                                'Completed')
                                                                                <span
                                                                                    class="badge badge-light-warning fs-7 fw-bold"
                                                                                    style="background:#eaea00; color:black">{{
                                                                                    $order->projectstatus }}</span>
                                                                                @elseif($order->projectstatus ==
                                                                                'Delivered')
                                                                                <span
                                                                                    class="badge badge-light-success fs-7 fw-bold"
                                                                                    style="background:green; color:white">{{
                                                                                    $order->projectstatus }}</span>
                                                                                @elseif($order->projectstatus ==
                                                                                'Feedback')
                                                                                <span
                                                                                    class="badge badge-light-primary fs-7 fw-bold"
                                                                                    style="background:black; color:white">{{
                                                                                    $order->projectstatus }}</span>
                                                                                @elseif($order->projectstatus ==
                                                                                'Feedback Delivered')
                                                                                <span
                                                                                    class="badge badge-light-danger fs-7 fw-bold"
                                                                                    style="background:black; color:white">{{
                                                                                    $order->projectstatus }}</span>
                                                                                @elseif($order->projectstatus ==
                                                                                'Cancelled')
                                                                                <span
                                                                                    class="badge badge-light-danger fs-7 fw-bold">{{
                                                                                    $order->projectstatus }}</span>
                                                                                @elseif($order->projectstatus == 'Draft
                                                                                Ready')
                                                                                <span
                                                                                    class="badge badge-light-primary fs-7 fw-bold"
                                                                                    style="background:#eaea00; color:black">{{
                                                                                    $order->projectstatus }}</span>
                                                                                @elseif($order->projectstatus == 'Draft
                                                                                Delivered')
                                                                                <span
                                                                                    class="badge badge-light-primary fs-7 fw-bold"
                                                                                    style="background:green; color:white">{{
                                                                                    $order->projectstatus }}</span>
                                                                                @elseif($order->projectstatus ==
                                                                                'Initiated')
                                                                                <span
                                                                                    class="badge badge-light-primary fs-7 fw-bold"
                                                                                    style="background:pink; color:white">{{
                                                                                    $order->projectstatus }}</span>
                                                                                @endif
                                                                            </a>
                                                                        </div>
                                                                        <!--end::Description-->
                                                                    </div>
                                                                    <!--end::Heading-->
                                                                    <!--begin::Input group-->
                                                                    <div class="d-flex flex-column mb-8 fv-row">
                                                                        <!--end::Label-->
                                                                        <label class=" fs-6 fw-bold mb-2">Title</label>
                                                                        <input type="text" readonly=""
                                                                            class="form-control form-control-solid"
                                                                            placeholder="" value="{{ $order['title'] }}"
                                                                            name="target_title">
                                                                    </div>
                                                                    <!--end::Input group-->
                                                                    <!--begin::Input group-->
                                                                    <div class="row g-9 mb-8">
                                                                        <!--begin::Col-->

                                                                        <div class="col-md-6 fv-row">
                                                                            <label class=" fs-6 fw-bold mb-2">Order
                                                                                Date</label>
                                                                            <input type="text" readonly=""
                                                                                class="form-control form-control-solid"
                                                                                placeholder=""
                                                                                value="{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}"
                                                                                name="target_title">

                                                                        </div>
                                                                        <!--end::Col-->
                                                                        <!--begin::Col-->
                                                                        <div class="col-md-6 fv-row">
                                                                            <label class=" fs-6 fw-bold mb-2">Delivery
                                                                                Date</label>
                                                                            <input type="text" readonly=""
                                                                                class="form-control form-control-solid"
                                                                                placeholder=""
                                                                                value="{{ \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}"
                                                                                name="target_title">

                                                                        </div>
                                                                        <!--end::Col-->
                                                                    </div>



                                                                    <div class="row g-9 mb-8">
                                                                        <!--begin::Col-->
                                                                        <div class="col-md-6 fv-row">
                                                                            <label
                                                                                class=" fs-6 fw-bold mb-2">Pages</label>
                                                                            <input type="text" readonly=""
                                                                                class="form-control form-control-solid"
                                                                                placeholder=""
                                                                                value="{{ $order->pages }}"
                                                                                name="target_title">

                                                                        </div>
                                                                        <!--end::Col-->
                                                                        <!--begin::Col-->
                                                                        <div class="col-md-6 fv-row">
                                                                            <label class=" fs-6 fw-bold mb-2">Total
                                                                                Amount</label>
                                                                            <input type="text" readonly=""
                                                                                class="form-control form-control-solid"
                                                                                placeholder=""
                                                                                value="{{ $order->amount }}"
                                                                                name="target_title">

                                                                        </div>
                                                                        <!--end::Col-->
                                                                    </div>

                                                                    <div class="row g-9 mb-8">
                                                                        <!--begin::Col-->
                                                                        <div class="col-md-6 fv-row">
                                                                            <label class=" fs-6 fw-bold mb-2">Paid
                                                                                Amount</label>
                                                                            <input type="text" readonly=""
                                                                                class="form-control form-control-solid"
                                                                                placeholder=""
                                                                                value="{{ $order->received_amount }}"
                                                                                name="target_title">

                                                                        </div>
                                                                        <!--end::Col-->
                                                                        <!--begin::Col-->
                                                                        <div class="col-md-6 fv-row">
                                                                            <label class=" fs-6 fw-bold mb-2">Due
                                                                                amount</label>
                                                                            <input readonly="" type="text"
                                                                                class="form-control form-control-solid"
                                                                                placeholder=""
                                                                                value=" {{ $order->amount - $order->received_amount }}"
                                                                                name="target_title">

                                                                        </div>
                                                                        <!--end::Col-->
                                                                    </div>


                                                                </form>
                                                                <!--end:Form-->
                                                            </div>
                                                            <!--end::Modal body-->
                                                        </div>
                                                        <!--end::Modal content-->
                                                    </div>
                                                    <!--end::Modal dialog-->
                                                </div>


                                                <div class="modal fade" id="kt_modal_new_target1UKS33742" tabindex="-1"
                                                    aria-hidden="true">
                                                    <!--begin::Modal dialog-->
                                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                                        <!--begin::Modal content-->
                                                        <div class="modal-content rounded">
                                                            <!--begin::Modal header-->
                                                            <div class="modal-header pb-0 border-0 justify-content-end">
                                                                <!--begin::Close-->
                                                                <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                                    data-bs-dismiss="modal">
                                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                                    <span class="svg-icon svg-icon-1">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24" viewBox="0 0 24 24"
                                                                            fill="none">
                                                                            <rect opacity="0.5" x="6" y="17.3137"
                                                                                width="16" height="2" rx="1"
                                                                                transform="rotate(-45 6 17.3137)"
                                                                                fill="black"></rect>
                                                                            <rect x="7.41422" y="6" width="16"
                                                                                height="2" rx="1"
                                                                                transform="rotate(45 7.41422 6)"
                                                                                fill="black"></rect>
                                                                        </svg>
                                                                    </span>
                                                                    <!--end::Svg Icon-->
                                                                </div>
                                                                <!--end::Close-->
                                                            </div>
                                                            <!--begin::Modal header-->
                                                            <!--begin::Modal body-->
                                                            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                                                                <fieldset class="scheduler-border">
                                                                    <legend class="scheduler-border"> Documents
                                                                        Details
                                                                    </legend>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <label>1</label>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <label class="control-label">Uploaded
                                                                                File
                                                                                :</label>
                                                                            <div style="height: 10%;width: 100%;">
                                                                                <a href="https://assignnmentinneed.com/user_login/uploads/"
                                                                                    target="_blank">
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                </fieldset>
                                                            </div>
                                                            <!--end::Modal body-->
                                                        </div>
                                                        <!--end::Modal content-->
                                                    </div>
                                                    <!--end::Modal dialog-->
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <!--end::Table body-->
                                </table>

                            </div>
                        </div>
                       
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection