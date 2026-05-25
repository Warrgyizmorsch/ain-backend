@extends('layouts.app')

@section('content')
	<style>
		#teamMembersContainer {
			max-height: 500px;
			/* Set a maximum height for the container */
			overflow-y: auto;
			/* Enable vertical scrolling if content exceeds the container height */
		}

		.member-container {
			white-space: nowrap;
			/* Prevent member names from wrapping to the next line */
			overflow: hidden;
			/* Hide the overflowed content (e.g., long names) */
			text-overflow: ellipsis;
			/* Display an ellipsis (...) for truncated text */
		}
	</style>

	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
		<div class="toolbar" id="kt_toolbar">
			<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
				<div data-kt-swapper="true" data-kt-swapper-mode="prepend"
					data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
					class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
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
								<h3 class="card-title fw-bolder text-white">Payment Statistics</h3>
							</div>
							<div class="card-body p-0">
								<div class="mixed-widget-2-chart card-rounded-bottom bg-danger" data-kt-color="danger"
									style="height: 200px"></div>
								<div class="card-p mt-n20 position-relative">
									<div class="row g-0">
										<!-- Total Payment -->
										<div
											class="col bg-light-warning px-6 py-8 rounded-2 me-7 mb-7 d-flex align-items-center justify-content-between">
											<div>
												<span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
													<i class="fas fa-wallet text-warning fa-3x"></i>
												</span>
												<a href="#" class="text-warning fw-bold fs-6">Total Payment</a>
											</div>
											<h2 class="me-3">{{ $Paymentstotal }}</h2>
										</div>
										<!-- Checked Payments -->
										<div
											class="col bg-light-primary px-6 py-8 rounded-2 mb-7 d-flex align-items-center justify-content-between">
											<div>
												<span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
													<i class="fas fa-check-circle text-primary fa-3x"></i>
												</span>
												<a href="#" class="text-primary fw-bold fs-6">Checked Payments</a>
											</div>
											<h2 class="me-3">{{ $checkedPayments }}</h2>
										</div>
									</div>
									<div class="row g-0">
										<!-- Unchecked Payments -->
										<div
											class="col bg-light-danger px-6 py-8 rounded-2 me-7 d-flex align-items-center justify-content-between">
											<div>
												<span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
													<i class="fas fa-times-circle text-danger fa-3x"></i>
												</span>
												<a href="#" class="text-danger fw-bold fs-6">Unchecked Payments</a>
											</div>
											<h2 class="me-3">{{ $uncheckedpayment }}</h2>
										</div>
										<!-- Today's Payments -->
										<div
											class="col bg-light-success px-6 py-8 rounded-2 d-flex align-items-center justify-content-between">
											<div>
												<span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
													<i class="fas fa-calendar-day text-success fa-3x"></i>
												</span>
												<a href="#" class="text-success fw-bold fs-6">Today's Payments</a>
											</div>
											<h2 class="me-3">{{ $todaypaymentcheckked }}</h2>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

@endsection