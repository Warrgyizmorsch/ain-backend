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
@if (auth()->check() && auth()->user()->role_id == 4 || auth()->user()->role_id == 9) 
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="toolbar" id="kt_toolbar">
		<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
			<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
				<h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard Marketing Team
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
					<div class="card card-xl-stretch">
						<div class="card-header border-0 bg-danger py-5">
							<h3 class="card-title fw-bolder text-white">Order Statistics</h3>
							<div class="card-toolbar">
								<button type="button" class="btn btn-sm btn-icon btn-color-white btn-active-white btn-active-color- border-0 me-n3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
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
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
									<div class="menu-item px-3">
										<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
									</div>
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Create Invoice</a>
									</div>
									<div class="menu-item px-3">
										<a href="#" class="menu-link flex-stack px-3">Create Payment
										<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference"></i></a>
									</div>
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Generate Bill</a>
									</div>
									<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
										<a href="#" class="menu-link px-3">
											<span class="menu-title">Subscription</span>
											<span class="menu-arrow"></span>
										</a>
										<div class="menu-sub menu-sub-dropdown w-175px py-4">
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Plans</a>
											</div>
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Billing</a>
											</div>
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Statements</a>
											</div>
											<div class="separator my-2"></div>
											<div class="menu-item px-3">
												<div class="menu-content px-3">
													<label class="form-check form-switch form-check-custom form-check-solid">
														<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
														<span class="form-check-label text-muted fs-6">Recuring</span>
													</label>
												</div>
											</div>
										</div>
									</div>
									<div class="menu-item px-3 my-1">
										<a href="#" class="menu-link px-3">Settings</a>
									</div>
								</div>
							</div>
						</div>
						<div class="card-body p-0">
							<div class="mixed-widget-2-chart card-rounded-bottom bg-danger" data-kt-color="danger" style="height: 200px"></div>
							<div class="card-p mt-n20 position-relative">
								<div class="row g-0">
									<div class="col bg-light-warning px-6 py-8 rounded-2 me-7 mb-7" style="display: flex; align-items: center; justify-content: space-between;">
										<div>
											<span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<rect x="8" y="9" width="3" height="10" rx="1.5" fill="black" />
													<rect opacity="0.5" x="13" y="5" width="3" height="14" rx="1.5" fill="black" />
													<rect x="18" y="11" width="3" height="8" rx="1.5" fill="black" />
													<rect x="3" y="13" width="3" height="6" rx="1.5" fill="black" />
												</svg>
											</span>
											<a href="#" class="text-warning fw-bold fs-6">Total orders</a>
										</div>
										<h2 style="margin-right: 10px;">{{ $totalOrderCount }}</h2>
									</div>
									<div class="col bg-light-primary px-6 py-8 rounded-2 mb-7" style="display: flex; align-items: center; justify-content: space-between;">
										<div>
											<span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#74C0FC" d="M211.2 96a64 64 0 1 0 -128 0 64 64 0 1 0 128 0zM32 256c0 17.7 14.3 32 32 32h85.6c10.1-39.4 38.6-71.5 75.8-86.6c-9.7-6-21.2-9.4-33.4-9.4H96c-35.3 0-64 28.7-64 64zm461.6 32H576c17.7 0 32-14.3 32-32c0-35.3-28.7-64-64-64H448c-11.7 0-22.7 3.1-32.1 8.6c38.1 14.8 67.4 47.3 77.7 87.4zM391.2 226.4c-6.9-1.6-14.2-2.4-21.6-2.4h-96c-8.5 0-16.7 1.1-24.5 3.1c-30.8 8.1-55.6 31.1-66.1 60.9c-3.5 10-5.5 20.8-5.5 32c0 17.7 14.3 32 32 32h224c17.7 0 32-14.3 32-32c0-11.2-1.9-22-5.5-32c-10.8-30.7-36.8-54.2-68.9-61.6zM563.2 96a64 64 0 1 0 -128 0 64 64 0 1 0 128 0zM321.6 192a80 80 0 1 0 0-160 80 80 0 1 0 0 160zM32 416c-17.7 0-32 14.3-32 32s14.3 32 32 32H608c17.7 0 32-14.3 32-32s-14.3-32-32-32H32z"/></svg>
											</span>
											<a href="#" class="text-primary fw-bold fs-6">In Progress</a>
										</div>
										<h2 style="margin-right: 10px;">{{ $inprogressOrder }}</h2>
									</div>
								</div>
								<div class="row g-0">
									<div class="col bg-light-danger px-6 py-8 rounded-2 me-7" style="display: flex; align-items: center; justify-content: space-between;">
										<div>
											<span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path opacity="0.3" d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z" fill="black" />
													<path d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z" fill="black" />
												</svg>
											</span>
											<a href="#" class="text-danger fw-bold fs-6 mt-2">Today's Orders</a>
										</div>
										<h2 style="margin-right: 10px;">{{ $todayOrdersCount }}</h2>
									</div>
									<div class="col bg-light-success px-6 py-8 rounded-2" style="display: flex; align-items: center; justify-content: space-between;">
										<div>
											<span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path d="M6 8.725C6 8.125 6.4 7.725 7 7.725H14L18 11.725V12.925L22 9.725L12.6 2.225C12.2 1.925 11.7 1.925 11.4 2.225L2 9.725L6 12.925V8.725Z" fill="black" />
													<path opacity="0.3" d="M22 9.72498V20.725C22 21.325 21.6 21.725 21 21.725H3C2.4 21.725 2 21.325 2 20.725V9.72498L11.4 17.225C11.8 17.525 12.3 17.525 12.6 17.225L22 9.72498ZM15 11.725H18L14 7.72498V10.725C14 11.325 14.4 11.725 15 11.725Z" fill="black" />
												</svg>
											</span>
											<a href="#" class="text-success fw-bold fs-6 mt-2">Feedback Issue Raised Orders</a>
										</div>
										<h2 style="margin-right: 10px;">{{ $feedbackCount }}</h2>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row gy-5 g-xl-8">
				<div class="col-xl-4">
					<div class="card card-xl-stretch mb-xl-8">
						<div class="card-header border-0">
							<h3 class="card-title fw-bolder text-dark">{{auth()->user()->name}}</h3>
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
				<div class="col-xl-8">
                    <!--begin::Charts Widget 5-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Recent Orders</span>
                                <span class="text-muted fw-bold fs-7">More than {{$currentMonthOrdersCount}}+ new orders this month.</span>
                            </h3>
                            <!--begin::Toolbar-->
                            <div class="card-toolbar" data-kt-buttons="true">
                                <a class="btn btn-sm btn-color-muted btn-active btn-active-primary active px-4 me-1" id="kt_charts_widget_6_sales_btn">Sales</a>
                                <a class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4 me-1" id="kt_charts_widget_6_expenses_btn">Expenses</a>
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Chart-->
                            <div id="piechart_3d" style="width: 550px; height: 500px;"></div>
                            <!--end::Chart-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Charts Widget 5-->
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
            data: { role_id: 4 },
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

<!-- ... (remaining content) ... -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Task', 'Hours per Day'],
        ['Other',      <?php echo $otherOrder ?>],
        ['Cancelled',  <?php echo $cancelledOrder ?>],
        ['Pending',    <?php echo $pendingOrder ?>],
        ['Hold Work',  <?php echo $holdWorkOrder ?>],
        ['In Progress',<?php echo $inprogressOrder ?>]
    ]);

    var options = {
        title: 'Orders by Status',
        is3D: true,
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
    chart.draw(data, options);
    }
</script>
@else
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

	<div class="post d-flex flex-column-fluid" id="kt_post">
		<div id="kt_content_container" class="container-xxl">
			<div class="row gy-5 g-xl-8">
				<div class="col-xl-12">
					<div class="card card-xl-stretch">
						<div class="card-header border-0 bg-danger py-5">
							<h1>Coming Soon...</h1>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
						

@endif


@endsection
