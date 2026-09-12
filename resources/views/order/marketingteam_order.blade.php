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
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Order
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need for MarketingTeamRole</small>
                    </div>
                    
                </div>
	        </div>

			<div class="col-xl-12">
				@include('order.filter.marketing')
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
										<th class="min-w-15px">SR</th>
										<th class="min-w-50px">Order Code</th>
										<th class="min-w-50px text-center">User</th>
										<th class="min-w-30px">O Date</th>
										<th class="min-w-30px">D Date</th>
										<th class="min-w-100px">Title</th>
										<th class="min-w-40px">Status</th>
										<th class="min-w-40px">Ticket Status</th>
										<th class="min-w-40px">Word</th>
										<th class="min-w-40px">Amount</th>
										<th class="min-w-40px">Received</th>
										<th class="min-w-40px" >Due</th>
										<th class="min-w-40px">Writer_name</th>
										<th class="min-w-100px text-center" >Action</th>
									</tr>
								</thead>
								<tbody style="display:none" id="content" class="searchData">
							
								</tbody>
								<tbody class="allData">

                                    @foreach($data['orders'] as $order)
									<tr @if( $order->user->is_fail == 1) style="color:blue"  @endif  id="order_{{ $order->id }}" class="{{ ($order->is_read == 1) ? 'bold-row' : '' }}" onclick="markAsRead('{{ $order->id }}')">										<td>
										{{ $loop->index + 1 }}
										</td>
										<td class="text-center">
											<div class="d-inline-flex align-items-center justify-content-center">
												<span>{{ $order->order_id }}</span>
												@if(!empty($order->order_id))
													<button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Order ID" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $order->order_id }}', 'Order ID copied!');">
														<i class="fa fa-clone fs-8 text-muted"></i>
													</button>
												@endif
											</div>
											@if($order->team?->team_name)
												<div class="d-inline-flex align-items-center justify-content-center gap-1 mb-1">
													<span class="badge badge-light-primary fs-7 fw-bold">{{ $order->team->team_name }}</span>
												</div><br>
											@endif
											<span class="badge badge-light-danger fs-7 fw-bold ">{{$order->feedback_ticket}}</span>
                                            @if($order->is_fail == 1)
												<span class="badge badge-light-danger fs-7 fw-bold">Fail Order</span>
											@endif
											@if ($order->resit == 'on')
                                                <span class="badge badge-light-danger fs-7 fw-bold">Resit Work</span>
                                            @endif
											@if($order->services == 'First Class Work')
												<span class="badge badge-light-info fs-7 fw-bold">First Class Work</span>
											@endif

                                        </td>
										<td class="text-center">
										@if($order->user != null && ($order->user->name != '' || $order->user->name == null))
											@php
												$userAssignedLabels = optional($order->user)->labels ?? collect();
												$userAssignedLabelIds = $userAssignedLabels->pluck('id')->all();
												$rawUserMobile = $order->user->mobile_no ?: '';
												$rawUserEmail = $order->user->email ?: '';
												$displayMobile = mask_phone_for_display($order->user->countrycode, $order->user->mobile_no);
												$displayEmail  = mask_email_for_display($order->user->email);
											@endphp
											<div class="d-flex align-items-center justify-content-center">
												<span class="fw-bold">{{ $order->user->name }}</span>
											</div>
											@if(!empty($order->user->email))
												<div class="d-inline-flex align-items-center my-1">
													<span class="text-gray-600 fs-8 text-break">{{ $displayEmail }}</span>
													<button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Email" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $displayEmail }}', 'Email copied!');">
														<i class="fa fa-clone fs-8 text-muted"></i>
													</button>
												</div>
												<br>
											@endif
											@if(!empty($order->user->mobile_no))
												@php $displayMobile = mask_phone_for_display($order->user->countrycode, $order->user->mobile_no); @endphp
												<div class="d-inline-flex align-items-center my-1">
													<span class="badge badge-light-danger fs-7 fw-bold">{{ $displayMobile }}</span>
													<button type="button" class="btn btn-icon btn-sm btn-active-light-danger ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Mobile" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $displayMobile }}', 'Mobile number copied!');">
														<i class="fa fa-clone fs-8 text-danger"></i>
													</button>
												</div>
											@endif

											@php
												$orderRawWAPhone = preg_replace('/\D+/', '', (string)((optional($order->user)->countrycode ?? '') . (optional($order->user)->mobile_no ?? '')));
												$orderRawEmail = optional($order->user)->email ?? '';
												$orderEmailUrl = route('emails.index', array_filter(['account_id' => 2, 'search' => $orderRawEmail]));
												$orderWhatsAppUrl = !empty($orderRawWAPhone) ? route('whatsapp.chat', ['phone' => $orderRawWAPhone]) : route('whatsapp.chat');
											@endphp

											{{-- Direct Contact Actions: WhatsApp & Email --}}
											<div class="d-inline-flex align-items-center justify-content-center gap-2 my-1">
												<a href="{{ $orderWhatsAppUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-wa" title="WhatsApp: {{ $orderRawWAPhone ?: 'Open Chat' }}">
													<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 16 16">
														<path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/>
													</svg>
												</a>
												<a href="{{ $orderEmailUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-email" title="Email: {{ $orderRawEmail ?: 'Open Emails' }}">
													<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16">
														<path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
													</svg>
												</a>
											</div>
											<br>

											{{-- User Assigned Labels Chips --}}
											<div class="d-flex flex-wrap justify-content-center gap-1 my-1" data-user-labels-badges="{{ $order->user->id }}" @if(!empty($rawUserMobile)) data-user-labels-badges-phone="{{ preg_replace('/\D+/', '', $rawUserMobile) }}" @endif>
												@foreach($userAssignedLabels as $lbl)
													<span class="badge" style="background:{{ $lbl->color }}1f; color:{{ $lbl->color }}; border:1px solid {{ $lbl->color }}4d; font-size: 10px; padding: 2px 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;">
														<span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:{{ $lbl->color }};"></span>{{ $lbl->name }}
													</span>
												@endforeach
											</div>

											<div class="d-flex justify-content-center align-items-center gap-2 mt-2">
												<button type="button" class="btn btn-icon btn-sm btn-light-success crm-btn-tag" title="Assign Labels" data-user-label-button="{{ $order->user->id }}" data-labels='@json($userAssignedLabelIds)' onclick="openUserLabelModal({{ $order->user->id }}, @js($order->user->name), @js($displayMobile ?? ''), @js($displayEmail ?? ''), JSON.parse(this.dataset.labels || '[]'))">
													<i class="fa fa-tag fs-7"></i>
												</button>
											</div>
										@else
											N/A
										@endif

										</td>
										<td>
											{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
											
										</td>
										<td onclick="updateDeliveryDate({{$order->id }})" >
										    @if($order->delivery_date != null)
												{{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
											@else
												Not Available
											@endif
										    @if( $order->draftrequired == 'Y')
                                            <span class="badge badge-light-success  fs-7 fw-bold">{{ \Carbon\Carbon::parse($order->draft_date)->format('d M Y') }} ({{ \Carbon\Carbon::parse($order->draft_time)->format('H:i') }})</span>	
                                            @endif	
										</td>
                                        <td style="width:50px">
                                          {{$order->title }}
                                           <br>
										 	@if( $order->semester != '' )
                                         	 	Semester :( {{$order->semester}})
                                            @endif	
										  	@if( $order->chapter != '' )
                                            <span class="badge badge-light-danger fs-7 fw-bold">{{$order->chapter}}</span>	
                                            @endif	

											@if( $order->tech == '1' )
                                            <span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>	
                                            @endif	
											@if ($order->module_code != '')
                                                <span class="badge badge-light-danger fs-7 fw-bold">{{$order->module_code}}</span>
                                            @endif
                                        </td>
										<td onclick="status('{{$order->id }}')" >
                                            @if($order->projectstatus == 'Other')
											<span class="badge badge-light-primary fs-7 fw-bold " style="background:#44f2e4; color:black">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Pending')
											<span class="badge badge-light-warning fs-7 fw-bold" style="background:pink; color:white">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'In Progress')
											<span class="badge badge-light-info fs-7 fw-bold">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Hold Work' || $order->projectstatus == 'Hold(writer query)')
											<span class="badge badge-light-danger fs-7 fw-bold">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Completed')
											<span class="badge badge-light-warning fs-7 fw-bold" style="background:#eaea00; color:black">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Delivered')
											<span class="badge badge-light-success fs-7 fw-bold" style="background:green; color:white">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Feedback')
											<span class="badge badge-light-primary fs-7 fw-bold" style="background:black; color:white">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Feedback Delivered')
											<span class="badge badge-light-danger fs-7 fw-bold" style="background:black; color:white">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Cancelled')
											<span class="badge badge-light-danger fs-7 fw-bold">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Draft Ready')
											<span class="badge badge-light-primary fs-7 fw-bold" style="background:#eaea00; color:black">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Draft Delivered')
											<span class="badge badge-light-primary fs-7 fw-bold" style="background:green; color:white">{{$order->projectstatus}}</span>
                                            @elseif($order->projectstatus == 'Initiated')
											<span class="badge badge-light-primary fs-7 fw-bold" style="background:pink; color:white">{{$order->projectstatus}}</span>
											@elseif($order->projectstatus == 'Advance Assignment')
											<span class="badge badge-light-danger fs-7 fw-bold" style="background:#44f2e4; color:black">{{$order->projectstatus}}</span>
                                            @endif
										</td>
										<td>
											@if($order->status_issue == 'Issue Raised')
												<span class="badge badge-light-danger fs-7 fw-bold ">{{$order->status_issue}}</span>
												@elseif($order->status_issue == 'Client Discussion Done')
												<span class="badge badge-light-info fs-7 fw-bold" >{{$order->status_issue}}</span>
												@elseif($order->status_issue == "Writer discussion Done")
												<span class="badge badge-light-success fs-7 fw-bold" >{{$order->status_issue}}</span>
												@elseif($order->status_issue == 'Work in progress')
												<span class="badge badge-light-warning fs-7 fw-bold" >{{$order->status_issue}}</span>
												@elseif($order->status_issue == 'Case Resolved')
												<span class="badge badge-light-success fs-7 fw-bold" >{{$order->status_issue}}</span>
												@elseif($order->status_issue == 'Issues Raised Again')
												<span class="badge badge-light-danger fs-7 fw-bold" style="background:red; color:white">{{$order->status_issue}}</span>
												@else
												<span class="badge badge-light-warning fs-7 fw-bold" > </span>
											@endif
										</td>
										<td style="width:50px">
										@if($order->pages != '')
										{{$order->pages }} 
										@else
											N/A
										@endif
                                        </td>
                                        <td style="width:50px">
                                           {{$order->amount }} £
                                        </td>
                                        <td style="width:50px">
                                           {{$order->received_amount }} £
                                        </td>
 										<td style="width:50px">
											@php
												$extraPriceAmt = $order->additionals ? (float)$order->additionals->sum('additional_price') : 0;
												$basePriceAmt  = is_numeric($order->amount) ? (float)$order->amount : 0;
												$recvPriceAmt  = is_numeric($order->received_amount) ? (float)$order->received_amount : 0;
												$calcDueAmt    = max(0, ($basePriceAmt + $extraPriceAmt) - $recvPriceAmt);
											@endphp
											@if(is_numeric($order->amount) || $extraPriceAmt > 0)
												{{ $calcDueAmt }} £
											@else
												N/A
											@endif
 										</td>

										<td>
											@php
												$orderRawEmail = optional($order->user)->email ?? '';
												$writeEmailUrl = route('emails.index', array_filter(['account_id' => 3, 'search' => $orderRawEmail]));
											@endphp
											@if($order->writer_name != null)
												<div class="d-inline-flex align-items-center justify-content-center gap-1">
													<span>{{ $order->writer_name }}</span>
													<a href="{{ $writeEmailUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-email" style="width: 20px !important; height: 20px !important; min-width: 20px !important;" title="Write Email: {{ $orderRawEmail ?: 'Open Write Email Channel' }}">
														<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 16 16">
															<path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
														</svg>
													</a>
												</div>
												<br>
												<span style="background-color: #f8f5ff;" class="badge badge-light-info fs-7 fw-bold">{{ \Carbon\Carbon::parse($order->writer_deadline)->format('d M Y') }}</span>	
											@else
                                            <span class="badge badge-light-danger fs-7 fw-bold">N/A</span>
											<a href="{{ $writeEmailUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-email ms-1" style="width: 20px !important; height: 20px !important; min-width: 20px !important;" title="Write Email: {{ $orderRawEmail ?: 'Open Write Email Channel' }}">
												<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 16 16">
													<path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
												</svg>
											</a>											
                                            @endif
										</td>


                                      
										<td class="text-end">
											<a target="_blank" href="edit.{{ $order->id }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
												<span class="svg-icon svg-icon-3">
													<li class="fa fa-eye "></li>
												</span>
											</a>
											@include('order.edit.marketing')

    										@php
    											$showQuestionMark = false;
    
    											if (count($order->payment) >= 1) {
    												foreach ($order->payment as $payment) {
    													if (empty($payment['payee_name']) || empty($payment['company_accounts'])) {
    														$showQuestionMark = true;
    														break;
    													}
    												}
    											}
    										@endphp
    
    										<a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_create_money{{$order->id}}" id="kt_toolbar_primary_button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 position-relative">
    											<span class="svg-icon svg-icon-3">
    												<li class="fa fa-money"></li>
    											</span>
    
    											@if($showQuestionMark)
    												<span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger animate__animated animate__flash animate__infinite" style="font-size: 10px; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center;">
    													?
    												</span>
    											@endif
    										</a>

											@include('order.section.payment-edit')

											<a href="#" onclick="showConfirmation({{ $order->id }})"class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
													<span class="svg-icon svg-icon-3">
														<i>F</i>
													</span>
											</a>
											@include('order.section.fail-order')

											
											<a href="#" data-kt-drawer-toggle="#kt_drawer_chat" id="kt_drawer_chat_toggle{{ $order->order_id }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
												<span class="svg-icon svg-icon-3">
													<i>T</i>
												</span>
											</a>
											@include('order.section.comment-order')
												<a   target="_blank" href="/call.{{$order->id}}" class="btn btn-icon btn-bg-warning btn-active-color-light btn-sm me-1">Call</a>

											<a href="#" onclick="showConfirmationclick('{{ $order->id }}')" id="clickToCallBtn{{$order->id}}" class="btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1">
												<span class="svg-icon svg-icon-3">
													<i class="fa fa-phone fa-lg"></i>
												</span>
											</a>

											<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
											<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

											<script>
												function showConfirmationclick(orderId) {
													openRingfySoftphone(@js($order->id), @js(optional($order->user)->countrycode), @js(optional($order->user)->mobile_no));
												}
											</script>
												<a href="#" id="clickToDownload{{$order->order_id}}" class="btn btn-icon btn-bg-danger btn-active-color-dark btn-sm me-1 download-btn{{$order->id}}" onclick="downloadFiles(this)">
											<span class="svg-icon svg-icon-3">
												<i class="fa fa-download fa-lg"></i>
											</span>
										</a>

										<script>
											function downloadFiles(element) {
												// Prevent the default anchor behavior
												event.preventDefault();

												// Get the order ID from the id attribute of the clicked <a> tag
												var orderId = element.id.replace('clickToDownload', '');

												// Send AJAX request to get file URLs for the order
												$.ajax({
													url: '/get-files-by-order', // Update the URL to your route endpoint
													method: 'GET',
													data: {order_id: orderId},
													success: function(response) {
														// Check if response is empty
														if (response.length === 0) {
															// Show SweetAlert popup for no files to download
															Swal.fire({
																icon: 'warning',
																title: 'No Files Found',
																text: 'There are no files to download for this order.',
																confirmButtonColor: '#3085d6',
																confirmButtonText: 'OK'
															});
														} else {
															// Loop through each file URL and trigger download
															response.forEach(function(fileUrl) {
																// Create an anchor element for the file
																var link = document.createElement('a');
																link.href = fileUrl;
																link.download = fileUrl.substring(fileUrl.lastIndexOf('/') + 1);
																document.body.appendChild(link);

																// Simulate a click event to trigger the download
																link.click();

																// Clean up
																document.body.removeChild(link);
															});
														}
													},
													error: function(xhr, status, error) {
														// Handle error
														console.error(xhr.responseText);
													}
												});
											}
										</script>


										</td>
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
	<script src="https://cdn.socket.io/3.0.3/socket.io.min.js"></script>
<!-- Your table structure -->
<script>
    function markAsRead(orderId) {
        // Assuming you're using jQuery
        $.ajax({
            type: 'POST',
            url: '/mark-as-read',
            data: {
                order_id: orderId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update the UI in real-time
                    $('#order_' + orderId).removeClass('bold-row');
                    // You may also update other information here if needed
                } else {
                    alert('Failed to mark as read');
                }
            },
            error: function() {
                alert('Failed to communicate with the server');
            }
        });
    }

    // Listen for real-time updates
    if (window.Echo?.channel) {
		window.Echo.channel('orders')
			.listen('OrderMarkedAsRead', (event) => {
				$('#order_' + event.order_id).removeClass('bold-row');
			});
	} else {
		console.warn('Echo is not available.');
	}
</script>

<style>
	.bold-row {
		font-weight: bold !important;
	}
</style>
<script>
	function status(orderId) {
		let data = <?php echo json_encode($data['Status']); ?>;
		let statusValues = Object.values(data).map(item => item.status);
		Swal.fire({
			title: 'Change Status',
			text: 'Select Status',
			icon: 'info',
			input: 'select',
			inputOptions: statusValues,
			inputPlaceholder: 'Select status',
			showCancelButton: true,
			confirmButtonText: 'OK',
			cancelButtonText: 'Cancel',
			preConfirm: (selectedStatus) => {
				if (!selectedStatus) {
					Swal.fire({
						title: 'Error!',
						text: 'Status cannot be empty!',
						icon: 'error'
					});
					return false; // Prevent further execution
				}
				
				let updateData = {
					orderId: orderId,
					status: selectedStatus,
					_token: '{{ csrf_token() }}'
				};
				$.ajax({
					type: 'POST',
					url: 'update_status',
					data: updateData,
					success: function(response) {
						if (response.warning) {
							Swal.fire({
								icon: 'warning',
								title: 'Warning',
								text: response.warning
							});
						} else {
							Swal.fire({
								icon: 'success',
								title: 'Success',
								text: 'Status updated successfully'
							}).then(() => {
								// Reload the page after showing the success message
								location.reload();
							});
						}
					},
					error: function(xhr, status, error) {
						console.log(updateData);
					}
				});
			}
		});
	}
</script>
<script>
    function updateDeliveryDate(orderId) {
        // Show date picker
        Swal.fire({
            title: 'Select Delivery Date',
            html: '<input type="date" id="deliveryDate" class="swal2-input">',
            confirmButtonText: 'Confirm',
            preConfirm: () => {
                const selectedDate = document.getElementById('deliveryDate').value;
                if (!selectedDate) {
                    Swal.showValidationMessage('Please select a delivery date');
                }
                return selectedDate;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Retrieve selected date
                const selectedDate = result.value;
                // Perform actions with the selected date (e.g., update status)
                console.log('Order ID:', orderId);
                console.log('Selected Delivery Date:', selectedDate);

                // Assuming you have the CSRF token available in a variable named csrfToken
                const csrfToken = '{{ csrf_token() }}';

                // Send AJAX request to update delivery date
                $.ajax({
                    url: 'update_date',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        orderId: orderId,
                        selectedDate: selectedDate
                    },
                    success: function(response) {
						if (response.Error) {
							// Show SweetAlert error message if there is an error in the response
							Swal.fire('Error!', response.Error, 'error');
						} else {
							// Reload the page if date updated successfully
							location.reload();
						}
					},
					error: function(xhr, status, error) {
						console.error(xhr.responseText);
						Swal.fire('Error!', 'An unexpected error occurred.', 'error');
					}
                });
            }
        });
    }
</script>

  @include('back-end.order.partials.user-label-modal')
  @endsection
  
