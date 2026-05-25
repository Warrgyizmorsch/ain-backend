@extends('layouts.app') <!-- Yahan apne layout ka naam daalein -->

@section('content')
<div class="container-fluid mt-5">
    
    <!-- USER INFO SECTION -->
    <div class="card mb-6 mb-xl-9 shadow-sm">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                <!-- Avatar -->
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        <div class="symbol-label fs-1 fw-bolder bg-light-primary text-primary" style="font-size: 3rem !important;">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-800 text-hover-primary fs-2 fw-bolder me-1">{{ $user->name ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <i class="fa fa-envelope me-2"></i> {{ $user->email ?? 'N/A' }}
                                </span>
                                <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <i class="fa fa-phone me-2"></i> {{ $user->countrycode ?? '' }} {{ $user->mobile_no ?? 'N/A' }}
                                </span>
                                <span class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                    <i class="fa fa-globe me-2"></i> {{ $user->countrycode ? 'Code: ' . $user->countrycode : 'Unknown' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STATS CARDS SECTION -->
    <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
        
        <!-- Total Orders Card -->
        <div class="col-md-3">
            <div class="card bg-light-primary border-primary border border-dashed">
                <div class="card-body py-8 text-center">
                    <i class="fa fa-shopping-cart fs-2x text-primary mb-3"></i>
                    <div class="fs-2 fw-bolder text-dark">{{ $user->orders_count ?? 0 }}</div>
                    <div class="fw-bold text-muted fs-5">Total Orders</div>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="col-md-3">
            <div class="card bg-light-success border-success border border-dashed">
                <div class="card-body py-8 text-center">
                    <i class="fa fa-dollar-sign fs-2x text-success mb-3"></i>
                    <div class="fs-2 fw-bolder text-dark">£{{ number_format($totalRevenue, 2) }}</div>
                    <div class="fw-bold text-muted fs-5">Total Revenue</div>
                </div>
            </div>
        </div>

        <!-- Total Tickets Card -->
        <div class="col-md-3">
            <div class="card bg-light-warning border-warning border border-dashed">
                <div class="card-body py-8 text-center">
                    <i class="fa fa-ticket-alt fs-2x text-warning mb-3"></i>
                    <div class="fs-2 fw-bolder text-dark">{{ $totalTickets }}</div>
                    <div class="fw-bold text-muted fs-5">Total Tickets</div>
                </div>
            </div>
        </div>

        <!-- Solved / Pending Tickets Card -->
        <div class="col-md-3">
            <div class="card bg-light-info border-info border border-dashed">
                <div class="card-body py-8 text-center">
                    <i class="fa fa-check-circle fs-2x text-info mb-3"></i>
                    <div class="fs-2 fw-bolder text-dark">{{ $solvedTickets }} <span class="fs-7 text-muted fw-normal">/ {{ $pendingTickets }} Pending</span></div>
                    <div class="fw-bold text-muted fs-5">Solved Tickets</div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection