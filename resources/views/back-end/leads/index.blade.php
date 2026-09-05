@extends('layouts.app')

@section('content')
@include('back-end.group-master.user-modal')
<div class="margin-top-on-desktop" id="kt_content">
    <script>
        if (localStorage.getItem('lead_filters')) {
            document.documentElement.classList.add('lead-filter-restoring');
        } else {
            document.documentElement.classList.remove('lead-filter-restoring');
        }
    </script>
    <div class="col-xl-12">
        <!-- Filter Card -->
        <div class="card card-xxl-stretch mb-5 mb-xl-8 lead-filter-card">
            <div class="card-body py-3">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" id="search_order" class="form-control form-control-solid" placeholder="Search by Order ID / Title">
                        <input type="hidden" id="lead_status_tab" name="lead_status_tab">
                    </div>
                    <div class="col-md-3">
                        <select id="status_filter" class="form-select form-select-solid">
                            <option value="">Select Status</option>
                            <option value="Quote">Quote</option>
                            <option value="Waiting">Waiting</option>
                            <option value="Confirmation">Confirmation</option>
                            <option value="Price">Price</option>
                            <option value="Quality">Quality</option>
                            <option value="Customer Service">Customer Service</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="type_filter" class="form-select form-select-solid">
                            <option value="">Search Tech / Resit / First Class</option>
                            <option value="First">First Class Work</option>
                            <option value="Resit">Resit</option>
                            <option value="Technical">Technical</option>
                        </select>
                    </div>
                    <div class="col-md-3 fv-row">
                        <input type="text" list="searchDatalist" id="searchInput" name="user" class="form-control form-control-solid" placeholder="Search..." autocomplete="off">
                        <!-- Datalist for displaying search results -->
                        <datalist id="searchDatalist"></datalist>
                        <!-- Container to display search results -->
                        <div id="searchResultss"></div>
                        <!-- Hidden field to store the selected value -->
                        <input type="hidden" id="selectedValue" name="uid">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <input type="date" id="date_from" class="form-control form-control-solid">
                    </div>
                    <div class="col-md-3">
                        <input type="date" id="date_to" class="form-control form-control-solid">
                    </div>
                    <div class="col-md-3">
                        <select id="date_type" class="form-select form-select-solid">
                            <option value="">Date Type</option>
                            <option value="deadline">Deadline</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="assign_type" id="assign_type" class="form-select form-select-solid">
                            <option value="">Assign Type</option>
                            <option value="0" {{ request('assign_type') === '0' ? 'selected' : '' }}>AIN</option>
                            <option value="1" {{ request('assign_type') === '1' ? 'selected' : '' }}>Let's Lern</option>
                        </select>

                    </div>
                    <div class="col-md-3 mt-2">
                        <select name="lead_source" id="lead_source" class="form-select form-select-solid">

                            <option value="">All Sources</option>

                            @foreach($sources as $source)
                            <option value="{{ $source->id }}"
                                {{ request('lead_source') == 'source_'.$source->id ? 'selected' : '' }}>

                                {{ $source->source_name }}
                            </option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-md-3 mt-2"><select id="lead_group_id" class="form-select form-select-solid"><option value="">All User Groups</option>@foreach(\App\Models\GroupMaster::where('status',1)->orderBy('name')->get(['id','name']) as $group)<option value="{{ $group->id }}">{{ $group->name }}</option>@endforeach</select></div>
                    <div class="col-md-3 mt-2">
                        {{-- <a href="/ain-backend/lead" class="btn btn-sm btn-light">Clear Filters</a> --}}
                        <a href="{{ route('lead.index') }}" class="btn btn-sm btn-light" onclick="localStorage.removeItem('lead_filters')">
                            Clear Filters
                        </a>
                        <button type="button" id="applyButton" class="btn btn-sm btn-primary">Search</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Leads Table -->
        <div class="card card-xl-stretch mb-5 lead-list-card">
            <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title fw-bolder fs-3 mb-1">Active Leads</h3>
                    <span class="text-muted mt-1 fw-bold fs-7">Live data with real-time update</span>
                </div>
                <div class="card-toolbar d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_appaa_newLeads">
                        <span class="svg-icon svg-icon-2">+</span> Add New Lead
                    </button>

                    <button class="btn btn-sm btn-light-info" data-bs-toggle="modal" data-bs-target="#kt_modal_create_next_lead">
                        <span class="svg-icon svg-icon-2">+</span> Next Lead
                    </button>

                    <button class="btn btn-sm btn-info position-relative" onclick="openNextLeadsModal()">
                        <i class="fa fa-calendar-alt me-1 text-white"></i> Current Month Lead
                        <span id="next_lead_current_month_badge" class="badge badge-circle badge-danger ms-1" style="font-size: 11px;">
                            {{ $nextLeadCount ?? 0 }}
                        </span>
                    </button>

                    @if( auth()->user()->role_id == 1)
                    <button class="btn btn-sm btn-danger" style="margin-left: 10px; display: none;" id="export-btn">
                        Export
                    </button>
                    @endif
                </div>
            </div>
            <div class="card-body pb-0">
                <ul class="nav lead-status-tabs flex-nowrap overflow-auto">
                    @foreach($status_counts as $name => $count)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                            href="javascript:void(0)"
                            data-status="{{ $name }}"
                            onclick="filterByStatusTab('{{ $name }}', this)">
                            {{ $name }}
                            <span class="lead-tab-count">{{ $count }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="leads-table">
                        <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-50px text-center" style="padding-right: 0px; background: #F5F8FA;">Sr.</th>
                                <th class="min-w-165px text-center" style="background: #F5F8FA;">Action</th>
                                <th class="min-w-220px text-center" style="background: #F5F8FA;">Recent Chat</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Order ID</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Name</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Order Date</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Project Title</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Words</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Price</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Due</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Delivery Date</th>
                            </tr>
                        </thead>
                        <tbody id="lead-rows">
                            @if(count($leads))
                                @foreach ($leads as $index => $lead)
                                    @include('back-end.leads.partials.row', ['lead' => $lead])
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-5">
                                        <i class="fa fa-folder-open-o fs-3 text-gray-400 d-block mb-2"></i>
                                        No leads found matching your criteria.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div id="load-more-wrapper" class="text-center mt-4" @if(($status_counts['All'] ?? 0) <= count($leads)) style="display:none;" @endif>
                        <button id="load-more" class="btn btn-light-primary">Load More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('back-end.leads.partials.create')
    @include('back-end.leads.partials.create-next-lead')
    @include('back-end.leads.partials.next-leads-list-modal', ['nextLeads' => collect(), 'creators' => $employees ?? collect()])
</div>
@include('back-end.leads.partials.preloader')
@include('back-end.leads.partials.models', ['lead' => $lead])

@push('scripts')
    @include('back-end.leads.ajax')
    <script>
        // Next Lead Auto-fill & Multiple User Autocomplete List
        let nextLeadMobileTimer = null;
        const $nextLeadMobileResult = $('#next_lead_mobile_user_result');
        const $nextLeadMobileLoader = $('#next_lead_lookup_loader');

        function fillNextLeadUser(user) {
            if (!user) return;
            $('#next_lead_user_name').val(user.name || '');
            if (user.email) $('#next_lead_email').val(user.email);
            if (user.countrycode) $('#next_lead_countrycode').val(user.countrycode);
            if (user.mobile_no) $('#next_lead_mobile').val(user.mobile_no);
            $nextLeadMobileResult.hide().html('');
            $('#next_lead_user_status').html('<span class="text-success fw-bold"><i class="fa fa-check-circle me-1"></i> Customer selected: ' + (user.name || '') + '</span>');
        }

        function renderNextLeadMobileDropdown(users) {
            if (!Array.isArray(users) || users.length === 0) {
                $nextLeadMobileResult.hide().html('');
                $('#next_lead_user_status').html('<span class="text-muted"><i class="fa fa-info-circle me-1"></i> New Customer (Enter details manually)</span>');
                return;
            }

            let html = '';
            users.forEach(function (user) {
                let jsonUser = $('<div>').text(JSON.stringify(user)).html();
                html += `
                    <div class="next-lead-user-item px-3 py-2 border-bottom text-start"
                        style="cursor:pointer; background-color: #fff;"
                        onmouseover="this.style.backgroundColor='#f1f5f9'"
                        onmouseout="this.style.backgroundColor='#fff'"
                        data-user='${jsonUser}'>
                        <strong class="text-gray-800 fs-7">${$('<div>').text(user.name || 'No Name').html()}</strong><br>
                        <small class="text-muted fs-8">${$('<div>').text((user.countrycode || '') + ' ' + (user.mobile_no || '') + ' | ' + (user.email || '')).html()}</small>
                    </div>
                `;
            });

            $nextLeadMobileResult.html(html).show();
            $('#next_lead_user_status').html('<span class="text-primary fw-bold"><i class="fa fa-list me-1"></i> ' + users.length + ' customer(s) found. Click one to select:</span>');
        }

        $('#next_lead_mobile').on('keyup input', function () {
            let mobile = $(this).val().trim();
            clearTimeout(nextLeadMobileTimer);

            if (mobile.length < 5) {
                $nextLeadMobileLoader.hide();
                $nextLeadMobileResult.hide().html('');
                $('#next_lead_user_status').html('');
                return;
            }

            $nextLeadMobileLoader.show();

            nextLeadMobileTimer = setTimeout(function () {
                $.ajax({
                    url: `{{ url('/search-user') }}`,
                    method: 'GET',
                    data: { user: mobile, query: mobile, term: mobile },
                    success: function (response) {
                        $nextLeadMobileLoader.hide();
                        renderNextLeadMobileDropdown(response);
                    },
                    error: function () {
                        $nextLeadMobileLoader.hide();
                        $nextLeadMobileResult.hide().html('');
                    }
                });
            }, 300);
        });

        $(document).on('click', '.next-lead-user-item', function () {
            let user = $(this).data('user');
            if (typeof user === 'string') {
                try { user = JSON.parse(user); } catch (e) {}
            }
            fillNextLeadUser(user);
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#next_lead_mobile, #next_lead_mobile_user_result').length) {
                $nextLeadMobileResult.hide();
            }
        });

        // Submit Next Lead Form
        $('#createNextLeadForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let submitBtn = $('#btnSubmitNextLead');
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(res) {
                    submitBtn.prop('disabled', false).html('Save Next Lead');
                    if (res.success) {
                        Swal.fire('Success!', res.message, 'success');
                        $('#kt_modal_create_next_lead').modal('hide');
                        form[0].reset();
                        $('#next_lead_user_status').html('');
                        if (typeof res.count !== 'undefined') {
                            $('#next_lead_current_month_badge').text(res.count);
                        }
                    } else {
                        Swal.fire('Error!', res.message || 'Failed to save Next Lead.', 'error');
                    }
                },
                error: function(err) {
                    submitBtn.prop('disabled', false).html('Save Next Lead');
                    Swal.fire('Error!', 'Server error while saving Next Lead.', 'error');
                }
            });
        });

        // Open Next Leads List Modal
        function openNextLeadsModal() {
            filterNextLeadsList();
            $('#kt_modal_next_leads_list').modal('show');
        }

        // Filter Next Leads List
        function filterNextLeadsList() {
            let month = $('#filter_next_lead_month').val() || 'all';
            let search = $('#filter_next_lead_user_search').val() || '';

            $('#next-leads-table-body').html('<tr><td colspan="8" class="text-center py-5"><i class="fa fa-spinner fa-spin text-primary fs-3"></i> Loading...</td></tr>');

            $.ajax({
                url: `{{ route('nextlead.list') }}`,
                method: 'GET',
                data: {
                    target_month: month,
                    search: search,
                    render_table_only: 1
                },
                success: function(html) {
                    $('#next-leads-table-body').html(html);
                },
                error: function() {
                    $('#next-leads-table-body').html('<tr><td colspan="8" class="text-center text-danger py-4">Failed to load Next Leads data.</td></tr>');
                }
            });
        }

        // Reset Next Lead Filters
        function resetNextLeadFilters() {
            $('#filter_next_lead_month').val('{{ date("Y-m") }}');
            $('#filter_next_lead_user_search').val('');
            filterNextLeadsList();
        }

        // Convert Next Lead to Active Lead
        function convertNextLead(id, btn) {
            Swal.fire({
                title: 'Convert to Active Lead?',
                text: 'This will generate an Order Code and move this Next Lead into Active Leads!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Convert!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let btnObj = $(btn);
                    btnObj.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Converting...');

                    $.ajax({
                        url: `{{ url('/lead/next-lead/convert') }}/${id}`,
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    title: 'Converted!',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                btnObj.prop('disabled', false).html('<i class="fa fa-arrow-right fs-9 me-1 text-white"></i> Convert to Active Lead');
                                Swal.fire('Error!', res.message || 'Conversion failed.', 'error');
                            }
                        },
                        error: function() {
                            btnObj.prop('disabled', false).html('<i class="fa fa-arrow-right fs-9 me-1 text-white"></i> Convert to Active Lead');
                            Swal.fire('Error!', 'Server error during conversion.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush



<style>
    .margin-top-on-desktop {
        margin-top: -60px;
    }

    .lead-filter-card,
    .lead-list-card {
        border: 1px solid #e4e6ef;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    }

    .lead-filter-card .form-control,
    .lead-filter-card .form-select {
        border: 1px solid #dbe2ea;
        background-color: #fff;
        min-height: 42px;
    }

    .lead-list-card .card-header {
        border-bottom: 1px solid #eef1f5 !important;
        padding-bottom: 1.25rem;
    }

    .lead-status-tabs {
        gap: 8px;
        padding-bottom: 4px;
    }

    .lead-status-tabs .nav-link {
        align-items: center;
        border: 1px solid #dbe2ea;
        border-radius: 8px;
        color: #4b5563;
        display: inline-flex;
        font-size: 13px;
        font-weight: 700;
        gap: 8px;
        line-height: 1;
        margin: 0;
        min-height: 38px;
        padding: 10px 12px;
        transition: all .18s ease;
        white-space: nowrap;
    }

    .lead-status-tabs .nav-link:hover {
        border-color: #009ef7;
        color: #009ef7;
    }

    .lead-status-tabs .nav-link.active {
        background: #009ef7;
        border-color: #009ef7;
        box-shadow: 0 6px 14px rgba(0, 158, 247, .22);
        color: #fff !important;
    }

    .lead-tab-count {
        align-items: center;
        background: #f1f5f9;
        border-radius: 999px;
        color: #334155;
        display: inline-flex;
        font-size: 11px;
        height: 22px;
        justify-content: center;
        min-width: 28px;
        padding: 0 8px;
    }

    .lead-status-tabs .nav-link.active .lead-tab-count {
        background: rgba(255,255,255,.22);
        color: #fff;
    }

    #leads-table {
        border-color: #e5e7eb;
    }

    #leads-table thead tr {
        border-bottom: 1px solid #dbe2ea;
    }

    #leads-table thead th {
        color: #475569 !important;
        font-size: 12px;
        letter-spacing: 0;
        text-transform: uppercase;
        vertical-align: middle;
    }

    #leads-table tbody tr:hover {
        background: #f8fafc;
    }

    @media (max-width: 992px) {
        .margin-top-on-desktop {
            margin-top: 0px;
        }
    }
</style>

<script>
    const leadExportBtn = document.getElementById("export-btn");
    if (leadExportBtn) {
    leadExportBtn.addEventListener("click", function() {
        $('#export-btn').hide();
        Swal.fire({
            title: 'Choose Export Option',
            icon: 'question',
            showConfirmButton: true,
            confirmButtonText: 'Export All',
            showDenyButton: true,
            denyButtonText: 'Custom Export',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
        }).then((result) => {
            const filters = {
                order: document.getElementById("search_order")?.value ?? "",
                status: document.getElementById("status_filter")?.value ?? "",
                type: document.getElementById("type_filter")?.value ?? "",
                selectedValue: document.getElementById("selectedValue")?.value ?? "",
                date_from: document.getElementById("date_from")?.value ?? "",
                date_to: document.getElementById("date_to")?.value ?? "",
                date_type: document.getElementById("date_type")?.value ?? "",
            };

            if (result.isConfirmed) {
                // Export All
                sendExport(filters);
            } else if (result.isDenied) {
                // Custom Export
                Swal.fire({
                    title: 'Select Columns to Export',
                    html: `
                            <div style="text-align: left;">
                                <label><input type="checkbox" value="order_id" class="export-column" > Order ID</label><br>
                                <label><input type="checkbox" value="name" class="export-column" > Name</label><br>
                                <label><input type="checkbox" value="email" class="export-column" > Email</label><br>
                                <label><input type="checkbox" value="customer_country_code" class="export-column" > Country Code</label><br>
                                <label><input type="checkbox" value="customer_phone" class="export-column" > Phone</label><br>
                                <label><input type="checkbox" value="order_date" class="export-column" > Order Date</label><br>
                                <label><input type="checkbox" value="project_title" class="export-column" > Project Title</label><br>
                                <label><input type="checkbox" value="pages" class="export-column" > Words</label><br>
                                <label><input type="checkbox" value="price" class="export-column" > Price</label><br>
                                <label><input type="checkbox" value="deadline" class="export-column" > Delivery Date</label>
                            </div>
                        `,
                    confirmButtonText: 'Export Selected',
                    showCancelButton: true,
                    preConfirm: () => {
                        const selected = Array.from(document.querySelectorAll(".export-column:checked")).map(el => el.value);
                        if (selected.length === 0) {
                            Swal.showValidationMessage("Please select at least one column");
                        }
                        return selected;
                    }
                }).then((colRes) => {
                    if (colRes.isConfirmed) {
                        sendExport({
                            ...filters,
                            selected_columns: colRes.value
                        });
                    }
                });
            }
        });

        function sendExport(payload) {
            fetch("/lead/export", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(payload),
                })
                .then(res => res.json())
                .then(() => {
                    localStorage.setItem("leadExportStatus", "pending");
                    Swal.fire({
                        title: 'Export started!',
                        text: 'Your file will be ready shortly.',
                        icon: 'info',
                        timer: 2000,
                        showConfirmButton: false
        });
    });
    }
        }

    });
</script>
@endsection
