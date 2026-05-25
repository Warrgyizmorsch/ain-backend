 <!-- Edit Section Start   -->
 <div class="modal fade" id="kt_modal_create_app{{$user->id}}" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered mw-900px">
			<div class="modal-content">
				<div class="modal-header">
					<h2>User Details </h2>
					<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
						<span class="svg-icon svg-icon-1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
								<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
							</svg>
						</span>
					</div>
				</div>
			    <div class="card-body p-9">
                    <form id="{{$user->id}}" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST" enctype="multipart/form-data" action="{{ route('user.update', ['id' =>  $user->id ]) }}">
						@csrf
						<div class="card-body border-top p-9">
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">Avatar</label>
								<div class="col-lg-8">
									<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(assets/media/avatars/blank.png)">
									@if($user->photo)
										<div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ asset($user->photo) }})"></div>
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

							<div class="row mb-12">
								<label class="col-lg-4 col-form-label required fw-bold fs-6">Name</label>
								<div class="col-lg-8">
									<div class="row">
										<div class="col-lg-12 fv-row fv-plugins-icon-container">
											<input type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" value="{{ $user->name }}">
										<div class="fv-plugins-message-container invalid-feedback"></div></div>
										
									</div>
								</div>
							</div>
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input type="email" name="email" class="form-control form-control-lg form-control-solid" placeholder="Youremail@gmail.com"  value="{{ $user->email }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>

                            <div class="row mb-6">
								<label class="col-lg-4 col-form-label required fw-bold fs-6">Role</label>
								<div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="role" aria-label="Select a Language"   class="form-select form-select-solid form-select-lg "  >
                                    <option value="" data-select2-id="select2-data-15-jtqd"></option>
                                    @foreach($data['role'] as $roles)
										<option  value="{{ $roles['id'] }}" @if($roles['id'] == $user['role_id']) selected @endif>{{ $roles['role']}}</option>
									@endforeach
                                    
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
								</div>
							</div>
                            <div class="row mb-6">
								<label class="col-lg-4 col-form-label required fw-bold fs-6">Bank</label>
								<div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="bank" aria-label="Select a Language"   class="form-select form-select-solid form-select-lg "  >
                                    <option value="" data-select2-id="select2-data-15-jtqd"></option>
                                    @foreach($data['bank'] as $banks)
										<option  value="{{ $banks->id }}" @if($banks->id == $user->bank_id) selected @endif>{{ $banks->name }}</option>
									@endforeach
                                    
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
								</div>
							</div>

							<!-- New password fields -->
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">
									<span class="required">SIP ID</span>
									<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i>
								</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input type="tel" name="sip" class="form-control form-control-lg form-control-solid" placeholder="sip id" value="{{ $user->sip }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>
						


							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">
									<span class="required">Primary Mobile Number</span>
									<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i>
								</label>
								<div class="col-lg-2 fv-row fv-plugins-icon-container">
									<input type="tel" name="countrycode" class="form-control form-control-lg form-control-solid" placeholder="Country Code" value="{{ $user->countrycode }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
								<div class="col-lg-6 fv-row fv-plugins-icon-container">
									<input type="tel" name="phone" class="form-control form-control-lg form-control-solid" placeholder="Primary Mobile Number" value="{{ $user->mobile_no }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">
									<span class="required">Secondary Mobile Number</span>
									<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i>
								</label>
								<div class="col-lg-2 fv-row fv-plugins-icon-container">
									<input type="tel" name="countrycode2" class="form-control form-control-lg form-control-solid" placeholder="Country Code2" value="{{ $user->countrycode2 }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
								<div class="col-lg-6 fv-row fv-plugins-icon-container">
									<input type="tel" name="phone2" class="form-control form-control-lg form-control-solid" placeholder="Secondary Mobile Number" value="{{ $user->mobile_no2 }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>

                            <div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">
									<span class="required">Address</span>
									<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i>
								</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input type="text" name="address" class="form-control form-control-lg form-control-solid" placeholder="Enter YOur Address" value="{{ $user->address }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>
							
							@if($user->role_id == 9)
							 <div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">
									<span class="required">Calling Id</span>
									<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Calling Id" aria-label="Calling Id"></i>
								</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input type="text" name="call_id" class="form-control form-control-lg form-control-solid" placeholder="Enter Agent Calling Id" value="{{ $user->call_id }}">
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>
							@endif
							
						</div>
					<div>

					</div>
                    </div>
			    <div class="modal-footer">
			    	<button data-bs-dismiss="modal" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			    	<button  type="submit" class="btn btn-primary" >Submit</button>
			    </div>
			</form>
			</div>
		</div>
	</div>
</div>

<!-- Edit Section End -->

<!-- Delete Section Start -->
<div class="modal fade" id="kt_modal_create_delete{{$user->id}}" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered mw-900px">
			<div class="modal-content">

				<div class="modal-header">
					<h2> Delete User </h2>
					<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
						<span class="svg-icon svg-icon-1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
								<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
							</svg>
						</span>
					</div>
				</div>
			    <div class="card-body p-9">
                     <h3>Are You Sure To Delete User <span style="color:brown">{{ $user->name}}({{ $user->email}})</span> </h3>
                </div>
			    <div class="modal-footer">
					<form method="POST" action="{{ route('user.delete', ['id' => $user->id]) }}">
						@method('DELETE')
						@csrf
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Delete</button>
					</form>
			    </div>
			</div>
		</div>
	</div>
</div>
<!-- Delete Section End -->
