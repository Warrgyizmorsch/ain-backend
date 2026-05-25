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
    					<option value="" data-select2-id="select2-data-18-e9lhs"></option>
                        @foreach($data['Team'] as $writer)
                        <option value="{{$writer->writer_name}}">{{$writer->writer_name}}</option>
                        @endforeach
    				</select>
    			</div>
                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                        <select name="extra" id="extra" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By Tech Resit Failed Job" class="form-select form-select-solid form-select-lg" tabindex="-1">
                            <option value=""></option>
                            <!-- Option for Tech -->
                            <option value="tech">Tech</option>
                            <!-- Option for Resit -->
                            <option value="resit">Resit</option>
                            <!-- Option for Failed Job -->
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
                    </select>
                </div>
                
                </div>
                


                
    			<div class="col-lg-12 fv-row fv-plugins-icon-container">
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
        function applyFilters() {
            var $search = $('#search').val();
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

            // Store filter values in localStorage
            localStorage.setItem('filters', JSON.stringify({
                search: $search,
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
                secondaryMobile: $secondaryMobile
            }));

            // Check if any filter is set
            var filtersExist = $search || $uid || $status || $writer || $dateStatus || $fromDate || $toDate || $WriterTL || $SubWriter || $college || $extra || $secondaryMobile;

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
                    'secondary_mobile': $secondaryMobile
                },
                success: function (data) {
                    console.log(data);
                    $('#content').html(data.output);
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
        $('#search, #selectedValue, #status, #writer, #date_status, #from_date, #to_date, #writerTL, #SubWriter, #college, #extra, #secondary_mobile').on('click', applyFilters);

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









	




	