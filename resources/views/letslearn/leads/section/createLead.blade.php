<!-- Create Lead Modal -->
<div class="modal fade" id="kt_modal_create_lead" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">            
            <div class="modal-header">
                <h5 class="modal-title" id="kt_modal_create_lead_label">Create New Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body p-9">
                <form id="createLeadForm" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST" action="{{ route('ll.leads.create') }}">
                    @csrf
                    <div class="row">
                        <!-- User Details -->
                        <!-- Name -->
                        <div class="form-group col-md-6 mb-2">
                            <label for="name">User Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
                        </div> 
                        <!-- Email -->
                        <div class="form-group col-md-6 mb-2">
                            <label for="email">User Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter a valid email address" required>
                        </div> 
                        <!-- Country Code -->
                        <div class="form-group col-md-6 mb-2">
                            <label for="country_code">Country Code</label>
                            <input type="number" class="form-control" id="country_code" name="country_code" placeholder="Enter country code" required>
                        </div> 
                        <!-- Contact Number -->
                        <div class="form-group col-md-6 mb-2">
                            <label for="contact">Contact Number</label>
                            <input type="tel" class="form-control" id="contact" name="contact" placeholder="Enter contact number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                        </div> 

                        <!-- Project Title -->
                        <div class="form-group col-md-12 mb-2">
                            <label for="project_title">Project Title</label>
                            <input type="text" class="form-control" id="project_title" name="project_title" placeholder="Enter project title" required>
                        </div>                        

                        <!-- Pages -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="pages">Word</label>
                            <input type="number" class="form-control" id="pages" name="pages" placeholder="Enter a valid word count" 
                                min="0" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                                onkeydown="return event.key === 'Backspace' || event.key === 'Tab' || /\d/.test(event.key);">
                        </div>

                        <!-- Price -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="price">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Enter a valid price"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');" 
                                onkeydown="return event.key === 'Backspace' || event.key === 'Tab' || event.key === '.' || /\d/.test(event.key);">
                        </div>

                        <!-- Deadline -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="deadline">Deadline</label>
                            <input type="date" class="form-control" id="deadline" name="deadline"  min="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Delivery Time -->
                        <!-- <div class="form-group col-md-6">
                            <label for="delivery_time">Delivery Time</label>
                            <input type="text" class="form-control" id="delivery_time" name="delivery_time" >
                        </div> -->                        

                        <!-- Work Type -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="workTypeSelect">Work Type</label>
                            <select required id="workTypeSelect" name="work_type" class="form-control">
                                <option >Select Work Type</option>
                                <option value="Standard">Standard</option>
                                <option value="First Class Work">First Class Work</option>
                            </select>
                        </div>
                        
                        <!-- Work Type -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="serviceTypeSelect">Service Type</label>
                            <select required id="serviceTypeSelect" name="service_type" class="form-control">
                                <option >Select Service Type</option>
                                <option value="Assignment">Assignment</option>
                                <option value="Dissertation">Dissertation</option>
                                <option value="Thesis">Thesis</option>
                                <option value="Research Project">Research Project</option>
                            </select>
                        </div>
                        <!-- Subjects -->
                        <div class="form-group col-md-4 mb-2">
                            <div class="form-group">
                                <label for="subjectSelect">Subject</label>
                                <select required id="subjectSelect" name="subject" class="form-control">
                                    <option >Select Subject</option>
                                    <option value="Matlab">Matlab</option>
                                    <option value="Data Science">Data Science</option>
                                    <option value="Engineering">Engineering</option>
                                    <option value="App Development">App Development</option>
                                    <option value="Web Development">Web Development</option>
                                    <option value="Exam">Exam</option>
                                    <option value="Public Health">Public Health</option>
                                    <option value="Presentation">Presentation (PPT)</option>
                                    <option value="Portfolio">Portfolio</option>
                                    <option value="Research Report">Research Report</option>
                                    <option value="Business Management">Business Management</option>
                                    <option value="Project Management">Project Management</option>
                                    <option value="Essay">Essay</option>
                                    <option value="HRM">HRM</option>
                                    <option value="Economic">Economic</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Assignment Details -->
                        <div class="form-group col-md-12 mb-2">
                            <label for="assignment_details">Assignment Details</label>
                            <textarea required class="form-control" id="assignment_details" name="assignment_details" rows="4" cols="50"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button data-bs-dismiss="modal" type="button" class="btn btn-secondary">Close</button>
                <button type="submit" form="createLeadForm" class="btn btn-primary">Create Lead</button>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to populate modal with user data if user exists
        function populateUserData(data) {
            $('#name').val(data.name || '').attr('readonly', true);
            $('#email').val(data.email || '');
            $('#country_code').val(data.country_code || '').attr('readonly', true);
            $('#contact').val(data.contact || '').attr('readonly', true);
        }

        // Function to clear user data fields if no user found
        function clearUserDataFields() {
            $('#name').val('').removeAttr('readonly');
            $('#country_code').val('').removeAttr('readonly');
            $('#contact').val('').removeAttr('readonly');
        }

        // Event listener when email input loses focus
        $('#email').on('blur', function () {
            let email = $(this).val();

            // Perform AJAX request to check if user exists by email
            if (email) {
                $.ajax({
                    url: '{{ route("ll.checkUser") }}', 
                    type: 'GET',
                    data: {
                        email: email
                    },
                    success: function(response) {
                        if (response.exists) {
                            populateUserData(response.user);
                        } else {
                            clearUserDataFields();
                        }
                    },
                    error: function() {
                        console.log('Error fetching user data');
                        clearUserDataFields(); // Clear fields on error as well
                    }
                });
            } else {
                clearUserDataFields(); // Clear fields if email input is empty
            }
        });

        // Reset the form when the modal is closed
        $('#kt_modal_create_lead').on('hide.bs.modal', function () {
            $('#createLeadForm')[0].reset(); 
            clearUserDataFields();
        });
    });
</script>

