<div class="modal fade" id="kt_modal_create_appaa_newLeads" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-1000px">
            <div class="modal-content rounded shadow" style="height: 100%;">
                <div class="modal-header py-3 border-0 justify-content-between px-6">
                    <h2 class="fw-bolder mb-0">Create New Leads</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body px-8 pt-0 pb-6">
                    <form id="kt_modal_new_target_form" class="form" method="POST" action="{{ route('leads') }}">
                        @csrf
                        @method('POST')

                        <div class="card p-4 mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold">User Name</label>
                                    <input type="text" name="user_name" class="form-control form-control-sm form-control-solid">
                                </div>

                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold">Email</label>
                                    <input type="email"
                                        name="email"
                                        class="form-control form-control-sm form-control-solid">

                                    <input type="hidden" name="id" id="id">
                                </div>

                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold">Country Code</label>
                                    <input type="text"
                                        name="countrycode"
                                        class="form-control form-control-sm form-control-solid"
                                        required>
                                </div>

                                <div class="col-md-3 position-relative">
                                    <label class="fs-7 fw-bold">Mobile</label>
                                    <div class="position-relative">
                                        <input type="text"
                                            name="mobile"
                                            id="mobile"
                                            class="form-control form-control-sm form-control-solid pe-10"
                                            required>
                                        <span id="mobile_lookup_loader"
                                            class="spinner-border spinner-border-sm text-primary position-absolute top-50 end-0 translate-middle-y me-3"
                                            role="status"
                                            aria-hidden="true"
                                            style="display:none;">
                                        </span>
                                    </div>
                                    <div id="mobile_user_result"
                                        class="bg-white border rounded shadow-sm position-absolute w-100"
                                        style="display:none; z-index:9999; max-height:220px; overflow-y:auto;">
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold mb-1">Lead Source</label>
                                    <select name="lead_source" id="lead_source" class="form-select form-select-solid" required>

                                        <option value="">Select Source</option>

                                        @foreach($sources as $source)
                                        <option value="{{ $source->id }}"
                                            {{ request('lead_source') == 'source_'.$source->id ? 'selected' : '' }}>

                                            {{ $source->source_name }}
                                        </option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold">Semester</label>
                                    <select name="semester" class="form-select form-select-sm form-select-solid">
                                        <option value="">Select</option>
                                        <option>I Semester</option>
                                        <option>II Semester</option>
                                        <option>III Semester</option>
                                        <option>IV Semester</option>
                                        <option>Final Semester</option>
                                    </select>
                                </div>
                                <div class="col-md-3 position-relative">
                                    <label class="fs-7 fw-bold">Referred By</label>

                                    <input type="text"
                                        id="refer_search"
                                        class="form-control form-control-sm form-control-solid"
                                        placeholder="Name, Email, Mobile search">

                                    <input type="hidden" name="refer_id" id="refer_id">

                                    <div id="refer_result"
                                        class="bg-white border rounded shadow-sm position-absolute w-100"
                                        style="display:none; z-index:9999; max-height:220px; overflow-y:auto;">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold">Count</label>
                                    <input type="number"
                                        id="lead_count_input"
                                        class="form-control form-control-sm form-control-solid"
                                        value="1"
                                        min="1">
                                </div>
                            </div>
                        </div>



                        <div class="text-end mb-3">
                            <button type="button" id="addLeadBox" class="btn btn-sm btn-primary">
                                + Add More
                            </button>
                        </div>

                        <div id="leadContainer" style="max-height: 450px; overflow-y: auto; overflow-x: hidden; padding-right: 6px;">

                            <div class="lead-box card p-4 mb-4  position-relative border border-dark">

                                <!-- REMOVE BUTTON -->
                                <div class="d-flex justify-content-end gap-4 mb-2">
                                    <button type="button" class="btn btn-sm btn-primary toggle-extra">
                                        + Additional fields
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger remove-box">
                                        Remove
                                    </button>
                                </div>

                                <!-- ROW 1 -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-2">
                                        <label class="fs-7 fw-bold">Module Code</label>
                                        <input type="text" name="module_code[]" class="form-control form-control-sm form-control-solid">
                                    </div>

                                    <div class="col-md-5">
                                        <label class="fs-7 fw-bold">Project Title</label>
                                        <input type="text" name="project_title[]" class="form-control form-control-sm form-control-solid">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="fs-7 fw-bold">Word/Pages</label>
                                        <input type="text" name="pages[]" class="form-control form-control-sm form-control-solid">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="fs-7 fw-bold">Type Of Paper</label>
                                        <select name="paper[]" class="form-select form-select-sm form-select-solid">
                                            <option value="">Not Selected</option>
                                            @foreach($papers as $paper)
                                            <option value="{{ $paper->paper_type }}" {{ isset($lead) && $lead->typeofpaper === $paper->paper_type ? 'selected' : '' }}>{{ $paper->paper_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- <div class="col-md-3">
                                        <label class="fs-7 fw-bold">Lead Status</label>
                                        <select name="i_status[]" class="form-select form-select-sm form-select-solid">
                                            <option value="">Select Status</option>
                                            <option>Waiting</option>
                                            <option>Quote</option>
                                            <option>Confirmation</option>
                                        </select>
                                    </div> -->
                                </div>

                                <!-- ROW 2 -->
                                <div class="row g-3 mb-3">
                                    <!-- <div class="col-md-4">
                                        <label class="fs-7 fw-bold">Services</label>
                                        <select name="service_type[]" class="form-select form-select-sm form-select-solid">
                                            <option value="">Select Service</option>
                                            @foreach($service as $s)
                                            <option value="{{ $s->service_name }}" {{ isset($lead) && $lead->service_type === $s->service_name ? 'selected' : '' }}>{{ $s->service_name }}</option>
                                            @endforeach
                                        </select>
                                    </div> -->



                                    <!-- <div class="col-md-3 d-flex align-items-center">
                                        <input type="checkbox" name="tech[]" class="me-2"> Technical
                                        <input type="checkbox" name="resit[]" class="ms-3 me-2"> Resit
                                    </div> -->

                                    <div class="col-md-2">
                                        <label class="fs-7 fw-bold">Amount</label>
                                        <input type="text" name="amount[]" class="form-control form-control-sm form-control-solid">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="fs-7 fw-bold mb-1">Chapters</label>
                                        <select name="chapter[]" data-control="select2" data-placeholder="Select Chapters" multiple="multiple" class="form-select form-select-sm form-select-solid chapter-select">
                                            <option value="Chapter 1: Introduction">Chapter 1: Introduction</option>
                                            <option value="Chapter 2: Litreature Review">Chapter 2: Litreature Review</option>
                                            <option value="Chapter 3: Methedology">Chapter 3: Methedology</option>
                                            <option value="Chapter 4: Data Analysis">Chapter 4: Data Analysis</option>
                                            <option value="Chapter 5: Conclusion & Recommendation">Chapter 5: Conclusion & Recommendation</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="fs-7 fw-bold">Delivery Date</label>
                                        <input type="date" name="delivery_date[]" class="form-control form-control-sm form-control-solid">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="fs-7 fw-bold">Delivery Time</label>
                                        <input type="time" name="delivery_time[]" class="form-control form-control-sm form-control-solid">
                                    </div>



                                    <!-- ROW 3 -->




                                    <!-- <div class="row g-3 mb-3">
                                    <div class="col-md-3 fv-row">
                                        <label class="fs-7 fw-bold mb-1">Draft Required</label>
                                        <select name="draft_required[]" onchange="showHideDiv(this);" data-placeholder="Draft needed?" class="form-select form-select-sm form-select-solid">
                                            <option value="">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 fv-row draftDiv" style="display:none;">
                                        <label class="fs-7 fw-bold mb-1">Draft Deadline</label>
                                        <div class="d-flex gap-2">
                                            <input type="date" name="draft_date[]" class="form-control form-control-sm form-control-solid w-50">
                                            <input type="time" name="draft_time[]" class="form-control form-control-sm form-control-solid w-50">
                                        </div>
                                    </div>

                                </div> -->

                                    <!-- MESSAGE -->
                                    <!-- <div>
                                    <label class="fs-7 fw-bold">Message</label>
                                    <textarea name="message[]" class="form-control form-control-sm form-control-solid"></textarea>
                                </div> -->

                                    <div class="extra-fields" style="display: none;">

                                        <!-- Services -->
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-4">
                                                <label class="fs-7 fw-bold">Services</label>
                                                <select name="service_type[]" class="form-select form-select-sm form-select-solid">
                                                    <option value="">Select Service</option>
                                                    @foreach($service as $s)
                                                    <option value="{{ $s->service_name }}">{{ $s->service_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3 d-flex align-items-center">
                                                <input type="checkbox" name="tech[]" class="me-2"> Technical
                                                <input type="checkbox" name="resit[]" class="ms-3 me-2"> Resit
                                            </div>


                                            <div class="col-md-3 fv-row">
                                                <label class="fs-7 fw-bold mb-1">Draft Required</label>
                                                <select name="draft_required[]" onchange="showHideDiv(this);" data-placeholder="Draft needed?" class="form-select form-select-sm form-select-solid">
                                                    <option value="">No</option>
                                                    <option value="Yes">Yes</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 fv-row draftDiv" style="display:none;">
                                                <label class="fs-7 fw-bold mb-1">Draft Deadline</label>
                                                <div class="d-flex gap-2">
                                                    <input type="date" name="draft_date[]" class="form-control form-control-sm form-control-solid w-50">
                                                    <input type="time" name="draft_time[]" class="form-control form-control-sm form-control-solid w-50">
                                                </div>
                                            </div>

                                        </div>

                                        <!-- Message -->
                                        <div>
                                            <label class="fs-7 fw-bold">Message</label>
                                            <textarea name="message[]" class="form-control form-control-sm form-control-solid"></textarea>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="text-end pt-2">
                            <button type="button" class="btn btn-light btn-sm me-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm px-8">Submit Lead</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
    let loggedInRoleId = {{ auth()->user()->role_id ?? 0 }};
</script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.addEventListener('click', function(e) {

                // 🔹 Toggle additional fields
                if (e.target.classList.contains('toggle-extra')) {

                    let box = e.target.closest('.lead-box');
                    let extra = box.querySelector('.extra-fields');

                    if (extra.style.display === 'none') {
                        extra.style.display = 'block';
                        e.target.innerText = '- Hide';
                    } else {
                        extra.style.display = 'none';
                        e.target.innerText = '+ Additional';
                    }
                }

            });

            const addBtn = document.getElementById("addLeadBox");
            const container = document.getElementById("leadContainer");

            // INITIAL SELECT2 LOAD
            initSelect2();

            // ======================
            // ADD / CLONE BOX
            // ======================

            window.showHideDiv = function(select) {
                var selectedOption = select.value;

                // sirf current box ke andar search kare
                var parentBox = select.closest(".lead-box");

                var newDiv = parentBox.querySelector(".draftDiv");

                if (selectedOption === "Yes") {
                    newDiv.style.display = "block";
                } else {
                    newDiv.style.display = "none";
                }
            }

            function cloneLeadBox() {
                let firstBox = document.querySelector(".lead-box");
                if (!firstBox) return;
                let clone = firstBox.cloneNode(true);

                clone.querySelectorAll('.draftDiv').forEach(div => {
                    div.style.display = "none";
                });

                // CLEAR INPUTS
                clone.querySelectorAll("input").forEach(input => {
                    if (input.type === "checkbox") {
                        input.checked = false;
                    } else {
                        input.value = "";
                    }
                });

                // CLEAR TEXTAREA
                clone.querySelectorAll("textarea").forEach(t => t.value = "");

                // RESET SELECT
                clone.querySelectorAll("select").forEach(select => {
                    $(select).val(null).trigger('change'); // Select2 reset
                });

                // REMOVE OLD SELECT2 HTML (IMPORTANT)
                clone.querySelectorAll('.select2').forEach(el => el.remove());

                // SHOW ORIGINAL SELECT AGAIN
                clone.querySelectorAll('.chapter-select').forEach(el => {
                    el.style.display = "block";
                });

                container.appendChild(clone);

                // RE-INIT SELECT2 ONLY FOR NEW CLONE
                initSelect2();
            }

            function updateCountInput() {
                let count = document.querySelectorAll(".lead-box").length;
                let countInput = document.getElementById("lead_count_input");
                if (countInput) {
                    countInput.value = count;
                }
            }

            const leadCountInput = document.getElementById("lead_count_input");
            if (leadCountInput) {
                leadCountInput.addEventListener("input", function() {
                    let targetCount = parseInt(this.value) || 1;
                    if (targetCount < 1) {
                        targetCount = 1;
                        this.value = 1;
                    }

                    let currentBoxes = container.querySelectorAll(".lead-box");
                    let currentCount = currentBoxes.length;

                    if (targetCount > currentCount) {
                        for (let i = 0; i < targetCount - currentCount; i++) {
                            cloneLeadBox();
                        }
                    } else if (targetCount < currentCount) {
                        for (let i = 0; i < currentCount - targetCount; i++) {
                            let boxes = container.querySelectorAll(".lead-box");
                            if (boxes.length > 1) {
                                boxes[boxes.length - 1].remove();
                            }
                        }
                    }
                    toggleRemoveButtons();
                });
            }

            addBtn.addEventListener("click", function() {
                cloneLeadBox();
                toggleRemoveButtons();
                updateCountInput();
            });

            // ======================
            // REMOVE BOX
            // ======================
            container.addEventListener("click", function(e) {
                if (e.target.classList.contains("remove-box")) {

                    let allBoxes = document.querySelectorAll(".lead-box");

                    if (allBoxes.length > 1) {
                        e.target.closest(".lead-box").remove();
                    }

                    toggleRemoveButtons();
                    updateCountInput();
                }
            });

            // ======================
            // HIDE FIRST REMOVE BTN
            // ======================
            function toggleRemoveButtons() {
                let boxes = document.querySelectorAll(".lead-box");

                boxes.forEach((box, index) => {
                    let btn = box.querySelector(".remove-box");

                    if (index === 0) {
                        btn.style.display = "none";
                    } else {
                        btn.style.display = "block";
                    }
                });
            }

            // ======================
            // SELECT2 INIT FUNCTION
            // ======================
            function initSelect2() {
                $('.chapter-select').select2({
                    placeholder: "Select Chapters",
                    width: '100%'
                });
            }

            // INITIAL CALL
            toggleRemoveButtons();

        });
    </script>

    <script>
        $(document).ready(function () {

            let referTimer = null;

            $('#refer_search').on('keyup', function () {
                if ($(this).prop('readonly')) {
                    return;
                }
                let search = $(this).val();

                $('#refer_id').val('');

                clearTimeout(referTimer);

                if (search.length < 2) {
                    $('#refer_result').hide().html('');
                    return;
                }

                referTimer = setTimeout(function () {
                    $.ajax({
                        url: "{{ route('search.refer.users') }}",
                        type: "GET",
                        data: {
                            search: search
                        },
                        success: function (users) {
                            let html = '';

                            if (users.length > 0) {
                                users.forEach(function (user) {
                                    html += `
                                        <div class="refer-item px-3 py-2 border-bottom"
                                            style="cursor:pointer;"
                                            data-id="${user.id}"
                                            data-name="${user.name ?? ''}"
                                            data-email="${user.email ?? ''}"
                                            data-mobile="${user.mobile_no ?? ''}">
                                            <strong>${user.name ?? 'No Name'}</strong><br>
                                            <small>${user.email ?? ''} | ${user.mobile_no ?? ''}</small>
                                        </div>
                                    `;
                                });
                            } else {
                                html = `<div class="px-3 py-2 text-muted">No user found</div>`;
                            }

                            $('#refer_result').html(html).show();
                        }
                    });
                }, 300);
            });

            $(document).on('click', '.refer-item', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let mobile = $(this).data('mobile');

                $('#refer_id').val(id);
                $('#refer_search').val(name + ' - ' + mobile + ' - ' + email);
                $('#refer_result').hide();
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('#refer_search, #refer_result').length) {
                    $('#refer_result').hide();
                }
            });

        });
    </script>
<script>
$(document).ready(function () {

    let mobileTimer = null;
    const $mobileResult = $('#mobile_user_result');
    const $mobileLoader = $('#mobile_lookup_loader');

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function resetUserFields() {
        $('input[name="user_name"]').val('').prop('readonly', false);
        $('input[name="countrycode"]').val('').prop('readonly', false);
        $('input[name="email"]').val('').prop('readonly', false);
        $('input[name="id"]').val('');
        $('#refer_id').val('');
        $('#refer_search').val('').prop('readonly', false);
    }

    function fillReferUser(referUser) {
        if (referUser) {
            $('#refer_id').val(referUser.id);
            $('#refer_search').val(
                (referUser.name || '') + ' - ' +
                (referUser.mobile_no || '') + ' - ' +
                (referUser.email || '')
            );

            $('#refer_search').prop('readonly', loggedInRoleId != 1);
        } else {
            $('#refer_id').val('');
            $('#refer_search').val('').prop('readonly', false);
        }
    }

    function fillUser(user, referUser = null) {
        if (!user) {
            resetUserFields();
            return;
        }

        $('input[name="user_name"]').val(user.name || '').prop('readonly', true);
        $('input[name="countrycode"]').val(user.countrycode || '').prop('readonly', true);
        $('input[name="email"]').val(user.email || '').prop('readonly', true);
        $('input[name="id"]').val(user.id || '');
        $('#mobile').val(user.mobile_no || $('#mobile').val());
        fillReferUser(referUser || user.refer_user || null);
    }

    function renderMobileDropdown(users) {
        if (!Array.isArray(users) || users.length === 0) {
            $mobileResult.hide().html('');
            return;
        }

        let html = '';
        users.forEach(function (user) {
            html += `
                <div class="mobile-user-item px-3 py-2 border-bottom"
                    style="cursor:pointer;"
                    data-user='${escapeHtml(JSON.stringify(user))}'>
                    <strong>${escapeHtml(user.name || 'No Name')}</strong><br>
                    <small>${escapeHtml(user.countrycode || '')} ${escapeHtml(user.mobile_no || '')} | ${escapeHtml(user.email || '')}</small>
                </div>
            `;
        });

        $mobileResult.html(html).show();
    }

    $('#mobile').on('keyup input', function () {

        let mobile = $(this).val().replace(/\D/g, '');
        $(this).val(mobile);

        clearTimeout(mobileTimer);

        if (mobile.length < 5) {
            resetUserFields();
            $mobileLoader.hide();
            $mobileResult.hide().html('');
            return;
        }

        $mobileLoader.show();
        $mobileResult.hide().html('');

        mobileTimer = setTimeout(function () {

            $.ajax({
                type: 'GET',
                url: '{{ url("userData") }}',
                data: {
                    mobile: mobile
                },

                success: function (data) {
                    $mobileLoader.hide();
                    renderMobileDropdown(data.users || []);

                    if (data.user) {
                        fillUser(data.user, data.referUser);
                        if ((data.users || []).length <= 1) {
                            $mobileResult.hide();
                        }
                    } else {
                        resetUserFields();
                    }
                },

                error: function (error) {
                    $mobileLoader.hide();
                    console.log('UserData Error:', error);
                }
            });

        }, 300);

    });

    $(document).on('click', '.mobile-user-item', function () {
        let user = $(this).data('user');

        if (typeof user === 'string') {
            user = JSON.parse(user);
        }

        fillUser(user);
        $mobileResult.hide();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#mobile, #mobile_user_result').length) {
            $mobileResult.hide();
        }
    });

});
</script>
@endpush
