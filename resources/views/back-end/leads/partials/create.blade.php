    {{-- <div class="modal fade" id="kt_modal_create_appaa_newLeads" tabindex="-1" aria-hidden="true">
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
                    <form id="kt_modal_new_target_form" class="form" method="POST" action="{{ route('leads') }}">
    @csrf
    @method('POST')
    <div class="mb-13 text-center">
        <h1 class="mb-3">Create New Leads</h1>
        <div class="text-muted fw-bold fs-5"></div>
    </div>

    <div class="row g-9 mb-8 text-start">
        <div class="col-md-6 fv-row">
            <label class="fs-6 fw-bold mb-2">User Name</label>
            <input type="text" id="nameInput" class="form-control form-control-solid" placeholder="" value="" name="user_name">
        </div>
        <div class="col-md-6 fv-row text-start">
            <label class="fs-6 fw-bold mb-2">Email</label>
            <input type="text" class="form-control form-control-solid" placeholder="" value="" name="email" id="emailInput">
            <input type="hidden" class="form-control form-control-solid" placeholder="" value="" name="id" id="id">
        </div>
    </div>
    <div class="row g-9 mb-8 text-start" id="primaryMobileFields">
        <div class="col-md-6 fv-row">
            <label class="fs-6 fw-bold mb-2">Country Code</label>
            <input type="text" required class="form-control form-control-solid" placeholder="" value="" name="countrycode">
        </div>
        <div class="col-md-6 fv-row text-start">
            <label class="fs-6 fw-bold mb-2">Mobile Number</label>
            <input type="text" required id="mobile" class="form-control form-control-solid" placeholder="" value="" name="mobile">
        </div>
    </div>
    <div class="row g-9 mb-8 text-start" id="secondaryMobileFields" style="display: none;">
        <div class="col-md-6 fv-row">
            <label class="fs-6 fw-bold mb-2">Secondary Country Code</label>
            <input type="text" class="form-control form-control-solid" placeholder="" value="" name="countrycode2">
        </div>
        <div class="col-md-6 fv-row text-start">
            <label class="fs-6 fw-bold mb-2">Secondary Mobile Number</label>
            <input type="text" id="mobile2" class="form-control form-control-solid" placeholder="" value="" name="mobile_no2">
        </div>
    </div>

    <script>
        document.getElementById("mobile").addEventListener("input", function(event) {
            let inputValue = event.target.value;
            let numericInput = inputValue.replace(/\D/g, ""); // Replace non-numeric characters with an empty string
            event.target.value = numericInput; // Update the input field value with only numeric characters

            // Toggle visibility of secondary mobile fields based on primary mobile number input
            if (inputValue.trim() !== "") {
                document.getElementById("secondaryMobileFields").style.display = "flex";
            } else {
                document.getElementById("secondaryMobileFields").style.display = "none";
            }
        });
    </script>
    <div class="row g-9 mb-8 ">
        <div class="col-md-4 mx-auto fv-row">
            <label class=" fs-6 fw-bold mb-2">Module Code</label>
            <input type="text" class="form-control form-control-solid" placeholder="" value="" name="module_code">
        </div>
        <div class="col-md-8 mx-auto fv-row">
            <label class=" fs-6 fw-bold mb-2">Project Title</label>
            <input type="text" class="form-control form-control-solid" placeholder="" value="" name="project_title">
        </div>
    </div>
    <div class="row g-9 mb-8 text-start">
        <div class="col-md-6 fv-row">
            <label class=" fs-6 fw-bold mb-2">Word</label>
            <input type="text" class="form-control form-control-solid" placeholder="" value="" name="pages">
        </div>

        <div class="col-md-6 fv-row">
            <label class=" fs-6 fw-bold mb-2">Lead Status</label>
            <select name="i_status" aria-label="Select a Timezone" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                <option value="Waiting">Waiting</option>
                <option value="Quote">Quote</option>
                <option value="Confirmation">Confirmation</option>
            </select>
        </diV>

    </div>

    <div class="row g-9 mb-8 text-start">
        <div class="col-md-6 fv-row">
            <label class="fs-6 fw-bold mb-2">Services</label>
            <select name="service_type" class="form-select">
                <option value="">Select Service</option>
                @foreach($service as $s)
                <option value="{{ $s->service_name }}" {{ $lead->service_type === $s->service_name ? 'selected' : '' }}>{{ $s->service_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 fv-row d-flex">
            <label class="fs-6 fw-bold mb-2">Technical</label>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input name="tech" style="height: 2.5rem;width: 2.5rem;"
                    class="form-check-input submenu-checkbox"
                    type="checkbox" data-kt-check="true"
                    data-kt-check-target=".widget-9-check">
            </div>
        </div>

        <div class="col-md-3 fv-row d-flex">
            <label class="fs-6 fw-bold mb-2">Resit</label>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input name="resit" style="height: 2.5rem;width: 2.5rem;"
                    class="form-check-input submenu-checkbox"
                    type="checkbox" data-kt-check="true"
                    data-kt-check-target=".widget-9-check">
            </div>
        </div>

        <div class="row g-9 mb-8 text-start">
            <div class="col-md-6 fv-row text-start">
                <label class="fs-6 fw-bold mb-2">Delivery Date</label>
                <input type="date" class="form-control form-control-solid" id="inputdate" placeholder="" value="" name="delivery_date" onchange="showSelectedDate(this)">
            </div>
            <div class="col-md-6 fv-row text-start">
                <label class="fs-6 fw-bold mb-2">Delivery Time</label>
                <input type="time" class="form-control form-control-solid" placeholder="" value="" name="delivery_time" onchange="showSelectedDate(this)">
            </div>
            {{-- <div class="col-md-6 fv-row">
                                    <label class=" fs-6 fw-bold mb-2">Type Of Paper</label>
                                    <select name="paper" class="form-select">
                                        <option value="">Not Selected</option>
                                        @foreach($papers as $paper)
                                            <option value="{{ $paper->paper_type }}" {{ $lead->typeofpaper === $paper->paper_type ? 'selected' : '' }}>{{ $paper->paper_type }}</option>
            @endforeach
            </select>
        </div>

        <div class="col-md-6 fv-row">
            <label class=" fs-6 fw-bold mb-2">Chapter</label>
            <select id="chapter" name="chapter" aria-label="Select a Timezone" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                <option value=" "> </option>
                <option value="Chapter 1:  Introduction">Chapter 1: Introduction</option>
                <option value="Chapter 2: Litreature Review">Chapter 2: Litreature Review</option>
                <option value="Chapter 3: Methedology">Chapter 3: Methedology</option>
                <option value="Chapter 4: Data Analysis">Chapter 4: Data Analysis</option>
                <option value="Chapter 5: Conclusion & Recommendation">Chapter 5: Conclusion & Recommendation</option>
            </select>
        </div> --}}

        {{-- <div class="col-md-6 fv-row">
                                    <label class=" fs-6 fw-bold mb-2">Type Of Paper</label>
                                    <select name="paper" id="typeOfPaper" class="form-select form-select-solid" data-control="select2" data-placeholder="Select Paper Type">
                                        <option value="">Not Selected</option>
                                        @foreach($papers as $paper)
                                            <option value="{{ $paper->paper_type }}" {{ $lead->typeofpaper === $paper->paper_type ? 'selected' : '' }}>{{ $paper->paper_type }}</option>
        @endforeach
        </select>
    </div>

    <div class="col-md-6 fv-row">
        <label class="d-block fs-6 fw-bold mb-2">Chapter</label>
        <select id="chapter" name="chapter[]"
            class="form-select form-select-solid"
            data-control="select2"
            data-placeholder="Select Chapters"
            multiple="multiple">
            <option value="Chapter 1: Introduction">Chapter 1: Introduction</option>
            <option value="Chapter 2: Litreature Review">Chapter 2: Litreature Review</option>
            <option value="Chapter 3: Methedology">Chapter 3: Methedology</option>
            <option value="Chapter 4: Data Analysis">Chapter 4: Data Analysis</option>
            <option value="Chapter 5: Conclusion & Recommendation">Chapter 5: Conclusion & Recommendation</option>
        </select>
    </div> --}}
    {{-- <script>
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

                                </script> --}}

    {{-- <script>
                                    $(document).ready(function () {
                                        const $paperSelect = $('#typeOfPaper');
                                        const $chapterSelect = $('#chapter');

                                        // 1. UI aur Enable/Disable Handle karne ka function
                                        function refreshUI() {
                                            const paperType = $paperSelect.val();
                                            const allowedTypes = ['Dissertation', 'Thesis', 'Assignment', 'Assignments'];

                                            if (allowedTypes.includes(paperType)) {
                                                $chapterSelect.prop('disabled', false);
                                            } else {
                                                // Agar allow nahi hai toh selection clear karke disable karo
                                                $chapterSelect.val(null).trigger('change');
                                                $chapterSelect.prop('disabled', true);
                                            }
                                            // UI update karne ke liye trigger
                                            $chapterSelect.trigger('change');
                                        }

                                        // 2. Multiple Selection Control (Professional Workaround)
                                        $chapterSelect.on('select2:selecting', function (e) {
                                            const paperType = $paperSelect.val();
                                            
                                            // Agar Dissertation ya Assignment NAHI hai
                                            if (paperType !== 'Dissertation' && paperType !== 'Assignments' && paperType !== 'Assignment') {
                                                // Toh ek se zyada select nahi hone dega (Purana hata dega, naya rakh lega)
                                                $(this).val(null).trigger('change.select2'); 
                                            }
                                        });

                                        // Event Listeners
                                        $paperSelect.on('change', function() {
                                            refreshUI();
                                        });

                                        // Initial load par check karein
                                        refreshUI();
                                    });
                                </script>
                                <div class="col-md-6 fv-row">
                                    <label class=" fs-6 fw-bold mb-2">Amount</label>
                                    <input type="text"  class="form-control form-control-solid" placeholder="" value="" name="amount">
                                </div>
                                <div class="col-md-6 fv-row" >
                                <label class=" fs-6 fw-bold mb-2">Draft Required</label>
                                <select name="draft_required" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true" onchange="showHideDiv(this);">
                                    <option value="" data-select2-id="select2-data-18-e9lh12">No</option>
                                    <option value="Yes" >Yes</option>
                                </select>              
                                </div>
                            </div>
                        </div>
                        <div class="row g-9 mb-8 text-start" id="newDiv" style="display:none;">
                            <div class="col-md-6 fv-row text-start">
                                <label class="fs-6 fw-bold mb-2">Draft Date</label>
                                <input type="date" class="form-control form-control-solid" placeholder="" value="" name="draft_date" onchange="showSelectedDate(this)">
                            </div>
                            <div class="col-md-6 fv-row text-start">
                                <label class="fs-6 fw-bold mb-2">Draft Time</label>
                                <input type="time" class="form-control form-control-solid" placeholder="" value="" name="draft_time" onchange="showSelectedDate(this)">
                            </div>
                    
                        </div>

                            <div class="col-md-6 fv-row">
                                <label class=" fs-6 fw-bold mb-2">Semester</label>
                                <select required id="semester" name="semester"aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg"  tabindex="-1" aria-hidden="true">
                                    <option value=" ">Select Semester </option>
                                    <option value="I Semester" >I Semester</option>
                                    <option value="II Semester" >II Semester</option>
                                    <option value="III Semester" >III Semester</option>
                                    <option value="IV Semester" >IV Semester</option>
                                    <option value="final Semester" >final Semester</option>
                                </select>    
                            </div>
                        

                            <div class="row g-9 mb-8 text-center">
                                <div class="col-md-12 fv-row">
                                    <label class=" fs-6 fw-bold mb-2">Messages</label>
                                    <textarea name="message"  class="form-control form-control-solid" id="" cols="30" rows="3"></textarea>
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function () {
            // Search functionality
            $('#mobile').on('keyup', function () {
                var $value = $(this).val();

                $.ajax({
                    type: 'get',
                    url: '{{ url('userData') }}',
    data: {'mobile': $value},
    success: function (data) {
    if (data.user) {
    $('input[name="user_name"]').val(data.user.name);
    $('input[name="countrycode"]').val(data.user.countrycode);
    $('input[name="countrycode2"]').val(data.user.countrycode2);
    $('input[name="email"]').val(data.user.email);
    $('input[name="mobile_no2"]').val(data.user.mobile_no2);
    $('input[name="id"]').val(data.user.id);

    // Make the email input box writable
    $('input[name="email"]').prop('readonly', true);
    $('input[name="countrycode"]').prop('readonly', false);
    $('input[name="countrycode2"]').prop('readonly', false);
    $('input[name="user_name"]').prop('readonly', false);
    $('input[name="id"]').prop('readonly', false);
    } else {
    // Reset the input boxes and make email input writable
    $('input[name="user_name"]').val('');
    $('input[name="countrycode"]').val('');
    $('input[name="countrycode2"]').val('');
    $('input[name="email"]').val('');
    $('input[name="mobile_no2"]').val('');
    $('input[name="id"]').val('');

    $('input[name="email"]').prop('readonly', false);
    $('input[name="countrycode"]').prop('readonly', false);
    $('input[name="countrycode2"]').prop('readonly', false);
    $('input[name="user_name"]').prop('readonly', false);
    $('input[name="id"]').prop('readonly', false);
    }
    },
    error: function (data) {
    // If there is an error, log it to the console
    console.log('Error:', data);
    // Reset the input boxes and make email input writable
    $('input[name="user_name"]').val('');
    $('input[name="countrycode"]').val('');
    $('input[name="countrycode2"]').val('');
    $('input[name="email"]').val('');
    $('input[name="mobile_no2"]').val('');

    $('input[name="email"]').prop('readonly', false);
    $('input[name="countrycode"]').prop('readonly', false);
    $('input[name="countrycode2"]').prop('readonly', false);
    $('input[name="user_name"]').prop('readonly', false);
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
    </script> --}}


    <!-- <div class="modal fade" id="kt_modal_create_appaa_newLeads" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-1000px">
            <div class="modal-content rounded shadow" style="height: 100%;">
                <div class="modal-header py-3 border-0 justify-content-between px-6">
                    <h2 class="fw-bolder mb-0">Create New Leads</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body px-8 pt-0 pb-6">
                    <form id="kt_modal_new_target_form" class="form" method="POST" action="{{ route('leads') }}">
                        @csrf
                        @method('POST')

                        <div class="row g-3 mb-3 mt-1">
                            <div class="col-md-3 fv-row">
                                <label class="fs-7 fw-bold mb-1">User Name</label>
                                <input type="text" name="user_name" class="form-control form-control-sm form-control-solid" placeholder="Enter full name">
                            </div>
                            <div class="col-md-3 fv-row">
                                <label class="fs-7 fw-bold mb-1">Email Address</label>
                                <input type="email" name="email" id="emailInput" class="form-control form-control-sm form-control-solid" placeholder="example@mail.com">
                                <input type="hidden" name="id" id="id">
                            </div>
                            <div class="col-md-3 fv-row">
                                <label class="fs-7 fw-bold mb-1">Country Code</label>
                                <input type="text" name="countrycode" required class="form-control form-control-sm form-control-solid" placeholder="+91">
                            </div>
                            <div class="col-md-3 fv-row">
                                <label class="fs-7 fw-bold mb-1">Mobile Number</label>
                                <input type="text" name="mobile" id="mobile" required class="form-control form-control-sm form-control-solid" placeholder="9876543210">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-2 fv-row">
                                <label class="fs-7 fw-bold mb-1">Module Code</label>
                                <input type="text" name="module_code" class="form-control form-control-sm form-control-solid" placeholder="Code (e.g. CS101)">
                            </div>
                            <div class="col-md-5 fv-row">
                                <label class="fs-7 fw-bold mb-1">Project Title</label>
                                <input type="text" name="project_title" class="form-control form-control-sm form-control-solid" placeholder="Enter project topic">
                            </div>
                            <div class="col-md-2 fv-row">
                                <label class="fs-7 fw-bold mb-1">Word/Pages</label>
                                <input type="text" name="pages" class="form-control form-control-sm form-control-solid" placeholder="e.g. 2000 words">
                            </div>
                            <div class="col-md-3 fv-row">
                                <label class="fs-7 fw-bold mb-1">Lead Status</label>
                                <select name="i_status" data-control="select2" data-placeholder="Select Status" class="form-select form-select-sm form-select-solid">
                                    <option value=""></option>
                                    <option value="Waiting">Waiting</option>
                                    <option value="Quote">Quote</option>
                                    <option value="Confirmation">Confirmation</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3 align-items-end">
                            <div class="col-md-4 fv-row">
                                <label class="fs-7 fw-bold mb-1">Services</label>
                                <select name="service_type" data-control="select2" data-placeholder="Choose Service" class="form-select form-select-sm form-select-solid">
                                    <option value="">Select Service</option>
                                    @foreach($service as $s)
                                        <option value="{{ $s->service_name }}" {{ isset($lead) && $lead->service_type === $s->service_name ? 'selected' : '' }}>{{ $s->service_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 fv-row">
                                <label class="fs-7 fw-bold mb-1">Type Of Paper</label>
                                <select name="paper" id="typeOfPaper" data-control="select2" data-placeholder="Select Paper Type" class="form-select form-select-sm form-select-solid">
                                    <option value="">Not Selected</option>
                                    @foreach($papers as $paper)
                                        <option value="{{ $paper->paper_type }}" {{ isset($lead) && $lead->typeofpaper === $paper->paper_type ? 'selected' : '' }}>{{ $paper->paper_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 fv-row">
                                <div class="d-flex align-items-center mb-2" style="height: 32px;"> 
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                        <input name="tech" class="form-check-input h-18px w-18px" type="checkbox">
                                        <label class="form-check-label fs-7 fw-bold">Technical</label>
                                    </div>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input name="resit" class="form-check-input h-18px w-18px" type="checkbox">
                                        <label class="form-check-label fs-7 fw-bold">Resit</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 fv-row">
                                <label class="fs-7 fw-bold mb-1">Amount</label>
                                <input type="text" name="amount" class="form-control form-control-sm form-control-solid" placeholder="0.00">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6 fv-row">
                                <label class="fs-7 fw-bold mb-1">Chapters</label>
                                <select id="chapter" name="chapter[]" data-control="select2" data-placeholder="Select Chapters" multiple="multiple" class="form-select form-select-sm form-select-solid">
                                    <option value="Chapter 1: Introduction">Chapter 1: Introduction</option>
                                    <option value="Chapter 2: Litreature Review">Chapter 2: Litreature Review</option>
                                    <option value="Chapter 3: Methedology">Chapter 3: Methedology</option>
                                    <option value="Chapter 4: Data Analysis">Chapter 4: Data Analysis</option>
                                    <option value="Chapter 5: Conclusion & Recommendation">Chapter 5: Conclusion & Recommendation</option>
                                </select>
                            </div>
                            <div class="col-md-3 fv-row">
                                <label class="fs-7 fw-bold mb-1">Delivery Date</label>
                                <input type="date" name="delivery_date" class="form-control form-control-sm form-control-solid">
                            </div>
                            <div class="col-md-3 fv-row">
                                <label class="fs-7 fw-bold mb-1">Delivery Time</label>
                                <input type="time" name="delivery_time" class="form-control form-control-sm form-control-solid">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-3 fv-row">
                                <label class="fs-7 fw-bold mb-1">Draft Required</label>
                                <select name="draft_required" onchange="showHideDiv(this);" data-control="select2" data-placeholder="Draft needed?" class="form-select form-select-sm form-select-solid">
                                    <option value="">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                            <div class="col-md-6 fv-row" id="newDiv" style="display:none;">
                                <label class="fs-7 fw-bold mb-1">Draft Deadline</label>
                                <div class="d-flex gap-2">
                                    <input type="date" name="draft_date" class="form-control form-control-sm form-control-solid w-50">
                                    <input type="time" name="draft_time" class="form-control form-control-sm form-control-solid w-50">
                                </div>
                            </div>
                            <div class="col-md-3 fv-row">
                                <label class="fs-7 fw-bold mb-1">Semester</label>
                                <select name="semester" data-control="select2" data-placeholder="Choose Semester" class="form-select form-select-sm form-select-solid">
                                    <option value="">Select</option>
                                    <option value="I Semester">I Semester</option>
                                    <option value="II Semester">II Semester</option>
                                    <option value="III Semester">III Semester</option>
                                    <option value="IV Semester">IV Semester</option>
                                    <option value="final Semester">final Semester</option>
                                </select>
                            </div>
                        </div>

                        <div class="fv-row mb-3">
                            <label class="fs-7 fw-bold mb-1">Messages</label>
                            <textarea name="message" class="form-control form-control-sm form-control-solid" rows="2" placeholder="Write any specific instructions here..."></textarea>
                        </div>

                        <div class="text-end pt-2">
                            <button type="button" class="btn btn-light btn-sm me-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm px-8">Submit Lead</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->

    <div class="modal fade" id="kt_modal_create_appaa_newLeads" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-1000px">
            <div class="modal-content rounded shadow" style="height: 100%;">
                <div class="modal-header py-3 border-0 justify-content-between px-6">
                    <h2 class="fw-bolder mb-0">Create New Leads</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body px-8 pt-0 pb-6">
                    <form id="kt_modal_new_target_form" class="form" method="POST" action="{{ route('leads') }}">
                        @csrf
                        @method('POST')

                        <div class="card p-4 mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold">User Name</label>
                                    <input type="text" name="user_name" class="form-control form-control-sm form-control-solid">
                                </div>

                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold">Email</label>
                                    <input type="email"
                                        name="email"
                                        class="form-control form-control-sm form-control-solid">

                                    <input type="hidden" name="id" id="id">
                                </div>

                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold">Country Code</label>
                                    <input type="text"
                                        name="countrycode"
                                        class="form-control form-control-sm form-control-solid">
                                </div>

                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold">Mobile</label>
                                    <input type="text"
                                        name="mobile"
                                        id="mobile"
                                        class="form-control form-control-sm form-control-solid">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold mb-1">Lead Source</label>
                                    <select name="lead_source" id="lead_source" class="form-select form-select-solid">

                                        <option value="">All Sources</option>

                                        @foreach($sources as $source)
                                        <option value="{{ $source->id }}"
                                            {{ request('lead_source') == 'source_'.$source->id ? 'selected' : '' }}>

                                            {{ $source->source_name }}
                                        </option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold">Semester</label>
                                    <select name="semester" class="form-select form-select-sm form-select-solid">
                                        <option value="">Select</option>
                                        <option>I Semester</option>
                                        <option>II Semester</option>
                                        <option>III Semester</option>
                                        <option>IV Semester</option>
                                        <option>Final Semester</option>
                                    </select>
                                </div>
                                <div class="col-md-3 position-relative">
                                    <label class="fs-7 fw-bold">Referred By</label>

                                    <input type="text"
                                        id="refer_search"
                                        class="form-control form-control-sm form-control-solid"
                                        placeholder="Name, Email, Mobile search">

                                    <input type="hidden" name="refer_id" id="refer_id">

                                    <div id="refer_result"
                                        class="bg-white border rounded shadow-sm position-absolute w-100"
                                        style="display:none; z-index:9999; max-height:220px; overflow-y:auto;">
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="text-end mb-3">
                            <button type="button" id="addLeadBox" class="btn btn-sm btn-primary">
                                + Add More
                            </button>
                        </div>

                        <div id="leadContainer">

                            <div class="lead-box card p-4 mb-4  position-relative border border-dark">

                                <!-- REMOVE BUTTON -->
                                <div class="d-flex justify-content-end gap-4 mb-2">
                                    <button type="button" class="btn btn-sm btn-primary toggle-extra">
                                        + Additional fields
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger remove-box">
                                        Remove
                                    </button>
                                </div>

                                <!-- ROW 1 -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-2">
                                        <label class="fs-7 fw-bold">Module Code</label>
                                        <input type="text" name="module_code[]" class="form-control form-control-sm form-control-solid">
                                    </div>

                                    <div class="col-md-5">
                                        <label class="fs-7 fw-bold">Project Title</label>
                                        <input type="text" name="project_title[]" class="form-control form-control-sm form-control-solid">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="fs-7 fw-bold">Word/Pages</label>
                                        <input type="text" name="pages[]" class="form-control form-control-sm form-control-solid">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="fs-7 fw-bold">Type Of Paper</label>
                                        <select name="paper[]" class="form-select form-select-sm form-select-solid">
                                            <option value="">Not Selected</option>
                                            @foreach($papers as $paper)
                                            <option value="{{ $paper->paper_type }}" {{ isset($lead) && $lead->typeofpaper === $paper->paper_type ? 'selected' : '' }}>{{ $paper->paper_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- <div class="col-md-3">
                                        <label class="fs-7 fw-bold">Lead Status</label>
                                        <select name="i_status[]" class="form-select form-select-sm form-select-solid">
                                            <option value="">Select Status</option>
                                            <option>Waiting</option>
                                            <option>Quote</option>
                                            <option>Confirmation</option>
                                        </select>
                                    </div> -->
                                </div>

                                <!-- ROW 2 -->
                                <div class="row g-3 mb-3">
                                    <!-- <div class="col-md-4">
                                        <label class="fs-7 fw-bold">Services</label>
                                        <select name="service_type[]" class="form-select form-select-sm form-select-solid">
                                            <option value="">Select Service</option>
                                            @foreach($service as $s)
                                            <option value="{{ $s->service_name }}" {{ isset($lead) && $lead->service_type === $s->service_name ? 'selected' : '' }}>{{ $s->service_name }}</option>
                                            @endforeach
                                        </select>
                                    </div> -->



                                    <!-- <div class="col-md-3 d-flex align-items-center">
                                        <input type="checkbox" name="tech[]" class="me-2"> Technical
                                        <input type="checkbox" name="resit[]" class="ms-3 me-2"> Resit
                                    </div> -->

                                    <div class="col-md-2">
                                        <label class="fs-7 fw-bold">Amount</label>
                                        <input type="text" name="amount[]" class="form-control form-control-sm form-control-solid">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="fs-7 fw-bold mb-1">Chapters</label>
                                        <select name="chapter[]" data-control="select2" data-placeholder="Select Chapters" multiple="multiple" class="form-select form-select-sm form-select-solid chapter-select">
                                            <option value="Chapter 1: Introduction">Chapter 1: Introduction</option>
                                            <option value="Chapter 2: Litreature Review">Chapter 2: Litreature Review</option>
                                            <option value="Chapter 3: Methedology">Chapter 3: Methedology</option>
                                            <option value="Chapter 4: Data Analysis">Chapter 4: Data Analysis</option>
                                            <option value="Chapter 5: Conclusion & Recommendation">Chapter 5: Conclusion & Recommendation</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="fs-7 fw-bold">Delivery Date</label>
                                        <input type="date" name="delivery_date[]" class="form-control form-control-sm form-control-solid">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="fs-7 fw-bold">Delivery Time</label>
                                        <input type="time" name="delivery_time[]" class="form-control form-control-sm form-control-solid">
                                    </div>



                                    <!-- ROW 3 -->




                                    <!-- <div class="row g-3 mb-3">
                                    <div class="col-md-3 fv-row">
                                        <label class="fs-7 fw-bold mb-1">Draft Required</label>
                                        <select name="draft_required[]" onchange="showHideDiv(this);" data-placeholder="Draft needed?" class="form-select form-select-sm form-select-solid">
                                            <option value="">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 fv-row draftDiv" style="display:none;">
                                        <label class="fs-7 fw-bold mb-1">Draft Deadline</label>
                                        <div class="d-flex gap-2">
                                            <input type="date" name="draft_date[]" class="form-control form-control-sm form-control-solid w-50">
                                            <input type="time" name="draft_time[]" class="form-control form-control-sm form-control-solid w-50">
                                        </div>
                                    </div>

                                </div> -->

                                    <!-- MESSAGE -->
                                    <!-- <div>
                                    <label class="fs-7 fw-bold">Message</label>
                                    <textarea name="message[]" class="form-control form-control-sm form-control-solid"></textarea>
                                </div> -->

                                    <div class="extra-fields" style="display: none;">

                                        <!-- Services -->
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-4">
                                                <label class="fs-7 fw-bold">Services</label>
                                                <select name="service_type[]" class="form-select form-select-sm form-select-solid">
                                                    <option value="">Select Service</option>
                                                    @foreach($service as $s)
                                                    <option value="{{ $s->service_name }}">{{ $s->service_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3 d-flex align-items-center">
                                                <input type="checkbox" name="tech[]" class="me-2"> Technical
                                                <input type="checkbox" name="resit[]" class="ms-3 me-2"> Resit
                                            </div>


                                            <div class="col-md-3 fv-row">
                                                <label class="fs-7 fw-bold mb-1">Draft Required</label>
                                                <select name="draft_required[]" onchange="showHideDiv(this);" data-placeholder="Draft needed?" class="form-select form-select-sm form-select-solid">
                                                    <option value="">No</option>
                                                    <option value="Yes">Yes</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 fv-row draftDiv" style="display:none;">
                                                <label class="fs-7 fw-bold mb-1">Draft Deadline</label>
                                                <div class="d-flex gap-2">
                                                    <input type="date" name="draft_date[]" class="form-control form-control-sm form-control-solid w-50">
                                                    <input type="time" name="draft_time[]" class="form-control form-control-sm form-control-solid w-50">
                                                </div>
                                            </div>

                                        </div>

                                        <!-- Message -->
                                        <div>
                                            <label class="fs-7 fw-bold">Message</label>
                                            <textarea name="message[]" class="form-control form-control-sm form-control-solid"></textarea>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="text-end pt-2">
                            <button type="button" class="btn btn-light btn-sm me-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm px-8">Submit Lead</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    let loggedInRoleId = {{ auth()->user()->role_id ?? 0 }};
</script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.addEventListener('click', function(e) {

                // 🔹 Toggle additional fields
                if (e.target.classList.contains('toggle-extra')) {

                    let box = e.target.closest('.lead-box');
                    let extra = box.querySelector('.extra-fields');

                    if (extra.style.display === 'none') {
                        extra.style.display = 'block';
                        e.target.innerText = '- Hide';
                    } else {
                        extra.style.display = 'none';
                        e.target.innerText = '+ Additional';
                    }
                }

            });

            const addBtn = document.getElementById("addLeadBox");
            const container = document.getElementById("leadContainer");

            // INITIAL SELECT2 LOAD
            initSelect2();

            // ======================
            // ADD / CLONE BOX
            // ======================

            window.showHideDiv = function(select) {
                var selectedOption = select.value;

                // sirf current box ke andar search kare
                var parentBox = select.closest(".lead-box");

                var newDiv = parentBox.querySelector(".draftDiv");

                if (selectedOption === "Yes") {
                    newDiv.style.display = "block";
                } else {
                    newDiv.style.display = "none";
                }
            }

            addBtn.addEventListener("click", function() {

                let firstBox = document.querySelector(".lead-box");
                let clone = firstBox.cloneNode(true);

                clone.querySelectorAll('.draftDiv').forEach(div => {
                    div.style.display = "none";
                });

                // CLEAR INPUTS
                clone.querySelectorAll("input").forEach(input => {
                    if (input.type === "checkbox") {
                        input.checked = false;
                    } else {
                        input.value = "";
                    }
                });

                // CLEAR TEXTAREA
                clone.querySelectorAll("textarea").forEach(t => t.value = "");

                // RESET SELECT
                clone.querySelectorAll("select").forEach(select => {
                    $(select).val(null).trigger('change'); // Select2 reset
                });

                // REMOVE OLD SELECT2 HTML (IMPORTANT)
                clone.querySelectorAll('.select2').forEach(el => el.remove());

                // SHOW ORIGINAL SELECT AGAIN
                clone.querySelectorAll('.chapter-select').forEach(el => {
                    el.style.display = "block";
                });

                container.appendChild(clone);

                // RE-INIT SELECT2 ONLY FOR NEW CLONE
                initSelect2();

                toggleRemoveButtons();
            });

            // ======================
            // REMOVE BOX
            // ======================
            container.addEventListener("click", function(e) {
                if (e.target.classList.contains("remove-box")) {

                    let allBoxes = document.querySelectorAll(".lead-box");

                    if (allBoxes.length > 1) {
                        e.target.closest(".lead-box").remove();
                    }

                    toggleRemoveButtons();
                }
            });

            // ======================
            // HIDE FIRST REMOVE BTN
            // ======================
            function toggleRemoveButtons() {
                let boxes = document.querySelectorAll(".lead-box");

                boxes.forEach((box, index) => {
                    let btn = box.querySelector(".remove-box");

                    if (index === 0) {
                        btn.style.display = "none";
                    } else {
                        btn.style.display = "block";
                    }
                });
            }

            // ======================
            // SELECT2 INIT FUNCTION
            // ======================
            function initSelect2() {
                $('.chapter-select').select2({
                    placeholder: "Select Chapters",
                    width: '100%'
                });
            }

            // INITIAL CALL
            toggleRemoveButtons();

        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function () {

            let referTimer = null;

            $('#refer_search').on('keyup', function () {
                if ($(this).prop('readonly')) {
                    return;
                }
                let search = $(this).val();

                $('#refer_id').val('');

                clearTimeout(referTimer);

                if (search.length < 2) {
                    $('#refer_result').hide().html('');
                    return;
                }

                referTimer = setTimeout(function () {
                    $.ajax({
                        url: "{{ route('search.refer.users') }}",
                        type: "GET",
                        data: {
                            search: search
                        },
                        success: function (users) {
                            let html = '';

                            if (users.length > 0) {
                                users.forEach(function (user) {
                                    html += `
                                        <div class="refer-item px-3 py-2 border-bottom"
                                            style="cursor:pointer;"
                                            data-id="${user.id}"
                                            data-name="${user.name ?? ''}"
                                            data-email="${user.email ?? ''}"
                                            data-mobile="${user.mobile_no ?? ''}">
                                            <strong>${user.name ?? 'No Name'}</strong><br>
                                            <small>${user.email ?? ''} | ${user.mobile_no ?? ''}</small>
                                        </div>
                                    `;
                                });
                            } else {
                                html = `<div class="px-3 py-2 text-muted">No user found</div>`;
                            }

                            $('#refer_result').html(html).show();
                        }
                    });
                }, 300);
            });

            $(document).on('click', '.refer-item', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let mobile = $(this).data('mobile');

                $('#refer_id').val(id);
                $('#refer_search').val(name + ' - ' + mobile + ' - ' + email);
                $('#refer_result').hide();
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('#refer_search, #refer_result').length) {
                    $('#refer_result').hide();
                }
            });

        });
    </script>
<script>
$(document).ready(function () {

    let mobileTimer = null;

    $('#mobile').on('keyup', function () {

        let mobile = $(this).val().replace(/\D/g, '');
        $(this).val(mobile);

        clearTimeout(mobileTimer);

        if (mobile.length < 5) {

            $('input[name="user_name"]').val('');
            $('input[name="countrycode"]').val('');
            $('input[name="email"]').val('');
            $('input[name="id"]').val('');

            // REFER RESET
            $('#refer_id').val('');
            $('#refer_search').val('');

            $('input[name="email"]').prop('readonly', false);

            return;
        }

        mobileTimer = setTimeout(function () {

            $.ajax({
                type: 'GET',
                url: '{{ url("userData") }}',
                data: {
                    mobile: mobile
                },

                success: function (data) {

                    if (data.user) {

                        // USER AUTO FILL
                        $('input[name="user_name"]').val(data.user.name);
                        $('input[name="countrycode"]').val(data.user.countrycode);
                        $('input[name="email"]').val(data.user.email);
                        $('input[name="id"]').val(data.user.id);

                        $('input[name="email"]').prop('readonly', true);

                        // REFER AUTO FILL
                        if (data.referUser) {

                            $('#refer_id').val(data.referUser.id);

                            $('#refer_search').val(
                                data.referUser.name + ' - ' +
                                data.referUser.mobile_no + ' - ' +
                                data.referUser.email
                            );
                            if (loggedInRoleId == 1) {
                                $('#refer_search').prop('readonly', false);
                            } else {
                                $('#refer_search').prop('readonly', true);
                            }

                        } else {

                            $('#refer_id').val('');
                            $('#refer_search').val('');
                            $('#refer_search').prop('readonly', false);
                        }

                    } else {

                        // RESET USER
                        $('input[name="user_name"]').val('');
                        $('input[name="countrycode"]').val('');
                        $('input[name="email"]').val('');
                        $('input[name="id"]').val('');

                        // RESET REFER
                        $('#refer_id').val('');
                        $('#refer_search').val('');

                        $('input[name="email"]').prop('readonly', false);
                    }
                },

                error: function (error) {
                    console.log('UserData Error:', error);
                }
            });

        }, 300);

    });

});
</script>