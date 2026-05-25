				<form id="" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST" enctype="multipart/form-data" action="{{ route('profile.update', ['id' =>  Auth::user()->id ]) }}">
						@csrf
						
						<div class="card-body border-top p-9">
							<!-- ... (other form fields) -->
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">Avatar</label>
								<div class="col-lg-8">
									<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(assets/media/avatars/blank.png)">
									@if(Auth::user()->photo)
										<div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ asset(Auth::user()->photo) }})"></div>
									@else
										<div class="image-input-wrapper w-125px h-125px" style="background-image:  url(assets/media/avatars/blank.png)"></div>
									@endif
										<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change avatar">
											<i class="bi bi-pencil-fill fs-7"></i>
											<input type="file" name="photo" accept=".png, .jpg, .jpeg">
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
							<!-- ... (other form fields) -->

							@if(Auth::User()->role_id == 1 || Auth::User()->role_id ==2 )
							<div class="row mb-12">
								<label class="col-lg-4 col-form-label required fw-bold fs-6">Name</label>
								<div class="col-lg-8">
									<div class="row">
										<div class="col-lg-12 fv-row fv-plugins-icon-container">
											<input type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" value="{{ Auth::user()->name }}">
										<div class="fv-plugins-message-container invalid-feedback"></div></div>
										
									</div>
								</div>
							</div>
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input type="email" name="email" class="form-control form-control-lg form-control-solid" placeholder="Youremail@gmail.com"  value="{{ Auth::user()->email }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">
									<span class="required">Contact Phone</span>
									<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i>
								</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input type="tel" name="phone" class="form-control form-control-lg form-control-solid" placeholder="Phone number" value="{{ Auth::user()->mobile_no }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>

                            <div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">
									<span class="required">Address</span>
									<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i>
								</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input type="text" name="address" class="form-control form-control-lg form-control-solid" placeholder="Enter YOur Address" value="{{ Auth::user()->address }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>
							@else
							<div class="row mb-12">
								<label class="col-lg-4 col-form-label required fw-bold fs-6">Name</label>
								<div class="col-lg-8">
									<div class="row">
										<div class="col-lg-12 fv-row fv-plugins-icon-container">
											<input readonly type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" value="{{ Auth::user()->name }}">
										<div class="fv-plugins-message-container invalid-feedback"></div></div>
										
									</div>
								</div>
							</div>
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input type="email" readonly name="email" class="form-control form-control-lg form-control-solid" placeholder="Youremail@gmail.com"  value="{{ Auth::user()->email }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">
									<span class="required">Contact Phone</span>
									<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i>
								</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input type="tel" readonly name="phone" class="form-control form-control-lg form-control-solid" placeholder="Phone number" value="{{ Auth::user()->mobile_no }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>

                            <div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">
									<span class="required">Address</span>
									<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i>
								</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input type="text" readonly name="address" class="form-control form-control-lg form-control-solid" placeholder="Enter YOur Address" value="{{ Auth::user()->address }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>
							@endif
						</div>
						<div class="card-footer d-flex justify-content-end py-6 px-9">
							<!-- <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button> -->
							<button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
						</div>
					<input type="hidden">
					<div>

					</div>
				</form>