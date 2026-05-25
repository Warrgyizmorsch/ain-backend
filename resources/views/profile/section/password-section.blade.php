
<div class="card mb-5 mb-xl-10">
	
	<div id="kt_account_signin_method" class="collapse show">
		<div class="card-body border-top p-9">
			<div class="d-flex flex-wrap align-items-center mb-10">
				<div id="kt_signin_password">
					<div class="fs-6 fw-bolder mb-1">Password</div>
					<div class="fw-bold text-gray-600">************</div>
				</div>
				<div id="kt_signin_password_edit" class="flex-row-fluid d-none">
				<form method="POST" action="{{ route('postChangePassword') }}" id="kt_signin_change_password" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
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
							<button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
							<button id="kt_password_cancel" type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6">Cancel</button>
						</div>
					</form>

				</div>
				<div id="kt_signin_password_button" class="ms-auto">
					<button class="btn btn-light btn-active-light-primary">Reset Password</button>
				</div>
			</div>
			
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


                              
		