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
    			     <select  name="status" id="status"  aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" >
                            <option value="" >Select Status</option>
						    <option  value="Not Assign">Not Assign</option>
                            <option value="In Progress" >In Progress</option>
                            <option value="Draft Ready" >Draft Ready</option>
                            <option value="Draft Feedback" >Draft Feedback</option>
                            <option value="Attached to Email (Draft)" >Attached to Email (Draft) </option>
                            <option value="Draft Delivered">Draft Delivered</option>
                            <option value="Complete file Ready">Complete file Ready</option>
                            <option value="Feedback">Feedback</option>
                            <option value="Feedback Delivered">Feedback Delivered</option>
                            <option value="Attached to Email (Complete file)">Attached to Email (Complete file) </option>
                            <option value="Delivered" >Delivered</option>
                            <option value="Hold">Hold</option>
                    </select>
    				<div class="fv-plugins-message-container invalid-feedback"></div>
    			</div>
                    <div class="col-md-3 fv-row">
						<select name="writerTL" id="writerTL" class="form-select form-select-solid" placeholder="">
							<option value="">Select Writer</option>
							<option  value="Not Assigned">Not Assigned</option>
							<option  value="{{Auth::user()->id}}">My Writer</option>
							@foreach($data['writerTL'] as $tl)
								<option  value="{{ $tl->id }}">{{ $tl->name }}</option>
							@endforeach
						</select>
					</div>
						<div class="col-md-3 fv-row" id="subwriterContainer">
							<select  name="SubWriter" id="SubWriter" class="form-select form-select-solid" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true" >
								<option value="">Subwriter</option>
							</select>
							</div>
							<!-- <script>
								// Function to fetch and populate subwriters based on the selected TL
								function populateSubwriters() {
									var tlId = document.getElementById('writerTL').value;
									var subwriterSelect = document.getElementById('SubWriter');
									// Clear previous options
									subwriterSelect.innerHTML = '<option value="">Select Subwriter</option>';
									// Check if a TL is selected
									if (tlId !== '') {
										// Fetch subwriters based on the selected TL
										@foreach($data['SubWriter'] as $subWriter)
											// Check if the subwriter has the same TL id
											if ("{{$subWriter->tl_id}}" === tlId) {
												var option = document.createElement('option');
												option.value = "{{$subWriter->id}}";
												option.text = "{{$subWriter->name}}";
												// Pre-select based on order's subwriter ID
												
												subwriterSelect.appendChild(option);
											}
										@endforeach
									}
								}
								// Attach the function to the onchange event of the TL select box
								document.getElementById('writerTL').addEventListener('change', populateSubwriters);
								// Initial population in case there's a TL selected on page load
								populateSubwriters();
				</script> -->
                
                <div class="col-lg-3 fv-row fv-plugins-icon-container">
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
                <div class="col-md-3 fv-row">
                    <input type="date" name="from_date" id="from_date" class="form-control form-control-solid" placeholder="Search By OrderCode">
                </div>
                <div class="col-md-3 fv-row">
                    <input type="date" name="to_date" id="to_date" class="form-control form-control-solid" placeholder="Search By OrderCode">
                </div>
                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="date_status" id="date_status" aria-label="Select a Timezone" data-control="select2" data-placeholder="Date Type " class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                        <option value=""></option>
                        <option value="writer_deadline">Writer Deadline</option>
                        <option value="draft_date">Draft Date</option>

                    </select>
                </div>

    			</div>

                <div class="row mb-3 additional-filters" style="display:none;">
               

                

                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
                    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
                    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
                    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
                    
                    <!-- Hidden fields to store start and end dates -->
                    <input type="hidden" id="startDate" name="startDate" />
                    <input type="hidden" id="endDate" name="endDate" />

                    <input type="text" id="daterange" name="daterange" class="form-control form-control-solid" placeholder="DD - MM - YYYY Delivery From" />

                    <script>
                        $(function() {
                            $('input[name="daterange"]').daterangepicker({
                                opens: 'left',
                                autoUpdateInput: false,
                                showDropdowns: true,
                                locale: {
                                    format: 'DDMMYYYY'
                                },
                                linkedCalendars: false, // Display two separate calendars
                            }, function(start, end, label) {
                                // Set the values of hidden fields when a date range is selected
                                $('#startDate').val(start.format('YYYY-MM-DD'));
                                $('#endDate').val(end.format('YYYY-MM-DD'));

                                // Callback function to handle date selection
                                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                            });

                            // Listen for the 'apply.daterangepicker' event and update the input field
                            $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' to ' + picker.endDate.format('DD-MM-YYYY'));
                            });

                            // Listen for the 'cancel.daterangepicker' event and clear the input field and hidden fields
                            $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                                $(this).val('');
                                $('#startDate').val('');
                                $('#endDate').val('');
                            });
                        });
                    </script>
                </div>

             
                                <!-- Include jQuery library -->
             

                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <!-- Hidden fields to store start and end dates -->
                    <input type="hidden" id="startDate1" name="startDate1" />
                    <input type="hidden" id="endDate1" name="endDate1" />

                    <input type="text" id="daterange1" name="daterange1" class="form-control form-control-solid" placeholder="DD - MM - YYYY Delivery UpTo" />

                    <script>
                        $(function () {
                            $('input[name="daterange1"]').daterangepicker({
                                opens: 'left',
                                autoUpdateInput: false,
                                showDropdowns: true,
                                startDate: moment().subtract(1, 'days'),
                                endDate: moment(),
                                locale: {
                                    format: 'DD-MM-YYYY',
                                },
                            }, function (start, end, label) {
                                // Set the values of hidden fields when a date range is selected
                                $('#startDate1').val(start.format('YYYY-MM-DD'));
                                $('#endDate1').val(end.format('YYYY-MM-DD'));

                                // Callback function to handle date selection
                                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                            });

                            // Listen for the 'apply.daterangepicker' event and update the input field
                            $('input[name="daterange1"]').on('apply.daterangepicker', function (ev, picker) {
                                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' to ' + picker.endDate.format('DD-MM-YYYY'));
                                // Trigger the applyFilters function to update data with the selected date range
                                applyFilters();
                            });

                            // Listen for the 'cancel.daterangepicker' event and clear the input field and hidden fields
                            $('input[name="daterange1"]').on('cancel.daterangepicker', function () {
                                $(this).val('');
                                $('#startDate1').val('');
                                $('#endDate1').val('');
                                // Trigger the applyFilters function to update data without a date range
                                applyFilters();
                            });
                        });
                    </script>
                </div>
                <div class="col-3">
                 
                </div>

                </div>
                


                
    			<div class="col-lg-12 fv-row fv-plugins-icon-container">
                    <a  id="resetFiltersBtn" class="btn btn-sm btn-danger">Reset</a>
                    <a  id="applyButton" class="btn btn-sm btn-primary">Search</a> 
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
    $(document).ready(function () {
        function applyFilters() {
            var $search = $('#search').val();
            var $daterange = $('#daterange').val();
            var $daterange1 = $('#daterange1').val(); // Added daterange1 variable
            var $status = $('#status').val();
            var $writer = $('#writer').val();
            var $dateStatus = $('#date_status').val();
            var $fromDate = $('#from_date').val();
            var $toDate = $('#to_date').val();
            var $WriterTL = $('#writerTL').val();
            var $SubWriter = $('#SubWriter').val();
            var $college = $('#college').val();
            var $extra = $('#extra').val();

            sessionStorage.setItem('filters', JSON.stringify({
                search: $search,
                daterange: $daterange,
                daterange1: $daterange1, // Stored daterange1 value
                status: $status,
                writer: $writer,
                dateStatus: $dateStatus,
                fromDate: $fromDate,
                toDate: $toDate,
                WriterTL: $WriterTL,
                SubWriter: $SubWriter,
                college: $college,
                extra: $extra
            }));

            var filtersExist = $search || $daterange || $daterange1 || $status || $writer || $dateStatus || $fromDate || $toDate || $WriterTL || $SubWriter || $college || $extra;

            if (filtersExist) {
                $('.allData').hide();
                $('.searchData').show();
            } else {
                $('.allData').show();
                $('.searchData').hide();
            }

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $('#content').html('<div class="loading-container">Please Wait Data Is Loading......<div class="loading-spinner"></div></div>');

            $.ajax({
                type: 'get',
                url: '{{ url('search-writer') }}',
                data: {
                    '_token': CSRF_TOKEN,
                    'search': $search,
                    'daterange': $daterange,
                    'daterange1': $daterange1, // Added daterange1 to AJAX data
                    'status': $status,
                    'writer': $writer,
                    'date_status': $dateStatus,
                    'from_date': $fromDate,
                    'to_date': $toDate,
                    'WriterTL': $WriterTL,
                    'SubWriter': $SubWriter,
                    'college': $college,
                    'extra': $extra
                },
                success: function (data) {
                    if (data.message === "No data found") {
                        $('#content').html('<div class="no-data">No data found</div>');
                    } else {
                        $('#content').html(data.output);
                    }

                    // Access the totalWordCount and totalOrderCount from the data object
                    var totalWordCount = data.totalWordCount !== undefined ? data.totalWordCount : 0;
                    var totalOrderCount = data.totalOrderCount !== undefined ? data.totalOrderCount : 0;

                    // Update the text content of elements with the counts
                    $('#orderCount').text('Orders: ' + totalOrderCount);
                    $('#wordCount').text('Words: ' + totalWordCount);
                },
                error: function (data) {
                    $('#content').html('<div class="error">Not Able fetching data</div>');
                }
            });
        }

        function populateSubwriters() {
            var tlId = $('#writerTL').val();
            var subwriterSelect = $('#SubWriter');
            var selectedSubWriter = subwriterSelect.val();
            subwriterSelect.empty();

            if (tlId !== '') {
                $.ajax({
                    type: 'get',
                    url: '/fetch-subwriters',
                    data: {
                        'tlId': tlId
                    },
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
        }
        // function populateSubwriters() {
        //     var tlId = $('#writerTL').val();
        //     var subwriterSelect = $('#SubWriter');
        //     var selectedSubWriter = subwriterSelect.val();
        //     subwriterSelect.empty();

        //     if (tlId !== '') {
        //         // Pre-append the 'Not Assigned' option manually
        //         subwriterSelect.append('<option value="">Select Subwriter</option>');
        //         subwriterSelect.append('<option value="Not Assigned">Not Assigned</option>');

        //         $.ajax({
        //             type: 'get',
        //             url: '/fetch-subwriters',
        //             data: {
        //                 'tlId': tlId
        //             },
        //             success: function (data) {
        //                 $.each(data, function (key, value) {
        //                     subwriterSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
        //                 });

        //                 // Set the selected value after appending all options
        //                 subwriterSelect.val(selectedSubWriter);
        //                 applyFilters();
        //             },
        //             error: function (data) {
        //                 console.log('Error fetching SubWriters:', data);
        //             }
        //         });
        //     }
        // }


        $('#showMoreFilters').on('click', function () {
            $('.additional-filters').toggle();
        });

        $(document).on('change', '#writerTL', populateSubwriters);

        function resetFilters() {
            sessionStorage.clear();
            $('input[type=search], input[type=date] , input[type=text]').val('');
            $('select').val('').trigger('change');
            applyFilters();
        }

        $('#resetFiltersBtn').on('click', function () {
            resetFilters();
        });

        $('#applyButton').on('click', function () {
        applyFilters();
    });



        var storedFilters = JSON.parse(sessionStorage.getItem('filters'));
        if (storedFilters) {
            $.each(storedFilters, function(key, value) {
                $('#' + key).val(value);
            });
            applyFilters();
        }

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
