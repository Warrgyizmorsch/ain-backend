<div class="card card-xxl-stretch mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
        <div class="d-flex align-items-center gap-3">
            <h3 class="card-title align-items-start flex-column mb-0">
                <span id="filter-total" class="card-label fw-bolder fs-3 mb-1">
                    Filter
                </span>
            </h3>

            <button type="button" id="toggleFilterBtn" class="btn btn-sm btn-primary">
                Show Filters
            </button>
        </div>
    </div>
    <div class="card-body py-3" id="filterBody" style="display:none;">
        <form action="" id="applyFilters">
            <div class="row mb-3">
                <div class="col-md-3 fv-row">
                    <input type="text" name="additional_filter1" id="additional_filter1" class="form-control form-control-solid" placeholder="Search By Order Id / Title / Contact">
                </div>

                <div class="col-md-3 fv-row position-relative">
                    <input type="text" id="searchInput" name="user" class="form-control form-control-solid" placeholder="Search Customer (Name, Phone, Email)" autocomplete="off">
                    <!-- Dropdown for live user search results -->
                    <div id="searchResultss" class="dropdown-menu w-100 shadow-lg p-0 mt-1" style="display:none; max-height: 250px; overflow-y: auto; z-index: 1050; position: absolute;"></div>
                    <!-- Hidden field to store selected user ID -->
                    <input type="hidden" id="selectedValue" name="uid">
                </div>

                <div class="col-md-3 fv-row">
                    <select name="additional_filter4" id="additional_filter4" class="form-select form-select-solid" data-control="select2" data-placeholder="Select Status">
                        <option value="">All Status</option>
                        <option value="Quote">Quote</option>
                        <option value="Waiting">Waiting</option>
                        <option value="Confirmation">Confirmation</option>
                    </select>
                </div>

                <div class="col-md-3 fv-row">
                    <select name="additional_filter5" id="additional_filter5" class="form-select form-select-solid" data-control="select2" data-placeholder="Select Work Type">
                        <option value="">All Work Types</option>
                        <option value="Technical">Technical Work</option>
                        <option value="Resit">Resit</option>
                        <option value="First">First Class Work</option>
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 fv-row">
                    <input type="date" name="additional_filter3" id="additional_filter3" class="form-control form-control-solid" placeholder="From Date">
                </div>

                <div class="col-md-3 fv-row">
                    <input type="date" name="additional_filter6" id="additional_filter6" class="form-control form-control-solid" placeholder="To Date">
                </div>

                <div class="col-md-3 fv-row">
                    <select name="additional_filter7" id="additional_filter7" class="form-select form-select-solid" data-control="select2" data-placeholder="Date Type">
                        <option value="">Created Date (Default)</option>
                        <option value="Deadline">Deadline</option>
                    </select>
                </div>

                <div class="col-md-3 fv-row d-flex align-items-center gap-2">
                    <button type="button" id="resetFiltersBtn" class="btn btn-sm btn-danger">Reset</button>
                    <button type="button" id="applyButton" class="btn btn-sm btn-primary">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .loading-container {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .loading-spinner {
        border: 3px solid rgba(0, 0, 0, 0.1);
        border-top: 3px solid #3498db;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>
$(document).ready(function () {
    // 1. Toggle filter body
    $('#toggleFilterBtn').on('click', function () {
        const isCurrentlyVisible = $('#filterBody').is(':visible');
        $('#filterBody').slideToggle(300);
        if (isCurrentlyVisible) {
            $(this).text('Show Filters')
                   .removeClass('btn-danger')
                   .addClass('btn-primary');
        } else {
            $(this).text('Hide Filters')
                   .removeClass('btn-primary')
                   .addClass('btn-danger');
        }
    });

    // 2. Debounced User Search Autocomplete
    let searchTimeout = null;
    $('#searchInput').on('input focus', function () {
        var searchValue = $(this).val().trim();
        clearTimeout(searchTimeout);

        if (searchValue.length >= 2) {
            $('#searchResultss').html(
                '<div class="p-3 text-center text-muted fs-7 d-flex align-items-center justify-content-center gap-2">' +
                    '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>' +
                    '<span>Searching users...</span>' +
                '</div>'
            ).show();

            searchTimeout = setTimeout(function () {
                $.ajax({
                    url: "{{ route('search-order') }}",
                    type: "GET",
                    data: { user: searchValue },
                    success: function (response) {
                        var resultsHtml = '';
                        if (response && response.length > 0) {
                            $.each(response, function (key, value) {
                                var mobileStr = value.mobile_no ? ' | 📞 ' + value.mobile_no : '';
                                var emailStr = value.email ? value.email : '';
                                resultsHtml += '<a href="javascript:void(0)" class="dropdown-item user-select-item p-3 border-bottom text-wrap" ' +
                                    'data-id="' + value.id + '" data-email="' + emailStr + '" data-name="' + value.name + '" data-mobile="' + (value.mobile_no || '') + '">' +
                                    '<div class="fw-bolder text-dark fs-6">' + value.name + '</div>' +
                                    '<div class="text-muted fs-7">' + emailStr + mobileStr + '</div>' +
                                    '</a>';
                            });
                        } else {
                            resultsHtml = '<div class="p-3 text-muted fs-7 text-center">No results found</div>';
                        }
                        $('#searchResultss').html(resultsHtml).show();
                    },
                    error: function () {
                        $('#searchResultss').html('<div class="p-3 text-danger fs-7 text-center">Error loading results</div>').show();
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

    // Handle user selection from dropdown
    $(document).on('click', '.user-select-item', function (e) {
        e.preventDefault();
        var selectedId = $(this).attr('data-id');
        var selectedEmail = $(this).attr('data-email');
        var selectedName = $(this).attr('data-name');
        var selectedMobile = $(this).attr('data-mobile');
        var label = selectedName + (selectedMobile ? ' (' + selectedMobile + ')' : (selectedEmail ? ' (' + selectedEmail + ')' : ''));

        $('#searchInput').val(label);
        $('#selectedValue').val(selectedId);
        $('#searchResultss').hide().empty();
    });

    // Close dropdown on clicking outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#searchInput, #searchResultss').length) {
            $('#searchResultss').hide();
        }
    });

    // Enter key submits filters
    $('#additional_filter1, #searchInput').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#searchResultss').hide();
            applyFilters();
        }
    });

    // 3. Apply Filters Function
    function applyFilters() {
        var additionalFilter1 = $('#additional_filter1').val().trim();
        var additionalFilter2 = $('#selectedValue').val().trim();
        var userText = $('#searchInput').val().trim();
        var additionalFilter3 = $('#additional_filter3').val().trim();
        var additionalFilter4 = $('#additional_filter4').val().trim();
        var additionalFilter5 = $('#additional_filter5').val().trim();
        var additionalFilter6 = $('#additional_filter6').val().trim();
        var additionalFilter7 = $('#additional_filter7').val().trim();

        var selectedData = {
            additionalFilter1: additionalFilter1,
            additionalFilter2: additionalFilter2,
            userText: userText,
            additionalFilter3: additionalFilter3,
            additionalFilter4: additionalFilter4,
            additionalFilter5: additionalFilter5,
            additionalFilter6: additionalFilter6,
            additionalFilter7: additionalFilter7
        };

        var hasActiveFilter = additionalFilter1 || additionalFilter2 || userText || additionalFilter3 || additionalFilter4 || additionalFilter5 || additionalFilter6 || additionalFilter7;

        if (hasActiveFilter) {
            localStorage.setItem('lead_filters', JSON.stringify(selectedData));

            $('.allData').hide();
            $('.searchData').show();
            $('#content').html('<tr><td colspan="9" class="text-center py-5"><div class="loading-container gap-3"><div class="loading-spinner"></div><span class="fw-bold text-gray-700">Loading leads...</span></div></td></tr>');

            $.ajax({
                url: '{{ route('search-leads') }}',
                method: 'GET',
                data: selectedData,
                success: function (response) {
                    $('#content').html(response);
                },
                error: function (error) {
                    console.error(error);
                    $('#content').html('<tr><td colspan="9" class="text-center py-4 text-danger fw-bold">Error loading search results. Please try again.</td></tr>');
                }
            });
        } else {
            localStorage.removeItem('lead_filters');
            $('.allData').show();
            $('.searchData').hide();
            $('#content').empty();
        }
    }

    // 4. Reset Filters Function
    function resetFilters() {
        localStorage.removeItem('lead_filters');
        $('#applyFilters')[0].reset();
        $('#selectedValue').val('');
        $('#searchInput').val('');
        $('#searchResultss').hide().empty();

        if ($.fn.select2) {
            $('#additional_filter4, #additional_filter5, #additional_filter7').val('').trigger('change.select2');
        }

        $('.allData').show();
        $('.searchData').hide();
        $('#content').empty();
    }

    // Bind Search & Reset buttons
    $('#applyButton').on('click', function (e) {
        e.preventDefault();
        applyFilters();
    });

    $('#resetFiltersBtn').on('click', function (e) {
        e.preventDefault();
        resetFilters();
    });

    // 5. Restore Stored Filters on Page Load
    var storedFilters = localStorage.getItem('lead_filters');
    if (storedFilters) {
        try {
            var filters = JSON.parse(storedFilters);
            var hasActive = false;
            $.each(filters, function (key, value) {
                if (value) {
                    hasActive = true;
                    if (key === 'userText') {
                        if (!$('#searchInput').val()) {
                            $('#searchInput').val(value);
                        }
                    } else {
                        $('#' + key).val(value);
                        if ($.fn.select2 && $('#' + key).is('select')) {
                            $('#' + key).trigger('change.select2');
                        }
                    }
                }
            });

            if (hasActive) {
                $('#filterBody').show();
                $('#toggleFilterBtn').text('Hide Filters').removeClass('btn-primary').addClass('btn-danger');
                applyFilters();
            }
        } catch (e) {
            console.error('Error parsing stored filters', e);
        }
    }
});
</script>
