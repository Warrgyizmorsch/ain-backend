<div class="card card-xxl-stretch mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
    	<h3 class="card-title align-items-start flex-column">
    		<span class="card-label fw-bolder fs-3 mb-1">Filter</span>
    	</h3>
    </div>
    <div class="card-body py-3">
    <form action="">
    		<div class="row mb-3">
                <div class="col-md-3 fv-row">
                	 <input type="search"  name="search" id="search" class="form-control form-control-solid" placeholder="Serach By OrderCode or Title" >
                </div>
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
                    <div id="searchResults"></div>
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
                                        $('#searchResults').html(results);
                                    }
                                });
                            } else {
                                $('#searchResults').empty();
                            }
                        });

                        // Handle click on search result
                        $('#searchDatalist').on('input', function() {
                            var selectedEmail = $(this).val();
                            $('#selectedValue').val(selectedEmail);
                        });
                    });
                </script>


               
                           
    			<div class="col-lg-3 fv-row fv-plugins-icon-container">
    				<select name="status" id="status" aria-label="Select a Language" data-control="select2" data-placeholder="Status" class="form-select form-select-solid form-select-lg " data-select2-id="select2-data-13-mh4q" tabindex="-1" >
    					<option value="" ></option>
                        <option value="">reset</option>
                        @foreach($data['Status'] as $Status)
                           <option value="{{$Status->status}}">{{$Status->status}}</option>
                        @endforeach
    				</select>
    				<div class="fv-plugins-message-container invalid-feedback"></div>
    			</div>
               
                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="college"  id="college" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By College Name" class="form-select form-select-solid form-select-lg" tabindex="-1">
                        <option value=""></option>
                        <option value="">reset</option>
                        @foreach($data['college'] as $college)
                            <option value="{{$college->college_name}}">{{$college->college_name}}</option>
                        @endforeach  
                    </select>
                </div>
                
                @if(Auth::user()->role_id == 9 || Auth::user()->role_id == 4)
                    <div class="col-lg-3 fv-row fv-plugins-icon-container">
                        <select name="writer" id="writer" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By writer Name " class="form-select form-select-solid form-select-lg " data-select2-id="select2-data-16-79699" tabindex="-1" >
                            <option value="" >select</option>
                            <option value="Not Assign">Not Assign</option>

                            @foreach($data['Team'] as $writer)
                            <option value="{{$writer->writer_name}}">{{$writer->writer_name}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                

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
                            <option value="">reset</option>
                            <option value="writer_deadline">Writer Deadline</option>
                            <option value="delivery_date">Delivery Date</option>
                            <option value="draft_date">Draft Date</option>
                        </select>
                    </div>
                    <div class="col-lg-3 fv-row fv-plugins-icon-container mt-2">
                        <select name="extra" id="extra" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By Tech Resit Failed Job" class="form-select form-select-solid form-select-lg" tabindex="-1">
                            <option value=""></option>
                            <option value="">reset</option>
                            <!-- Option for Tech -->
                            <option value="tech" {{ old('extra') == 'tech' ? 'selected' : '' }}>Tech</option>
                            <!-- Option for Resit -->
                            <option value="resit" {{ old('extra') == 'resit' ? 'selected' : '' }}>Resit</option>
                            <!-- Option for Failed Job -->
                            <option value="1" {{ old('extra') == '1' ? 'selected' : '' }}>First Class Work</option>
                            <option value="failedjob">Failed Job</option>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
    // Debounce utility function
    function debounce(func, delay) {
        let timer;
        return function () {
            const context = this;
            const args = arguments;
            clearTimeout(timer);
            timer = setTimeout(() => func.apply(context, args), delay);
        };
    }

    function filtersChanged(current, previous) {
        return JSON.stringify(current) !== JSON.stringify(previous);
    }

    const isPageLoad = performance.getEntriesByType("navigation")[0]?.type === "reload";

    function applyFilters(skipComparison = false) {
        const filters = {
            search: $('#search').val(),
            uid: $('#selectedValue').val(),
            status: $('#status').val(),
            writer: $('#writer').val(),
            date_status: $('#date_status').val(),
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val(),
            WriterTL: $('#writerTL').val(),
            SubWriter: $('#SubWriter').val(),
            college: $('#college').val(),
            extra: $('#extra').val(),
            secondary_mobile: $('#secondary_mobile').val(),
            paper_type: $('#paper_type').val(),
            semester: $('#semester').val()
        };

        const previousFilters = JSON.parse(localStorage.getItem('filters'));

        if (!skipComparison && previousFilters && !filtersChanged(filters, previousFilters)) {
            return;
        }

        localStorage.setItem('filters', JSON.stringify(filters));

        const filtersExist = Object.values(filters).some(value => value && value.trim() !== '');

        if (!filtersExist) {
            $('.allData').show();
            $('.searchData').hide();
            $('#content').html('');
            return;
        }

        $('.allData').hide();
        $('.searchData').show();

        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $('#content').html('<div class="loading-container">Please Wait Data Is Loading......<div class="loading-spinner"></div></div>');

        $.ajax({
            type: 'GET',
            url: '{{ url('search') }}',
            data: {
                '_token': CSRF_TOKEN,
                search: filters.search,
                uid: filters.uid,
                status: filters.status,
                writer: filters.writer,
                date_status: filters.date_status,
                from_date: filters.from_date,
                to_date: filters.to_date,
                WriterTL: filters.WriterTL,
                SubWriter: filters.SubWriter,
                college: filters.college,
                extra: filters.extra,
                secondary_mobile: filters.secondary_mobile,
                paper_type: filters.paper_type,
                semester: filters.semester
            },
            success: function (data) {
                if (data && data.output) {
                    $('#content').html(data.output);
                } else {
                    $('#content').html('<div class="no-data">No Data Found</div>');
                }
            },
            error: function () {
                $('#content').html('<div class="error">An error occurred while fetching data. Please try again later.</div>');
            }
        });
    }

    const debouncedApplyFilters = debounce(applyFilters, 500);

    function resetFilters() {
        localStorage.removeItem('filters');
        $('#searchInput, #search, #selectedValue, #status, #writer, #date_status, #from_date, #to_date, #writerTL, #SubWriter, #college, #extra, #secondary_mobile, #paper_type, #semester').val('');
        $('select').trigger('change');
        $('.allData').show();
        $('.searchData').hide();
        $('#content').html('');
    }

    $(document).ready(function () {
        $('#resetFiltersBtn').on('click', function () {
            resetFilters();
        });

        const storedFilters = JSON.parse(localStorage.getItem('filters'));
        if (storedFilters) {
            $('#search').val(storedFilters.search || '');
            $('#selectedValue').val(storedFilters.uid || '');
            $('#status').val(storedFilters.status || '');
            $('#writer').val(storedFilters.writer || '');
            $('#date_status').val(storedFilters.date_status || '');
            $('#from_date').val(storedFilters.from_date || '');
            $('#to_date').val(storedFilters.to_date || '');
            $('#writerTL').val(storedFilters.WriterTL || '');
            $('#SubWriter').val(storedFilters.SubWriter || '');
            $('#college').val(storedFilters.college || '');
            $('#extra').val(storedFilters.extra || '');
            $('#secondary_mobile').val(storedFilters.secondary_mobile || '');
            $('#paper_type').val(storedFilters.paper_type || '');
            $('#semester').val(storedFilters.semester || '');

            applyFilters(true); // force apply on load
        }

        $('#showMoreFilters').on('click', function () {
            $('.additional-filters').toggle();
        });

        $(document).on('change', '#writerTL', function () {
            var tlId = $(this).val();
            var subwriterSelect = $('#SubWriter');
            var selectedSubWriter = subwriterSelect.val();

            subwriterSelect.empty();

            if (tlId !== '') {
                $.ajax({
                    type: 'get',
                    url: '/fetch-subwriters',
                    data: { 'tlId': tlId },
                    success: function (data) {
                        $.each(data, function (key, value) {
                            subwriterSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        subwriterSelect.val(selectedSubWriter);
                        applyFilters();
                    },
                    error: function (data) {
                        console.log('Error fetching SubWriters:', data);
                    }
                });
            }
        });

        var successMessage = new URLSearchParams(window.location.search).get('Success');
        if (successMessage) {
            var searchParam = new URLSearchParams(window.location.search).get('search');
            if (searchParam) {
                $('#search').val(searchParam);
                applyFilters(true);
            }
            alert(successMessage);
        }
    });
</script>










	




	