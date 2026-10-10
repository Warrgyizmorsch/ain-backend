@extends('layouts.app')

@section('body-layout-classes', 'toolbar-disabled')
@section('body-layout-style', '--kt-toolbar-height:0px; --kt-toolbar-height-tablet-and-mobile:0px')

@push('head')
<style>
    /* Eliminate Metronic toolbar-fixed empty gap & extra whitespace */
    .header-fixed.toolbar-fixed #kt_wrapper,
    .header-fixed #kt_wrapper,
    #kt_wrapper {
        padding-top: 65px !important;
    }

    #kt_wrapper .content,
    .content.flex-column-fluid,
    .content,
    #kt_content {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    #kt_content_container {
        padding-top: 0.5rem !important;
    }

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
    .table-danger-light {
        background-color: rgba(241, 65, 108, 0.04) !important;
    }
    .table-danger-light:hover {
        background-color: rgba(241, 65, 108, 0.08) !important;
    }
    .provider-nav-tab {
        border-radius: 8px !important;
        padding: 8px 18px !important;
        font-weight: 700 !important;
        transition: all 0.2s ease;
    }
    .provider-nav-tab.active {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
</style>
@endpush

@section('content')
<div id="kt_content_container" class="container-fluid pt-2 pb-4">

    {{-- Top Level: Provider Selection Tabs (Next2Call Softphone & Twilio Voice Call) --}}
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <span class="fs-7 fw-bold text-gray-700 text-uppercase me-2">Call Provider:</span>
                <ul class="nav nav-pills gap-2 mb-0">
                    {{-- Next2Call Tab --}}
                    <li class="nav-item">
                        <a class="nav-link provider-nav-tab d-flex align-items-center gap-2 {{ ($provider ?? 'next2call') === 'next2call' ? 'active bg-primary text-white' : 'bg-light text-gray-700 text-hover-primary' }}"
                           href="{{ route('call.history', ['provider' => 'next2call']) }}">
                            <span class="position-relative d-inline-flex align-items-center">
                                <i class="fa fa-phone-alt fs-7 {{ ($provider ?? 'next2call') === 'next2call' ? 'text-white' : 'text-success' }}"></i>
                                <span style="position:absolute;bottom:-4px;right:-7px;background:#e53e3e;color:#fff;font-size:7px;font-weight:900;line-height:1;padding:1px 2.5px;border-radius:2px;">2</span>
                            </span>
                            <span>Next2Call Softphone</span>
                        </a>
                    </li>
                    {{-- Twilio Tab --}}
                    <li class="nav-item">
                        <a class="nav-link provider-nav-tab d-flex align-items-center gap-2 {{ ($provider ?? '') === 'twilio' ? 'active bg-primary text-white' : 'bg-light text-gray-700 text-hover-primary' }}"
                           href="{{ route('call.history', ['provider' => 'twilio']) }}">
                            <i class="fa fa-broadcast-tower fs-7 {{ ($provider ?? '') === 'twilio' ? 'text-white' : 'text-danger' }}"></i>
                            <span>Twilio Voice Call</span>
                        </a>
                    </li>
                </ul>
            </div>

            @if(($provider ?? 'next2call') === 'next2call')
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-light-primary fw-bold" id="btnSyncNext2Call" onclick="syncNext2CallCalls()">
                        <i class="fa fa-sync-alt me-1" id="syncIcon"></i> Sync Latest Calls
                    </button>
                    <a href="{{ route('next2call.page') }}" class="btn btn-sm btn-light-dark fw-bold" target="_blank" title="Open Next2Call Dialer Screen">
                        <i class="fa fa-external-link-alt me-1"></i> Open Dialer
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Quick Tabs & Granular Filters Card --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header border-0 pt-3 pb-2 d-flex flex-wrap align-items-center justify-content-between gap-3">
            {{-- Quick Sub-Tab Navigation --}}
            <ul class="nav nav-pills nav-pills-custom gap-2 mb-0">
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-light-primary fw-bold {{ ($tab ?? 'all') === 'all' ? 'active bg-primary text-white' : '' }}" 
                       href="{{ route('call.history', array_merge(request()->except('tab', 'page'), ['provider' => $provider ?? 'next2call', 'tab' => 'all'])) }}">
                        All Calls ({{ $totalCalls ?? 0 }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-light-danger fw-bold {{ ($tab ?? '') === 'missed' ? 'active bg-danger text-white' : '' }}" 
                       href="{{ route('call.history', array_merge(request()->except('tab', 'page'), ['provider' => $provider ?? 'next2call', 'tab' => 'missed'])) }}">
                        <i class="fa fa-phone-slash me-1"></i> Missed Calls
                        @if(($missedCalls ?? 0) > 0)
                            <span class="badge badge-circle badge-white text-danger ms-1 fw-bolder">{{ $missedCalls }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-light-info fw-bold {{ ($tab ?? '') === 'inbound' ? 'active bg-info text-white' : '' }}" 
                       href="{{ route('call.history', array_merge(request()->except('tab', 'page'), ['provider' => $provider ?? 'next2call', 'tab' => 'inbound'])) }}">
                        <i class="fa fa-arrow-down me-1"></i> Inbound ({{ $inboundCalls ?? 0 }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-light-primary fw-bold {{ ($tab ?? '') === 'outbound' ? 'active bg-primary text-white' : '' }}" 
                       href="{{ route('call.history', array_merge(request()->except('tab', 'page'), ['provider' => $provider ?? 'next2call', 'tab' => 'outbound'])) }}">
                        <i class="fa fa-arrow-up me-1"></i> Outbound ({{ $outboundCalls ?? 0 }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-light-success fw-bold {{ in_array(($tab ?? ''), ['completed', 'answered']) ? 'active bg-success text-white' : '' }}" 
                       href="{{ route('call.history', array_merge(request()->except('tab', 'page'), ['provider' => $provider ?? 'next2call', 'tab' => ($provider === 'next2call' ? 'answered' : 'completed')])) }}">
                        <i class="fa fa-check me-1"></i> {{ ($provider ?? 'next2call') === 'next2call' ? 'Answered' : 'Completed' }} ({{ $completedCalls ?? 0 }})
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body pt-1 pb-4 px-4 border-top">
            <form id="callFilterForm" method="GET" action="{{ route('call.history') }}" class="row g-2 align-items-end">
                <input type="hidden" name="provider" value="{{ $provider ?? 'next2call' }}">
                <input type="hidden" name="tab" value="{{ $tab ?? 'all' }}">
                
                <div class="col-md-3 position-relative">
                    <label class="form-label fw-bold fs-8 text-uppercase mb-1">Search Contact / Number / ID</label>
                    <div class="position-relative">
                        <input type="text" id="callSearchInput" name="search" class="form-control form-control-sm form-control-solid pe-8"
                               placeholder="User, Number, DID, ID..." value="{{ request('search') }}" autocomplete="off">
                        <input type="hidden" id="callSelectedUid" name="uid" value="{{ request('uid') }}">
                        @if(request('search') || request('uid'))
                            <a href="{{ route('call.history', ['provider' => $provider ?? 'next2call', 'tab' => $tab ?? 'all']) }}" 
                               class="position-absolute end-0 top-50 translate-middle-y me-2 text-muted text-hover-primary" title="Clear search">
                                <i class="fa fa-times-circle fs-6"></i>
                            </a>
                        @endif
                        <!-- Custom search results dropdown like Lead / Order -->
                        <div id="callSearchResults" class="dropdown-menu w-100 shadow-lg p-0 mt-1" 
                             style="display:none; max-height: 280px; overflow-y: auto; z-index: 1050; position: absolute; left: 0; top: 100%;"></div>
                    </div>
                </div>

                @if(($tab ?? 'all') === 'all')
                <div class="col-md-2">
                    <label class="form-label fw-bold fs-8 text-uppercase mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm form-select-solid">
                        <option value="">All Statuses</option>
                        @if(($provider ?? 'next2call') === 'next2call')
                            <option value="ANSWER" {{ strtoupper(request('status')) === 'ANSWER' ? 'selected' : '' }}>Answered</option>
                            <option value="NOANSWER" {{ strtoupper(request('status')) === 'NOANSWER' ? 'selected' : '' }}>No Answer</option>
                            <option value="CANCEL" {{ strtoupper(request('status')) === 'CANCEL' ? 'selected' : '' }}>Cancelled</option>
                            <option value="CONGESTION" {{ strtoupper(request('status')) === 'CONGESTION' ? 'selected' : '' }}>Congestion</option>
                        @else
                            @foreach(['completed','missed','no-answer','failed','cancelled','in-progress'] as $s)
                                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('-', ' ', $s)) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold fs-8 text-uppercase mb-1">Direction</label>
                    <select name="direction" class="form-select form-select-sm form-select-solid">
                        <option value="">All Directions</option>
                        <option value="inbound"  {{ request('direction') == 'inbound'  ? 'selected' : '' }}>Inbound</option>
                        <option value="outbound" {{ request('direction') == 'outbound' ? 'selected' : '' }}>Outbound</option>
                    </select>
                </div>
                @endif

                <div class="col-md-2">
                    <label class="form-label fw-bold fs-8 text-uppercase mb-1">From Date</label>
                    <input type="date" name="date_from" class="form-control form-control-sm form-control-solid"
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold fs-8 text-uppercase mb-1">To Date</label>
                    <input type="date" name="date_to" class="form-control form-control-sm form-control-solid"
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100" title="Search"><i class="fa fa-search"></i></button>
                    <a href="{{ route('call.history', ['provider' => $provider ?? 'next2call', 'tab' => $tab ?? 'all']) }}" class="btn btn-sm btn-light w-100" title="Reset Filters"><i class="fa fa-refresh"></i></a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="callHistoryTable">
                    <thead class="bg-light">
                        <tr class="fw-bolder text-muted fs-8 text-uppercase">
                            <th class="ps-4" style="width: 50px;">#</th>
                            <th style="min-width: 140px;">Date & Time</th>
                            <th style="min-width: 90px;">Direction</th>
                            <th style="min-width: 110px;">Status</th>
                            <th style="min-width: 170px;">Customer / Contact</th>
                            @if(($provider ?? 'next2call') === 'next2call')
                                <th style="min-width: 110px;">DID Number</th>
                                <th style="min-width: 90px;">Agent</th>
                                <th style="min-width: 80px;">Duration</th>
                                <th style="min-width: 70px;">Hangup</th>
                                <th style="min-width: 210px;">Audio Recording</th>
                            @else
                                <th style="min-width: 130px;">From</th>
                                <th style="min-width: 130px;">To</th>
                                <th style="min-width: 90px;">Duration</th>
                                <th style="min-width: 200px;">Audio Recording</th>
                                <th style="min-width: 110px;">Agent</th>
                            @endif
                            <th style="min-width: 110px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $i => $log)

                        {{-- Next2Call Provider Table Row --}}
                        @if(($provider ?? 'next2call') === 'next2call')
                            @php
                                $statusUpper = strtoupper($log->status);
                                $isMissed = in_array($statusUpper, ['NOANSWER', 'CANCEL', 'CONGESTION']);
                                
                                // Determine customer phone
                                $candidatePhone = ($log->call_to == $log->agent_id) 
                                    ? ($log->call_from ?: $log->call_to) 
                                    : ($log->call_to ?: $log->call_from);
                                
                                $displayCustomerPhone = ($isSuperAdmin ?? false) ? $candidatePhone : mask_raw_phone($candidatePhone);
                                $displayName = $log->customer_name ?: 'Customer';
                                if (!($isSuperAdmin ?? false) && preg_match('/^user\d{7,}$/i', $displayName)) {
                                    $displayName = 'user' . mask_mobile_only(null, substr($displayName, 4));
                                }

                                $cleanTargetDigits = preg_replace('/\D/', '', (string) $candidatePhone);
                                $whatsAppUrl = !empty($cleanTargetDigits) ? route('whatsapp.chat', ['phone' => $cleanTargetDigits]) : route('whatsapp.chat');

                                $hasLocal = !empty($log->local_record_path) && file_exists(public_path($log->local_record_path));
                                $audioSrc = $hasLocal ? asset($log->local_record_path) : $log->record_url;
                            @endphp
                            <tr class="{{ $isMissed ? 'table-danger-light' : '' }}">
                                <td class="ps-4 text-muted fs-8">{{ $logs->firstItem() + $i }}</td>
                                <td>
                                    <div class="fw-bold fs-7 text-dark">{{ $log->started_at ? $log->started_at->format('d M Y') : ($log->created_at ? $log->created_at->format('d M Y') : '—') }}</div>
                                    <div class="text-muted fs-8">{{ $log->started_at ? $log->started_at->format('h:i:s A') : ($log->created_at ? $log->created_at->format('h:i:s A') : '') }}</div>
                                </td>
                                <td>
                                    @if($log->direction === 'inbound')
                                        <span class="badge badge-light-info fs-8 fw-semibold d-inline-flex align-items-center gap-1">
                                            <i class="fa fa-arrow-down text-info" style="font-size: 11px;"></i> Inbound
                                        </span>
                                    @else
                                        <span class="badge badge-light-primary fs-8 fw-semibold d-inline-flex align-items-center gap-1">
                                            <i class="fa fa-arrow-up text-primary" style="font-size: 11px;"></i> Outbound
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($statusUpper === 'ANSWER')
                                        <span class="badge badge-light-success fs-8 fw-bold d-inline-flex align-items-center gap-1 px-2.5 py-1">
                                            <i class="fa fa-check text-success" style="font-size: 11px;"></i> Answered
                                        </span>
                                    @elseif($statusUpper === 'NOANSWER')
                                        <span class="badge badge-light-danger fs-8 fw-bolder d-inline-flex align-items-center gap-1 px-2.5 py-1">
                                            <i class="fa fa-phone-slash text-danger" style="font-size: 11px;"></i> Missed
                                        </span>
                                    @elseif($statusUpper === 'CANCEL')
                                        <span class="badge badge-light-warning fs-8 fw-bold d-inline-flex align-items-center gap-1 px-2.5 py-1">
                                            <i class="fa fa-times text-warning" style="font-size: 11px;"></i> Cancelled
                                        </span>
                                    @elseif($statusUpper === 'CONGESTION')
                                        <span class="badge badge-light-dark fs-8 fw-bold px-2.5 py-1">
                                            Congestion
                                        </span>
                                    @else
                                        <span class="badge {{ $log->status_badge }} fs-8 fw-bold px-2.5 py-1">
                                            {{ $log->status_label }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold fs-7 text-dark text-truncate" style="max-width: 170px;" title="{{ $displayName }}">
                                        {{ $displayName }}
                                    </div>
                                    <div class="font-monospace text-muted fs-8">
                                        {{ $displayCustomerPhone ?: '—' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="font-monospace fs-8 text-dark">{{ $log->did ?: '—' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-secondary fs-8 fw-semibold">{{ $log->agent_id ?: ($log->agent?->name ?? '—') }}</span>
                                </td>
                                <td>
                                    @if($log->duration > 0)
                                        <span class="badge badge-light-success fs-8 fw-semibold">
                                            <i class="fa fa-clock me-1" style="font-size: 10px;"></i>{{ $log->duration_formatted ?: gmdate('H:i:s', $log->duration) }}
                                        </span>
                                    @else
                                        <span class="text-muted fs-8">00:00:00</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->hangup)
                                        <span class="badge badge-light-{{ $log->hangup === 'AGENT' ? 'primary' : 'info' }} fs-9">
                                            {{ ucfirst(strtolower($log->hangup)) }}
                                        </span>
                                    @else
                                        <span class="text-muted fs-9">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->record_url || $hasLocal)
                                        <div class="d-inline-flex align-items-center gap-1 bg-light rounded p-1" id="rec-wrap-{{ $log->id }}">
                                            <audio controls preload="none" style="height: 28px; width: 135px;" id="audio-{{ $log->id }}">
                                                <source src="{{ $audioSrc }}" type="audio/wav">
                                                Your browser does not support audio.
                                            </audio>
                                            <div id="rec-actions-{{ $log->id }}" class="d-inline-flex align-items-center gap-1">
                                                @if($hasLocal)
                                                    <span class="badge badge-light-success fs-9 py-1 px-1.5" title="Saved locally in CRM server">
                                                        <i class="fa fa-check text-success fs-9"></i> Saved
                                                    </span>
                                                    <a href="{{ route('next2call.save-recording', ['id' => $log->id, 'download' => 1]) }}" 
                                                       class="btn btn-icon btn-xs btn-light-primary" 
                                                       title="Download Saved Audio (.wav)">
                                                        <i class="fa fa-download fs-9"></i>
                                                    </a>
                                                @else
                                                    <button type="button" 
                                                            class="btn btn-xs btn-light-success px-2 py-1 fs-9 fw-bold" 
                                                            onclick="saveRecordingLocally({{ $log->id }}, this)" 
                                                            title="Download and save recording locally to CRM server">
                                                        <i class="fa fa-save fs-9 me-1"></i> Save
                                                    </button>
                                                    <a href="{{ route('next2call.save-recording', ['id' => $log->id, 'download' => 1]) }}" 
                                                       class="btn btn-icon btn-xs btn-light-primary" 
                                                       title="Direct Download (.wav)">
                                                        <i class="fa fa-download fs-9"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge badge-light-secondary text-muted fs-9">No recording</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        @if(!empty($candidatePhone))
                                            {{-- Next2Call Button (with red 2 badge) --}}
                                            <button type="button" 
                                                    class="btn btn-icon btn-sm btn-light-success hover-elevate-up position-relative" 
                                                    style="width: 30px; height: 30px;"
                                                    onclick="initiateNext2Call('{{ $candidatePhone }}', '{{ addslashes($displayName) }}')" 
                                                    title="Call via Next2Call: {{ $displayCustomerPhone }}">
                                                <i class="fa fa-phone text-success fs-7"></i>
                                                <span style="position:absolute;bottom:-2px;right:-1px;background:#e53e3e;color:#ffffff;font-size:8px;font-weight:900;line-height:1;padding:1px 2.5px;border-radius:2px;box-shadow:0 1px 2px rgba(0,0,0,0.3);font-family:Arial,sans-serif;pointer-events:none;">2</span>
                                            </button>

                                            {{-- Twilio Call Button --}}
                                            <button type="button" 
                                                    class="btn btn-icon btn-sm btn-light-primary hover-elevate-up" 
                                                    style="width: 30px; height: 30px;"
                                                    onclick="initiateTwilioCall('{{ $candidatePhone }}', '{{ addslashes($displayName) }}')" 
                                                    title="Call via Twilio: {{ $displayCustomerPhone }}">
                                                <i class="fa fa-phone text-primary fs-7"></i>
                                            </button>

                                            {{-- WhatsApp Action --}}
                                            <a href="{{ $whatsAppUrl }}" 
                                               target="_blank" 
                                               class="btn btn-icon btn-sm btn-light-success hover-elevate-up" 
                                               style="width: 30px; height: 30px;"
                                               title="Chat on WhatsApp ({{ $displayCustomerPhone }})">
                                                <svg width="15" height="15" viewBox="0 0 16 16" fill="#25D366"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                                            </a>
                                        @else
                                            <span class="text-muted fs-9">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                        {{-- Twilio Provider Table Row --}}
                        @else
                            @php
                                $isMissed = in_array($log->status, ['missed', 'no-answer']);
                                $customerPhone = $log->direction === 'inbound' ? ($log->from_number ?: $log->to_number) : ($log->to_number ?: $log->from_number);
                                $displayFrom = ($isSuperAdmin ?? false) ? $log->from_number : mask_raw_phone($log->from_number);
                                $displayTo = ($isSuperAdmin ?? false) ? $log->to_number : mask_raw_phone($log->to_number);
                                $displayCustomerPhone = ($isSuperAdmin ?? false) ? $customerPhone : mask_raw_phone($customerPhone);
                                
                                $cleanTargetDigits = preg_replace('/\D/', '', (string) $customerPhone);
                                $whatsAppUrl = !empty($cleanTargetDigits) ? route('whatsapp.chat', ['phone' => $cleanTargetDigits]) : route('whatsapp.chat');
                            @endphp
                            <tr class="{{ $isMissed ? 'table-danger-light' : '' }}">
                                <td class="ps-4 text-muted fs-8">{{ $logs->firstItem() + $i }}</td>
                                <td>
                                    <div class="fw-bold fs-7 text-dark">{{ $log->created_at ? $log->created_at->format('d M Y') : '—' }}</div>
                                    <div class="text-muted fs-8">{{ $log->created_at ? $log->created_at->format('h:i:s A') : '' }}</div>
                                </td>
                                <td>
                                    @if($log->direction === 'inbound')
                                        <span class="badge badge-light-info fs-8 fw-semibold d-inline-flex align-items-center gap-1">
                                            <i class="fa fa-arrow-down text-info" style="font-size: 11px;"></i> Inbound
                                        </span>
                                    @else
                                        <span class="badge badge-light-primary fs-8 fw-semibold d-inline-flex align-items-center gap-1">
                                            <i class="fa fa-arrow-up text-primary" style="font-size: 11px;"></i> Outbound
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($isMissed)
                                        <span class="badge badge-light-danger fs-8 fw-bolder d-inline-flex align-items-center gap-1 px-2.5 py-1">
                                            <i class="fa fa-phone-slash text-danger" style="font-size: 11px;"></i> Missed Call
                                        </span>
                                    @elseif($log->status === 'completed')
                                        <span class="badge badge-light-success fs-8 fw-bold d-inline-flex align-items-center gap-1 px-2.5 py-1">
                                            <i class="fa fa-check text-success" style="font-size: 11px;"></i> Completed
                                        </span>
                                    @elseif($log->status === 'failed')
                                        <span class="badge badge-light-danger fs-8 fw-bold d-inline-flex align-items-center gap-1 px-2.5 py-1">
                                            <i class="fa fa-times text-danger" style="font-size: 11px;"></i> Failed
                                        </span>
                                    @elseif($log->status === 'in-progress')
                                        <span class="badge badge-light-info fs-8 fw-bold d-inline-flex align-items-center gap-1 px-2.5 py-1">
                                            <i class="fa fa-spinner fa-spin text-info" style="font-size: 11px;"></i> In Progress
                                        </span>
                                    @else
                                        <span class="badge {{ $log->status_badge }} fs-8 fw-bold px-2.5 py-1">
                                            {{ $log->status_label }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $displayName = $log->customer_name ?: 'Unknown Contact';
                                        if (!($isSuperAdmin ?? false) && preg_match('/^user\d{7,}$/i', $displayName)) {
                                            $displayName = 'user' . mask_mobile_only(null, substr($displayName, 4));
                                        }
                                    @endphp
                                    <div class="fw-bold fs-7 text-dark text-truncate" style="max-width: 170px;" title="{{ $displayName }}">
                                        {{ $displayName }}
                                    </div>
                                    <div class="font-monospace text-muted fs-8">
                                        {{ $displayCustomerPhone ?: '—' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="font-monospace fs-8 text-dark">{{ $displayFrom ?: '—' }}</span>
                                </td>
                                <td>
                                    <span class="font-monospace fs-8 text-dark">{{ $displayTo ?: '—' }}</span>
                                </td>
                                <td>
                                    @if($log->duration > 0)
                                        <span class="badge badge-light-success fs-8 fw-semibold">
                                            <i class="fa fa-clock me-1" style="font-size: 10px;"></i>{{ $log->formatted_duration }}
                                        </span>
                                    @else
                                        <span class="text-muted fs-8">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->recording_url)
                                        <div class="d-inline-flex align-items-center gap-1 bg-light rounded p-1">
                                            <audio controls preload="none" style="height: 28px; width: 145px;">
                                                <source src="{{ $log->recording_url }}" type="audio/mpeg">
                                                Your browser does not support audio.
                                            </audio>
                                            <a href="{{ $log->recording_url }}" target="_blank" download class="btn btn-icon btn-xs btn-light-primary" title="Download Audio (MP3)">
                                                <i class="fa fa-download fs-9"></i>
                                            </a>
                                        </div>
                                    @else
                                        <span class="badge badge-light-secondary text-muted fs-9">No recording</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-dark fs-8 fw-semibold">{{ $log->agent?->name ?? '—' }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        @if(!empty($customerPhone))
                                            {{-- Next2Call Button (with red 2 badge) --}}
                                            <button type="button" 
                                                    class="btn btn-icon btn-sm btn-light-success hover-elevate-up position-relative" 
                                                    style="width: 30px; height: 30px;"
                                                    onclick="initiateNext2Call('{{ $customerPhone }}', '{{ addslashes($displayName) }}')" 
                                                    title="Call via Next2Call: {{ $displayCustomerPhone }}">
                                                <i class="fa fa-phone text-success fs-7"></i>
                                                <span style="position:absolute;bottom:-2px;right:-1px;background:#e53e3e;color:#ffffff;font-size:8px;font-weight:900;line-height:1;padding:1px 2.5px;border-radius:2px;box-shadow:0 1px 2px rgba(0,0,0,0.3);font-family:Arial,sans-serif;pointer-events:none;">2</span>
                                            </button>

                                            {{-- Twilio Call Button --}}
                                            <button type="button" 
                                                    class="btn btn-icon btn-sm btn-light-primary hover-elevate-up" 
                                                    style="width: 30px; height: 30px;"
                                                    onclick="initiateTwilioCall('{{ $customerPhone }}', '{{ addslashes($displayName) }}')" 
                                                    title="Call via Twilio: {{ $displayCustomerPhone }}">
                                                <i class="fa fa-phone text-primary fs-7"></i>
                                            </button>

                                            {{-- WhatsApp Action --}}
                                            <a href="{{ $whatsAppUrl }}" 
                                               target="_blank" 
                                               class="btn btn-icon btn-sm btn-light-success hover-elevate-up" 
                                               style="width: 30px; height: 30px;"
                                               title="Chat on WhatsApp ({{ $displayCustomerPhone }})">
                                                <svg width="15" height="15" viewBox="0 0 16 16" fill="#25D366"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                                            </a>
                                        @else
                                            <span class="text-muted fs-9">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif

                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-10 text-muted">
                                <div class="d-inline-flex p-4 rounded-circle bg-light-primary mb-3">
                                    <i class="fa fa-phone fs-1 text-primary"></i>
                                </div>
                                <h6 class="fw-bold text-gray-800 mb-1">No call records found</h6>
                                <p class="text-muted fs-8 mb-0">No calls match your filter criteria or tab for {{ ($provider ?? 'next2call') === 'next2call' ? 'Next2Call' : 'Twilio' }}.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
        <div class="card-footer py-3 px-4 d-flex justify-content-end">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

</div>

{{-- Twilio Call Helper Script --}}
@include('layouts.partials.twilio-call-helper')

<script>
    // Sync calls from Next2Call PBX
    function syncNext2CallCalls() {
        const $btn = $('#btnSyncNext2Call');
        const $icon = $('#syncIcon');
        $btn.prop('disabled', true);
        $icon.addClass('fa-spin');

        $.ajax({
            url: "{{ route('next2call.sync') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                limit: 100
            },
            success: function(res) {
                if (typeof toastr !== 'undefined') {
                    toastr.success(res.message || 'Call report synchronized successfully.');
                }
                setTimeout(function() {
                    window.location.reload();
                }, 800);
            },
            error: function(err) {
                $btn.prop('disabled', false);
                $icon.removeClass('fa-spin');
                const msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Failed to sync Next2Call calls.';
                if (typeof toastr !== 'undefined') toastr.error(msg);
                else alert(msg);
            }
        });
    }

    // Save Next2Call recording locally to CRM server
    function saveRecordingLocally(logId, btnElem) {
        const $btn = $(btnElem);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin fs-9"></i> Saving...');

        $.ajax({
            url: "{{ route('next2call.save-recording') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: logId
            },
            success: function(res) {
                if (res.success) {
                    if (typeof toastr !== 'undefined') toastr.success(res.message || 'Recording saved locally on server.');
                    
                    // Replace action buttons with 'Saved' badge and download button
                    const downloadUrl = "{{ route('next2call.save-recording') }}?id=" + logId + "&download=1";
                    const newHtml = '<span class="badge badge-light-success fs-9 py-1 px-1.5" title="Saved locally in CRM server">' +
                        '<i class="fa fa-check text-success fs-9"></i> Saved</span>' +
                        '<a href="' + downloadUrl + '" class="btn btn-icon btn-xs btn-light-primary" title="Download Audio (.wav)">' +
                        '<i class="fa fa-download fs-9"></i></a>';
                    
                    $('#rec-actions-' + logId).html(newHtml);

                    // Update audio element src to local file
                    if (res.local_url) {
                        const audio = document.getElementById('audio-' + logId);
                        if (audio) {
                            audio.src = res.local_url;
                        }
                    }
                } else {
                    $btn.prop('disabled', false).html(originalHtml);
                    if (typeof toastr !== 'undefined') toastr.error(res.message || 'Could not save recording.');
                    else alert(res.message);
                }
            },
            error: function(err) {
                $btn.prop('disabled', false).html(originalHtml);
                const msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Error downloading recording.';
                if (typeof toastr !== 'undefined') toastr.error(msg);
                else alert(msg);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ === 'undefined') return;

        let callSearchTimeout = null;
        const $searchInput = $('#callSearchInput');
        const $resultsDropdown = $('#callSearchResults');
        const $selectedUid = $('#callSelectedUid');
        const $filterForm = $('#callFilterForm');

        $searchInput.on('input focus', function() {
            const searchValue = $(this).val().trim();
            clearTimeout(callSearchTimeout);

            if (searchValue.length >= 2) {
                $resultsDropdown.html(
                    '<div class="p-3 text-center text-muted fs-8 d-flex align-items-center justify-content-center gap-2">' +
                        '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>' +
                        '<span>Searching contacts & leads...</span>' +
                    '</div>'
                ).show();

                callSearchTimeout = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('search-order') }}",
                        type: "GET",
                        data: {
                            user: searchValue
                        },
                        success: function(response) {
                            let resultsHtml = '';
                            if (response && response.length > 0) {
                                $.each(response, function(key, value) {
                                    const mobileStr = value.mobile_no ? '<span class="text-primary fw-semibold"><i class="fa fa-phone fs-9 me-1"></i>' + value.mobile_no + '</span>' : '';
                                    const emailStr = value.email ? '<span class="text-muted">' + value.email + '</span>' : '';
                                    resultsHtml += '<a href="javascript:void(0)" class="dropdown-item call-user-select-item p-2.5 border-bottom text-wrap" ' +
                                        'data-id="' + value.id + '" data-email="' + (value.email || '') + '" data-name="' + (value.name || '') + '" data-mobile="' + (value.mobile_no || '') + '">' +
                                        '<div class="d-flex justify-content-between align-items-center mb-0.5">' +
                                            '<span class="fw-bold text-dark fs-7">' + (value.name || 'User') + '</span>' +
                                            '<span class="badge badge-light-secondary fs-9">#' + value.id + '</span>' +
                                        '</div>' +
                                        '<div class="fs-8 d-flex flex-wrap align-items-center gap-2">' +
                                            emailStr + (emailStr && mobileStr ? '<span class="text-muted">•</span>' : '') + mobileStr +
                                        '</div>' +
                                    '</a>';
                                });
                            } else {
                                resultsHtml = '<div class="p-3 text-muted fs-8 text-center">No contact found. Press Enter to search calls directly.</div>';
                            }
                            $resultsDropdown.html(resultsHtml).show();
                        },
                        error: function() {
                            $resultsDropdown.html('<div class="p-3 text-danger fs-8 text-center">Error searching contacts</div>').show();
                        }
                    });
                }, 250);
            } else {
                $resultsDropdown.hide().empty();
                if (searchValue.length === 0) {
                    $selectedUid.val('');
                }
            }
        });

        // Click on custom dropdown item
        $(document).on('click', '.call-user-select-item', function(e) {
            e.preventDefault();
            const selectedId = $(this).attr('data-id');
            const selectedName = $(this).attr('data-name');
            const selectedMobile = $(this).attr('data-mobile');
            const selectedEmail = $(this).attr('data-email');

            $selectedUid.val(selectedId);
            $searchInput.val(selectedMobile || selectedName || selectedEmail);
            $resultsDropdown.hide().empty();

            $filterForm.submit();
        });

        // Close dropdown on clicking outside or escape
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#callSearchInput, #callSearchResults').length) {
                $resultsDropdown.hide();
            }
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $resultsDropdown.hide();
            }
        });
    });
</script>
@endsection
