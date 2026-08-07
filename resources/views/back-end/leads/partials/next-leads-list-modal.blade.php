<!-- Next Leads Management List Modal -->
<div class="modal fade" id="kt_modal_next_leads_list" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-1000px">
        <div class="modal-content rounded shadow">
            <div class="modal-header border-0 pb-0 justify-content-between">
                <div>
                    <h3 class="fw-bolder fs-3 mb-1">Next Leads Management</h3>
                    <span class="text-muted fs-7">Targeted future month orders and callback list</span>
                </div>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="fa fa-times fs-4"></i>
                </div>
            </div>

            <div class="modal-body px-8 py-6">
                <!-- Filters Bar (Without Upper Labels, Clean Placeholders) -->
                <div class="row g-2 align-items-center mb-5 bg-light p-3 rounded border">
                    <!-- Month Filter -->
                    <div class="col-md-5">
                        <input type="month" id="filter_next_lead_month" class="form-control form-control-sm next-lead-filter-field" value="{{ date('Y-m') }}" title="Filter by Target Month" onchange="filterNextLeadsList()">
                    </div>

                    <!-- Search User / Creator Field -->
                    <div class="col-md-5 position-relative">
                        <div class="position-relative">
                            <input type="text" id="filter_next_lead_user_search" class="form-control form-control-sm next-lead-filter-field pe-8" placeholder="Search Customer / Creator (Name, Mobile, Email)..." onkeyup="filterNextLeadsList()">
                            <i class="fa fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted fs-8"></i>
                        </div>
                    </div>

                    <!-- Reset Filter Button -->
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-sm btn-light-secondary w-100 fw-bold border" onclick="resetNextLeadFilters()">
                            <i class="fa fa-refresh fs-8 me-1"></i> Reset
                        </button>
                    </div>
                </div>

                <style>
                    .next-lead-filter-field {
                        border: 1.5px solid #cbd5e1 !important;
                        border-radius: 6px !important;
                        background-color: #f8fafc !important;
                        color: #1e293b !important;
                        font-weight: 500;
                        transition: all 0.2s ease-in-out;
                    }
                    .next-lead-filter-field:focus, .next-lead-filter-field:hover {
                        border-color: #009ef7 !important;
                        background-color: #ffffff !important;
                        box-shadow: 0 0 0 3px rgba(0, 158, 247, 0.15) !important;
                    }
                </style>

                <!-- Next Leads Table -->
                <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-bordered table-hover align-middle m-0">
                        <thead class="bg-light sticky-top" style="top: 0; z-index: 10;">
                            <tr class="fw-bolder text-muted fs-8 text-uppercase">
                                <th class="text-center min-w-40px">Sr.</th>
                                <th class="text-center min-w-160px">Customer</th>
                                <th class="text-center min-w-130px">Mobile</th>
                                <th class="text-center min-w-130px">Target Month</th>
                                <th class="text-center min-w-120px">Created By</th>
                                <th class="text-center min-w-180px">Notes</th>
                                <th class="text-center min-w-100px">Date Added</th>
                                <th class="text-center min-w-150px">Action</th>
                            </tr>
                        </thead>
                        <tbody id="next-leads-table-body">
                            @include('back-end.leads.partials.next-leads-table-rows', ['nextLeads' => $nextLeads])
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
