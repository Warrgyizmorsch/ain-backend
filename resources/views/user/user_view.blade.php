@extends('layouts.app')
@section('content')
    @include('back-end.group-master.user-modal')
    <style>
        main.content {
            padding-top: 0 !important;
        }

        .user-tabs {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
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

        /* Role (5) and Join Date (6): left aligned like other columns */
        .user-list-table thead th:nth-child(5),
        .user-list-table thead th:nth-child(6),
        .user-list-table tbody td:nth-child(5),
        .user-list-table tbody td:nth-child(6) {
            text-align: left !important;
        }

        .user-list-table tbody td:nth-child(5) .flex-stack,
        .user-list-table tbody td:nth-child(6) .flex-stack {
            justify-content: flex-start !important;
        }

        /* Order Count (7) and Follow-ups (8): centered under their titles */
        .user-list-table thead th:nth-child(7),
        .user-list-table thead th:nth-child(8),
        .user-list-table tbody td:nth-child(7),
        .user-list-table tbody td:nth-child(8) {
            text-align: center !important;
        }

        /* Actions (9): right aligned */
        .user-list-table thead th:nth-child(9),
        .user-list-table tbody td:nth-child(9) {
            text-align: right !important;
        }
    </style>
    @php
        $hasActiveFilters =
            request()->filled('user') ||
            request()->filled('user_id') ||
            request()->filled('uid') ||
            request()->filled('start_date') ||
            request()->filled('end_date') ||
            request()->filled('role') ||
            request()->filled('countrycode') ||
            request()->filled('order_category') ||
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

                            <div class="row mb-3">
                                <!-- User ID filter -->
                                <div class="col-lg-3 fv-row fv-plugins-icon-container position-relative">
                                    <input type="text" id="searchInput" name="user"
                                        class="form-control form-control-solid" placeholder="User-Name,Number,Email"
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

                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="order_category" class="form-select form-select-solid"
                                        data-control="select2" data-placeholder="Filter By Order Category">
                                        <option value="">All Categories</option>
                                        <option value="beginner"
                                            {{ request('order_category') == 'beginner' ? 'selected' : '' }}>Beginners
                                        </option>
                                        <option value="Retainer"
                                            {{ request('order_category') == 'Retainer' ? 'selected' : '' }}>Retainer
                                        </option>
                                        <option value="loyal"
                                            {{ request('order_category') == 'loyal' ? 'selected' : '' }}>Loyal Customers
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

                                <a onclick="exportUsers()" style="height: fit-content;"
                                    class="btn btn-sm btn-danger">Export</a>
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
                                            <th class="min-w-80px">Role</th>
                                            <th class="min-w-190px">Join Date</th>
                                            <th class="min-w-100px">Order Count<br><small>(Last 1.5 Yrs)</small></th>
                                            <th class="min-w-100px">Follow-ups<br><small>(Last 1 Yr)</small></th>
                                            <th class="min-w-90px">Actions</th>
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
            // Retrieve filter parameters
            var userId = $('#selectedValue').val() || $('input[name="user_id"]').val();
            var startDate = $('input[name="start_date"]').val();
            var endDate = $('input[name="end_date"]').val();
            var roleId = $('select[name="role"]').val();
            var countrycode = $('select[name="countrycode"]').val();

            // Use CSRF token for security
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            var requestData = {
                _token: csrfToken,
                user_id: userId,
                start_date: startDate,
                end_date: endDate,
                role: roleId,
                countrycode: countrycode
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
    </script>
@endpush
