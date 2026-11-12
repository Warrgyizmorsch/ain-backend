@extends('layouts.app')

@section('content')
@php
    $sharedQueryParams = array_filter([
        'search' => $filters['search'] ?? '',
        'executive_id' => $filters['executive_id'] ?? null,
        'referrer_id' => $filters['referrer_id'] ?? null,
        'from_date' => $filters['from_date'] ?? '',
        'to_date' => $filters['to_date'] ?? '',
        'per_page' => ($perPage ?? 25) !== 25 ? $perPage : null,
    ], fn ($v) => $v !== null && $v !== '');

    $hasActiveFilters = !empty($filters['search'])
        || !empty($filters['executive_id'])
        || !empty($filters['referrer_id'])
        || !empty($filters['from_date'])
        || !empty($filters['to_date'])
        || !empty($filters['status']);

    $tabMeta = [
        'clients' => ['label' => 'Clients & Codes', 'icon' => 'fa-users', 'count' => $tabCounts['clients'] ?? 0],
        'orders' => ['label' => 'Referred Orders', 'icon' => 'fa-shopping-bag', 'count' => $tabCounts['orders'] ?? 0],
        'signups' => ['label' => 'Referred Signups', 'icon' => 'fa-user-plus', 'count' => $tabCounts['signups'] ?? 0],
        'executives' => ['label' => 'Executives & Prefixes', 'icon' => 'fa-user-tie', 'count' => $tabCounts['executives'] ?? 0],
        'wallet' => ['label' => 'Wallet Ledger', 'icon' => 'fa-wallet', 'count' => $tabCounts['wallet'] ?? 0],
    ];
@endphp

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">

        {{-- Page Header Card --}}
        <div class="card mb-5 shadow-sm border-0">
            <div class="card-body py-4 px-6 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h1 class="fs-2 fw-bolder text-gray-900 mb-0">
                            <i class="fa fa-tags text-primary me-2"></i>Referral System Control Center
                        </h1>
                        <span class="badge badge-light-danger fw-bold fs-8">Super Admin Only</span>
                    </div>
                    <div class="text-muted fw-semibold fs-7 mt-1">
                        Client referral codes, staff prefixes, friend discounts, referred signups &amp; orders, and 10% wallet bonus ledger.
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-nowrap flex-shrink-0">
                    <button type="button" class="btn btn-sm btn-light-info fw-bold text-nowrap" data-bs-toggle="collapse"
                        data-bs-target="#referralRulesCollapse" aria-expanded="false" aria-controls="referralRulesCollapse">
                        <i class="fa fa-info-circle me-1"></i> How Referral Works
                    </button>
                    <a href="{{ route('admin.referral.export', request()->query()) }}"
                        class="btn btn-sm btn-success fw-bold text-nowrap">
                        <i class="fa fa-file-csv me-1"></i> Export {{ $tabMeta[$activeTab]['label'] ?? 'CSV' }}
                    </a>
                </div>
            </div>
        </div>

        @if (!$schemaReady['users'] || !$schemaReady['orders'] || !$schemaReady['wallet'])
            <div class="alert alert-warning d-flex align-items-center mb-5" role="alert">
                <i class="fa fa-exclamation-triangle me-3 fs-4"></i>
                <div>
                    <strong>Schema Notice:</strong> One or more referral database columns/tables are not present yet
                    (Users: {{ $schemaReady['users'] ? 'OK' : 'Missing' }},
                    Orders: {{ $schemaReady['orders'] ? 'OK' : 'Missing' }},
                    Wallet Ledger: {{ $schemaReady['wallet'] ? 'OK' : 'Missing' }}).
                    Run migrations to enable full tracking.
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-5" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-5" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Collapsible Quick Reference --}}
        <div class="collapse mb-5" id="referralRulesCollapse">
            <div class="card border border-info border-dashed bg-light-info shadow-sm">
                <div class="card-body py-4 px-6">
                    <h4 class="fw-bolder text-gray-800 mb-3">
                        <i class="fa fa-book-open text-info me-2"></i>Client Individual Referral Code System &mdash; Quick Reference
                    </h4>
                    <div class="row g-3 fs-7 text-gray-700">
                        <div class="col-md-3">
                            <div class="bg-white rounded p-3 h-100 border">
                                <div class="fw-bolder text-primary mb-1">1. Code Auto-Generation</div>
                                Generated once on a client's <strong>first confirmed / converted</strong> non-cancelled order in format
                                <code class="font-monospace">[Client 3-char][Exec 2-char][10]</code> (e.g. <code>CARUS10</code>).
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-white rounded p-3 h-100 border">
                                <div class="fw-bolder text-dark mb-1">2. Executive Prefix Priority</div>
                                Resolved via <code>l_converted_by</code> &rarr; <code>order.created_by</code> &rarr;
                                <code>lead.created_by</code> &rarr; acting staff &rarr; fallback <code>AI</code>.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-white rounded p-3 h-100 border">
                                <div class="fw-bolder text-success mb-1">3. Friend Discount (10% Default)</div>
                                Applied once on friend's first order. Super Admin / Sub Admin can customize each client's code, %, or executive inline.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-white rounded p-3 h-100 border">
                                <div class="fw-bolder text-warning mb-1">4. 10% Referrer Wallet Bonus</div>
                                Credited strictly once when friend's <strong>first genuine order converts</strong>
                                (tracked via order bonus lock + wallet ledger).
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 6 Global KPI Summary Cards --}}
        <div class="row g-3 mb-5">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fw-bold fs-8 text-uppercase">Coded Clients</span>
                            <span class="badge badge-light-primary p-2"><i class="fa fa-key text-primary fs-8"></i></span>
                        </div>
                        <div class="fs-2 fw-bolder text-gray-900">{{ number_format($summary['coded_clients']) }}</div>
                        <div class="text-muted fs-8 mt-1">{{ number_format($summary['custom_discount_clients']) }} custom %</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fw-bold fs-8 text-uppercase">Staff Prefixes</span>
                            <span class="badge badge-light-dark p-2"><i class="fa fa-user-tie text-dark fs-8"></i></span>
                        </div>
                        <div class="fs-2 fw-bolder text-gray-900">
                            {{ number_format($summary['coded_executives']) }}
                            <span class="fs-6 text-muted fw-normal">/ {{ number_format($summary['total_executives']) }}</span>
                        </div>
                        <div class="text-muted fs-8 mt-1">2-char exec prefixes</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fw-bold fs-8 text-uppercase">Referred Signups</span>
                            <span class="badge badge-light-info p-2"><i class="fa fa-user-plus text-info fs-8"></i></span>
                        </div>
                        <div class="fs-2 fw-bolder text-gray-900">{{ number_format($summary['referred_clients']) }}</div>
                        <div class="text-muted fs-8 mt-1">Joined via referral link</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fw-bold fs-8 text-uppercase">Referred Orders</span>
                            <span class="badge badge-light-warning p-2"><i class="fa fa-shopping-bag text-warning fs-8"></i></span>
                        </div>
                        <div class="fs-2 fw-bolder text-gray-900">{{ number_format($summary['referred_orders_total']) }}</div>
                        <div class="text-muted fs-8 mt-1 text-nowrap">
                            <span class="text-success fw-bold">{{ $summary['referred_orders_awarded'] }}</span> paid &middot;
                            <span class="text-warning fw-bold">{{ $summary['referred_orders_pending'] }}</span> pend &middot;
                            <span class="text-danger fw-bold">{{ $summary['referred_orders_cancelled'] }}</span> canc
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fw-bold fs-8 text-uppercase">Referred Revenue</span>
                            <span class="badge badge-light-success p-2"><i class="fa fa-pound-sign text-success fs-8"></i></span>
                        </div>
                        <div class="fs-2 fw-bolder text-gray-900">&pound;{{ number_format($summary['total_referred_revenue'], 2) }}</div>
                        <div class="text-muted fs-8 mt-1">&pound;{{ number_format($summary['total_discount_given'], 2) }} discount</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fw-bold fs-8 text-uppercase">10% Bonus Paid</span>
                            <span class="badge badge-light-success p-2"><i class="fa fa-wallet text-success fs-8"></i></span>
                        </div>
                        <div class="fs-2 fw-bolder text-success">&pound;{{ number_format($summary['total_bonus_credited'], 2) }}</div>
                        <div class="text-muted fs-8 mt-1">Wallet bal: &pound;{{ number_format($summary['total_referrer_wallet'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Workspace Card (Single-Row Tabs + Filters + Clean Table) --}}
        <div class="card shadow-sm border-0">
            <div class="card-header card-header-stretch pt-3 pb-0 px-6 border-bottom">
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-7 fw-bolder flex-nowrap overflow-auto w-100" role="tablist">
                    @foreach ($tabMeta as $tabKey => $meta)
                        <li class="nav-item flex-shrink-0" role="presentation">
                            <a class="nav-link text-active-primary py-3 me-5 text-nowrap {{ $activeTab === $tabKey ? 'active' : '' }}"
                                href="{{ route('admin.referral.system', array_merge(['tab' => $tabKey], $sharedQueryParams)) }}">
                                <i class="fa {{ $meta['icon'] }} me-2 fs-7"></i>{{ $meta['label'] }}
                                <span class="badge badge-sm ms-1 {{ $activeTab === $tabKey ? 'badge-primary' : 'badge-light-secondary text-gray-700' }}">
                                    {{ number_format($meta['count']) }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="card-body py-5 px-6">

                {{-- Active Referrer Filter Pill --}}
                @if ($filteredReferrer)
                    <div class="alert alert-primary d-flex align-items-center justify-content-between flex-wrap gap-2 py-3 px-4 mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa fa-filter text-primary"></i>
                            <span class="fw-bold">
                                Referrer Filter: {{ $filteredReferrer->name }}
                            </span>
                            @if (!empty($filteredReferrer->referral_code))
                                <span class="badge badge-light-primary font-monospace">{{ $filteredReferrer->referral_code }}</span>
                            @endif
                        </div>
                        <a href="{{ route('admin.referral.system', array_merge(['tab' => $activeTab], collect($sharedQueryParams)->except('referrer_id')->all(), !empty($filters['status']) ? ['status' => $filters['status']] : [])) }}"
                            class="btn btn-sm btn-light-danger fw-bold py-1 px-3">
                            <i class="fa fa-times me-1"></i> Clear Referrer Filter
                        </a>
                    </div>
                @endif

                {{-- Global Search & Filter Bar --}}
                <form method="GET" action="{{ route('admin.referral.system') }}" class="bg-light border border-gray-200 rounded p-4 mb-5">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    @if (!empty($filters['referrer_id']))
                        <input type="hidden" name="referrer_id" value="{{ $filters['referrer_id'] }}">
                    @endif

                    <div class="row g-3 align-items-end">
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm form-control-solid bg-white border"
                                placeholder="Name, email, phone, code, order ID..."
                                value="{{ $filters['search'] ?? '' }}">
                        </div>

                        <div class="col-md-6 col-lg-2">
                            <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Executive</label>
                            <select name="executive_id" class="form-select form-select-sm form-select-solid bg-white border">
                                <option value="">All Executives</option>
                                @foreach ($executivesList as $exec)
                                    <option value="{{ $exec->id }}" @selected((int) ($filters['executive_id'] ?? 0) === (int) $exec->id)>
                                        {{ $exec->name }}@if (!empty($exec->referral_prefix)) ({{ $exec->referral_prefix }})@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Status</label>
                            @if ($activeTab === 'clients')
                                <select name="status" class="form-select form-select-sm form-select-solid bg-white border">
                                    <option value="">All Clients</option>
                                    <option value="coded" @selected(($filters['status'] ?? '') === 'coded')>Has Referral Code</option>
                                    <option value="with_referrals" @selected(($filters['status'] ?? '') === 'with_referrals')>Has Signups / Orders</option>
                                    <option value="bonus_earned" @selected(($filters['status'] ?? '') === 'bonus_earned')>Earned Bonus (&gt; &pound;0)</option>
                                    <option value="custom_discount" @selected(($filters['status'] ?? '') === 'custom_discount')>Custom Discount (% &ne; 10%)</option>
                                </select>
                            @elseif ($activeTab === 'orders')
                                <select name="status" class="form-select form-select-sm form-select-solid bg-white border">
                                    <option value="">All Referred Orders</option>
                                    <option value="awarded" @selected(($filters['status'] ?? '') === 'awarded')>Bonus Awarded (10%)</option>
                                    <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending Conversion / Confirm</option>
                                    <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Cancelled / Trash</option>
                                </select>
                            @elseif ($activeTab === 'signups')
                                <select name="status" class="form-select form-select-sm form-select-solid bg-white border">
                                    <option value="">All Referred Signups</option>
                                    <option value="awarded" @selected(($filters['status'] ?? '') === 'awarded')>Bonus Unlocked (10%)</option>
                                    <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending Bonus Conversion</option>
                                    <option value="legacy_ordered" @selected(($filters['status'] ?? '') === 'legacy_ordered')>Ordered (No Code / Legacy)</option>
                                    <option value="no_order" @selected(($filters['status'] ?? '') === 'no_order')>Signed Up Only (No Order)</option>
                                </select>
                            @else
                                <select name="status" class="form-select form-select-sm form-select-solid bg-white border" disabled>
                                    <option value="">All</option>
                                </select>
                            @endif
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fs-8 fw-bold text-gray-700 mb-1">From Date</label>
                                    <input type="date" name="from_date" class="form-control form-control-sm form-control-solid bg-white border"
                                        value="{{ $filters['from_date'] ?? '' }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fs-8 fw-bold text-gray-700 mb-1">To Date</label>
                                    <input type="date" name="to_date" class="form-control form-control-sm form-control-solid bg-white border"
                                        value="{{ $filters['to_date'] ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <div class="d-flex align-items-end gap-2">
                                @if ($activeTab !== 'executives')
                                    <div style="width: 68px;" class="flex-shrink-0">
                                        <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Rows</label>
                                        <select name="per_page" class="form-select form-select-sm form-select-solid bg-white border">
                                            @foreach ([10, 25, 50, 100] as $size)
                                                <option value="{{ $size }}" @selected((int) $perPage === $size)>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <button type="submit" class="btn btn-sm btn-primary fw-bold flex-grow-1 text-nowrap">
                                    <i class="fa fa-search me-1"></i> Filter
                                </button>
                                @if ($hasActiveFilters)
                                    <a href="{{ route('admin.referral.system', ['tab' => $activeTab]) }}"
                                        class="btn btn-sm btn-light-danger fw-bold flex-shrink-0" title="Reset All Filters">
                                        <i class="fa fa-redo"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>

                {{-- TAB 1: CLIENTS & CODES (8 Clean Balanced Columns) --}}
                @if ($activeTab === 'clients')
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-3 gy-3 mb-0">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light fs-8 text-uppercase">
                                    <th class="ps-3 min-w-175px">Client</th>
                                    <th class="min-w-150px">Referral Code &amp; %</th>
                                    <th class="min-w-150px">Executive &amp; Meta</th>
                                    <th class="text-center min-w-70px">Signups</th>
                                    <th class="text-center min-w-90px">Orders (Bonus)</th>
                                    <th class="text-end min-w-110px">Revenue / Disc</th>
                                    <th class="text-end min-w-110px">Earnings / Wallet</th>
                                    <th class="text-end pe-3 min-w-120px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fs-7">
                                @forelse ($clients as $client)
                                    @php
                                        $clientDiscount = rtrim(rtrim(number_format((float) ($client->referral_discount_percent ?? 10.00), 2, '.', ''), '0'), '.');
                                        $clientPrefix = $client->referral_prefix ?: ($client->referralExecutive?->referral_prefix ?? '');
                                        $clientWallet = is_numeric($client->Wallet) ? (float) $client->Wallet : 0.0;
                                    @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <a href="{{ route('user.report', $client->id) }}" class="text-gray-900 fw-bolder text-hover-primary d-block">
                                                {{ $client->name }}
                                            </a>
                                            <div class="text-muted fs-8">{{ mask_email_for_display($client->email) }}</div>
                                            @if (!empty($client->mobile_no))
                                                <div class="text-muted fs-8">{{ mask_phone_for_display($client->countrycode, $client->mobile_no) }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($client->referral_code))
                                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                                    <span class="badge badge-light-primary font-monospace fs-7 fw-bolder">{{ $client->referral_code }}</span>
                                                    <button type="button" class="btn btn-icon btn-sm p-0 border-0 bg-transparent"
                                                        title="Copy Referral Code"
                                                        onclick="window.copyContactText ? window.copyContactText(@js($client->referral_code), this) : navigator.clipboard.writeText(@js($client->referral_code));">
                                                        <i class="fa fa-copy text-muted fs-8"></i>
                                                    </button>
                                                </div>
                                                <div class="mt-1">
                                                    <span class="badge {{ (float) ($client->referral_discount_percent ?? 10) != 10.0 ? 'badge-light-warning' : 'badge-light-success' }} fw-bold fs-8">
                                                        {{ $clientDiscount }}% OFF
                                                    </span>
                                                </div>
                                            @else
                                                <span class="badge badge-light-secondary fs-8">Not Generated</span>
                                                <div class="text-muted fs-8 mt-1">Default: {{ $clientDiscount }}% OFF</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-gray-800">
                                                {{ $client->referralExecutive?->name ?? 'System' }}
                                                @if ($clientPrefix !== '')
                                                    <span class="badge badge-light-dark font-monospace fs-8 ms-1">{{ $clientPrefix }}</span>
                                                @endif
                                            </div>
                                            @if ($client->referral_code_generated_at)
                                                <div class="text-muted fs-8 mt-1">
                                                    Gen: {{ $client->referral_code_generated_at->format('d M Y') }}
                                                </div>
                                            @endif
                                            @if ($client->referralUpdatedBy)
                                                <div class="text-muted fs-8">By: {{ $client->referralUpdatedBy->name }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ((int) ($client->referred_signups_count ?? 0) > 0)
                                                <a href="{{ route('admin.referral.system', ['tab' => 'signups', 'referrer_id' => $client->id]) }}"
                                                    class="badge badge-light-info fw-bolder text-hover-primary fs-7"
                                                    title="View referred signups for this client">
                                                    {{ (int) $client->referred_signups_count }}
                                                </a>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ((int) ($client->referred_orders_count ?? 0) > 0)
                                                <a href="{{ route('admin.referral.system', ['tab' => 'orders', 'referrer_id' => $client->id]) }}"
                                                    class="badge badge-light-primary fw-bolder text-hover-primary fs-7"
                                                    title="View referred orders for this client">
                                                    {{ (int) $client->referred_orders_count }}
                                                    <span class="text-success ms-1">({{ (int) ($client->awarded_bonus_orders_count ?? 0) }})</span>
                                                </a>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <div class="fw-bold text-gray-900">&pound;{{ number_format((float) ($client->referred_orders_revenue ?? 0), 2) }}</div>
                                            <div class="text-muted fs-8">Disc: &pound;{{ number_format((float) ($client->referred_discount_given ?? 0), 2) }}</div>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <div class="fw-bolder text-success">&pound;{{ number_format((float) ($client->total_referral_earnings ?? 0), 2) }}</div>
                                            <div class="text-muted fs-8">Wallet: &pound;{{ number_format($clientWallet, 2) }}</div>
                                        </td>
                                        <td class="text-end pe-3 text-nowrap">
                                            <button type="button" class="btn btn-sm btn-light-warning fw-bold px-2 py-1 me-1 js-sa-edit-referral"
                                                title="Edit Referral Settings"
                                                data-user-id="{{ $client->id }}"
                                                data-user-name="{{ $client->name }}"
                                                data-role-id="{{ (int) $client->role_id }}"
                                                data-referral-code="{{ $client->referral_code ?? '' }}"
                                                data-discount-percent="{{ number_format((float) ($client->referral_discount_percent ?? 10.00), 2, '.', '') }}"
                                                data-executive-id="{{ $client->referral_executive_id ?? '' }}"
                                                data-prefix="{{ $clientPrefix }}">
                                                <i class="fa fa-edit fs-8"></i> Edit
                                            </button>
                                            <a href="{{ route('user.report', $client->id) }}" class="btn btn-sm btn-light-primary fw-bold px-2 py-1"
                                                title="Open Client Profile">
                                                Profile
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-10 text-muted fw-semibold">
                                            No clients match the current referral filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($clients && $clients->hasPages())
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $clients->links('pagination::bootstrap-5') }}
                        </div>
                    @endif

                {{-- TAB 2: REFERRED ORDERS (7 Clean Balanced Columns) --}}
                @elseif ($activeTab === 'orders')
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-3 gy-3 mb-0">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light fs-8 text-uppercase">
                                    <th class="ps-3 min-w-120px">Order</th>
                                    <th class="min-w-160px">Buyer (Friend)</th>
                                    <th class="min-w-150px">Referrer &amp; Code</th>
                                    <th class="min-w-120px">Executive</th>
                                    <th class="text-end min-w-170px">Order Pricing</th>
                                    <th class="min-w-100px">Order Status</th>
                                    <th class="pe-3 min-w-170px">10% Referrer Bonus</th>
                                </tr>
                            </thead>
                            <tbody class="fs-7">
                                @forelse ($orders as $order)
                                    @php
                                        $payable = (float) ($order->amount ?? 0);
                                        $disc = (float) ($order->coupon_discount_amount ?? 0);
                                        $orig = $order->coupon_original_amount !== null
                                            ? (float) $order->coupon_original_amount
                                            : ($payable + $disc);
                                        $st = $orderStatuses[$order->id] ?? ['label' => 'Pending', 'badge' => 'badge-light-secondary', 'bonus_amount' => 0];
                                    @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <a href="{{ route('orders.edit', $order->id) }}" class="text-gray-900 fw-bolder text-hover-primary d-block">
                                                {{ $order->order_id ?: ('#' . $order->id) }}
                                            </a>
                                            <div class="text-muted fs-8">
                                                {{ $order->order_date ?: optional($order->created_at)->format('Y-m-d') }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($order->user)
                                                <a href="{{ route('user.report', $order->user->id) }}" class="text-gray-900 fw-bold text-hover-primary d-block">
                                                    {{ $order->user->name }}
                                                </a>
                                                <div class="text-muted fs-8">{{ mask_email_for_display($order->user->email) }}</div>
                                            @else
                                                <span class="text-muted">User #{{ $order->uid }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->referredByUser)
                                                <a href="{{ route('admin.referral.system', ['tab' => 'orders', 'referrer_id' => $order->referredByUser->id]) }}"
                                                    class="text-gray-900 fw-bold text-hover-primary d-block">
                                                    {{ $order->referredByUser->name }}
                                                </a>
                                            @endif
                                            <span class="badge badge-light-primary font-monospace fs-8 mt-1">
                                                {{ $order->coupon_code ?: ($order->referredByUser?->referral_code ?? '—') }}
                                            </span>
                                        </td>
                                        <td class="fs-8">
                                            <div class="fw-bold text-gray-800">{{ $order->referredByUser?->referralExecutive?->name ?? 'System' }}</div>
                                            @if (!empty($order->referredByUser?->referralExecutive?->referral_prefix ?? $order->referredByUser?->referral_prefix))
                                                <span class="badge badge-light-dark font-monospace mt-1">
                                                    {{ $order->referredByUser?->referralExecutive?->referral_prefix ?: $order->referredByUser?->referral_prefix }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <div class="fw-bolder text-gray-900">&pound;{{ number_format($payable, 2) }} <span class="text-muted fw-normal fs-8">payable</span></div>
                                            <div class="text-muted fs-8">
                                                Orig: &pound;{{ number_format($orig, 2) }} &middot;
                                                <span class="text-success fw-semibold">-&pound;{{ number_format($disc, 2) }}</span>
                                            </div>
                                            <div class="text-muted fs-8">Paid: &pound;{{ number_format((float) ($order->received_amount ?? 0), 2) }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-info fw-bold">{{ $order->projectstatus ?: 'Pending' }}</span>
                                        </td>
                                        <td class="pe-3">
                                            <span class="badge {{ $st['badge'] }} fw-bold d-inline-block">{{ $st['label'] }}</span>
                                            @if (($st['bonus_amount'] ?? 0) > 0)
                                                <div class="fs-8 fw-bolder {{ ($st['code'] ?? '') === 'awarded' ? 'text-success' : 'text-warning' }} mt-1">
                                                    &pound;{{ number_format((float) $st['bonus_amount'], 2) }}
                                                    @if ($order->referral_bonus_awarded_at)
                                                        <span class="text-muted fw-normal">({{ $order->referral_bonus_awarded_at->format('d M Y') }})</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-10 text-muted fw-semibold">
                                            No referred orders found for the current filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($orders && $orders->hasPages())
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    @endif

                {{-- TAB 3: REFERRED SIGNUPS (7 Clean Balanced Columns) --}}
                @elseif ($activeTab === 'signups')
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-3 gy-3 mb-0">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light fs-8 text-uppercase">
                                    <th class="ps-3 min-w-175px">Referred Client</th>
                                    <th class="min-w-130px">Signup Date</th>
                                    <th class="min-w-160px">Referred By</th>
                                    <th class="min-w-120px">Executive</th>
                                    <th class="text-center min-w-100px">Orders (Gen.)</th>
                                    <th class="min-w-160px">Lifecycle Status</th>
                                    <th class="pe-3 min-w-130px">Own Code</th>
                                </tr>
                            </thead>
                            <tbody class="fs-7">
                                @forelse ($signups as $signup)
                                    @php
                                        $totalOrders = (int) ($signup->total_orders_count ?? 0);
                                        $genuineOrders = (int) ($signup->genuine_orders_count ?? 0);
                                        $st = $signupStatuses[$signup->id] ?? ['label' => 'Signed Up Only (No Order)', 'badge' => 'badge-light-secondary'];
                                    @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <a href="{{ route('user.report', $signup->id) }}" class="text-gray-900 fw-bolder text-hover-primary d-block">
                                                {{ $signup->name }}
                                            </a>
                                            <div class="text-muted fs-8">{{ mask_email_for_display($signup->email) }}</div>
                                            @if (!empty($signup->mobile_no))
                                                <div class="text-muted fs-8">{{ mask_phone_for_display($signup->countrycode, $signup->mobile_no) }}</div>
                                            @endif
                                        </td>
                                        <td class="text-muted fs-8">
                                            {{ optional($signup->created_at)->format('d M Y, h:i A') ?: '—' }}
                                        </td>
                                        <td>
                                            @if ($signup->referrer)
                                                <a href="{{ route('admin.referral.system', ['tab' => 'signups', 'referrer_id' => $signup->referrer->id]) }}"
                                                    class="text-gray-900 fw-bold text-hover-primary d-block">
                                                    {{ $signup->referrer->name }}
                                                </a>
                                                @if (!empty($signup->referrer->referral_code))
                                                    <span class="badge badge-light-primary font-monospace fs-8 mt-1">{{ $signup->referrer->referral_code }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">User #{{ $signup->refer_id }}</span>
                                            @endif
                                        </td>
                                        <td class="fs-8">
                                            {{ $signup->referrer?->referralExecutive?->name ?? 'System' }}
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold text-gray-900">{{ $totalOrders }}</span>
                                            <span class="text-primary fw-bold fs-8">({{ $genuineOrders }})</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $st['badge'] }} fw-bold">{{ $st['label'] }}</span>
                                        </td>
                                        <td class="pe-3">
                                            @if (!empty($signup->referral_code))
                                                <div class="d-inline-flex align-items-center gap-1">
                                                    <span class="badge badge-light-primary font-monospace fw-bold">{{ $signup->referral_code }}</span>
                                                    <button type="button" class="btn btn-icon btn-sm p-0 border-0 bg-transparent"
                                                        title="Copy Referral Code"
                                                        onclick="window.copyContactText ? window.copyContactText(@js($signup->referral_code), this) : navigator.clipboard.writeText(@js($signup->referral_code));">
                                                        <i class="fa fa-copy text-muted fs-8"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-muted fs-8">Unassigned</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-10 text-muted fw-semibold">
                                            No referred client signups found for the current filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($signups && $signups->hasPages())
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $signups->links('pagination::bootstrap-5') }}
                        </div>
                    @endif

                {{-- TAB 4: STAFF PREFIXES & PERFORMANCE (8 Clean Balanced Columns) --}}
                @elseif ($activeTab === 'executives')
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-3 gy-3 mb-0">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light fs-8 text-uppercase">
                                    <th class="ps-3 min-w-180px">Staff Executive</th>
                                    <th class="min-w-110px">2-Char Prefix</th>
                                    <th class="text-center min-w-90px">Coded Clients</th>
                                    <th class="text-center min-w-80px">Signups</th>
                                    <th class="text-center min-w-90px">Ref Orders</th>
                                    <th class="text-end min-w-130px">Revenue / Disc</th>
                                    <th class="text-end min-w-110px">Bonuses</th>
                                    <th class="text-end pe-3 min-w-110px">Action</th>
                                </tr>
                            </thead>
                            <tbody class="fs-7">
                                @forelse ($executives as $exec)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <a href="{{ route('admin.referral.system', ['tab' => 'clients', 'executive_id' => $exec->id]) }}"
                                                    class="text-gray-900 fw-bolder text-hover-primary">
                                                    {{ $exec->name }}
                                                </a>
                                                <span class="badge badge-light-info fs-8 fw-bold">{{ $exec->role_label }}</span>
                                            </div>
                                            <div class="text-muted fs-8">{{ mask_email_for_display($exec->email) }}</div>
                                        </td>
                                        <td>
                                            @if (!empty($exec->referral_prefix))
                                                <span class="badge badge-light-dark font-monospace fs-7 fw-bolder">{{ $exec->referral_prefix }}</span>
                                            @else
                                                <span class="text-muted fs-8">Auto on 1st order</span>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bolder text-primary">{{ number_format($exec->coded_clients_count) }}</td>
                                        <td class="text-center fw-bold">{{ number_format($exec->referred_signups_count) }}</td>
                                        <td class="text-center fw-bold">{{ number_format($exec->referred_orders_count) }}</td>
                                        <td class="text-end text-nowrap">
                                            <div class="fw-bold text-gray-900">&pound;{{ number_format($exec->referred_revenue, 2) }}</div>
                                            <div class="text-muted fs-8">Disc: &pound;{{ number_format($exec->discount_given, 2) }}</div>
                                        </td>
                                        <td class="text-end fw-bolder text-success text-nowrap">&pound;{{ number_format($exec->bonuses_unlocked, 2) }}</td>
                                        <td class="text-end pe-3 text-nowrap">
                                            <button type="button" class="btn btn-sm btn-light-warning fw-bold px-3 py-1 js-sa-edit-referral"
                                                data-user-id="{{ $exec->id }}"
                                                data-user-name="{{ $exec->name }}"
                                                data-role-id="{{ (int) $exec->role_id }}"
                                                data-prefix="{{ $exec->referral_prefix ?? '' }}">
                                                <i class="fa fa-edit me-1 fs-8"></i>Edit Prefix
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-10 text-muted fw-semibold">
                                            No staff executives found for the current filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                {{-- TAB 5: WALLET BONUS LEDGER (7 Clean Balanced Columns) --}}
                @elseif ($activeTab === 'wallet')
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-3 gy-3 mb-0">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light fs-8 text-uppercase">
                                    <th class="ps-3 min-w-120px">Tx / Date</th>
                                    <th class="min-w-175px">Referrer &amp; Code</th>
                                    <th class="min-w-80px">Type</th>
                                    <th class="text-end min-w-90px">Amount</th>
                                    <th class="text-end min-w-120px">Balance / Wallet</th>
                                    <th class="min-w-200px">Description</th>
                                    <th class="pe-3 min-w-100px">Expiry</th>
                                </tr>
                            </thead>
                            <tbody class="fs-7">
                                @forelse ($walletTransactions as $tx)
                                    @php
                                        $liveWallet = is_numeric($tx->user?->Wallet) ? (float) $tx->user->Wallet : 0.0;
                                    @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bolder text-gray-900">#{{ $tx->id }}</div>
                                            <div class="text-muted fs-8">{{ optional($tx->created_at)->format('d M Y, h:i A') ?: '—' }}</div>
                                        </td>
                                        <td>
                                            @if ($tx->user)
                                                <a href="{{ route('user.report', $tx->user->id) }}" class="text-gray-900 fw-bolder text-hover-primary d-block">
                                                    {{ $tx->user->name }}
                                                </a>
                                                <div class="text-muted fs-8">{{ mask_email_for_display($tx->user->email) }}</div>
                                                <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                                    @if (!empty($tx->user->referral_code))
                                                        <span class="badge badge-light-primary font-monospace fs-8">{{ $tx->user->referral_code }}</span>
                                                    @endif
                                                    @if ($tx->user->referralExecutive)
                                                        <span class="text-muted fs-8">{{ $tx->user->referralExecutive->name }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">User #{{ $tx->user_id }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ strtolower((string) $tx->type) === 'credit' ? 'badge-light-success' : 'badge-light-danger' }} fw-bold text-uppercase">
                                                {{ $tx->type }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bolder text-nowrap {{ strtolower((string) $tx->type) === 'credit' ? 'text-success' : 'text-danger' }}">
                                            {{ strtolower((string) $tx->type) === 'credit' ? '+' : '-' }}&pound;{{ number_format((float) $tx->amount, 2) }}
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <div class="fw-bold text-gray-900">After: &pound;{{ number_format((float) $tx->balance_after, 2) }}</div>
                                            <div class="text-primary fw-semibold fs-8">Live: &pound;{{ number_format($liveWallet, 2) }}</div>
                                        </td>
                                        <td class="fs-8 text-gray-700">{{ $tx->description }}</td>
                                        <td class="pe-3 fs-8">
                                            @if ($tx->expires_at)
                                                <div>{{ $tx->expires_at->format('d M Y') }}</div>
                                                @if ($tx->expired_processed)
                                                    <span class="badge badge-light-danger fs-8">Expired</span>
                                                @endif
                                            @else
                                                <span class="badge badge-light-success fs-8">No Expiry</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-10 text-muted fw-semibold">
                                            No referral wallet bonus transactions recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($walletTransactions && $walletTransactions->hasPages())
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $walletTransactions->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</div>

{{-- Super Admin Inline Edit Referral Modal --}}
<div class="modal fade" id="saEditReferralModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-550px">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fw-bolder">
                    Edit Referral Settings &mdash; <span id="saRefModalUserName" class="text-primary"></span>
                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saEditReferralForm">
                @csrf
                <input type="hidden" id="saRefModalUserId" value="">
                <div class="modal-body py-5 px-7">
                    <div id="saRefModalAlert" class="alert d-none mb-4"></div>

                    <div id="saRefClientFieldsWrapper">
                        <div class="mb-4">
                            <label class="form-label fw-bold required">Referral Code (5-10 chars)</label>
                            <input type="text" id="saRefModalCode" name="referral_code" maxlength="10"
                                class="form-control form-control-solid text-uppercase font-monospace"
                                placeholder="e.g. CARUS10">
                            <div class="form-text">Format: User prefix (3) + Executive prefix (2) + Discount (10). Must be unique across clients &amp; coupons.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold required">Friend Discount Percentage (%)</label>
                            <input type="number" step="0.01" min="0" max="100" id="saRefModalDiscount" name="referral_discount_percent"
                                class="form-control form-control-solid" value="10.00">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">Marketing Executive</label>
                            <select id="saRefModalExecutiveId" name="referral_executive_id" class="form-select form-select-solid">
                                <option value="">System / Default (AI)</option>
                                @foreach ($executivesList as $exec)
                                    <option value="{{ $exec->id }}">
                                        {{ $exec->name }}@if (!empty($exec->referral_prefix)) ({{ $exec->referral_prefix }})@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="saRefExecutiveFieldsWrapper" class="d-none">
                        <div class="mb-2">
                            <label class="form-label fw-bold required">Executive 2-Char Prefix</label>
                            <input type="text" id="saRefModalPrefix" name="referral_prefix" maxlength="2"
                                class="form-control form-control-solid text-uppercase font-monospace"
                                placeholder="e.g. US">
                            <div class="form-text">Leave blank to clear custom prefix (auto-assigned on next eligible conversion). Must be 2 alphanumeric characters.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saRefModalSubmitBtn" class="btn btn-primary fw-bold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('saEditReferralModal');
    const formEl = document.getElementById('saEditReferralForm');
    if (!modalEl || !formEl) return;

    const userIdInput = document.getElementById('saRefModalUserId');
    const userNameEl = document.getElementById('saRefModalUserName');
    const alertEl = document.getElementById('saRefModalAlert');
    const clientWrap = document.getElementById('saRefClientFieldsWrapper');
    const execWrap = document.getElementById('saRefExecutiveFieldsWrapper');
    const codeInput = document.getElementById('saRefModalCode');
    const discountInput = document.getElementById('saRefModalDiscount');
    const execSelect = document.getElementById('saRefModalExecutiveId');
    const prefixInput = document.getElementById('saRefModalPrefix');
    const submitBtn = document.getElementById('saRefModalSubmitBtn');
    const updateUrlTemplate = @json(route('user.referral.update', '__ID__'));

    document.querySelectorAll('.js-sa-edit-referral').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const roleId = parseInt(btn.dataset.roleId || '2', 10);
            const isExec = [1, 4, 9].includes(roleId);

            userIdInput.value = btn.dataset.userId || '';
            userNameEl.textContent = btn.dataset.userName || '';
            alertEl.className = 'alert d-none mb-4';
            alertEl.textContent = '';

            if (isExec) {
                clientWrap.classList.add('d-none');
                execWrap.classList.remove('d-none');
                prefixInput.value = btn.dataset.prefix || '';
            } else {
                execWrap.classList.add('d-none');
                clientWrap.classList.remove('d-none');
                codeInput.value = btn.dataset.referralCode || '';
                discountInput.value = btn.dataset.discountPercent || '10.00';
                execSelect.value = btn.dataset.executiveId || '';
            }

            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        });
    });

    formEl.addEventListener('submit', function (e) {
        e.preventDefault();
        const userId = userIdInput.value;
        if (!userId) return;

        const isExecMode = !execWrap.classList.contains('d-none');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';
        alertEl.className = 'alert d-none mb-4';
        alertEl.textContent = '';

        const payload = isExecMode
            ? { referral_prefix: (prefixInput.value || '').trim().toUpperCase() }
            : {
                referral_code: (codeInput.value || '').trim().toUpperCase(),
                referral_discount_percent: discountInput.value,
                referral_executive_id: execSelect.value
            };

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch(updateUrlTemplate.replace('__ID__', encodeURIComponent(userId)), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(async function (res) {
            const json = await res.json().catch(function () { return {}; });
            if (!res.ok || !json.success) {
                throw new Error(json.message || 'Unable to update referral settings.');
            }
            return json;
        })
        .then(function () {
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            }
            window.location.reload();
        })
        .catch(function (err) {
            alertEl.className = 'alert alert-danger mb-4';
            alertEl.textContent = err.message || 'Error saving referral settings.';
        })
        .finally(function () {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        });
    });
});
</script>
@endpush
