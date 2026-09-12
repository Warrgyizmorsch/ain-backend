@extends('layouts.app')
@section('content')
<style>
    .followup-table th, 
    .followup-table td {
        border: 1px solid #e4e6ef !important;
        vertical-align: middle;
    }
    .followup-table thead th {
        background-color: #f5f8fa !important;
        color: #3f4254 !important;
        font-weight: 700 !important;
    }
    /* Eliminate extra gap between toolbar and filter */
    #kt_content,
    .content {
        padding-top: 0 !important;
    }
    .toolbar,
    #kt_toolbar {
        margin-bottom: 0 !important;
    }
    .follow-filter-card {
        margin-top: 0 !important;
    }
    #searchResultss .user-select-item {
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
    }
    #searchResultss .user-select-item:hover {
        background-color: #f1faff !important;
    }
    /* Follow-up History Side Toggle Drawer */
    .followup-history-drawer {
        position: fixed !important;
        top: 0 !important;
        right: 0 !important;
        width: 500px !important;
        max-width: 95vw !important;
        height: 100vh !important;
        background: #ffffff !important;
        box-shadow: -6px 0 35px rgba(0, 0, 0, 0.22) !important;
        z-index: 100050 !important;
        display: flex !important;
        flex-direction: column !important;
        transform: translateX(100%) !important;
        transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        visibility: hidden !important;
    }
    .followup-history-drawer.show {
        transform: translateX(0) !important;
        visibility: visible !important;
    }
    .followup-drawer-backdrop {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background: rgba(0, 0, 0, 0.45) !important;
        backdrop-filter: blur(2px) !important;
        z-index: 100040 !important;
        opacity: 0 !important;
        visibility: hidden !important;
        transition: opacity 0.25s ease-in-out !important;
    }
    .followup-drawer-backdrop.show {
        opacity: 1 !important;
        visibility: visible !important;
    }
</style>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Follow-Up Orders
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In Need</small>
                    </h1>
                </div>
            </div>
        </div>

        {{-- Filter Box --}}
        <div class="col-xl-12 mt-2">
            <div class="card card-xxl-stretch mb-5 mb-xl-8 follow-filter-card">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Filter</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('follow-up') }}" id="followUpFilterForm">
                        <div class="row g-3 mb-3">
                            <div class="col-md-3 fv-row">
                                <label class="form-label fw-bold fs-7">Order Code / Title</label>
                                <input type="search" name="search" id="search" class="form-control form-control-solid" placeholder="Search By Order Code" value="{{ request('search') }}">
                            </div>

                            <div class="col-md-3 fv-row">
                                <label class="form-label fw-bold fs-7">Customer (Name / Number / Email)</label>
                                <div class="position-relative">
                                    <input type="text" id="searchInput" name="user" class="form-control form-control-solid pe-10" placeholder="Search by Name, Number, Email..." autocomplete="off" value="{{ request('user') }}">
                                    <!-- In-input preloader spinner -->
                                    <span id="searchSpinner" class="position-absolute end-0 top-50 translate-middle-y me-3" style="display:none; pointer-events: none; z-index: 10;">
                                        <span class="spinner-border spinner-border-sm text-primary" role="status" style="width: 1.1rem; height: 1.1rem; border-width: 2px;">
                                            <span class="visually-hidden">Loading...</span>
                                        </span>
                                    </span>
                                    <!-- Single Custom Dropdown -->
                                    <div id="searchResultss" class="dropdown-menu w-100 shadow-lg p-0 mt-1" style="display:none; max-height: 260px; overflow-y: auto; z-index: 1050; position: absolute; left: 0; top: 100%; background: #ffffff !important; border: 1px solid #d8dbe0;"></div>
                                </div>
                                <input type="hidden" id="selectedValue" name="uid" value="{{ request('uid') }}">
                            </div>

                            <div class="col-md-2 fv-row">
                                <label class="form-label fw-bold fs-7">Follow-Up Status</label>
                                <select name="status" id="status" class="form-select form-select-solid">
                                    <option value="">All Statuses</option>
                                    <option value="negative but convinced" {{ request('status') == 'negative but convinced' ? 'selected' : '' }}>negative but convinced</option>
                                    <option value="negative" {{ request('status') == 'negative' ? 'selected' : '' }}>negative</option>
                                    <option value="positive" {{ request('status') == 'positive' ? 'selected' : '' }}>positive</option>
                                    <option value="positive and referral" {{ request('status') == 'positive and referral' ? 'selected' : '' }}>positive and referral</option>
                                    <option value="positive and own order" {{ request('status') == 'positive and own order' ? 'selected' : '' }}>positive and own order</option>
                                    <option value="No response" {{ request('status') == 'No response' ? 'selected' : '' }}>No response</option>
                                </select>
                            </div>

                            <div class="col-md-2 fv-row">
                                <label class="form-label fw-bold fs-7">From Date</label>
                                <input type="date" name="fromDate" id="fromDate" class="form-control form-control-solid" value="{{ request('fromDate') }}">
                            </div>

                            <div class="col-md-2 fv-row">
                                <label class="form-label fw-bold fs-7">To Date</label>
                                <input type="date" name="toDate" id="toDate" class="form-control form-control-solid" value="{{ request('toDate') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-primary px-5">
                                    <i class="fa fa-search me-1"></i> Search
                                </button>
                                <a href="{{ route('follow-up') }}" class="btn btn-sm btn-danger px-5">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @include('layouts.flash')

            {{-- Table Card --}}
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Orders</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Total {{ $data['orders']->total() }} orders found</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle gs-3 gy-3 border mb-0 followup-table">
                            <thead>
                                <tr class="fw-bolder text-dark bg-light border-bottom border-gray-300">
                                    <th class="w-25px text-center">Check</th>
                                    <th class="w-30px text-center">SR</th>
                                    <th class="min-w-100px text-center">Order Code</th>
                                    <th class="min-w-200px">User Details</th>
                                    <th class="min-w-90px text-center">Order Date</th>
                                    <th class="min-w-90px text-center">Follow Up Date</th>
                                    <th style="width: 140px;" class="text-center">Status</th>
                                    <th class="min-w-140px">Comments</th>
                                    <th class="min-w-90px">Follow Up User</th>
                                    <th class="min-w-70px text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['orders'] as $order)
                                    <tr>
                                        <td class="text-center">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid d-inline-block">
                                                <input onchange="FollowUpUser(this, {{ $order->user?->id ?? 0 }})" class="form-check-input widget-13-check" type="checkbox" {{ ($order->user?->followup == 1) ? 'checked' : '' }} value="1">
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold">{{ ($data['orders']->currentPage() - 1) * $data['orders']->perPage() + $loop->iteration }}</td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center justify-content-center">
                                                <span class="fw-bold">{{ $order->order_id }}</span>
                                                @if(!empty($order->order_id))
                                                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Order Code" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $order->order_id }}', 'Order code copied!');">
                                                        <i class="fa fa-clone fs-8 text-muted"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($order->user)
                                                @php
                                                    $isSuperAdmin = auth()->check() && (int) auth()->user()->role_id === 1;
                                                    $rawName = $order->user->name ?? '';
                                                    $rawEmail = $order->user->email ?? '';
                                                    $rawMobile = $order->user->mobile_no ?? '';
                                                    $rawCC = $order->user->countrycode ?? '';
                                                    $cleanCC = preg_replace('/\D+/', '', (string)$rawCC);
                                                    $maskedEmail = $isSuperAdmin ? $rawEmail : ($rawEmail ? mask_email_for_display($rawEmail) : '');
                                                    $maskedMobile = $isSuperAdmin ? $rawMobile : ($rawMobile ? mask_mobile_only($cleanCC, $rawMobile) : '');

                                                    $orderRawWAPhone = preg_replace('/\D+/', '', (string)($cleanCC . $rawMobile));
                                                    $orderEmailUrl = route('emails.index', array_filter(['account_id' => 2, 'search' => $rawEmail]));
                                                    $orderWhatsAppUrl = !empty($orderRawWAPhone) ? route('whatsapp.chat', ['phone' => $orderRawWAPhone]) : route('whatsapp.chat');
                                                @endphp

                                                <div class="d-flex flex-column gap-1 py-1">
                                                    {{-- 1. User Name (Top line) --}}
                                                    @if(!empty($rawName))
                                                        <div class="d-flex align-items-center">
                                                            <span class="fw-bolder text-dark fs-6">{{ $rawName }}</span>
                                                            <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Name" onclick="event.stopPropagation(); crmCopyToClipboard('{{ addslashes($rawName) }}', 'Customer name copied!');">
                                                                <i class="fa fa-clone fs-8 text-muted"></i>
                                                            </button>
                                                        </div>
                                                    @endif

                                                    {{-- 2. Email (Below Name) --}}
                                                    @if(!empty($maskedEmail))
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-muted fs-8 text-break">{{ $maskedEmail }}</span>
                                                            <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Email" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $maskedEmail }}', 'Email copied!');">
                                                                <i class="fa fa-clone fs-8 text-muted"></i>
                                                            </button>
                                                        </div>
                                                    @endif

                                                    {{-- 3. Mobile Number with CC, Masking & Copy Button --}}
                                                    @if(!empty($maskedMobile))
                                                        <div class="d-flex align-items-center flex-wrap gap-1 mt-1">
                                                            @if(!empty($cleanCC))
                                                                <span class="badge badge-light-primary fs-8 fw-bold py-1 px-2">+{{ $cleanCC }}</span>
                                                            @endif
                                                            <span class="badge badge-light-danger fs-7 fw-bold py-1 px-2">{{ $maskedMobile }}</span>
                                                            <button type="button" class="btn btn-icon btn-sm btn-active-light-danger p-0 flex-shrink-0" style="width: 20px; height: 20px;" title="Copy Mobile" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $maskedMobile }}', 'Mobile number copied!');">
                                                                <i class="fa fa-clone fs-8 text-danger"></i>
                                                            </button>
                                                        </div>
                                                    @endif

                                                    {{-- Direct Contact Action Icons: Call, WhatsApp, Email (Client) --}}
                                                    <div class="d-inline-flex align-items-center gap-1 mt-1">
                                                        @if(!empty($rawMobile))
                                                            <a href="#" 
                                                               id="twilioCallBtnfollowup{{ $order->id }}"
                                                               onclick="event.preventDefault(); event.stopPropagation(); initiateCustomerCall('{{ $cleanCC . $rawMobile }}', '{{ addslashes($rawName ?: 'Customer') }}');"
                                                               class="btn btn-icon btn-sm shadow-sm call-btn-styled"
                                                               style="width: 24px; height: 24px; min-width: 24px; border-radius: 6px; background-color: #25D366; color: #ffffff; display: inline-flex; align-items: center; justify-content: center; transition: transform 0.2s ease, background-color 0.2s ease;"
                                                               onmouseover="this.style.backgroundColor='#1ebd58'; this.style.transform='scale(1.1)';"
                                                               onmouseout="this.style.backgroundColor='#25D366'; this.style.transform='scale(1)';"
                                                               title="Call: {{ $cleanCC . $rawMobile }}">
                                                                <i class="fa fa-phone text-white" style="font-size: 11px;"></i>
                                                            </a>
                                                        @endif

                                                        <a href="{{ $orderWhatsAppUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-wa" style="width: 24px !important; height: 24px !important; min-width: 24px !important;" title="WhatsApp: {{ $orderRawWAPhone ?: 'Open Chat' }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 16 16">
                                                                <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/>
                                                            </svg>
                                                        </a>

                                                        <a href="{{ $orderEmailUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-email" style="width: 24px !important; height: 24px !important; min-width: 24px !important;" title="Email: {{ $orderRawEmail ?: 'Open Emails' }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 16 16">
                                                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge badge-light-danger">User Deleted</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $order->order_date }}</td>
                                        <td class="text-center">{{ $order->followupdate ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($order->follow_status == 'negative but convinced' || $order->follow_status == 'negative')    
                                                <span class="badge badge-light-danger fs-7 fw-bold">{{ $order->follow_status }}</span>
                                            @elseif($order->follow_status == 'positive' || $order->follow_status == 'positive and referral')
                                                <span class="badge badge-light-warning fs-7 fw-bold">{{ $order->follow_status }}</span>
                                            @elseif($order->follow_status == 'positive and own order')
                                                <span class="badge badge-light-success fs-7 fw-bold">{{ $order->follow_status }}</span>
                                            @elseif($order->follow_status == 'No response')
                                                <span class="badge badge-light-primary fs-7 fw-bold">{{ $order->follow_status }}</span>
                                            @else
                                                <span class="text-muted fs-8">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ Str::limit($order->follow_comment, 50) }}</div>
                                            @if($order->follow_comment || (isset($order->allCommentsByUid) && count($order->allCommentsByUid) > 0))
                                                <a href="javascript:void(0)" 
                                                   data-bs-toggle="offcanvas" 
                                                   data-bs-target="#followupDrawer{{ $order->id }}" 
                                                   data-toggle-followup-drawer 
                                                   data-target="#followupDrawer{{ $order->id }}" 
                                                   class="btn btn-link btn-color-primary p-0 fs-8 fw-bold">
                                                    More...
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $order->follow_up_user ?? '-' }}</td>
                                        <td class="text-center"> 
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_create_appaa_newLeads{{ $order->id }}" class="btn btn-sm btn-icon btn-light-primary" title="Edit Follow-Up">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-5">
                                            No follow-up orders found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Links --}}
                    <div class="m-4">
                        {{ $data['orders']->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals container --}}
@foreach($data['orders'] as $order)
    {{-- Follow-Up History Side Toggle Drawer --}}
    <div class="offcanvas offcanvas-end followup-history-drawer" tabindex="-1" id="followupDrawer{{ $order->id }}" aria-labelledby="followupDrawerLabel{{ $order->id }}">
        <div class="offcanvas-header d-flex align-items-center justify-content-between p-5 border-bottom bg-light">
            <div class="d-flex flex-column">
                <h5 class="offcanvas-title fw-bolder text-gray-900 fs-5 mb-1" id="followupDrawerLabel{{ $order->id }}">
                    <i class="fa fa-history text-primary me-2"></i>Follow-Up History
                </h5>
                <span class="text-muted fs-8">
                    Order ID: <strong class="text-dark">{{ $order->order_id }}</strong>
                    @if(!empty($order->user->name))
                        • Client: <strong class="text-dark">{{ $order->user->name }}</strong>
                    @endif
                </span>
            </div>
            <button type="button" class="btn btn-sm btn-icon btn-light-danger btn-close-followup-drawer" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa fa-times fs-6"></i>
            </button>
        </div>

        <div class="offcanvas-body p-5 overflow-auto flex-grow-1" style="background-color: #f9fafb;">
            @php
                $commentsList = collect();
                if (isset($order->allCommentsByUid) && $order->allCommentsByUid->count() > 0) {
                    $commentsList = $order->allCommentsByUid->sortByDesc('created_at');
                } elseif (!empty($order->follow_comment)) {
                    $commentsList = collect([(object)[
                        'comment' => $order->follow_comment,
                        'status' => $order->follow_status,
                        'commented_by' => $order->follow_up_user ?: 'Admin',
                        'created_at' => $order->followupdate ?: $order->updated_at,
                    ]]);
                }
            @endphp

            @if($commentsList->count() > 0)
                <div class="timeline-widget">
                    @foreach($commentsList as $feedback)
                        @if(!empty($feedback->comment))
                            @php
                                $itemStatus = !empty($feedback->status) ? $feedback->status : ($order->follow_status ?: 'Updated');
                                $statusLower = strtolower(trim($itemStatus));
                                $statusBadgeClass = 'badge-light-primary text-primary';
                                $statusIcon = 'fa-info-circle';
                                if (str_contains($statusLower, 'negative')) {
                                    $statusBadgeClass = 'badge-light-danger text-danger border border-danger border-dashed';
                                    $statusIcon = 'fa-times-circle';
                                } elseif (str_contains($statusLower, 'own order') || $statusLower === 'positive and own order') {
                                    $statusBadgeClass = 'badge-light-success text-success border border-success border-dashed';
                                    $statusIcon = 'fa-check-circle';
                                } elseif (str_contains($statusLower, 'positive')) {
                                    $statusBadgeClass = 'badge-light-warning text-warning border border-warning border-dashed';
                                    $statusIcon = 'fa-thumbs-up';
                                } elseif (str_contains($statusLower, 'no response')) {
                                    $statusBadgeClass = 'badge-light-info text-info border border-info border-dashed';
                                    $statusIcon = 'fa-clock-o';
                                }

                                try {
                                    $carbonDate = \Carbon\Carbon::parse($feedback->created_at);
                                    $displayDate = $carbonDate->format('d M Y, h:i A');
                                    $humanDate = $carbonDate->diffForHumans();
                                } catch (\Exception $e) {
                                    $displayDate = $feedback->created_at;
                                    $humanDate = '';
                                }

                                $adminName = $feedback->commented_by ?: 'Admin';
                            @endphp
                            <div class="card mb-4 shadow-sm border border-gray-200 rounded-3 overflow-hidden bg-white">
                                <div class="card-header d-flex align-items-center justify-content-between py-3 px-4 bg-light-subtle border-bottom border-gray-100 min-h-auto">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="symbol symbol-30px symbol-circle">
                                            <span class="symbol-label bg-primary text-white fw-bold fs-8">
                                                {{ strtoupper(substr($adminName, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="fs-7 fw-bolder text-gray-900">{{ $adminName }}</span>
                                                <span class="badge badge-light-secondary fs-9 py-0 px-2 fw-semibold">Staff</span>
                                            </div>
                                            <span class="text-muted fs-8">{{ $displayDate }} @if($humanDate)({{ $humanDate }})@endif</span>
                                        </div>
                                    </div>
                                    <span class="badge {{ $statusBadgeClass }} fs-8 fw-bold px-2 py-1 text-capitalize">
                                        <i class="fa {{ $statusIcon }} me-1 fs-9"></i>{{ $itemStatus }}
                                    </span>
                                </div>
                                <div class="card-body p-4 text-dark fs-7 lh-base" style="white-space: pre-wrap; word-break: break-word;">{{ $feedback->comment }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="d-flex flex-column align-items-center justify-content-center py-10 text-center">
                    <div class="symbol symbol-60px symbol-circle bg-light-primary mb-3 d-flex align-items-center justify-content-center">
                        <i class="fa fa-commenting-o text-primary fs-2"></i>
                    </div>
                    <span class="fw-bolder text-gray-800 fs-6 mb-1">No Follow-Up History</span>
                    <span class="text-muted fs-7">No follow-up comments have been recorded for this lead/order yet.</span>
                </div>
            @endif
        </div>

        <div class="offcanvas-footer p-4 border-top bg-white d-flex align-items-center justify-content-between">
            <button type="button" class="btn btn-sm btn-light btn-close-followup-drawer" data-bs-dismiss="offcanvas">Close</button>
            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_create_appaa_newLeads{{ $order->id }}" class="btn btn-sm btn-primary">
                <i class="fa fa-edit me-1"></i>Edit Follow-Up
            </a>
        </div>
    </div>

    {{-- Edit Status Modal --}}
    <div class="modal fade" id="kt_modal_create_appaa_newLeads{{ $order->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-950px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form class="form" method="POST" action="{{ route('follow.update', ['id' => $order->id]) }}">
                        @csrf
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Status Edit Of Follow Up {{ $order->order_id }}</h1>
                        </div>
                        
                        <div class="row g-9 mb-8 text-start">
                            <div class="col-md-12 fv-row">
                                <label class="fs-6 fw-bold mb-2">Follow-Up Status</label>
                                <select name="follow_up_status" class="form-select form-select-solid form-select-lg">
                                    <option value=""></option>
                                    <option {{ old('follow_up_status', $order->follow_status) == 'negative but convinced' ? 'selected' : '' }} value="negative but convinced">negative but convinced</option>
                                    <option {{ old('follow_up_status', $order->follow_status) == 'negative' ? 'selected' : '' }} value="negative">negative</option>
                                    <option {{ old('follow_up_status', $order->follow_status) == 'positive' ? 'selected' : '' }} value="positive">positive</option>
                                    <option {{ old('follow_up_status', $order->follow_status) == 'positive and referral' ? 'selected' : '' }} value="positive and referral">positive and referral</option>
                                    <option {{ old('follow_up_status', $order->follow_status) == 'positive and own order' ? 'selected' : '' }} value="positive and own order">positive and own order</option>
                                    <option {{ old('follow_up_status', $order->follow_status) == 'No response' ? 'selected' : '' }} value="No response">No response</option>
                                </select>
                            </div>

                            <div class="col-md-12 fv-row">
                                <label class="fs-6 fw-bold mb-2">Comment</label>
                                <textarea name="comment" class="form-control form-control-solid" cols="30" rows="3">{{ $order->follow_comment }}</textarea>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function FollowUpUser(checkbox, UserId) {
    if (!UserId) return;
    var checkedValue = checkbox.checked ? 1 : 0;
    $.ajax({
        url: '/followUpUser/' + UserId,
        method: 'PUT',
        data: {
            followup: checkedValue
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (typeof toastr !== 'undefined') {
                toastr.success(response.message || 'Updated successfully');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error updating follow-up:", error);
            if (typeof toastr !== 'undefined') {
                toastr.error('Failed to update follow-up status');
            }
        }
    });
}

$(document).ready(function () {
    let searchTimeout = null;

    function doUserSearch() {
        var searchValue = $('#searchInput').val();
        clearTimeout(searchTimeout);

        if (searchValue && searchValue.trim().length >= 2) {
            var query = searchValue.trim();
            // Show spinner inside input immediately
            $('#searchSpinner').show();
            // Show preloader in dropdown
            $('#searchResultss').html(
                '<div class="p-3 text-center text-muted fs-7 d-flex align-items-center justify-content-center gap-2">' +
                    '<span class="spinner-border spinner-border-sm text-primary" role="status" style="width: 1.1rem; height: 1.1rem; border-width: 2px;"></span>' +
                    '<span>Searching users...</span>' +
                '</div>'
            ).addClass('show').css('display', 'block');

            searchTimeout = setTimeout(function () {
                $.ajax({
                    url: "{{ route('search-order') }}",
                    type: "GET",
                    data: { user: query },
                    success: function (response) {
                        $('#searchSpinner').hide();
                        var customDropdownHtml = '';
                        if (response && response.length > 0) {
                            $.each(response, function (key, value) {
                                var mobileStr = value.mobile_no ? ' | 📞 ' + value.mobile_no : '';
                                var emailStr = value.email ? value.email : '';

                                customDropdownHtml += '<a href="javascript:void(0)" class="dropdown-item user-select-item p-3 border-bottom text-wrap" ' +
                                    'data-id="' + value.id + '" data-email="' + emailStr + '" data-name="' + value.name + '" data-mobile="' + (value.mobile_no || '') + '" style="display: block; cursor: pointer;">' +
                                    '<div class="fw-bolder text-dark fs-6">' + value.name + '</div>' +
                                    '<div class="text-muted fs-7">' + emailStr + mobileStr + '</div>' +
                                    '</a>';
                            });

                            $('#searchResultss').html(customDropdownHtml).addClass('show').css('display', 'block');
                        } else {
                            $('#searchResultss').html('<div class="p-3 text-muted fs-7 text-center">No results found</div>').addClass('show').css('display', 'block');
                        }
                    },
                    error: function () {
                        $('#searchSpinner').hide();
                        $('#searchResultss').html('<div class="p-3 text-danger fs-7 text-center">Error loading results</div>').addClass('show').css('display', 'block');
                    }
                });
            }, 150);
        } else {
            $('#searchSpinner').hide();
            $('#searchResultss').removeClass('show').css('display', 'none').empty();
            if (!searchValue || searchValue.trim().length === 0) {
                $('#selectedValue').val('');
            }
        }
    }

    // Input & Keyup event (typed or pasted)
    $('#searchInput').on('input keyup', function () {
        doUserSearch();
    });

    // Paste event support with instant response
    $('#searchInput').on('paste', function () {
        $('#searchSpinner').show();
        setTimeout(function () {
            doUserSearch();
        }, 50);
    });

    // Focus event
    $('#searchInput').on('focus', function () {
        var val = $(this).val();
        if (val && val.trim().length >= 2) {
            doUserSearch();
        }
    });

    // Handle selection from visible custom dropdown
    $(document).on('click', '#searchResultss .user-select-item', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var selectedId = $(this).attr('data-id');
        var selectedEmail = $(this).attr('data-email');
        var selectedName = $(this).attr('data-name');
        var selectedMobile = $(this).attr('data-mobile');
        var label = selectedName + (selectedMobile ? ' (' + selectedMobile + ')' : (selectedEmail ? ' (' + selectedEmail + ')' : ''));

        $('#searchInput').val(label);
        $('#selectedValue').val(selectedId);
        $('#searchResultss').removeClass('show').css('display', 'none').empty();
    });

    // Close dropdown on outside click
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#searchInput, #searchResultss').length) {
            $('#searchResultss').removeClass('show').css('display', 'none');
        }
    });

    // Enter key closes dropdown
    $('#searchInput').on('keypress', function (e) {
        if (e.which === 13) {
            $('#searchResultss').removeClass('show').css('display', 'none');
        }
    });

    // Side Toggle Drawer Handlers
    $(document).on('click', '[data-toggle-followup-drawer]', function (e) {
        e.preventDefault();
        var targetId = $(this).data('target');
        $('.followup-history-drawer').removeClass('show');
        $(targetId).addClass('show');
        $('#followupDrawerBackdrop').addClass('show');
        $('body').css('overflow', 'hidden');
    });

    $(document).on('click', '.btn-close-followup-drawer, #followupDrawerBackdrop', function (e) {
        e.preventDefault();
        $('.followup-history-drawer').removeClass('show');
        $('#followupDrawerBackdrop').removeClass('show');
        $('body').css('overflow', '');
    });

    $(document).keyup(function(e) {
        if (e.key === "Escape") {
            $('.followup-history-drawer').removeClass('show');
            $('#followupDrawerBackdrop').removeClass('show');
            $('body').css('overflow', '');
        }
    });
});
</script>

{{-- Side Toggle Drawer Backdrop --}}
<div id="followupDrawerBackdrop" class="followup-drawer-backdrop"></div>

@endsection