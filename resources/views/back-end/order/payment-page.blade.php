@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="modal-content rounded">        
        <div class="modal-body scroll-y px-10 px-lg-15 pt-10 pb-15">
            @if(request()->has('deleted_id'))
                <div class="alert alert-success alert-dismissible fade show mb-5" role="alert">
                    <span class="fw-bold">Payment #{{ request('deleted_id') }} {{ request('deleted_amount') ? '(£' . request('deleted_amount') . ')' : '' }} deleted successfully. Showing remaining payments for this order.</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success') || session('Success'))
                <div class="alert alert-success alert-dismissible fade show mb-5" role="alert">
                    <span class="fw-bold">{{ session('success') ?: session('Success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error') || session('Error'))
                <div class="alert alert-danger alert-dismissible fade show mb-5" role="alert">
                    <span class="fw-bold">{{ session('error') ?: session('Error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="mb-8">
                <h1 class="mb-3">{{ isset($editPayment) ? 'Edit Payment' : 'Add Payment' }}</h1>
                <div class="text-muted fw-bold fs-5" style="display: flex; align-items: center; justify-content: space-between;">
                    <a href="javascript:void(0)" style="cursor: default" class="mb-0">{{ $order->order_id }}</a>
                    <a href="javascript:void(0)" style="cursor: default" class="fw-bolder link-primary">
                        Status :
                        <span class="label label-primary" style="background-color:green; color:white; padding:3px 10px; border-radius:30px">
                            {{ $order->projectstatus }}
                        </span>
                    </a>
                </div>
            </div>

            <!-- Payment Table -->
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle gs-0 gy-3 dark-bordered-table">
                        <thead class="p-2">
                            <tr class="fw-bolder text-dark bg-light">
                                <th class="text-center px-2">SR</th>
                                <th>Payment Date</th>
                                <th>Amount</th>
                                <th>Payee Name</th>
                                <th>Account</th>
                                <th>References</th>
                                <th>Receipt</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($order->payment->count())
                                @foreach($order->payment as $payment)
                                    <tr @if(isset($editPayment) && $editPayment->id == $payment->id) style="background-color: #f1faff;" @endif>
                                        <td class="text-center px-2">
                                            <span class="fw-bolder">{{ $loop->iteration }}</span><br>
                                            <span class="badge badge-light-dark fs-9">#{{ $payment->id }}</span>
                                        </td>
                                        <td>{{ $payment->payment_date }}</td>
                                        <td>
                                            <span class="fw-bold">£{{ $payment->paid_amount }}</span>
                                            @if(isset($editPayment) && $editPayment->id == $payment->id)
                                                <span class="badge badge-light-primary fs-9 ms-1">Editing</span>
                                            @endif
                                        </td>
                                        <td>{{ $payment->payee_name }}</td>
                                        <td>{{ $payment->company_accounts }}</td>
                                        <td>{{ $payment->reference }}</td>
                                        <td>
                                            @if($payment->screenshot)
                                                <a href="{{ asset($payment->screenshot) }}" target="_blank" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" title="View Receipt">
                                                    <i class="fa fa-image text-success fs-4"></i>
                                                </a>
                                            @else
                                                <span class="text-muted fs-8">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.payment.form', ['orderId' => $order->id, 'paymentId' => $payment->id]) }}"
                                            class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary btn-color-dark">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if(Auth::user()->role_id == 1)
                                                <a href="javascript:void(0);" 
                                                   class="btn btn-sm btn-icon btn-bg-light btn-active-color-danger btn-color-dark delete-payment"
                                                   onclick="event.preventDefault(); event.stopPropagation(); confirmDeletePayment('{{ $payment->id }}', '{{ $payment->paid_amount }}', '{{ $order->id }}')"
                                                   data-payment-id="{{ $payment->id }}"
                                                   data-amount="{{ $payment->paid_amount }}"
                                                   title="Delete Payment">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                             @else
                                 <tr><td colspan="8" class="text-center">No payments found.</td></tr>
                             @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="mt-5 p-5">
                <form id="paymentForm"
                      method="POST"
                      action="{{ isset($editPayment)
                          ? route('orders.payment.form.update', ['orderId' => $order->id, 'paymentId' => $editPayment->id])
                          : route('orders.payment.form.store', ['orderId' => $order->id]) }}">

                    @csrf
                    @if(isset($editPayment)) @method('PUT') @endif

                    @php
                        $extraPrice = $order->additionals ? $order->additionals->sum('additional_price') : 0;
                    @endphp
                    <h4 class="text-center mb-4">
                        {{ isset($editPayment) ? 'Update Payment' : 'Make Payment' }}
                        @if(!isset($editPayment))
                            (Due: {{ ($order->amount + $extraPrice) - $order->received_amount }} £)
                        @endif
                    </h4>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Date and Time</label>
                            <input type="text" name="payment_date" class="form-input"
                                   value="{{ isset($editPayment) ? $editPayment->payment_date : now()->format('l d F Y h:i A') }}"
                                   readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Paid Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-input" required
                                value="{{ $editPayment->paid_amount ?? '' }}"
                                {{ isset($editPayment) ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payee Name</label>
                            <input type="text" name="payee_name" class="form-input"
                                   value="{{ $editPayment->payee_name ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Account</label>
                            <select name="company_accounts" class="select-form" required>
                                <option disabled selected value="">Select a Company Account</option>
                                @foreach(['HDFC', 'Native', 'PayPal', 'Skydo', 'Banking Circle', 'Wallet', 'Other'] as $option)
                                    <option value="{{ $option }}"
                                            {{ isset($editPayment) && $editPayment->company_accounts == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Message / Reference</label>
                            <textarea name="message" rows="4" class="form-input" required>{{ $editPayment->reference ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" onclick="submitForm(this)" class="btn btn-primary">
                            {{ isset($editPayment) ? 'Update Payment' : 'Add Payment' }}
                        </button>
                    </div>
                </form>

                <script>
                    function submitForm(btn) {
                        var form = btn.form;
                        if (form.checkValidity()) {
                            btn.disabled = true;
                            btn.innerText = 'Submitting...';
                            form.submit();
                        } else {
                            form.reportValidity();
                        }
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<!-- Script to handle SweetAlert Delete Payment -->
<script>
    var isDeletingPayment = false;

    function confirmDeletePayment(paymentId, amount, orderId) {
        if (!paymentId || isDeletingPayment) return;

        Swal.fire({
            title: 'Are you sure?',
            text: 'Are You Sure To Delete Payment Deatail !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                isDeletingPayment = true;

                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while the payment detail is being deleted.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '{{ route('orderpayments.delete', ['id' => '__paymentId__']) }}'.replace('__paymentId__', paymentId),
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}',
                        order_id: orderId,
                        amount: amount || 0
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        try {
                            localStorage.setItem('order_payment_synced', Date.now().toString());
                        } catch (e) {}

                        Swal.fire({
                            title: 'Deleted!',
                            text: (response && response.message) ? response.message : 'Payment detail deleted successfully.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '{{ route('orders.payment.form', ['orderId' => $order->id]) }}?deleted_id=' + encodeURIComponent(paymentId) + '&deleted_amount=' + encodeURIComponent(amount);
                        });
                    },
                    error: function (xhr) {
                        isDeletingPayment = false;
                        var errMsg = 'Failed to delete payment.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            title: 'Error!',
                            text: errMsg,
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }

    $(document).on('click', '.delete-payment', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const paymentId = $(this).attr('data-payment-id');
        const amount = $(this).attr('data-amount') || 0;
        confirmDeletePayment(paymentId, amount, '{{ $order->id }}');
    });
</script>
{{-- mk 6/10/2026: Set sync flag on payment success so orders tab auto-refreshes with updated paid/due amount --}}
@if(session('success') || session('Success'))
<script>
    try {
        localStorage.setItem('order_payment_synced', Date.now().toString());
    } catch(e) {}
</script>
@endif
<style>
    .dark-bordered-table th,
    .dark-bordered-table td,
    .dark-bordered-table {
        border: 1px solid #000 !important;
    }

    .form-input {
        display: block;
        width: 100%;
        padding: 0.65rem 0.9rem;
        font-size: 1rem;
        font-weight: 400;
        min-height: 40px;
        color: #1a1a1a;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid gray;
        border-radius: 0.5rem;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    /* Focus State */
    .form-input:focus {
        color: #1a1a1a;
        background-color: #fff;
        border-color: #18336c;
        /* your theme color */
        outline: 0;
        box-shadow: 0 0 0 0.15rem rgba(24, 51, 108, 0.25);
    }

    /* Active State (when clicked) */
    .form-input:active {
        border-color: #18336c;
    }

    /* Disabled State */
    .form-input:disabled,
    .form-input[readonly] {
        background-color: #e9ecef;
        opacity: 1;
        cursor: not-allowed;
    }

    /* Invalid/Error State */
    .form-input.is-invalid {
        border-color: #dc3545;
        padding-right: 2.25rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='0 0 12 12'%3E%3Cpath d='M6.5 0a.5.5 0 0 0-1 0v5a.5.5 0 0 0 1 0V0zm-.5 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
    }

    .form-input::placeholder {
        color: #6c757d;
        opacity: 1;
    }

    .select-form {
        display: block;
        width: 100%;
        padding: 0.65rem 0.9rem;
        font-size: 1rem;
        font-weight: 400;
        min-height: 40px;
        color: #1a1a1a;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid gray;
        border-radius: 0.5rem;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    /* Focus State */
    .select-form:focus {
        color: #1a1a1a;
        background-color: #fff;
        border-color: #18336c;
        /* your theme color */
        outline: 0;
        box-shadow: 0 0 0 0.15rem rgba(24, 51, 108, 0.25);
    }

    /* Active State (when clicked) */
    .select-form:active {
        border-color: #18336c;
    }

    /* Disabled State */
    .select-form:disabled,
    .select-form[readonly] {
        background-color: #e9ecef;
        opacity: 1;
        cursor: not-allowed;
    }

    /* Invalid/Error State */
    .select-form.is-invalid {
        border-color: #dc3545;
        padding-right: 2.25rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='0 0 12 12'%3E%3Cpath d='M6.5 0a.5.5 0 0 0-1 0v5a.5.5 0 0 0 1 0V0zm-.5 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
    }

    .select-form::placeholder {
        color: #6c757d;
        opacity: 1;
    }
</style>
@endsection
