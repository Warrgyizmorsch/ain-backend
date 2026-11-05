@extends('layouts.app')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl" id="kt_content_container">

            @php
                $currentReportYear = (int) request('year', 2026);
                $canSeeFullContact = $canSeeFullContact ?? (auth()->check() && in_array((int) auth()->user()->role_id, [1, 3, 9]));
            @endphp

            <!-- Page Header -->
            <div class="card card-flush mb-5 shadow-sm border-0">
                <div class="card-body py-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="symbol symbol-45px symbol-circle bg-light-danger">
                            <span class="symbol-label">
                                <i class="fa fa-wallet fs-2 text-danger"></i>
                            </span>
                        </div>
                        <div>
                            <h2 class="fw-bolder text-gray-800 mb-0">Customer Due Payment Report ({{ $currentReportYear }})
                            </h2>
                            <span class="text-muted fs-7 fw-bold">Track, follow up, and recover outstanding balances from
                                customers for year {{ $currentReportYear }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}"
                            class="btn btn-sm btn-danger fw-bolder">
                            <i class="fa fa-file-excel me-1"></i> Export to CSV
                        </a>
                    </div>
                </div>
            </div>

            <!-- KPI Summary Cards (Year Filtered) -->
            <div class="row g-4 mb-5">
                <!-- Total Due Amount -->
                <div class="col-md-4">
                    <div class="card card-flush shadow-sm border border-danger border-dashed h-100 bg-light-danger">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-gray-700 fw-bold fs-7 d-block mb-1">Total Outstanding Due
                                    ({{ $currentReportYear }})</span>
                                <h3 class="fs-2hx fw-bolder text-danger mb-0">
                                    £{{ number_format($summary['total_due_amount'] ?? 0, 2) }}
                                </h3>
                            </div>
                            <div class="symbol symbol-50px symbol-circle bg-white shadow-sm">
                                <span class="symbol-label">
                                    <i class="fa fa-pound-sign fs-2 text-danger"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Customers with Due -->
                <div class="col-md-4">
                    <div class="card card-flush shadow-sm border border-warning border-dashed h-100 bg-light-warning">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-gray-700 fw-bold fs-7 d-block mb-1">Customers with Due
                                    ({{ $currentReportYear }})</span>
                                <h3 class="fs-2hx fw-bolder text-warning mb-0">
                                    {{ number_format($summary['total_customers'] ?? 0) }}
                                </h3>
                            </div>
                            <div class="symbol symbol-50px symbol-circle bg-white shadow-sm">
                                <span class="symbol-label">
                                    <i class="fa fa-users fs-2 text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Due Orders -->
                <div class="col-md-4">
                    <div class="card card-flush shadow-sm border border-primary border-dashed h-100 bg-light-primary">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-gray-700 fw-bold fs-7 d-block mb-1">Total Orders with Balance
                                    ({{ $currentReportYear }})</span>
                                <h3 class="fs-2hx fw-bolder text-primary mb-0">
                                    {{ number_format($summary['total_due_orders'] ?? 0) }}
                                </h3>
                            </div>
                            <div class="symbol symbol-50px symbol-circle bg-white shadow-sm">
                                <span class="symbol-label">
                                    <i class="fa fa-shopping-bag fs-2 text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="card card-flush mb-5 shadow-sm">
                <div class="card-body py-4">
                    <form id="customerDueFilterForm" method="GET" action="{{ route('reports.customer-due') }}">
                        <div class="row g-3 align-items-end">
                            <!-- Customer Dropdown Search (Like Order Page) -->
                            <div class="col-md-3 fv-row position-relative">
                                <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Search Customer (Name, Phone,
                                    Email, ID)</label>
                                <input type="text" id="searchInput" name="user"
                                    class="form-control form-control-solid" placeholder="Search Customer..."
                                    autocomplete="off"
                                    value="{{ request('user') ?: (request('uid') ? optional(\App\Models\User::find(request('uid')))->name ?? request('uid') : request('search') ?? '') }}">
                                <!-- Container to display custom search results dropdown -->
                                <div id="searchResultss" class="dropdown-menu w-100 shadow-lg p-0 mt-1"
                                    style="display:none; max-height: 280px; overflow-y: auto; z-index: 1050; position: absolute; left: 0; top: 100%; background: #ffffff !important; border: 1px solid #d8dbe0;">
                                </div>
                                <!-- Hidden field to store the selected customer UID -->
                                <input type="hidden" id="selectedValue" name="uid" value="{{ request('uid') }}">
                            </div>

                            <!-- Year Filter (Default 2026) -->
                            <div class="col-md-1">
                                <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Year</label>
                                <select name="year" class="form-select form-select-solid" onchange="this.form.submit()">
                                    @for ($y = 2026; $y >= 2021; $y--)
                                        <option value="{{ $y }}"
                                            {{ $currentReportYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Min Due Filter -->
                            <div class="col-md-2">
                                <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Min Due (£)</label>
                                <input type="number" step="0.01" name="min_due" value="{{ request('min_due') }}"
                                    class="form-control form-control-solid" placeholder="e.g. 100">
                            </div>

                            <!-- Order Date From -->
                            <div class="col-md-2">
                                <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Order Date From</label>
                                <input type="date" name="from_date" value="{{ request('from_date') }}"
                                    class="form-control form-control-solid">
                            </div>

                            <!-- Order Date To -->
                            <div class="col-md-2">
                                <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Order Date To</label>
                                <input type="date" name="to_date" value="{{ request('to_date') }}"
                                    class="form-control form-control-solid">
                            </div>

                            <!-- Rows Per Page -->
                            <div class="col-md-1">
                                <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Rows</label>
                                <select name="per_page" class="form-select form-select-solid px-2"
                                    onchange="this.form.submit()">
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>

                            <!-- Submit & Reset Buttons -->
                            <div class="col-md-1 d-flex gap-1">
                                <button type="submit" class="btn btn-primary btn-sm px-3 w-100" title="Apply Filter">
                                    <i class="fa fa-filter"></i>
                                </button>
                                <a href="{{ route('reports.customer-due', ['year' => 2026]) }}"
                                    class="btn btn-light-danger btn-sm px-3" title="Reset Filters (2026)">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Main Customer Due Table Card -->
            <div class="card card-flush shadow-sm">
                <div class="card-header border-0 pt-4 pb-2 d-flex align-items-center justify-content-between">
                    <h3 class="card-title fw-bold text-gray-800 fs-4 mb-0">
                        <span>Due Balances List ({{ $currentReportYear }})</span>
                        <span class="badge badge-light-danger fs-8 fw-bold ms-2">{{ number_format($users->total()) }}
                            Customers Found</span>
                    </h3>
                    <div class="text-muted fs-8">
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of
                        {{ number_format($users->total()) }}
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-hover table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bolder text-muted fs-7 bg-light">
                                    <th class="ps-3 text-center" style="width: 40px;">#</th>
                                    <th class="min-w-200px">Customer</th>
                                    <th class="min-w-160px">Contact</th>
                                    <th class="min-w-100px text-center">Due Orders</th>
                                    <th class="min-w-110px text-end">Total Amount</th>
                                    <th class="min-w-110px text-end">Total Paid</th>
                                    <th class="min-w-130px text-end pe-3">Net Due Amount</th>
                                    <th class="min-w-120px">Last Order</th>
                                    <th class="min-w-160px text-center pe-3">Actions / CTAs</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($users as $index => $u)
                                    @php
                                        $rank = ($users->currentPage() - 1) * $users->perPage() + ($index + 1);
                                        $cleanPhone = preg_replace(
                                            '/\D+/',
                                            '',
                                            ($u->countrycode ?? '') . ($u->mobile_no ?? ''),
                                        );
                                        $nameInitial = strtoupper(substr(trim($u->name ?? 'U'), 0, 1));
                                    @endphp
                                    <tr>
                                        <!-- Rank -->
                                        <td class="ps-3 text-center text-muted fs-8 fw-bold">
                                            {{ $rank }}
                                        </td>

                                        <!-- Customer Details -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="symbol symbol-35px symbol-circle bg-light-primary me-3 flex-shrink-0">
                                                    <span
                                                        class="symbol-label text-primary fw-bolder fs-6">{{ $nameInitial }}</span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-800 fw-bolder fs-7 text-hover-primary">
                                                        {{ $u->name ?? 'Customer' }}
                                                    </span>
                                                    <span class="text-muted fs-8">{{ $canSeeFullContact ? ($u->email ?? 'No email') : \App\Http\Controllers\ReportController::maskCustomerEmail($u->email) }}</span>
                                                    <span
                                                        class="badge badge-light-secondary fs-9 py-0 px-1 w-fit-content mt-0.5">ID:
                                                        #{{ $u->id }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Contact Info -->
                                        <td>
                                            @if (!empty($u->mobile_no))
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="badge badge-light fw-bold text-dark fs-8">
                                                        {{ $canSeeFullContact ? (($u->countrycode ? '+' . ltrim($u->countrycode, '+') . ' ' : '') . $u->mobile_no) : \App\Http\Controllers\ReportController::maskCustomerPhone($u->countrycode, $u->mobile_no) }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-muted fs-8">N/A</span>
                                            @endif
                                        </td>

                                        <!-- Due Orders Count (Modal Trigger) -->
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-sm btn-light-warning fw-bolder py-1 px-3 fs-8"
                                                onclick="openDueOrdersModal({{ $u->id }}, '{{ addslashes($u->name ?? 'Customer') }}')"
                                                title="View {{ $u->due_orders_count }} orders with pending due ({{ $currentReportYear }})">
                                                <i class="fa fa-shopping-bag text-warning me-1"></i>
                                                {{ $u->due_orders_count }}
                                                {{ $u->due_orders_count > 1 ? 'Orders' : 'Order' }}
                                            </button>
                                        </td>

                                        <!-- Total Ordered Amount -->
                                        <td class="text-end text-gray-700 fw-bold fs-7">
                                            £{{ number_format($u->total_order_amount, 2) }}
                                        </td>

                                        <!-- Total Paid -->
                                        <td class="text-end text-success fw-bold fs-7">
                                            £{{ number_format($u->total_received_amount, 2) }}
                                        </td>

                                        <!-- Net Due Amount (Highlighted in Red) -->
                                        <td class="text-end pe-3">
                                            <span class="badge badge-danger fs-7 fw-bolder px-3 py-1.5 shadow-sm">
                                                £{{ number_format($u->total_due_amount, 2) }}
                                            </span>
                                        </td>

                                        <!-- Last Order Date -->
                                        <td class="fs-8 text-gray-600">
                                            @if ($u->last_order_date)
                                                <span
                                                    class="fw-bold">{{ \Carbon\Carbon::parse($u->last_order_date)->format('d M Y') }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        <!-- Actions / CTAs -->
                                        <td class="text-center pe-3">
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <!-- WhatsApp Reminder CTA (POST to hide phone from URL) -->
                                                @if (!empty($cleanPhone))
                                                    <form method="POST" action="{{ route('whatsapp.chat.open-order') }}" target="_blank" class="d-inline-flex m-0">
                                                        @csrf
                                                        <input type="hidden" name="phone" value="{{ $cleanPhone }}">
                                                        <button type="submit" class="btn btn-icon btn-sm btn-light-success"
                                                            style="width: 32px; height: 32px;"
                                                            title="Send WhatsApp Payment Reminder">
                                                            <i class="fab fa-whatsapp fs-5"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Call Customer CTA -->
                                                    <button type="button" class="btn btn-icon btn-sm btn-light-primary"
                                                        style="width: 32px; height: 32px;"
                                                        onclick="callCustomer('{{ $canSeeFullContact ? $cleanPhone : '' }}', '{{ addslashes($u->name ?? 'Customer') }}')"
                                                        title="Call Customer">
                                                        <i class="fa fa-phone fs-6"></i>
                                                    </button>
                                                @endif

                                                <!-- Due Orders Modal Button CTA -->
                                                <button type="button" class="btn btn-icon btn-sm btn-light-danger"
                                                    style="width: 32px; height: 32px;"
                                                    onclick="openDueOrdersModal({{ $u->id }}, '{{ addslashes($u->name ?? 'Customer') }}')"
                                                    title="View Due Orders Breakdown ({{ $currentReportYear }})">
                                                    <i class="fa fa-list fs-6"></i>
                                                </button>

                                                <!-- Customer All Orders Link -->
                                                <a href="{{ route('orders.index', ['user' => $canSeeFullContact ? ($u->mobile_no ?: $u->email) : $u->id]) }}"
                                                    target="_blank" class="btn btn-icon btn-sm btn-light-dark"
                                                    style="width: 32px; height: 32px;"
                                                    title="View All Orders in Orders Screen">
                                                    <i class="fa fa-external-link-alt fs-7"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="fa fa-check-circle text-success fs-1 d-block mb-2"></i>
                                            <span class="fs-6 fw-bold">No customers found with pending due amounts for
                                                {{ $currentReportYear }}.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($users->hasPages())
                        <div class="d-flex justify-content-between align-items-center flex-wrap pt-4 border-top">
                            <div class="text-muted fs-8">
                                Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
                            </div>
                            <div>
                                {{ $users->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Standard Bootstrap Centered Modal for Customer Due Orders Breakdown -->
    <div class="modal fade" id="dueOrdersModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered mw-850px">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-light-danger py-4 border-bottom">
                    <div>
                        <h4 class="modal-title fw-bolder text-gray-800" id="modalCustomerName">Due Orders Breakdown
                            ({{ $currentReportYear }})</h4>
                        <span class="text-muted fs-8" id="modalCustomerContact">Loading customer details...</span>
                    </div>
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
                        <i class="fa fa-times fs-4"></i>
                    </div>
                </div>

                <div class="modal-body p-6">
                    <!-- Loading Spinner -->
                    <div id="modalLoading" class="text-center py-5">
                        <div class="spinner-border text-danger" role="status"></div>
                        <div class="text-muted fs-7 mt-2">Loading due orders...</div>
                    </div>

                    <!-- Content Area -->
                    <div id="modalContent" style="display: none;">
                        <!-- Summary Banner -->
                        <div class="d-flex align-items-center justify-content-between bg-light rounded p-4 mb-4 border">
                            <div>
                                <span class="text-muted fs-8 d-block fw-bold">Pending Orders
                                    ({{ $currentReportYear }})</span>
                                <strong class="fs-4 text-dark" id="modalOrdersCount">0</strong>
                            </div>
                            <div class="text-end">
                                <span class="text-muted fs-8 d-block fw-bold">Total Due</span>
                                <strong class="fs-3 text-danger fw-bolder" id="modalTotalDue">£0.00</strong>
                            </div>
                        </div>

                        <!-- Orders Table with Clickable Sort on Deadline Header -->
                        <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                            <table class="table table-sm table-row-dashed table-row-gray-300 align-middle">
                                <thead class="bg-light fw-bolder fs-8 sticky-top">
                                    <tr>
                                        <th>Order Code</th>
                                        <th>Status</th>
                                        <!-- Clickable Deadline Column Header for Ascending/Descending Toggle -->
                                        <th class="cursor-pointer text-hover-primary user-select-none"
                                            onclick="toggleModalDeadlineSort()" style="cursor: pointer;"
                                            title="Click to sort: Ascending / Descending by Deadline">
                                            <span>Deadline</span>
                                            <i id="modalDeadlineSortIcon" class="fa fa-sort-down ms-1 text-danger fs-6"
                                                title="Latest on top"></i>
                                        </th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Paid</th>
                                        <th class="text-end">Due</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="modalOrdersTbody" class="fs-8">
                                    <!-- Populated dynamically via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer py-3 bg-light">
                    <button type="button" class="btn btn-light-dark btn-sm fw-bold"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #searchResultss .user-select-item {
            cursor: pointer;
            transition: background 0.15s ease-in-out;
        }

        #searchResultss .user-select-item:hover {
            background-color: #f1f5f9;
        }
    </style>

    @push('scripts')
        <script>
            // State variable for modal orders and sorting
            window.currentModalOrders = [];
            window.modalSortDirection = 'desc'; // Default: latest date on top

            function callCustomer(phone, name) {
                if (!phone) return;
                if (typeof window.initiateCustomerCall === 'function') {
                    window.initiateCustomerCall(phone, name || 'Customer');
                } else {
                    window.location.href = 'tel:+' + phone;
                }
            }

            // Function to render orders rows in the modal
            function renderModalOrdersTable(orders) {
                var $tbody = $('#modalOrdersTbody');
                $tbody.empty();

                if (!orders || orders.length === 0) {
                    $tbody.html(
                        '<tr><td colspan="7" class="text-center py-4 text-muted">No pending due orders found for this customer in {{ $currentReportYear }}.</td></tr>'
                        );
                    return;
                }

                var html = '';
                orders.forEach(function(o) {
                    var orderUrl = '{{ url('/orders') }}?search=' + encodeURIComponent(o.order_id);
                    var payUrl = '{{ url('/orders/payment') }}/' + o.id;

                    // Deadline display
                    var deadlineDisplay = o.delivery_date ? o.delivery_date : '<span class="text-muted">N/A</span>';
                    if (o.delivery_time) {
                        deadlineDisplay += ' <span class="text-muted fs-9">(' + o.delivery_time + ')</span>';
                    }

                    html += '<tr>' +
                        '<td>' +
                        '<a href="' + orderUrl + '" target="_blank" class="fw-bolder text-primary text-hover-danger">' +
                        (o.order_id || ('#' + o.id)) +
                        '</a>' +
                        (o.title ? '<div class="text-muted fs-9 text-truncate" style="max-width: 180px;" title="' + o
                            .title.replace(/"/g, '&quot;') + '">' + o.title + '</div>' : '') +
                        '</td>' +
                        '<td><span class="badge fs-9" style="color:#000 !important;">' + (o.projectstatus || 'N/A') +
                        '</span></td>' +
                        '<td class="fw-bold text-gray-800">' + deadlineDisplay + '</td>' +
                        '<td class="text-end text-gray-700">£' + parseFloat(o.amount || 0).toFixed(2) + '</td>' +
                        '<td class="text-end text-success">£' + parseFloat(o.received_amount || 0).toFixed(2) +
                        '</td>' +
                        '<td class="text-end text-danger fw-bolder">£' + parseFloat(o.due_amount || 0).toFixed(2) +
                        '</td>' +
                        '<td class="text-center">' +
                        '<a href="' + payUrl +
                        '" target="_blank" class="btn btn-sm btn-icon btn-light-success" title="Add / Collect Payment">' +
                        '<i class="fa fa-credit-card fs-7"></i>' +
                        '</a>' +
                        '</td>' +
                        '</tr>';
                });
                $tbody.html(html);
            }

            // Click on Deadline column header to toggle Ascending / Descending
            function toggleModalDeadlineSort() {
                if (!window.currentModalOrders || window.currentModalOrders.length === 0) return;

                // Toggle direction
                window.modalSortDirection = (window.modalSortDirection === 'desc') ? 'asc' : 'desc';

                // Update sort icon
                var $icon = $('#modalDeadlineSortIcon');
                if (window.modalSortDirection === 'desc') {
                    $icon.attr('class', 'fa fa-sort-down ms-1 text-danger fs-6').attr('title',
                        'Latest date on top (Descending)');
                } else {
                    $icon.attr('class', 'fa fa-sort-up ms-1 text-success fs-6').attr('title',
                        'Earliest date on top (Ascending)');
                }

                // Sort window.currentModalOrders by delivery_date
                window.currentModalOrders.sort(function(a, b) {
                    var timeA = a.delivery_date ? new Date(a.delivery_date).getTime() : 0;
                    var timeB = b.delivery_date ? new Date(b.delivery_date).getTime() : 0;

                    if (window.modalSortDirection === 'desc') {
                        return timeB - timeA; // Latest date on top
                    } else {
                        return timeA - timeB; // Earliest date on top
                    }
                });

                renderModalOrdersTable(window.currentModalOrders);
            }

            function openDueOrdersModal(userId, customerName) {
                $('#modalCustomerName').text((customerName || 'Customer Due Orders') + ' ({{ $currentReportYear }})');
                $('#modalCustomerContact').text('Loading pending orders for Customer #' + userId + '...');
                $('#modalLoading').show();
                $('#modalContent').hide();
                $('#modalOrdersTbody').empty();

                // Reset sort state: default latest on top (desc)
                window.modalSortDirection = 'desc';
                $('#modalDeadlineSortIcon').attr('class', 'fa fa-sort-down ms-1 text-danger fs-6').attr('title',
                    'Latest date on top');

                // Open modal safely using jQuery/Bootstrap modal
                if (window.jQuery && typeof $('#dueOrdersModal').modal === 'function') {
                    $('#dueOrdersModal').modal('show');
                } else if (window.bootstrap && bootstrap.Modal) {
                    var modalEl = document.getElementById('dueOrdersModal');
                    var modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modalInstance.show();
                }

                $.ajax({
                    url: '{{ url('/customer-due-orders') }}/' + userId,
                    type: 'GET',
                    data: {
                        year: '{{ $currentReportYear }}'
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(res) {
                        $('#modalLoading').hide();
                        if (res.success && res.orders && res.orders.length > 0) {
                            var u = res.user;
                            var contactParts = [];
                            if (u.mobile_no) {
                                contactParts.push('📞 ' + (u.countrycode ? '+' + u.countrycode.replace('+', '') +
                                    ' ' : '') + u.mobile_no);
                            }
                            if (u.email) {
                                contactParts.push('✉️ ' + u.email);
                            }
                            $('#modalCustomerContact').text(contactParts.join(' | '));
                            $('#modalOrdersCount').text(res.orders_count + (res.orders_count > 1 ? ' Orders' :
                                ' Order'));
                            $('#modalTotalDue').text('£' + parseFloat(res.total_due).toFixed(2));

                            // Save orders in memory
                            window.currentModalOrders = res.orders;

                            // Sort by default: latest date on top (desc)
                            window.currentModalOrders.sort(function(a, b) {
                                var timeA = a.delivery_date ? new Date(a.delivery_date).getTime() : 0;
                                var timeB = b.delivery_date ? new Date(b.delivery_date).getTime() : 0;
                                return timeB - timeA;
                            });

                            renderModalOrdersTable(window.currentModalOrders);
                            $('#modalContent').show();
                        } else {
                            window.currentModalOrders = [];
                            $('#modalOrdersTbody').html(
                                '<tr><td colspan="7" class="text-center py-4 text-muted">No pending due orders found for this customer in {{ $currentReportYear }}.</td></tr>'
                                );
                            $('#modalContent').show();
                        }
                    },
                    error: function(xhr) {
                        $('#modalLoading').hide();
                        $('#modalOrdersTbody').html(
                            '<tr><td colspan="7" class="text-center py-4 text-danger">Failed to load order details. Please check connection and try again.</td></tr>'
                            );
                        $('#modalContent').show();
                    }
                });
            }

            // Order Page Style Live Autocomplete User Search
            (function() {
                function initUserAutocomplete() {
                    if (typeof jQuery === 'undefined' || !window.jQuery) {
                        setTimeout(initUserAutocomplete, 50);
                        return;
                    }
                    var $ = window.jQuery;
                    var searchTimeout = null;

                    // Enter key presses trigger submit
                    $('#searchInput').off('keypress.usersearch').on('keypress.usersearch', function(e) {
                        if (e.which === 13 || e.keyCode === 13) {
                            $('#searchResultss').hide();
                        }
                    });

                    // Typing in search input triggers live dropdown
                    $('#searchInput').off('input.usersearch focus.usersearch').on('input.usersearch focus.usersearch',
                        function() {
                            var searchValue = $(this).val().trim();
                            clearTimeout(searchTimeout);

                            // If user cleared the input, reset UID
                            if (searchValue.length === 0) {
                                $('#selectedValue').val('');
                                $('#searchResultss').hide().empty();
                                return;
                            }

                            if (searchValue.length >= 2 || (searchValue.length >= 1 && !isNaN(searchValue))) {
                                $('#searchResultss').html(
                                    '<div class="p-3 text-center text-muted fs-8 d-flex align-items-center justify-content-center gap-2">' +
                                    '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>' +
                                    '<span>Searching users...</span>' +
                                    '</div>'
                                ).show();

                                searchTimeout = setTimeout(function() {
                                    var canSeeFullContact = {{ $canSeeFullContact ? 'true' : 'false' }};
                                    function maskContactPhone(p) {
                                        if (!p) return '';
                                        var d = p.replace(/\D+/g, '');
                                        if (d.length <= 4) return '******';
                                        var prefix = p.indexOf('+') === 0 ? '+' : '';
                                        return prefix + '******' + d.slice(-4);
                                    }
                                    function maskContactEmail(em) {
                                        if (!em || em.indexOf('@') === -1) return '';
                                        var p = em.split('@');
                                        var n = p[0], d = p[1];
                                        var m = (n.length <= 2) ? (n.charAt(0) + '******') : (n.charAt(0) + '******' + n.charAt(n.length - 1));
                                        return m + '@' + d;
                                    }

                                    $.ajax({
                                        url: "{{ route('search-order') }}",
                                        type: "GET",
                                        data: {
                                            user: searchValue
                                        },
                                        success: function(response) {
                                            var resultsHtml = '';
                                            if (response && response.length > 0) {
                                                $.each(response, function(key, value) {
                                                    var dispMobile = canSeeFullContact ? value.mobile_no : maskContactPhone(value.mobile_no);
                                                    var dispEmail = canSeeFullContact ? value.email : maskContactEmail(value.email);
                                                    var mobileStr = dispMobile ?
                                                        ' | 📞 ' + dispMobile : '';
                                                    var emailStr = dispEmail ? dispEmail : '';
                                                    var idBadge = value.id ?
                                                        '<span class="badge badge-light-primary fw-bolder fs-9 ms-2 px-2 py-0.5" style="border: 1px solid #bfdbfe;">ID: #' +
                                                        value.id + '</span>' : '';
                                                    resultsHtml +=
                                                        '<a href="javascript:void(0)" class="dropdown-item user-select-item p-3 border-bottom text-wrap" ' +
                                                        'data-id="' + value.id +
                                                        '" data-email="' + (canSeeFullContact ? (value.email || '') : dispEmail) +
                                                        '" data-name="' + (value.name ||
                                                        '') + '" data-mobile="' + (canSeeFullContact ? (value.mobile_no || '') : dispMobile) + '">' +
                                                        '<div class="d-flex align-items-center justify-content-between">' +
                                                        '<span class="fw-bolder text-dark fs-7">' +
                                                        (value.name || 'User') + '</span>' +
                                                        idBadge +
                                                        '</div>' +
                                                        '<div class="text-muted fs-8 mt-1">' +
                                                        emailStr + mobileStr + '</div>' +
                                                        '</a>';
                                                });
                                            } else {
                                                resultsHtml =
                                                    '<div class="p-3 text-muted fs-8 text-center">No users found matching "' +
                                                    searchValue + '"</div>';
                                            }
                                            $('#searchResultss').html(resultsHtml).show();
                                        },
                                        error: function() {
                                            $('#searchResultss').html(
                                                '<div class="p-3 text-danger fs-8 text-center">Error loading results</div>'
                                                ).show();
                                        }
                                    });
                                }, 200);
                            } else {
                                $('#searchResultss').hide().empty();
                            }
                        });

                    // Click user item from dropdown
                    $(document).off('click.useritem', '#searchResultss .user-select-item').on('click.useritem',
                        '#searchResultss .user-select-item',
                        function(e) {
                            e.preventDefault();
                            var selectedId = $(this).attr('data-id');
                            var selectedName = $(this).attr('data-name');
                            var selectedMobile = $(this).attr('data-mobile');
                            var selectedEmail = $(this).attr('data-email');
                            var label = selectedName ? selectedName : (selectedMobile ? selectedMobile : selectedEmail);

                            $('#searchInput').val(label);
                            $('#selectedValue').val(selectedId);
                            $('#searchResultss').hide().empty();

                            // Auto-submit form
                            $('#customerDueFilterForm').submit();
                        });

                    // Close dropdown when clicking outside
                    $(document).on('click', function(e) {
                        if (!$(e.target).closest('#searchInput, #searchResultss').length) {
                            $('#searchResultss').hide();
                        }
                    });
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initUserAutocomplete);
                } else {
                    initUserAutocomplete();
                }
            })();
        </script>
    @endpush
@endsection
