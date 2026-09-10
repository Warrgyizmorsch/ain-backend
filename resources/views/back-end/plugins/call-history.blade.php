@extends('layouts.app')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
            <div>
                <h1 class="fs-2 fw-bolder text-dark mb-1">
                    <i class="fa fa-history text-primary me-2"></i>Call History
                </h1>
                <div class="text-muted fw-bold fs-7">
                    All Twilio outbound & inbound calls — completed, missed, failed
                </div>
            </div>
            <a href="{{ route('plugins.index') }}" class="btn btn-sm btn-light-primary">
                <i class="fa fa-arrow-left me-1"></i> Back to Plugins
            </a>
        </div>

        {{-- Filters --}}
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body py-3 px-4">
                <form method="GET" action="{{ route('plugins.twilio.call.history') }}" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold fs-7 mb-1">Search</label>
                        <input type="text" name="search" class="form-control form-control-sm form-control-solid"
                               placeholder="Name / Number / SID" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold fs-7 mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm form-select-solid">
                            <option value="">All Status</option>
                            @foreach(['completed','missed','no-answer','failed','cancelled','in-progress'] as $s)
                                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('-', ' ', $s)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold fs-7 mb-1">Direction</label>
                        <select name="direction" class="form-select form-select-sm form-select-solid">
                            <option value="">All</option>
                            <option value="outbound" {{ request('direction') == 'outbound' ? 'selected' : '' }}>Outbound</option>
                            <option value="inbound"  {{ request('direction') == 'inbound'  ? 'selected' : '' }}>Inbound</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold fs-7 mb-1">From Date</label>
                        <input type="date" name="date_from" class="form-control form-control-sm form-control-solid"
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold fs-7 mb-1">To Date</label>
                        <input type="date" name="date_to" class="form-control form-control-sm form-control-solid"
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100"><i class="fa fa-search"></i></button>
                        <a href="{{ route('plugins.twilio.call.history') }}" class="btn btn-sm btn-light w-100"><i class="fa fa-times"></i></a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Summary Stats --}}
        @php
            $total     = $logs->total();
            $completed = $logs->getCollection()->where('status', 'completed')->count();
            $missed    = $logs->getCollection()->whereIn('status', ['missed','no-answer'])->count();
            $failed    = $logs->getCollection()->where('status', 'failed')->count();
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 bg-light-primary text-center py-3">
                    <div class="fs-2 fw-bolder text-primary">{{ $logs->total() }}</div>
                    <div class="text-muted fs-8 fw-bold">Total (this page)</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 bg-light-success text-center py-3">
                    <div class="fs-2 fw-bolder text-success">{{ $logs->getCollection()->where('status','completed')->count() }}</div>
                    <div class="text-muted fs-8 fw-bold">Completed</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 bg-light-danger text-center py-3">
                    <div class="fs-2 fw-bolder text-danger">{{ $logs->getCollection()->whereIn('status',['missed','no-answer'])->count() }}</div>
                    <div class="text-muted fs-8 fw-bold">Missed / No Answer</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 bg-light-warning text-center py-3">
                    <div class="fs-2 fw-bolder text-warning">{{ $logs->getCollection()->where('status','failed')->count() }}</div>
                    <div class="text-muted fs-8 fw-bold">Failed</div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="callHistoryTable">
                        <thead class="bg-light">
                            <tr class="fw-bolder text-muted fs-8 text-uppercase">
                                <th class="ps-4">#</th>
                                <th>Date & Time</th>
                                <th>Direction</th>
                                <th>Status</th>
                                <th>Customer</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Duration</th>
                                <th>Recording</th>
                                <th>Agent</th>
                                <th>Call SID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $i => $log)
                            <tr class="{{ in_array($log->status, ['missed','no-answer']) ? 'table-danger' : (($log->status === 'completed') ? '' : '') }}">
                                <td class="ps-4 text-muted fs-8">{{ $logs->firstItem() + $i }}</td>
                                <td>
                                    <div class="fw-bold fs-7">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-muted fs-8">{{ $log->created_at->format('h:i:s A') }}</div>
                                </td>
                                <td>
                                    @if($log->direction === 'inbound')
                                        <span class="badge badge-light-info fs-8">
                                            <i class="fa fa-arrow-down me-1"></i>Inbound
                                        </span>
                                    @else
                                        <span class="badge badge-light-primary fs-8">
                                            <i class="fa fa-arrow-up me-1"></i>Outbound
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $log->status_badge }} fs-8 fw-bold">
                                        @if(in_array($log->status, ['missed','no-answer']))
                                            <i class="fa fa-phone-slash me-1"></i>
                                        @elseif($log->status === 'completed')
                                            <i class="fa fa-check me-1"></i>
                                        @elseif($log->status === 'failed')
                                            <i class="fa fa-times me-1"></i>
                                        @endif
                                        {{ $log->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold fs-7">{{ $log->customer_name ?: '—' }}</div>
                                </td>
                                <td>
                                    <span class="font-monospace fs-8 text-dark">
                                        {{ $log->from_number ? mask_raw_phone($log->from_number) : '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-monospace fs-8 text-dark">
                                        {{ $log->to_number ? mask_raw_phone($log->to_number) : '—' }}
                                    </span>
                                </td>
                                <td>
                                    @if($log->duration > 0)
                                        <span class="badge badge-light-success fs-8">
                                            <i class="fa fa-clock me-1"></i>{{ $log->formatted_duration }}
                                        </span>
                                    @else
                                        <span class="text-muted fs-8">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->recording_url)
                                        <div class="d-flex align-items-center gap-1">
                                            <audio controls preload="none" style="height: 28px; width: 130px;">
                                                <source src="{{ $log->recording_url }}" type="audio/mpeg">
                                            </audio>
                                            <a href="{{ $log->recording_url }}" target="_blank" class="btn btn-icon btn-xs btn-light-success" title="Download / Open Audio">
                                                <i class="fa fa-download fs-9"></i>
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-muted fs-8">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted fs-8">{{ $log->agent?->name ?? '—' }}</span>
                                </td>
                                <td>
                                    <span class="font-monospace text-muted fs-9" title="{{ $log->call_sid }}">
                                        {{ $log->call_sid ? Str::limit($log->call_sid, 16) : '—' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-8 text-muted">
                                    <i class="fa fa-history fs-2 text-muted mb-3 d-block"></i>
                                    No call records found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($logs->hasPages())
            <div class="card-footer py-3 px-4">
                {{ $logs->links() }}
            </div>
            @endif
        </div>

    </div>
</div>

@php
if (!function_exists('crmMaskPhonePHP')) {
    function crmMaskPhonePHP(string $phone): string {
        return mask_raw_phone($phone);
    }
}
@endphp

<style>
#callHistoryTable thead th {
    position: sticky;
    top: 0;
    z-index: 5;
    background: #f5f8fa !important;
    font-size: 11px;
    letter-spacing: .04em;
    border-bottom: 2px solid #edf0f5;
}
#callHistoryTable td, #callHistoryTable th {
    border-color: #f0f2f8;
    vertical-align: middle;
}
.table-danger td { background-color: #fff5f8 !important; }
</style>
@endsection
