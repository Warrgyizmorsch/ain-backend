@extends('layouts.app')

@section('content')

<style>
    .content { padding: 0 !important; }
    #kt_content { margin-top: -20px; }

    .feedback-card {
        border-radius: 10px;
        border: 1px solid #e4e6ef;
        box-shadow: 0 4px 14px rgba(0,0,0,0.04);
    }

    .feedback-card .card-header {
        min-height: 62px;
        background: #fff;
        border-bottom: 1px solid #edf0f5;
    }

    table thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #f5f8fa !important;
        font-size: 12px;
        text-transform: uppercase;
        color: #5e6278;
        letter-spacing: .3px;
        white-space: nowrap;
    }

    table.table td,
    table.table th {
        border: 1px solid #edf0f5;
        vertical-align: middle;
    }

    tbody tr:hover {
        background: #f8fbff !important;
    }

    .filter-box {
        background: #f9fafb;
        border: 1px solid #e4e6ef;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 18px;
    }

    .user-info-box {
        min-width: 230px;
        max-width: 270px;
        text-align: left;
    }

    .user-info-box strong {
        display: block;
        color: #181c32;
        font-size: 13px;
    }

    .user-info-box span {
        font-size: 12px;
    }

    .title-cell {
        min-width: 260px;
        max-width: 340px;
        text-align: left;
        white-space: normal;
        word-break: break-word;
        line-height: 1.5;
    }

    .order-id-cell,
    .date-cell {
        min-width: 125px;
        white-space: nowrap;
    }

    .feedback-toggle-btn {
        border-radius: 8px;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 700;
        min-width: 70px;
    }

    .btn-feedback-yes {
        background: #e8fff3;
        color: #0f7a3a;
        border: 1px solid #b7f5d1;
    }

    .btn-feedback-no {
        background: #fff4de;
        color: #b45309;
        border: 1px solid #f5d59b;
    }
</style>

<div id="kt_content">

    <div class="card card-xl-stretch mb-5 feedback-card">
        <div class="card-header">
            <h3 class="card-title fw-bolder mb-0">Delivered Order Feedback</h3>
        </div>

        <div class="card-body py-3">

            <form method="GET" action="{{ url()->current() }}" class="filter-box">
                <div class="row g-3 align-items-end">

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Feedback Status</label>
                        <select name="feedback_status" class="form-control">
                            <option value="">All</option>
                            <option value="no" {{ request('feedback_status') == 'no' ? 'selected' : '' }}>No</option>
                            <option value="yes" {{ request('feedback_status') == 'yes' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Order From</label>
                        <input type="date"
                               name="order_date_from"
                               class="form-control"
                               value="{{ request('order_date_from') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Order To</label>
                        <input type="date"
                               name="order_date_to"
                               class="form-control"
                               value="{{ request('order_date_to') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Delivery From</label>
                        <input type="date"
                               name="feedback_date_from"
                               class="form-control"
                               value="{{ request('feedback_date_from') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Delivery To</label>
                        <input type="date"
                               name="feedback_date_to"
                               class="form-control"
                               value="{{ request('feedback_date_to') }}">
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="{{ url()->current() }}" class="btn btn-light-danger">Reset</a>
                    </div>

                </div>
            </form>

            <div id="scroll-order-table" style="max-height: 76vh; overflow-y: auto;">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr class="fw-bolder text-dark bg-light">
                            <th class="text-center">Order ID</th>
                            <th class="text-center">User Details</th>
                            <th class="text-center">Title</th>
                            <th class="text-center">Order Date</th>
                            <th class="text-center">Delivery Date</th>
                            <th class="text-center">Project Status</th>
                            <th class="text-center">Feedback</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data as $item)
                            @php
                                $user = $item['user'];
                                $orders = $item['orders'];
                            @endphp

                            @foreach($orders as $order)
                                @php
                                    $status = strtolower($order->feedback_status ?? '');
                                    $isYes = in_array($status, ['yes', 'completed']);
                                @endphp

                                <tr>
                                    <td class="text-center order-id-cell">
                                        <strong>{{ $order->order_code ?? $order->order_id ?? 'N/A' }}</strong>
                                    </td>

                                    <td class="user-info-box">
                                        @if($user)
                                            <strong>{{ $user->name ?? 'N/A' }}</strong>
                                            <span>{{ $user->email ?? 'N/A' }}</span><br>

                                            <span class="badge badge-light-danger fs-7 fw-bold mt-1">
                                                +{{ $user->countrycode ?? $user->country_code ?? '' }}
                                                {{ $user->mobile_no ?? $user->mobile ?? '' }}
                                            </span>
                                        @else
                                            <span class="badge badge-light-danger">User Deleted</span>
                                        @endif
                                    </td>

                                    <td class="title-cell">
                                        {!! $order->title ?: '<span class="badge badge-light-danger">Not Available</span>' !!}
                                    </td>

                                    <td class="text-center date-cell">
                                        @if($order->order_date)
                                            {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                                        @else
                                            <span class="badge badge-light-danger">N/A</span>
                                        @endif
                                    </td>

                                    <td class="text-center date-cell">
                                        @if($order->delivery_date)
                                            {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
                                        @else
                                            <span class="badge badge-light-danger">N/A</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <span class="badge badge-light-success fs-8 fw-bold">
                                            {{ $order->projectstatus ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <button type="button"
                                                class="btn feedback-toggle-btn {{ $isYes ? 'btn-feedback-yes' : 'btn-feedback-no' }}"
                                                onclick="toggleFeedback({{ $order->id }}, '{{ $isYes ? 'no' : 'yes' }}', this)">
                                            {{ $isYes ? 'Yes' : 'No' }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    No delivered orders found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $data->links() }}
            </div>

        </div>
    </div>
</div>

<script>
    function toggleFeedback(orderId, newStatus, btn) {
        btn.disabled = true;
        btn.innerText = '...';

        fetch("{{ route('orders.mark-feedback') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                order_id: orderId,
                feedback_status: newStatus
            })
        })
        .then(response => response.json())
        .then(response => {
            if (!response.success) {
                alert('Something went wrong.');
                btn.disabled = false;
                btn.innerText = newStatus === 'yes' ? 'No' : 'Yes';
                return;
            }

            if (response.feedback_status === 'yes') {
                btn.innerText = 'Yes';
                btn.classList.remove('btn-feedback-no');
                btn.classList.add('btn-feedback-yes');
                btn.setAttribute('onclick', `toggleFeedback(${response.order_id}, 'no', this)`);
            } else {
                btn.innerText = 'No';
                btn.classList.remove('btn-feedback-yes');
                btn.classList.add('btn-feedback-no');
                btn.setAttribute('onclick', `toggleFeedback(${response.order_id}, 'yes', this)`);
            }

            btn.disabled = false;
        })
        .catch(() => {
            alert('Server error. Please try again.');
            btn.disabled = false;
            btn.innerText = newStatus === 'yes' ? 'No' : 'Yes';
        });
    }
</script>

@endsection