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
                                    <i class="fa fa-envelope me-2"></i> {{ mask_email_for_display($user->email ?? '') }}
                                </span>
                                <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <i class="fa fa-phone me-2"></i> {{ mask_phone_for_display($user->countrycode, $user->mobile_no) }}
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

    @if (\App\Services\ReferralCodeService::canView(auth()->user()))
        @php
            $canEditReferral = \App\Services\ReferralCodeService::canEdit(auth()->user());
            $isStaffExec = in_array((int) $user->role_id, \App\Services\ReferralCodeService::STAFF_EXECUTIVE_ROLE_IDS, true);
            $discountPctFormatted = rtrim(rtrim(number_format((float) ($user->referral_discount_percent ?? 10.00), 2, '.', ''), '0'), '.');
            $displayPrefix = $user->referral_prefix ?: $user->referralExecutive?->referral_prefix;
        @endphp
        <div class="card mb-6 mb-xl-9 shadow-sm">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-4 text-gray-800">
                        <i class="fa fa-tags text-primary me-2"></i>Referral Program Details
                    </span>
                    <span class="text-muted mt-1 fw-bold fs-7">
                        {{ $isStaffExec ? 'Marketing Executive Referral Prefix' : 'Client Individual Referral Code & Discount Settings' }}
                    </span>
                </h3>
            </div>
            <div class="card-body pt-2 pb-6">
                @if (session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif

                <div class="row g-4 mb-5">
                    @if ($isStaffExec)
                        <div class="col-md-4">
                            <div class="p-4 rounded bg-light">
                                <div class="text-muted fs-8 fw-bold text-uppercase">3-Char Executive Prefix</div>
                                <div class="fs-3 fw-bolder font-monospace text-dark mt-1">
                                    {{ $user->referral_prefix ?: 'Auto on first conversion' }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-3">
                            <div class="p-4 rounded bg-light-primary">
                                <div class="text-muted fs-8 fw-bold text-uppercase">Referral Code</div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    @if (!empty($user->referral_code))
                                        <span class="fs-3 fw-bolder font-monospace text-primary">{{ $user->referral_code }}</span>
                                        <button type="button" class="btn btn-icon btn-sm btn-light p-1"
                                            title="Copy Referral Code"
                                            onclick="window.copyContactText ? window.copyContactText(@js($user->referral_code), this) : navigator.clipboard.writeText(@js($user->referral_code));">
                                            <i class="fa fa-copy text-primary fs-7"></i>
                                        </button>
                                    @else
                                        <span class="text-muted fw-bold fs-6">Not generated yet</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-4 rounded bg-light-success">
                                <div class="text-muted fs-8 fw-bold text-uppercase">Discount Percentage</div>
                                <div class="fs-3 fw-bolder text-success mt-1">{{ $discountPctFormatted }}% OFF</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-4 rounded bg-light">
                                <div class="text-muted fs-8 fw-bold text-uppercase">Marketing Executive</div>
                                <div class="fs-5 fw-bolder text-dark mt-1">
                                    {{ $user->referralExecutive?->name ?? 'System' }}
                                    @if (!empty($displayPrefix))
                                        <span class="badge badge-light-dark font-monospace ms-1">{{ $displayPrefix }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-4 rounded bg-light">
                                <div class="text-muted fs-8 fw-bold text-uppercase">Generated On</div>
                                <div class="fs-6 fw-bold text-dark mt-1">
                                    {{ $user->referral_code_generated_at ? $user->referral_code_generated_at->format('d M Y, h:i A') : '—' }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if ($canEditReferral)
                    <form method="POST" action="{{ route('user.referral.update', $user->id) }}" class="border-top pt-5">
                        @csrf
                        <div class="row g-4 align-items-end">
                            @if ($isStaffExec)
                                <div class="col-md-4">
                                    <label class="form-label fw-bold required">Executive 3-Char Prefix</label>
                                    <input type="text" name="referral_prefix" maxlength="3"
                                        class="form-control form-control-solid text-uppercase font-monospace"
                                        placeholder="e.g. USK" value="{{ old('referral_prefix', $user->referral_prefix) }}">
                                </div>
                            @else
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Referral Code (5-10 chars)</label>
                                    <input type="text" name="referral_code" maxlength="10"
                                        class="form-control form-control-solid text-uppercase font-monospace"
                                        placeholder="e.g. USK49M" value="{{ old('referral_code', $user->referral_code) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold required">Discount (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" name="referral_discount_percent"
                                        class="form-control form-control-solid"
                                        value="{{ old('referral_discount_percent', $user->referral_discount_percent !== null ? number_format((float) $user->referral_discount_percent, 2, '.', '') : '10.00') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Marketing Executive</label>
                                    <select name="referral_executive_id" class="form-select form-select-solid">
                                        <option value="">System / Default (AIN)</option>
                                        @foreach (($executives ?? collect()) as $exec)
                                            <option value="{{ $exec->id }}" @if ((int) old('referral_executive_id', $user->referral_executive_id) === (int) $exec->id) selected @endif>
                                                {{ $exec->name }}@if (!empty($exec->referral_prefix)) ({{ $exec->referral_prefix }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100 fw-bold">Save Referral</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif

</div>
@endsection