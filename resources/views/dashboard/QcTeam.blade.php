@extends('layouts.app')

@section('content')
<style>
    #teamMembersContainer {
        max-height: 500px; /* Set a maximum height for the container */
        overflow-y: auto; /* Enable vertical scrolling if content exceeds the container height */
    }

    .member-container {
        white-space: nowrap; /* Prevent member names from wrapping to the next line */
        overflow: hidden; /* Hide the overflowed content (e.g., long names) */
        text-overflow: ellipsis; /* Display an ellipsis (...) for truncated text */
    }
</style>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="toolbar" id="kt_toolbar">
		<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
			<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
				<h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard Project Team
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Assingment In Need</small>
                </h1>
			</div>
			<!-- <div class="d-flex align-items-center py-1">
				<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app" id="kt_toolbar_primary_button">Create</a>
			</div> -->
		</div>
	</div>

	<div class="post d-flex flex-column-fluid" id="kt_post">
		<div id="kt_content_container" class="container-xxl">
			
			<div class="row gy-5 g-xl-8">
				<div class="col-xl-12">
					<div class="card card-xl-stretch mb-xl-8">
						<div class="card-header border-0">
							<h3 class="card-title fw-bolder text-dark">Your Team Member</h3>
							<div class="card-toolbar">
								<button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
									<span class="svg-icon svg-icon-2">
										<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="5" y="5" width="5" height="5" rx="1" fill="#000000" />
												<rect x="14" y="5" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
												<rect x="5" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
												<rect x="14" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
											</g>
										</svg>
									</span>
								</button>
								
							</div>
						</div>
						<div class="card-body pt-2">
							
							<!-- show the team member here -->
                            <div id="teamMembersContainer"></div>

						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- ... (previous content) ... -->

<script>
    $(document).ready(function () {
        // Event handler for role selection
        $('#roleSelector').change(function () {
            var roleId = $(this).val();

            // Check if a role is selected
            if (roleId) {
                // Make an AJAX request to fetch team members
                $.ajax({
                    url: '/fetch-team-members',  // The URL of the route that handles the AJAX request
                    type: 'GET',
                    data: { role_id: roleId },
                    success: function (response) {
                        // Handle the response and update the team members container
                        updateTeamMembers(response.teamMembers);
                        // Close the dropdown
                        $('.btn.btn-color-primary.btn-active-light-primary').trigger('click');
                    },
                    error: function (error) {
                        console.error('Error fetching team members:', error);
                    }
                });
            } else {
                // Clear team members container if no role is selected
                updateTeamMembers([]);
            }
        });

        // Initial fetch for default role (you can change this to the desired default role)
        fetchTeamMembers(4); // Assuming "Marketing Team" has roleId 4
    });

    function fetchTeamMembers(roleId) {
        // Make an initial AJAX request to fetch team members for the default role
        $.ajax({
            url: '/fetch-team-members',  // The URL of the route that handles the AJAX request
            type: 'GET',
            data: { role_id: 3 },
            success: function (response) {
                // Handle the response and update the team members container
                updateTeamMembers(response.teamMembers);
            },
            error: function (error) {
                console.error('Error fetching team members:', error);
            }
        });
    }

    function updateTeamMembers(teamMembers) {
        // Clear existing team members
        $('#teamMembersContainer').html('');

        // Append new team members
        teamMembers.forEach(function (member) {
            // Check if the member has a photo
            var photoUrl = member.photo ? member.photo : 'assets/media/avatars/blank.png';

            var memberHtml = '<div class="d-flex align-items-center mb-7">' +
                '<div class="symbol symbol-50px me-5">' +
                '<img src="' + photoUrl + '" class="" alt="" />' +
                '</div>' +
                '<div class="flex-grow-1">' +
                '<a href="#" class="text-dark fw-bolder text-hover-primary fs-6">' + member.name + '</a>' +
                '</div>' +
                '</div>';

            // Append the HTML to the container
            $('#teamMembersContainer').append(memberHtml);
        });
    }


</script>


<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="toolbar" id="kt_toolbar">
		<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
			<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
				<h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Assingment In Need</small>
                </h1>
			</div>
			<!-- <div class="d-flex align-items-center py-1">
				<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app" id="kt_toolbar_primary_button">Create</a>
			</div> -->
		</div>
	</div>
	</div>

	
						


@endsection
