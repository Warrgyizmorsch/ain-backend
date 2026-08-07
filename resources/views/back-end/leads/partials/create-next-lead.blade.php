<!-- Create Next Lead Modal -->
<div class="modal fade" id="kt_modal_create_next_lead" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded shadow">
            <div class="modal-header border-0 pb-0 justify-content-between">
                <h3 class="fw-bolder fs-3 mb-0">Create Next Lead (Targeted Month)</h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="fa fa-times fs-4"></i>
                </div>
            </div>

            <div class="modal-body scroll-y px-10 pt-4 pb-8">
                <form id="createNextLeadForm" method="POST" action="{{ route('nextlead.store') }}">
                    @csrf
                    <div class="row g-4 mb-4">
                        <!-- Country Code -->
                        <div class="col-md-4">
                            <label class="form-label fs-7 fw-bold required">Country Code</label>
                            <input type="text" name="countrycode" id="next_lead_countrycode" class="form-control form-control-sm form-control-solid" value="+44" placeholder="e.g. +44" required>
                        </div>

                        <!-- Mobile Number -->
                        <div class="col-md-8 position-relative">
                            <label class="form-label fs-7 fw-bold required">Mobile Number</label>
                            <div class="position-relative">
                                <input type="text" name="mobile" id="next_lead_mobile" class="form-control form-control-sm form-control-solid pe-10" placeholder="Enter Mobile Number" required autocomplete="off">
                                <span id="next_lead_lookup_loader" class="spinner-border spinner-border-sm text-primary position-absolute top-50 end-0 translate-middle-y me-3" style="display:none;"></span>
                            </div>
                            <div id="next_lead_mobile_user_result" class="bg-white border rounded shadow-sm position-absolute w-100 mt-1" style="display:none; z-index:9999; max-height:220px; overflow-y:auto;"></div>
                            <small id="next_lead_user_status" class="text-muted fs-8"></small>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <!-- User Name -->
                        <div class="col-md-6">
                            <label class="form-label fs-7 fw-bold required">Customer Name</label>
                            <input type="text" name="user_name" id="next_lead_user_name" class="form-control form-control-sm form-control-solid" placeholder="Enter Customer Name" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label fs-7 fw-bold">Email Address</label>
                            <input type="email" name="email" id="next_lead_email" class="form-control form-control-sm form-control-solid" placeholder="Optional / Auto-generated">
                        </div>
                    </div>

                    <!-- Target Month -->
                    <div class="mb-4">
                        <label class="form-label fs-7 fw-bold required">Target Order Month (Date Format)</label>
                        <input type="month" name="target_month" id="next_lead_target_month" class="form-control form-control-sm form-control-solid" value="{{ date('Y-m') }}" required>
                    </div>

                    <!-- Message / Notes -->
                    <div class="mb-6">
                        <label class="form-label fs-7 fw-bold">Notes / Requirements</label>
                        <textarea name="message" id="next_lead_message" class="form-control form-control-sm form-control-solid" rows="3" placeholder="Enter any notes or details about the future order..."></textarea>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-light btn-sm me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="btnSubmitNextLead" class="btn btn-primary btn-sm px-8">Save Next Lead</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
