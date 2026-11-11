@extends('layouts.app')
@section('content')
    @include('back-end.group-master.user-modal')
    <style>
        main.content {
            padding-top: 0 !important;
        }

        .user-tabs {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .user-tab {
            border: 1px solid #e4e6ef;
            border-radius: 12px;
            padding: 18px;
            color: #5e6278;
            background: #fff;
            transition: .2s ease;
        }

        .user-tab:hover {
            border-color: #b5b5c3;
            transform: translateY(-1px);
        }

        .user-tab.active {
            color: #fff;
            border-color: #009ef7;
            background: linear-gradient(135deg, #009ef7, #006ae6);
            box-shadow: 0 8px 22px rgba(0, 158, 247, .22);
        }

        .user-tab.confirmed.active {
            border-color: #50cd89;
            background: linear-gradient(135deg, #50cd89, #20a86b);
            box-shadow: 0 8px 22px rgba(80, 205, 137, .22);
        }

        .user-tab.not-confirmed.active {
            border-color: #f1a208;
            background: linear-gradient(135deg, #ffc700, #e89600);
            box-shadow: 0 8px 22px rgba(255, 199, 0, .22);
        }

        .user-tab.dummy-email.active {
            border-color: #7239ea;
            background: linear-gradient(135deg, #7239ea, #5014d0);
            box-shadow: 0 8px 22px rgba(114, 57, 234, .22);
        }

        .user-tab .tab-count {
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
        }

        .user-tab .tab-label {
            font-weight: 700;
            margin-top: 8px;
        }

        .user-tab .tab-note {
            font-size: 12px;
            margin-top: 4px;
            opacity: .78;
        }

        .user-list-table tbody tr:hover {
            background: #f9fbfd;
        }

        #searchResultss .user-select-item {
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
        }

        #searchResultss .user-select-item:hover {
            background-color: #f1faff !important;
        }

        @media (max-width: 767.98px) {
            .user-tabs {
                grid-template-columns: 1fr;
            }
        }

        .user-list-table tbody tr:hover {
            background: #f9fbfd;
        }

        .copy-contact-btn {
            opacity: 0.65;
            transition: opacity 0.2s ease, transform 0.15s ease;
            cursor: pointer;
        }

        .copy-contact-btn:hover {
            opacity: 1;
            transform: scale(1.15);
        }

        .copy-contact-btn:hover i {
            color: #009ef7 !important;
        }

        .user-toolbar {
            background: #fff;
            width: 100%;
            margin: 0;
            padding: 10px 0;
            border-bottom: 1px solid #eff2f5;
            border-top: 12px solid #eff2f5;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            position: relative;
            z-index: 1;
        }

        .user-toolbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
            gap: 10px;
            padding: 0 30px;
        }

        .user-toolbar-inner .page-title h1 {
            white-space: nowrap;
        }

        /* Desktop only: pull the bar up under the header */
        @media (min-width: 992px) {
            .user-toolbar {
                margin-top: -66px;
            }
        }

        /* Tablet / mobile */
        @media (max-width: 991.98px) {
            .user-toolbar {
                border-top: 0;
                margin-top: 0;
            }

            .user-toolbar-inner {
                padding: 0 15px;
            }
        }

        @media (max-width: 575.98px) {
            .user-toolbar-inner .page-title h1 {
                font-size: 1.1rem !important;
            }

            /* hide the "Assignment In need" subtitle and divider on small phones */
            .user-toolbar-inner .page-title small,
            .user-toolbar-inner .page-title h1>span {
                display: none !important;
            }
        }

        /* table ui changes mk 6 10 26 */
        /* ===== User table: clean alignment ===== */
        .user-list-table {
            width: 100%;
        }

        .user-list-table th,
        .user-list-table td {
            padding: 14px 12px !important;
            vertical-align: middle !important;
            white-space: nowrap;
        }

        .user-list-table thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .4px;
            line-height: 1.35;
            vertical-align: middle !important;
            border-bottom: 1px solid #eff2f5;
        }

        .user-list-table thead th small {
            font-size: 10px;
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0;
            opacity: .8;
        }

        /* Role and Join Date: left aligned like other columns */
        .user-list-table thead th.col-role,
        .user-list-table thead th.col-join-date,
        .user-list-table tbody td.col-role,
        .user-list-table tbody td.col-join-date {
            text-align: left !important;
        }

        .user-list-table tbody td.col-role .flex-stack,
        .user-list-table tbody td.col-join-date .flex-stack {
            justify-content: flex-start !important;
        }

        /* Order Count and Follow-ups: centered under their titles */
        .user-list-table thead th.col-order-count,
        .user-list-table thead th.col-followups,
        .user-list-table tbody td.col-order-count,
        .user-list-table tbody td.col-followups {
            text-align: center !important;
        }

        /* Referral column: left aligned */
        .user-list-table thead th.col-referral,
        .user-list-table tbody td.col-referral {
            text-align: left !important;
        }

        /* Actions: right aligned */
        .user-list-table thead th.col-actions,
        .user-list-table tbody td.col-actions {
            text-align: right !important;
        }
    </style>
    @php
        $canViewReferral = \App\Services\ReferralCodeService::canView(auth()->user());
        $canEditReferral = \App\Services\ReferralCodeService::canEdit(auth()->user());
        $hasActiveFilters =
            request()->filled('user') ||
            request()->filled('user_id') ||
            request()->filled('uid') ||
            request()->filled('start_date') ||
            request()->filled('end_date') ||
            request()->filled('role') ||
            request()->filled('countrycode') ||
            request()->filled('order_period') ||
            (request()->filled('order_sort') && request('order_sort') !== 'desc') ||
            request()->filled('college_name') ||
            request()->filled('group_id');
    @endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="padding-top:0px!important">

        <!-- TOOLBAR: full width, white, touches the header -->
        <div class="user-toolbar">
            <div class="user-toolbar-inner">
                <div class="page-title d-flex align-items-center flex-wrap">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Users
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In need</small>
                    </h1>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button"
                        class="btn btn-sm btn-icon {{ $hasActiveFilters ? 'btn-primary' : 'btn-light-primary' }} toggle-filter-btn"
                        id="toggleFilterBtnTop" title="Filter" style="border-radius: 8px;">
                        <i class="fa fa-filter fs-4"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="kt_content_container" class="container-xxl pt-5">

            <!-- Filter Module (Initially Hidden) -->
            <div class="col-xl-12 mb-5" id="filterModuleWrapper" style="{{ $hasActiveFilters ? '' : 'display: none;' }}">
                <div class="card card-xxl-stretch mb-0 shadow-sm border border-gray-200">
                    <div class="card-header border-0 pt-4 pb-2 d-flex align-items-center justify-content-between">
                        <h3 class="card-title align-items-center mb-0">
                            <span class="card-label fw-bolder fs-4 text-gray-800">
                                <i class="fa fa-filter text-primary me-2"></i>Filter
                            </span>
                        </h3>
                        <div class="card-toolbar m-0">
                            <button type="button" class="btn btn-sm btn-icon btn-light-primary toggle-filter-btn"
                                title="Close Filter" style="border-radius: 8px;">
                                <i class="fa fa-times fs-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <form action="" method="GET">

                            <!-- YEH HIDDEN INPUT ADD KIYA HAI TAB STATE KE LIYE -->
                            <input type="hidden" name="tab" value="{{ request('tab', 'all') }}">

                            <div class="row g-3 mb-3">
                                <!-- User ID filter -->
                                <div class="col-lg-3 fv-row fv-plugins-icon-container position-relative">
                                    <input type="text" id="searchInput" name="user"
                                        class="form-control form-control-solid" placeholder="User-Name,Number,Email,Referral Code"
                                        autocomplete="off"
                                        value="{{ request('user') ? request('user') : (isset($data['selected_user']) && $data['selected_user'] ? $data['selected_user']->name . ' (ID: ' . $data['selected_user']->id . ')' : '') }}">
                                    <!-- Container to display custom search results dropdown -->
                                    <div id="searchResultss" class="dropdown-menu w-100 shadow-lg p-0 mt-1"
                                        style="display:none; max-height: 250px; overflow-y: auto; z-index: 1050; position: absolute;">
                                    </div>
                                    <!-- Hidden field to store the selected value -->
                                    <input type="hidden" id="selectedValue" name="user_id"
                                        value="{{ request('user_id') ?? (request('uid') ?? (isset($data['selected_user']) && $data['selected_user'] ? $data['selected_user']->id : '')) }}">
                                </div>

                                <!-- Start Date filter -->
                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <input type="date" name="start_date" placeholder="Start Date" class="form-control"
                                        value="{{ request('start_date') }}" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>

                                <!-- End Date filter -->
                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <input type="date" name="end_date" placeholder="End Date" class="form-control"
                                        value="{{ request('end_date') }}" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>

                                <!-- Role filter -->
                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="role" aria-label="Select a Role" data-control="select2"
                                        class="form-select form-select-solid form-select-lg select2-hidden-accessible">
                                        <option value="">Select Role</option>
                                        @foreach ($data['role'] as $role)
                                            <option value="{{ $role->id }}"
                                                {{ $role->id == request('role') ? 'selected' : '' }}>
                                                {{ $role->role }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>

                                <!-- Country Code filter -->
                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="countrycode" class="form-select form-control" data-control="select2"
                                        data-placeholder="Select Country">
                                        <option value="">Select Country</option>

                                        @if (isset($data['countryList']))
                                            @foreach ($data['countryList'] as $country)
                                                <option value="{{ $country }}"
                                                    {{ request('countrycode') == $country ? 'selected' : '' }}>
                                                    {{ $country }}
                                                </option>
                                            @endforeach
                                        @endif

                                        <option value="Other" {{ request('countrycode') == 'Other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>

                                {{-- Commented out per request: hide Filter By Order Category & Min Genuine Orders fields
                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="order_category" class="form-select form-select-solid"
                                        data-control="select2" data-placeholder="Filter By Order Category">
                                        <option value="">All Categories</option>
                                        <option value="multiple"
                                            {{ request('order_category') == 'multiple' ? 'selected' : '' }}>Multiple Orders (2+ Orders)
                                        </option>
                                        <option value="2_to_3"
                                            {{ request('order_category') == '2_to_3' ? 'selected' : '' }}>Repeat (2-3 Orders)
                                        </option>
                                        <option value="Retainer"
                                            {{ in_array(strtolower((string) request('order_category')), ['retainer', 'repeated'], true) ? 'selected' : '' }}>Retainer (4+ Orders)
                                        </option>
                                        <option value="loyal"
                                            {{ request('order_category') == 'loyal' ? 'selected' : '' }}>Loyal Customers (&gt;10 Orders)
                                        </option>
                                        <option value="single"
                                            {{ request('order_category') == 'single' ? 'selected' : '' }}>Single Order (1 Order)
                                        </option>
                                        <option value="beginner"
                                            {{ request('order_category') == 'beginner' ? 'selected' : '' }}>Beginners (1-3 Orders)
                                        </option>
                                    </select>
                                </div>

                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <input type="number" name="min_orders" min="1" step="1"
                                        class="form-control form-control-solid" placeholder="Min Genuine Orders (e.g. 2)"
                                        value="{{ request('min_orders') }}">
                                </div>
                                --}}

                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    @php
                                        $selectedOrderSort = in_array(strtolower((string) request('order_sort', $orderSort ?? 'desc')), ['desc', 'asc', 'latest'], true)
                                            ? strtolower((string) request('order_sort', $orderSort ?? 'desc'))
                                            : 'desc';
                                    @endphp
                                    <select name="order_sort" class="form-select form-select-solid"
                                        data-control="select2" data-placeholder="Sort By Orders">
                                        <option value="desc" {{ $selectedOrderSort === 'desc' ? 'selected' : '' }}>Orders Count: Max to Min</option>
                                        <option value="asc" {{ $selectedOrderSort === 'asc' ? 'selected' : '' }}>Orders Count: Min to Max</option>
                                        <option value="latest" {{ $selectedOrderSort === 'latest' ? 'selected' : '' }}>Latest Users (Newest First)</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="order_period" class="form-select form-select-solid"
                                        data-control="select2" data-placeholder="Order Period">
                                        <option value="">Order Period (Auto)</option>
                                        <option value="lifetime"
                                            {{ request('order_period') == 'lifetime' ? 'selected' : '' }}>All Time (Lifetime)
                                        </option>
                                        <option value="18_months"
                                            {{ request('order_period') == '18_months' ? 'selected' : '' }}>Last 1.5 Years
                                        </option>
                                        <option value="this_year"
                                            {{ request('order_period') == 'this_year' ? 'selected' : '' }}>This Year ({{ date('Y') }})
                                        </option>
                                    </select>
                                </div>

                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="college_name" class="form-select form-select-solid" data-control="select2"
                                        data-placeholder="Filter By University">
                                        <option value="">All Universities</option>

                                        @foreach ($data['collegeList'] as $college)
                                            <option value="{{ $college }}"
                                                {{ request('college_name') == $college ? 'selected' : '' }}>
                                                {{ $college }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 fv-row"><select name="group_id"
                                        class="form-select form-select-solid" data-control="select2"
                                        data-placeholder="Filter By Group">
                                        <option value="">All Groups</option>
                                        @foreach ($data['groups'] as $group)
                                            <option value="{{ $group->id }}"
                                                {{ (string) request('group_id') === (string) $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}</option>
                                        @endforeach
                                    </select></div>

                                <!-- Search and Reset buttons -->
                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                    <a href="/user" class="btn btn-sm btn-danger">Reset</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="row gy-5 g-xl-8">
                <div class="col-xl-12">
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">User Management</span>
                                <span class="text-muted mt-1 fw-bold fs-7">Manage lead conversion and customer status in
                                    one place</span>
                            </h3>
                            <div class="d-flex align-items-center gap-2">

                                @if(auth()->check() && (int) auth()->user()->role_id === 1)
                                <a onclick="exportUsers()" style="height: fit-content;"
                                    class="btn btn-sm btn-danger">Export</a>
                                @endif
                            </div>
                        </div>

                        <div class="card-body pb-0 pt-4">
                            <div class="user-tabs">
                                <a href="{{ request()->fullUrlWithQuery(['tab' => 'all', 'page' => null]) }}"
                                    class="user-tab text-decoration-none {{ $tab === 'all' ? 'active' : '' }}">
                                    <div class="tab-count">{{ number_format($countAll ?? 0) }}</div>
                                    <div class="tab-label"><i class="fa fa-users me-2"></i>All Customers</div>
                                    <div class="tab-note">Customers with a lead or an order</div>
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['tab' => 'confirmed', 'page' => null]) }}"
                                    class="user-tab confirmed text-decoration-none {{ $tab === 'confirmed' ? 'active' : '' }}">
                                    <div class="tab-count">{{ number_format($countConfirmed ?? 0) }}</div>
                                    <div class="tab-label"><i class="fa fa-check-circle me-2"></i>Confirmed Users</div>
                                    <div class="tab-note">Customers who have placed at least one order</div>
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['tab' => 'not_confirmed', 'page' => null]) }}"
                                    class="user-tab not-confirmed text-decoration-none {{ $tab === 'not_confirmed' ? 'active' : '' }}">
                                    <div class="tab-count">{{ number_format($countNotConfirmed ?? 0) }}</div>
                                    <div class="tab-label"><i class="fa fa-user-clock me-2"></i>Not Confirmed</div>
                                    <div class="tab-note">Lead created, but no order has been placed</div>
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['tab' => 'dummy_email', 'page' => null]) }}"
                                    class="user-tab dummy-email text-decoration-none {{ $tab === 'dummy_email' ? 'active' : '' }}">
                                    <div class="tab-count">{{ number_format($countDummyEmail ?? 0) }}</div>
                                    <div class="tab-label"><i class="fa fa-envelope-open-text me-2"></i>Dummy Emails</div>
                                    <div class="tab-note">Auto-generated emails requiring real email</div>
                                </a>
                            </div>
                        </div>

                        <div class="card-body py-3">
                            <div class="table-responsive">
                                <table
                                    class="table user-list-table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bolder text-muted">
                                            <th class="min-w-200px">User</th>
                                            <th class="min-w-200px">Contact</th>
                                            <th class="min-w-80px">Groups</th>
                                            <th class="min-w-130px">Conversion</th>
                                            <th class="min-w-80px col-role">Role</th>
                                            <th class="min-w-190px col-join-date">Join Date</th>
                                            @php
                                                $currentOrderSort = in_array(strtolower((string) request('order_sort', $orderSort ?? 'desc')), ['desc', 'asc', 'latest'], true)
                                                    ? strtolower((string) request('order_sort', $orderSort ?? 'desc'))
                                                    : 'desc';
                                                $nextHeaderSort = $currentOrderSort === 'desc' ? 'asc' : 'desc';
                                                $sortArrow = $currentOrderSort === 'asc' ? '↑' : ($currentOrderSort === 'desc' ? '↓' : '↕');
                                                $sortTitle = $currentOrderSort === 'desc'
                                                    ? 'Sorted High to Low (Click for Min to Max)'
                                                    : 'Sorted Low to High / Default (Click for Max to Min)';
                                            @endphp
                                            <th class="min-w-100px col-order-count">
                                                <a href="{{ request()->fullUrlWithQuery(['order_sort' => $nextHeaderSort, 'page' => null]) }}"
                                                    class="text-muted text-hover-primary text-decoration-none d-inline-flex align-items-center gap-1"
                                                    title="{{ $sortTitle }}">
                                                    <span>Order Count</span>
                                                    <span class="fw-bolder {{ $currentOrderSort !== 'latest' ? 'text-primary' : 'text-muted' }}">{{ $sortArrow }}</span>
                                                </a>
                                                <br><small>({{ $orderPeriodLabel ?? 'Last 1.5 Yrs' }})</small>
                                            </th>
                                            <th class="min-w-100px col-followups">Follow-ups<br><small>(Last 1 Yr)</small></th>
                                            @if ($canViewReferral)
                                                <th class="min-w-170px col-referral">Referral Code</th>
                                            @endif
                                            <th class="min-w-90px col-actions">Actions</th>
                                        </tr>
                                    </thead>
                                    {{-- mk 5 10 26 - Infinite Scroll Tbody --}}
                                    <tbody id="userListTbody">
                                        @include('user.partials.rows', [
                                            'users' => $data['users'],
                                            'codeToCountry' => $codeToCountry,
                                            'data' => $data,
                                        ])
                                    </tbody>
                                </table>
                            </div>

                            {{-- mk 5 10 26 - Load More Button & Status Container --}}
                            <div id="userLoadMoreSection"
                                class="d-flex flex-column flex-md-row justify-content-between align-items-center py-5 px-4 bg-light-primary rounded mt-4">
                                <div class="text-gray-700 fw-bold fs-7 mb-3 mb-md-0">
                                    Showing <span id="displayedUserCount"
                                        class="text-primary fw-bolder">{{ count($data['users']) }}</span> of <span
                                        id="totalUserCount"
                                        class="text-primary fw-bolder">{{ number_format($data['users']->total()) }}</span>
                                    users
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" id="btnLoadMoreUsers"
                                        class="btn btn-primary btn-sm px-6 py-3 fw-bolder shadow-sm {{ $data['users']->hasMorePages() ? '' : 'd-none' }}"
                                        onclick="window.loadMoreUsers()">
                                        <span id="btnLoadMoreText" class="d-inline-flex align-items-center">
                                            <i class="fa fa-arrow-down me-2"></i> Load More (20 Users)
                                        </span>
                                        <span id="btnLoadMoreSpinner" class="d-none align-items-center">
                                            <span class="spinner-border spinner-border-sm me-2" role="status"
                                                aria-hidden="true"></span>
                                            Loading more users...
                                        </span>
                                    </button>
                                    <div id="userAllLoadedBadge"
                                        class="badge badge-light-success fs-7 py-3 px-4 fw-bold {{ $data['users']->hasMorePages() ? 'd-none' : '' }}">
                                        <i class="fa fa-check-circle text-success me-2"></i> All
                                        {{ number_format($data['users']->total()) }} users loaded
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @if ($canEditReferral)
        <div class="modal fade" id="editReferralModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-550px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title fw-bolder">Edit Referral Settings — <span id="refModalUserName" class="text-primary"></span></h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editReferralForm">
                        @csrf
                        <input type="hidden" id="refModalUserId" value="">
                        <div class="modal-body py-5 px-7">
                            <div id="refModalAlert" class="alert d-none mb-4"></div>

                            <div id="refClientFieldsWrapper">
                                <div class="mb-4">
                                    <label class="form-label fw-bold required">Referral Code (5-10 chars)</label>
                                    <input type="text" id="refModalCode" name="referral_code" maxlength="10"
                                        class="form-control form-control-solid text-uppercase font-monospace"
                                        placeholder="e.g. USK49M">
                                    <div class="form-text">First 3 characters identify the marketing executive prefix.</div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold required">Discount Percentage (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" id="refModalDiscount" name="referral_discount_percent"
                                        class="form-control form-control-solid" value="10.00">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Marketing Executive</label>
                                    <select id="refModalExecutiveId" name="referral_executive_id" class="form-select form-select-solid js-referral-exec-select" data-selected-executive="">
                                        <option value="">System / Default (AIN)</option>
                                    </select>
                                </div>
                            </div>

                            <div id="refExecutiveFieldsWrapper" class="d-none">
                                <div class="mb-2">
                                    <label class="form-label fw-bold required">Executive 3-Char Prefix</label>
                                    <input type="text" id="refModalPrefix" name="referral_prefix" maxlength="3"
                                        class="form-control form-control-solid text-uppercase font-monospace"
                                        placeholder="e.g. USK">
                                    <div class="form-text">Exactly 3 letters or digits used to prefix client referral codes converted by this executive.</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="refModalSubmitBtn" class="btn btn-primary fw-bold">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let searchTimeout = null;

            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13 || e.keyCode === 13) {
                    e.preventDefault();
                    $('#searchResultss').hide();
                    $(this).closest('form').submit();
                }
            });

            $('#searchInput').on('input focus', function() {
                var searchValue = $(this).val().trim();

                clearTimeout(searchTimeout);

                var currentSelectedId = $('#selectedValue').val();
                if (!searchValue || (currentSelectedId && searchValue.indexOf('ID: ' +
                        currentSelectedId) === -1)) {
                    if (!searchValue) {
                        $('#selectedValue').val('');
                    }
                }

                if (searchValue.length >= 2) {
                    $('#searchResultss').html(
                        '<div class="p-3 text-center text-muted fs-7 d-flex align-items-center justify-content-center gap-2">' +
                        '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>' +
                        '<span>Searching users & orders...</span>' +
                        '</div>'
                    ).show();

                    searchTimeout = setTimeout(function() {
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
                                        var mobileStr = value.mobile_no ?
                                            ' | 📞 ' + value.mobile_no : '';
                                        var orderAttr = value.order_id ?
                                            ' data-order-id="' + value
                                            .order_id + '"' : '';
                                        var idBadge = value.id ?
                                            '<span class="badge badge-light-primary fw-bolder fs-8 ms-2 px-2 py-0.5" style="border: 1px solid #bfdbfe;">ID: ' +
                                            value.id + '</span>' : '';
                                        resultsHtml +=
                                            '<a href="javascript:void(0)" class="dropdown-item user-select-item p-3 border-bottom text-wrap" ' +
                                            'data-id="' + value.id +
                                            '" data-email="' + (value.email ||
                                                '') + '" data-name="' + (value
                                                .name || '') + '"' + orderAttr +
                                            '>' +
                                            '<div class="d-flex align-items-center justify-content-between">' +
                                            '<span class="fw-bolder text-dark fs-6">' +
                                            (value.name || '') + '</span>' +
                                            idBadge +
                                            '</div>' +
                                            '<div class="text-muted fs-7 mt-1">' +
                                            (value.email || '') + mobileStr +
                                            '</div>' +
                                            '</a>';
                                    });
                                } else {
                                    resultsHtml =
                                        '<div class="p-3 text-muted fs-7 text-center">No results found</div>';
                                }
                                $('#searchResultss').html(resultsHtml).show();
                            },
                            error: function() {
                                $('#searchResultss').html(
                                    '<div class="p-3 text-danger fs-7 text-center">Error loading results</div>'
                                ).show();
                            }
                        });
                    }, 250);
                } else {
                    $('#searchResultss').hide().empty();
                    if (searchValue.length === 0) {
                        $('#selectedValue').val('');
                    }
                }
            });

            // Handle click on custom dropdown item
            $(document).on('click', '.user-select-item', function(e) {
                e.preventDefault();
                var selectedId = $(this).attr('data-id');
                var selectedEmail = $(this).attr('data-email');
                var selectedName = $(this).attr('data-name');
                var selectedOrderId = $(this).attr('data-order-id');

                if (selectedOrderId) {
                    $('#searchInput').val(selectedOrderId);
                    $('#selectedValue').val(selectedId);
                } else {
                    var displayName = selectedName;
                    if (selectedId && displayName.indexOf('ID:') === -1) {
                        displayName += ' (ID: ' + selectedId + ')';
                    }
                    $('#searchInput').val(displayName);
                    $('#selectedValue').val(selectedId);
                }
                $('#searchResultss').hide().empty();
                $('#searchInput').closest('form').submit();
            });

            // Close dropdown on clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#searchInput, #searchResultss').length) {
                    $('#searchResultss').hide();
                }
            });

            // Toggle Filter Module
            $(document).on('click', '.toggle-filter-btn', function(e) {
                e.preventDefault();
                $('#filterModuleWrapper').stop().slideToggle(250, function() {
                    var isVisible = $('#filterModuleWrapper').is(':visible');
                    if (isVisible) {
                        $('.toggle-filter-btn').removeClass('btn-light-primary').addClass(
                            'btn-primary');
                    } else {
                        @if (!$hasActiveFilters)
                            $('.toggle-filter-btn').removeClass('btn-primary').addClass(
                                'btn-light-primary');
                        @endif
                    }
                });
            });
        });

        function exportUsers() {
            var urlParams = new URLSearchParams(window.location.search);

            var userId = $('#selectedValue').val() || $('input[name="user_id"]').val() || urlParams.get('user_id') || urlParams.get('uid') || '';
            var userText = $('#searchInput').val() || $('input[name="user"]').val() || urlParams.get('user') || '';
            var startDate = $('input[name="start_date"]').val() || urlParams.get('start_date') || '';
            var endDate = $('input[name="end_date"]').val() || urlParams.get('end_date') || '';
            var roleId = $('select[name="role"]').val() || urlParams.get('role') || '';
            var countrycode = $('select[name="countrycode"]').val() || urlParams.get('countrycode') || '';
            var orderCategory = urlParams.get('order_category') || '';
            var minOrders = urlParams.get('min_orders') || '';
            var orderPeriod = $('select[name="order_period"]').val() || urlParams.get('order_period') || '';
            var orderSort = $('select[name="order_sort"]').val() || urlParams.get('order_sort') || 'desc';
            var collegeName = $('select[name="college_name"]').val() || urlParams.get('college_name') || '';
            var groupId = $('select[name="group_id"]').val() || urlParams.get('group_id') || '';
            var tab = urlParams.get('tab') || $('input[name="tab"]').val() || '{{ $tab ?? "all" }}';

            // Use CSRF token for security
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            var requestData = {
                _token: csrfToken,
                user_id: userId,
                user: userText,
                start_date: startDate,
                end_date: endDate,
                role: roleId,
                countrycode: countrycode,
                order_category: orderCategory,
                min_orders: minOrders,
                order_period: orderPeriod,
                order_sort: orderSort,
                college_name: collegeName,
                group_id: groupId,
                tab: tab
            };

            $.ajax({
                type: 'GET',
                url: '{{ route('export.users') }}',
                data: requestData,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data) {
                    var blob = new Blob([data], {
                        type: 'text/csv'
                    });
                    var url = window.URL.createObjectURL(blob);
                    var link = document.createElement('a');
                    var filename = 'users_' + new Date().toISOString().slice(0, 19).replace(/[-T:/]/g, '') +
                        '.csv';
                    link.href = url;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(link);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while exporting the data.');
                }
            });
        }

        // mk 5 10 26 - Infinite Scroll & Load More Button Implementation
        (function() {
            let currentPage = {{ (int) $data['users']->currentPage() }};
            let hasMore = {{ $data['users']->hasMorePages() ? 'true' : 'false' }};
            let isLoading = false;
            let displayedCount = {{ (int) count($data['users']) }};
            const totalCount = {{ (int) $data['users']->total() }};

            window.loadMoreUsers = function() {
                if (isLoading || !hasMore) return;
                isLoading = true;

                const btn = document.getElementById('btnLoadMoreUsers');
                const btnText = document.getElementById('btnLoadMoreText');
                const btnSpinner = document.getElementById('btnLoadMoreSpinner');
                if (btn) btn.disabled = true;
                if (btnText) {
                    btnText.classList.remove('d-inline-flex');
                    btnText.classList.add('d-none');
                }
                if (btnSpinner) {
                    btnSpinner.classList.remove('d-none');
                    btnSpinner.classList.add('d-inline-flex');
                }

                const nextPage = currentPage + 1;
                // Build dynamic URL using current browser location to guarantee correct path under Apache/subdirectories
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('page', nextPage);
                currentUrl.searchParams.set('scroll', '1');
                const fetchUrl = currentUrl.toString();

                fetch(fetchUrl, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('HTTP status ' + res.status);
                        return res.json();
                    })
                    .then(data => {
                        if (data && data.html && data.html.trim().length > 0) {
                            const tbody = document.getElementById('userListTbody');
                            if (tbody) {
                                tbody.insertAdjacentHTML('beforeend', data.html);
                            }
                            currentPage = data.current_page || nextPage;
                            hasMore = Boolean(data.has_more);

                            // Count appended rows
                            const temp = document.createElement('div');
                            temp.innerHTML = data.html;
                            const rowsAdded = temp.querySelectorAll('tr').length;
                            displayedCount += rowsAdded;
                            if (displayedCount > totalCount) displayedCount = totalCount;

                            const countEl = document.getElementById('displayedUserCount');
                            if (countEl) countEl.innerText = displayedCount.toLocaleString();

                            // Re-initialize Metronic menus & dropdowns
                            if (typeof KTMenu !== 'undefined' && KTMenu.createInstances) {
                                KTMenu.createInstances();
                            }
                            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                                document.querySelectorAll('#userListTbody [data-bs-toggle="tooltip"]').forEach(
                                    el => {
                                        new bootstrap.Tooltip(el);
                                    });
                            }

                            // Re-observe target for next scroll batch
                            if (observer && btn) {
                                observer.observe(btn);
                            }

                            // Check if more rows needed to fill viewport
                            setTimeout(checkScrollTrigger, 300);
                        } else {
                            hasMore = false;
                        }

                        if (!hasMore) {
                            if (btn) btn.classList.add('d-none');
                            const badge = document.getElementById('userAllLoadedBadge');
                            if (badge) badge.classList.remove('d-none');
                        }
                    })
                    .catch(err => {
                        console.error('Error loading more users:', err);
                    })
                    .finally(() => {
                        isLoading = false;
                        if (btn) btn.disabled = false;
                        if (btnSpinner) {
                            btnSpinner.classList.remove('d-inline-flex');
                            btnSpinner.classList.add('d-none');
                        }
                        if (btnText) {
                            btnText.classList.remove('d-none');
                            btnText.classList.add('d-inline-flex');
                        }
                    });
            };

            // Automatic loading when scrolling down via IntersectionObserver
            let observer = null;

            function setupObserver() {
                if ('IntersectionObserver' in window) {
                    if (observer) observer.disconnect();
                    observer = new IntersectionObserver(function(entries) {
                        entries.forEach(function(entry) {
                            if (entry.isIntersecting && !isLoading && hasMore) {
                                window.loadMoreUsers();
                            }
                        });
                    }, {
                        root: null,
                        rootMargin: '600px 0px 600px 0px',
                        threshold: 0
                    });

                    const target = document.getElementById('btnLoadMoreUsers') || document.getElementById(
                        'userLoadMoreSection');
                    if (target) observer.observe(target);
                }
            }
            setupObserver();

            // Secondary fallback on window & container scroll
            let scrollTimer = null;

            function checkScrollTrigger() {
                if (isLoading || !hasMore) return;
                const target = document.getElementById('btnLoadMoreUsers') || document.getElementById(
                    'userLoadMoreSection');
                if (!target || target.classList.contains('d-none')) return;

                const rect = target.getBoundingClientRect();
                const windowHeight = window.innerHeight || document.documentElement.clientHeight;

                // Trigger when within 600px of bottom
                if (rect.top <= windowHeight + 600) {
                    window.loadMoreUsers();
                }
            }

            // Capture scroll on window, document, and any container
            window.addEventListener('scroll', function() {
                if (scrollTimer) return;
                scrollTimer = setTimeout(function() {
                    scrollTimer = null;
                    checkScrollTrigger();
                }, 60);
            }, true);

            window.addEventListener('wheel', function() {
                if (scrollTimer) return;
                scrollTimer = setTimeout(function() {
                    scrollTimer = null;
                    checkScrollTrigger();
                }, 60);
            }, {
                passive: true,
                capture: true
            });

            // Initial check after page render
            setTimeout(checkScrollTrigger, 400);
        })();

        @if ($canEditReferral)
        window.__refExecutivesList = null;
        window.__refExecutivesPromise = null;

        window.loadReferralExecutivesList = function() {
            if (Array.isArray(window.__refExecutivesList)) {
                return Promise.resolve(window.__refExecutivesList);
            }
            if (!window.__refExecutivesPromise) {
                window.__refExecutivesPromise = fetch("{{ route('user.referral.executives') }}", {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.ok ? res.json() : { success: false, data: [] })
                .then(json => {
                    window.__refExecutivesList = Array.isArray(json.data) ? json.data : [];
                    return window.__refExecutivesList;
                })
                .catch(() => {
                    window.__refExecutivesPromise = null;
                    return [];
                });
            }
            return window.__refExecutivesPromise;
        };

        window.populateReferralExecutiveSelect = function($select, selectedId, fallbackLabel) {
            if (!$select || !$select.length) return;
            const targetId = selectedId !== undefined && selectedId !== null ? String(selectedId) : '';
            $select.attr('data-selected-executive', targetId);

            if (targetId && !$select.find('option[value="' + targetId.replace(/"/g, '\\"') + '"]').length) {
                $select.append($('<option>', {
                    value: targetId,
                    text: fallbackLabel || ('Executive #' + targetId)
                }));
            }
            $select.val(targetId);

            window.loadReferralExecutivesList().then(list => {
                if (!Array.isArray(list) || !list.length) return;
                const currentTarget = String($select.attr('data-selected-executive') || '');
                const preservedFallbackText = currentTarget
                    ? ($select.find('option[value="' + currentTarget.replace(/"/g, '\\"') + '"]').text() || ('Executive #' + currentTarget))
                    : '';

                $select.empty();
                $select.append($('<option>', { value: '', text: 'System / Default (AIN)' }));

                let matchedTarget = false;
                list.forEach(exec => {
                    const idStr = String(exec.id);
                    if (idStr === currentTarget) matchedTarget = true;
                    const label = exec.name + (exec.referral_prefix ? ' (' + exec.referral_prefix + ')' : '');
                    $select.append($('<option>', { value: idStr, text: label }));
                });

                if (currentTarget && !matchedTarget) {
                    $select.append($('<option>', { value: currentTarget, text: preservedFallbackText }));
                }
                $select.val(currentTarget);
            });
        };

        $(document).on('show.bs.modal', '.modal', function() {
            const $execSelect = $(this).find('.js-referral-exec-select');
            if ($execSelect.length && $execSelect.attr('id') !== 'refModalExecutiveId') {
                const selectedId = $execSelect.attr('data-selected-executive') || $execSelect.val() || '';
                window.populateReferralExecutiveSelect($execSelect, selectedId);
            }
        });

        window.openEditReferralModal = function(btn) {
            const $btn = $(btn);
            const userId = $btn.data('user-id');
            const userName = $btn.data('user-name') || '';
            const roleId = parseInt($btn.data('role-id'), 10) || 2;
            const referralCode = $btn.data('referral-code') || '';
            const discountPercent = $btn.data('discount-percent') !== undefined && $btn.data('discount-percent') !== '' ? $btn.data('discount-percent') : '10.00';
            const execId = $btn.data('executive-id') || '';
            const execName = $btn.data('executive-name') || '';
            const prefix = $btn.data('prefix') || '';

            $('#refModalUserId').val(userId);
            $('#refModalUserName').text(userName);
            $('#refModalAlert').addClass('d-none').removeClass('alert-danger alert-success').text('');

            const isStaffExec = [1, 4, 9].includes(roleId);
            if (isStaffExec) {
                $('#refClientFieldsWrapper').addClass('d-none');
                $('#refExecutiveFieldsWrapper').removeClass('d-none');
                $('#refModalPrefix').val(prefix);
            } else {
                $('#refExecutiveFieldsWrapper').addClass('d-none');
                $('#refClientFieldsWrapper').removeClass('d-none');
                $('#refModalCode').val(referralCode);
                $('#refModalDiscount').val(discountPercent);
                const fallbackExecLabel = execName ? (execName + (prefix ? ' (' + prefix + ')' : '')) : '';
                window.populateReferralExecutiveSelect($('#refModalExecutiveId'), execId, fallbackExecLabel);
            }

            const modalEl = document.getElementById('editReferralModal');
            if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        };

        $('#editReferralForm').on('submit', function(e) {
            e.preventDefault();
            const userId = $('#refModalUserId').val();
            const isExecMode = !$('#refExecutiveFieldsWrapper').hasClass('d-none');
            const $btn = $('#refModalSubmitBtn');
            const $alert = $('#refModalAlert');
            $btn.prop('disabled', true).text('Saving...');
            $alert.addClass('d-none').removeClass('alert-danger alert-success');

            const payload = isExecMode
                ? { referral_prefix: $('#refModalPrefix').val().trim().toUpperCase() }
                : {
                    referral_code: $('#refModalCode').val().trim().toUpperCase(),
                    referral_discount_percent: $('#refModalDiscount').val(),
                    referral_executive_id: $('#refModalExecutiveId').val()
                };

            fetch("{{ url('/user') }}/" + userId + "/referral", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || ''
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                const json = await res.json().catch(() => ({}));
                if (!res.ok || !json.success) {
                    throw new Error(json.message || 'Unable to update referral settings.');
                }
                return json;
            })
            .then(json => {
                const d = json.data || {};
                const cell = document.querySelector('[data-user-referral-cell="' + userId + '"]');
                if (cell) {
                    const esc = (v) => $('<div>').text(v || '').html();
                    const escapedName = esc($('#refModalUserName').text());
                    const resolvedRoleId = parseInt(d.role_id, 10) || (isExecMode ? 4 : 2);
                    const safeCode = esc(d.referral_code || '');
                    const safePrefix = esc(d.referral_prefix || '');

                    if (isExecMode) {
                        const p = safePrefix || 'Auto';
                        cell.innerHTML = '<div class="d-flex align-items-center gap-1">' +
                            '<span class="badge badge-light-dark fw-bolder fs-8 font-monospace">Prefix: ' + p + '</span>' +
                            '<button type="button" class="btn btn-icon btn-sm btn-light-warning p-1 js-edit-referral-btn" ' +
                            'data-user-id="' + userId + '" data-user-name="' + escapedName + '" ' +
                            'data-role-id="' + resolvedRoleId + '" data-prefix="' + safePrefix + '" ' +
                            'onclick="event.stopPropagation(); window.openEditReferralModal(this);" title="Edit Referral Prefix"><i class="fa fa-edit fs-8"></i></button>' +
                            '</div>';
                    } else {
                        const codeHtml = safeCode
                            ? '<span class="badge badge-light-primary fw-bolder fs-7 font-monospace">' + safeCode + '</span>' +
                              '<button type="button" class="btn btn-icon btn-sm p-0 border-0 bg-transparent ms-1 copy-contact-btn" title="Copy Referral Code" onclick="event.stopPropagation(); window.copyContactText ? window.copyContactText(\'' + safeCode + '\', this) : navigator.clipboard.writeText(\'' + safeCode + '\'); return false;"><i class="fa fa-copy text-muted fs-8"></i></button>' +
                              '<span class="badge badge-light-success fw-bold fs-8 ms-1">' + esc(d.referral_discount_display || '10') + '% OFF</span>'
                            : '<span class="text-muted fs-8">Not generated</span>';
                        const execLine = safeCode
                            ? '<div class="text-muted fs-8 mt-1">Exec: ' + esc(d.referral_executive_name || 'System') +
                              (safePrefix ? ' (' + safePrefix + ')' : '') + '</div>'
                            : '';
                        cell.innerHTML = '<div class="d-flex align-items-center flex-wrap gap-1">' + codeHtml +
                            '<button type="button" class="btn btn-icon btn-sm btn-light-warning p-1 ms-1 js-edit-referral-btn" ' +
                            'data-user-id="' + userId + '" data-user-name="' + escapedName + '" ' +
                            'data-role-id="' + resolvedRoleId + '" data-referral-code="' + safeCode + '" ' +
                            'data-discount-percent="' + esc(d.referral_discount_percent || '10.00') + '" ' +
                            'data-executive-id="' + esc(d.referral_executive_id || '') + '" ' +
                            'data-executive-name="' + esc(d.referral_executive_name || '') + '" ' +
                            'data-prefix="' + safePrefix + '" ' +
                            'onclick="event.stopPropagation(); window.openEditReferralModal(this);" title="Edit Referral Settings"><i class="fa fa-edit fs-8"></i></button>' +
                            '</div>' + execLine;
                    }
                }
                const modalEl = document.getElementById('editReferralModal');
                if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                }
            })
            .catch(err => {
                $alert.removeClass('d-none').addClass('alert-danger').text(err.message || 'Error saving referral settings.');
            })
            .finally(() => {
                $btn.prop('disabled', false).text('Save Changes');
            });
        });
        @endif
    </script>
@endpush
