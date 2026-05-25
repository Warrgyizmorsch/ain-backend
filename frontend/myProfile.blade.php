@extends('frontend-layouts.app')

@section('content')
    <style>
        .inner-column {
            padding-top: 0px !important;
        }

        .banner-section-three .content-column .inner-column h2 {
            padding-top: 100px;
        }

        .text-black {
            color: black;
        }

        .contact-list li a {
            font-size: 14px;
            /* Adjust the font size as needed */
        }

        .card {
            background-color: #fff;
            /* Background color */
            border-radius: 15px;
            /* Rounded corners */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            /* Increased shadow for 3D effect */
            padding: 20px;
            /* Padding for content */
        }

        .card-content {
            /* Add styles for card content if needed */
        }.gradient-custom {
/* fallback for old browsers */
background: #f6d365;

/* Chrome 10-25, Safari 5.1-6 */
background: -webkit-linear-gradient(to right bottom, rgba(246, 211, 101, 1), rgba(253, 160, 133, 1));

/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
background: linear-gradient(to right bottom, rgba(246, 211, 101, 1), rgba(253, 160, 133, 1))
}
    </style>
    <section class="banner-section-three">
        <div class="pattern-layer-two" style="background-image: url(images/bubble.png); background-size: contain; background-position: center; background-repeat: no-repeat;"></div>
        <div class="auto-container" style="margin-top: 100px;">
            <!-- Page Breadcrumb -->
            <div style="text-align: center; height: 100px;">
                <ul class="page-breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li>My Profile</li>
                </ul>
            </div>
            <div class="row clearfix">
                <!-- Content Column -->
                <div class="content-column col-lg-12 col-md-12 col-sm-12">
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                    <section class="vh-100"  >
                        <div class="container  h-100">
                            <div class="row d-flex justify-content-center align-items-center h-100">
                                <div class="col col-lg-12 mb-12 mb-lg-12">
                                    <div class="card mb-3" style="border-radius: .5rem; background-color: inherit;">
                                        <form method="POST" action="{{ route('userProfile.update', ['id' => Auth::user()->id]) }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row g-0">
                                                <div class="col-md-5 gradient-custom text-center text-white p-2" style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                                    <!-- Container for photo and edit icons -->
                                                    <div class="position-relative">
                                                        <!-- display the edit icon to edit photo -->
                                                        <!-- Edit icon for the photo -->
                                                        <!-- <i id="photo" class="fas fa-file-image-o mb-2 edit-icon-photo d-none" style="color: black; position: relative; top: -40px; right: -90px;"></i> -->
                                                        <!-- Display the photo -->
                                                        @if(!empty(Auth::user()->photo))
                                                            <img id="profilePhoto" src="{{Auth::user()->photo }}" alt="Avatar" class="img-fluid my-5" style=" width: 130px; border-radius: 10px;  box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">
                                                        @else
                                                            <img id="profilePhoto" src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava1-bg.webp" alt="Avatar" class="img-fluid my-5" style=" width: 130px; border-radius: 10px;  box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">
                                                          
                                                        @endif
                                                            <div>
                                                                <input type="file" name="photo" id="photoInput" class="mb-2 edit-icon-photo d-none" onchange="previewPhoto()">
                                                            </div>
                                                       

                                                        <script>
                                                            function previewPhoto() {
                                                                const fileInput = document.getElementById('photoInput');
                                                                const profilePhoto = document.getElementById('profilePhoto');
                                                                
                                                                // Check if any file is selected
                                                                if (fileInput.files && fileInput.files[0]) {
                                                                    const reader = new FileReader();
                                                                    reader.onload = function (e) {
                                                                        profilePhoto.src = e.target.result; // Set the source of the image to the selected file
                                                                    }
                                                                    reader.readAsDataURL(fileInput.files[0]); // Read the file as a data URL
                                                                }
                                                            }
                                                        </script>

                                                    </div>
                                                    <input type="text" id="name" name="name" class="form-control mb-1 text-center border-0 editable-field" style="background-color: transparent; font-size: 1.5rem; box-shadow: none;" value="{{ Auth::user()->name }}" readonly>
                                                    <div class="edit-buttons d-none mt-2">
                                                        <button type="submit" class="btn btn-primary btn-sm submit-btn">Submit</button>
                                                        <button type="button" class="btn btn-secondary btn-sm cancel-btn">Cancel</button>
                                                    </div>
                                                    <i class="far fa-edit mb-5 edit-icon"></i>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="card-body p-4">
                                                        <h6>Profile Details</h6>
                                                        <hr class="mt-0 mb-4">
                                                        <div class="row pt-1">
                                                            <div class="col-6 mb-3">
                                                                <h6>Email</h6>
                                                                <input type="email" id="email" name="email" class="fw-bold mb-1 editable-field" placeholder="Enter Email" value="{{ Auth::user()->email ?? '' }}" readonly style="background-color: inherit;">
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                <h6>Phone</h6>
                                                                <input type="text" id="phone" name="phone" class="fw-bold mb-1 editable-field" placeholder="Enter Phone" value="{{ Auth::user()->mobile_no ?? '' }}" readonly style="background-color: inherit;">
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                <h6>Address</h6>
                                                                <input type="text" id="address" name="address" class="fw-bold mb-1 editable-field" placeholder="Enter Address" value="{{ Auth::user()->address ?? '' }}" readonly style="background-color: inherit;">
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            <a href="#!"><i class="fab fa-facebook-f fa-lg me-3 m-2"></i></a>
                                                            <a href="#!"><i class="fab fa-twitter fa-lg me-3 m-2"></i></a>
                                                            <a href="#!"><i class="fab fa-instagram fa-lg m-2"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
<!-- 
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            // Get the file input element
                                            var fileInput = document.getElementById('photo');

                                            // Add event listener to the file input element
                                            fileInput.addEventListener('change', function (event) {
                                                // Get the selected file
                                                var file = event.target.files[0];

                                                // Check if a file is selected
                                                if (file) {
                                                    // Create a FileReader object
                                                    var reader = new FileReader();

                                                    // Set up the FileReader onload function
                                                    reader.onload = function (e) {
                                                        // Update the value of the hidden input field with the data URL of the selected file
                                                        document.getElementById('photoData').value = e.target.result;
                                                    };

                                                    // Read the selected file as a data URL
                                                    reader.readAsDataURL(file);
                                                }
                                            });
                                            // Get the form and submit button
                                            var form = document.getElementById('kt_signin_change_user_details');
                                            var submitBtn = form.querySelector('.submit-btn');

                                            // Add event listener to the submit button
                                            submitBtn.addEventListener('click', function () {
                                                // Construct FormData object with form data
                                                var formData = new FormData(form);

                                                // Make AJAX POST request
                                                fetch(form.action, {
                                                    method: 'POST',
                                                    body: formData,
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                    }
                                                })
                                                .then(response => {
                                                    if (!response.ok) {
                                                        throw new Error('Network response was not ok');
                                                    }
                                                    return response.json();
                                                })
                                                .then(data => {
                                                    // Handle success response
                                                    console.log(data);
                                                })
                                                .catch(error => {
                                                    // Handle error
                                                    console.error('There was a problem with your fetch operation:', error);
                                                });
                                            });
                                        });
                                    </script> -->




                                    
                                    
                                    @if(Auth::User()->role_id ==1 ||Auth::User()->role_id ==2)
                                    <div class="card mb-5 mb-xl-10" style="background-color: inherit;">
                                        <div id="kt_account_signin_method" class="collapse show">
                                            <div class="card-header border-0 cursor-pointer" style="background-color: transparent;" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_password" aria-expanded="true" aria-controls="kt_account_profile_details">
                                                <div class="card-title m-0">
                                                    <h3 class="fw-bolder m-0">Password Changes</h3>
                                                </div>
                                            </div>
                                            <div class="card-body border-top p-9">
                                                <div class="d-flex flex-wrap align-items-center mb-10">
                                            
                                                    <div id="kt_signin_password">
                                                        <div class="fs-6 fw-bolder mb-1">Password</div>
                                                        <div class="fw-bold text-gray-600">************</div>
                                                    </div>
                                                    <div id="kt_signin_password_edit" class="flex-row-fluid d-none">
                                                        <form method="POST" action="{{ route('postChangeUserPassword') }}" id="kt_signin_change_password" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="row mb-1">
                                                                <div class="col-lg-4">
                                                                    <div class="fv-row mb-0 fv-plugins-icon-container">
                                                                        <label for="currentpassword" class="form-label fs-6 fw-bolder mb-3">Current Password</label>
                                                                        <input type="password"  id="current_password" name="current_password" class="form-control form-control-lg form-control-solid" required autofocus autocomplete="username">
                                                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="fv-row mb-0 fv-plugins-icon-container">
                                                                        <label for="newpassword" class="form-label fs-6 fw-bolder mb-3">New Password</label>
                                                                        <input type="password"id="new_password" name="new_password" class="form-control form-control-lg form-control-solid" >
                                                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="fv-row mb-0 fv-plugins-icon-container">
                                                                        <label for="confirmpassword" class="form-label fs-6 fw-bolder mb-3">Confirm New Password</label>
                                                                        <input id="new_password_confirmation" name="new_password_confirmation" type="password"  required autocomplete="new-password" class="form-control form-control-lg form-control-solid">
                                                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-text mb-5">Password must be at least 8 characters and contain symbols</div>
                                                            <div class="d-flex">
                                                                <button type="submit" class="btn btn-primary mr-2" id="kt_account_profile_details_submit">Save Changes</button>
                                                                <button id="kt_password_cancel" type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6 ml-2">Cancel</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div id="kt_signin_password_button" class="ml-auto">
                                                        <button class="btn btn-light btn-active-light-primary" id="resetPasswordBtn">Reset Password</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            // Get the form and Save Changes button
                                            var form = document.getElementById('kt_signin_change_password');
                                            var saveChangesBtn = document.getElementById('saveChangesBtn');

                                            // Add event listener to the Save Changes button
                                            saveChangesBtn.addEventListener('click', function () {
                                                // Get the current password, new password, and confirm new password
                                                var currentPassword = document.getElementById('current_password').value;
                                                var newPassword = document.getElementById('new_password').value;
                                                var newPasswordConfirmation = document.getElementById('new_password_confirmation').value;

                                                // Set the values in the form fields
                                                document.getElementById('current_password').value = currentPassword;
                                                document.getElementById('new_password').value = newPassword;
                                                document.getElementById('new_password_confirmation').value = newPasswordConfirmation;

                                                // Submit the form
                                                form.submit();
                                            });
                                        });
                                    </script>


                                                                
                                            
                                </div>
                            </div>

                        </div>
                    </section>
                </div>
                
                
            </div>


        </div>

    </section>
   
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the edit icon
            var editIcon = document.querySelector('.edit-icon');
            
            // Add event listener to the edit icon
            editIcon.addEventListener('click', function() {
                // Get all the editable fields
                var editableFields = document.querySelectorAll('.editable-field');
                var editButtons = document.querySelector('.edit-buttons');
                var editIconPhoto = document.querySelector('.edit-icon-photo');
                
                // Toggle readonly attribute for each editable field
                editableFields.forEach(function(field) {
                    field.readOnly = !field.readOnly;
                    // Show or hide edit buttons
                    editButtons.classList.toggle('d-block', !field.readOnly);
                    // Show or hide editIconPhoto buttons
                    editIconPhoto.classList.toggle('d-none', field.readOnly);
                    
                    // Add highlight effect when editable
                    if (!field.readOnly) {
                        field.style.backgroundColor = '#f3f4f6'; // Change background color
                        field.style.border = '1px solid #d1d5db'; // Add border
                        editIcon.style.display = 'none'; // Hide edit icon
                    } else {
                        field.style.backgroundColor = 'transparent'; // Reset background color
                        field.style.border = 'none'; // Remove border
                        editIcon.style.display = 'block'; // Show edit icon
                    }
                });
                editIconPhoto.style.display = '';
            });
            
            // Get submit and cancel buttons
            var submitBtn = document.querySelector('.submit-btn');
            var cancelBtn = document.querySelector('.cancel-btn');
            
            // Add event listener to the submit button
            submitBtn.addEventListener('click', function() {
                // Submit the form or perform any necessary action
                console.log('Submit button clicked');
            });
            
            // Add event listener to the cancel button
            cancelBtn.addEventListener('click', function() {
                // Get all the editable fields
                var editableFields = document.querySelectorAll('.editable-field');
                var editButtons = document.querySelector('.edit-buttons');
                
                // Toggle readonly attribute for each editable field
                editableFields.forEach(function(field) {
                    field.readOnly = true;
                    
                    // Show or hide edit buttons
                    editButtons.classList.toggle('d-block', !field.readOnly);
                    // Show or hide editIconPhoto buttons
                    editIconPhoto.classList.toggle('d-none', !field.readOnly);
                    // Remove highlight effect
                    field.style.backgroundColor = 'transparent';
                    field.style.border = 'none';
                });
                
                // Show edit icon
                editIcon.style.display = 'block';
                editIconPhoto.style.display = 'none';
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the reset password button
            var resetPasswordBtn = document.getElementById('resetPasswordBtn');
            var cancelButton = document.getElementById('kt_password_cancel');
            var firstDiv = document.getElementById('kt_signin_password');
            var LastDiv = document.getElementById('kt_signin_password_button');
            var middleDiv = document.getElementById('kt_signin_password_edit');
            
            // Add event listener to the reset password button
            resetPasswordBtn.addEventListener('click', function() {
                // Get the div containing the new password option
                var newPasswordDiv = document.getElementById('kt_signin_password_edit');
                
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

@endsection
