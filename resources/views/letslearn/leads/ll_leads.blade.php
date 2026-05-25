@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-fluid">
        <div class="col-xl-12">
            <div class="card card-xxl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Filter</span>
                    </h3>
                </div>
                <div class="card-body py-3">                    
                    <form method="GET" action="{{ url()->current() }}">
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 fv-row fv-plugins-icon-container mb-2">
                                <input type="text" name="additional_filter1" id="additional_filter1" class="form-control form-control-solid" placeholder="Search By Order Id / Title" value="{{ old('additional_filter1', request('additional_filter1')) }}">
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
                                <input type="text" list="searchDatalist" id="searchInput" name="user_filter" class="form-control form-control-solid" placeholder="Search..." autocomplete="off" value="{{ old('user_filter', request('user_filter')) }}">
                                
                                <!-- Datalist for displaying search results -->
                                <datalist id="searchDatalist"></datalist>
                                
                                <!-- Container to display search results -->
                                <div id="searchResultss"></div>
                                
                                <!-- Hidden field to store the selected value -->
                                <input type="hidden" id="selectedValue" name="additional_filter2" value="{{ old('additional_filter2', request('additional_filter2')) }}">
                            </div>

                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script>
                                $(document).ready(function () {
                                    $('#searchInput').on('input', function () {
                                        var searchValue = $(this).val();

                                        if (searchValue.length >= 3) {
                                            $.ajax({
                                                url: "{{ route('ll_search_user') }}",
                                                type: "GET",
                                                data: { user: searchValue },
                                                success: function (response) {
                                                    // Clear the datalist before adding new results
                                                    $('#searchDatalist').empty();
                                                    var results = '';
                                                    
                                                    if (response && response.length > 0) {
                                                        $.each(response, function (key, value) {
                                                            // Populate each option with email and attach user ID in data-id
                                                            $('#searchDatalist').append('<option data-id="' + value.id + '" value="' + value.email + '">' + value.name + ' (' + value.mobile + ')</option>');
                                                        });
                                                        
                                                        if (response.length === 1) {
                                                            // Automatically select single result
                                                            $('#searchInput').val(response[0].email);
                                                            $('#selectedValue').val(response[0].id);  // Store the selected user ID in hidden input
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

                                    // Update hidden input when user selects an option from datalist
                                    $('#searchInput').on('change', function() {
                                        var selectedEmail = $(this).val();
                                        var selectedOption = $('#searchDatalist option').filter(function () {
                                            return $(this).val() === selectedEmail;
                                        });

                                        // Set hidden input with data-id if found
                                        if (selectedOption.length > 0) {
                                            $('#selectedValue').val(selectedOption.data('id'));
                                        } else {
                                            $('#selectedValue').val('');
                                        }
                                    });

                                    // Reset the search input and hidden input when the reset button is clicked
                                    $('#resetFiltersBtn').on('click', function () {
                                        $('#searchInput').val(''); // Clear search input
                                        $('#selectedValue').val(''); // Clear hidden input for UID
                                    });
                                });
                            </script>


                            <div class="col-lg-3 col-md-4 fv-row mb-2">
                                <select name="additional_filter3" id="additional_filter3" class="form-control form-control-solid" placeholder="Search By Type">
                                    <option value="">Select Service Type</option>
                                    <option value="Assignment" {{ request('additional_filter3') == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                                    <option value="Dissertation" {{ request('additional_filter3') == 'Dissertation' ? 'selected' : '' }}>Dissertation</option>
                                    <option value="Thesis" {{ request('additional_filter3') == 'Thesis' ? 'selected' : '' }}>Thesis</option>
                                    <option value="Research Project" {{ request('additional_filter3') == 'Research Project' ? 'selected' : '' }}>Research Project</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-4 fv-row mb-2">
                                <select name="additional_filter4" id="additional_filter4" class="form-control form-control-solid" placeholder="Work Type">
                                    <option value="">Select Work Type</option>
                                    <option value="Standard" {{ request('additional_filter4') == 'Standard' ? 'selected' : '' }}>Standard</option>
                                    <option value="First Class Work" {{ request('additional_filter4') == 'First Class Work' ? 'selected' : '' }}>First Class Work</option>                                    
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-4 fv-row mb-2">
                                <select name="additional_filter5" id="additional_filter5" class="form-control form-control-solid" placeholder="Order Type">
                                    <option value="">Select Order Type</option>                                                                        
                                    <option value="frontendorder" {{ request('additional_filter5') == 'frontendorder' ? 'selected' : '' }}>FrontEnd Order</option>                                    
                                    <option value="backendorder" {{ request('additional_filter5') == 'backendorder' ? 'selected' : '' }}>BackEnd Order</option>                                    
                                </select>
                            </div>
                        
                            <!-- Other filters... -->

                            <div class="col-lg-3 col-md-4 fv-row mb-2">
                                <select name="additional_filter6" id="additional_filter6" class="form-control form-control-solid">
                                    <option value="">Select Date Type</option>
                                    <option value="created" {{ request('additional_filter6') == 'created' ? 'selected' : '' }}>Created</option>
                                    <option value="deadline" {{ request('additional_filter6') == 'deadline' ? 'selected' : '' }}>Deadline</option>
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-4 fv-row mb-2">
                                <input type="date" name="additional_filter7" id="additional_filter7" class="form-control form-control-solid" value="{{ request('additional_filter7') }}">
                            </div>

                            <div class="col-lg-3 col-md-4 fv-row mb-2">
                                <input type="date" name="additional_filter8" id="additional_filter8" class="form-control form-control-solid" value="{{ request('additional_filter8') }}">
                            </div>


                            <div class="col-lg-3 col-md-4 fv-row fv-plugins-icon-container mb-2">
                                <button type='submit' class="btn btn-sm btn-primary" >Search</button>
                                <a href="{{ url()->current() }}" class="btn btn-sm btn-danger" >reset</a>
                            </div>
                        
                        </div>
                    </form>
                </div>
            </div>
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Leads
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Let's Learn Together</small>
                    </div>
                    
                </div>
            </div>
            <div class="row gy-5 g-xl-8">
                <div class="col-xl-12">
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">All Leads</span>
                                <span class="text-muted mt-1 fw-bold fs-7"></span>
                            </h3>
                            <div class="d-flex align-items-center py-1">
                                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_lead" id="kt_toolbar_primary_button">Create Lead</a>
                            </div>
                            @include('letslearn.leads.section.createLead')
                        </div>
                        <div class="card-body py-3">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bolder text-muted">
                                            <th class="">#</th>
                                            <th class="">Order Code</th>
                                            <th class="">User</th>
                                            <th class="">Title</th>
                                            <th class="">Word</th>
                                            <th class="">Amount</th>
                                            <th class="">Type</th>
                                            <th class="">Subject</th>
                                            <th class="">Details</th>
                                            <th class="text-danger">Deadline</th>
                                            <th class="text-success">Created</th>
                                            <th class="text-warning">Updated</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $serial = ($pagination['current_page'] - 1) * $pagination['per_page'];
                                        @endphp
                                        @foreach($leads as $lead)
                                            <tr>
                                                <td>{{ ++$serial }}</td>
                                                <td class="text-start">
                                                    @if (isset($lead['frontendorder']) && $lead['frontendorder'] == '1')
                                                        <span class="badge badge-light-primary fs-7 fw-bold">{{ $lead['order_id'] }}</span>
                                                    @else
                                                        {{ $lead['order_id'] }}
                                                    @endif
                                                    <br>

                                                    @if ($lead['work_type'] == 'First Class Work')
                                                        <span class="badge badge-light-info fs-7 fw-bold">First Class Work</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ isset($lead['user']) && $lead['user'] ? $lead['user']['name'] : 'No User' }}
                                                    <a href="#" class="text-dark fw-bolder text-hover-primary d-block fs-6">
                                                        {{ isset($lead['user']) && $lead['user'] ? $lead['user']['country_code'] . ' ' . $lead['user']['mobile'] : '' }}
                                                    </a>
                                                    <span class="text-muted fw-bold text-muted d-block fs-7">
                                                        {{ isset($lead['user']) && $lead['user'] ? $lead['user']['email'] : '' }}
                                                    </span>
                                                </td>
                                                <td class="text-start">
                                                    @if(isset($lead['project_title']))
                                                        {{ $lead['project_title'] }}
                                                    @else
                                                        <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                    @endif
                                                </td>
                                                <td class="text-start">
                                                    @if(isset($lead['pages']))
                                                        {{ $lead['pages'] }}
                                                    @else
                                                        <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                    @endif
                                                </td>
                                                <td class="text-start">
                                                    @if(isset($lead['price']))
                                                        {{ $lead['price'] }}
                                                    @else
                                                        <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                    @endif
                                                </td>
                                                <td class="text-start">
                                                    @if(isset($lead['service_type']))
                                                        {{ $lead['service_type'] }}
                                                    @else
                                                        <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                    @endif
                                                </td>
                                                <td class="text-start">
                                                    @if(isset($lead['subject']))
                                                        {{ $lead['subject'] }}
                                                    @else
                                                        <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                    @endif
                                                </td>
                                                <td class="text-start">
                                                    @if(isset($lead['assignment_details']))
                                                        {{ $lead['assignment_details'] }}
                                                    @else
                                                        <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                    @endif
                                                </td>
                                                <td class="text-start">
                                                    <div class="d-flex flex-column w-100 me-2">
                                                        <div class="d-flex flex-stack mb-2">
                                                            <span class="text-danger me-2 fs-7 fw-bold">
                                                                {{ \Carbon\Carbon::parse($lead['deadline'])->format('d M Y D (h:i:s A)') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-start">
                                                    <div class="d-flex flex-column w-100 me-2">
                                                        <div class="d-flex flex-stack mb-2">
                                                            <span class="text-success me-2 fs-7 fw-bold">
                                                                {{ \Carbon\Carbon::parse($lead['created_at'])->format('d M Y D (h:i:s A)') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-start">
                                                    <div class="d-flex flex-column w-100 me-2">
                                                        <div class="d-flex flex-stack mb-2">
                                                            <span class="text-warning me-2 fs-7 fw-bold">
                                                                {{ \Carbon\Carbon::parse($lead['updated_at'])->format('d M Y D (h:i:s A)') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-end flex-shrink-0">
                                                        <a data-bs-toggle="modal" data-bs-target="#kt_modal_create_edit_lead{{$lead['id']}}" aria-label="Edit Lead" href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                            <span class="svg-icon svg-icon-3">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path>
                                                                    <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path>
                                                                </svg>
                                                            </span>
                                                        </a>
                                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#kt_modal_convert_lead{{ $lead['id'] }}" aria-label="Convert Lead">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                                <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                                                            </svg>
                                                        </a>
                                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#kt_modal_cancel_lead{{ $lead['id'] }}" aria-label="Cancel Lead">
                                                            <span class="svg-icon svg-icon-3">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                                                </svg>
                                                            </span>
                                                        </a>

                                                        <a href="#" id="clickToDownload{{$lead['order_id']}}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 download-btn{{$lead['id']}}" onclick="downloadFiles(this)">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                                                            </svg>
                                                        </a>
                                                        
                                                        <script>
                                                            function downloadFiles(element) {
                                                                // Prevent the default anchor behavior
                                                                event.preventDefault();

                                                                // Get the order ID from the id attribute of the clicked <a> tag
                                                                var orderId = element.id.replace('clickToDownload', '');

                                                                // Send AJAX request to fetch the file URLs for the order
                                                                $.ajax({
                                                                    url: '/ll-get-files-by-order', // URL to the controller method in Ain
                                                                    method: 'GET',
                                                                    data: { order_id: orderId },
                                                                    success: function(response) {
                                                                        // Check if files are available
                                                                        if (response.files && response.files.length > 0) {
                                                                            // Loop through each file URL
                                                                            response.files.forEach(function(fileUrl) {
                                                                                // Create a link element for opening or downloading
                                                                                var link = document.createElement('a');
                                                                                link.href = fileUrl;  // Set the file URL
                                                                                link.target = '_blank'; // Open in a new tab

                                                                                // Extract filename from URL
                                                                                var fileName = fileUrl.split('/').pop(); // Optionally customize this
                                                                                link.download = fileName; // Set the filename for download

                                                                                // Append link to the body, click it, then remove it
                                                                                document.body.appendChild(link);
                                                                                link.click(); // Simulate a click to open/download
                                                                                document.body.removeChild(link);
                                                                            });
                                                                        } else {
                                                                            Swal.fire({
                                                                                icon: 'warning',
                                                                                title: 'No Files Available',
                                                                                text: 'There are no files available for this order.',
                                                                                confirmButtonColor: '#3085d6',
                                                                                confirmButtonText: 'OK'
                                                                            });
                                                                        }
                                                                    },
                                                                    error: function(xhr, status, error) {
                                                                        // Check for a 404 error specifically
                                                                        if (xhr.status === 404) {
                                                                            Swal.fire({
                                                                                icon: 'warning',
                                                                                title: 'Files Not Found',
                                                                                text: 'No files found for this order.',
                                                                                confirmButtonColor: '#3085d6',
                                                                                confirmButtonText: 'OK'
                                                                            });
                                                                        } else {
                                                                            console.error(xhr.responseText);
                                                                            Swal.fire({
                                                                                icon: 'error',
                                                                                title: 'Error',
                                                                                text: 'An error occurred while fetching the files.',
                                                                                confirmButtonColor: '#3085d6',
                                                                                confirmButtonText: 'OK'
                                                                            });
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    </div>
                                                    @include('letslearn.leads.section.leadsEdit-section')
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                <!-- <div class="pagination py-2">
                                    {{-- Previous Page Link --}}
                                    @if ($pagination['current_page'] > 1)
                                        <a href="{{ route('ll_fetchLeads', ['page' => $pagination['current_page'] - 1]) }}">Previous</a>
                                    @endif

                                    
                                    @php
                                        $totalPages = $pagination['last_page'];
                                        $currentPage = $pagination['current_page'];
                                        $startPage = max(1, $currentPage - 4);  // Show up to 4 pages before the current page
                                        $endPage = min($totalPages, $currentPage + 4);  // Show up to 4 pages after the current page
                                        if ($totalPages <= 10) {
                                            $startPage = 1;
                                            $endPage = $totalPages;
                                        }
                                    @endphp

                                    {{-- Loop through each page number --}}
                                    @for ($i = $startPage; $i <= $endPage; $i++)
                                        @if ($i == $currentPage)
                                            <span class="mx-2">{{ $i }}</span>  {{-- Current page --}}
                                        @else
                                            <a href="{{ route('ll_fetchLeads', ['page' => $i]) }}" class="mx-2">{{ $i }}</a>
                                        @endif
                                    @endfor

                                    {{-- Next Page Link --}}
                                    @if ($currentPage < $totalPages)
                                        <a href="{{ route('ll_fetchLeads', ['page' => $currentPage + 1]) }}">Next</a>
                                    @endif

                                    {{-- Page Number Links --}}
                                    <span class="mx-2">Page {{ $pagination['current_page'] }} of {{ $pagination['last_page'] }}</span>
                                </div> -->
                                <div class="pagination py-3 d-flex justify-content-center align-items-center">
                                    {{-- Previous Page Link --}}
                                    @if ($pagination['current_page'] > 1)
                                        <a href="{{ route('ll_fetchLeads', array_merge(request()->all(), ['page' => $pagination['current_page'] - 1])) }}" class="btn btn-light mx-1">
                                            &laquo; Previous
                                        </a>
                                    @else
                                        <span class="btn btn-light mx-1 disabled">&laquo; Previous</span>
                                    @endif

                                    @php
                                        $totalPages = $pagination['last_page'];
                                        $currentPage = $pagination['current_page'];
                                        $startPage = max(1, $currentPage - 4);
                                        $endPage = min($totalPages, $currentPage + 4);
                                        if ($totalPages <= 10) {
                                            $startPage = 1;
                                            $endPage = $totalPages;
                                        }
                                    @endphp

                                    {{-- Page Number Links --}}
                                    @for ($i = $startPage; $i <= $endPage; $i++)
                                        @if ($i == $currentPage)
                                            <span class="btn btn-primary mx-1">{{ $i }}</span> {{-- Active page --}}
                                        @else
                                            <a href="{{ route('ll_fetchLeads', array_merge(request()->all(), ['page' => $i])) }}" class="btn btn-light mx-1">{{ $i }}</a>
                                        @endif
                                    @endfor

                                    {{-- Next Page Link --}}
                                    @if ($currentPage < $totalPages)
                                        <a href="{{ route('ll_fetchLeads', array_merge(request()->all(), ['page' => $currentPage + 1])) }}" class="btn btn-light mx-1">
                                            Next &raquo;
                                        </a>
                                    @else
                                        <span class="btn btn-light mx-1 disabled">Next &raquo;</span>
                                    @endif

                                    {{-- Total Pages Information --}}
                                    <span class="mx-3 text-muted">
                                        Page {{ $currentPage }} of {{ $totalPages }}
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection