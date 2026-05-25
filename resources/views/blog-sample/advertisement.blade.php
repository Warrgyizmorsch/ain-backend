@extends('layouts.app')
@section('content')
<style>
  /* Set the height of the Quill editor for desktop view */
 
    #kt_docs_quill_autosave {
      height: 400px; /* Adjust the height as needed */
    
  }
  .symbol>img {
    width: auto;
    height: 50px;
}
</style>

<div class="docs-content d-flex flex-column flex-column-fluid" id="kt_docs_content">
    <div class="container" id="kt_docs_content_container">
        <div class="card card-docs mb-2">
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="pt-10">
                    <form id="blogForm" action="{{ route('submit_advertisement') }}" method="post" enctype="multipart/form-data" onsubmit="return validateImage()">
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
                        
                        
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                    <!-- End of Blog submission form -->
                    <div class="mb-3">
                        <div class="card-body py-3">
							<div class="table-responsive">
								<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
									<thead>
										<tr class="fw-bolder text-muted">
											
											<th class="min-w-250px">Thumbnail</th>
											<th class="min-w-140px">Created By</th>
											<th class="min-w-140px">Create at</th>
											<th class="min-w-100px text-end">Actions</th>
										</tr>
									</thead>
									<tbody>
                                        @foreach($advertisements as $advertisement)

										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol  me-5">
														    <img src="{{$advertisement->images}}" alt="">
                                                      
                                                    </div>
													
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bolder text-hover-primary d-block fs-6">{{$advertisement->created_by}}</a>
											</td>
											
											<td class="text-end">
												<div class="d-flex flex-column w-100 me-2">
													<div class="d-flex flex-stack mb-2">
														<span class="text-muted me-2 fs-7 fw-bold">{{$advertisement->created_at}}</span>
													</div>
													
												</div>
											</td>
											<td>
												<div class="d-flex justify-content-end flex-shrink-0">
													


													<a data-bs-toggle="modal" data-bs-target="#kt_modal_create_delete{{$advertisement->id}}" href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
														<span class="svg-icon svg-icon-3">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"></path>
																<path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"></path>
																<path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"></path>
															</svg>
														</span>
													</a>

                                                    <div class="modal fade" id="kt_modal_create_delete{{$advertisement->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to delete this entry?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <form action="{{ route('advertisement.destroy', $advertisement->id) }}" method="POST">
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
            var minWidth = 1000;
            var maxWidth = 1540;

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