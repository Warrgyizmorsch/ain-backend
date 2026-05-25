@extends('layouts.app')

@section('content')
<div class="min-vh-100">
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Lead Edit
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
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                        </svg> -->
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <form id="kt_modal_new_target_form{{$lead->id}}" class="form" method="POST" action="{{ route('leads.edit', ['id' => $lead->id ]) }}">
                    @csrf
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">Leads View/Edit</h1>
                        <div class="text-muted fw-bold fs-5">{{ $lead->order_id }}</div>
                    </div>
                    
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-6 fv-row">
                            <label class=" fs-6 fw-bold mb-2">User Name</label>
                        <input type="text"  class="form-control form-control-solid" placeholder="" value="{{ $lead->user->name }}" name="user_name">
                            
                        </div>
                        <div class="col-md-6 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Email</label>
                            <!-- <input type="text"  class="form-control form-control-solid" placeholder="" value="{{ $lead->user->email }}" name="email"> -->
                           
                            <input type="text" class="form-control form-control-solid" placeholder="" value="{{ $lead->user->email }}" readonly name="email" id="emailInput" >
                            <input type="hidden" class="form-control form-control-solid" placeholder="" value="" name="id" id="id" >
                        </div>
                    </div>
                    <div class="row g-9 mb-8 text-start order-container" data-order-id="{{$lead->id}}">
                    <div class="col-md-2 fv-row">
                            <label class="fs-6 fw-bold mb-2">Country Code</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" value="{{$lead->user->countrycode}}" name="country_code" id="country_primary">
                        </div>
                        <div class="col-md-3 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Mobile Number</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" value="{{$lead->user->mobile_no}}" name="mobile" id="primary">
                        </div>
                        <div class="col-md-2 fv-row d-flex align-items-end justify-content-center">
                            <!-- Button with double-sided arrow icon -->
                            <button type="button" class="btn btn-primary swapButton" id="swapButton{{$lead->id}}">
                                <i class="fas fa-exchange-alt"></i> <!-- Font Awesome double-sided arrow icon -->
                            </button>
                        </div>
                        <div class="col-md-2 fv-row">
                            <label class="fs-6 fw-bold mb-2">Country Code</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" value="{{$lead->user->countrycode2}}" name="country_code2">
                        </div>
                        <div class="col-md-3 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Mobile Number-2</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" value="{{$lead->user->mobile_no2}}" name="mobile2">
                        </div>
                        
                    </div>
                    <script>
                        document.querySelectorAll('#swapButton{{$lead->id}}').forEach(function(button) {
                            button.addEventListener('click', function () {
                                var orderContainer = button.closest('.order-container');
                                var orderId = orderContainer.getAttribute('data-order-id');
                                
                                fetch('/swap-lead-mobile', {
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
                    
                    <div class="row g-9 mb-8 text-center">
                        <div class="col-md-4 mx-auto fv-row">
                            <label class=" fs-6 fw-bold mb-2">Module Code</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$lead->module_code}}" name="module_code">
                        </div>
                        <div class="col-md-8 mx-auto fv-row">
                            <label class=" fs-6 fw-bold mb-2">Project Title</label>
                            <input type="text" required class="form-control form-control-solid" placeholder="" value="{{$lead->project_title}}" name="project_title">
                        </div>
                    </div>   
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-6 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Word</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$lead->pages}}" name="pages">
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-bold mb-2">Lead Status</label>
                            <select name="l_status" aria-label="Select Lead Status" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                <option value="" <?php if (empty($lead['l_status'])) echo "selected"; ?> data-select2-id="select2-data-18-e9lh12"></option>
                                <option <?php if ($lead['l_status'] == 'Waiting') echo "selected"; ?> value="Waiting">Waiting</option>
                                <option <?php if ($lead['l_status'] == 'Quote') echo "selected"; ?> value="Quote">Quote</option>
                                <option <?php if ($lead['l_status'] == 'Confirmation') echo "selected"; ?> value="Confirmation">Confirmation</option>
                            </select>
                        </div>

                    </div>

                    <div class="row g-9 mb-8 text-start">
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-bold mb-2">Services</label>
                        <select name="service_type" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                            <option value="" <?php if (empty($lead['service_type'])) echo "selected"; ?> data-select2-id="select2-data-18-e9lh12"></option>
                            @foreach($service as $service)
                                <option <?php if ($lead['service_type'] == $service['service_name']) echo "selected"; ?> value="{{ $service['service_name'] }}">{{ $service['service_name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 fv-row d-flex">
                        <label class="fs-6 fw-bold mb-2">Technical</label>
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                            <input name="tech" style="height: 2.5rem;width: 2.5rem;" 
                                class="form-check-input submenu-checkbox" 
                                type="checkbox"  data-kt-check="true" 
                                data-kt-check-target=".widget-9-check"
                                {{ $lead['tech'] == 'on' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="col-md-3 fv-row d-flex">
                        <label class="fs-6 fw-bold mb-2">Resit</label>
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                            <input name="resit" style="height: 2.5rem;width: 2.5rem;" 
                                class="form-check-input submenu-checkbox" 
                                type="checkbox"  data-kt-check="true" 
                                data-kt-check-target=".widget-9-check"
                                {{ $lead['resit'] == 'on' ? 'checked' : '' }}>
                        </div>
                    </div>
                    
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-6 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Delivery Date</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" id="DeliveryDate" value="{{ $lead->deadline }}" name="delivery_date" onchange="showSelectedDate(this)" data-created-at="{{ \Carbon\Carbon::parse($lead->create_at)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Delivery Time</label>
                            <input type="time" class="form-control form-control-solid" placeholder="" value="{{ $lead->delivery_time }}" name="delivery_time" onchange="showSelectedTime(this)">
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Type Of Paper</label>
                            <select id="typeOfPaper" name="paper" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
						        <option value="" data-select2-id="select2-data-18-e9lh12">Not Selected</option>
                                @foreach($papers as $paper)
                                    <option <?php if ( $lead->typeofpaper == $paper->paper_type) {echo "selected";} ?> value="{{$paper->paper_type}}">{{$paper->paper_type}}</option>
                                @endforeach   
                            </select>                         
                        </div>
                       
                        <div class="col-md-6 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Chapter</label>
                            <select id="chapter" name="chapter"aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                <option value=" " <?php if ( $lead->chapter == ' ') {echo "selected";} ?>> </option>
                                <option value="Chapter 1:  Introduction" <?php if ($lead->chapter == 'Chapter 1:  Introduction') {echo "selected";} ?>>Chapter 1:  Introduction</option>
                                <option value="Chapter 2: Litreature Review" <?php if ($lead->chapter == 'Chapter 2: Litreature Review') {echo "selected";} ?>>Chapter 2: Litreature Review</option>
                                <option value="Chapter 3: Methedology" <?php if ($lead->chapter == 'Chapter 3: Methedology') {echo "selected";} ?>>Chapter 3: Methedology</option>
                                <option value="Chapter 4: Data Analysis" <?php if ($lead->chapter == 'Chapter 4: Data Analysis') {echo "selected";} ?>>Chapter 4: Data Analysis</option>
                                <option value="Chapter 5: Conclusion & Recommendation" <?php if ($lead->chapter== 'Chapter 5: Conclusion & Recommendation') {echo "selected";} ?>>Chapter 5: Conclusion & Recommendation</option>
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
                        <div class="col-md-6 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Amount</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$lead->price}}" name="price">
                        </div>
                         <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-bold mb-2">Draft Required</label>
                            <select name="draft_required" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true" onchange="showHideDiv(this);">
                                <?php if ($lead->draft_required == "Yes"): ?>
                                    <option value="">No</option>
                                    <option value="Yes" selected>Yes</option>
                                <?php else: ?>
                                    <option value="" selected>No</option>
                                    <option value="Yes">Yes</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-9 mb-8 text-start" id="newDiv" style="<?php echo $lead->draft_required === 'Yes' ? '' : 'display:none;'; ?>">
                        <div class="col-md-6 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Draft Date</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" value="{{$lead->draft_date}}" name="draft_date">
                        </div>
                        <div class="col-md-6 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Draft Time</label>
                            <input type="time" class="form-control form-control-solid" placeholder="" value="{{$lead->draft_time}}" name="draft_time">
                        </div>
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-bold mb-2">Semester</label>
                        <select id="semester" name="semester" aria-label="Select a Semester" data-control="select2" class="form-select form-select-solid form-select-lg" tabindex="-1" aria-hidden="true">
                            <option value="">Select Semester</option>
                            <option {{ ($lead['semester'] == 'I Semester') ? 'selected' : '' }} value="I Semester">I Semester</option>
                            <option {{ ($lead['semester'] == 'II Semester') ? 'selected' : '' }} value="II Semester">II Semester</option>
                            <option {{ ($lead['semester'] == 'III Semester') ? 'selected' : '' }} value="III Semester">III Semester</option>
                            <option {{ ($lead['semester'] == 'IV Semester') ? 'selected' : '' }} value="IV Semester">IV Semester</option>
                            <option {{ ($lead['semester'] == 'final semester') ? 'selected' : '' }} value="final semester">final semester</option>
                        </select>
                    </div>

                    <div class="row g-9 mb-8 text-center">
                        <div class="col-md-12 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Messages</label>
                            <textarea name="message"  class="form-control form-control-solid" id="" cols="30" rows="3" >{{$lead->message}}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="action" value="submit">Submit</button>
                        <button type="button" class="btn btn-secondary convert-btn{{$lead->id}}" data-lead-id="{{$lead->id}}">Convert</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
    </div> 
</div> 
   
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        $('.convert-btn{{$lead->id}}').on('click', function (e) {
            e.preventDefault();

            var formData = $('#kt_modal_new_target_form{{$lead->id}}').serialize();

            $.ajax({
                url: "{{ route('convert.convert', ['id' => $lead->id ]) }}",
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



<!-- Add the following script at the end of the file -->
<script>
    function showHideDiv(select) {
        var selectedOption = select.value;
        var newDiv = document.getElementById("newDiv");

        if (selectedOption === "Yes") {
            newDiv.style.display = "flex";
        } else {
            newDiv.style.display = "none";
        }
    }
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function(){
        // Get the created_at date from the data attribute
        var createdAt = $('#DeliveryDate').data('created-at');
    
        // Set the minimum date attribute to the created_at date
        $('#DeliveryDate').attr('min', createdAt);
    });
</script>

<!-- Add the following script at the end of the file -->




@endsection