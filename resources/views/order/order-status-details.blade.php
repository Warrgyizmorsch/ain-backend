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
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Writer
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need</small>
                    </div>
                    
                </div>
	        </div>

			<div class="col-xl-12">
				<!-- include('order.section.fileter') -->
                <div class="card card-xxl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Filter</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <form action="">
                            <div class="row">
                                <div class="col-md-3 fv-row">
                                    <input type="date" name="from_date" id="from_date" class="form-control form-control-solid" placeholder="From">
                                </div>
                                <div class="col-md-3 fv-row">
                                    <input type="date" name="to_date" id="to_date" class="form-control form-control-solid" placeholder="To">
                                </div>
                                <div class="col-lg-12 fv-row fv-plugins-icon-container mt-5">
                                    <a  href="/status-details" class="btn btn-sm btn-danger">Reset</a>
                                    <input type="submit" value="Search" class="btn btn-sm btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
            
			<div class="card card-xl-stretch  mb-xl-">
				
				<div class="card-body py-3">
					<div class="card-header border-0 pt-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1">Orders</span>
						</h3>
					</div>
					<div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                <thead class="p-2">
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th class="min-w-55px text-center">SR</th>
                                        <th class="min-w-125px">Order Code</th>
                                        <th class="min-w-150px">O- Date</th>
                                        <th class="min-w-250px">D-Date</th>
                                        <th class="min-w-250px">Status</th>
                                        <th class="min-w-150px text-start">User</th>
                                        <th class="min-w-150px text-start">Email</th>
                                        <th class="min-w-150px text-start">Mobile No</th>
                                        <th class="min-w-150px text-start">Stauts Update Date</th>
                                        <th class="min-w-150px text-start">Status Update User</th>
                                    </tr>
                                </thead>
                                @foreach($data['order'] as $orderData)
                                <tbody> 
                                    <tr>
                                        <td>{{$loop->index +1 }}</td>
                                        <td>{{$orderData->order_id}}</td>
                                        <td>{{$orderData->order_date}}</td>
                                        <td>{{$orderData->delivery_date}}</td>
                                        <td onclick="status('{{$orderData->id }}')">
                                            @if($orderData->projectstatus == 'Other')
											<span class="badge badge-light-primary fs-7 fw-bold " style="background:#44f2e4; color:black">{{$orderData->projectstatus}}</span>
                                            @elseif($orderData->projectstatus == 'Pending')
											<span class="badge badge-light-warning fs-7 fw-bold" style="background:pink; color:white">{{$orderData->projectstatus}}</span>
                                            @elseif($orderData->projectstatus == 'In Progress')
											<span class="badge badge-light-info fs-7 fw-bold">{{$orderData->projectstatus}}</span>
                                            @elseif($orderData->projectstatus == 'Hold Work')
											<span class="badge badge-light-danger fs-7 fw-bold">{{$orderData->projectstatus}}</span>
                                            @elseif($orderData->projectstatus == 'Completed')
											<span class="badge badge-light-warning fs-7 fw-bold" style="background:#eaea00; color:black">{{$orderData->projectstatus}}</span>
                                            @elseif($orderData->projectstatus == 'Delivered')
											<span class="badge badge-light-success fs-7 fw-bold" style="background:green; color:white">{{$orderData->projectstatus}}</span>
                                            @elseif($orderData->projectstatus == 'Feedback')
											<span class="badge badge-light-primary fs-7 fw-bold" style="background:black; color:white">{{$orderData->projectstatus}}</span>
                                            @elseif($orderData->projectstatus == 'Feedback Delivered')
											<span class="badge badge-light-danger fs-7 fw-bold" style="background:black; color:white">{{$orderData->projectstatus}}</span>
                                            @elseif($orderData->projectstatus == 'Cancelled')
											<span class="badge badge-light-danger fs-7 fw-bold">{{$orderData->projectstatus}}</span>
                                            @elseif($orderData->projectstatus == 'Draft Ready')
											<span class="badge badge-light-primary fs-7 fw-bold" style="background:#eaea00; color:black">{{$orderData->projectstatus}}</span>
                                            @elseif($orderData->projectstatus == 'Draft Delivered')
											<span class="badge badge-light-primary fs-7 fw-bold" style="background:green; color:white">{{$orderData->projectstatus}}</span>
                                            @elseif($orderData->projectstatus == 'Initiated')
											<span class="badge badge-light-primary fs-7 fw-bold" style="background:pink; color:white">{{$orderData->projectstatus}}</span>
                                            @endif
										</td>
                                        <td>
                                           @if($orderData->user)
                                            {{$orderData->user->name}} <br>
                                           
                                            @endif 
                                        </td>
                                           <td>
                                           @if($orderData->user)
                                         
                                            {{$orderData->user->email}} <br>
                                       
                                            @endif 
                                        </td>
                                           <td>
                                           @if($orderData->user)
                                           
                                            {{$orderData->user->mobile_no}} 
                                            @endif 
                                        </td>
                                        <td><?php
                                            $status_date = $orderData->status_date;
                                            $date_time = new DateTime($status_date);
                                            $date_formatted = $date_time->format('j F Y');
                                            $time_formatted = $date_time->format('H:i');
                                            echo "Date: $date_formatted<br>";
                                            echo "Time: $time_formatted";
                                            ?>
                                            </td>
                                        <td>{{$orderData->status_by}}</td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>

					</div>

				</div>
			</div>

        </div>
    </div>



  @endsection
  