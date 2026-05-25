<div class="card card-xxl-stretch mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Filter</span>
        </h3>
    </div>
    <div class="card-body py-3">
    <form action="" id="applyFilters">

        <div class="row mb-3 additional-filters">
            <div class="col-md-3 fv-row">
                <input type="text" name="additional_filter1" id="additional_filter1" class="form-control form-control-solid" placeholder="Search By Order Id / Title">
            </div>

            <div class="col-md-3 fv-row">
                <select name="additional_filter4" id="additional_filter4" class="form-select form-select-solid" placeholder="Additional Filter 4">
                    <option value="">Select Status</option>
                    <option value="Quote">Quote</option>
                    <option value="Waiting">Waiting</option>
                    <option value="Confirmation">Confirmation</option>
                </select>
            </div>
            <div class="col-md-3 fv-row">
                <select name="additional_filter5" id="additional_filter5" class="form-select form-select-solid" placeholder="Additional Filter 4">
                    <option value="">search Tech / Resit / First Class</option>
                    <option value="First">First Class Work</option>
                    <option value="Resit">Resit</option>
                    <option value="Technical">Technical</option>
                </select>
            </div>
            <!-- <div class="col-md-3 fv-row">
                <input type="input" name="additional_filter2" id="additional_filter2" class="form-control form-control-solid" placeholder="Search with Mobile/Name/email  ">
            </div> -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('searchInput');

                    searchInput.addEventListener('input', function(event) {
                        const inputValue = event.target.value;
                        const sanitizedValue = inputValue.replace(/\s/g, ''); // Remove spaces

                        // Update input value without spaces
                        if (inputValue !== sanitizedValue) {
                            searchInput.value = sanitizedValue;
                        }
                    });
                });
            </script>

            <div class="col-md-3 fv-row">
                <input type="text" list="searchDatalist" id="searchInput" name="user" class="form-control form-control-solid" placeholder="Search..." autocomplete="off">
                <!-- Datalist for displaying search results -->
                <datalist id="searchDatalist"></datalist>
                <!-- Container to display search results -->
                <div id="searchResultss"></div>
                <!-- Hidden field to store the selected value -->
                <input type="hidden" id="selectedValue" name="uid">
            </div>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function () {
                    $('#searchInput').on('input', function () {
                        var searchValue = $(this).val();

                        if (searchValue.length >= 3) {
                            $.ajax({
                                url: "{{ route('search-order') }}",
                                type: "GET",
                                data: { user: searchValue },
                                success: function (response) {
                                    var results = '';
                                    if (response.length > 0) {
                                        // Populate the datalist with search results
                                        $('#searchDatalist').empty();
                                        $.each(response, function (key, value) {
                                            // Append each option with email, name, and mobile number
                                            $('#searchDatalist').append('<option value="' + value.email + '">' + value.name + ' (' + value.mobile_no + ')</option>');
                                        });
                                        if(response.length === 1) {
                                            // If there is only one result, automatically fill in the search input
                                            $('#searchInput').val(response[0].email);
                                            // Store the selected value in the hidden field
                                            $('#selectedValue').val(response[0].id);
                                        }
                                    } else {
                                        results = '<div>No results found</div>';
                                    }
                                    $('#searchResultss').html(results);
                                }
                            });
                        } else {
                            $('#searchResultss').empty();
                        }
                    });

                    // Handle click on search result
                    $('#searchInput').on('input', function() {
                        var selectedEmail = $(this).val();
                        var selectedOption = $('#searchDatalist option[value="' + selectedEmail + '"]');
                            if (selectedOption.length > 0) {
                                resetPasswordBtn.addEventListener('click', function() {
                                // Get the reset password button
                                var resetPasswordBtn = document.getElementById('resetFiltersBtn');

                                // Clear the value of the selectedValue input
                                $('#selectedValue').val('');
                            });
                            // If the selected value exists in the datalist, get its associated ID
                            var selectedId = selectedOption.data('id');
                            $('#selectedValue').val(selectedId);
                        } else {
                            // If the selected value doesn't exist in the datalist, clear the hidden field
                            $('#selectedValue').val('');
                        }
                    });

                    
                });
            </script>
        </div>


        <div class="row">
            <div class="col-md-3 fv-row">
                <input type="date" name="additional_filter3" id="additional_filter3" class="form-control form-control-solid" placeholder="Upto ">
            </div>

            <div class="col-md-3 fv-row">
                <input type="date" name="additional_filter6" id="additional_filter6" class="form-control form-control-solid" placeholder="Upto ">
            </div>

            <div class="col-md-3 fv-row">
                <select name="additional_filter7" id="additional_filter7" class="form-select form-select-solid" placeholder="Additional Filter 4">
                    <option value="">Date Type</option>
                    <option value="Deadline">Deadline</option>
                </select>
            </div>

            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                <a id="resetFiltersBtn"class="btn btn-sm btn-danger">Reset</a>
                <a id="applyButton"  class="btn btn-sm btn-primary">search</a>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<style>
        .loading-container {
            position: relative;
            height: 100%; /* Adjust this value according to your layout */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error {
            color: red;
        }

        /* Additional styling can be added here */
    </style>
<script>
$(document).ready(function () {
    // Function to apply filters
    function applyFilters() {
        // Retrieve input values
        var additionalFilter1 = $('#additional_filter1').val();
        var additionalFilter2 = $('#selectedValue').val();
        var additionalFilter3 = $('#additional_filter3').val();
        var additionalFilter4 = $('#additional_filter4').val();
        var additionalFilter5 = $('#additional_filter5').val();
        var additionalFilter6 = $('#additional_filter6').val();
        var additionalFilter7 = $('#additional_filter7').val();

        // Create an object with the selected data
        var selectedData = {
            additionalFilter1: additionalFilter1,
            additionalFilter2: additionalFilter2,
            additionalFilter3: additionalFilter3,
            additionalFilter4: additionalFilter4,
            additionalFilter5: additionalFilter5,
            additionalFilter6: additionalFilter6,
            additionalFilter7: additionalFilter7
        };

        // Save filters to local storage
        localStorage.setItem('filters', JSON.stringify(selectedData));

        // Filter logic
        if (selectedData.additionalFilter1 || selectedData.additionalFilter2 || selectedData.additionalFilter3 || selectedData.additionalFilter4 || selectedData.additionalFilter5 || selectedData.additionalFilter6 || selectedData.additionalFilter7) {
            $('.allData').hide();
            $('.searchData').show();
        } else {
            $('.allData').show();
            $('.searchData').hide();
        }

        // Show loading while waiting for response
        $('#content').html('<div class="loading-container">Please Wait Data Is Loading......<div class="loading-spinner"></div></div>');

        // Send data to the backend using AJAX
        $.ajax({
            url: '{{ route('search-leads') }}',
            method: 'GET',
            data: selectedData,
            success: function(response) {
                // Update the table with the received data
                console.log(response);
                $('#content').html(response);
            },
            error: function(error) {
                // Handle errors if the request fails
                console.error(error);
            }
        });
    }

    // Function to reset filters
    function resetFilters() {
        localStorage.removeItem('filters'); // Remove stored filters

        $('#applyFilters')[0].reset();

        // Clear the value of the selectedValue input
        $('#selectedValue').val('');
        // Apply filters
        applyFilters();
    }

    // Apply filters when the apply button is clicked
    $('#applyButton').on('click', function () {
        applyFilters();
    });

    // Reset filters when the reset button is clicked
    $('#resetFiltersBtn').on('click', function() {
        resetFilters();
    });

    // Check for stored filters and apply them
    var storedFilters = localStorage.getItem('filters');
    if (storedFilters) {
        var filters = JSON.parse(storedFilters);
        $.each(filters, function(key, value) {
            $('#' + key).val(value);
        });
        applyFilters();
    } else {
        // If no filters are stored, apply default filters
        applyFilters();
    }
});

</script>
