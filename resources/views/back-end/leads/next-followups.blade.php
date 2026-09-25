@extends('layouts.app')

@section('content')
<style>
    .followup-table th, 
    .followup-table td {
        border: 1px solid #e4e6ef !important;
        vertical-align: middle;
        padding: 8px 12px !important;
    }
    .followup-table thead th {
        background-color: #f5f8fa !important;
        color: #3f4254 !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 9px 12px !important;
    }
    .h-42px {
        height: 42px !important;
    }
    main.content,
    #kt_content,
    .content {
        padding-top: 0 !important;
    }
    .toolbar,
    #kt_toolbar {
        margin-bottom: 0 !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
        min-height: auto !important;
    }
    .follow-filter-card {
        margin-top: 0 !important;
        overflow: visible !important;
    }
    .follow-filter-card .card-body {
        overflow: visible !important;
    }
    #searchResultss {
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        z-index: 100050 !important;
        background: #ffffff !important;
        border: 1px solid #d8dbe0 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18) !important;
        max-height: 280px !important;
        overflow-y: auto !important;
        border-radius: 8px !important;
    }
    #searchResultss .user-select-item {
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
    }
    #searchResultss .user-select-item:hover {
        background-color: #f1faff !important;
    }
</style>

<div class="content d-flex flex-column flex-column-fluid pt-0" id="kt_content" style="padding-top: 0 !important;">
    <div class="toolbar py-1 mb-0" id="kt_toolbar" style="margin-bottom: 0 !important; min-height: auto !important;">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack py-1">
            <div class="page-title d-flex align-items-center flex-wrap me-3 my-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-0">
                    <i class="fa fa-calendar-check-o text-primary fs-2 me-2"></i> Next Follow-ups
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold ms-1">Today on top, Upcoming & Overdue schedule</small>
                </h1>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ url('/lead') }}" class="btn btn-sm btn-light-primary fw-bolder">
                    <i class="fa fa-arrow-left me-1"></i> Back to Leads
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-2">
        <!-- Filter Card (Matching follow.blade.php with Live Customer Search Dropdown) -->
        <div class="card card-xxl-stretch mb-4 shadow-xs border border-gray-200 follow-filter-card" style="margin-top: 0 !important;">
            <div class="card-header border-0 pt-4 pb-2">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-5 mb-0">
                        <i class="fa fa-filter text-primary me-2"></i>Filter Follow-ups
                    </span>
                </h3>
            </div>
            <div class="card-body py-3">
                <form method="GET" action="{{ route('next-followups') }}" id="nextFollowUpFilterForm">
                    <input type="hidden" name="tab" id="active_tab_input" value="{{ $tab }}">
                    
                    <div class="row g-3 mb-2 align-items-end">
                        <!-- Order Code / Title / Note -->
                        <div class="col-md-3 fv-row">
                            <label class="form-label fw-bold fs-7">Order Code / Title / Note</label>
                            <input type="search" 
                                   name="search" 
                                   id="search" 
                                   class="form-control form-control-solid fs-7 h-42px" 
                                   placeholder="Search by Order Code, Title, Note..." 
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Customer Live Search with Dropdown (Matching Orders filter & follow.blade.php) -->
                        <div class="col-md-3 fv-row position-relative" style="overflow: visible !important;">
                            <label class="form-label fw-bold fs-7">Customer (Name / Number / Email)</label>
                            <div class="position-relative" style="overflow: visible !important;">
                                <input type="text" 
                                       list="searchDatalist"
                                       id="searchInput" 
                                       name="user" 
                                       class="form-control form-control-solid pe-10 fs-7 h-42px" 
                                       placeholder="Search by Name, Number, Email..." 
                                       autocomplete="off" 
                                       value="{{ request('user') }}">
                                <datalist id="searchDatalist"></datalist>
                                <span id="searchSpinner" class="position-absolute end-0 top-50 translate-middle-y me-3" style="display:none; pointer-events: none; z-index: 10;">
                                    <span class="spinner-border spinner-border-sm text-primary" role="status" style="width: 1.1rem; height: 1.1rem; border-width: 2px;">
                                        <span class="visually-hidden">Loading...</span>
                                    </span>
                                </span>
                                <!-- Custom Dropdown Menu for Results -->
                                <div id="searchResultss" class="dropdown-menu w-100 shadow-lg p-0 mt-1" style="display:none; max-height: 260px; overflow-y: auto; z-index: 100050; position: absolute; left: 0; top: 100%; background: #ffffff !important; border: 1px solid #d8dbe0;"></div>
                            </div>
                            <input type="hidden" id="selectedValue" name="uid" value="{{ request('uid') }}">
                        </div>

                        <!-- From Date -->
                        <div class="col-md-2 fv-row">
                            <label class="form-label fw-bold fs-7">From Date</label>
                            <input type="date" 
                                   name="fromDate" 
                                   id="fromDate" 
                                   class="form-control form-control-solid fs-7 h-42px" 
                                   value="{{ request('fromDate') }}">
                        </div>

                        <!-- To Date -->
                        <div class="col-md-2 fv-row">
                            <label class="form-label fw-bold fs-7">To Date</label>
                            <input type="date" 
                                   name="toDate" 
                                   id="toDate" 
                                   class="form-control form-control-solid fs-7 h-42px" 
                                   value="{{ request('toDate') }}">
                        </div>

                        <!-- Submit & Reset -->
                        <div class="col-md-2 fv-row d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-sm btn-primary px-4 h-42px flex-grow-1 fw-bold">
                                <i class="fa fa-search me-1"></i> Search
                            </button>
                            <button type="button" id="btnResetFilters" class="btn btn-sm btn-danger px-3 h-42px d-inline-flex align-items-center fw-bold">
                                Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Tabs Card -->
        <div class="card card-custom shadow-xs border border-gray-200">
            <div class="card-header card-header-stretch border-bottom bg-light">
                <div class="card-title">
                    <h3 class="fw-bolder m-0 text-dark fs-5">
                        <i class="fa fa-list-alt text-primary me-2"></i>Follow-up Schedule
                    </h3>
                </div>
                <div class="card-toolbar">
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bolder" id="followupTabs" role="tablist">
                        <!-- Tab 1: All Follow-ups (Pending only: Today on top, Tomorrow below) -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary px-4 py-3 {{ $tab === 'all' ? 'active' : '' }}" 
                               id="tab-all-link" 
                               data-bs-toggle="tab" 
                               href="#tab_all" 
                               role="tab" 
                               aria-controls="tab_all" 
                               aria-selected="{{ $tab === 'all' ? 'true' : 'false' }}"
                               onclick="switchFollowupTab('all')">
                                <i class="fa fa-clock-o text-primary me-2"></i> All Follow-ups
                                <span class="badge badge-circle badge-primary ms-2 fw-bolder" id="badge_all_count">{{ $allCount }}</span>
                            </a>
                        </li>

                        <!-- Tab 2: Today's Followups -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-warning px-4 py-3 {{ $tab === 'today' ? 'active' : '' }}" 
                               id="tab-today-link" 
                               data-bs-toggle="tab" 
                               href="#tab_today" 
                               role="tab" 
                               aria-controls="tab_today" 
                               aria-selected="{{ $tab === 'today' ? 'true' : 'false' }}"
                               onclick="switchFollowupTab('today')">
                                <i class="fa fa-calendar-check-o text-warning me-2"></i> Today's Follow-ups
                                <span class="badge badge-circle badge-warning ms-2 fw-bolder" id="badge_today_count">{{ $todayCount }}</span>
                            </a>
                        </li>

                        <!-- Tab 3: Overdue Followups -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-danger px-4 py-3 {{ $tab === 'overdue' ? 'active' : '' }}" 
                               id="tab-overdue-link" 
                               data-bs-toggle="tab" 
                               href="#tab_overdue" 
                               role="tab" 
                               aria-controls="tab_overdue" 
                               aria-selected="{{ $tab === 'overdue' ? 'true' : 'false' }}"
                               onclick="switchFollowupTab('overdue')">
                                <i class="fa fa-exclamation-triangle text-danger me-2"></i> Overdue Follow-ups
                                <span class="badge badge-circle badge-danger ms-2 fw-bolder" id="badge_overdue_count">{{ $overdueCount }}</span>
                            </a>
                        </li>

                        <!-- Tab 4: Done Follow-ups (Completed) -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-success px-4 py-3 {{ $tab === 'done' ? 'active' : '' }}" 
                               id="tab-done-link" 
                               data-bs-toggle="tab" 
                               href="#tab_done" 
                               role="tab" 
                               aria-controls="tab_done" 
                               aria-selected="{{ $tab === 'done' ? 'true' : 'false' }}"
                               onclick="switchFollowupTab('done')">
                                <i class="fa fa-check-circle text-success me-2"></i> Done Follow-ups
                                <span class="badge badge-circle badge-success ms-2 fw-bolder" id="badge_done_count">{{ $doneCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="tab-content" id="followupTabContent">
                    
                    <!-- TAB 1: ALL FOLLOWUPS -->
                    <div class="tab-pane fade {{ $tab === 'all' ? 'show active' : '' }}" id="tab_all" role="tabpanel" aria-labelledby="tab-all-link">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle gs-3 gy-3 border mb-0 followup-table">
                                <thead>
                                    <tr class="fw-bolder text-dark bg-light border-bottom border-gray-300">
                                        <th class="w-35px text-center">SR</th>
                                        <th class="min-w-110px text-center">Order Code</th>
                                        <th class="min-w-200px">User Details</th>
                                        <th class="min-w-150px">Project / Price</th>
                                        <th class="min-w-220px">Follow-up Note</th>
                                        <th class="min-w-120px text-center">Scheduled Date</th>
                                        <th class="min-w-100px text-center">Status</th>
                                        <th class="min-w-90px text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_followups_all">
                                    <tr class="tab-loading-row">
                                        <td colspan="8" class="text-center py-10">
                                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                            <span class="text-muted fs-7 fw-semibold">Loading follow-ups...</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 2: TODAY'S FOLLOWUPS -->
                    <div class="tab-pane fade {{ $tab === 'today' ? 'show active' : '' }}" id="tab_today" role="tabpanel" aria-labelledby="tab-today-link">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle gs-3 gy-3 border mb-0 followup-table">
                                <thead>
                                    <tr class="fw-bolder text-dark bg-light border-bottom border-gray-300">
                                        <th class="w-35px text-center">SR</th>
                                        <th class="min-w-110px text-center">Order Code</th>
                                        <th class="min-w-200px">User Details</th>
                                        <th class="min-w-150px">Project / Price</th>
                                        <th class="min-w-220px">Follow-up Note</th>
                                        <th class="min-w-120px text-center">Scheduled Date</th>
                                        <th class="min-w-100px text-center">Status</th>
                                        <th class="min-w-90px text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_followups_today">
                                    <tr class="tab-loading-row">
                                        <td colspan="8" class="text-center py-10">
                                            <div class="spinner-border spinner-border-sm text-warning me-2" role="status"></div>
                                            <span class="text-muted fs-7 fw-semibold">Loading today's follow-ups...</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 3: OVERDUE FOLLOWUPS -->
                    <div class="tab-pane fade {{ $tab === 'overdue' ? 'show active' : '' }}" id="tab_overdue" role="tabpanel" aria-labelledby="tab-overdue-link">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle gs-3 gy-3 border mb-0 followup-table">
                                <thead>
                                    <tr class="fw-bolder text-dark bg-light border-bottom border-gray-300">
                                        <th class="w-35px text-center">SR</th>
                                        <th class="min-w-110px text-center">Order Code</th>
                                        <th class="min-w-200px">User Details</th>
                                        <th class="min-w-150px">Project / Price</th>
                                        <th class="min-w-220px">Follow-up Note</th>
                                        <th class="min-w-120px text-center">Scheduled Date</th>
                                        <th class="min-w-100px text-center">Status</th>
                                        <th class="min-w-90px text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_followups_overdue">
                                    <tr class="tab-loading-row">
                                        <td colspan="8" class="text-center py-10">
                                            <div class="spinner-border spinner-border-sm text-danger me-2" role="status"></div>
                                            <span class="text-muted fs-7 fw-semibold">Loading overdue follow-ups...</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 4: DONE FOLLOWUPS -->
                    <div class="tab-pane fade {{ $tab === 'done' ? 'show active' : '' }}" id="tab_done" role="tabpanel" aria-labelledby="tab-done-link">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle gs-3 gy-3 border mb-0 followup-table">
                                <thead>
                                    <tr class="fw-bolder text-dark bg-light border-bottom border-gray-300">
                                        <th class="w-35px text-center">SR</th>
                                        <th class="min-w-110px text-center">Order Code</th>
                                        <th class="min-w-200px">User Details</th>
                                        <th class="min-w-150px">Project / Price</th>
                                        <th class="min-w-220px">Follow-up Note</th>
                                        <th class="min-w-120px text-center">Scheduled Date</th>
                                        <th class="min-w-100px text-center">Status</th>
                                        <th class="min-w-90px text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_followups_done">
                                    <tr class="tab-loading-row">
                                        <td colspan="8" class="text-center py-10">
                                            <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
                                            <span class="text-muted fs-7 fw-semibold">Loading completed follow-ups...</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <!-- Infinite Scroll Bottom Loader -->
                <div id="infinite_scroll_loader" class="py-4 text-center border-top bg-light" style="display: none;">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="text-muted fs-8 fw-semibold">Loading more follow-ups...</span>
                </div>

                <!-- End of list notice -->
                <div id="infinite_scroll_end" class="py-3 text-center border-top bg-light-subtle" style="display: none;">
                    <span class="text-muted fs-8">All follow-ups loaded.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reusable Follow-up Side Drawer -->
@include('back-end.leads.partials.followup-drawer')

<!-- SweetAlert2 Library for Alerts & Confirmations -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function() {
    // Universal dynamic endpoint resolver - works seamlessly on localhost subfolders, virtual hosts, and production
    function getAppEndpoint(endpointName) {
        const curPath = window.location.pathname;
        const cleanEndpoint = endpointName.replace(/^\/+/, '');
        if (curPath.includes('next-followups')) {
            return curPath.replace(/next-followups\/?.*$/, cleanEndpoint);
        }
        return '/' + cleanEndpoint;
    }

    const tabAjaxUrl = window.location.pathname;
    const searchOrderUrl = getAppEndpoint('search-order');

    let activeTab = '{{ $tab }}';
    let tabLoaded = { all: false, today: false, overdue: false, done: false };
    let paginationPages = { all: 1, today: 1, overdue: 1, done: 1 };
    let hasMorePages = { all: false, today: false, overdue: false, done: false };
    let isLoading = false;

    function getEmptyTabHtml(tabName) {
        if (tabName === 'today') {
            return `
                <tr class="empty-row">
                    <td colspan="8" class="text-center py-10 text-muted">
                        <div class="symbol symbol-50px symbol-circle bg-light-warning mb-3 mx-auto d-inline-flex align-items-center justify-content-center">
                            <i class="fa fa-smile-o fs-2 text-warning"></i>
                        </div>
                        <h6 class="fw-bolder text-gray-700">No Follow-ups Scheduled for Today</h6>
                        <p class="fs-8 text-muted mb-0">All clear! Check Overdue Follow-ups or schedule new follow-ups from the Leads page.</p>
                    </td>
                </tr>
            `;
        } else if (tabName === 'overdue') {
            return `
                <tr class="empty-row">
                    <td colspan="8" class="text-center py-10 text-muted">
                        <div class="symbol symbol-50px symbol-circle bg-light-success mb-3 mx-auto d-inline-flex align-items-center justify-content-center">
                            <i class="fa fa-check-circle fs-2 text-success"></i>
                        </div>
                        <h6 class="fw-bolder text-gray-700">No Overdue Follow-ups!</h6>
                        <p class="fs-8 text-muted mb-0">Great job! All previous follow-ups have been completed.</p>
                    </td>
                </tr>
            `;
        } else if (tabName === 'done') {
            return `
                <tr class="empty-row">
                    <td colspan="8" class="text-center py-10 text-muted">
                        <div class="symbol symbol-50px symbol-circle bg-light-success mb-3 mx-auto d-inline-flex align-items-center justify-content-center">
                            <i class="fa fa-check-circle fs-2 text-success"></i>
                        </div>
                        <h6 class="fw-bolder text-gray-700">No Completed Follow-ups Yet</h6>
                        <p class="fs-8 text-muted mb-0">Follow-ups you mark as done will appear here.</p>
                    </td>
                </tr>
            `;
        } else {
            return `
                <tr class="empty-row">
                    <td colspan="8" class="text-center py-10 text-muted">
                        <div class="symbol symbol-50px symbol-circle bg-light-primary mb-3 mx-auto d-inline-flex align-items-center justify-content-center">
                            <i class="fa fa-comments-o fs-2 text-primary"></i>
                        </div>
                        <h6 class="fw-bolder text-gray-700">No Follow-ups Found</h6>
                        <p class="fs-8 text-muted mb-0">No follow-ups match your current filter criteria.</p>
                    </td>
                </tr>
            `;
        }
    }

    function fetchTabData(tabName, page = 1, append = false) {
        if (!window.jQuery) return;
        const $ = window.jQuery;

        if (isLoading) return;
        isLoading = true;

        const $tbody = $('#tbody_followups_' + tabName);

        if (append) {
            $('#infinite_scroll_loader').show();
            $('#infinite_scroll_end').hide();
        } else {
            $tbody.html(`
                <tr class="tab-loading-row">
                    <td colspan="8" class="text-center py-10">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        <span class="text-muted fs-7 fw-semibold">Loading follow-ups...</span>
                    </td>
                </tr>
            `);
            $('#infinite_scroll_end').hide();
        }

        const formData = $('#nextFollowUpFilterForm').serializeArray();
        const cleanData = formData.filter(item => item.name !== 'tab' && item.name !== 'page' && item.name !== 'ajax');
        cleanData.push({ name: 'ajax', value: 1 });
        cleanData.push({ name: 'tab', value: tabName });
        cleanData.push({ name: 'page', value: page });

        $.ajax({
            url: tabAjaxUrl,
            type: 'GET',
            data: $.param(cleanData),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function (res) {
                isLoading = false;
                $('#infinite_scroll_loader').hide();

                if (res && res.status) {
                    if (append) {
                        $tbody.find('.tab-loading-row, .empty-row').remove();
                        $tbody.append(res.html);
                    } else {
                        if (res.html && res.html.trim().length > 0) {
                            $tbody.html(res.html);
                        } else {
                            $tbody.html(getEmptyTabHtml(tabName));
                        }
                    }

                    paginationPages[tabName] = res.current_page || 1;
                    hasMorePages[tabName] = res.has_more || false;
                    tabLoaded[tabName] = true;

                    // Update count badges dynamically
                    if (res.counts) {
                        if (typeof res.counts.all !== 'undefined') $('#badge_all_count').text(res.counts.all);
                        if (typeof res.counts.today !== 'undefined') $('#badge_today_count').text(res.counts.today);
                        if (typeof res.counts.overdue !== 'undefined') $('#badge_overdue_count').text(res.counts.overdue);
                        if (typeof res.counts.done !== 'undefined') $('#badge_done_count').text(res.counts.done);
                    }

                    if (!res.has_more) {
                        const rowCount = $tbody.find('tr:not(.empty-row):not(.tab-loading-row)').length;
                        if (rowCount > 0) {
                            $('#infinite_scroll_end').show();
                        } else {
                            $('#infinite_scroll_end').hide();
                        }
                    } else {
                        $('#infinite_scroll_end').hide();
                    }
                } else {
                    if (!append) {
                        $tbody.html(getEmptyTabHtml(tabName));
                    }
                    hasMorePages[tabName] = false;
                    $('#infinite_scroll_end').hide();
                }
            },
            error: function (xhr, status, error) {
                console.error('Failed to load follow-ups:', error);
                isLoading = false;
                $('#infinite_scroll_loader').hide();
                if (!append) {
                    $tbody.html(`
                        <tr class="empty-row">
                            <td colspan="8" class="text-center py-10 text-danger">
                                <i class="fa fa-exclamation-triangle fs-2 text-danger mb-2 d-block"></i>
                                <div class="fw-bold fs-6 mb-1">Failed to load follow-ups</div>
                                <span class="text-muted fs-7">Please check your connection and retry.</span>
                                <div class="mt-3">
                                    <button type="button" onclick="fetchTabData('${tabName}', 1, false)" class="btn btn-sm btn-light-primary fw-bold">
                                        <i class="fa fa-refresh me-1"></i> Try Again
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `);
                }
            }
        });
    }

    // Switch Tab - Loads via AJAX if not loaded yet
    window.switchFollowupTab = function(tabName, forceReload = false) {
        activeTab = tabName;
        if (window.jQuery) {
            window.jQuery('#active_tab_input').val(tabName);
        }

        try {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabName);
            window.history.replaceState({}, '', url.toString());
        } catch(e) {}

        if (!tabLoaded[tabName] || forceReload) {
            fetchTabData(tabName, 1, false);
        } else {
            if (window.jQuery) {
                const $ = window.jQuery;
                if (hasMorePages[activeTab]) {
                    $('#infinite_scroll_end').hide();
                } else {
                    const rowCount = $('#tbody_followups_' + activeTab + ' tr:not(.empty-row):not(.tab-loading-row)').length;
                    if (rowCount > 0) {
                        $('#infinite_scroll_end').show();
                    } else {
                        $('#infinite_scroll_end').hide();
                    }
                }
            }
        }
    };

    window.fetchTabData = fetchTabData;

    // ─────────────────────────────────────────────────────────────────────────────
    // MARK DONE BUTTON: REAL-TIME (Zero confirmation dialogs, immediate UI response)
    // ─────────────────────────────────────────────────────────────────────────────
    window.handleDoneClick = function(followupId, btnEl) {
        if (!followupId) return;
        if (!window.jQuery) return;
        const $ = window.jQuery;

        const $btn = $(btnEl);
        if ($btn.hasClass('disabled') || $btn.prop('disabled')) return;

        $btn.prop('disabled', true);
        $btn.html('<span class="spinner-border spinner-border-sm text-success" role="status" style="width: 13px; height: 13px; border-width: 2px;"></span>');

        const $row = $('#row_followup_' + followupId);

        // 1. Highlight row in green
        $row.addClass('table-success');

        // 2. Real-time badge count updates immediately
        let curAllCount = parseInt($('#badge_all_count').text()) || 0;
        if (curAllCount > 0) $('#badge_all_count').text(curAllCount - 1);

        if (activeTab === 'today') {
            let curTodayCount = parseInt($('#badge_today_count').text()) || 0;
            if (curTodayCount > 0) $('#badge_today_count').text(curTodayCount - 1);
        } else if (activeTab === 'overdue') {
            let curOverdueCount = parseInt($('#badge_overdue_count').text()) || 0;
            if (curOverdueCount > 0) $('#badge_overdue_count').text(curOverdueCount - 1);
        }

        let curDoneCount = parseInt($('#badge_done_count').text()) || 0;
        $('#badge_done_count').text(curDoneCount + 1);

        // 3. Smooth real-time fade out
        $row.fadeOut(300, function () {
            $(this).remove();
            const $activeTbody = $('#tbody_followups_' + activeTab);
            if ($activeTbody.children('tr:not(.empty-row):not(.tab-loading-row)').length === 0) {
                $activeTbody.html(getEmptyTabHtml(activeTab));
            }
        });

        tabLoaded.done = false;

        // 4. Background server update using universal dynamic endpoint
        const doneUrl = getAppEndpoint('lead/followups/' + followupId + '/done');

        $.ajax({
            url: doneUrl,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                if (res && res.status) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Follow-up marked Completed!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            },
            error: function () {
                console.error('Error completing follow-up on server.');
            }
        });
    };

    // ─────────────────────────────────────────────────────────────────────────────
    // MAIN INITIALIZATION WHEN JQUERY IS READY
    // ─────────────────────────────────────────────────────────────────────────────
    function initApp() {
        if (!window.jQuery) {
            setTimeout(initApp, 50);
            return;
        }
        const $ = window.jQuery;

        // Filter Form Submit via AJAX
        $('#nextFollowUpFilterForm').on('submit', function (e) {
            e.preventDefault();
            tabLoaded = { all: false, today: false, overdue: false, done: false };

            try {
                const url = new URL(window.location.href);
                url.searchParams.set('tab', activeTab);
                const searchVal = $('#search').val();
                const userVal = $('#searchInput').val();
                const uidVal = $('#selectedValue').val();
                const fromVal = $('#fromDate').val();
                const toVal = $('#toDate').val();

                if (searchVal) url.searchParams.set('search', searchVal); else url.searchParams.delete('search');
                if (userVal) url.searchParams.set('user', userVal); else url.searchParams.delete('user');
                if (uidVal) url.searchParams.set('uid', uidVal); else url.searchParams.delete('uid');
                if (fromVal) url.searchParams.set('fromDate', fromVal); else url.searchParams.delete('fromDate');
                if (toVal) url.searchParams.set('toDate', toVal); else url.searchParams.delete('toDate');
                window.history.replaceState({}, '', url.toString());
            } catch(e) {}

            fetchTabData(activeTab, 1, false);
        });

        // Reset Filters Button
        $('#btnResetFilters').on('click', function (e) {
            e.preventDefault();
            $('#search').val('');
            $('#searchInput').val('');
            $('#selectedValue').val('');
            $('#searchDatalist').empty();
            $('#searchResultss').removeClass('show').css('display', 'none').empty();
            $('#searchSpinner').hide();
            $('#fromDate').val('');
            $('#toDate').val('');

            tabLoaded = { all: false, today: false, overdue: false, done: false };

            try {
                const url = new URL(window.location.origin + window.location.pathname);
                url.searchParams.set('tab', activeTab);
                window.history.replaceState({}, '', url.toString());
            } catch(e) {}

            fetchTabData(activeTab, 1, false);
        });

        // Infinite Scroll
        $(window).on('scroll', function () {
            if (isLoading) return;
            if (!hasMorePages[activeTab]) return;

            const scrollHeight = $(document).height();
            const scrollPos = $(window).height() + $(window).scrollTop();

            if ((scrollHeight - scrollPos) / scrollHeight < 0.15) {
                const nextPage = (paginationPages[activeTab] || 1) + 1;
                fetchTabData(activeTab, nextPage, true);
            }
        });

        // ─────────────────────────────────────────────────────────────────────────────
        // LIVE CUSTOMER SEARCH (Matching Orders & follow.blade.php)
        // ─────────────────────────────────────────────────────────────────────────────
        let searchTimeout = null;

        function doUserSearch() {
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
                        url: searchOrderUrl,
                        type: "GET",
                        data: { user: query },
                        success: function (response) {
                            $('#searchSpinner').hide();
                            var customDropdownHtml = '';
                            $('#searchDatalist').empty();

                            if (response && response.length > 0) {
                                $.each(response, function (key, value) {
                                    var mobileStr = value.mobile_no ? ' | 📞 ' + value.mobile_no : '';
                                    var emailStr = value.email ? value.email : '';
                                    var mobileSuffix = value.mobile_no ? ' (' + value.mobile_no + ')' : '';

                                    // Populate datalist option (Orders filter style)
                                    $('#searchDatalist').append('<option data-id="' + value.id + '" value="' + (value.email || value.name) + '">' + value.name + mobileSuffix + '</option>');

                                    // Populate custom styled floating dropdown
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
                        error: function (xhr, status, error) {
                            console.error('Search order error:', error);
                            $('#searchSpinner').hide();
                            $('#searchResultss').html('<div class="p-3 text-danger fs-7 text-center">Error loading results</div>').addClass('show').css('display', 'block');
                        }
                    });
                }, 120);
            } else {
                $('#searchSpinner').hide();
                $('#searchResultss').removeClass('show').css('display', 'none').empty();
                if (!searchValue || searchValue.trim().length === 0) {
                    $('#selectedValue').val('');
                }
            }
        }

        window.doUserSearch = doUserSearch;

        // Input & Keyup events (real-time live typing)
        $('#searchInput').on('input keyup', function () {
            doUserSearch();
        });

        // Datalist selection change handler (Matching Orders filter style)
        $('#searchInput').on('change', function() {
            var val = $(this).val();
            var selectedOption = $('#searchDatalist option').filter(function () {
                return $(this).val() === val;
            });
            if (selectedOption.length > 0) {
                var selectedId = selectedOption.attr('data-id') || selectedOption.data('id');
                $('#selectedValue').val(selectedId);
                $('#searchResultss').removeClass('show').css('display', 'none');
            }
        });

        // Paste event support
        $('#searchInput').on('paste', function () {
            $('#searchSpinner').show();
            setTimeout(function () {
                doUserSearch();
            }, 30);
        });

        // Focus event
        $('#searchInput').on('focus', function () {
            var val = $(this).val();
            if (val && val.trim().length >= 2) {
                doUserSearch();
            }
        });

        // Selection from custom dropdown item click
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
            $('#searchSpinner').hide();
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

        // Initial AJAX Load: Fetch the active tab data immediately
        switchFollowupTab(activeTab, true);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initApp);
    } else {
        initApp();
    }
})();
</script>
@endsection
