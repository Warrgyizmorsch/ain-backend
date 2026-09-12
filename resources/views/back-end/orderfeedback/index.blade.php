@extends('layouts.app')

@section('content')

<style>
    /* Eliminate Metronic toolbar-fixed empty gap */
    #kt_content,
    .content {
        padding-top: 0 !important;
    }
    .toolbar,
    #kt_toolbar {
        margin-bottom: 0 !important;
    }

    .feedback-card {
        margin-top: 0 !important;
        border-radius: 10px;
        border: 1px solid #e4e6ef;
        box-shadow: 0 4px 14px rgba(0,0,0,0.04);
    }

    .feedback-card .card-header {
        min-height: 62px;
        background: #fff;
        border-bottom: 1px solid #edf0f5;
    }

    table thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #f5f8fa !important;
        font-size: 12px;
        text-transform: uppercase;
        color: #5e6278;
        letter-spacing: .3px;
        white-space: nowrap;
    }

    table.table td,
    table.table th {
        border: 1px solid #edf0f5;
        vertical-align: middle;
    }

    tbody tr:hover {
        background: #f8fbff !important;
    }

    .filter-box {
        background: #f9fafb;
        border: 1px solid #e4e6ef;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 18px;
    }

    .user-info-box {
        min-width: 230px;
        max-width: 270px;
        text-align: left;
    }

    .user-info-box strong {
        display: block;
        color: #181c32;
        font-size: 13px;
    }

    .user-info-box span {
        font-size: 12px;
    }

    .title-cell {
        min-width: 260px;
        max-width: 340px;
        text-align: left;
        white-space: normal;
        word-break: break-word;
        line-height: 1.5;
    }

    .order-id-cell,
    .date-cell {
        min-width: 125px;
        white-space: nowrap;
    }

    .feedback-toggle-btn {
        border-radius: 8px;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 700;
        min-width: 70px;
    }

    .btn-feedback-yes {
        background: #e8fff3;
        color: #0f7a3a;
        border: 1px solid #b7f5d1;
    }

    .btn-feedback-no {
        background: #fff4de;
        color: #b45309;
        border: 1px solid #f5d59b;
    }

    .failed-order-box {
        display: inline-block;
        background: #ffeaea !important;
        color: #b50000 !important;
        border: 2px solid #ff0000 !important;
        border-radius: 8px;
        padding: 6px 10px;
        font-weight: 700;
        line-height: 1.35;
    }

    /* Sort Icon Styles */
    .sort-th-link {
        color: #5e6278;
        font-weight: 700;
        font-size: 12px;
        transition: color 0.15s;
    }

    .sort-th-link:hover {
        color: #009ef7;
    }

    .sort-icon-wrap {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        line-height: 1;
        gap: 1px;
        margin-left: 2px;
        vertical-align: middle;
    }

    .sort-icon-wrap .sort-up-arrow,
    .sort-icon-wrap .sort-down-arrow {
        opacity: 0.25;
        display: block;
    }

    /* Ascending — up arrow active */
    .sort-icon-wrap.sort-asc .sort-up-arrow {
        opacity: 1;
        color: #009ef7;
    }

    /* Descending — down arrow active */
    .sort-icon-wrap.sort-desc .sort-down-arrow {
        opacity: 1;
        color: #009ef7;
    }

    #searchResultss .user-select-item {
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
    }
    #searchResultss .user-select-item:hover {
        background-color: #f1faff !important;
    }
</style>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Order Feedback
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Delivered Orders</small>
                    </h1>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mt-2">
            <div class="card card-xl-stretch mb-5 feedback-card">
        <div class="card-header">
            <h3 class="card-title fw-bolder mb-0">Delivered Order Feedback</h3>
        </div>

        <div class="card-body py-3">            <form method="GET" action="{{ url()->current() }}" class="filter-box" id="orderFeedbackFilterForm">
                <div class="row g-3 mb-3">
                    <div class="col-md-3 fv-row">
                        <label class="form-label fw-bold fs-7">Order Code / Title</label>
                        <input type="search" name="search" id="search" class="form-control form-control-solid" placeholder="Search By Order Code / Title" value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3 fv-row">
                        <label class="form-label fw-bold fs-7">Customer (Name / Number / Email)</label>
                        <div class="position-relative">
                            <input type="text" id="searchInput" name="user" class="form-control form-control-solid pe-10" placeholder="Search by Name, Number, Email..." autocomplete="off" value="{{ request('user') ?: (request('uid') ? (is_numeric(request('uid')) ? optional(\App\Models\User::find(request('uid')))->name : request('uid')) : '') }}">
                            <!-- In-input preloader spinner -->
                            <span id="searchSpinner" class="position-absolute end-0 top-50 translate-middle-y me-3" style="display:none; pointer-events: none; z-index: 10;">
                                <span class="spinner-border spinner-border-sm text-primary" role="status" style="width: 1.1rem; height: 1.1rem; border-width: 2px;">
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                            </span>
                            <!-- Single Custom Dropdown (No datalist) -->
                            <div id="searchResultss" class="dropdown-menu w-100 shadow-lg p-0 mt-1" style="display:none; max-height: 260px; overflow-y: auto; z-index: 1050; position: absolute; left: 0; top: 100%; background: #ffffff !important; border: 1px solid #d8dbe0;"></div>
                        </div>
                        <input type="hidden" id="selectedValue" name="uid" value="{{ request('uid') }}">
                    </div>

                    <div class="col-md-2 fv-row">
                        <label class="form-label fw-bold fs-7">Feedback Status</label>
                        <select name="feedback_status" class="form-select form-select-solid">
                            <option value="">All</option>
                            <option value="no" {{ request('feedback_status') == 'no' ? 'selected' : '' }}>No</option>
                            <option value="yes" {{ request('feedback_status') == 'yes' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>

                    <div class="col-md-2 fv-row">
                        <label class="form-label fw-bold fs-7">Order From</label>
                        <input type="date" name="order_date_from" class="form-control form-control-solid" value="{{ request('order_date_from') }}">
                    </div>

                    <div class="col-md-2 fv-row">
                        <label class="form-label fw-bold fs-7">Order To</label>
                        <input type="date" name="order_date_to" class="form-control form-control-solid" value="{{ request('order_date_to') }}">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-3 fv-row">
                        <label class="form-label fw-bold fs-7">Delivery From</label>
                        <input type="date" name="feedback_date_from" class="form-control form-control-solid" value="{{ request('feedback_date_from') }}">
                    </div>

                    <div class="col-md-3 fv-row">
                        <label class="form-label fw-bold fs-7">Delivery To</label>
                        <input type="date" name="feedback_date_to" class="form-control form-control-solid" value="{{ request('feedback_date_to') }}">
                    </div>

                    <div class="col-md-6 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-sm btn-primary px-5">
                            <i class="fa fa-search me-1"></i> Search
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-sm btn-danger px-5">Reset</a>
                    </div>
                </div>
            </form>

            <div id="scroll-order-table" style="max-height: 76vh; overflow-y: auto;">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr class="fw-bolder text-dark bg-light">
                            <th class="text-center">Order ID</th>
                            <th class="text-center">User Details</th>
                            <th class="text-center">Title</th>
                            <th class="text-center">Order Date</th>
                            <th class="text-center" style="white-space: nowrap;">
                                @php
                                    $currentSortDir = request('sort_dir', 'desc');
                                    $nextSortDir = $currentSortDir === 'desc' ? 'asc' : 'desc';
                                    $sortQuery = array_merge(request()->query(), ['sort_dir' => $nextSortDir]);
                                    $sortUrl = url()->current() . '?' . http_build_query($sortQuery);
                                @endphp
                                <a href="{{ $sortUrl }}" class="text-decoration-none d-inline-flex align-items-center gap-1 sort-th-link">
                                    Delivery Date
                                    <span class="sort-icon-wrap {{ $currentSortDir === 'asc' ? 'sort-asc' : 'sort-desc' }}">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="sort-up-arrow" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5L5 12H19L12 5Z" fill="currentColor"/>
                                        </svg>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="sort-down-arrow" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 19L5 12H19L12 19Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                </a>
                            </th>
                            <th class="text-center">Project Status</th>
                            <th class="text-center">Feedback</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data as $order)
                            @php
                                $user = $order->user;
                                $status = strtolower($order->feedback_status ?? '');
                                $isYes = in_array($status, ['yes', 'completed']);
                                $isFailedOrder = (int) ($order->is_fail ?? 0) === 1;
                                $orderCode = $order->order_code ?? $order->order_id ?? '';
                            @endphp

                            <tr>
                                <td class="text-center order-id-cell">
                                    @if($isFailedOrder)
                                        <span class="failed-order-box">
                                            <span class="d-inline-flex align-items-center justify-content-center">
                                                <span>{{ $orderCode ?: 'N/A' }}</span>
                                                @if(!empty($orderCode))
                                                    <button type="button" class="btn btn-icon btn-sm btn-active-light-danger ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Order Code" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $orderCode }}', 'Order code copied!');">
                                                        <i class="fa fa-clone fs-8 text-danger"></i>
                                                    </button>
                                                @endif
                                            </span><br>
                                            <span class="fs-8">Fail Order</span>
                                            @if($order->failed_at)
                                                <br><span class="fs-9">{{ \Carbon\Carbon::parse($order->failed_at)->format('d M Y h:i A') }}</span>
                                            @endif
                                        </span>
                                    @else
                                        <div class="d-inline-flex align-items-center justify-content-center">
                                            <strong class="text-primary">{{ $orderCode ?: 'N/A' }}</strong>
                                            @if(!empty($orderCode))
                                                <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Order Code" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $orderCode }}', 'Order code copied!');">
                                                    <i class="fa fa-clone fs-8 text-muted"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <td class="user-info-box">
                                    @if($user)
                                        @php
                                            $isSuperAdmin = auth()->check() && (int) auth()->user()->role_id === 1;
                                            $rawName = $user->name ?? '';
                                            $rawEmail = $user->email ?? '';
                                            $rawMobile = $user->mobile_no ?? $user->mobile ?? '';
                                            $rawCC = $user->countrycode ?? $user->country_code ?? '';
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
                                                       id="twilioCallBtnfeedback{{ $order->id }}"
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

                                                <a href="{{ $orderEmailUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-email" style="width: 24px !important; height: 24px !important; min-width: 24px !important;" title="Email: {{ $rawEmail ?: 'Open Emails' }}">
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

                                <td class="title-cell">
                                    {!! $order->title ?: '<span class="badge badge-light-danger">Not Available</span>' !!}
                                </td>

                                <td class="text-center date-cell">
                                    @if($order->order_date)
                                        {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                                    @else
                                        <span class="badge badge-light-danger">N/A</span>
                                    @endif
                                </td>

                                <td class="text-center date-cell">
                                    @if($order->delivery_date)
                                        {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
                                    @else
                                        <span class="badge badge-light-danger">N/A</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <span class="badge badge-light-success fs-8 fw-bold">
                                        {{ $order->projectstatus ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <button type="button"
                                            class="btn feedback-toggle-btn {{ $isYes ? 'btn-feedback-yes' : 'btn-feedback-no' }}"
                                            onclick="toggleFeedback({{ $order->id }}, '{{ $isYes ? 'no' : 'yes' }}', this)">
                                        {{ $isYes ? 'Yes' : 'No' }}
                                    </button>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    No delivered orders found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $data->links() }}
            </div>

        </div>
    </div>
</div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function toggleFeedback(orderId, newStatus, btn) {
        btn.disabled = true;
        btn.innerText = '...';

        fetch("{{ route('orders.mark-feedback') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                order_id: orderId,
                feedback_status: newStatus
            })
        })
        .then(response => response.json())
        .then(response => {
            if (!response.success) {
                alert('Something went wrong.');
                btn.disabled = false;
                btn.innerText = newStatus === 'yes' ? 'No' : 'Yes';
                return;
            }

            if (response.feedback_status === 'yes') {
                btn.innerText = 'Yes';
                btn.classList.remove('btn-feedback-no');
                btn.classList.add('btn-feedback-yes');
                btn.setAttribute('onclick', `toggleFeedback(${response.order_id}, 'no', this)`);
            } else {
                btn.innerText = 'No';
                btn.classList.remove('btn-feedback-yes');
                btn.classList.add('btn-feedback-no');
                btn.setAttribute('onclick', `toggleFeedback(${response.order_id}, 'yes', this)`);
            }

            btn.disabled = false;
        })
        .catch(() => {
            alert('Server error. Please try again.');
            btn.disabled = false;
            btn.innerText = newStatus === 'yes' ? 'No' : 'Yes';
        });
    }

    $(document).ready(function () {
        let searchTimeout = null;

        function doFeedbackUserSearch() {
            var searchValue = $('#searchInput').val();
            clearTimeout(searchTimeout);

            if (searchValue && searchValue.trim().length >= 2) {
                var query = searchValue.trim();
                $('#searchSpinner').show();
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

        // Input & Keyup event (typed)
        $('#searchInput').on('input keyup', function () {
            doFeedbackUserSearch();
        });

        // Paste event with instant spinner
        $('#searchInput').on('paste', function () {
            $('#searchSpinner').show();
            setTimeout(function () {
                doFeedbackUserSearch();
            }, 50);
        });

        // Focus event
        $('#searchInput').on('focus', function () {
            var val = $(this).val();
            if (val && val.trim().length >= 2) {
                doFeedbackUserSearch();
            }
        });

        // Dropdown selection (ONLY one dropdown, NO datalist)
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

        // Outside click closes dropdown
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
    });
</script>

@endsection
