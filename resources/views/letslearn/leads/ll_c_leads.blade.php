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
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Cancel Leads
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
                                <span class="card-label fw-bolder fs-3 mb-1">All Cancel Leads</span>
                                <span class="text-muted mt-1 fw-bold fs-7"></span>
                            </h3>
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
                                            <th class="min-w-100px text-end">Actions</th>
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
                                         
                                                @if ($lead['frontendorder'] == '1')
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
                                                {{ $lead['user'] ? $lead['user']['name'] : 'No User' }}
                                                <a href="#" class="text-dark fw-bolder text-hover-primary d-block fs-6">{{$lead['user']['country_code']}} {{$lead['user']['mobile']}}</a>
                                                <span class="text-muted fw-bold text-muted d-block fs-7">{{$lead['user']['email']}}</span>
                                            </td>                                            
                                            <td class="text-start">
                                                @if($lead['project_title'])
                                                    {{ $lead['project_title'] }}
                                                @else
                                                    <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                @endif                                                
                                            </td>
                                            <td class="text-start">
                                                @if($lead['pages'])
                                                    {{ $lead['pages'] }}
                                                @else
                                                    <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                @endif 
                                            </td>
                                            <td class="text-start">
                                                @if($lead['price'])
                                                    {{ $lead['price'] }}
                                                @else
                                                    <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                @endif
                                            </td>
                                            <td class="text-start">
                                                @if($lead['service_type'])
                                                    {{ $lead['service_type'] }}
                                                @else
                                                    <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                @endif
                                            </td>
                                            <td class="text-start">
                                                @if($lead['subject'])
                                                    {{ $lead['subject'] }}
                                                @else
                                                    <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                @endif
                                            </td>
                                            <td class="text-start">
                                                @if($lead['assignment_details'])
                                                    {{ $lead['assignment_details'] }}
                                                @else
                                                    <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                @endif
                                            </td>
                                            <td class="text-start">
                                                <div class="d-flex flex-column w-100 me-2">
                                                    <div class="d-flex flex-stack mb-2">
                                                        <span class="text-danger me-2 fs-7 fw-bold">{{ \Carbon\Carbon::parse($lead['deadline'])->format('d M Y D (h:i:s A)') }}</span>
                                                    </div>
                                                    
                                                </div>
                                            </td>
                                            <td class="text-start">
												<div class="d-flex flex-column w-100 me-2">
													<div class="d-flex flex-stack mb-2">
														<span class="text-success me-2 fs-7 fw-bold">{{ \Carbon\Carbon::parse($lead['created_at'])->format('d M Y D (h:i:s A)') }}</span>
													</div>
													
												</div>
											</td>
                                            <td class="text-start">
												<div class="d-flex flex-column w-100 me-2">
													<div class="d-flex flex-stack mb-2">
														<span class="text-warning me-2 fs-7 fw-bold">{{ \Carbon\Carbon::parse($lead['updated_at'])->format('d M Y D (h:i:s A)') }}</span>
													</div>
													
												</div>
											</td>
                                            <td>
                                                <div class="d-flex justify-content-end flex-shrink-0">
                                                    <a data-bs-toggle="modal" data-bs-target="#kt_modal_restore_lead{{$lead['id']}}" href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2z"/>
                                                            <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466"/>
                                                        </svg>
                                                    </a>
                                                    <a data-bs-toggle="modal" data-bs-target="#kt_modal_delete_lead{{$lead['id']}}" href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                                        <span class="svg-icon svg-icon-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"></path>
                                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"></path>
                                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"></path>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                                @include('letslearn.leads.section.c-leadsEdit-section')
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <div class="pagination py-3 d-flex justify-content-center align-items-center">
                                    {{-- Previous Page Link --}}
                                    @if ($pagination['current_page'] > 1)
                                        <a href="{{ route('ll_fetchC_Leads', array_merge(request()->all(), ['page' => $pagination['current_page'] - 1])) }}" class="btn btn-light mx-1">
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
                                            <a href="{{ route('ll_fetchC_Leads', array_merge(request()->all(), ['page' => $i])) }}" class="btn btn-light mx-1">{{ $i }}</a>
                                        @endif
                                    @endfor

                                    {{-- Next Page Link --}}
                                    @if ($currentPage < $totalPages)
                                        <a href="{{ route('ll_fetchC_Leads', array_merge(request()->all(), ['page' => $currentPage + 1])) }}" class="btn btn-light mx-1">
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
