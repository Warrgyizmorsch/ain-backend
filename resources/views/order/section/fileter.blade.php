<div class="card card-xxl-stretch mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
    	<h3 class="card-title align-items-start flex-column">
    		<span class="card-label fw-bolder fs-3 mb-1">Filter</span>
    	</h3>
    </div>
    <div class="card-body py-3">
    	<form action="" onsubmit="event.preventDefault(); applyFilters(); return false;">
    		<div class="row mb-3">
            <div class="col-md-3 fv-row">
                <input type="search" name="search" id="search" class="form-control form-control-solid" placeholder="Search By OrderCode or Title">
            </div>

                <div class="col-md-3 fv-row position-relative">
                    <input type="text" id="searchInput" name="user" class="form-control form-control-solid" placeholder="Search Customer (Name, Phone, Email)" autocomplete="off" value="{{ request('user') }}">
                    <!-- Container to display custom search results dropdown -->
                    <div id="searchResultss" class="dropdown-menu w-100 shadow-lg p-0 mt-1" style="display:none; max-height: 260px; overflow-y: auto; z-index: 1050; position: absolute; left: 0; top: 100%; background: #ffffff !important; border: 1px solid #d8dbe0;"></div>
                    <!-- Hidden field to store the selected value -->
                    <input type="hidden" id="selectedValue" name="uid" value="{{ request('uid') }}">
                </div>

                <style>
                    #searchResultss .user-select-item {
                        cursor: pointer;
                        transition: background 0.15s ease-in-out;
                    }
                    #searchResultss .user-select-item:hover {
                        background-color: #f1f5f9;
                    }
                </style>

                <script>
                    (function() {
                        function initOrderUserSearch() {
                            if (typeof jQuery === 'undefined' || !window.jQuery) {
                                setTimeout(initOrderUserSearch, 50);
                                return;
                            }
                            var $ = window.jQuery;
                            var searchTimeout = null;

                            $('#search, #searchInput').off('keypress.usersearch').on('keypress.usersearch', function(e) {
                                if (e.which === 13 || e.keyCode === 13) {
                                    e.preventDefault();
                                    applyFilters();
                                }
                            });

                            $('#searchInput').off('input.usersearch focus.usersearch').on('input.usersearch focus.usersearch', function () {
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
                                                        var idBadge = value.id ? '<span class="badge badge-light-primary fw-bolder fs-8 ms-2 px-2 py-0.5" style="border: 1px solid #bfdbfe;">ID: ' + value.id + '</span>' : '';
                                                        resultsHtml += '<a href="javascript:void(0)" class="dropdown-item user-select-item p-3 border-bottom text-wrap" ' +
                                                            'data-id="' + value.id + '" data-email="' + emailStr + '" data-name="' + value.name + '" data-mobile="' + (value.mobile_no || '') + '">' +
                                                            '<div class="d-flex align-items-center justify-content-between">' +
                                                                '<span class="fw-bolder text-dark fs-6">' + value.name + '</span>' +
                                                                idBadge +
                                                            '</div>' +
                                                            '<div class="text-muted fs-7 mt-1">' + emailStr + mobileStr + '</div>' +
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
                            $(document).off('click.useritem', '#searchResultss .user-select-item').on('click.useritem', '#searchResultss .user-select-item', function (e) {
                                e.preventDefault();
                                var selectedId = $(this).attr('data-id');
                                var selectedEmail = $(this).attr('data-email');
                                var selectedName = $(this).attr('data-name');
                                var selectedMobile = $(this).attr('data-mobile');
                                var label = selectedName + (selectedMobile ? ' (' + selectedMobile + ')' : (selectedEmail ? ' (' + selectedEmail + ')' : ''));

                                $('#searchInput').val(label);
                                $('#selectedValue').val(selectedId);
                                $('#searchResultss').hide().empty();
                                applyFilters();
                            });

                            // Close dropdown when clicking outside
                            $(document).off('click.outsidesearch').on('click.outsidesearch', function (e) {
                                if (!$(e.target).closest('#searchInput, #searchResultss').length) {
                                    $('#searchResultss').hide();
                                }
                            });
                        }

                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', initOrderUserSearch);
                        } else {
                            initOrderUserSearch();
                        }
                    })();
                </script>





                           
    			<div class="col-lg-3 fv-row fv-plugins-icon-container">
    				<select name="status" id="status" aria-label="Select a Language" data-control="select2" data-placeholder="Status" class="form-select form-select-solid form-select-lg " data-select2-id="select2-data-13-mh4q" tabindex="-1" >
    					<option value="" ></option>
                        @foreach($data['Status'] as $Status)
                           <option value="{{$Status->status}}">{{$Status->status}}</option>
                        @endforeach
    				</select>
    				<div class="fv-plugins-message-container invalid-feedback"></div>
    			</div>
                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="writer" id="writer" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By writer Name " class="form-select form-select-solid form-select-lg " data-select2-id="select2-data-16-79699" tabindex="-1" >
    					<option value="" >select</option>
                        <option value="Not Assign">Not Assign</option>

                        @foreach($data['Team'] as $writer)
                        <option value="{{$writer->writer_name}}">{{$writer->writer_name}}</option>
                        @endforeach
    				</select>
    			</div>

                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="writerTL" id="writerTL" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By Writer TL" class="form-select form-select-solid form-select-lg" tabindex="-1">
                        <option value=""></option>
                        @foreach($data['writerTL'] as $tl)
                            <option value="{{ $tl->id }}">{{ $tl->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="SubWriter" id="SubWriter" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By Sub Writer" class="form-select form-select-solid form-select-lg" tabindex="-1">
                        <option value=""></option>
                        @foreach($data['SubWriter'] as $Sub)
                            <option value="{{ $Sub->id }}">{{ $Sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="college"  id="college" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By College Name" class="form-select form-select-solid form-select-lg" tabindex="-1">
                        <option value=""></option>
                        @foreach($data['college'] as $college)
                            <option value="{{$college->college_name}}">{{$college->college_name}}</option>
                        @endforeach  
                    </select>
                </div>
                
                <div class="col-lg-3 fv-row fv-plugins-icon-container mt-2">
                    <select name="extra" id="extra" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By Tech Resit Failed Job" class="form-select form-select-solid form-select-lg" tabindex="-1">
                        <option value=""></option>
                        <!-- Option for Tech -->
                        <option value="tech" {{ old('extra') == 'tech' ? 'selected' : '' }}>Tech</option>
                        <!-- Option for Resit -->
                        <option value="resit" {{ old('extra') == 'resit' ? 'selected' : '' }}>Resit</option>
                        <!-- Option for Failed Job -->
                        <option value="1" {{ old('extra') == '1' ? 'selected' : '' }}>First Class Work</option>
                        <option value="failedjob">Failed Job</option>
                    </select>
                </div>

                

    			</div>

                <div class="row mb-3 additional-filters" style="display:none;">
               

                    <div class="col-md-3 fv-row">
                        <input type="date" name="from_date" id="from_date" class="form-control form-control-solid" placeholder="Search By OrderCode">
                    </div>
                    <div class="col-md-3 fv-row">
                        <input type="date" name="to_date" id="to_date" class="form-control form-control-solid" placeholder="Search By OrderCode">
                    </div>
    
                    <div class="col-lg-3 fv-row fv-plugins-icon-container">
                        <select name="date_status" id="date_status" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By writer Name " class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                            <option value=""></option>
                            <option value="writer_deadline">Writer Deadline</option>
                            <option value="delivery_date">Delivery Date</option>
                            <option value="draft_date">Draft Date</option>
                            <option value="failed_at">Failed Date</option>
    
                        </select>
                    </div>
                    <div class="col-md-3 fv-row">
                        <input type="search" name="secondary_mobile" id="secondary_mobile" class="form-control form-control-solid" placeholder="Search By Module Code">
                    </div>
                    <div class="col-lg-3 fv-row fv-plugins-icon-container">
                        <select name="paper_type" id="paper_type" aria-label="Select a Language" data-control="select2" data-placeholder="Paper Type" class="form-select form-select-solid form-select-lg " data-select2-id="select2-data-13-mh4q" tabindex="-1" >
                            <option value="" ></option>
                            @foreach($data['paper'] as $paperType)
                            <option value="{{$paperType->paper_type}}">{{$paperType->paper_type}}</option>
                            @endforeach
                        </select>
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <div class="col-lg-3 fv-row fv-plugins-icon-container">
                        <select name="semester" id="semester" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By writer Name " class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                            <option value="">semester</option>
                            <option value="I semester">I semester</option>
                            <option value="II semester">II semester</option>
                            <option value="III semester">III semester</option>
                            <option value="IV semester">IV semester</option>
                            <option value="final semester">final semester</option>
                        </select>
                    </div>
                </div>

                
    			<div class="col-lg-12 fv-row fv-plugins-icon-container">
    				<!-- <button type='submit' class="btn btn-sm btn-primary" >Search</button> -->
                    <a  id="resetFiltersBtn" class="btn btn-sm btn-danger">Reset</a>
                    <a  onclick="applyFilters()" class="btn btn-sm btn-primary">Search</a>
                    <button type="button" id="showMoreFilters" class="btn btn-sm btn-success">Show More Filters</button>

    			</div>
    		</div>
    	</form>

		
    </div>
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
    function applyFilters() {
        var $search = $('#search').val();
        var $user = $('#searchInput').val();
        var $uid = $('#selectedValue').val();
        var $status = $('#status').val();
        var $writer = $('#writer').val();
        var $dateStatus = $('#date_status').val();
        var $fromDate = $('#from_date').val();
        var $toDate = $('#to_date').val();
        var $WriterTL = $('#writerTL').val();
        var $SubWriter = $('#SubWriter').val();
        var $college = $('#college').val();
        var $extra = $('#extra').val();
        var $secondaryMobile = $('#secondary_mobile').val();
        var $paper_type = $('#paper_type').val();
        var $semester = $('#semester').val();

        // Store filter values in localStorage
        localStorage.setItem('filters', JSON.stringify({
            search: $search,
            user: $user,
            uid: $uid,
            status: $status,
            writer: $writer,
            dateStatus: $dateStatus,
            fromDate: $fromDate,
            toDate: $toDate,
            WriterTL: $WriterTL,
            SubWriter: $SubWriter,
            college: $college,
            extra: $extra,
            secondaryMobile: $secondaryMobile,
            paper_type: $paper_type,
            semester: $semester
        }));

        // Check if any filter is set
        var filtersExist = $search || $user || $uid || $status || $writer || $dateStatus || $fromDate || $toDate || $WriterTL || $SubWriter || $college || $extra || $secondaryMobile || $paper_type || $semester;

        if (filtersExist) {
            $('.allData').hide();
            $('.searchData').show();
        } else {
            $('.allData').show();
            $('.searchData').hide();
        }

        // Use CSRF token for security
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        // Show loading while waiting for response
        $('#content').html('<div class="loading-container">Please Wait Data Is Loading......<div class="loading-spinner"></div></div>');

        $.ajax({
            type: 'get',
            url: '{{ url('search') }}',
            data: {
                '_token': CSRF_TOKEN,
                'search': $search,
                'user': $user,
                'uid': $uid,
                'status': $status,
                'writer': $writer,
                'date_status': $dateStatus,
                'from_date': $fromDate,
                'to_date': $toDate,
                'WriterTL': $WriterTL,
                'SubWriter': $SubWriter,
                'college': $college,
                'extra': $extra,
                'secondary_mobile': $secondaryMobile,
                'paper_type': $paper_type,
                'semester': $semester
            },
            success: function (data) {
                if (data.message === "No data found") {
                    $('#content').html('<div class="no-data">No data found</div>');
                } else {
                    $('#content').html(data.output);
                }

                var totalWordCount = data.totalWordCount != undefined ? data.totalWordCount : 0;
                var totalOrderCount = data.totalOrderCount != undefined ? data.totalOrderCount : 0;

                $('#orderCount').text('Orders: ' + totalOrderCount);
                $('#wordCount').text('Words: ' + totalWordCount);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }
</script>
    <script type="text/javascript">
    $(document).ready(function () {
        // Function to handle both search and filter
        
        // Function to populate SubWriter dropdown based on the selected Writer TL
        function populateSubwriters() {
            var tlId = $('#writerTL').val();
            var subwriterSelect = $('#SubWriter');

            // Store the currently selected SubWriter value
            var selectedSubWriter = subwriterSelect.val();

            // Clear previous options
            subwriterSelect.empty();

            // Check if a TL is selected
            if (tlId !== '') {
                // Fetch subwriters based on the selected TL
                $.ajax({
                    type: 'get',
                    url: '/fetch-subwriters', // Use the correct URL here
                    data: {
                        'tlId': tlId
                    },
                    success: function (data) {
                        // Populate SubWriter dropdown with fetched data
                        $.each(data, function (key, value) {
                            subwriterSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        // Set the selected option back to its original value
                        subwriterSelect.val(selectedSubWriter);

                        // Trigger the applyFilters function to update data with the selected SubWriter
                        applyFilters();
                    },
                    error: function (data) {
                        console.log('Error fetching SubWriters:', data);
                    }
                });
            }
        }

        // Show More button click event to reveal additional filters
        $('#showMoreFilters').on('click', function () {
            $('.additional-filters').toggle();
        });

        // Use event delegation for dynamically populated elements
        $(document).on('change', '#writerTL', populateSubwriters);
        
       function resetFilters() {
                localStorage.clear(); // Clear all stored filters

                // Clear input values
                $('input[type=search], input[type=date] , input[type=text] , input[type=hidden]').val('');
                $('select').val('').trigger('change');

                // Apply filters
                applyFilters();
            }


            // Click event for the reset button
            $('#resetFiltersBtn').on('click', function() {
                resetFilters();
            });


        // Use the input event for real-time search
        $('#search, #selectedValue, #status, #writer, #date_status, #from_date, #to_date, #writerTL, #SubWriter, #college, #extra, #secondary_mobile, #paper_type').on('click', applyFilters);

        // Retrieve and apply filters from localStorage when the page loads
        var storedFilters = JSON.parse(localStorage.getItem('filters'));
        if (storedFilters) {
            $('#search').val(storedFilters.search);
            $('#selectedValue').val(storedFilters.uid);
            $('#status').val(storedFilters.status);
            $('#writer').val(storedFilters.writer);
            $('#date_status').val(storedFilters.dateStatus);
            $('#from_date').val(storedFilters.fromDate);
            $('#to_date').val(storedFilters.toDate);
            $('#writerTL').val(storedFilters.WriterTL);
            $('#SubWriter').val(storedFilters.SubWriter);
            $('#college').val(storedFilters.college);
            $('#extra').val(storedFilters.extra);
            $('#secondary_mobile').val(storedFilters.secondaryMobile);
            $('#paper_type').val(storedFilters.paper_type).trigger('change');
            $('#paper_type').val(storedFilters.paper_type).trigger('change');

            applyFilters();
        }

        // Check for success message in URL and apply search filter if present
        var successMessage = new URLSearchParams(window.location.search).get('Success');
        if (successMessage) {
            var searchParam = new URLSearchParams(window.location.search).get('search');
            if (searchParam) {
                $('#search').val(searchParam);
                applyFilters();
            }
            alert(successMessage);
        }
    });
</script>









	




	