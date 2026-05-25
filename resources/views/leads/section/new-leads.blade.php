<div class="modal fade" id="kt_modal_create_appaa_newLeads" tabindex="-1" aria-hidden="true">
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
                            <input type="text"  id="nameInput" class="form-control form-control-solid" placeholder="" value="" name="user_name">
                        </div>
                        <div class="col-md-6 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Email</label>
                            <input type="text"   class="form-control form-control-solid" placeholder="" value="" name="email" id="emailInput" >
                            <input type="hidden" class="form-control form-control-solid" placeholder="" value="" name="id" id="id" >
                        </div>
                       

                    </div>
                    <div class="row g-9 mb-8 text-start" id="primaryMobileFields">
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-bold mb-2">Country Code</label>
                            <input type="text" required class="form-control form-control-solid" placeholder="" value="" name="countrycode">
                        </div>
                        <div class="col-md-6 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Mobile Number</label>
                            <input type="text"  required id="mobile" class="form-control form-control-solid" placeholder="" value="" name="mobile">
                        </div>
                    </div>
                    <div class="row g-9 mb-8 text-start" id="secondaryMobileFields" style="display: none;" >
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-bold mb-2">Secondary Country Code</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="" name="countrycode2">
                        </div>
                        <div class="col-md-6 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Secondary Mobile Number</label>
                            <input type="text"  id="mobile2" class="form-control form-control-solid" placeholder="" value="" name="mobile_no2">
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
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="" name="module_code">
                        </div>
                        <div class="col-md-8 mx-auto fv-row">
                            <label class=" fs-6 fw-bold mb-2">Project Title</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="" name="project_title">
                        </div>
                    </div>
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-6 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Word</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="" name="pages">
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Lead Status</label>
                            <select name="i_status" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
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
                            <select name="service_type" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                <option value="" <?php if (empty($lead['service_type'])) echo "selected"; ?> data-select2-id="select2-data-18-e9lh12"></option>
                                @foreach($service as $service)
                                    <option>{{ $service['service_name'] }}</option>
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
                                    >
                            </div>
                        </div>

                        <div class="col-md-3 fv-row d-flex">
                            <label class="fs-6 fw-bold mb-2">Resit</label>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input name="resit" style="height: 2.5rem;width: 2.5rem;" 
                                    class="form-check-input submenu-checkbox" 
                                    type="checkbox"  data-kt-check="true" 
                                    data-kt-check-target=".widget-9-check"
                                    >
                            </div>
                        </div>
                 
                        <div class="row g-9 mb-8 text-start">
                            
                            <div class="col-md-6 fv-row text-start">
                                <label class="fs-6 fw-bold mb-2">Delivery Date</label>
                                <input type="date"  class="form-control form-control-solid" id="inputdate" placeholder="" value="" name="delivery_date" onchange="showSelectedDate(this)">
                            </div>
                            <script>
                                $(function(){
                                    var dtToday = new Date();

                                    var month = dtToday.getMonth() + 1;
                                    var day = dtToday.getDate();
                                    var year = dtToday.getFullYear();
                                    if(month < 10)
                                        month = '0' + month.toString();
                                    if(day < 10)
                                        day = '0' + day.toString();
                                    
                                    var maxDate = year + '-' + month + '-' + day;
                                    // alert(maxDate);
                                    $('#inputdate').attr('min', maxDate);
                                });
                            </script>
                            <div class="col-md-6 fv-row text-start">
                                <label class="fs-6 fw-bold mb-2">Delivery Time</label>
                                <input type="time"  class="form-control form-control-solid" placeholder="" value="" name="delivery_time" onchange="showSelectedDate(this)">
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class=" fs-6 fw-bold mb-2">Type Of Paper</label>
                                <select id="typeOfPaper" name="paper" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                    <option value="" data-select2-id="select2-data-18-e9lh12">Not Selected</option>
                                    @foreach($papers as $paper)
                                        <option value="{{$paper->paper_type}}">{{$paper->paper_type}}</option>
                                    @endforeach                              
                                </select>                         
                            </div>
                        
                            <div class="col-md-6 fv-row">
                                <label class=" fs-6 fw-bold mb-2">Chapter</label>
                                <select id="chapter" name="chapter"aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                    <option value=" "> </option>
                                    <option value="Chapter 1:  Introduction" >Chapter 1:  Introduction</option>
                                    <option value="Chapter 2: Litreature Review" >Chapter 2: Litreature Review</option>
                                    <option value="Chapter 3: Methedology" >Chapter 3: Methedology</option>
                                    <option value="Chapter 4: Data Analysis" >Chapter 4: Data Analysis</option>
                                    <option value="Chapter 5: Conclusion & Recommendation" >Chapter 5: Conclusion & Recommendation</option>
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
</script>

