@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bolder text-dark mb-1">{{ $type }}</h2>
            <span class="text-muted fw-bold fs-7">
                Analytics Report Details
            </span>
        </div>

        <a href="{{ url()->previous() }}" class="btn btn-sm btn-light-primary">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Card -->
    <div class="card card-flush shadow-sm">

        <!-- Card Header -->
        <div class="card-header align-items-center py-5">
            <h3 class="card-title fw-bolder text-dark">
                {{ $type }} Data Table
            </h3>
        </div>

        <!-- Card Body -->
        <div class="card-body pt-0">
            <!-- ========================================== -->
            <!-- COMMON FILTER -->
            <!-- ========================================== -->

            <form method="GET" action="">

                <div class="row mb-7">

                    <!-- From Date -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">From Date</label>

                        <input type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}">
                    </div>

                    <!-- To Date -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">To Date</label>

                        <input type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}">
                    </div>

                    <!-- Name -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Name</label>

                        <input type="text"
                            name="name"
                            class="form-control"
                            placeholder="Search Name"
                            value="{{ request('name') }}">
                    </div>

                    <!-- Country -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Country</label>

                        <input type="text"
                            name="country"
                            class="form-control"
                            placeholder="Search Country"
                            value="{{ request('country') }}">
                    </div>

                </div>

                <div class="d-flex gap-3 mb-5">

                    <button type="submit" class="btn btn-primary">
                        Filter
                    </button>

                    <a href="{{ url()->current() }}"
                    class="btn btn-light-danger">
                        Reset
                    </a>

                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-row-bordered table-row-gray-200 align-middle gs-0 gy-4">

                    <thead>

                        @if($type == 'Inquiry Tracking')
                            <tr class="fw-bolder text-muted bg-light-primary">
                                <th class="ps-4 rounded-start">Month</th>
                                <th>New Users</th>
                                <th>Retained Users</th>
                                <th class="rounded-end">Loyal Customers</th>
                            </tr>

                        @elseif($type == 'Lead Tracking')
                            <tr class="fw-bolder text-muted bg-light-info">
                                <th class="ps-4 rounded-start">Employee</th>
                                <th>Worked</th>
                                <th class="rounded-end">Converted</th>
                                <th class="rounded-end">Referred By</th>
                            </tr>

                        @elseif($type == 'Country Users')
                            <tr class="fw-bolder text-muted bg-light-warning">
                                <th class="ps-4 rounded-start">Country</th>
                                <th class="rounded-end">Total Users</th>
                            </tr>

                        @elseif($type == 'SEO Leads')

                        <tr class="fw-bolder text-muted bg-light-success">
                            <th>Total Leads</th>
                            <th>Converted</th>
                            <th>Not Converted</th>
                        </tr>

                        @elseif($type == 'Lead Sources')
                            <tr class="fw-bolder text-muted bg-light-danger">
                                <th class="ps-4 rounded-start">Source 106</th>
                                <th>Source 1696</th>
                                <th>Source insta</th>
                                <th>Source facebook</th>
                                <th>Source whatsapp</th>
                            </tr>

                        @elseif($type == 'Conversion Ratio')
                            <tr class="fw-bolder text-muted bg-light-dark">
                                <th>Total Leads</th>
                                <th>Converted</th>
                                <th>Not Converted</th>
                                <th>Ratio</th>
                            </tr>
                        @endif

                    </thead>

                    <tbody class="fw-bold text-gray-700">

                        @foreach($data as $row)

                            @if($type == 'Inquiry Tracking')

                                <tr>
                                    <td class="ps-4">{{ $row['month'] }}</td>

                                    <td>
                                        <span class="badge badge-light-primary px-4 py-2">
                                            {{ $row['new_users'] }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-light-info px-4 py-2">
                                            {{ $row['retained_users'] }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-light-success px-4 py-2">
                                            {{ $row['loyal_customers'] }}
                                        </span>
                                    </td>
                                </tr>

                            @elseif($type == 'Lead Tracking')

                            {{-- Main Row --}}
                            <tr>
                                <td class="ps-4 fw-bold">{{ $row->name }}</td>

                                {{-- Worked Count --}}
                                <td>
                                    <button
                                        class="btn btn-sm btn-light-info fw-bolder worked-toggle"
                                        data-target="worked-{{ $loop->index }}">
                                        {{ $row->worked }}
                                    </button>
                                </td>

                                {{-- Converted Count --}}
                                <td>
                                    <button
                                        class="btn btn-sm btn-light-success fw-bolder converted-toggle"
                                        data-target="converted-{{ $loop->index }}">
                                        {{ $row->converted }}
                                    </button>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-sm btn-light-primary fw-bolder referred-toggle"
                                        data-target="referred-{{ $loop->index }}">
                                        {{ $row->referred }}
                                    </button>
                                </td>
                            </tr>

                            {{-- Worked Leads Hidden Row --}}
                            <tr id="worked-{{ $loop->index }}"
                                class="worked-detail-row"
                                style="display:none;">

                                <td colspan="3" class="p-5 bg-light-info">

                                    <h5 class="fw-bolder mb-4 text-info">
                                        Worked Leads - {{ $row->name }}
                                    </h5>

                                    <div class="table-responsive">

                                        <table class="table table-row-bordered align-middle gs-0 gy-3">

                                            <thead>
                                                <tr class="fw-bolder text-muted">
                                                    <th>Lead ID</th>
                                                    <th>Name</th>
                                                    <th>Order Date</th>
                                                    <th>Project Title</th>
                                                    <th>Words</th>
                                                    <th>Price</th>
                                                    <th>Delivery Date</th>
                                                    {{-- <th>Date</th> --}}
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @forelse($row->worked_leads as $lead)

                                                    <tr>
                                                        <td>#{{ $lead->id }}</td>
                                                        <td class="text-center">
                                                            {{ $lead->user->name ?? 'No Name' }}<br>
                                                            @if(!empty($lead->user))

                                                            @php
                                                                $count = \App\Models\Order::where('uid', $lead->user->id)->count();

                                                                if($count > 10) { 
                                                                    $class = "badge-light-success"; 
                                                                    $label = "Loyal Customer"; 
                                                                } elseif($count >= 4) { 
                                                                    $class = "badge-light-warning"; 
                                                                    $label = "Repeated"; 
                                                                } else { 
                                                                    $class = "badge-light-info"; 
                                                                    $label = "Beginner"; 
                                                                }
                                                            @endphp

                                                            <span class="badge {{ $class }} fw-bold fs-8 mb-1">
                                                                {{ $label }}
                                                            </span><br>

                                                            @endif
                                                            <span class="badge badge-light-danger fs-7 fw-bold">{{ $lead->user->mobile_no ?? '' }}</span></br>
                                                        </td>
                                                        <td class="text-center">{{ \Carbon\Carbon::parse($lead->create_at)->format('d M Y') }}</br>
                                                            @if($lead->lead_source)
                                                                <strong>Source:</strong><span>{{$lead->source->source_name}}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            {!! $lead->project_title
                                                            ? e($lead->project_title)
                                                            : '<span class="badge badge-light-danger fs-7 fw-bold">No Title</span>' !!}
                                                            @if ($lead->semester)
                                                            <br><span class="badge badge-light-success fs-7">Semester: {{ $lead->semester }}</span>
                                                            @endif
                                                            @if ($lead->tech === 'on')
                                                            <br><span class="badge badge-light-success fs-7">Technical Work</span>
                                                            @endif
                                                            @if ($lead->module_code)
                                                            <br><span class="badge badge-light-danger fs-7">{{ $lead->module_code }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            {!! $lead->pages ? e($lead->pages) : '<span class="badge badge-light-danger fs-7 fw-bold">No Pages</span>' !!}
                                                        </td>
                                                        <td class="text-center">
                                                            {!! $lead->price ? e($lead->price) : '<span class="badge badge-light-danger fs-7 fw-bold">No Price</span>' !!}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ \Carbon\Carbon::parse($lead->deadline)->format('d M Y') }}
                                                            @if ($lead->delivery_time)
                                                            <span class="badge badge-light-info fs-7 fw-bold">({{ $lead->delivery_time }})</span>
                                                            @endif

                                                            @if ($lead->draft_required == 'Yes')
                                                            <br><span class="badge badge-light-success fs-7">{{ $lead->draft_date }}</span>
                                                            <br><span class="badge badge-light-success fs-7">{{ $lead->draft_time }}</span>
                                                            @endif
                                                        </td>
                                                        {{-- <td>{{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}</td> --}}
                                                    </tr>

                                                @empty

                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">
                                                            No Worked Leads Found
                                                        </td>
                                                    </tr>

                                                @endforelse

                                            </tbody>

                                        </table>

                                    </div>

                                </td>

                            </tr>

                            {{-- Converted Leads Hidden Row --}}
                            <tr id="converted-{{ $loop->index }}"
                                class="converted-detail-row"
                                style="display:none;">

                                <td colspan="3" class="p-5 bg-light-success">

                                    <h5 class="fw-bolder mb-4 text-success">
                                        Converted Leads - {{ $row->name }}
                                    </h5>

                                    <div class="table-responsive">

                                        <table class="table table-row-bordered align-middle gs-0 gy-3">

                                            <thead>
                                                <tr class="fw-bolder text-muted">
                                                    <th>Lead ID</th>
                                                    <th>Name</th>
                                                    <th>Order Date</th>
                                                    <th>Project Title</th>
                                                    <th>Words</th>
                                                    <th>Price</th>
                                                    <th>Delivery Date</th>
                                                    <th>Converted Date</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @forelse($row->converted_leads as $lead)

                                                    <tr>
                                                        <td>#{{ $lead->id }}</td>
                                                        <td class="text-center">
                                                            {{ $lead->user->name ?? 'No Name' }}<br>
                                                            @if(!empty($lead->user))

                                                            @php
                                                                $count = \App\Models\Order::where('uid', $lead->user->id)->count();

                                                                if($count > 10) { 
                                                                    $class = "badge-light-success"; 
                                                                    $label = "Loyal Customer"; 
                                                                } elseif($count >= 4) { 
                                                                    $class = "badge-light-warning"; 
                                                                    $label = "Repeated"; 
                                                                } else { 
                                                                    $class = "badge-light-info"; 
                                                                    $label = "Beginner"; 
                                                                }
                                                            @endphp

                                                            <span class="badge {{ $class }} fw-bold fs-8 mb-1">
                                                                {{ $label }}
                                                            </span><br>

                                                            @endif
                                                            <span class="badge badge-light-danger fs-7 fw-bold">{{ $lead->user->mobile_no ?? '' }}</span></br>
                                                        </td>
                                                        <td class="text-center">{{ \Carbon\Carbon::parse($lead->create_at)->format('d M Y') }}</br>
                                                            @if($lead->lead_source)
                                                                <strong>Source:</strong><span>{{$lead->source->source_name}}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            {!! $lead->project_title
                                                            ? e($lead->project_title)
                                                            : '<span class="badge badge-light-danger fs-7 fw-bold">No Title</span>' !!}
                                                            @if ($lead->semester)
                                                            <br><span class="badge badge-light-success fs-7">Semester: {{ $lead->semester }}</span>
                                                            @endif
                                                            @if ($lead->tech === 'on')
                                                            <br><span class="badge badge-light-success fs-7">Technical Work</span>
                                                            @endif
                                                            @if ($lead->module_code)
                                                            <br><span class="badge badge-light-danger fs-7">{{ $lead->module_code }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            {!! $lead->pages ? e($lead->pages) : '<span class="badge badge-light-danger fs-7 fw-bold">No Pages</span>' !!}
                                                        </td>
                                                        <td class="text-center">
                                                            {!! $lead->price ? e($lead->price) : '<span class="badge badge-light-danger fs-7 fw-bold">No Price</span>' !!}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ \Carbon\Carbon::parse($lead->deadline)->format('d M Y') }}
                                                            @if ($lead->delivery_time)
                                                            <span class="badge badge-light-info fs-7 fw-bold">({{ $lead->delivery_time }})</span>
                                                            @endif

                                                            @if ($lead->draft_required == 'Yes')
                                                            <br><span class="badge badge-light-success fs-7">{{ $lead->draft_date }}</span>
                                                            <br><span class="badge badge-light-success fs-7">{{ $lead->draft_time }}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($lead->converted_at)->format('d M Y') }}</td>
                                                    </tr>

                                                @empty

                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">
                                                            No Converted Leads Found
                                                        </td>
                                                    </tr>

                                                @endforelse

                                            </tbody>

                                        </table>

                                    </div>

                                </td>

                            </tr>

                            <tr id="referred-{{ $loop->index }}"
                                class="referred-detail-row"
                                style="display:none;">

                                <td colspan="4" class="p-5 bg-light-primary">

                                    <h5 class="fw-bolder mb-4 text-primary">
                                        Referred Leads - {{ $row->name }}
                                    </h5>

                                    <div class="table-responsive">
                                        <table class="table table-row-bordered align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bolder text-muted">
                                                    <th>User ID</th>
                                                    <th class="text-center">User Details</th>
                                                    <th>Joined Date</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse($row->referred_users as $user)
                                                    <tr>
                                                        <td>{{ $user->id }}</td>
                                                        <td class="text-center">
                                                            {{ $user->name ?? 'No Name' }}<br>
                                                            @php
                                                                $count = \App\Models\Order::where('uid', $user->id)->count();

                                                                if($count > 10) { 
                                                                    $class = "badge-light-success"; 
                                                                    $label = "Loyal Customer"; 
                                                                } elseif($count >= 4) { 
                                                                    $class = "badge-light-warning"; 
                                                                    $label = "Repeated"; 
                                                                } else { 
                                                                    $class = "badge-light-info"; 
                                                                    $label = "Beginner"; 
                                                                }
                                                            @endphp
                                                            <span class="badge {{ $class }} fw-bold fs-8 mb-1">
                                                                {{ $label }}
                                                            </span><br>
                                                            <span class="badge badge-light-danger fs-7 fw-bold">
                                                                +{{ $user->countrycode }} {{ $user->mobile_no }}
                                                            </span><br>
                                                            <span class="fs-7 fw-bold">
                                                                {{ $user->email ?? 'N/A' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d M Y') : 'N/A' }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">
                                                            No Referred Users Found
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>

                            @elseif($type == 'Country Users')
                                <tr>
                                    <td class="ps-4 fw-bold">
                                        {{ $row->country_name ?? 'Unknown' }}
                                    </td>

                                    <td>
                                        <button
                                            class="btn btn-sm btn-light-warning fw-bolder country-toggle"
                                            data-target="country-{{ $loop->index }}">

                                            {{ $row->total }}

                                        </button>
                                    </td>
                                </tr>

                                <tr id="country-{{ $loop->index }}"
                                    class="country-detail-row"
                                    style="display:none;">

                                    <td colspan="2" class="p-5 bg-light-warning">

                                        <h5 class="fw-bolder mb-4 text-warning">
                                            Users List - {{ $row->country_name }}
                                        </h5>

                                        <div class="table-responsive">

                                            <table class="table table-row-bordered align-middle">

                                                <thead>
                                                    <tr class="fw-bolder text-muted">
                                                        <th>User ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Mobile</th>
                                                        <th>Joined</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    @forelse($row->users as $user)

                                                        <tr>

                                                            <td>#{{ $user->id }}</td>

                                                            <td>{{ $user->name }}</td>

                                                            <td>{{ $user->email }}</td>

                                                            <td>{{ $user->mobile_no }}</td>

                                                            <td>
                                                                {{ \Carbon\Carbon::parse($user->created_on)->format('d M Y') }}
                                                            </td>

                                                        </tr>

                                                    @empty

                                                        <tr>
                                                            <td colspan="5" class="text-center">
                                                                No Users Found
                                                            </td>
                                                        </tr>

                                                    @endforelse

                                                </tbody>

                                            </table>

                                        </div>

                                    </td>

                                </tr>


                                @elseif($type == 'SEO Leads')
                                <tr>
                                    {{-- Total Leads --}}
                                    <td>
                                        <button
                                            class="btn btn-sm btn-light-primary fw-bolder seo-total-toggle"
                                            data-target="seo-total-{{ $loop->index }}">
                                            {{ $row->total }}
                                        </button>
                                    </td>

                                    {{-- Completed --}}
                                    <td>
                                        <button
                                            class="btn btn-sm btn-light-success fw-bolder seo-completed-toggle"
                                            data-target="seo-completed-{{ $loop->index }}">
                                                {{ $row->converted }}
                                        </button>
                                    </td>

                                    {{-- Cancelled --}}
                                    <td>
                                        <button
                                            class="btn btn-sm btn-light-danger fw-bolder seo-cancelled-toggle"
                                            data-target="seo-cancelled-{{ $loop->index }}">
                                            {{ $row->not_converted }}                                        
                                        </button>
                                    </td>
                                </tr>

                                {{-- ========================================= --}}
                                {{-- Total Leads List --}}
                                {{-- ========================================= --}}

                                <tr id="seo-total-{{ $loop->index }}"
                                    class="seo-detail-row"
                                    style="display:none;">

                                    <td colspan="3" class="p-5 bg-light-primary">

                                        <h5 class="fw-bolder mb-4 text-primary">
                                            Total SEO Leads
                                        </h5>

                                        <div class="table-responsive">

                                            <table class="table table-row-bordered align-middle">

                                                <thead>
                                                    <tr>
                                                        <th>Lead ID</th>
                                                        <th>Name</th>
                                                        <th>Project Title</th>
                                                        {{-- <th>Status</th> --}}
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    @forelse($row->total_leads as $lead)

                                                        <tr>

                                                            <td>{{ $lead->order_id }}</td>

                                                            <td class="text-center">
                                                                {{ $lead->user->name ?? 'No Name' }}<br>
                                                                @if(!empty($lead->user))

                                                                @php
                                                                    $count = \App\Models\Order::where('uid', $lead->user->id)->count();

                                                                    if($count > 10) { 
                                                                        $class = "badge-light-success"; 
                                                                        $label = "Loyal Customer"; 
                                                                    } elseif($count >= 4) { 
                                                                        $class = "badge-light-warning"; 
                                                                        $label = "Repeated"; 
                                                                    } else { 
                                                                        $class = "badge-light-info"; 
                                                                        $label = "Beginner"; 
                                                                    }
                                                                @endphp

                                                                <span class="badge {{ $class }} fw-bold fs-8 mb-1">
                                                                    {{ $label }}
                                                                </span><br>

                                                                @endif
                                                                <span class="badge badge-light-danger fs-7 fw-bold">{{ $lead->user->mobile_no ?? '' }}</span></br>
                                                            </td>

                                                            <td>{{ $lead->project_title ?? 'N/A' }}</td>

                                                            {{-- <td>{{ $lead->status ?? 'N/A' }}</td> --}}

                                                            <td>
                                                                {{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}
                                                            </td>

                                                        </tr>

                                                    @empty

                                                        <tr>
                                                            <td colspan="5" class="text-center">
                                                                No Leads Found
                                                            </td>
                                                        </tr>

                                                    @endforelse

                                                </tbody>

                                            </table>

                                        </div>

                                    </td>

                                </tr>

                                {{-- ========================================= --}}
                                {{-- Completed Leads List --}}
                                {{-- ========================================= --}}

                                <tr id="seo-completed-{{ $loop->index }}"
                                    class="seo-detail-row"
                                    style="display:none;">

                                    <td colspan="3" class="p-5 bg-light-success">

                                        <h5 class="fw-bolder mb-4 text-success">
                                            Completed SEO Leads
                                        </h5>

                                        <div class="table-responsive">

                                            <table class="table table-row-bordered align-middle">

                                                <thead>
                                                    <tr>
                                                        <th>Lead ID</th>
                                                        <th>Name</th>
                                                        <th>Project Title</th>
                                                        {{-- <th>Status</th> --}}
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    @forelse($row->converted_leads as $lead)

                                                        <tr>

                                                            <td>{{ $lead->order_id }}</td>

                                                            <td class="text-center">
                                                                {{ $lead->user->name ?? 'No Name' }}<br>
                                                                @if(!empty($lead->user))

                                                                @php
                                                                    $count = \App\Models\Order::where('uid', $lead->user->id)->count();

                                                                    if($count > 10) { 
                                                                        $class = "badge-light-success"; 
                                                                        $label = "Loyal Customer"; 
                                                                    } elseif($count >= 4) { 
                                                                        $class = "badge-light-warning"; 
                                                                        $label = "Repeated"; 
                                                                    } else { 
                                                                        $class = "badge-light-info"; 
                                                                        $label = "Beginner"; 
                                                                    }
                                                                @endphp

                                                                <span class="badge {{ $class }} fw-bold fs-8 mb-1">
                                                                    {{ $label }}
                                                                </span><br>

                                                                @endif
                                                                <span class="badge badge-light-danger fs-7 fw-bold">{{ $lead->user->mobile_no ?? '' }}</span></br>
                                                            </td>

                                                            <td>{{ $lead->project_title ?? 'N/A' }}</td>

                                                            {{-- <td>{{ $lead->projectstatus ?? 'Completed' }}</td> --}}

                                                            <td>
                                                                {{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}
                                                            </td>

                                                        </tr>

                                                    @empty

                                                        <tr>
                                                            <td colspan="5" class="text-center">
                                                                No Completed Leads
                                                            </td>
                                                        </tr>

                                                    @endforelse

                                                </tbody>

                                            </table>

                                        </div>

                                    </td>

                                </tr>

                                {{-- ========================================= --}}
                                {{-- Cancelled Leads List --}}
                                {{-- ========================================= --}}

                                <tr id="seo-cancelled-{{ $loop->index }}"
                                    class="seo-detail-row"
                                    style="display:none;">

                                    <td colspan="3" class="p-5 bg-light-danger">

                                        <h5 class="fw-bolder mb-4 text-danger">
                                            Cancelled SEO Leads
                                        </h5>

                                        <div class="table-responsive">

                                            <table class="table table-row-bordered align-middle">

                                                <thead>
                                                    <tr>
                                                        <th>Lead ID</th>
                                                        <th>Name</th>
                                                        <th>Project Title</th>
                                                        {{-- <th>Status</th> --}}
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    @forelse($row->not_converted_leads as $lead)

                                                        <tr>

                                                            <td>{{ $lead->order_id }}</td>

                                                            <td class="text-center">
                                                                {{ $lead->user->name ?? 'No Name' }}<br>
                                                                @if(!empty($lead->user))

                                                                @php
                                                                    $count = \App\Models\Order::where('uid', $lead->user->id)->count();

                                                                    if($count > 10) { 
                                                                        $class = "badge-light-success"; 
                                                                        $label = "Loyal Customer"; 
                                                                    } elseif($count >= 4) { 
                                                                        $class = "badge-light-warning"; 
                                                                        $label = "Repeated"; 
                                                                    } else { 
                                                                        $class = "badge-light-info"; 
                                                                        $label = "Beginner"; 
                                                                    }
                                                                @endphp

                                                                <span class="badge {{ $class }} fw-bold fs-8 mb-1">
                                                                    {{ $label }}
                                                                </span><br>

                                                                @endif
                                                                <span class="badge badge-light-danger fs-7 fw-bold">{{ $lead->user->mobile_no ?? '' }}</span></br>
                                                            </td>

                                                            <td>{{ $lead->project_title ?? 'N/A' }}</td>

                                                            {{-- <td>{{ $lead->projectstatus ?? 'Cancelled' }}</td> --}}

                                                            <td>
                                                                {{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}
                                                            </td>

                                                        </tr>
                                                    @empty

                                                        <tr>
                                                            <td colspan="5" class="text-center">
                                                                No Cancelled Leads
                                                            </td>
                                                        </tr>

                                                    @endforelse

                                                </tbody>

                                            </table>

                                        </div>

                                    </td>

                                </tr>

                            @elseif($type == 'Lead Sources')
                            <tr>
                                {{-- Source 106 --}}
                                <td>
                                    <button
                                        class="btn btn-sm btn-light-danger fw-bolder source-toggle"
                                        data-target="source-106">

                                        {{ $row->source_106_count }}

                                    </button>
                                </td>

                                {{-- Source 1696 --}}
                                <td>
                                    <button
                                        class="btn btn-sm btn-light-danger fw-bolder source-toggle"
                                        data-target="source-1696">

                                        {{ $row->source_1696_count }}

                                    </button>
                                </td>

                                {{-- Insta --}}
                                <td>
                                    <button
                                        class="btn btn-sm btn-light-danger fw-bolder source-toggle"
                                        data-target="source-insta">

                                        {{ $row->source_insta_count }}

                                    </button>
                                </td>

                                {{-- Facebook --}}
                                <td>
                                    <button
                                        class="btn btn-sm btn-light-danger fw-bolder source-toggle"
                                        data-target="source-facebook">

                                        {{ $row->source_facebook_count }}

                                    </button>
                                </td>

                                {{-- Whatsapp --}}
                                <td>
                                    <button
                                        class="btn btn-sm btn-light-danger fw-bolder source-toggle"
                                        data-target="source-whatsapp">

                                        {{ $row->source_whatsapp_count }}

                                    </button>
                                </td>  
                            </tr>

                            {{-- ================================================= --}}
                            {{-- Source 106 Leads --}}
                            {{-- ================================================= --}}

                            <tr id="source-106"
                                class="source-detail-row"
                                style="display:none;">

                                <td colspan="6" class="p-5 bg-light-danger">
                                    <h5 class="fw-bolder mb-4 text-danger">
                                        Source 106 Leads
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bolder text-muted">
                                                    <th>Lead ID</th>
                                                    <th>Name</th>
                                                    <th>Project Title</th>
                                                    <th>Price</th>
                                                    <th>Deadline</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($row->source_106_leads as $lead)
                                                    <tr>
                                                        <td>#{{ $lead->id }}</td>
                                                        <td class="text-center">
                                                            {{ $lead->user->name ?? 'No Name' }}<br>
                                                            @if(!empty($lead->user))

                                                            @php
                                                                $count = \App\Models\Order::where('uid', $lead->user->id)->count();

                                                                if($count > 10) { 
                                                                    $class = "badge-light-success"; 
                                                                    $label = "Loyal Customer"; 
                                                                } elseif($count >= 4) { 
                                                                    $class = "badge-light-warning"; 
                                                                    $label = "Repeated"; 
                                                                } else { 
                                                                    $class = "badge-light-info"; 
                                                                    $label = "Beginner"; 
                                                                }
                                                            @endphp

                                                            <span class="badge {{ $class }} fw-bold fs-8 mb-1">
                                                                {{ $label }}
                                                            </span><br>

                                                        @endif
                                                        <span class="badge badge-light-danger fs-7 fw-bold">{{ $lead->user->mobile_no ?? '' }}</span></br>
                                                        <td>{{ $lead->project_title ?? 'N/A' }}</td>
                                                        <td>{{ $lead->price ?? 'N/A' }}</td>
                                                        <td>
                                                            @if($lead->deadline)
                                                                {{ \Carbon\Carbon::parse($lead->deadline)->format('d M Y') }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}
                                                        </td>
                                                    </tr>
                                            @empty

                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        No Leads Found
                                                    </td>
                                                </tr>

                                            @endforelse

                                        </tbody>

                                    </table>

                                </div>

                            </td>

                        </tr>

                            @elseif($type == 'Conversion Ratio')

                                <tr>

                                    <td>
                                        <span class="badge badge-light-primary px-4 py-2">
                                            {{ $row['total'] }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-light-success px-4 py-2">
                                            {{ $row['converted'] }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-light-danger px-4 py-2">
                                            {{ $row['not_converted'] }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-light-info px-4 py-2">
                                            {{ $row['ratio'] }}%
                                        </span>
                                    </td>

                                </tr>

                            @endif

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    // Worked Toggle
    document.querySelectorAll('.worked-toggle').forEach(button => {

        button.addEventListener('click', function () {

            let target = this.getAttribute('data-target');
            let row = document.getElementById(target);

            // Hide all rows first
            document.querySelectorAll('.worked-detail-row').forEach(r => {
                if (r.id !== target) {
                    r.style.display = 'none';
                }
            });

            document.querySelectorAll('.converted-detail-row').forEach(r => {
                r.style.display = 'none';
            });

            // Toggle current row
            if (row.style.display === 'table-row') {
                row.style.display = 'none';
            } else {
                row.style.display = 'table-row';
            }

        });

    });

    // Converted Toggle
    document.querySelectorAll('.converted-toggle').forEach(button => {

        button.addEventListener('click', function () {

            let target = this.getAttribute('data-target');
            let row = document.getElementById(target);

            // Hide all rows first
            document.querySelectorAll('.worked-detail-row').forEach(r => {
                r.style.display = 'none';
            });

            document.querySelectorAll('.converted-detail-row').forEach(r => {
                if (r.id !== target) {
                    r.style.display = 'none';
                }
            });

            // Toggle current row
            if (row.style.display === 'table-row') {
                row.style.display = 'none';
            } else {
                row.style.display = 'table-row';
            }

        });

    });

    // Referred Toggle
    document.querySelectorAll('.referred-toggle').forEach(button => {

        button.addEventListener('click', function () {

            let target = this.getAttribute('data-target');
            let row = document.getElementById(target);

            // Hide worked rows
            document.querySelectorAll('.worked-detail-row').forEach(r => {
                r.style.display = 'none';
            });

            // Hide converted rows
            document.querySelectorAll('.converted-detail-row').forEach(r => {
                r.style.display = 'none';
            });

            // Hide referred rows except current
            document.querySelectorAll('.referred-detail-row').forEach(r => {
                if (r.id !== target) {
                    r.style.display = 'none';
                }
            });

            // Toggle current row
            if (row.style.display === 'table-row') {
                row.style.display = 'none';
            } else {
                row.style.display = 'table-row';
            }

        });

    });

});

// SEO Toggle
document.querySelectorAll(
    '.seo-total-toggle, .seo-completed-toggle, .seo-cancelled-toggle'
).forEach(button => {

    button.addEventListener('click', function () {

        let target = this.getAttribute('data-target');

        let row = document.getElementById(target);

        // Hide all
        document.querySelectorAll('.seo-detail-row').forEach(r => {

            if (r.id !== target) {

                r.style.display = 'none';
            }

        });

        // Toggle current
        if (row.style.display === 'table-row') {

            row.style.display = 'none';

        } else {

            row.style.display = 'table-row';
        }

    });

});

document.querySelectorAll('.source-toggle').forEach(button => {

    button.addEventListener('click', function () {

        let target = this.getAttribute('data-target');
        let row = document.getElementById(target);

        document.querySelectorAll('.source-detail-row').forEach(r => {

            if (r.id !== target) {

                r.style.display = 'none';
            }

        });

        if (row.style.display === 'table-row') {

            row.style.display = 'none';

        } else {

            row.style.display = 'table-row';
        }

    });

});

document.querySelectorAll('.country-toggle').forEach(button => {

    button.addEventListener('click', function () {

        let target = this.getAttribute('data-target');
        let row = document.getElementById(target);

        document.querySelectorAll('.country-detail-row').forEach(r => {

            if (r.id !== target) {

                r.style.display = 'none';
            }

        });

        if (row.style.display === 'table-row') {

            row.style.display = 'none';

        } else {

            row.style.display = 'table-row';
        }

    });

});

</script>

@endsection