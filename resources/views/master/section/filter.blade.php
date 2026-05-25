<div class="card card-xxl-stretch mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
    	<h3 class="card-title align-items-start flex-column">
    		<span class="card-label fw-bolder fs-3 mb-1">Filter</span>
    	</h3>
    </div>
    <div class="card-body py-3">
    	<form action="">
    		<div class="row mb-3">
                
				<div class="col-md-3 fv-row">
                    <input type="search" name="search" id="search" class="form-control form-control-solid" placeholder="Serach By OrderCode">
                </div>

				<script>
					document.addEventListener('DOMContentLoaded', function() {
						const searchInput = document.getElementById('searchInput');

						searchInput.addEventListener('input', function(event) {
							const inputValue = event.target.value;
							const sanitizedValue = inputValue.replace(/\s/g, ''); // Remove spaces

							// Update input value without spaces
							if (inputValue !== sanitizedValue) {
								searchInput.value = sanitizedValue;
							}
						});
					});
				</script>
                @if(auth()->user()->role_id == 1)
                <div class="col-md-3 fv-row">
                    <input type="text" list="searchDatalist" id="searchInput" class="form-control form-control-solid" placeholder="Search..." autocomplete="off">
                    <!-- Datalist for displaying search results -->
                    <datalist id="searchDatalist"></datalist>
                    <!-- Container to display search results -->
                    <div id="searchResultss"></div>
                    <!-- Hidden field to store the selected value -->
                    <input type="hidden" id="selectedValue" name="uid">
                </div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function () {
                        $('#searchInput').on('input', function () {
                            var searchValue = $(this).val();

                            if (searchValue.length >= 3) {
                                $.ajax({
                                    url: "{{ route('search-order') }}",
                                    type: "GET",
                                    data: { user: searchValue },
                                    success: function (response) {
                                        var results = '';
                                        if (response.length > 0) {
                                            // Populate the datalist with search results
                                            $('#searchDatalist').empty();
                                            $.each(response, function (key, value) {
                                                // Append each option with email, name, and mobile number
                                                $('#searchDatalist').append('<option value="' + value.email + '">' + value.name + ' (' + value.mobile_no + ')</option>');
                                            });
                                            if(response.length === 1) {
                                                // If there is only one result, automatically fill in the search input
                                                $('#searchInput').val(response[0].email);
                                                // Store the selected value in the hidden field
                                                $('#selectedValue').val(response[0].id);
                                            }
                                        } else {
                                            results = '<div>No results found</div>';
                                        }
                                        $('#searchResultss').html(results);
                                    }
                                });
                            } else {
                                $('#searchResultss').empty();
                            }
                        });

                        // Handle click on search result
                        $('#searchInput').on('input', function() {
                            var selectedEmail = $(this).val();
                            var selectedOption = $('#searchDatalist option[value="' + selectedEmail + '"]');
                            if (selectedOption.length > 0) {
                                // If the selected value exists in the datalist, get its associated ID
                                var selectedId = selectedOption.data('id');
                                $('#selectedValue').val(selectedId);
                            } else {
                                // If the selected value doesn't exist in the datalist, clear the hidden field
                                $('#selectedValue').val('');
                            }
                        });
                    });
                </script>
                @endif
				<div class="col-md-3 fv-row">
                    <input type="date" name="dateFrom" id="" class="form-control form-control-solid" placeholder="Search By Date">
                </div>
				<div class="col-md-3 fv-row">
                    <input type="date" name="dateTo" id="" class="form-control form-control-solid" placeholder="Search By Date">
                </div>
    			<div class="col-md-3 fv-row mt-3">
                    <select name="company_accounts" id="" aria-label="Select a Timezone" data-control="select2" class="form-control form-control-solid" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                        <option value="" disabled selected hidden>Select a Company Account</option>
                        <option value="HDFC">HDFC</option>
                        <option value="Native">Native</option>
                        <option value="PayPal">PayPal</option>
                          <option value="Skydo">Skydo</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-3 fv-row mt-3">
                    <input type="text" name="p_name"  class="form-control form-control-solid" placeholder="Serach By Payee Name">
                </div>

				<div class="row">
					<div class="col-lg-12 fv-row fv-plugins-icon-container mt-2">
						<button type='submit' class="btn btn-sm btn-primary">Search</button>
						<a href="/1008/Payments" class="btn btn-sm btn-danger">reset</a>
					</div>
				</div>
    		</div>
    	</form>

		
    </div>




	