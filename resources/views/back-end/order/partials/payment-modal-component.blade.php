@php
    $extraPrice = $order->additionals ? $order->additionals->sum('additional_price') : 0;
    $totalAmount = (float) ($order->amount + $extraPrice);
    $receivedAmount = (float) $order->received_amount;
    $dueAmount = max(0, $totalAmount - $receivedAmount);
    $roleId = optional(auth()->user())->role_id ?? 0;
@endphp

<div class="p-3 p-md-4" id="waPaymentComponentContainer" data-order-id="{{ $order->id }}">
    <!-- ══════════════════════════════════════════════════
         1. Financial Metrics Summary Cards
    ══════════════════════════════════════════════════ -->
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="p-2 px-3 rounded border bg-white shadow-xs h-100 d-flex flex-column justify-content-center">
                <span class="text-muted fs-8 fw-semibold text-uppercase">Order Code</span>
                <div class="d-flex align-items-center gap-1 mt-1">
                    <strong class="fs-6 text-dark font-monospace">{{ $order->order_id }}</strong>
                    <button type="button" class="btn btn-xs btn-icon btn-light py-0 px-1" onclick="window.crmCopyToClipboard('{{ $order->order_id }}', 'Order code copied!')" title="Copy Order Code">
                        <i class="fa fa-copy text-muted fs-8"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-2 px-3 rounded border bg-white shadow-xs h-100 d-flex flex-column justify-content-center">
                <span class="text-muted fs-8 fw-semibold text-uppercase">Total Amount</span>
                <div class="fs-6 fw-bolder text-dark mt-1">£{{ number_format($totalAmount, 2) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-2 px-3 rounded border bg-white shadow-xs h-100 d-flex flex-column justify-content-center">
                <span class="text-muted fs-8 fw-semibold text-uppercase">Total Received</span>
                <div class="fs-6 fw-bolder text-success mt-1">£{{ number_format($receivedAmount, 2) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-2 px-3 rounded border {{ $dueAmount > 0 ? 'bg-light-danger border-danger' : 'bg-light-success border-success' }} shadow-xs h-100 d-flex flex-column justify-content-center">
                <span class="{{ $dueAmount > 0 ? 'text-danger' : 'text-success' }} fs-8 fw-bold text-uppercase">Remaining Due</span>
                <div class="fs-6 fw-bolder {{ $dueAmount > 0 ? 'text-danger' : 'text-success' }} mt-1">£{{ number_format($dueAmount, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         2. Payment Transaction History Table
    ══════════════════════════════════════════════════ -->
    <div class="mb-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-dark mb-0 fs-7">
                <i class="fa fa-history me-1 text-primary"></i> Payment History
                <span class="badge badge-light-primary ms-1 fs-9">{{ $order->payment ? $order->payment->count() : 0 }} records</span>
            </h6>
            @if($order->payment && $order->payment->count())
                <span class="text-muted fs-9">Status: <span class="badge bg-secondary text-dark fs-9">{{ $order->projectstatus ?: 'Pending' }}</span></span>
            @endif
        </div>
        
        <div class="table-responsive rounded border" style="border-color:#cbd5e1 !important; max-height:190px; overflow-y:auto; background:#fff;">
            <table class="table table-bordered table-hover align-middle mb-0 fs-8" style="border-collapse:collapse; width:100%;">
                <thead class="bg-light fw-bolder text-dark position-sticky top-0" style="background:#f1f5f9 !important; z-index:2; border-bottom:2px solid #cbd5e1;">
                    <tr>
                        <th style="width:36px; text-align:center; padding:6px 8px;">#</th>
                        <th style="min-width:140px; padding:6px 8px;">Date &amp; Time</th>
                        <th style="min-width:90px; padding:6px 8px;">Paid Amount</th>
                        <th style="min-width:110px; padding:6px 8px;">Payee Name</th>
                        <th style="min-width:85px; padding:6px 8px;">Account</th>
                        <th style="padding:6px 8px;">Reference / Note</th>
                        <th style="width:60px; text-align:center; padding:6px 8px;">Receipt</th>
                        @if($roleId == 1)
                            <th style="width:50px; text-align:center; padding:6px 8px;">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($order->payment && $order->payment->count())
                        @foreach($order->payment as $index => $payment)
                            <tr class="{{ $payment->is_revoked ? 'table-danger' : '' }}">
                                <td class="text-center text-muted fw-bold" style="padding:6px 8px;">{{ $index + 1 }}</td>
                                <td class="fw-semibold text-dark fs-8" style="padding:6px 8px;">
                                    {{ $payment->payment_date }}
                                    @if($payment->payment_update_by)
                                        <div class="text-muted fs-9">by {{ $payment->payment_update_by }}</div>
                                    @endif
                                </td>
                                <td style="padding:6px 8px;">
                                    <strong class="text-success fs-8">£{{ number_format((float) $payment->paid_amount, 2) }}</strong>
                                    @if($payment->is_revoked)
                                        <span class="badge badge-danger fs-9 ms-1">Revoked</span>
                                    @endif
                                </td>
                                <td class="text-dark" style="padding:6px 8px;">{{ $payment->payee_name ?: '—' }}</td>
                                <td style="padding:6px 8px;">
                                    <span class="badge badge-light-primary fw-bold fs-9">{{ $payment->company_accounts ?: '—' }}</span>
                                </td>
                                <td class="text-truncate" style="max-width:180px; padding:6px 8px;" title="{{ $payment->reference }}">
                                    {{ $payment->reference ?: '—' }}
                                </td>
                                <td class="text-center" style="padding:6px 8px;">
                                    @if(!empty($payment->screenshot))
                                        <a href="{{ asset($payment->screenshot) }}" target="_blank" class="btn btn-xs btn-icon btn-light-success" title="View Receipt">
                                            <i class="fa fa-file-image-o text-success fs-7"></i>
                                        </a>
                                    @else
                                        <span class="text-muted fs-9">—</span>
                                    @endif
                                </td>
                                @if($roleId == 1)
                                    <td class="text-center" style="padding:6px 8px;">
                                        <button type="button" class="btn btn-xs btn-icon btn-light-danger delete-modal-payment-btn"
                                                data-payment-id="{{ $payment->id }}"
                                                data-order-id="{{ $order->id }}"
                                                title="Delete Payment">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{ $roleId == 1 ? 8 : 7 }}" class="text-center text-muted py-4">
                                <div class="fs-7 text-gray-500 mb-1"><i class="fa fa-folder-open-o fs-3 text-gray-400"></i></div>
                                <div>No payment records found for this order yet.</div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════
         3. Make / Add Payment Form (Compact & Clean)
    ══════════════════════════════════════════════════ -->
    <div class="card p-3 rounded border shadow-xs" style="background:#ffffff; border-color:#cbd5e1 !important;">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-dark mb-0 fs-7">
                <i class="fa fa-plus-circle me-1 text-success"></i> Add Payment Entry
            </h6>
            <span class="fs-8 fw-semibold {{ $dueAmount > 0 ? 'text-danger' : 'text-success' }}">
                {{ $dueAmount > 0 ? 'Remaining Due: £' . number_format($dueAmount, 2) : 'Fully Paid (No Due)' }}
            </span>
        </div>

        <form id="waPaymentAjaxForm" method="POST" action="{{ route('orders.payment.form.store', ['orderId' => $order->id]) }}">
            @csrf
            <div class="row g-2">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Date &amp; Time</label>
                    <input type="text" name="payment_date" class="form-control form-control-sm bg-light fs-8"
                           value="{{ isset($editPayment) ? $editPayment->payment_date : now()->format('l d F Y h:i A') }}" readonly>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Paid Amount (£) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" id="waPaymentAmountInput" class="form-control form-control-sm fs-8 fw-bold text-success"
                           placeholder="0.00" value="{{ $dueAmount > 0 ? $dueAmount : '' }}" required min="0.01" max="{{ $dueAmount > 0 ? $dueAmount : '' }}">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Payee Name</label>
                    <input type="text" name="payee_name" class="form-control form-control-sm fs-8" placeholder="Client / Payer Name" value="{{ optional($order->user)->name ?? '' }}">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Company Account <span class="text-danger">*</span></label>
                    <select name="company_accounts" class="form-select form-select-sm fs-8" required>
                        <option value="" disabled selected>-- Select Account --</option>
                        @foreach(['HDFC', 'Native', 'PayPal', 'Skydo', 'Wallet', 'Other'] as $acc)
                            <option value="{{ $acc }}">{{ $acc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Message / Transaction Reference <span class="text-danger">*</span></label>
                    <textarea name="message" rows="2" class="form-control form-control-sm fs-8" placeholder="Enter bank reference, UTR, transaction ID or payment notes..." required></textarea>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-end gap-2 mt-3 pt-2 border-top">
                <button type="submit" id="waPaymentSubmitBtn" class="btn btn-sm btn-success py-1 px-4 fs-8 fw-bold" style="background:#00a884; border-color:#00a884;">
                    <i class="fa fa-check me-1"></i>Add Payment Entry
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    const form = document.getElementById('waPaymentAjaxForm');
    const submitBtn = document.getElementById('waPaymentSubmitBtn');
    const container = document.getElementById('waPaymentComponentContainer');
    const orderId = container ? container.dataset.orderId : null;

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa fa-check me-1"></i>Add Payment Entry';

                if (data.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(data.message || 'Payment added successfully!');
                    }
                    if (window.reloadWaPaymentComponent && orderId) {
                        window.reloadWaPaymentComponent(orderId);
                    }
                    if (window.fetchOrders) {
                        window.fetchOrders(1);
                    }
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(data.message || 'Failed to add payment.');
                    } else {
                        alert(data.message || 'Failed to add payment.');
                    }
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa fa-check me-1"></i>Add Payment Entry';
                console.error('Error adding payment:', err);
                if (typeof toastr !== 'undefined') {
                    toastr.error('An error occurred while adding payment.');
                }
            });
        });
    }

    // Handle delete payment inside modal
    document.querySelectorAll('.delete-modal-payment-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const paymentId = this.dataset.paymentId;
            const currentOrderId = this.dataset.orderId;
            if (!paymentId || !confirm('Are you sure you want to delete this payment record?')) {
                return;
            }

            const deleteUrl = '{{ url("orderpayments") }}/' + paymentId;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (typeof toastr !== 'undefined') {
                    toastr.success('Payment deleted successfully.');
                }
                if (window.reloadWaPaymentComponent && currentOrderId) {
                    window.reloadWaPaymentComponent(currentOrderId);
                }
                if (window.fetchOrders) {
                    window.fetchOrders(1);
                }
            })
            .catch(err => {
                console.error('Error deleting payment:', err);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to delete payment.');
                }
            });
        });
    });
})();
</script>
