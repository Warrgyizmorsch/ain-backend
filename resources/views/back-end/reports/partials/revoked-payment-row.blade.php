<tr>
    <td class="text-center" style="padding-right: 0px;">
        {{ $index + 1 }}
    </td>

    <td class="text-center" style="position: sticky; left: 0; background: white; z-index: 2;">
            <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">

                <!-- Edit Order Button -->
                <a target="_blank" style="background-color: #1e1e2d;" href="orders/edit/{{ $order->id }}" class="btn btn-icon btn-sm" title="Edit Order">
                    <i style="color: white;" class="fa fa-edit"></i>
                </a>

                <!-- Chat / Comment Button -->
                {{-- <button onclick="loadCommentDrawer({{ $order->id }})" class="btn btn-icon btn-secondary btn-sm" title="Open Chat">
                    <i class="fa fa-comment-alt"></i>
                </button> --}}


                <!-- Button to Open Unified Payment Page -->
                <!-- Button to Open Unified Payment Page -->
                {{-- <a href="{{ route('orders.payment.form', ['orderId' => $order->id]) }}"
                    target="_blank"
                    class="btn btn-icon btn-success btn-sm position-relative"
                    title="Add/Edit Payment">

                    <i class="fa fa-money"></i> --}}

                    {{-- Check if any payment is missing payee_name or company_accounts --}}
                    {{-- @if($order->payment->contains(function($p) {
                    return empty($p->payee_name) || empty($p->company_accounts);
                    }))
                    <i class="fa fa-question-circle text-danger bg-white"
                        title="Incomplete payment info"
                        style="position: absolute; top: -3px; right: -3px; font-size: 11px; border-radius: 50%;"></i>
                    @endif

                </a> --}}

                <a href="{{ route('orders.payment.form', [
                        'orderId' => $order->id,
                        'paymentId' => $payment->id
                    ]) }}"
                    target="_blank"
                    class="btn btn-icon btn-success btn-sm position-relative"
                    title="View Revoked Payment">

                    <i class="fa fa-money"></i>
                </a>


                <!-- Mark as Failed Button -->
                {{-- <a href="javascript:void(0);" onclick="showConfirmation({{ $order->id }}, {{ $order->is_fail }})" class="btn btn-icon btn-danger btn-sm" title="Mark as Failed">
                    <i class="fa fa-times-circle"></i>
                </a> --}}

                {{-- <a href="#"
                data-bs-toggle="modal"
                data-bs-target="#requestExtensionModal{{ $payment->id }}"
                class="btn btn-icon btn-warning btn-sm"
                title="Request Deadline Extension">
                    <span class="fw-bold text-white">E</span>
                </a> --}}

                {{-- @if(auth()->user()->role_id == 1)
                    <button type="button" class="btn btn-icon btn-sm btn-light-danger" title="Looking For Refund" onclick="markLookingForRefund({{ $order->id }})">
                        <span class="fw-bold fs-6">R</span>
                    </button>

                    <button type="button" class="btn btn-icon btn-sm btn-light-primary" title="Add Additional" onclick="openAdditionalModal({{ $order->id }})">
                        <span class="fw-bold fs-6">A</span>
                    </button>
                @endif --}}

                @if(auth()->user()->role_id == 9)
                <a onclick="CallToWriter('{{ $order->id }}')" class="btn btn-icon btn-bg-warning btn-active-color-dark btn-color-white btn-sm me-1 download-btn">
                    <span class="svg-icon svg-icon-3">
                        <i class="fa fa-phone fa-lg"></i>
                    </span>
                </a>
                @endif
            </div>

        </td>

    <td class="text-center">
        <div style="background-color: {{ $payment->revoke_resolved ? '#f0fff4' : '#fff5f5' }}; border-radius: 8px;padding: 12px; margin-top: 5px; border: 1px solid {{ $payment->revoke_resolved ? '#50cd89' : '#f1416c' }}; text-align: left; min-width: 220px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bolder fs-6" style="color: #5e6278;">Revoked Payment</span>
                <span class="text-muted fs-8 fw-bold">
                    {{ $payment->revoked_at ? \Carbon\Carbon::parse($payment->revoked_at)->diffForHumans() : '' }}
                </span>
            </div>

            <div class="text-dark fs-7 mb-3" style="word-wrap: break-word; line-height: 1.4;">
                {{ $payment->revoke_comment ?? 'No Comment Found' }}
            </div>

            <div class="d-flex align-items-center fw-bolder fs-7" style="color: {{ $payment->revoke_resolved ? '#50cd89' : '#f1416c' }};">
                <i class="fa fa-calendar-alt me-2"
                    style="color: {{ $payment->revoke_resolved ? '#50cd89' : '#f1416c' }};
                    font-size:1.1rem;">
                </i>
                {{ $payment->revoked_at ? \Carbon\Carbon::parse($payment->revoked_at)->format('d M Y, h:i A') : 'Date N/A' }}
            </div>
            @if($payment->revoke_deadline_at && $payment->revoke_resolved == 0)
                <div class="mt-3">
                    <span
                        class="badge badge-light-warning revoke-countdown-badge"
                        data-payment-id="{{ $payment->id }}"
                        data-deadline="{{ \Carbon\Carbon::parse($payment->revoke_deadline_at)->toIso8601String() }}"
                        style="font-size: 12px; padding: 8px 12px;"
                    >
                        Loading timer...
                    </span>
                </div>
            @endif
        </div>
    </td>

    <td class="text-center">
        <span class="fw-bold text-gray-800">{{ $order->order_id ?? 'N/A' }}</span><br>

        @if($order->team?->team_name)
            <span class="badge badge-light-primary fs-7 fw-bold mb-1">
                {{ $order->team->team_name }}
            </span>
        @endif

        @if($order->semester)
            <span class="badge badge-light-warning fs-7 fw-bold mb-1">
                {{ $order->semester }}
            </span>
        @endif

        @if($order->offer)
            <span class="badge badge-light-success fs-7 fw-bold mb-1">
                {{ $order->offer }}
            </span>
        @endif

        @if($order->feedback_ticket)
            <span class="badge badge-light-danger fs-7 fw-bold mb-1">
                {{ $order->feedback_ticket }}
            </span>
        @endif
    </td>

    <td class="text-center">
        @if($order->user)
            {{ $order->user->name }}<br>
            <span class="badge badge-light-danger fs-7 fw-bold">
                +{{ $order->user->countrycode }} {{ $order->user->mobile_no }}
            </span><br>
            <span class="fs-7 fw-bold">{{ $order->user->email }}</span>
        @else
            <span class="badge badge-light-danger fs-7 fw-bold">User Was Deleted</span>
        @endif
    </td>

    <td class="text-center">
        {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d M Y') : 'N/A' }}
    </td>

    <td class="text-center">
        @if($order->delivery_date)
            {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
        @else
            <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
        @endif

        @if($order->f_delivery_date)
            <br>
            <span class="badge badge-light-warning fs-8 fw-bold mt-1">
                Feedback Date: {{ \Carbon\Carbon::parse($order->f_delivery_date)->format('d M Y') }}
            </span>
        @endif
    </td>

    <td class="text-center" style="width:50px">
        {!! $order->title ?: '<span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>' !!}

        @if($order->semester)
            <br>Semester: ({{ $order->semester }})
        @endif

        @if($order->tech == '1')
            <br><span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>
        @endif

        @if($order->module_code)
            <br><span class="badge badge-light-danger fs-7 fw-bold">{{ $order->module_code }}</span>
        @endif
    </td>

    <td class="text-center">
        <span class="badge badge-light-info fs-7 fw-bold">
            {{ $order->projectstatus ?? 'Not Available' }}
        </span>
    </td>

    <td class="text-center">
        <span class="badge badge-light-warning fs-7 fw-bold">
            {{ $order->status_issue ?? 'Not Available' }}
        </span>
    </td>

    <td class="text-center" style="width:50px">
        {{ $order->pages ?? 'N/A' }}
    </td>

    <td class="text-center" style="width:50px">
        £{{ $order->amount ?? '00.00' }}
    </td>

    <td class="text-center" style="width:50px">
        £{{ $order->received_amount ?? '0.00' }}
    </td>

    <td class="text-center" style="width:50px">
        @if(is_numeric($order->amount) && is_numeric($order->received_amount))
            £{{ $order->amount - $order->received_amount }}
        @else
            <span class="badge badge-light-danger fs-7 fw-bold">N/A</span>
        @endif
    </td>

    <td class="text-center">
        @if($order->writer_name)
            {{ $order->writer_name }}<br>
            @if($order->writer_deadline)
                <span class="badge badge-light-info fs-7 fw-bold">
                    {{ \Carbon\Carbon::parse($order->writer_deadline)->format('d M Y') }}
                </span>
            @endif
        @else
            <span class="badge badge-light-danger fs-7 fw-bold">N/A</span>
        @endif
    </td>

    @if(auth()->user()->role_id == 1)
        <td class="text-center">
            Convert By: {{ $order->l_converted_by ?: 'N/A' }}<br>
            Revoked Amount: £{{ $payment->paid_amount ?? '0.00' }}
        </td>
    @endif
</tr>

<div class="modal fade" id="requestExtensionModal{{ $payment->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('revoke.extension.request', $payment->id) }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white">Request Deadline Extension</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="fw-bold mb-3">
                        Are you sure you want to request admin approval to extend this revoke payment deadline?
                    </p>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Comment</label>
                        <textarea name="comment"
                                  class="form-control"
                                  rows="4"
                                  required
                                  placeholder="Enter reason for deadline extension..."></textarea>
                    </div>

                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-warning">
                        Send Request
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>