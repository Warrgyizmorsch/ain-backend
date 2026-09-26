<form id="" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST" enctype="multipart/form-data" action="{{ route('usercreate') }}">
						@csrf
						
						<div class="card-body border-top p-9">
							<!-- ... (other form fields) -->
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">Avatar</label>
								<div class="col-lg-8">
									<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(assets/media/avatars/blank.png)">
										<div class="image-input-wrapper w-125px h-125px" style="background-image:  url(assets/media/avatars/blank.png)"></div>
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


							<div class="row mb-12">
								<label class="col-lg-4 col-form-label required fw-bold fs-6">Name</label>
								<div class="col-lg-8">
									<div class="row">
										<div class="col-lg-12 fv-row fv-plugins-icon-container">
											<input required type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name"  value="">
										<div class="fv-plugins-message-container invalid-feedback"></div></div>
										
									</div>
								</div>
							</div>
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input required type="email" name="email" class="form-control form-control-lg form-control-solid" placeholder="Youremail@gmail.com"  >
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>

                            <div class="row mb-6">
								<label class="col-lg-4 col-form-label required fw-bold fs-6">Role</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <select required name="role" id="user_role_select" aria-label="Select Role" data-control="select2" data-placeholder="Select Role" class="form-select form-select-solid form-select-lg">
                                    <option value="">Select Role</option>
                                    @foreach($data['role'] as $role)
									<option value="{{ $role->id}}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->role}}</option>
                                    @endforeach
                                </select>
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>

							<!-- Team Dropdown (Mandatory when Role is Marketing Team) -->
							<div class="row mb-6" id="team_select_row" style="display: none;">
								<label class="col-lg-4 col-form-label fw-bold fs-6">
									<span id="team_required_indicator" class="required">Team</span>
								</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<select name="team_id" id="user_team_select" data-control="select2" data-placeholder="Select Team" class="form-select form-select-solid form-select-lg">
										<option value="">Select Team</option>
										@if(!empty($data['teams']))
											@foreach($data['teams'] as $team)
												<option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>{{ $team->team_name }}</option>
											@endforeach
										@endif
									</select>
									<div class="form-text text-danger fw-semibold" id="team_help_text" style="display:none;">
										<i class="bi bi-exclamation-circle text-danger me-1"></i> Team selection is mandatory for Marketing Team users.
									</div>
									<div class="fv-plugins-message-container invalid-feedback" id="team_error_msg"></div>
								</div>
							</div>
							<!-- Country Code input -->
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">Primary Country Code</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<select name="country_code" class="form-select form-select-lg form-select-solid" data-control="select2" data-placeholder="Select Primary Country Code" data-allow-clear="true">
										<option value="">Select Country Code</option>
										<option value="91">India (+91)</option>
										<option value="1">USA/Canada (+1)</option>
										<option value="44">UK (+44)</option>
										<option value="61">Australia (+61)</option>
										<option value="971">UAE (+971)</option>
										<option value="92">Pakistan (+92)</option>
										<option value="880">Bangladesh (+880)</option>
										<option value="94">Sri Lanka (+94)</option>
										<option value="977">Nepal (+977)</option>
										<option value="994">Azerbaijan (+994)</option>
										<option value="40">Romania (+40)</option>
										<option value="64">New Zealand (+64)</option>
										<option value="233">Ghana (+233)</option>
										<option value="45">Denmark (+45)</option>
										<option value="49">Germany (+49)</option>
										<option value="234">Nigeria (+234)</option>
										<option value="966">Saudi Arabia (+966)</option>
										<option value="27">South Africa (+27)</option>
										<option value="65">Singapore (+65)</option>
										<option value="60">Malaysia (+60)</option>
										<option value="353">Ireland (+353)</option>
										<option value="968">Oman (+968)</option>
										<option value="974">Qatar (+974)</option>
										<option value="965">Kuwait (+965)</option>
										<option value="973">Bahrain (+973)</option>
										<option value="33">France (+33)</option>
										<option value="39">Italy (+39)</option>
										<option value="34">Spain (+34)</option>
										<option value="31">Netherlands (+31)</option>
										<option value="46">Sweden (+46)</option>
										<option value="41">Switzerland (+41)</option>
										<option value="86">China (+86)</option>
										<option value="81">Japan (+81)</option>
										<option value="82">South Korea (+82)</option>
										<option value="7">Russia (+7)</option>
										<option value="55">Brazil (+55)</option>
										<option value="52">Mexico (+52)</option>
										<option value="20">Egypt (+20)</option>
										<option value="254">Kenya (+254)</option>
										<option value="256">Uganda (+256)</option>
										<option value="90">Turkey (+90)</option>
										<option value="98">Iran (+98)</option>
										<option value="380">Ukraine (+380)</option>
										<option value="359">Bulgaria (+359)</option>
										<option value="224">Guinea (+224)</option>
										<option value="356">Malta (+356)</option>
										<option value="231">Liberia (+231)</option>
										<option value="230">Mauritius (+230)</option>
										<option value="30">Greece (+30)</option>
										<option value="43">Austria (+43)</option>
										<option value="964">Iraq (+964)</option>
										<option value="237">Cameroon (+237)</option>
										<option value="355">Albania (+355)</option>
										<option value="32">Belgium (+32)</option>
										<option value="972">Israel (+972)</option>
										<option value="260">Zambia (+260)</option>
										<option value="371">Latvia (+371)</option>
									</select>
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div>
							</div>
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">
									<span class="required">Primary Mobile Number</span>
									<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i>
								</label>
								
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input required type="tel" name="primary_mobile" class="form-control form-control-lg form-control-solid" placeholder="Primary Mobile Number" >
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>
							<!-- Country Code input -->
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">Secondary Country Code</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<select name="country_code2" class="form-select form-select-lg form-select-solid" data-control="select2" data-placeholder="Select Secondary Country Code" data-allow-clear="true">
										<option value="">Select Country Code</option>
										<option value="91">India (+91)</option>
										<option value="1">USA/Canada (+1)</option>
										<option value="44">UK (+44)</option>
										<option value="61">Australia (+61)</option>
										<option value="971">UAE (+971)</option>
										<option value="92">Pakistan (+92)</option>
										<option value="880">Bangladesh (+880)</option>
										<option value="94">Sri Lanka (+94)</option>
										<option value="977">Nepal (+977)</option>
										<option value="994">Azerbaijan (+994)</option>
										<option value="40">Romania (+40)</option>
										<option value="64">New Zealand (+64)</option>
										<option value="233">Ghana (+233)</option>
										<option value="45">Denmark (+45)</option>
										<option value="49">Germany (+49)</option>
										<option value="234">Nigeria (+234)</option>
										<option value="966">Saudi Arabia (+966)</option>
										<option value="27">South Africa (+27)</option>
										<option value="65">Singapore (+65)</option>
										<option value="60">Malaysia (+60)</option>
										<option value="353">Ireland (+353)</option>
										<option value="968">Oman (+968)</option>
										<option value="974">Qatar (+974)</option>
										<option value="965">Kuwait (+965)</option>
										<option value="973">Bahrain (+973)</option>
										<option value="33">France (+33)</option>
										<option value="39">Italy (+39)</option>
										<option value="34">Spain (+34)</option>
										<option value="31">Netherlands (+31)</option>
										<option value="46">Sweden (+46)</option>
										<option value="41">Switzerland (+41)</option>
										<option value="86">China (+86)</option>
										<option value="81">Japan (+81)</option>
										<option value="82">South Korea (+82)</option>
										<option value="7">Russia (+7)</option>
										<option value="55">Brazil (+55)</option>
										<option value="52">Mexico (+52)</option>
										<option value="20">Egypt (+20)</option>
										<option value="254">Kenya (+254)</option>
										<option value="256">Uganda (+256)</option>
										<option value="90">Turkey (+90)</option>
										<option value="98">Iran (+98)</option>
										<option value="380">Ukraine (+380)</option>
										<option value="359">Bulgaria (+359)</option>
										<option value="224">Guinea (+224)</option>
										<option value="356">Malta (+356)</option>
										<option value="231">Liberia (+231)</option>
										<option value="230">Mauritius (+230)</option>
										<option value="30">Greece (+30)</option>
										<option value="43">Austria (+43)</option>
										<option value="964">Iraq (+964)</option>
										<option value="237">Cameroon (+237)</option>
										<option value="355">Albania (+355)</option>
										<option value="32">Belgium (+32)</option>
										<option value="972">Israel (+972)</option>
										<option value="260">Zambia (+260)</option>
										<option value="371">Latvia (+371)</option>
									</select>
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div>
							</div>

							<!-- Secondary Mobile Number input with Country Code -->
							<div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">Secondary Mobile Number</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input type="tel" name="secondary_mobile" class="form-control form-control-lg form-control-solid" placeholder="Secondary Mobile Number">
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div>
							</div>

                            <div class="row mb-6">
								<label class="col-lg-4 col-form-label fw-bold fs-6">
									<span class="required">Address</span>
									<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Phone number must be active" aria-label="Phone number must be active"></i>
								</label>
								<div class="col-lg-8 fv-row fv-plugins-icon-container">
									<input  type="text" name="address" class="form-control form-control-lg form-control-solid" placeholder="Enter YOur Address" >
								<div class="fv-plugins-message-container invalid-feedback"></div></div>
							</div>
							
						</div>
						<div class="card-footer d-flex justify-content-end py-6 px-9">
							<!-- <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button> -->
							<button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
						</div>
					<input type="hidden">
					<div>

					</div>
				</form>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const roleSelect = document.getElementById("user_role_select") || document.querySelector('select[name="role"]');
    const teamRow = document.getElementById("team_select_row");
    const teamSelect = document.getElementById("user_team_select");
    const teamIndicator = document.getElementById("team_required_indicator");
    const teamHelpText = document.getElementById("team_help_text");

    function checkRoleForTeam() {
        const val = roleSelect ? roleSelect.value : "";
        // Role ID 4 is Marketing Team
        if (val == "4") {
            if (teamRow) teamRow.style.display = "flex";
            if (teamSelect) teamSelect.setAttribute("required", "required");
            if (teamIndicator) teamIndicator.style.display = "inline-block";
            if (teamHelpText) teamHelpText.style.display = "block";
        } else if (val == "9") {
            // Subadmin: optional team
            if (teamRow) teamRow.style.display = "flex";
            if (teamSelect) teamSelect.removeAttribute("required");
            if (teamIndicator) teamIndicator.style.display = "none";
            if (teamHelpText) teamHelpText.style.display = "none";
        } else {
            if (teamRow) teamRow.style.display = "none";
            if (teamSelect) {
                teamSelect.removeAttribute("required");
                if (typeof $ !== "undefined" && $(teamSelect).val()) {
                    $(teamSelect).val("").trigger("change");
                }
            }
            if (teamIndicator) teamIndicator.style.display = "none";
            if (teamHelpText) teamHelpText.style.display = "none";
        }
    }

    if (roleSelect) {
        if (typeof $ !== "undefined") {
            $(roleSelect).on("change", checkRoleForTeam);
        } else {
            roleSelect.addEventListener("change", checkRoleForTeam);
        }
        checkRoleForTeam();
    }
});
</script>
