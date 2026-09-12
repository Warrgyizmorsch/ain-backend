@extends('layouts.app')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div id="kt_content_container" class="">
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">FeedBackSheet
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need</small>
                    </div>
                    <div class="d-flex align-items-center py-1">
                    </div>
                </div>
	        </div>

			<div class="col-xl-12">
			@include('order.section.feedbackfilter')
			</div>
            
			<div class="card card-xl-stretch  mb-xl-">
				<div class="card-header border-0 pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bolder fs-3 mb-1">Feedback & Ticket Conversations</span>
						<span class="text-muted mt-1 fw-bold fs-7">Ticket number, issue status and order-status history in one place</span>
					</h3>
					@if(auth()->user()->role_id == 1)
					<div class="card-toolbar">
						<button type="button" id="delete-selected-tickets" class="btn btn-sm btn-light-danger">
							Delete Selected Tickets
						</button>
					</div>
					@endif
				</div>
				<div class="card-body py-3">
					
					<div class="card-body py-3">
						<div id="scroll-feedback-table" class="table-responsive">
							<table class="table table-bordered table-hover align-middle" id="feedback-table">
								<thead>
									<tr class="fw-bolder text-dark bg-light">
										@if(auth()->user()->role_id == 1)
										<th class="min-w-50px text-center" style="background: #F5F8FA;">
											<input type="checkbox" id="select-all-tickets" class="form-check-input">
										</th>
										@endif
										<th class="min-w-50px text-center" style="background: #F5F8FA;">SR</th>
										<th class="min-w-150px text-center" style="background: #F5F8FA;">Order Code</th>
										@if(auth()->user()->role_id != 4)
										<th class="min-w-150px text-center" style="background: #F5F8FA;">WriterTeam</th>
										@endif
										<th class="min-w-150px text-center" style="background: #F5F8FA;">Order Date</th>
										<th class="min-w-150px text-center" style="background: #F5F8FA;">Ticket Date</th>
										<th class="min-w-150px text-center" style="background: #F5F8FA;">Status</th>
										<th class="min-w-250px text-center" style="background: #F5F8FA;">Comments</th>
										<th class="min-w-150px text-center" style="background: #F5F8FA;">Action</th>
									
									</tr>
								</thead>
								<tbody>
                                @foreach($orders as $order)
                                @php
                                    $isFailedOrder = (int) ($order->is_fail ?? 0) === 1;
                                @endphp
								<tr>
									@if(auth()->user()->role_id == 1)
									<td class="text-center">
										@if($order->feedback_ticket)
										<input type="checkbox" class="form-check-input ticket-delete-checkbox" value="{{ $order->id }}">
										@endif
									</td>
									@endif
                                    <td class="text-center">{{ $loop->index +1}}</td>
                                    <td class="text-center">
                                        @if($isFailedOrder)
                                            <span class="failed-order-box">
                                                {{ $order->order_id }}<br>
                                                <span class="fs-8">Fail Order</span>
                                                @if($order->failed_at)
                                                    <br><span class="fs-9">{{ \Carbon\Carbon::parse($order->failed_at)->format('d M Y h:i A') }}</span>
                                                @endif
                                            </span><br>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <span class="fw-bold">{{ $order->order_id }}</span>
                                                <button type="button" class="btn btn-icon btn-sm p-0 border-0 bg-transparent copy-btn" title="Copy Order Code" onclick="fbCopyText('{{ $order->order_id }}', this)" style="width:18px;height:18px;line-height:1">
                                                    <i class="fa fa-copy text-muted" style="font-size:13px;"></i>
                                                </button>
                                            </div>
                                        @endif
										@if($order->feedback_ticket)
                                            <div class="d-flex align-items-center justify-content-center gap-1 mt-1">
                                                <span class="badge badge-light-danger fs-7 fw-bold text-nowrap">{{ $order->feedback_ticket }}</span>
                                                <button type="button" class="btn btn-icon btn-sm p-0 border-0 bg-transparent copy-btn" title="Copy Ticket Number" onclick="fbCopyText('{{ $order->feedback_ticket }}', this)" style="width:18px;height:18px;line-height:1">
                                                    <i class="fa fa-copy text-muted" style="font-size:13px;"></i>
                                                </button>
                                            </div>
										@php
											$ticketDate = $order->feedback_date ?: optional($order->feedback->sortBy('created_at')->first())->created_at;
										@endphp
										@if($ticketDate)
											<div class="text-muted fs-8 fw-bold mt-1">
												Ticket Date: {{ \Carbon\Carbon::parse($ticketDate)->format('d M Y h:i a') }}
											</div>
										@endif
										@endif
										 @if($order->team?->team_name)
										<span class="badge badge-light-primary fs-7 fw-bold mb-1">{{ $order->team->team_name }}</span><br>
										@endif
									</td>
									@if(auth()->user()->role_id != 4)
										<td class="text-center">{{  $order->writer_name}}</td>
									@endif
                                    <td class="text-center">
										@if($order->order_date)
										{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
										@endif
									</td>

                                    <td class="text-center">
										@if($order->feedback_date)
											{{ \Carbon\Carbon::parse($order->feedback_date)->format('d M Y h:i a') }}
										@endif
									</td>
                                    <td class="text-center">
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
											@elseif($order->status_issue == 'Retention')
											<span class="badge badge-light-danger fs-7 fw-bold" style="background:red; color:white">{{$order->status_issue}}</span>
											@else
											<span class="badge badge-light-success fs-7 fw-bold" >{{$order->status_issue}}</span>
										@endif
									</td>
									
									<td class="text-center">
										<div class="feedback-comment-box">
											{{ $order->comment }}
										</div>
										<a href="#" id="{{ $order->order_id }}" data-bs-toggle="modal" data-bs-target="#confirmationModal{{ $order->order_id }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
											More...
										</a>

									<div class="modal fade" id="confirmationModal{{ $order->order_id }}" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered mw-650px" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="confirmationModalLabel">Feedback - {{ $order->order_id }}</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												
												<div class="modal-body scroll-y mh-400px">
													<div class="timeline-label">
														@foreach($order->feedback->sortByDesc('created_at') as $feedback)
															@if($feedback->comment != '')
																<div class="timeline-item d-flex align-items-start mb-7">
																	<div class="timeline-line border-start-2 border-gray-300 ms-2 me-4" style="height: 100%; position: absolute; left: 8px;"></div>
																	
																	<div class="timeline-icon me-4" style="z-index: 1;">
																		<i class="fa fa-genderless text-{{ $feedback->created_by == Auth::user()->id ? 'primary' : 'success' }} fs-1"></i>
																	</div>

																	<div class="timeline-content flex-grow-1">
																		<div class="d-flex justify-content-between align-items-start mb-1">
																			<div>
																				<span class="fs-6 fw-bolder text-gray-800 me-2">{{ $feedback->user->name ?? 'System' }}</span>
																				<span class="text-muted fw-bold fs-8">{{ $feedback->created_at->format('d M Y, h:i a') }}</span>
																			</div>
																			
																			<div class="status-badge">
																				@php
																					$status = $feedback->status; // Assuming feedback table has 'status' field
																					$badgeStyle = "";
																				@endphp
																				
																				@if($status == 'Pending')
																					<span class="badge fs-9 fw-bold" style="background:pink; color:white">{{ $status }}</span>
																				@elseif($status == 'Completed' || $status == 'Draft Ready')
																					<span class="badge fs-9 fw-bold" style="background:#eaea00; color:black">{{ $status }}</span>
																				@elseif($status == 'Delivered' || $status == 'Draft Delivered')
																					<span class="badge fs-9 fw-bold" style="background:green; color:white">{{ $status }}</span>
																				@elseif($status == 'Feedback' || $status == 'Feedback Delivered')
																					<span class="badge fs-9 fw-bold" style="background:black; color:white">{{ $status }}</span>
																				@elseif($status == 'In Progress')
																					<span class="badge badge-light-info fs-9 fw-bold">{{ $status }}</span>
																				@elseif($status == 'Cancelled' || $status == 'Hold Work')
																					<span class="badge badge-light-danger fs-9 fw-bold">{{ $status }}</span>
																				@else
																					<span class="badge badge-light-primary fs-9 fw-bold">{{ $order->projectstatus ?? 'N/A' }}</span>
																				@endif
																			</div>
																		</div>

																		<div class="p-3 bg-light rounded text-gray-700 fw-bold border border-gray-200">
																			{{ $feedback->comment }}
																		</div>
																	</div>
																</div>
															@endif
														@endforeach
													</div>
													</div>
												
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</div>
									</td>

                                    <td class="text-center">
										<div class="icon-container my-auto d-flex align-items-center justify-content-center flex-wrap gap-1">
                                        <a href="#"  style="background-color:black" data-kt-drawer-toggle="#kt_drawer_chat{{ $order->order_id }}" id="kt_drawer_chat_toggle{{ $order->order_id }}" class="btn btn-icon btn-bg-secondary btn-active-color-primary btn-sm me-1">
                                            <span class="svg-icon svg-icon-3">
                                            <li style="color:white" class="fa fa-edit"></li>
                                            </span>
                                        </a>

										
										@include('order.section.order-action-edit')
										       

										<a href="#" data-bs-toggle="modal" data-bs-target="#statusModal{{$order->id}}" id="{{ $order->order_id }}" class="btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1">
                                            <span class="svg-icon svg-icon-3" style="color:white">
                                              S
                                            </span>
                                        </a>
										
										@include('order.section.feedback-status')

                                        @php
                                            $fbPhone = $order->user->mobile_no ?? '';
                                            $fbEmail = $order->user->email ?? '';
                                            $fbCC = preg_replace('/\D+/', '', (string)($order->user->countrycode ?? ''));
                                            $fbName = addslashes($order->user->name ?? 'Customer');
                                            $fbWAPhone = $fbCC . $fbPhone;
                                            $fbEmailUrl = route('emails.index', array_filter(['account_id' => 2, 'search' => $fbEmail]));
                                            $fbWhatsAppUrl = !empty($fbWAPhone) ? route('whatsapp.chat', ['phone' => $fbWAPhone]) : route('whatsapp.chat');
                                        @endphp
                                        @if($fbPhone)
                                        <a href="#"
                                           id="twilioCallBtnfeedback{{ $order->id }}"
                                           onclick="event.preventDefault(); event.stopPropagation(); initiateCustomerCall('{{ $fbCC . $fbPhone }}', '{{ $fbName }}');"
                                           class="btn btn-icon btn-sm me-1 shadow-sm"
                                           style="width:28px;height:28px;min-width:28px;border-radius:6px;background-color:#25D366;color:#ffffff;display:inline-flex;align-items:center;justify-content:center;transition:transform 0.2s ease,background-color 0.2s ease;"
                                           onmouseover="this.style.backgroundColor='#1ebd58';this.style.transform='scale(1.1)';"
                                           onmouseout="this.style.backgroundColor='#25D366';this.style.transform='scale(1)';"
                                           title="Call: {{ $fbCC . $fbPhone }}">
                                            <i class="fa fa-phone text-white" style="font-size:12px;"></i>
                                        </a>

                                        <a href="{{ $fbWhatsAppUrl }}" target="_blank" class="btn btn-icon btn-sm me-1 crm-btn-wa" style="width: 28px !important; height: 28px !important; min-width: 28px !important;" title="WhatsApp: {{ $fbWAPhone ?: 'Open Chat' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16">
                                                <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/>
                                            </svg>
                                        </a>
                                        @endif

                                        <a href="{{ $fbEmailUrl }}" target="_blank" class="btn btn-icon btn-sm me-1 crm-btn-email" style="width: 28px !important; height: 28px !important; min-width: 28px !important;" title="Email: {{ $fbEmail ?: 'Open Emails' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16">
                                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                                            </svg>
                                        </a>
										@if(auth()->user()->role_id == 1 && $order->feedback_ticket)
										<button type="button"
											class="btn btn-icon btn-bg-danger btn-active-color-light btn-sm me-1 delete-ticket-btn"
											data-order-id="{{ $order->id }}"
											title="Delete Ticket Number">
											<span class="svg-icon svg-icon-3" style="color:white">
												<i class="fa fa-trash"></i>
											</span>
										</button>
										@endif
                                        </div>
                                    </td>
								</tr>
                                @endforeach
								</tbody>
							</table>
					
								@if ($orders instanceof \Illuminate\Pagination\AbstractPaginator)
									{{ $orders->links() }}
								@else
								
									{{ $orders->onEachSide(1)->links() }}
								@endif					
						</div>
					</div>

				</div>
			</div>

        </div>
    </div>
</div>
<style>
	.shadow-sm {
    display: none;
}
.text-gray-700 {
    margin-top: revert;
}

    #scroll-feedback-table {
        max-height: 76vh;
        overflow: auto;
    }

    #feedback-table {
        margin-bottom: 0;
    }

    #feedback-table thead th {
        position: sticky;
        top: 0;
        z-index: 5;
    }

    #feedback-table td,
    #feedback-table th {
        border: 1px solid #dee2e6;
        vertical-align: middle;
    }

	.feedback-comment-box {
		background: linear-gradient(135deg, #f8f9ff 0%, #f5f8fa 100%);
		border: 1px solid #dfe3f2;
		border-left: 4px solid #6f42c1;
		border-radius: 8px;
        color: #3f4254;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.4;
        margin: 0 auto 8px;
        max-width: 280px;
		padding: 10px 12px;
		text-align: left;
		word-break: break-word;
		box-shadow: 0 3px 12px rgba(63, 66, 84, 0.06);
	}

	#feedback-table tbody tr:hover td {
		background-color: #fafbff;
	}

	#feedback-table thead th {
		text-transform: uppercase;
		letter-spacing: .03em;
		font-size: 11px;
	}

    .failed-order-box {
        display: inline-block;
        background: #ffeaea !important;
        color: #b50000 !important;
        border: 2px solid #ff0000 !important;
        border-radius: 8px;
        padding: 6px 10px;
        font-weight: 700;
        line-height: 1.35;
    }

    #feedback-table .icon-container {
        gap: 4px;
        min-width: 145px;
    }

.timeline-label { position: relative; }
    .timeline-item { position: relative; }
    .timeline-label::before {
        content: "";
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #eff2f5;
        z-index: 0;
    }
    .timeline-icon i {
        background-color: #ffffff;
        padding: 2px;
    }
</style>
<script>
    function fbCopyText(text, btn) {
        navigator.clipboard.writeText(text).then(function() {
            var icon = btn.querySelector('i');
            icon.classList.remove('fa-copy', 'text-muted');
            icon.classList.add('fa-check', 'text-success');
            setTimeout(function() {
                icon.classList.remove('fa-check', 'text-success');
                icon.classList.add('fa-copy', 'text-muted');
            }, 1500);
        }).catch(function() {
            // fallback
            var ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        });
    }

    $(document).ready(function() {
        // --- Team Alpha Click ---
        $('#teamAlphaBtn').on('click', function() {
            $('#filter_team_id').val('1'); // Alpha ki ID 1 hai
            // Form ko programmatically submit kar do taaki search start ho jaye
            $('#searchForm').submit(); 
        });

        // --- Team Giga Click ---
        $('#teamGigaBtn').on('click', function() {
            $('#filter_team_id').val('2'); // Giga ki ID 2 hai
            $('#searchForm').submit();
        });

		$('#select-all-tickets').on('change', function() {
			$('.ticket-delete-checkbox').prop('checked', $(this).is(':checked'));
		});

		$(document).on('change', '.ticket-delete-checkbox', function() {
			const total = $('.ticket-delete-checkbox').length;
			const checked = $('.ticket-delete-checkbox:checked').length;
			$('#select-all-tickets').prop('checked', total > 0 && total === checked);
		});

		$(document).on('click', '.delete-ticket-btn', function() {
			const orderId = $(this).data('order-id');
			deleteFeedbackTickets([orderId]);
		});

		$('#delete-selected-tickets').on('click', function() {
			const orderIds = $('.ticket-delete-checkbox:checked').map(function() {
				return $(this).val();
			}).get();

			deleteFeedbackTickets(orderIds);
		});
    });

	function deleteFeedbackTickets(orderIds) {
		if (!orderIds.length) {
			alert('Please select at least one ticket.');
			return;
		}

		if (!confirm('Are you sure you want to delete selected ticket number(s)?')) {
			return;
		}

		fetch(@json(route('feedback.ticket.delete')), {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			},
			body: JSON.stringify({ order_ids: orderIds })
		})
		.then(async response => {
			const data = await response.json().catch(() => ({}));
			if (!response.ok) {
				throw new Error(data.message || 'Failed to delete ticket number(s).');
			}
			return data;
		})
		.then(data => {
			alert(data.message || 'Ticket number(s) deleted successfully.');
			window.location.reload();
		})
		.catch(error => {
			alert(error.message || 'Failed to delete ticket number(s).');
		});
	}
</script>
@endsection
