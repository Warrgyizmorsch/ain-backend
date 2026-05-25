@extends('layouts.app')
@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div id="kt_content_container" class="">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Failed Jobs
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In Need</small>
                </div>
                
            </div>
        </div>
        <!-- Add Filter -->
        <div class="col-xl-12">
        @isset($data['all_order'])
            @include('master.section.failed_job_filter')
        @endisset
        </div>
        <div class="card card-xl-stretch  mb-xl-">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Failed Jobs List</span>
                    <span class="text-muted mt-1 fw-bold fs-7"></span>
                </h3>
                

            </div>
            <div class="card-body py-3">
                
                <div>
                    <div class="table-responsive">
                    <table  class="table table-hover table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead class="p-2">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class='px-2'>Sr.No.</th>
                                    <th>Order Date</th>
                                    <th>Order Code</th>
                                    <th>Delivery Date</th>
                                    <th>Writer Name</th>
                                    <th>Writer Deadline</th>
                                </tr>
                            </thead>
                            <tbody style="" id="content" class="searchData">
                                <!-- ... (your existing code) ... -->
                                @isset($filterOrder) 
                                <tr>
                                    <td class='text-center'>1</td>
                                    <td>{{ \Carbon\Carbon::parse($filterOrder->order_date)->format('d M Y') }}</td>
                                    <td>{{ $filterOrder->order_id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($filterOrder->delivery_date)->format('d M Y') }}</td>
                                    <td>{{ $filterOrder->writer_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($filterOrder->writer_deadline)->format('d M Y') }}</td>
                                </tr>
                                <div class='py-4'>
                                    <a href="{{ url()->previous() }}" class="btn btn-primary">Go Back</a>
                                </div>
                            @endisset
                               
                            </tbody>
                            <tbody>
                            @isset($data['all_order'])
                                @php $counter = ($order->currentPage() - 1) * $order->perPage() + 1; @endphp
                                @foreach($order as $item)
                                <tr>
                                    <td class='text-center'>{{ $counter++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->order_date)->format('d M Y') }}</td>
                                    <td>{{ $item->order_id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->delivery_date)->format('d M Y') }}</td>
                                    <td>{{ $item->writer_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->writer_deadline)->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                        <div class="m-4">
                        @isset($data['all_order'])
                            {{$order->links('pagination::bootstrap-4')}}
                        @endisset
                        </div>
                    </div>
                </div>

            </div>
        </div>
            

        </div>
    </div>
@endsection