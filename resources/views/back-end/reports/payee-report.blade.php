@extends('layouts.app')

@section('content')

<style>
    .order-summary-footer-wrapper {
        width: 100%;
        position: sticky;
        bottom: 0;
        background: #ffffff;
        border-top: 1px solid #e4e6ef;
        padding: 10px 15px;
        z-index: 10;
    }

    .order-summary-footer {
        display: flex;
        justify-content: space-evenly;
        align-items: center;
        gap: 15px;
    }

    /* Card style */
    .summary-box {
        min-width: 130px;
        text-align: center;
        padding: 6px 10px;
        border-radius: 8px;
        background: #f5f8fa;
        border: 1px solid #e4e6ef;
    }

    .summary-box span {
        display: block;
        font-size: 11px;
        color: #7e8299;
        margin-bottom: 2px;
    }

    .summary-box strong {
        font-size: 14px;
        font-weight: 600;
        color: #3f4254;
    }

    /* subtle color indicators only */
    .summary-box.amount strong {
        color: #009ef7;
    }

    .summary-box.paid strong {
        color: #50cd89;
    }

    .summary-box.due strong {
        color: #f1416c;
    }

    /* Sort Icon Styles - scoped to payee-report only */
    .sort-th-link-pr {
        color: #5e6278;
        font-weight: 700;
        font-size: 12px;
        text-decoration: none;
        transition: color 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .sort-th-link-pr:hover {
        color: #009ef7;
    }

    .sort-icon-wrap-pr {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        line-height: 1;
        gap: 1px;
    }

    .sort-icon-wrap-pr .si-up,
    .sort-icon-wrap-pr .si-down {
        opacity: 0.22;
        display: block;
    }

    .sort-icon-wrap-pr.sort-asc .si-up {
        opacity: 1;
        color: #009ef7;
    }

    .sort-icon-wrap-pr.sort-desc .si-down {
        opacity: 1;
        color: #009ef7;
    }
</style>

<div class="card card-flush mb-5">
    <div class="card-header">
        <h3 class="card-title fw-bold">Payment Report</h3>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('reports.payee') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date"
                        name="from_date"
                        value="{{ $fromDate }}"
                        class="form-control form-control-solid">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date"
                        name="to_date"
                        value="{{ $toDate }}"
                        class="form-control form-control-solid">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Search Payee Name</label>
                    <input type="text"
                        name="payee_name"
                        value="{{ $searchPayee }}"
                        class="form-control form-control-solid"
                        placeholder="Search Payee Name">
                </div>

                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                        <a href="{{ route('reports.payee') }}" class="btn btn-light-danger w-100 text-center">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-flush">
    <div class="card-body pt-5">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead>
                    <tr class="fw-bold bg-light text-dark">
                        <th class="w-80px text-center">Sr. No.</th>
                        <th>Payee Name</th>
                        <th class="text-center w-200px">Total Payments</th>
                        <th class="text-end w-200px">
                            @php
                                $prSortDir = request('sort_dir', 'desc');
                                $prNextDir = $prSortDir === 'desc' ? 'asc' : 'desc';
                                $prQuery = array_merge(request()->query(), ['sort_dir' => $prNextDir]);
                                $prSortUrl = url()->current() . '?' . http_build_query($prQuery);
                            @endphp
                            <a href="{{ $prSortUrl }}" class="sort-th-link-pr">
                                Total Paid Amount
                                <span class="sort-icon-wrap-pr {{ $prSortDir === 'asc' ? 'sort-asc' : 'sort-desc' }}">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" class="si-up" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5L5 12H19L12 5Z" fill="currentColor"/>
                                    </svg>
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" class="si-down" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 19L5 12H19L12 19Z" fill="currentColor"/>
                                    </svg>
                                </span>
                            </a>
                        </th>
                        <th class="text-center w-150px">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($payees as $payee)
                        <tr>
                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-gray-800">{{ $payee->payee_name }}</td>
                            <td class="text-center">
                                <span class="badge badge-light-primary fs-6 fw-bolder">{{ $payee->total_payments }}</span>
                            </td>
                            <td class="text-end fw-bolder text-success">
                                £{{ number_format($payee->total_paid_amount, 2) }}
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light-primary fw-bold" onclick="togglePaymentDetails('details-{{ $loop->index }}')">
                                    <i class="fa fa-eye me-1"></i>
                                </button>
                            </td>
                        </tr>

                        <tr id="details-{{ $loop->index }}" class="payment-detail-row" style="display:none; background-color: #fcfcfc;">
                            <td colspan="5" class="p-4">
                                <div class="card card-flush border">
                                    <div class="card-header min-h-auto pt-3 pb-3 bg-light-dark">
                                        <h4 class="card-title fw-bold text-gray-800 fs-6">
                                            Payment logs for {{ $payee->payee_name }}
                                        </h4>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-row-bordered table-row-gray-200 align-middle gs-4 gy-3 mb-0">
                                            <thead>
                                                <tr class="fw-bold fs-7 text-gray-700 bg-light-secondary">
                                                    <th>Sr.</th>
                                                    <th>Order ID</th>
                                                    <th class="text-end">Paid Amount</th>
                                                    <th>Payment Date</th>
                                                    <th>Reference</th>
                                                    <th>Company Account</th>
                                                    <th>Updated By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($payee->payments as $payment)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            @if($payment->order)
                                                                <a href="{{ route('orders.edit', $payment->order->id) }}" target="_blank" class="fw-bold text-primary">
                                                                    {{ $payment->order->order_id }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end fw-bold text-success">
                                                            £{{ number_format($payment->paid_amount, 2) }}
                                                        </td>
                                                        <td>{{ $payment->payment_date }}</td>
                                                        <td>{{ $payment->reference ?: '-' }}</td>
                                                        <td>{{ $payment->company_accounts ?: '-' }}</td>
                                                        <td>{{ $payment->payment_update_by ?: '-' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">No individual payment logs found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                No records found matching the criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="order-summary-footer-wrapper">
            <div class="order-summary-footer">
                <div class="summary-box amount">
                    <span>Total Payments</span>
                    <strong id="total-payments">
                        {{ $grandTotalPayments }}
                    </strong>
                </div>
                <div class="summary-box paid">
                    <span>Total Paid Amount</span>
                    <strong id="total-paid">
                        £{{ number_format($grandTotalPaidAmount, 2) }}
                    </strong>
                </div>
                <!-- <div class="summary-box due">
                    <span>Total Payees</span>
                    <strong id="total-payees">
                        {{ $payees->total() }}
                    </strong>
                </div> -->
            </div>
        </div>

        <div class="mt-4 px-5">
            {{ $payees->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>

<script>
function togglePaymentDetails(rowId) {
    $('.payment-detail-row').not('#' + rowId).hide();
    $('#' + rowId).toggle();
}
</script>

@endsection
