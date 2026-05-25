@extends('layouts.app')

@section('content')
   
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Order Edit
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need</small>
            </div>
            <div class="d-flex align-items-center py-1">
                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app" id="kt_toolbar_primary_button">Create</a>
            </div>
        </div>
	</div>
    <div id="kt_content_container" class="container-xxl">
        <div class="modal-content rounded">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                
            <form id="kt_modal_new_target_form" class="form" action="{{ route('order.update', ['id' => $order->id]) }}" method="POST">
                @csrf
                @method('PUT')
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">Order View</h1>
                        @php
                            $statusColors = [
                                'Delivered' => 'green',
                                'Pending' => 'pink',
                                'Hold Work' => '#f1416c',
                                'In Progress' => '#7239ea',
                                'Completed' => '#eaea00',
                                'Feedback' => 'black',
                                'Feedback Delivered' => 'black',
                                'Cancelled' => '#f1416c',
                                'Draft Ready' => '#eaea00',
                                'Draft Delivered' => 'green',
                                'Other' => '#44f2e4',
                                'initiated' => 'pink',
                                // Add more statuses and their colors as needed
                            ];
                            @endphp

                            <div class="text-muted fw-bold fs-5">
                                <a href="/edit.{{$order->id}}">{{$order->order_id}}</a>
                                <a href="#" class="fw-bolder link-primary"> 
                                    <span class="label label-primary" style="background-color: {{ $statusColors[$order->projectstatus] ?? 'blue' }}; color: white; padding: 3px 10px 2px 10px; border-radius: 30px;">
                                        {{$order->projectstatus}}
                                    </span>
                                </a>
                            </div>
                    </div>
                    @if($order->user)
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-bold mb-2">User Name</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" 
                                value="{{ $order->user->name ?? '' }}" name="user_name">
                        </div>

                        <div class="col-md-6 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Email</label>
                             <input type="text" readonly class="form-control form-control-solid" placeholder="" value="{{$order->user->email}}" name="email" id="emailInput" >
                            <input type="hidden" class="form-control form-control-solid" placeholder="" value="" name="id" id="id" >                       
                            </div>
                    </div>
                    <div class="row g-9 mb-8 text-start order-container" data-order-id="{{$order->user->id}}">
                        <div class="col-md-2 fv-row">
                            <label class="fs-6 fw-bold mb-2">Country Code</label>
                            <input type="text" readonly class="form-control form-control-solid" placeholder="" value="{{$order->user->countrycode}}" name="country_code" id="country_primary">
                        </div>
                        <div class="col-md-3 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Mobile Number</label>
                            <input type="text" readonly class="form-control form-control-solid" placeholder="" value="{{$order->user->mobile_no}}" name="mobile" id="primary">
                        </div>
                        <div class="col-md-2 fv-row d-flex align-items-end justify-content-center">
                            <!-- Button with double-sided arrow icon -->
                            <button type="button" class="btn btn-primary swapButton" id="swapButton{{$order->id}}">
                                <i class="fas fa-exchange-alt"></i> <!-- Font Awesome double-sided arrow icon -->
                            </button>
                        </div>
                        <div class="col-md-2 fv-row">
                            <label class="fs-6 fw-bold mb-2">Country Code</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" value="{{$order->user->countrycode2}}" name="country_code2">
                        </div>
                        <div class="col-md-3 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Mobile Number-2</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" value="{{$order->user->mobile_no2}}" name="mobile2">
                        </div>
                    </div>
                    @else
                        User Not Available
                    @endif


                    <script>
                        document.querySelectorAll('#swapButton{{$order->id}}').forEach(function(button) {
                            button.addEventListener('click', function () {
                                var orderContainer = button.closest('.order-container');
                                var orderId = orderContainer.getAttribute('data-order-id');
                                
                                fetch('/swap-user-data', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                    body: JSON.stringify({ user_id: orderId }),
                                })
                                .then(response => response.json())
                                .then(data => handleResponse(data, orderContainer))
                                .catch(error => console.error('Error:', error));
                            });
                            
                            function handleResponse(data, orderContainer) {
                                

                                if (data.success) {
                                    orderContainer.querySelector('[name="country_code"]').value = data.mobile1.country_code;
                                    orderContainer.querySelector('[name="mobile"]').value = data.mobile1.mobile_number;
                                    orderContainer.querySelector('[name="country_code2"]').value = data.mobile2.country_code;
                                    orderContainer.querySelector('[name="mobile2"]').value = data.mobile2.mobile_number;
                                    
                                    // Add any additional logic based on the response
                                }
                            }
                        });
                    </script>



                    
                    <div class="row g-9 mb-8 text-start">


                        <div class="col-md-4 mx-auto fv-row">
                            <label class=" fs-6 fw-bold mb-2">Module Code</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$order->module_code}}" name="module_code">
                        </div>
                        <div class="col-md-8 mx-auto fv-row">
                            <label class=" fs-6 fw-bold mb-2">Project Title</label>
                            <input type="text" required class="form-control form-control-solid" placeholder="" value="{{$order->title}}" name="title">
                        </div>

                        <div class="col-md-8 fv-row">
                            <input  type="text" list="searchDatalist" id="searchInput" name="user" class="form-control form-control-solid" placeholder="Search..." autocomplete="off">
                            <!-- Datalist for displaying search results -->
                            <datalist id="searchDatalist"></datalist>
                            <!-- Container to display search results -->
                            <div id="searchResultss"></div>
                            <!-- Hidden field to store the selected value -->
                            <input type="hidden" id="selectedValue"  name="uid">
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


                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Order Date</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}" name="order_date" onchange="showSelectedDate(this)">
                        </div>
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Writer Deadline</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->writer_deadline)->format('Y-m-d') }}" name="writer_deadline" onchange="showSelectedDate(this)">
                        </div>
                        <div class="col-md-4 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Writer Deadline Time</label>
                            @if($order->writer_deadline_time != '')
                            <input type="time"  class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->writer_deadline_time)->format('H:i') }}" name="writer_deadline_time" onchange="showSelectedTime(this)">
                            @else
                            <input type="time" class="form-control form-control-solid" placeholder="" value="" name="writer_deadline_time" onchange="showSelectedTime(this)">
                            @endif
                        </div>
                        <div class="col-md-4 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Delivery Date</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}" name="delivery_date">
                        </div>

                        <script>
                            // Function to handle delivery date change event
                            function validateDeliveryDate() {
                                // Get the value of the order date from PHP variable
                                const orderDate = new Date('{{ $order->order_date }}');
                                
                                // Get the delivery date input element
                                const deliveryDateInput = document.getElementsByName('delivery_date')[0];
                                
                                // Parse the selected delivery date
                                const selectedDeliveryDate = new Date(deliveryDateInput.value);

                                // Compare selected delivery date with order date
                                if (selectedDeliveryDate < orderDate) {
                                    // Display SweetAlert if selected delivery date is before order date
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Invalid Delivery Date',
                                        text: 'Delivery date cannot be before the order date.',
                                    });

                                    // Clear the value of the delivery date input
                                    deliveryDateInput.value = '{{ \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}';
                                }
                            }

                            // Add event listener to delivery date input
                            document.getElementsByName('delivery_date')[0].addEventListener('change', validateDeliveryDate);
                        </script>
                        <div class="col-md-4 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Delivery Time</label>
                            @if($order->delivery_time != '')
                            <input type="time"  class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->delivery_time)->format('H:i') }}" name="delivery_time" onchange="showSelectedTime(this)">
                            @else
                            <input type="time" class="form-control form-control-solid" placeholder="" value="" name="delivery_time" onchange="showSelectedTime(this)">
                            @endif
                        </div>
                    </div>

                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-4 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Amount</label>
                        <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$order->amount}}  " name="amount">
                           
                        </div>
                        <div class="col-md-4 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Received Amount</label>
                            <input type="text" readonly class="form-control form-control-solid" placeholder="" value="{{$order->received_amount}} " name="r_amount">
                        </div>
                        <div class="col-md-4 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Project Status</label>
                            <select name="status" id="status-select" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
						        <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                @foreach($data['Status'] as $status)
                                <option <?php if ( $order['projectstatus'] == $status['status']) {echo "selected";} ?> value="{{$status->status}}">{{$status->status}}</option>
                                @endforeach
                            </select>                           
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                

                                // SweetAlert for status change
                                var statusSelect = document.getElementById('status-select');
                                var initialStatus = statusSelect.value;
                                var orderAmount = {{ (int)$order->amount }};
                                var receivedAmount = {{ (int)$order->received_amount }};
                                
                                statusSelect.addEventListener('change', function() {
                                    var selectedStatus = this.value;
                                    if (selectedStatus === 'Delivered' && orderAmount - receivedAmount !== 0) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Attention!',
                                            text: 'Order cannot be marked as completed if there is any due payment remaining.'
                                        }).then((result) => {
                                            // Revert to the initial status if the user closes the alert
                                            if (result.isDismissed) {
                                                statusSelect.value = initialStatus;
                                            }else {
                                                statusSelect.value = initialStatus;                        }
                                        });
                                    }
                                });
                            });
                        </script>
                       
                    </div>

                    


                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-4 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Word</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$order->pages}}" name="word">
                        </div>
                          
                        <div class="col-md-4 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Writer Name</label>
                            <select name="writer_name" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
						        <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                @foreach($data['Team'] as $writer)
                                <option <?php if ( $order['writer_name'] == $writer['writer_name']) {echo "selected";} ?> value="{{$writer->writer_name}}">{{$writer->writer_name}}</option>
                                @endforeach
                            </select>                    
                        </div>
                        <div class="col-md-4 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">College Name</label>
                            <select name="college_name" id="cld" aria-label="Select a Timezone" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                @foreach($data['college'] as $college)
                                    <option {{ $order['college_name'] == $college['college_name'] ? 'selected' : '' }} value="{{ $college->college_name }}">{{ $college->college_name }}</option>
                                @endforeach
                                <option value="add_college" data-select2-id="select2-data-18-e9lh12">Add College</option>
                            </select>
                        </div>

                        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                        <script>
                            // Shorthand for $(document).ready()
                            $(function () {
                                // Use event delegation to handle the change event for #cld
                                $(document).on('change', '#cld', function () {
                                    // Check if the selected option is "Add College"
                                    if ($(this).val() === 'add_college') {
                                        // Open a new tab and redirect to the /college page
                                        window.open('/college', '_blank');
                                    }
                                });
                            });
                        </script>
                    </div>
                    
                    

                    <div class="row g-9 mb-8 text-start">
                       <div class="col-md-4 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Type Of Paper</label>
                            <select id="typeOfPaper" name="paper" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
						        <option value="" data-select2-id="select2-data-18-e9lh12">Not Selected</option>
                                @foreach($data['paper'] as $paper)
                                    <option <?php if ( $order['typeofpaper'] == $paper['paper_type']) {echo "selected";} ?> value="{{$paper->paper_type}}">{{$paper->paper_type}}</option>
                                @endforeach   
                            </select>                         
                        </div>
                       
                        <div class="col-md-4 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Chapter</label>
                            <select id="chapter" name="chapter"aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                <option value=" " <?php if ( $order['chapter'] == ' ') {echo "selected";} ?>> </option>
                                <option value="Chapter 1:  Introduction" <?php if ($order['chapter'] == 'Chapter 1:  Introduction') {echo "selected";} ?>>Chapter 1:  Introduction</option>
                                <option value="Chapter 2: Litreature Review" <?php if ($order['chapter'] == 'Chapter 2: Litreature Review') {echo "selected";} ?>>Chapter 2: Litreature Review</option>
                                <option value="Chapter 3: Methedology" <?php if ($order['chapter'] == 'Chapter 3: Methedology') {echo "selected";} ?>>Chapter 3: Methedology</option>
                                <option value="Chapter 4: Data Analysis" <?php if ($order['chapter'] == 'Chapter 4: Data Analysis') {echo "selected";} ?>>Chapter 4: Data Analysis</option>
                                <option value="Chapter 5: Conclusion & Recommendation" <?php if ($order['chapter']== 'Chapter 5: Conclusion & Recommendation') {echo "selected";} ?>>Chapter 5: Conclusion & Recommendation</option>
                            </select>    
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const typeOfPaperDropdown = document.getElementById('typeOfPaper');
                                const chapterDropdown = document.getElementById('chapter');

                                // Function to toggle chapter dropdown based on selected type of paper
                                function toggleChapterDropdown() {
                                    const selectedValue = typeOfPaperDropdown.value;
                                    if (selectedValue === 'Dissertation' || selectedValue === 'Thesis') {
                                        chapterDropdown.removeAttribute('disabled');
                                    } else {
                                        chapterDropdown.setAttribute('disabled', 'disabled');
                                        // Clear the selected value of the chapter dropdown
                                        chapterDropdown.value = '';
                                    }
                                }

                                // Initial check in case the form is pre-populated
                                toggleChapterDropdown();

                                // Add event listener to type of paper dropdown
                                typeOfPaperDropdown.addEventListener('change', toggleChapterDropdown);
                            });

                        </script>


                        <div class="col-md-4 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Type Of Services</label>
                            <select name="services" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
						        <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                @foreach($data['service'] as $service)
                                    <option <?php if ( $order['services'] == $service['service_name']) {echo "selected";} ?> value="{{$service->service_name}}">{{$service->service_name}}</option>
                                @endforeach   
                            </select>                         
                        </div>

                        <script>
                            // Assuming you have jQuery loaded in your project
                            $(document).ready(function() {
                                $('select[name="paper_type"]').change(function() {
                                    if ($(this).val() === 'add_college') {
                                        window.location.href = '/college';
                                    }
                                });
                            });
                        </script>   
                    </div>

                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Draft Required</label>
                            <select name="daraft_status" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
						        <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                    <option <?php if ( $order['draftrequired'] == 'Y') {echo "selected";} ?>  value="Y">Yes</option>
                                    <option <?php if ( $order['draftrequired'] == 'N') {echo "selected";} ?>value="N">No</option>
                            </select>                         
                          </div>
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Draft Date</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->draft_date)->format('Y-m-d') }}" name="draft_date" onchange="showSelectedDate(this)">
                        </div>
                       
                        <div class="col-md-4 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Draft Time</label>
                            @if($order->draft_time != '')
                            <input type="time" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->draft_time)->format('H:i') }}" name="draft_time" oncange="showSelectedTime(this)">
                            @else
                            <input type="time" class="form-control form-control-solid" placeholder="" value="" name="draft_time" onchange="showSelectedTime(this)">
                            @endif
                        </div>


                    </div>
                    
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Technical</label>
                            <select name="tech" aria-label="Select a Timezone" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                <option {{ $order['tech'] == '1' ? 'selected' : '' }} value="1">Technical</option>
                                <option {{ ($order['tech'] == '0' || $order['tech'] == '') ? 'selected' : '' }} value="0">Non Technical</option>
                            </select>
                        </div>

                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Resit</label>
                            <select name="resit" aria-label="Select a Timezone" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                <option {{ $order['resit'] == 'on' ? 'selected' : '' }} value="on">Resit</option>
                                <option {{ ($order['resit'] == 'off' || $order['resit'] == '') ? 'selected' : '' }} value="off">Normal</option>
                            </select>
                        </div>
                        
                       <div class="col-md-4 fv-row">
    <label class="fs-6 fw-bold mb-2">Semester</label>
    <select id="semester" name="semester" aria-label="Select a Semester" data-control="select2" class="form-select form-select-solid form-select-lg" tabindex="-1" aria-hidden="true">
        <option value="">Select Semester</option>
        <option {{ ($order['semester'] == 'I Semester') ? 'selected' : '' }} value="I Semester">I Semester</option>
        <option {{ ($order['semester'] == 'II Semester') ? 'selected' : '' }} value="II Semester">II Semester</option>
        <option {{ ($order['semester'] == 'III Semester') ? 'selected' : '' }} value="III Semester">III Semester</option>
        <option {{ ($order['semester'] == 'IV Semester') ? 'selected' : '' }} value="IV Semester">IV Semester</option>
        <option {{ ($order['semester'] == 'final Semester') ? 'selected' : '' }} value="V Semester">final Semester</option>
   
    </select>
</div>
                       
                       
                       
                    </div>

                    <div class="row g-9 mb-8 text-center">
                        <div class="col-md-12 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Messages</label>
                            <textarea name="messages" value="{{$order->message}}"  class="form-control form-control-solid" id="" cols="30" rows="3">{{$order->message}}</textarea>
                        </div>
                     
                    </div>

                    <div class="card-footer">
                        <button  type="submit" class="btn btn-lg btn-primary fw-bolder">
							Submit
						</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        
        // Double click event on email input
        $('input[name="email"]').on('dblclick', function () {
            Swal.fire({
                title: 'Are you sure you want to change the email?',
                text: "This action will update the email for all users and orders!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var email = $(this).val();
                    var newEmail = prompt("Enter new email:", email);

                    $.ajax({
                        type: 'get',
                        url: '{{ url('checkEmail') }}',
                        data: {'email': newEmail},
                        success: function (response) {
                            if (response.exists) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'This email (' + response.email + ') is already registered with the mobile number: ' + response.mobile_no,
                                });
                            } else {
                                $('input[name="email"]').val(newEmail);
                                
                            }
                        },
                        error: function (error) {
                            // Handle error
                            console.log('Error:', error);
                        }
                    });
                }
            });
        });
    });
</script> 
                



@endsection