@extends('layouts.app')
@section('content')
<style>
  /* Set the height of the Quill editor for desktop view */
 
    #kt_docs_quill_autosave {
      height: 400px; /* Adjust the height as needed */
    
  }
</style>

<div class="docs-content d-flex flex-column flex-column-fluid" id="kt_docs_content">
    <div class="container" id="kt_docs_content_container">
        <div class="card card-docs mb-2">
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="pt-10">
                    <form id="blogForm" action="{{ route('submit_sample') }}" method="post"  enctype="multipart/form-data" onsubmit="return validateImage()">
                        @csrf
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">Thumbnail</label>
                            <div class="col-lg-8">
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(assets/media/avatars/blank.png)">
                                    <div class="image-input-wrapper w-125px h-125px" style="width: 200px !important; height:150px; background-image:  url(assets/media/avatars/blank.png)"></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="photo" accept=".png, .jpg, .jpeg" onchange="showImageDimensionsAlert(this)">
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>
                                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="blogTitle" class="form-label">Sample Title</label>
                            <input type="text" class="form-control" id="blogTitle" name="blogTitle" required>
                            <!-- Hidden input field to store type  -->
                            <input type="hidden" name="type" value="sample">
                        </div>
                        <div class="mb-3">
                            <label for="blogContent" class="form-label">Sample Content</label>
                            <!-- Quill editor container -->
                            <div id="kt_docs_quill_editor" style="height: 400px;"></div>
                            <!-- Hidden input field to store Quill editor content -->
                            <input type="hidden" id="blogContent" name="blogContent" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                    <!-- End of Blog submission form -->
                </div>
            </div>
        </div>
    </div>
</div>

     
    @endsection