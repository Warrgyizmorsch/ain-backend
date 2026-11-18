
@php
	$passwordError = session('error');
	$isPasswordSessionError = is_string($passwordError) && (
		str_contains(strtolower($passwordError), 'current password') ||
		str_contains(strtolower($passwordError), 'new password')
	);
	$showPasswordEdit = $errors->has('current_password')
		|| $errors->has('new_password')
		|| $errors->has('new_password_confirmation')
		|| $isPasswordSessionError;
@endphp

<div class="card mb-5 mb-xl-10">
	
	<div id="kt_account_signin_method" class="collapse show">
		<div class="card-body border-top p-9">
			<div class="d-flex flex-wrap align-items-center mb-10">
				<div id="kt_signin_password" class="{{ $showPasswordEdit ? 'd-none' : '' }}">
					<div class="fs-6 fw-bolder mb-1">Password</div>
					<div class="fw-bold text-gray-600">************</div>
				</div>
				<div id="kt_signin_password_edit" class="flex-row-fluid {{ $showPasswordEdit ? '' : 'd-none' }}">
					@if ($isPasswordSessionError)
						<div class="alert alert-danger mb-5" role="alert">
							{{ $passwordError }}
						</div>
					@endif
				<form method="POST" action="{{ route('postChangePassword') }}" id="kt_signin_change_password" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
						@csrf
						@method('POST')
						<div class="row mb-1">
							<div class="col-lg-4">
								<div class="fv-row mb-0 fv-plugins-icon-container">
									<label for="current_password" class="form-label fs-6 fw-bolder mb-3">Current Password</label>
									<input type="password" id="current_password" name="current_password" class="form-control form-control-lg form-control-solid{{ $errors->has('current_password') ? ' is-invalid' : '' }}" required autocomplete="current-password">
									
									<div class="fv-plugins-message-container invalid-feedback{{ $errors->has('current_password') ? ' d-block' : '' }}">@error('current_password'){{ $message }}@enderror</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="fv-row mb-0 fv-plugins-icon-container">
									<label for="new_password" class="form-label fs-6 fw-bolder mb-3">New Password</label>
									<input type="password" id="new_password" name="new_password" class="form-control form-control-lg form-control-solid{{ $errors->has('new_password') ? ' is-invalid' : '' }}" required autocomplete="new-password">
									<div class="fv-plugins-message-container invalid-feedback{{ $errors->has('new_password') ? ' d-block' : '' }}">@error('new_password'){{ $message }}@enderror</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="fv-row mb-0 fv-plugins-icon-container">
									<label for="new_password_confirmation" class="form-label fs-6 fw-bolder mb-3">Confirm New Password</label>
									<input id="new_password_confirmation" name="new_password_confirmation" type="password" required autocomplete="new-password" class="form-control form-control-lg form-control-solid{{ $errors->has('new_password_confirmation') ? ' is-invalid' : '' }}">
									<div class="fv-plugins-message-container invalid-feedback{{ $errors->has('new_password_confirmation') ? ' d-block' : '' }}">@error('new_password_confirmation'){{ $message }}@enderror</div>
								</div>
							</div>
						</div>
						<div class="form-text mb-5">Password must be at least 8 characters and contain symbols</div>
						<div class="d-flex">
							<button type="submit" class="btn btn-primary" id="kt_signin_password_submit">Save Changes</button>
							<button id="kt_password_cancel" type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6">Cancel</button>
						</div>
					</form>

				</div>
				<div id="kt_signin_password_button" class="ms-auto {{ $showPasswordEdit ? 'd-none' : '' }}">
					<button type="button" id="kt_signin_password_reset_btn" class="btn btn-light btn-active-light-primary">Reset Password</button>
				</div>
			</div>
			
		</div>
	</div>
</div>

<script>
	(function () {
		function initPasswordResetToggle() {
			var passwordDisplay = document.getElementById('kt_signin_password');
			var passwordEdit = document.getElementById('kt_signin_password_edit');
			var passwordButtonWrap = document.getElementById('kt_signin_password_button');
			var cancelButton = document.getElementById('kt_password_cancel');
			var resetButton = document.getElementById('kt_signin_password_reset_btn')
				|| (passwordButtonWrap ? passwordButtonWrap.querySelector('button') : null);

			if (!passwordDisplay || !passwordEdit || !passwordButtonWrap || !resetButton || !cancelButton) {
				return;
			}

			if (resetButton.dataset.passwordToggleBound === '1') {
				return;
			}
			resetButton.dataset.passwordToggleBound = '1';

			resetButton.addEventListener('click', function (event) {
				event.preventDefault();
				passwordDisplay.classList.add('d-none');
				passwordButtonWrap.classList.add('d-none');
				passwordEdit.classList.remove('d-none');

				var currentPasswordInput = document.getElementById('current_password');
				if (currentPasswordInput) {
					currentPasswordInput.focus();
				}
			});

			cancelButton.addEventListener('click', function (event) {
				event.preventDefault();
				passwordEdit.classList.add('d-none');
				passwordDisplay.classList.remove('d-none');
				passwordButtonWrap.classList.remove('d-none');

				var currentPasswordInput = document.getElementById('current_password');
				var newPasswordInput = document.getElementById('new_password');
				var confirmPasswordInput = document.getElementById('new_password_confirmation');
				if (currentPasswordInput) currentPasswordInput.value = '';
				if (newPasswordInput) newPasswordInput.value = '';
				if (confirmPasswordInput) confirmPasswordInput.value = '';
			});
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', initPasswordResetToggle);
		} else {
			initPasswordResetToggle();
		}
	})();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


                              
		