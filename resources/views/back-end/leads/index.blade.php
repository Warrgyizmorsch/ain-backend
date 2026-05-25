@extends('layouts.app')

@section('content')
<div class="margin-top-on-desktop" id="kt_content">
    <div class="col-xl-12">
        <!-- Filter Card -->
        <div class="card card-xxl-stretch mb-5 mb-xl-8">
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
                            <option value="Deadline">Deadline</option>
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
                    <div class="col-md-3 mt-2">
                        {{-- <a href="/1008/lead" class="btn btn-sm btn-light">Clear Filters</a> --}}
                        <a href="/lead" class="btn btn-sm btn-light" onclick="localStorage.removeItem('lead_filters')">
                            Clear Filters
                        </a>
                        <button id="applyButton" class="btn btn-sm btn-primary">Search</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Leads Table -->
        <div class="card card-xl-stretch mb-5">
            <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title fw-bolder fs-3 mb-1">Active Leads</h3>
                    <span class="text-muted mt-1 fw-bold fs-7">Live data with real-time update</span>
                </div>
                <div class="card-toolbar">
                    <button class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_appaa_newLeads">
                        <span class="svg-icon svg-icon-2">+</span> Add New Lead
                    </button>
                    @if( auth()->user()->role_id == 1)
                    <button class="btn btn-sm btn-danger" style="margin-left: 10px; display: none;" id="export-btn">
                        Export
                    </button>
                    @endif
                </div>
            </div>
            <div class="card-body pb-0">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap overflow-auto">
                    @foreach($status_counts as $name => $count)
                    <li class="nav-item">
                        <a class="nav-link text-active-primary me-6 {{ $loop->first ? 'active' : '' }}"
                            href="javascript:void(0)"
                            onclick="filterByStatusTab('{{ $name }}', this)">
                            {{ $name }}
                            <span class="badge badge-light-primary ms-2">{{ $count }}</span>
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
                                <th class="text-center">Action</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Comment</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Order ID</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Name</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Order Date</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Project Title</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Words</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Price</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Delivery Date</th>
                            </tr>
                        </thead>
                        <tbody id="lead-rows">
                            @foreach ($leads as $index => $lead)
                            @include('back-end.leads.partials.row', ['lead' => $lead])
                            @endforeach
                        </tbody>
                    </table>
                    <div id="load-more-wrapper" class="text-center mt-4">
                        <button id="load-more" class="btn btn-light-primary">Load More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('back-end.leads.partials.create')
</div>
@include('back-end.leads.partials.preloader')
@include('back-end.leads.ajax')
@include('back-end.leads.partials.models', ['lead' => $lead])



<style>
    .margin-top-on-desktop {
        margin-top: -60px;
    }

    @media (max-width: 992px) {
        .margin-top-on-desktop {
            margin-top: 0px;
        }
    }
</style>

<script>
    document.getElementById("export-btn").addEventListener("click", function() {
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

    });
</script>
@endsection