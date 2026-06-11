@extends('layouts.app')

@section('content')

{{-- ===== FILTER CARD ===== --}}
<div class="card card-flush mb-4">
    <div class="card-header">
        <h3 class="card-title fw-bold">Source Lead Report</h3>
        <span class="text-muted fs-7">{{ $reports->count() }} records</span>
    </div>
    <div class="card-body py-3">
        <form method="GET" action="{{ route('source.lead.report') }}" class="d-flex align-items-center gap-3 flex-wrap" id="mainFilterForm">
            <input type="hidden" name="source_id" id="selected_source_id" value="{{ request('source_id') }}">
            <input type="hidden" name="status_tab" id="selected_status_tab" value="{{ request('status_tab', 'All') }}">

            <div class="d-flex align-items-center gap-2">
                <label class="text-muted fs-7 mb-0">From</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm form-control-solid" style="width:160px">
            </div>
            <div class="d-flex align-items-center gap-2">
                <label class="text-muted fs-7 mb-0">To</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm form-control-solid" style="width:160px">
            </div>
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="fa fa-search me-1"></i>Search
            </button>
            <a href="{{ route('source.lead.report') }}" class="btn btn-sm btn-light-danger">
                <i class="fa fa-redo me-1"></i>Reset
            </a>
        </form>
    </div>
</div>

{{-- ===== SOURCE PILLS ===== --}}
<div class="bg-white rounded mb-4 p-4 shadow-sm">
    <div class="text-muted fw-bold fs-8 text-uppercase letter-spacing-1 mb-3">Filter by Source</div>
    <div class="d-flex flex-wrap gap-2">

        <button type="button" onclick="filterBySource('')"
            class="btn btn-sm d-flex align-items-center gap-2 {{ !request()->filled('source_id') ? 'btn-primary' : 'btn-light-primary' }}">
            All Sources
            <span class="badge {{ !request()->filled('source_id') ? 'badge-light text-primary' : 'badge-primary' }} fs-8 px-2 py-1">
                {{ $allSourcesCount }}
            </span>
        </button>

        @foreach($sources as $source)
            @php $isActive = request('source_id') == $source->id; @endphp
            <button type="button" onclick="filterBySource('{{ $source->id }}')"
                class="btn btn-sm d-flex align-items-center gap-2 {{ $isActive ? 'btn-primary' : 'btn-light-primary' }}">
                {{ $source->source_name }}
                <span class="badge {{ $isActive ? 'badge-light text-primary' : 'badge-primary' }} fs-8 px-2 py-1">
                    {{ $source->leads_count }}
                </span>
            </button>
        @endforeach

    </div>
</div>

{{-- ===== TABLE CARD ===== --}}
<div class="card card-flush">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" style="font-size:13px">
                <thead>
                    <tr class="text-muted bg-light">
                        <th class="text-center ps-4" style="width:50px">Sr.</th>
                        <th class="text-center">Order ID</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Order Date</th>
                        <th class="text-center">Project Title</th>
                        <th class="text-center">Words</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Delivery Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $index => $lead)
                        @php
                            $isConverted = $lead->is_converted == 1;

                            $hasFailed = false;
                            if (!empty($lead->user)) {
                                $hasFailed = \App\Models\Order::where('uid', $lead->user->id)
                                    ->where('is_fail', 1)->exists();
                            }

                            // Order ID style:
                            // converted => green badge
                            // failed    => red border badge (screenshot style)
                            // frontend  => primary badge
                            // normal    => plain text
                        @endphp
                        <tr id="lead-{{ $lead->id }}">

                            {{-- SR --}}
                            <td class="text-center text-muted ps-4">{{ $index + 1 }}</td>

                            {{-- ORDER ID --}}
                            <td class="text-center">
                                @if ($isConverted)
                                    {{-- Converted: green badge --}}
                                    <span style="display:inline-block;padding:3px 10px;border-radius:6px;border:2px solid #16a34a;background:#f0fdf4;color:#15803d;font-weight:600;font-size:13px">
                                        {{ $lead->order_id }}
                                    </span>
                                @elseif ($hasFailed)
                                    {{-- Failed order: red border badge (like screenshot) --}}
                                    <span style="display:inline-block;padding:3px 10px;border-radius:6px;border:2px solid #dc2626;background:#fff5f5;color:#b91c1c;font-weight:600;font-size:13px">
                                        {{ $lead->order_id }}
                                    </span>
                                @elseif ($lead['frontendorder'] == '1')
                                    <span class="badge badge-light-primary fs-7 fw-bold">{{ $lead->order_id }}</span>
                                @else
                                    <span class="fw-semibold" style="color:#4F46E5">{{ $lead->order_id }}</span>
                                @endif

                                @if ($isConverted)
                                    <br><span class="badge badge-light-success fs-8 mt-1">Converted</span>
                                @endif
                                @if ($lead['resit'] == 'on')
                                    <br><span class="badge badge-light-danger fs-8 mt-1">Resit Work</span>
                                @endif
                                @if ($lead['service_type'] == 'First Class Work')
                                    <br><span class="badge badge-light-info fs-8 mt-1">First Class Work</span>
                                @endif
                            </td>

                            {{-- CUSTOMER --}}
                            <td class="text-center">
                                @php
                                    $userLeadCount = !empty($lead->user)
                                        ? \App\Models\Leads::where('emp_id', $lead->user->id)
                                            ->where('status', 0)
                                            ->where('duplicate_lead', 0)
                                            ->count()
                                        : 0;
                                @endphp
                                <div class="fw-semibold">{{ $lead->user->name ?? 'No Name' }}</div>
                                <div class="text-muted fs-8 mt-1">{{ $lead->user->mobile_no ?? '—' }}</div>
                                <div class="d-flex justify-content-center flex-wrap gap-1 mt-1">
                                    <span class="badge badge-light-primary fs-8">Leads: {{ $userLeadCount }}</span>
                                    @if(!empty($lead->user))
                                        @php
                                            $orderCount = \App\Models\Order::where('uid', $lead->user->id)->count();
                                            if($orderCount > 10)     { $cls = "badge-light-success"; $lbl = "Loyal Customer"; }
                                            elseif($orderCount >= 4) { $cls = "badge-light-warning"; $lbl = "Repeated"; }
                                            else                     { $cls = "badge-light-info";    $lbl = "Beginner"; }
                                        @endphp
                                        <span class="badge {{ $cls }} fs-8">{{ $lbl }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- ORDER DATE --}}
                            <td class="text-center">
                                <div class="fw-bolder text-gray-800 fs-6">
                                    {{ \Carbon\Carbon::parse($lead->create_at)->format('d M Y') }}
                                </div>
                                @if($lead->source)
                                    <div class="d-flex justify-content-center align-items-center mt-1">
                                        <span class="badge badge-light-info d-flex align-items-center gap-1 px-2 py-1" style="border: 1px solid rgba(0, 158, 247, 0.15); border-radius: 4px;">
                                            @if(!empty($lead->source->source_icon))
                                                <img src="{{ asset($lead->source->source_icon) }}"
                                                    style="height:14px; width:14px; object-fit:cover; border-radius:3px;"
                                                    title="{{ $lead->source->source_name }}"
                                                    onerror="this.style.display='none'">
                                            @endif
                                            <span class="fw-bold fs-8" style="color: #009ef7;">
                                                {{ $lead->source->source_name }}
                                            </span>
                                        </span>
                                    </div>
                                @endif
                            </td>

                            {{-- PROJECT TITLE --}}
                            <td class="text-center">
                                {!! $lead->project_title
                                    ? '<span>'.e($lead->project_title).'</span>'
                                    : '<span class="text-muted fst-italic fs-8">No title</span>' !!}
                                @if ($lead->semester)
                                    <br><span class="badge badge-light-success fs-8 mt-1">Semester: {{ $lead->semester }}</span>
                                @endif
                                @if ($lead->tech === 'on')
                                    <br><span class="badge badge-light-success fs-8 mt-1">Technical Work</span>
                                @endif
                                @if ($lead->module_code)
                                    <br><span class="badge badge-light-danger fs-8 mt-1">{{ $lead->module_code }}</span>
                                @endif
                            </td>

                            {{-- WORDS --}}
                            <td class="text-center">
                                {!! $lead->pages
                                    ? e($lead->pages)
                                    : '<span class="text-muted fst-italic fs-8">No pages</span>' !!}
                            </td>

                            {{-- PRICE --}}
                            <td class="text-center fw-semibold">
                                {!! $lead->price
                                    ? e($lead->price)
                                    : '<span class="text-muted fst-italic fs-8">No price</span>' !!}
                            </td>

                            {{-- DELIVERY DATE --}}
                            <td class="text-center">
                                <div>{{ \Carbon\Carbon::parse($lead->deadline)->format('d M Y') }}</div>
                                @if ($lead->delivery_time)
                                    <span class="badge badge-light-info fs-8 mt-1">{{ $lead->delivery_time }}</span>
                                @endif
                                @if ($lead->draft_required == 'Yes')
                                    <br><span class="badge badge-light-success fs-8 mt-1">{{ $lead->draft_date }}</span>
                                    <span class="badge badge-light-success fs-8 mt-1">{{ $lead->draft_time }}</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5 fst-italic">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterBySource(sourceId) {
    document.getElementById('selected_source_id').value = sourceId;
    document.getElementById('mainFilterForm').submit();
}
function filterByStatusTab(statusName) {
    document.getElementById('selected_status_tab').value = statusName;
    document.getElementById('mainFilterForm').submit();
}
</script>

@endsection
