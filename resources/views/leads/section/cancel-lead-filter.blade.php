<div class="card card-xxl-stretch mb-5 mb-xl-8 cancel-lead-filter-card">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Filter</span>
        </h3>
    </div>
    <div class="card-body py-3">
        <form action="/c-leads" method="GET" id="cleadFilterForm">
            <div class="row g-3 mb-3">
                <div class="col-md-3 fv-row">
                    <label class="form-label fw-bold fs-7">Order Code / Project Title</label>
                    <input type="search" name="search" id="search" class="form-control form-control-solid" placeholder="Search By Order Code / Title" value="{{ request('search') }}">
                </div>

                <div class="col-md-3 fv-row">
                    <label class="form-label fw-bold fs-7">Customer (Name / Number / Email)</label>
                    <div class="position-relative">
                        <input type="text" id="searchInput" name="user" class="form-control form-control-solid pe-10" placeholder="Search by Name, Number, Email..." autocomplete="off" value="{{ request('user') ?: (request('uid') && !is_numeric(request('uid')) ? request('uid') : '') }}">
                        <!-- In-input preloader spinner -->
                        <span id="searchSpinner" class="position-absolute end-0 top-50 translate-middle-y me-3" style="display:none; pointer-events: none; z-index: 10;">
                            <span class="spinner-border spinner-border-sm text-primary" role="status" style="width: 1.1rem; height: 1.1rem; border-width: 2px;">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                        </span>
                        <!-- Single Custom Dropdown -->
                        <div id="searchResultss" class="dropdown-menu w-100 shadow-lg p-0 mt-1" style="display:none; max-height: 260px; overflow-y: auto; z-index: 1050; position: absolute; left: 0; top: 100%; background: #ffffff !important; border: 1px solid #d8dbe0;"></div>
                    </div>
                    <input type="hidden" id="selectedValue" name="uid" value="{{ request('uid') }}">
                </div>

                <div class="col-md-2 fv-row">
                    <label class="form-label fw-bold fs-7">From Date</label>
                    <input type="date" name="additional_filter3" id="additional_filter3" class="form-control form-control-solid" value="{{ request('additional_filter3') }}">
                </div>

                <div class="col-md-2 fv-row">
                    <label class="form-label fw-bold fs-7">To Date</label>
                    <input type="date" name="additional_filter6" id="additional_filter6" class="form-control form-control-solid" value="{{ request('additional_filter6') }}">
                </div>

                <div class="col-md-2 fv-row">
                    <label class="form-label fw-bold fs-7">Date Type</label>
                    <select name="additional_filter7" id="additional_filter7" class="form-select form-select-solid">
                        <option value="">Created At (Default)</option>
                        <option value="Deadline" {{ request('additional_filter7') == 'Deadline' ? 'selected' : '' }}>Deadline</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary px-5">
                        <i class="fa fa-search me-1"></i> Search
                    </button>
                    <a href="/c-leads" class="btn btn-sm btn-danger px-5">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    let searchTimeout = null;

    function doCleadUserSearch() {
        var searchValue = $('#searchInput').val();
        clearTimeout(searchTimeout);

        if (searchValue && searchValue.trim().length >= 2) {
            var query = searchValue.trim();
            // Show preloader in input immediately
            $('#searchSpinner').show();
            // Show preloader in custom dropdown
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

    // Input & Keyup event
    $('#searchInput').on('input keyup', function () {
        doCleadUserSearch();
    });

    // Paste event with instant spinner
    $('#searchInput').on('paste', function () {
        $('#searchSpinner').show();
        setTimeout(function () {
            doCleadUserSearch();
        }, 50);
    });

    // Focus event
    $('#searchInput').on('focus', function () {
        var val = $(this).val();
        if (val && val.trim().length >= 2) {
            doCleadUserSearch();
        }
    });

    // Dropdown item selection
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
