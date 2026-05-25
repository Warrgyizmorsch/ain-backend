@extends('layouts.app')

@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Whatsapp
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Message Send</small>
                    </h1>
                </div>
            </div>
        </div>

        <div class="row g-5 g-xl-8">
            <div class="col-xl-12">
                <div class="card card-xxl-stretch mb-xl-8">
                    <div class="card-body py-3">
                        <form id="complaintForm">
                            @csrf
                            <input type="hidden" name="media_id" id="media_id">
                            <input type="hidden" name="media_name" id="media_name">
                            <input type="hidden" name="file_name" id="file_name">

                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold fs-6">Template Name</label>
                                    <select class="form-select form-control-solid" name="template_name">
                                  
                                        @foreach ($templates as $template)
                                            <option value="{{$template['name']}}">{{$template['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold fs-6">Language Type</label>
                                    <select class="form-select form-control-solid" name="language_type">
                                        <option value="en_GB">en_GB</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold fs-6">Country Code</label>
                                    <input type="text" class="form-control form-control-solid" name="country_code" id="country_code" placeholder="Enter country code">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold fs-6">Mobile Number</label>
                                    <input type="text" class="form-control form-control-solid" name="mobile_number" id="mobile_number" placeholder="Enter mobile number">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label class="form-label fw-bold fs-6">Choose Image</label>
                                    <input type="file" class="form-control form-control-solid" name="image" id="image" accept="image/*">
                                </div>
                            </div>

                            <div class="progress mb-3" id="uploadProgressContainer" style="display: none;">
                                <div id="progress-bar" class="progress-bar bg-info" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>

                            <div class="row mb-3" id="fileInfo" style="display: none;">
                                <div class="col-lg-12">
                                    <label class="form-label fw-bold fs-6">Uploaded File Info</label>
                                    <input type="text" class="form-control form-control-solid" id="uploaded_file_name" placeholder="Uploaded file name" readonly>
                                    <input type="text" class="form-control form-control-solid mt-2" id="media_id_display" placeholder="Media ID" readonly>
                                    <input type="text" class="form-control form-control-solid mt-2" id="media_type_display" placeholder="Media Type" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-12 text-end">
                                    <button type="submit" class="btn btn-primary" id="submitComplaint">Submit Complaint</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="pageLoader" class="loader-wrapper d-none">
    <div class="loader"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
    $('#fileInfo').hide();

    function validateForm() {
        let countryCode = $('#country_code').val();
        let mobileNumber = $('#mobile_number').val();

        if (!countryCode || !mobileNumber) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Failed!',
                text: 'Both Country Code and Mobile Number are required!',
            });
            return false;
        }
        return true;
    }

    $('#image').on('change', function () {
        var formData = new FormData();
        var imageFile = $('#image')[0].files[0]; 
        formData.append('image', imageFile);
        formData.append('_token', '{{ csrf_token() }}');

        $('#pageLoader').removeClass('d-none');
        $('#uploadProgressContainer').show();

        $.ajax({
            url: '{{ route("image.upload") }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        $('#progress-bar').css("width", percentComplete + "%").attr("aria-valuenow", percentComplete).text(Math.round(percentComplete) + "%");
                    }
                }, false);
                return xhr;
            },
            success: function (response) {
                $('#pageLoader').addClass('d-none');
                $('#progress-bar').css("width", "100%").attr("aria-valuenow", 100).text("100%");
                $('#progress-bar').removeClass('bg-info').addClass('bg-success');

                Swal.fire({
                    icon: 'success',
                    title: 'Image Uploaded!',
                    text: 'File upload completed successfully!',
                    timer: 2000
                });

                if (response.data && response.data.media_id) {
                    $('#media_id').val(response.data.media_id); 
                    $('#media_name').val(response.data.mimetype);  
                    $('#file_name').val(imageFile.name);  

                    $('#uploaded_file_name').val(imageFile.name);
                    $('#media_id_display').val(response.data.media_id);
                    $('#media_type_display').val(response.data.mimetype);

                    $('#fileInfo').show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed!',
                        text: 'Invalid response from server.',
                    });
                    $('#fileInfo').hide(); 
                }
            },
            error: function (xhr, status, error) {
                $('#pageLoader').addClass('d-none');
                $('#fileInfo').hide(); 

                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed!',
                    text: 'There was an error uploading the file.',
                });
            }
        });
    });

    $('#submitComplaint').on('click', function (e) {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        var formData = $('#complaintForm').serialize();

        // Show loader
        $('#pageLoader').removeClass('d-none');

        $.ajax({
            url: '{{ route("complaint.submit") }}',  // Adjust the route accordingly
            type: 'POST',
            data: formData,
            success: function (response) {
                $('#pageLoader').addClass('d-none');

                Swal.fire({
                    icon: 'success',
                    title: 'Complaint Submitted!',
                    text: 'Your complaint has been successfully submitted!',
                    timer: 2000
                });
            },
            error: function (xhr, status, error) {
                $('#pageLoader').addClass('d-none');

                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed!',
                    text: 'There was an error submitting the complaint.',
                });
            }
        });
    });
});
</script>

<!-- Styles for loader -->
<style>
    .loader-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .progress {
        height: 25px;
    }
</style>

@endsection
