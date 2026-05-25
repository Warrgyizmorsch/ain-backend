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
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Cancel Leads
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need</small>
                    </div>
                    
                </div>
	        </div>

			<div class="col-xl-12">
                @include('leads.section.cancel-lead-filter')
            </div>
            
			<div class="card card-xl-stretch ">
				<div class="card-header border-0 pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bolder fs-3 mb-1">Cancel Leads</span>
						<span class="text-muted mt-1 fw-bold fs-7"></span>
					</h3>
                    
				</div>
				
                <div class="card-body py-3">
					
					<div >
						<div class="table-responsive">
							<table  class="table table-hover table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
								<thead class="p-2">
									<tr class="fw-bolder text-muted bg-light">
										<th class='px-2'>No</th>
										<th class='text-center'>Action</th>
										<th>Order ID</th>
										
										<th>Name</th>
										<th>Mobile</th>
										<th>Order Date</th>
										<th>Project Title</th>
										<th>Words</th>
										<th>Price</th>
										<th class='px-2'>Delivery Date</th>
									</tr>
								</thead>
								<tbody>
                                    @foreach($status1Leads as $lead)
                                        <tr>
                                            <td class='text-center'>{{ ($status1Leads->currentPage() - 1) * $status1Leads->perPage() + $loop->iteration }}</td>
                                            
                                            <td  style="justify-content:center" class=" text-center icon-container my-auto d-flex">
                                                <div class="form-check form-switch my-auto">
                                                    <input class="form-check-input" type="checkbox" id="{{ $lead->id }}" role="switch" unchecked onchange="handleChange(this, {{ $lead->id }})">
                                                </div>

                                                <a href="#" data-kt-drawer-toggle="#kt_drawer_chat"
                                                id="kt_drawer_chat_toggle{{ $lead->id }}"
                                                class="btn btn-icon btn-bg-warning btn-active-color-light btn-sm me-1">Call</a>
                                            @include('leads.section.call-lead')
                                                @if(auth()->user()->role_id === 1)
                                                <a href="#" id="{{$lead->id}}" style="color:white" class="btn btn-icon btn-bg-danger btn-active-color-light btn-sm me-1 delete-link">
                                                    <span class="svg-icon svg-icon-3">
                                                        <li class="fa fa-trash fa-lg"></li>
                                                    </span>
                                                </a>
                                                @endif
                                            </td>
                                            <td>{{ $lead->order_id }}</td>
                                            
                                            <td style="width:50px">@if ($lead->user != '') 
                                                                        {{ $lead->user->name }} </br>
                                                                         {{ $lead->user->email }}
                                                                    @endif
                                            </td>
                                            <td>@if ($lead->user !='') {{ $lead->user->mobile_no }} @endif</td>

                                            <td style="width:50px">{{ \Carbon\Carbon::parse($lead->create_at)->format('d M Y') }}</td>
                                            
                                            <td style="width:50px">{{ $lead->project_title }}</td>
                                            <td>{{ $lead->pages }}</td>
                                            <td>{{ $lead->price }}</td>
                                            <td>{{$lead->deadline}}</td>
                                            
                                        </tr>
                                    @endforeach
								</tbody>
							</table>
                            <div class="m-4">
                                {{$status1Leads->links('pagination::bootstrap-4')}}
                            </div>
                            
						</div>
					</div>
                   
				</div>
			</div>
        </div>
    </div>
    
   @include('leads.section.cancelleadsscript');

 

  @endsection
  