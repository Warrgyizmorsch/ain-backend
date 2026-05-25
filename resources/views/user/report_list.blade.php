@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">

    <!-- FILTER FORM SECTION -->
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <form method="GET" action="{{ route('user.report.list') }}" class="row g-3 align-items-end">
                {{-- <input type="hidden" name="tab" value="{{ request('tab', 'all') }}"> --}}

                <div class="col-md-3">
                    <label class="form-label fw-bold">Search User</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, Email or Mobile" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Country</label>
                    <select name="country" class="form-select">
                        <option value="">All Countries</option>
                        @foreach($countryList as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                        <option value="Other" {{ request('country') == 'Other' ? 'selected' : '' }}>Other / Unknown</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Show Entries</label>
                    <select name="per_page" class="form-select" onchange="this.form.submit()">
                        {{-- <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option> --}}
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="80" {{ request('per_page') == 80 ? 'selected' : '' }}>80</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary me-2"><i class="fa fa-search"></i> Search</button>
                    <a href="{{ route('user.report.list') }}" class="btn btn-light-danger"><i class="fa fa-times"></i> Reset</a>
                    
                </div>
                
            </form>
        </div>
    </div>

    <!-- TABLE SECTION -->
    <div class="card card-flush shadow-sm">

        <!-- TABS SECTION -->
        {{-- <div class="card-header pt-7 pb-0 border-0">
            <div class="d-flex align-items-center">
                <a href="{{ request()->fullUrlWithQuery(['tab' => 'all']) }}" class="text-decoration-none me-6 {{ $tab == 'all' ? 'text-primary fw-bolder' : 'text-muted fw-bold text-hover-primary' }} fs-5">
        All <span class="badge badge-light-primary fw-bolder ms-1">{{ $countAll }}</span>
        </a>

        <a href="{{ request()->fullUrlWithQuery(['tab' => 'new']) }}" class="text-decoration-none me-6 {{ $tab == 'new' ? 'text-primary fw-bolder' : 'text-muted fw-bold text-hover-primary' }} fs-5">
            New <span class="badge badge-light-primary fw-bolder ms-1">{{ $countNew }}</span>
        </a>

        <a href="{{ request()->fullUrlWithQuery(['tab' => 'retained']) }}" class="text-decoration-none me-6 {{ $tab == 'retained' ? 'text-primary fw-bolder' : 'text-muted fw-bold text-hover-primary' }} fs-5">
            Retained <span class="badge badge-light-primary fw-bolder ms-1">{{ $countRetained }}</span>
        </a>
    </div>
</div> --}}

<div class="card-body pt-5">
    <div class="table-responsive">
        <table class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bolder text-center">
            <thead class="fs-7 text-gray-400 text-uppercase">
                <tr>
                    <th class="text-start min-w-250px">User Info</th>

                    <!-- Total Orders -->
                    <th class="min-w-100px">
                        @php $orderSort = (request('sort_by') == 'orders_count' && request('order') == 'desc') ? 'asc' : 'desc'; @endphp
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'orders_count', 'order' => $orderSort]) }}" class="text-gray-400 text-hover-primary">
                            Total Orders
                            <i class="fa fa-sort{{ request('sort_by') == 'orders_count' ? '-' . (request('order') == 'asc' ? 'up' : 'down') : '' }} ms-1"></i>
                        </a>
                    </th>

                    <!-- Total Revenue -->
                    @if(auth()->user()->role_id == 1)
                    <th class="min-w-125px">
                        @php $revSort = (request('sort_by') == 'total_revenue' && request('order') == 'desc') ? 'asc' : 'desc'; @endphp
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'total_revenue', 'order' => $revSort]) }}" class="text-gray-400 text-hover-primary">
                            Total Revenue
                            <i class="fa fa-sort{{ request('sort_by') == 'total_revenue' ? '-' . (request('order') == 'asc' ? 'up' : 'down') : '' }} ms-1"></i>
                        </a>
                    </th>
                    @endif

                    <!-- Total Tickets -->
                    <th class="min-w-100px">
                        @php $ticketSort = (request('sort_by') == 'total_tickets' && request('order') == 'desc') ? 'asc' : 'desc'; @endphp
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'total_tickets', 'order' => $ticketSort]) }}" class="text-gray-400 text-hover-primary">
                            Total Tickets
                            <i class="fa fa-sort{{ request('sort_by') == 'total_tickets' ? '-' . (request('order') == 'asc' ? 'up' : 'down') : '' }} ms-1"></i>
                        </a>
                    </th>

                    <!-- Failed Orders (Alias match with controller) -->
                    <th class="min-w-100px">
                        @php $failedSort = (request('sort_by') == 'failed_orders_count' && request('order') == 'desc') ? 'asc' : 'desc'; @endphp
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'failed_orders_count', 'order' => $failedSort]) }}" class="text-gray-400 text-hover-primary">
                            Failed Orders
                            <i class="fa fa-sort{{ request('sort_by') == 'failed_orders_count' ? '-' . (request('order') == 'asc' ? 'up' : 'down') : '' }} ms-1"></i>
                        </a>
                    </th>

                    <th class="min-w-150px">
                        @php
                            $ticketFailedSort = (
                                request('sort_by') == 'tickets_failed_total'
                                && request('order') == 'desc'
                            ) ? 'asc' : 'desc';
                        @endphp
                        <a href="{{ request()->fullUrlWithQuery([
                                'sort_by' => 'tickets_failed_total',
                                'order' => $ticketFailedSort
                            ]) }}"
                        class="text-gray-400 text-hover-primary">
                            Tickets + Failed
                            <i class="fa fa-sort{{ request('sort_by') == 'tickets_failed_total'
                                ? '-' . (request('order') == 'asc' ? 'up' : 'down')
                                : '' }} ms-1"></i>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody class="fs-6">
                @forelse($users as $user)
                <tr>
                    <td class="text-start">
                        <div class="d-flex flex-column">
                            <a href="#" class="text-gray-800 text-hover-primary mb-1 fs-5">{{ $user->name ?? 'N/A' }}</a>
                            <span class="text-muted fs-7"><i class="fa fa-envelope text-primary"></i> {{ $user->email ?? 'N/A' }}</span>
                            <span class="text-muted fs-7"><i class="fa fa-phone text-success"></i> {{ $user->countrycode }} {{ $user->mobile_no ?? 'N/A' }}</span>
                            <span class="text-muted fs-7">
                                <i class="fa fa-globe text-info"></i>
                                {{ isset($codeToCountry[$user->countrycode]) ? $codeToCountry[$user->countrycode] : 'Unknown' }}
                            </span>
                            @php
                                $count = $user->orders_count;
                                
                                if($count > 10) { 
                                    $class = "badge-light-success"; $label = "Loyal Customer"; 
                                } elseif($count >= 4) { 
                                    $class = "badge-light-warning"; $label = "Repeated"; 
                                } else { 
                                    $class = "badge-light-info"; $label = "Beginner"; 
                                }
                            @endphp
                            <span class="badge {{ $class }} fw-bold fs-8" style="width: fit-content;">{{ $label }}</span>
                        </div>
                    </td>
                    <td><span class="badge badge-circle badge-light-primary fw-bold fs-5 px-3 py-2">{{ $user->orders_count ?? 0 }}</span></td>
                    @if(auth()->user()->role_id == 1)
                    <td><span class="text-success fw-bolder fs-5">£{{ number_format($user->total_revenue ?? 0, 2) }}</span></td>
                    @endif
                    <td><span class="badge badge-light-warning fw-bolder fs-6 px-3 py-2">{{ $user->total_tickets ?? 0 }}</span></td>
                    <td><span class="badge badge-light-danger fw-bolder fs-6 px-3 py-2">{{ $user->failed_orders_count ?? 0 }}</span></td>
                    <td>
                        <span class="badge badge-light-primary fw-bolder fs-6 px-3 py-2">
                            {{ ($user->total_tickets ?? 0) + ($user->failed_orders_count ?? 0) }}
                        </span>
                    </td>
                    {{-- <td>
                                    @php $pendingTickets = ($user->total_tickets ?? 0) - ($user->solved_tickets ?? 0); @endphp
                                    <span class="badge badge-light-danger fw-bolder fs-6 px-3 py-2">{{ $pendingTickets }}</span>
                    </td> --}}
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">No Users Found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $users->links() }}
    </div>
</div>
    @if(auth()->user()->role_id == 1)
    <div class="order-summary-footer-wrapper">
        <div class="order-summary-footer">

            <div class="summary-box amount">
                <span>Total Orders</span>
                <strong>{{ number_format($totalOrders) }}</strong>
            </div>

            <div class="summary-box paid">
                <span>Total Revenue</span>
                <strong>£{{ number_format($totalRevenue, 2) }}</strong>
            </div>

            <div class="summary-box due">
                <span>Total Tickets</span>
                <strong>{{ number_format($totalTickets) }}</strong>
            </div>

            <div class="summary-box due">
                <span>Total Failed Orders</span>
                <strong>{{ number_format($totalFailedOrders) }}</strong>
            </div>

            <div class="summary-box due">
                <span>Total Tickets + Failed Orders</span>
                <strong>{{ number_format($totalTicketsAndFailed) }}</strong>
            </div>

        </div>
    </div>
    @endif
</div>
</div>

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
    </style>
@endsection