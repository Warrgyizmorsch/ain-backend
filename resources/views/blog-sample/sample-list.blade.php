@extends('layouts.app')
@section('content')
<style>
  /* Set the height of the Quill editor for desktop view */
 
    #kt_docs_quill_autosave {
      height: 400px; /* Adjust the height as needed */
    
  }
  
    .modal-dialog {
        max-width: 70%;
        /* margin: 1.75rem auto; */
    }
</style>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div id="kt_content_container" class="container-xxl">
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

								<div class="col-lg-3 fv-row fv-plugins-icon-container">
									<select name="user_id" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By Name email" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-7969" tabindex="-1" aria-hidden="true">
										<option value="" data-select2-id="select2-data-18-e9lh"></option>
										
										
									</select>
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
								<!-- <div class="col-lg-3 fv-row fv-plugins-icon-container">
									<select name="refer" aria-label="Select a Language" data-control="select2" data-placeholder="refer code" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-13-mh4q" tabindex="-1" aria-hidden="true">
										<option value="" data-select2-id="select2-data-15-jtqd"></option>
									
									</select>
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div> -->
								</div>

								<div class="col-lg-3 fv-row fv-plugins-icon-container">
									<button type='submit' class="btn btn-sm btn-primary" >Search</button>
									<a href="/user" class="btn btn-sm btn-danger" >reset</a>
								</div>

							
							</div>
						</form>
				</div>
			</div>
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Sample List
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Let's Learn Together</small>
                    </div>
                    <div class="d-flex align-items-center py-1">
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app" id="kt_toolbar_primary_button">Create</a>
                    </div>
                </div>
	        </div>
            <div class="row gy-5 g-xl-8">
				<div class="col-xl-12">
					<div class="card card-xl-stretch mb-5 mb-xl-8">
						<div class="card-header border-0 pt-5">
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bolder fs-3 mb-1">All User</span>
								<span class="text-muted mt-1 fw-bold fs-7"></span>
							</h3>
						</div>
						<div class="card-body py-3">
							<div class="table-responsive">
								<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
									<thead>
										<tr class="fw-bolder text-muted">
											
											<th class="min-w-150px">Thumbnail</th>
											<th class="min-w-140px">Title</th>
											<th class="min-w-140px">Create at</th>
											<th class="min-w-100px text-end">Actions</th>
										</tr>
									</thead>
									<tbody>
                                        @foreach($data['blog'] as $blog)
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-45px me-5">
														    <img src="{{ asset($blog->images) }}" alt="">
                                                      
                                                    </div>
													
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bolder text-hover-primary d-block fs-6">{{$blog->tittle}}</a>
											</td>
											
											<td class="text-end">
												<div class="d-flex flex-column w-100 me-2">
													<div class="d-flex flex-stack mb-2">
														<span class="text-muted me-2 fs-7 fw-bold">{{$blog->created_at->format('d D Y (H:i:s)')}}</span>
													</div>
													
												</div>
											</td>
											<td>
												<div class="d-flex justify-content-end flex-shrink-0">
													<a  data-bs-toggle="modal" data-bs-target="#kt_modal_create_edit{{$blog->id}}" href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" >
														<span class="svg-icon svg-icon-3">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path>
																<path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path>
															</svg>
														</span>
													</a>
                                                    <div class="modal fade" id="kt_modal_create_edit{{$blog->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Confirm Edit</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to edit this sample entry?
                                                                </div>
                                                                <div class="docs-content d-flex flex-column flex-column-fluid" id="kt_docs_content">
                                                                    <div class="container" id="kt_docs_content_container">
                                                                        <div class="card card-docs mb-2">
                                                                            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                                                                                <div class="pt-10">
                                                                                    <form id="blogForm" action="{{ route('submit_sample') }}" method="post" enctype="multipart/form-data" onsubmit="return validateImage()">
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
                                                                                            <input type="text" class="form-control" id="blogTitle" name="blogTitle" value="{{ $blog->tittle ?? '' }}" required>
                                                                                        </div>
                                                                                        <div class="mb-3">
                                                                                            <label for="blogContent" class="form-label">Sample Content</label>
                                                                                            <!-- Quill editor container -->
                                                                                            <div id="kt_docs_quill_editor" style="height: 400px;"></div>
                                                                                            <!-- Hidden input field to store Quill editor content -->
                                                                                            <input type="hidden" id="blogContent" name="blogContent" required>
                                                                                        </div>
                                                                                        <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                                                                                    </form>

                                                                                    <!-- End of Blog submission form -->
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <form action="{{ route('sample.edit', $blog->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('put')
                                                                        <button type="submit" class="btn btn-danger">Submit</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


													<a data-bs-toggle="modal" data-bs-target="#kt_modal_create_delete{{$blog->id}}" href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
														<span class="svg-icon svg-icon-3">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"></path>
																<path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"></path>
																<path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"></path>
															</svg>
														</span>
													</a>

                                                    <div class="modal fade" id="kt_modal_create_delete{{$blog->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to delete this sample entry?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <form action="{{ route('sample.destroy', $blog->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
												</div>

                                                
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
        </div>
    </div>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
		<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <!-- Include SweetAlert2 library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

		
<script>
    function showImageDimensionsAlert(input) {
        var file = input.files[0];

        // Check if a file is selected
        if (!file) {
            return true; // No file selected, allow form submission
        }

        // Check image dimensions
        var image = new Image();
        image.src = URL.createObjectURL(file);

        image.onload = function () {
            var minHeight = 300;
            var maxHeight = 450;
            var minWidth = 300;
            var maxWidth = 540;

            if (
                image.height < minHeight ||
                image.height > maxHeight ||
                image.width < minWidth ||
                image.width > maxWidth
            ) {
                // Show SweetAlert popup for invalid dimensions
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Image Dimensions',
                    text: 'Image dimensions must be between height and widht 300 to 450.',
                });

                // Clear the file input
                input.value = '';

                return false; // Prevent form submission
            }

            return true; // Valid dimensions, allow form submission
        };
    }
</script>
    @endsection