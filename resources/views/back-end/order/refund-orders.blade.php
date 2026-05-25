@extends('layouts.app')

@section('content')

<div class="card card-flush">
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('orders.refund.list') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Order ID</label>
                    <input type="text"
                        name="order_id"
                        value="{{ request('order_id') }}"
                        class="form-control form-control-solid"
                        placeholder="Search Order ID">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">User</label>
                    <input type="text" name="user" value="{{ request('user') }}" class="form-control form-control-solid" placeholder="Name / Mobile / Email">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select form-select-solid">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->status }}"
                                {{ request('status') == $status->status ? 'selected' : '' }}>
                                {{ $status->status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date"
                        name="from_date"
                        value="{{ request('from_date') }}"
                        class="form-control form-control-solid">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date"
                        name="to_date"
                        value="{{ request('to_date') }}"
                        class="form-control form-control-solid">
                </div>

                <div class="col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary">
                        Search
                    </button>

                    <a href="{{ route('orders.refund.list') }}" class="btn btn-sm btn-light-danger">
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>
    <div class="card-header align-items-center py-5">
        <div>
            <h3 class="card-title fw-bold text-dark">Looking For Refund Orders</h3>
            <div class="text-muted fw-semibold fs-7">
                Total Orders: {{ $orders->count() }}
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table table-row-dashed align-middle table-row-gray-300 gy-4">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th>#</th>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Project Title</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Received</th>
                        <th>Due</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $key => $order)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>
                                <span class="badge badge-light-primary fw-bold">
                                    {{ $order->order_id }}
                                </span>
                            </td>

                            <td>
                                @if($order->user)
                                    <div class="fw-bold text-gray-800">{{ $order->user->name }}</div>
                                    <div class="text-muted fs-8">{{ $order->user->email }}</div>
                                    <div class="badge badge-light-danger fs-8">
                                        +{{ $order->user->countrycode }} {{ $order->user->mobile_no }}
                                    </div>
                                @else
                                    <span class="badge badge-light-danger">User Deleted</span>
                                @endif
                            </td>

                            <td>{{ $order->title ?? 'N/A' }}</td>

                            <td>
                                <span class="badge badge-light-warning fw-bold">
                                    {{ $order->projectstatus ?? 'N/A' }}
                                </span>
                            </td>

                            <td>{{ $order->amount ?? 0 }}</td>
                            <td>{{ $order->received_amount ?? 0 }}</td>
                            <td>
                                {{ (int)$order->amount - (int)$order->received_amount }}
                            </td>

                            <td>
                                {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d M Y') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-10">
                                No refund orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection