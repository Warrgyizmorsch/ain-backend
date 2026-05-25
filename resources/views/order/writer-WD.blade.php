@extends('layouts.app')
@section('content')
<style>
	.shadow-sm {
    display: none;
}
.text-gray-700 {
    margin-top: revert;
}
</style>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div id="kt_content_container" class="">
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Writer
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need</small>
                    </div>
                    
                </div>
	        </div>

			<div class="col-xl-12">
				<!-- include('order.section.fileter') -->
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
                                    <input type="date" name="from_date" id="from_date" class="form-control form-control-solid" placeholder="From">
                                </div>
                                <div class="col-md-3 fv-row">
                                    <input type="date" name="to_date" id="to_date" class="form-control form-control-solid" placeholder="To">
                                </div>
                                <!-- <div class="col-md-3 fv-row">
                                    <select name="writerTL" id="writerTL" class="form-select form-select-solid" placeholder="">
                                        <option value="">Select Writer</option>
                                        <option  value="Not Assigned">Not Assigned</option>
                                        
                                        @foreach($data['writerTL'] as $tl)
                                            <option  value="{{ $tl->id }}">{{ $tl->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 fv-row">
                                    <select name="SubWriter" id="SubWriter" class="form-select form-select-solid" placeholder="">
                                        <option value="">Select SubWriter</option>
                                        <option  value="Not Assigned">Not Assigned</option>
                                        
                                        @foreach($data['SubWriter'] as $sw)
                                            <option  value="{{ $sw->id }}">{{ $sw->name }}</option>
                                        @endforeach
                                    </select>
                                </div> -->
                                 <!-- -------------- -->
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select name="writerTL" id="writerTL" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By writer Name" class="form-select form-select-solid form-select-lg" tabindex="-1">
                                    <option value="">Select Writer</option>
                                    <!-- <option  value="Not Assigned">Not Assigned</option> -->
                                    @foreach($data['writerTL'] as $tl)
                                        <option value="{{ $tl->id }}">{{ $tl->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select name="SubWriter" id="SubWriter" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By Sub Writer" class="form-select form-select-solid form-select-lg" tabindex="-1">
                                    <option value="">Select SubWriter</option>
                                    <!-- <option  value="Not Assigned">Not Assigned</option> -->
                                    @foreach($data['SubWriter'] as $Sub)
                                        <option value="{{ $Sub->id }}">{{ $Sub->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                            <script>
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
                                            },
                                            error: function (data) {
                                                console.log('Error fetching SubWriters:', data);
                                            }
                                        });
                                    } else {
                                        // If no TL is selected, show all sub-writers
                                        subwriterSelect.append('<option value=""></option>');
                                        @foreach($data['SubWriter'] as $Sub)
                                            subwriterSelect.append('<option value="{{ $Sub->id }}">{{ $Sub->name }}</option>');
                                        @endforeach
                                    }
                                }

                                // Use event delegation for dynamically populated elements
                                $(document).on('change', '#writerTL', populateSubwriters);

                                // Populate SubWriter dropdown on page load
                                populateSubwriters();
                            });

                            </script>

                            

                           <!-- ----------------------- -->
                                <div class="col-lg-12 fv-row fv-plugins-icon-container mt-2">
                                    <a  id="resetFiltersBtn" class="btn btn-sm btn-danger">Reset</a>
                                    <a  id="applySearch" class="btn btn-sm btn-primary">Search</a> 
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
            
			<div class="card card-xl-stretch  mb-xl-">
				
				<div class="card-body py-3">
					<div class="card-header border-0 pt-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1">Orders
                                <span id="totalOrders" class="text-muted fs-4 fw-bold my-1 ms-1"></span>
                                <span id="totalWords" class="text-muted fs-4 fw-bold my-1 ms-1"></span>
                            </span>
						</h3>
                        <a onclick="orderExport()" style="height: fit-content;" class="btn btn-sm btn-danger">Export</a>
					</div>
					<div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                <thead class="p-2">
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th class="min-w-55px text-center">SR</th>
                                        <th class="min-w-125px">Order Code</th>
                                        <th class="min-w-150px">Date</th>
                                        <th class="min-w-250px">Title</th>
                                        <th class="min-w-150px text-start">Word</th>
                                        <th class="min-w-150px text-start">writer name</th>
                                        <th class="min-w-150px text-start">subwriter name</th>
                                        <!-- <th class="min-w-50px">DateU</th> -->
                                        <!-- <th class="min-w-30px">Writer</th>
                                        <th class="min-w-30px">SubWriter</th> -->
                                    </tr>
                                </thead>
                                <tbody id="ordersTableBody"> <!-- Add id to tbody -->
                                </tbody>
                            </table>
                        </div>

					</div>

				</div>
			</div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script>
        $(document).ready(function() {
            $('#writerTL').change(function() {
                var tlId = $(this).val();
                console.log('Selected TL ID:', tlId); // Log the selected TL ID

                // Make AJAX request to get orders for the selected TL
                $.ajax({
                    url: '{{ route('order.writer2') }}',
                    type: 'POST',
                    data: {
                        tlId: tlId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        var orders = response.orders;
                        var tbody = $('#ordersTableBody'); // Select tbody by its id
                        tbody.empty();

                        // Populate table with orders
                        $.each(orders, function(index, order) {
                            var row = '<tr>' +
                                '<td class="min-w-55px text-center">' + (index + 1) + '</td>' +
                                '<td>' + order.order_id + '</td>' +
                                '<td>' + order.date + '</td>' +
                                '<td>' + order.title + '</td>' +
                                '<td>' + order.pages + '</td>' +
                                // '<td>' + order.writer_ud + '</td>' +
                                // '<td>' + order.wid + '</td>' +
                                // '<td>' + order.swid + '</td>' +
                                '</tr>';
                            tbody.append(row);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error); // Log AJAX error
                    }
                });
            });
        });
    </script> -->
<script>
    $(document).ready(function() {
        // Function to fetch orders based on filter criteria
        function fetchOrders() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var tlId = $('#writerTL').val();
            var swId = $('#SubWriter').val();

            // Make AJAX request to fetch orders
            $.ajax({
                url: '{{ route('order.writer2') }}',
                type: 'POST',
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    tlId: tlId,
                    swId: swId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var orders = response.orders;
                    var tbody = $('#ordersTableBody');
                    var totalWords = response.total_word_count; // Total number of orders
                    var totalOrders = response.total_orders; // Total number of orders
                    
                    var totalWordsSpan = $('#totalWords'); // Span element for total order count
                    var totalOrdersSpan = $('#totalOrders'); // Span element for total order count

                    // Update total order count in the header
                    totalWordsSpan.text('Total Words: ' + totalWords);
                    totalOrdersSpan.text('Total Orders: ' + totalOrders);
                    tbody.empty();

                    // Populate table with orders
                    $.each(orders, function(index, order) {
                        var row = '<tr>' +
                            '<td class="min-w-55px text-center">' + (index + 1) + '</td>' +
                            '<td>' + order.order_id + '</td>' +
                            '<td>' + order.date + '</td>' +
                            '<td>' + order.title + '</td>' +
                            '<td>' + order.pages + '</td>' +
                            '<td>' + order.writer_name + '</td>' +
                            '<td>' + order.sub_writer_names + '</td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }

        // Trigger fetchOrders function on search button click
        $('#applySearch').click(function(e) {
            e.preventDefault();
            fetchOrders();
        });

        // Reset filter values and refresh the page on reset button click
        $('#resetFiltersBtn').click(function(e) {
            e.preventDefault();
            $('#from_date').val('');
            $('#to_date').val('');
            $('#writerTL').val('');
            location.reload(); // Refresh the page
        });
    });
</script>

<script>
		function orderExport() {
			// Retrieve filter parameters
			var writerTL = $('select[name="writerTL"]').val();
			var SubWriter = $('select[name="SubWriter"]').val();
			var from_date = $('input[name="from_date"]').val();
			var to_date = $('input[name="to_date"]').val();
			
			// Use CSRF token for security
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			// Send AJAX request to export endpoint
			$.ajax({
				type: 'get',
				url: '{{ route('export.WD') }}',
				data: {
					_token: CSRF_TOKEN,
					writerTL: writerTL,
                    SubWriter: SubWriter,
					from_date: from_date,
					to_date: to_date
				},
				success: function (data) {
					// On success, trigger file download
					var blob = new Blob([data], { type: 'text/csv' });
					var link = document.createElement('a');
					link.href = window.URL.createObjectURL(blob);

					// Generate file name with current timestamp
					var filename = 'Writer_orders_' + new Date().toISOString().slice(0, 19).replace(/[-T:/]/g, '') + '.csv';
					link.download = filename;

					document.body.appendChild(link);
					link.click();
					document.body.removeChild(link);
				},
				error: function (data) {
					console.log('Error:', data);
				}
			});
		}
	</script>



  @endsection
  