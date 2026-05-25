<div class="card mb-5 mb-xl-10" style="background-color: inherit;">
    <div id="kt_account_signin_methodAdmin" class="collapse show">
    <div class="card-header border-0 cursor-pointer" style="background-color: transparent;" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_password" aria-expanded="true" aria-controls="kt_account_profile_details">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Change the Password of User</h3>
        </div>
    </div>
    <div class="card-body border-top p-9">
        <div class="d-flex flex-wrap align-items-center mb-10">

            <div id="kt_signin_passwordAdmin">
                <div class="fs-6 fw-bolder mb-1">Password</div>
                <div class="fw-bold text-gray-600">************</div>
            </div>
            <div id="kt_signin_password_editAdmin" class="flex-row-fluid d-none">
                <form method="POST" action="{{ route('postChangePasswordAdmin') }}" id="kt_signin_change_passwordAdmin" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                    
                    <div class="row mb-1">
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
                        <div class="col-lg-4">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="currentpassword" class="form-label fs-6 fw-bolder mb-3">Search</label>
                                <input type="text" list="searchDatalist" id="searchInput" name="user" class="form-control form-control-solid"  autocomplete="off">
                                <!-- Datalist for displaying search results -->
                                <datalist id="searchDatalist"></datalist>
                                <!-- Container to display search results -->
                                <div id="searchResultss"></div>
                                <!-- Hidden field to store the selected value -->
                                <input type="hidden" id="selectedValue" name="uid">
                            </div>
                            
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
                        <div class="col-lg-4">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="currentpassword" class="form-label fs-6 fw-bolder mb-3">Current Password of Admin</label>
                                <input type="password"  id="current_passwordAdmin" name="current_password_admin" class="form-control form-control-lg form-control-solid" required autofocus autocomplete="username">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="newpassword" class="form-label fs-6 fw-bolder mb-3">New Password for User</label>
                                <input type="password"id="new_passwordAdmin" name="new_password" class="form-control form-control-lg form-control-solid" >
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="confirmpassword" class="form-label fs-6 fw-bolder mb-3">Confirm New Password</label>
                                <input id="new_password_confirmationAdmin" name="new_password_confirmation" type="password"  required autocomplete="new-password" class="form-control form-control-lg form-control-solid">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-text mb-5">Password must be at least 8 characters and contain symbols</div>
                    <div id="kt_signin_password_message" class="fs-6 fw-bolder mb-1"></div> <!-- Container for displaying response messages -->
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary mr-2" id="kt_account_profile_details_submitAdmin">Save Changes</button>
                        <button id="kt_password_cancelAdmin" type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6 ml-2">Cancel</button>
                    </div>
                </form>
            </div>
            <div id="kt_signin_password_buttonAdmin" class="ml-auto">
                <button class="btn btn-light btn-active-light-primary" id="resetPasswordBtnAdmin">Reset Password</button>
            </div>
        </div>
    </div>
    </div>
    </div>

                                    
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the reset password button
        var resetPasswordBtn = document.getElementById('resetPasswordBtnAdmin');
        var cancelButton = document.getElementById('kt_password_cancelAdmin');
        var firstDiv = document.getElementById('kt_signin_passwordAdmin');
        var LastDiv = document.getElementById('kt_signin_password_buttonAdmin');
        var middleDiv = document.getElementById('kt_signin_password_editAdmin');

        // Add event listener to the reset password button
        resetPasswordBtn.addEventListener('click', function() {
            // Get the div containing the new password option
            var newPasswordDiv = document.getElementById('kt_signin_password_editAdmin');

            // Toggle the visibility of the div
            newPasswordDiv.classList.toggle('d-none');

            // Hide the first and last div
            firstDiv.classList.add('d-none');
            LastDiv.classList.add('d-none');
            // Show the middle div
            middleDiv.classList.remove('d-none');
        });

        // Add event listener to the cancel button
        cancelButton.addEventListener('click', function() {
            // Hide the first and last div
            middleDiv.classList.add('d-none');
            // Show the middle div
            firstDiv.classList.remove('d-none');
            LastDiv.classList.remove('d-none');
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Get the form and Save Changes button
    var form = document.getElementById('kt_signin_change_passwordAdmin');
    var saveChangesBtn = document.getElementById('kt_account_profile_details_submitAdmin');
    var passwordMessage = document.getElementById('kt_signin_password_message'); // Container for response messages

    // Add event listener to the Save Changes button
    saveChangesBtn.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Get the CSRF token
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Get the current password, new password, and confirm new password
        var currentPasswordAdmin = document.getElementById('current_passwordAdmin').value;
        var newPassword = document.getElementById('new_passwordAdmin').value;
        var newPasswordConfirmation = document.getElementById('new_password_confirmationAdmin').value;
        var userId = document.getElementById('selectedValue').value; // Get the selected user ID

        // Create a FormData object and append the form data
        var formData = new FormData();
        formData.append('current_password_admin', currentPasswordAdmin);
        formData.append('new_password', newPassword);
        formData.append('new_password_confirmation', newPasswordConfirmation);
        formData.append('user_id', userId); // Append the user ID

        // Send the AJAX request
        $.ajax({
            url: form.action,
            type: form.method,
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in request headers
            },
            data: formData,
            processData: false,
            contentType: false,
             // AJAX configuration
             success: function(response) {
                // Handle success response
                console.log(response);
                // Display response message in the container
                passwordMessage.innerHTML = '<div class="text-success">Success: ' + response.message + '</div>';
                // Clear input values
                document.getElementById('current_passwordAdmin').value = '';
                document.getElementById('new_passwordAdmin').value = '';
                document.getElementById('new_password_confirmationAdmin').value = '';
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
                // Display error message in the container
                passwordMessage.innerHTML = '<div class="text-danger">Error: ' + xhr.responseJSON.message + '</div>';
                // Clear input values
                document.getElementById('current_passwordAdmin').value = '';
                document.getElementById('new_passwordAdmin').value = '';
                document.getElementById('new_password_confirmationAdmin').value = '';
            }
        });
    });
});


</script>
