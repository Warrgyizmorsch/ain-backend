<!-- Edit Section Start -->
<div class="modal fade" id="kt_modal_create_app{{$user->id}}" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-900px" style="max-height: 90vh; margin-top: auto; margin-bottom: auto;">
		<div class="modal-content" style="max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; border-radius: 0.65rem;">
			<form id="edit_user_form_{{$user->id}}" autocomplete="off" class="form d-flex flex-column m-0 flex-grow-1" style="display: flex; flex-direction: column; max-height: 90vh; overflow: hidden; margin: 0; width: 100%;" method="POST" enctype="multipart/form-data" action="{{ route('user.update', ['id' =>  $user->id ]) }}">
				@csrf
				<!-- Anti-autofill dummy traps to prevent Chrome/Edge password managers from overwriting form fields -->
				<input style="display:none;" type="text" name="fakeusernameremembered" autocomplete="off" />
				<input style="display:none;" type="password" name="fakepasswordremembered" autocomplete="new-password" />
				<div class="modal-header py-4 px-9" style="flex-shrink: 0; border-bottom: 1px solid #eff2f5;">
					<h2 class="fw-bolder m-0">User Details <span class="badge badge-light-primary fs-8 ms-2">#{{ $user->id }}</span></h2>
					<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
						<span class="svg-icon svg-icon-1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
								<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
							</svg>
						</span>
					</div>
				</div>
				<div class="modal-body py-6 px-9" style="overflow-y: auto !important; max-height: calc(90vh - 130px) !important; -webkit-overflow-scrolling: touch;">
					<div class="row mb-6">
						<label class="col-lg-4 col-form-label fw-bold fs-6">Avatar</label>
						<div class="col-lg-8">
							<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/avatars/blank.png') }})">
							@if(!empty($user->photo))
								<div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ asset($user->photo) }})"></div>
							@else
								<div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ asset('assets/media/avatars/blank.png') }})"></div>
							@endif
								<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
									<i class="bi bi-pencil-fill fs-7"></i>
									<input type="file" name="photo" accept=".png, .jpg, .jpeg">
									<input type="hidden" name="avatar_remove" value="0">
								</label>
								<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
									<i class="bi bi-x fs-2"></i>
								</span>
								<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
									<i class="bi bi-x fs-2"></i>
								</span>
							</div>
							<div class="form-text">Allowed file types: png, jpg, jpeg.</div>
						</div>
					</div>

					<div class="row mb-6">
						<label class="col-lg-4 col-form-label required fw-bold fs-6">Name</label>
						<div class="col-lg-8">
							<input type="text" name="name" required autocomplete="off" class="form-control form-control-lg form-control-solid" placeholder="Name" value="{{ $user->name }}">
						</div>
					</div>

					<div class="row mb-6">
						<label class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
						<div class="col-lg-8">
							<input type="email" name="email" required autocomplete="off" class="form-control form-control-lg form-control-solid" placeholder="Youremail@gmail.com" value="{{ $user->email }}">
						</div>
					</div>

					<div class="row mb-6">
						<label class="col-lg-4 col-form-label required fw-bold fs-6">Role</label>
						<div class="col-lg-8">
							<select name="role" class="form-select form-select-solid form-select-lg">
							@if(isset($data['role']))
								@foreach($data['role'] as $roles)
									<option value="{{ $roles['id'] }}" @if($roles['id'] == $user['role_id']) selected @endif>{{ $roles['role'] }}</option>
								@endforeach
							@endif
							</select>
						</div>
					</div>

					<div class="row mb-6">
						<label class="col-lg-4 col-form-label fw-bold fs-6">Bank</label>
						<div class="col-lg-8">
							<select name="bank" class="form-select form-select-solid form-select-lg">
								<option value="">Select Bank (Optional)</option>
								@if(isset($data['bank']))
									@foreach($data['bank'] as $banks)
										<option value="{{ $banks->id }}" @if($banks->id == $user->bank_id) selected @endif>{{ $banks->name }}</option>
									@endforeach
								@endif
							</select>
						</div>
					</div>

					<div class="row mb-6">
						<label class="col-lg-4 col-form-label fw-bold fs-6">
							<span class="{{ $user['role_id'] == 4 ? 'required' : '' }}">Team</span>
						</label>
						<div class="col-lg-8">
							<select name="team_id" class="form-select form-select-solid form-select-lg" {{ $user['role_id'] == 4 ? 'required' : '' }}>
								<option value="">Select Team</option>
								@php
									$allTeams = isset($data['teams']) ? $data['teams'] : \App\Models\Team::where('is_delete', false)->orderBy('priority', 'asc')->get();
								@endphp
								@foreach($allTeams as $team)
									<option value="{{ $team->id }}" @if($team->id == ($user->team_id ?? null)) selected @endif>{{ $team->team_name }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<!-- SIP fields (Staff / Agent roles only, excluded for Customer role 2) -->
					@if((int)$user->role_id !== 2)
					<div class="row mb-6">
						<label class="col-lg-4 col-form-label fw-bold fs-6">
							<span>SIP ID / Extension</span>
							<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Next2Call agent extension / SIP ID"></i>
						</label>
						<div class="col-lg-8">
							<input type="text" inputmode="numeric" pattern="[0-9]*" name="sip" autocomplete="off" class="form-control form-control-lg form-control-solid" placeholder="e.g. 10101" value="{{ $user->sip }}">
							<div class="text-muted fs-8 mt-1">Agent SIP extension ID for Next2Call softphone calls.</div>
						</div>
					</div>

					<div class="row mb-6">
						<label class="col-lg-4 col-form-label fw-bold fs-6">
							<span>SIP Password</span>
							<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Next2Call agent SIP password (optional)"></i>
						</label>
						<div class="col-lg-8">
							<input type="password" name="sip_password" autocomplete="new-password" class="form-control form-control-lg form-control-solid" placeholder="Leave blank to preserve existing password" value="">
							<div class="text-muted fs-8 mt-1">Leave blank to keep existing password or use Next2Call admin default.</div>
						</div>
					</div>
					@endif

					<div class="row mb-6">
						<label class="col-lg-4 col-form-label fw-bold fs-6">
							<span>Primary Mobile Number</span>
						</label>
						<div class="col-lg-3">
							<input type="tel" name="countrycode" class="form-control form-control-lg form-control-solid" placeholder="Country Code" value="{{ $user->countrycode }}">
						</div>
						<div class="col-lg-5">
							<input type="tel" name="phone" class="form-control form-control-lg form-control-solid" placeholder="Primary Mobile Number" value="{{ $user->mobile_no }}">
						</div>
					</div>

					<div class="row mb-6">
						<label class="col-lg-4 col-form-label fw-bold fs-6">
							<span>Secondary Mobile Number</span>
						</label>
						<div class="col-lg-3">
							<input type="tel" name="countrycode2" class="form-control form-control-lg form-control-solid" placeholder="Country Code 2" value="{{ $user->countrycode2 }}">
						</div>
						<div class="col-lg-5">
							<input type="tel" name="phone2" class="form-control form-control-lg form-control-solid" placeholder="Secondary Mobile Number" value="{{ $user->mobile_no2 }}">
						</div>
					</div>

					<div class="row mb-6">
						<label class="col-lg-4 col-form-label fw-bold fs-6">
							<span>Address</span>
						</label>
						<div class="col-lg-8">
							<input type="text" name="address" class="form-control form-control-lg form-control-solid" placeholder="Enter Address" value="{{ $user->address }}">
						</div>
					</div>

					@if($user->role_id == 9)
					<div class="row mb-6">
						<label class="col-lg-4 col-form-label fw-bold fs-6">
							<span>Calling Id</span>
						</label>
						<div class="col-lg-8">
							<input type="text" name="call_id" class="form-control form-control-lg form-control-solid" placeholder="Enter Agent Calling Id" value="{{ $user->call_id }}">
						</div>
					</div>
					@endif

					@if(\App\Services\ReferralCodeService::canEdit(auth()->user()))
						<div class="separator my-6"></div>
						<h4 class="fw-bolder text-gray-800 mb-5">Referral Settings</h4>
						@if(in_array((int) $user->role_id, \App\Services\ReferralCodeService::STAFF_EXECUTIVE_ROLE_IDS, true))
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">Executive Prefix (2 chars)</label>
								<div class="col-lg-8">
									<input type="text" name="referral_prefix" maxlength="2" class="form-control form-control-lg form-control-solid text-uppercase font-monospace" placeholder="e.g. US" value="{{ $user->referral_prefix }}">
									<div class="form-text">Exactly 2 letters/digits used as the executive prefix in client codes generated from this executive's conversions.</div>
								</div>
							</div>
						@else
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">Referral Code (5-10 chars)</label>
								<div class="col-lg-8">
									<input type="text" name="referral_code" maxlength="10" class="form-control form-control-lg form-control-solid text-uppercase font-monospace" placeholder="Auto-generated on confirmed order" value="{{ $user->referral_code }}">
									<div class="form-text">Format: User prefix (3) + Executive prefix (2) + Discount (10).</div>
								</div>
							</div>
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">Referral Discount (%)</label>
								<div class="col-lg-4">
									<input type="number" step="0.01" min="0" max="100" name="referral_discount_percent" class="form-control form-control-lg form-control-solid" value="{{ $user->referral_discount_percent !== null ? number_format((float) $user->referral_discount_percent, 2, '.', '') : '10.00' }}">
								</div>
							</div>
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">Marketing Executive</label>
								<div class="col-lg-8">
									<select name="referral_executive_id" class="form-select form-select-solid form-select-lg js-referral-exec-select" data-selected-executive="{{ $user->referral_executive_id ?? '' }}">
										<option value="">System / Default (AI)</option>
										@if($user->referralExecutive)
											<option value="{{ $user->referralExecutive->id }}" selected>{{ $user->referralExecutive->name }}@if(!empty($user->referralExecutive->referral_prefix)) ({{ $user->referralExecutive->referral_prefix }})@endif</option>
										@endif
									</select>
								</div>
							</div>
						@endif
					@endif
				</div>
				<div class="modal-footer py-4 px-9" style="flex-shrink: 0; border-top: 1px solid #eff2f5; background: #fff;">
					<button data-bs-dismiss="modal" type="button" class="btn btn-secondary">Close</button>
					<button type="submit" class="btn btn-primary" style="font-weight: 600;"><i class="fa fa-save me-1"></i> Save Changes</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Edit Section End -->

<!-- Delete Section Start -->
<div class="modal fade" id="kt_modal_create_delete{{$user->id}}" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-600px">
		<div class="modal-content">
			<form method="POST" action="{{ route('user.delete', ['id' => $user->id]) }}">
				@method('DELETE')
				@csrf
				<div class="modal-header">
					<h2 class="fw-bolder">Delete User</h2>
					<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
						<span class="svg-icon svg-icon-1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
								<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
							</svg>
						</span>
					</div>
				</div>
				<div class="modal-body py-8 px-9">
					<p class="fs-5 mb-0">Are you sure you want to delete user <span class="text-danger fw-bolder">{{ $user->name }} ({{ $user->email }})</span>?</p>
					<p class="text-muted fs-7 mb-0 mt-2">This action cannot be undone.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-danger">Delete</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Delete Section End -->
