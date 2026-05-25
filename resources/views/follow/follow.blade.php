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
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Order
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need</small>
                    </div>
                    
                </div>
	        </div>

			<div class="col-xl-12">
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
                                <input type="search"   name="search" id="search" class="form-control form-control-solid" placeholder="Serach By OrderCode " >
                            </div>
                            
                           <!-- 15-march -->
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

                            <!-- 15-march -->
                            
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select name="status" id="status" data-placeholder="Search By writer Name" class="form-select form-select-solid form-select-lg">
                                    <option value=""></option> <!-- Empty option -->
                                    <option value="negative but convinced">negative but convinced</option> 
                                    <option value="negative">negative</option>
                                    <option value="positive ">positive</option>
                                    <option value="positive and referral">positive and referral</option>
                                    <option value="positive and own order ">positive and own order</option>
                                    <option value="No response">No response</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>

                            <div class="col-md-3 fv-row">
                                <input type="date" name="fromDate" id="fromDate" class="form-control form-control-solid" placeholder="Search By OrderCode">
                            </div>
                            <div class="col-md-3 fv-row">
                                <input type="date" name="toDate" id="toDate" class="form-control form-control-solid" placeholder="Search By OrderCode">
                            </div>

                            
                               

                            
                            <div class="col-md-3 fv-row fv-plugins-icon-container mt-1">
                                <a  id="resetFiltersBtn" class="btn btn-sm btn-danger">Reset</a>
                                <a  onclick="applyFilters()" class="btn btn-sm btn-primary">Search</a>
                                
                            </div>
                        </div>
                    </form>
                </div>
			</div>
            @include('layouts.flash')

			<div class="card card-xl-stretch  mb-xl-">
				<div class="card-header border-0 pt-5">
					<h3 class="card-title align-items-start flex-column">
                         
						<span class="card-label fw-bolder fs-3 mb-1">All User</span>
						<span class="text-muted mt-1 fw-bold fs-7"></span>
					</h3>
				</div>
				<div class="card-body py-3">
					<div class="card-header border-0 pt-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1">Orders</span>
						</h3>
						
					</div>
					<div class="card-body py-3">
						<div class="table-responsive">
							<table  class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
								<thead class="p-2">
									<tr class="fw-bolder text-muted bg-light">
										<th class="min-w-20px text-center">Check</th>
										<th class="min-w-20px text-center">SR</th>
										<th class="min-w-20px text-center" >Order Code</th>
										<th class="min-w-50px">User Details </th>
										<th class="min-w-50px">Order Date</th>
										<th class="min-w-30px">Follow Up Date</th>
										<th style="width: 175px;" class="min-w-100px text-center">Status</th>
										<th class="min-w-40px">Comments</th>
										<th class="min-w-40px">Follow Up User</th>
										
										<th class="min-w-150px text-center" >Action</th>
									</tr>
								</thead>
                                <div id="loadind" style="display:none"></div>
								<tbody style="display:none" id="content" class="searchData">
							
								</tbody>
                                @foreach($data['orders'] as $order)
								<tbody class="allData">
                                        <tr>
                                        <td>
    @if($order->user?->followup == 1)
        <div class="form-check form-check-sm form-check-custom form-check-solid m-5">
            <input onclick="FollowUpUser(this, {{ $order->user->id }})" class="form-check-input widget-13-check" type="checkbox" checked value="1">
        </div>
    @else
        <div class="form-check form-check-sm form-check-custom form-check-solid m-5">
            <input onclick="FollowUpUser(this, {{ $order->user?->id }})" class="form-check-input widget-13-check" type="checkbox" value="1">
        </div>
    @endif
</td>

<script>
function FollowUpUser(checkbox, UserId) {
    var checkedValue = checkbox.checked ? 1 : 0;
    $.ajax({
        url: '/followUpUser/' + UserId,
        method: 'PUT',
        data: {
            followup: checkedValue
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (checkbox.checked) {
                console.log("Lead with ID " + UserId + " cancelled successfully.");
            } else {
                console.log("Lead with ID " + UserId + " restored successfully.");
            }
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error);
        }
    });
}
</script>


                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{$order->order_id}}</td>
                                            <td>{{$order->user?->name}} <br>
                                                <span class="badge badge-light-danger fs-7 fw-bold" > + {{$order->user?->countrycode}} {{$order->user?->mobile_no}} </span> <br>
                                                <span class="badge badge-light-danger fs-7 fw-bold" >  {{$order->user?->email}}</span>
                                            </td>
                                            <td>{{$order->order_date}}</td>

                                            <td>{{$order->followupdate}}</td>
                                            <td>
                                                @if($order->follow_status == 'negative but convinced' || $order->follow_status == 'negative')    
                                                    <span class="badge badge-light-danger fs-7 fw-bold">{{$order->follow_status}}</span><br>
                                                @elseif($order->follow_status == 'positive' || $order->follow_status == 'positive and referral')
                                                    <span class="badge badge-light-warning fs-7 fw-bold">{{$order->follow_status}}</span><br>
                                                @elseif($order->follow_status == 'positive and own order')
                                                    <span class="badge badge-light-success fs-7 fw-bold">{{$order->follow_status}}</span><br>
                                                @elseif($order->follow_status == 'No response')
                                                    <span class="badge badge-light-primary fs-7 fw-bold">{{$order->follow_status}}</span><br>
                                                @endif

                                            </td>
                                            <td>
                                                {{ Str::limit($order->follow_comment, 50) }}  <!-- Displaying a summary or the first 50 characters -->
                                                @if($order->follow_comment)
                                                <a href="#" id="{{ $order->order_id }}" data-bs-toggle="modal" data-bs-target="#confirmationModal{{ $order->order_id }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                    More...
                                                </a>
                                                @endif
                                            </td>

                                            <div class="modal fade" id="confirmationModal{{ $order->order_id }}" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="confirmationModalLabel">All Comments</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="col-xl-12">
                                                            <div class="card-body" id="kt_drawer_chat_messenger_body">
                                                                <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer" data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
                                                                    @foreach($order->allCommentsByUid  as $feedback)
                                                                        @if($feedback->comment != '')
                                                                            @if($feedback->commented_by != Auth::user()->name)
                                                                                <div class="d-flex justify-content-start mb-10">
                                                                                    <div class="d-flex flex-column align-items-start">
                                                                                        <div class="d-flex align-items-center mb-2">
                                                                                            <div class="symbol symbol-35px symbol-circle">
                                                                                                <img alt="Pic" src="assets/media/avatars/blank.png" />                                                                                                
                                                                                            </div>
                                                                                            <div class="ms-3">
                                                                                                @if($feedback->commented_by)
                                                                                                    <a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary me-1">{{ $feedback->commented_by }}</a>
                                                                                                @endif
                                                                                            </div>
                                                                                            <div class="me-3">
                                                                                                <span class="text-muted fs-7 mb-1">{{ $feedback->created_at }}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start" data-kt-element="message-text">{{ $feedback->comment }}</div>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                <div class="d-flex justify-content-end mb-10">
                                                                                    <div class="d-flex flex-column align-items-end">
                                                                                        <div class="d-flex align-items-center mb-2">
                                                                                            <div class="me-3">
                                                                                                <span class="text-muted fs-7 mb-1">{{ $feedback->created_at }}</span>
                                                                                            </div>
                                                                                            <div class="symbol symbol-35px symbol-circle">
                                                                                                <img alt="Pic" src="assets/media/avatars/blank.png" />
                                                                                            </div>
                                                                                            <div class="ms-3">
                                                                                                <a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary me-1">You</a>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="p-5 rounded bg-light-primary text-dark fw-bold mw-lg-400px text-end" data-kt-element="message-text">{{ $feedback->comment }}</div>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <td>{{$order->follow_up_user}}</td>
                                            <td class="text-center"> 
                                                <div class="card-toolbar">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_create_appaa_newLeads{{$order->id}}" id="kt_toolbar_primary_button" class="btn btn-sm btn-light-primary">
                                                        
                                                        <li class="fa fa-edit"> </li>
                                                    </a>
                                                </div>

                                                <div class="modal fade" id="kt_modal_create_appaa_newLeads{{$order->id}}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered mw-950px">
                                                        <div class="modal-content rounded">
                                                            <div class="modal-header pb-0 border-0 justify-content-end">
                                                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                                    <span class="svg-icon svg-icon-1">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                                                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                                                                        </svg>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                                                                <form id="kt_modal_new_target_form" class="form" method="POST"  action="{{ route('follow.update', ['id' =>  $order->id]) }}">
                                                                    @csrf
                                                                    <div class="mb-13 text-center">
                                                                        <h1 class="mb-3">Status Edit Of Follow Up {{$order->order_id}}</h1>
                                                                        <div class="text-muted fw-bold fs-5"></div>
                                                                    </div>
                                                                    
                                                                    <div class="row g-9 mb-8 text-start">
                                                                        <div class="col-md-6 fv-row">
                                                                            <label class="fs-6 fw-bold mb-2">Follow-Up Status</label>
                                                                            <select name="follow_up_status" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                                                                <option value=""></option>
                                                                                <option {{ old('follow_up_status', $order->follow_status) == 'negative but convinced' ? 'selected' : '' }} value="negative but convinced">negative but convinced</option>
                                                                                <option {{ old('follow_up_status', $order->follow_status) == 'negative' ? 'selected' : '' }} value="negative">negative</option>
                                                                                <option {{ old('follow_up_status', $order->follow_status) == 'positive' ? 'selected' : '' }} value="positive">positive</option>
                                                                                <option {{ old('follow_up_status', $order->follow_status) == 'positive and referral' ? 'selected' : '' }} value="positive and referral">positive and referral</option>
                                                                                <option {{ old('follow_up_status', $order->follow_status) == 'positive and own order' ? 'selected' : '' }} value="positive and own order">positive and own order</option>
                                                                                <option {{ old('follow_up_status', $order->follow_status) == 'No response' ? 'selected' : '' }} value="No response">No response</option>
                                                                            </select>
                                                                   

                                                                   
                                                                    <div class="row g-9 mb-8 text-start">
                                                                        <div class="col-md-12 fv-row">
                                                                            <label class="fs-6 fw-bold mb-2">Comment</label>
                                                                            <textarea name="comment" value="" class="form-control form-control-solid" id="" cols="30" rows="3">{{$order->follow_comment}}</textarea>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

									
								   </tbody>
                                @endforeach
							</table>
						</div>
					</div>

				</div>
			</div>

        </div>
    </div>
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
    // Function to apply filters
    function applyFilters() {
        var $search = $('#search').val();
        var $status = $('#status').val();
        var $uid = $('#selectedValue').val();
        var $fromDate = $('#fromDate').val();
        var $toDate = $('#toDate').val();

        // Store filter values in sessionStorage
        var followfilter = {
            search: $search,
            status: $status,
            uid: $uid,
            fromDate: $fromDate,
            toDate: $toDate
        };
        sessionStorage.setItem('followfilter', JSON.stringify(followfilter));

        // Check if any filter is set
        var filtersExist = Object.values(followfilter).some(value => value);

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
            url: '{{ url('search-fw') }}',
            data: {
                '_token': CSRF_TOKEN,
                ...followfilter
            },
            success: function (data) {
                console.log(data);
                $('#content').html(data);
            },
            error: function (xhr, status, error) {
                console.log('Error:', error);
            }
        });
    }

    // Function to reset filters
    function resetFilters() {
        sessionStorage.removeItem('followfilter'); // Clear stored filters

        // Clear input values
        $('input[type=search], input[type=date], input[type=text], input[type=hidden]').val('');
        $('select').val('').trigger('change');

        // Apply filters
        applyFilters();
    }

    // Click event for the reset button
    $('#resetFiltersBtn').on('click', function() {
        resetFilters();
    });

    // On document ready, check if there are stored filters and apply them
    $(document).ready(function() {
        var storedFilters = sessionStorage.getItem('followfilter');
        if (storedFilters) {
            var filters = JSON.parse(storedFilters);
            $('#search').val(filters.search);
            $('#status').val(filters.status);
            $('#selectedValue').val(filters.uid);
            $('#fromDate').val(filters.fromDate);
            $('#toDate').val(filters.toDate);
            applyFilters();
        }
    });
</script>


<script>
function FollowUpUser(checkbox, UserId) {
    $.ajax({
        url: 'followUpUser/' + UserId,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').att('content')
        },
        success: function(response) {
            if (checkbox.checked) {
                console.log("Lead with ID " + UserId "cancelledsuccessfully.");
              
            } else {
                console.log("Lead with ID " + UserId "restoredsuccessfully.");
              
            }
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error);
        }
    });
}
</script>
                                           
@endsection