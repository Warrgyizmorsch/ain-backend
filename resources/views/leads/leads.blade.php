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
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                        data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Leads
                            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                            <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need</small>
                    </div>
                </div>
            </div>
            @if(session('danger'))
                <div class="alert alert-danger">
                    {{ session('danger') }}
                    @if(session('existingUser'))
                        <!-- Display existing user details if available -->
                        User email: {{ session('existingUser')->email }}, User mobile:{{ session('existingUser')->mobile_no }}
                    @endif
                </div>
            @endif
            <div class="col-xl-12">
                @include('leads.section.filter')
            </div>
            <div class="card card-xl-stretch  mb-xl-">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Active Leads</span>
                        <span class="text-muted mt-1 fw-bold fs-7"></span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_create_appaa_newLeads"
                            id="kt_toolbar_primary_button" class="btn btn-sm btn-light-primary">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                        transform="rotate(-90 11.364 20.364)" fill="black"></rect>
                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black">
                                    </rect>
                                </svg>
                            </span>
                            New Leads
                        </a>
                        @if(auth()->user()->role_id == 1)
                            <a onclick="leadsExport()" style="margin-left: 10px;" class="btn btn-sm btn-danger">Export</a>
                        @endif
                    </div>
                    @include('leads.section.new-leads')
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead class="p-2">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class='px-2'>No</th>
                                    <th class='text-center'>Action</th>
                                    <th>Order ID</th>

                                    <th>Name</th>
                                    <th>Order Date</th>

                                    <th>Project Title </th>
                                    <th>Words</th>
                                    <th>Price</th>
                                    <th class='px-2'>Delivery Date</th>
                                </tr>
                            </thead>
                            <tbody style="display:none" id="content" class="searchData">

                            </tbody>
                            <tbody class="allData">
                                @foreach ($status0Leads as $lead)
                                    <tr>
                                        <td class='text-center'>
                                            {{ $loop->index + 1 }}
                                        </td>
                                        <td class="icon-container my-auto d-flex">
                                            @if($lead->flag == '1')
                                                <div class="form-check form-check-sm form-check-custom form-check-solid m-5">
                                                    <input onchange="checkedLead(this, {{ $lead->id }})"
                                                        class="form-check-input widget-13-check" type="checkbox" checked value="1">
                                                </div>
                                            @else
                                                <div class="form-check form-check-sm form-check-custom form-check-solid m-5">
                                                    <input onchange="checkedLead(this, {{ $lead->id }})"
                                                        class="form-check-input widget-13-check" type="checkbox" value="1">
                                                </div>
                                            @endif


                                            <div class="form-check form-switch my-auto">
                                                <input class="form-check-input" type="checkbox" id="{{ $lead->id }}"
                                                    role="switch" checked onchange="handleChange(this, {{ $lead->id }})">
                                            </div>


                                            <a href="#" data-kt-drawer-toggle="#kt_drawer_chat"
                                                id="kt_drawer_chat_toggle{{ $lead->id }}"
                                                class="btn btn-icon btn-bg-warning btn-active-color-light btn-sm me-1">
                                                Call
                                            </a>
                                            @include('leads.section.call-lead')

                                            <a href="/leadedit.{{$lead->id}}" target="_blank" id="kt_toolbar_primary_button"
                                                class="btn btn-icon btn-bg-secondary btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3"
                                                            d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                            fill="black"></path>
                                                        <path
                                                            d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                            fill="black"></path>
                                                    </svg>
                                                </span>
                                            </a>

                                            <a href="javascript:void(0);" id="clickToCallBtn{{$lead->id}}" href="#"
                                                data-bs-toggle="modal" id="kt_toolbar_primary_button"
                                                class="btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1">
                                                <span class="svg-icon svg-icon-3">
                                                    <li class="fa fa-phone fa-lg"></li>
                                                </span>
                                            </a>



                                            <!-- Include jQuery library -->
                                            <script
                                                src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                                            <!-- Include SweetAlert library -->
                                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#clickToCallBtn{{$lead->id}}").click(function () {
                                                        var primarycountrycode = $("#country_primary").val(); // Assuming country_primary is an input field
                                                        var primarynumber = $("#primary").val(); // Assuming primary is an input field
                                                        var apiUrl = "https://103.113.27.27/Anilmadan/click2call.php?callerNumber={{Auth::user()->sip}}&receiverNumber=" + primarycountrycode + primarynumber + "&user=anil&key=jbti89692vc60b2o9nu%^7";

                                                        $.get(apiUrl, function (data, status) {
                                                            // Use SweetAlert to display data and status
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'Call Transfer Check SoftPhone',
                                                                html: 'Data: ' + data + '<br>Status: ' + status,
                                                            });
                                                        });
                                                    });
                                                });
                                            </script>

                                            <form action="{{ url('convertleads/' . $lead->id) }}" method="post">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-icon btn-bg-secondary btn-active-color-light btn-sm me-1">
                                                    <span class="svg-icon svg-icon-3">C</span>
                                                </button>
                                            </form>


                                            <a href="#" id="clickToDownload{{$lead->order_id}}"
                                                class="btn btn-icon btn-bg-danger btn-active-color-dark btn-sm me-1 download-btn{{$lead->id}}"
                                                onclick="downloadFiles(this)">
                                                <span class="svg-icon svg-icon-3">
                                                    <i class="fa fa-download fa-lg"></i>
                                                </span>
                                            </a>

                                            <script>
                                                function downloadFiles(element) {
                                                    // Prevent the default anchor behavior
                                                    event.preventDefault();

                                                    // Get the order ID from the id attribute of the clicked <a> tag
                                                    var orderId = element.id.replace('clickToDownload', '');

                                                    // Send AJAX request to get file URLs for the order
                                                    $.ajax({
                                                        url: '/get-files-by-order', // Update the URL to your route endpoint
                                                        method: 'GET',
                                                        data: { order_id: orderId },
                                                        success: function (response) {
                                                            // Check if response is empty
                                                            if (response.length === 0) {
                                                                // Show SweetAlert popup for no files to download
                                                                Swal.fire({
                                                                    icon: 'warning',
                                                                    title: 'No Files Found',
                                                                    text: 'There are no files to download for this order.',
                                                                    confirmButtonColor: '#3085d6',
                                                                    confirmButtonText: 'OK'
                                                                });
                                                            } else {
                                                                // Loop through each file URL and trigger download
                                                                response.forEach(function (fileUrl) {
                                                                    // Create an anchor element for the file
                                                                    var link = document.createElement('a');
                                                                    link.href = fileUrl;
                                                                    link.download = fileUrl.substring(fileUrl.lastIndexOf('/') + 1);
                                                                    document.body.appendChild(link);

                                                                    // Simulate a click event to trigger the download
                                                                    link.click();

                                                                    // Clean up
                                                                    document.body.removeChild(link);
                                                                });
                                                            }
                                                        },
                                                        error: function (xhr, status, error) {
                                                            // Handle error
                                                            console.error(xhr.responseText);
                                                        }
                                                    });
                                                }
                                            </script>
                                        </td>


                                        <td class="text-center">

                                            @if ($lead['frontendorder'] == '1')
                                                <span class="badge badge-light-primary fs-7 fw-bold">{{ $lead->order_id }}</span>
                                            @else
                                                {{ $lead->order_id }}
                                            @endif

                                            <br>
                                            @if ($lead['resit'] == 'on')
                                                <span class="badge badge-light-danger fs-7 fw-bold">Resit Work</span>
                                            @endif
                                            @if ($lead['service_type'] == 'First Class Work')
                                                <span class="badge badge-light-info fs-7 fw-bold">First Class Work</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($lead->user && $lead->user->name)
                                                {{ $lead->user->name }}
                                            @endif
                                            <br>

                                            <span class="badge badge-light-danger fs-7 fw-bold">
                                                @if($lead->user && $lead->user->mobile_no)
                                                    {{ $lead->user->mobile_no }}
                                                @endif
                                            </span>

                                            <br>

                                            @if($lead->user && $lead->user->verified == 1)
                                                <span class="badge badge-light-success fs-7 fw-bold">
                                                    Verified
                                                </span>
                                            @else
                                                <span class="badge badge-light-warning fs-7 fw-bold">
                                                    Unverified
                                                </span>
                                            @endif
                                        </td>

                                        <td>{{ \Carbon\Carbon::parse($lead->create_at)->format('d M Y') }}</td>
                                        <td style=>{{ $lead->project_title }}
                                            <br>
                                            @if ($lead->semester)
                                                Semester : {{ $lead->semester }}
                                            @endif


                                            <br>
                                            @if ($lead['tech'] == 'on')
                                                <span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>
                                            @endif

                                            @if ($lead['module_code'] != '')
                                                <span class="badge badge-light-danger fs-7 fw-bold">{{$lead['module_code']}}</span>
                                            @endif
                                        </td>
                                        <td>{{ $lead->pages }}</td>
                                        <td>{{ $lead->price }}</td>
                                        <td>{{$lead->deadline}}
                                            @if($lead->delivery_time)
                                                <span
                                                    class="badge badge-light-info fs-7 fw-bold">({{ $lead->delivery_time }})</span>
                                            @endif

                                            @if($lead->draft_required == 'Yes')
                                                <span class="badge badge-light-success fs-7 fw-bold">{{ $lead->draft_date }}</span>
                                                <br>
                                                <span class="badge badge-light-success fs-7 fw-bold">{{ $lead->draft_time }}</span>
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function handleChange(checkbox, leadId) {
            if (!checkbox.checked) {
                // Show SweetAlert when the checkbox is unchecked
                Swal.fire({
                    title: 'Cancel Lead',
                    text: 'Are you sure you want to cancel this lead?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    // If the user clicks "Yes," send the lead ID to the controller
                    if (result.isConfirmed) {
                        // You can use AJAX to send the lead ID to the controller
                        // For example, using jQuery AJAX
                        $.ajax({
                            url: 'cancel_leads' + leadId,
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },


                            error: function (error) {
                                console.error(error);
                            }
                        });
                    } else {
                        // If the user clicks "No" or closes the SweetAlert
                        // You can handle it here if needed
                        checkbox.checked = true;
                    }
                });
            }
        }
    </script>


    <script>
        function checkedLead(checkbox, leadId) {
            $.ajax({
                url: 'lead/checklead/' + leadId,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (checkbox.checked) {
                        console.log("Lead with ID " + leadId + " cancelled successfully.");
                    } else {
                        console.log("Lead with ID " + leadId + " restored successfully.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error: " + error);
                }
            });
        }
    </script>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.convert-btn{{$lead->id}}').on('click', function (e) {
                e.preventDefault();

                var formData = $('#kt_modal_new_target_form{{$lead->id}}').serialize();

                $.ajax({
                    url: "{{ route('convert.convert', ['id' => $lead->id]) }}",
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        console.log(response.message);

                        // Extract order_id from form data
                        var orderId = $('[name="order_id"]').val();

                        // Redirect to the order page with the order_id parameter
                        window.location.href = "/order?order_id=" + orderId;
                    },
                    error: function (error) {
                        console.error(error);
                        // Check if the error indicates email already exists
                        if (error.responseJSON && error.responseJSON.error === 'Email already registered with a different user') {
                            // Show SweetAlert
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'The email is already registered with a different user!',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Handle other errors as needed
                        }
                    }
                });
            });
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function sendPostRequest(leadId) {
            // Send an AJAX POST request
            $.ajax({
                url: '/convertleads/' + leadId,
                headers:
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                success: function (response) {
                    // Handle success response
                    console.log(response);
                    window.location.href = '/order';
                },
                error: function (xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
    <!-- Add this script to the bottom of your page or within a <script> tag -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to handle the AJAX request
        function sendData(leadId, description) {

            $.ajax({
                type: 'POST',
                url: '{{ route("send-data") }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'description': description,
                    'id': leadId,
                },
                success: function (response) {
                    var messageContainer = $('[data-kt-element="messages' + leadId + '"]');

                    var newMessage = `
                        <div class="d-flex justify-content-end mb-10">
                            <div class="d-flex flex-column align-items-end">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-3">
                                        <span class="text-muted fs-7 mb-1">${response.created_at}</span>
                                        <a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary ms-1">You</a>
                                    </div>
                                    <div class="symbol symbol-35px symbol-circle">
                                        <img alt="Pic" src="${response.avatar}" />
                                    </div>
                                </div>
                                <div class="p-5 rounded bg-light-primary text-dark fw-bold mw-lg-400px text-end" data-kt-element="message-text">${response.message}</div>
                            </div>
                        </div>
                    `;

                    // Use prepend to add the new message at the beginning
                    messageContainer.prepend(newMessage);

                    // Clear the input field
                    $('#description{{$lead->id}}').val('');
                },
            });
        }


    </script>

    <script>
        function leadsExport() {
            // Retrieve filter parameters
            var additionalFilter1 = $('#additional_filter1').val();
            var additionalFilter2 = $('#selectedValue').val();
            var additionalFilter3 = $('#additional_filter3').val();
            var additionalFilter4 = $('#additional_filter4').val();
            var additionalFilter5 = $('#additional_filter5').val();
            var additionalFilter6 = $('#additional_filter6').val();
            var additionalFilter7 = $('#additional_filter7').val();

            // Store filter values in localStorage
            localStorage.setItem('filters', JSON.stringify({
                additionalFilter1: additionalFilter1,
                additionalFilter2: additionalFilter2,
                additionalFilter3: additionalFilter3,
                additionalFilter4: additionalFilter4,
                additionalFilter5: additionalFilter5,
                additionalFilter6: additionalFilter6,
                additionalFilter7: additionalFilter7
            }));

            // Use CSRF token for security
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            // Send AJAX request to export endpoint
            $.ajax({
                type: 'get',
                url: '{{ route('export.leads') }}',
                data: {
                    _token: CSRF_TOKEN,
                    additional_filter1: additionalFilter1,
                    additional_filter2: additionalFilter2,
                    additional_filter3: additionalFilter3,
                    additional_filter4: additionalFilter4,
                    additional_filter5: additionalFilter5,
                    additional_filter6: additionalFilter6,
                    additional_filter7: additionalFilter7
                },
                success: function (data) {
                    // On success, trigger file download
                    var blob = new Blob([data], { type: 'text/csv' });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);

                    // Generate file name with current timestamp
                    var filename = 'leads_' + new Date().toISOString().slice(0, 19).replace(/[-T:/]/g, '') + '.csv';
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