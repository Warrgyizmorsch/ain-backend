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
	@if (auth()->check() && auth()->user()->role_id == 1)
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
									<h3 class="card-title fw-bolder text-white">Order Statistics</h3>
									<div class="card-toolbar">
										<button type="button"
											class="btn btn-sm btn-icon btn-color-white btn-active-white btn-active-color- border-0 me-n3"
											data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
											<span class="svg-icon svg-icon-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
													viewBox="0 0 24 24">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="5" y="5" width="5" height="5" rx="1" fill="#000000" />
														<rect x="14" y="5" width="5" height="5" rx="1" fill="#000000"
															opacity="0.3" />
														<rect x="5" y="14" width="5" height="5" rx="1" fill="#000000"
															opacity="0.3" />
														<rect x="14" y="14" width="5" height="5" rx="1" fill="#000000"
															opacity="0.3" />
													</g>
												</svg>
											</span>
										</button>
										<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3"
											data-kt-menu="true">
											<div class="menu-item px-3">
												<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments
												</div>
											</div>
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Create Invoice</a>
											</div>
											<div class="menu-item px-3">
												<a href="#" class="menu-link flex-stack px-3">Create Payment
													<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
														title="Specify a target name for future usage and reference"></i></a>
											</div>
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Generate Bill</a>
											</div>
											<div class="menu-item px-3" data-kt-menu-trigger="hover"
												data-kt-menu-placement="right-end">
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
															<label
																class="form-check form-switch form-check-custom form-check-solid">
																<input class="form-check-input w-30px h-20px" type="checkbox"
																	value="1" checked="checked" name="notifications" />
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
									<div class="mixed-widget-2-chart card-rounded-bottom bg-danger" data-kt-color="danger"
										style="height: 200px"></div>
									<div class="card-p mt-n20 position-relative">
										<div class="row g-0">
											<div class="col bg-light-warning px-6 py-8 rounded-2 me-7 mb-7"
												style="display: flex; align-items: center; justify-content: space-between;">
												<div>
													<span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
															viewBox="0 0 24 24" fill="none">
															<rect x="8" y="9" width="3" height="10" rx="1.5" fill="black" />
															<rect opacity="0.5" x="13" y="5" width="3" height="14" rx="1.5"
																fill="black" />
															<rect x="18" y="11" width="3" height="8" rx="1.5" fill="black" />
															<rect x="3" y="13" width="3" height="6" rx="1.5" fill="black" />
														</svg>
													</span>
													<a href="#" class="text-warning fw-bold fs-6">Total orders</a>
												</div>
												<h2 style="margin-right: 10px;">{{ $totalOrderCount }}</h2>
											</div>
											<div class="col bg-light-primary px-6 py-8 rounded-2 mb-7"
												style="display: flex; align-items: center; justify-content: space-between;">
												<div>
													<span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
														<svg xmlns="http://www.w3.org/2000/svg"
															viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
															<path fill="#74C0FC"
																d="M211.2 96a64 64 0 1 0 -128 0 64 64 0 1 0 128 0zM32 256c0 17.7 14.3 32 32 32h85.6c10.1-39.4 38.6-71.5 75.8-86.6c-9.7-6-21.2-9.4-33.4-9.4H96c-35.3 0-64 28.7-64 64zm461.6 32H576c17.7 0 32-14.3 32-32c0-35.3-28.7-64-64-64H448c-11.7 0-22.7 3.1-32.1 8.6c38.1 14.8 67.4 47.3 77.7 87.4zM391.2 226.4c-6.9-1.6-14.2-2.4-21.6-2.4h-96c-8.5 0-16.7 1.1-24.5 3.1c-30.8 8.1-55.6 31.1-66.1 60.9c-3.5 10-5.5 20.8-5.5 32c0 17.7 14.3 32 32 32h224c17.7 0 32-14.3 32-32c0-11.2-1.9-22-5.5-32c-10.8-30.7-36.8-54.2-68.9-61.6zM563.2 96a64 64 0 1 0 -128 0 64 64 0 1 0 128 0zM321.6 192a80 80 0 1 0 0-160 80 80 0 1 0 0 160zM32 416c-17.7 0-32 14.3-32 32s14.3 32 32 32H608c17.7 0 32-14.3 32-32s-14.3-32-32-32H32z" />
														</svg>
													</span>
													<a href="#" class="text-primary fw-bold fs-6">Total User</a>
												</div>
												<h2 style="margin-right: 10px;">{{ $totalUserCount }}</h2>
											</div>
										</div>
										<div class="row g-0">
											<div class="col bg-light-danger px-6 py-8 rounded-2 me-7"
												style="display: flex; align-items: center; justify-content: space-between;">
												<div>
													<span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
															viewBox="0 0 24 24" fill="none">
															<path opacity="0.3"
																d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z"
																fill="black" />
															<path
																d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z"
																fill="black" />
														</svg>
													</span>
													<a href="#" class="text-danger fw-bold fs-6 mt-2">Today's Orders</a>
												</div>
												<h2 style="margin-right: 10px;">{{ $todayOrdersCount }}</h2>
											</div>
											<div class="col bg-light-success px-6 py-8 rounded-2"
												style="display: flex; align-items: center; justify-content: space-between;">
												<div>
													<span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
															viewBox="0 0 24 24" fill="none">
															<path
																d="M6 8.725C6 8.125 6.4 7.725 7 7.725H14L18 11.725V12.925L22 9.725L12.6 2.225C12.2 1.925 11.7 1.925 11.4 2.225L2 9.725L6 12.925V8.725Z"
																fill="black" />
															<path opacity="0.3"
																d="M22 9.72498V20.725C22 21.325 21.6 21.725 21 21.725H3C2.4 21.725 2 21.325 2 20.725V9.72498L11.4 17.225C11.8 17.525 12.3 17.525 12.6 17.225L22 9.72498ZM15 11.725H18L14 7.72498V10.725C14 11.325 14.4 11.725 15 11.725Z"
																fill="black" />
														</svg>
													</span>
													<a href="#" class="text-success fw-bold fs-6 mt-2">Feedback Orders</a>
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
									<h3 class="card-title fw-bolder text-dark">Team</h3>
									<div class="card-toolbar">
										<button type="button"
											class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
											data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
											<span class="svg-icon svg-icon-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
													viewBox="0 0 24 24">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="5" y="5" width="5" height="5" rx="1" fill="#000000" />
														<rect x="14" y="5" width="5" height="5" rx="1" fill="#000000"
															opacity="0.3" />
														<rect x="5" y="14" width="5" height="5" rx="1" fill="#000000"
															opacity="0.3" />
														<rect x="14" y="14" width="5" height="5" rx="1" fill="#000000"
															opacity="0.3" />
													</g>
												</svg>
											</span>
										</button>
										<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px"
											data-kt-menu="true">


											<div class="card card-xl-stretch mb-xl-8">
												<div class="card-header border-0">
													<h3 class="card-title fw-bolder text-dark" style="margin-top: 20px;">Role
													</h3>
													<div class="card-toolbar">
														<select id="roleSelector"
															class="form-select form-select-sm form-select-white">
															<option value="" selected disabled>Select a Role</option>
															<option value="1">Super Admin</option>
															<option value="3">Sub Admin</option>
															<option value="4">Marketing Team</option>
															<option value="5">Project Team</option>
															<option value="6">Writer TL</option>
															<option value="7">Sub Writer</option>
															<option value="8">Writer Admin</option>
															<!-- Add options for other roles as needed -->
														</select>
													</div>

												</div>

											</div>
										</div>
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
										<span class="text-muted fw-bold fs-7">More than {{$currentMonthOrdersCount}}+ new orders
											this month.</span>
									</h3>
									<!--begin::Toolbar-->
									<div class="card-toolbar" data-kt-buttons="true">
										<a class="btn btn-sm btn-color-muted btn-active btn-active-primary active px-4 me-1"
											id="kt_charts_widget_6_sales_btn">Sales</a>
										<a class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4 me-1"
											id="kt_charts_widget_6_expenses_btn">Expenses</a>
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
					<div class="row gy-5 g-xl-8">
						<div class="col-xl-12">
							<form id="dateForm" action="{{ route('dashboard') }}" method="GET">
								@csrf
								<div class="row mb-5">
									<div class="col-md-6">
										<label for="dateSelector" class="form-label">Select Month and Year:</label>
										<input type="month" id="dateSelector" name="selectedDate" class="form-control">
									</div>
								</div>
								<button type="submit" class="btn btn-primary">Submit/Reset</button>
							</form>

						</div>

						<div class="col-xl-6">

							<div id="piechart_3d-2" style="width: 100%; height: 400px;"></div>
						</div>
						<div class="col-xl-6">

							<div id="piechart_3d-3" style="width: 100%; height: 400px;"></div>
						</div>

					</div>

					<div class="row gy-5 g-xl-8 mt-2">
						<div class="col-xl-6">
							<div class="card card-xl-stretch mb-5">
								<div class="card-header border-0 pt-5 d-flex justify-content-between align-items-start">

									<!-- Left Side -->
									<h3 class="card-title align-items-start flex-column m-0">
										<span class="card-label fw-bolder fs-3 mb-1">
											Inquiry Tracking
										</span>
										<span class="text-muted fw-bold fs-7">
											User Growth vs Retention
										</span>
									</h3>

									<!-- Right Side Button -->
									{{-- <div class="card-toolbar">
										<a href="{{ route('analytics.report', 'inquiry') }}"
											class="btn btn-sm btn-light-primary">
											<i class="fa fa-eye"></i>
										</a>
									</div> --}}

								</div>

								<div class="card-body">
									<canvas id="userTrackingChart" style="height: 350px; width: 100%;"></canvas>
								</div>
							</div>
						</div>

						<div class="col-xl-6">
							<div class="card card-xl-stretch mb-5">
								<div class="card-header border-0 pt-5">
									<h3 class="card-title align-items-start flex-column">
										<span class="card-label fw-bolder fs-3 mb-1">Lead Tracking</span>
										<span class="text-muted fw-bold fs-7">Worked vs Converted Leads</span>
									</h3>
									<div class="card-toolbar gap-3">
										<!-- Eye Button Added Here -->
										<a href="/analytics-report/lead-tracking?from_date=2026-05-15&to_date={{ now()->format('Y-m-d') }}&name=&country="
											class="btn btn-sm btn-light-primary">
											<i class="fa fa-eye"></i>
										</a>

										<select id="employeeFilter"
											class="form-select form-select-solid form-select-sm w-125px">
											<option value="all">All Employees</option>
											@if(isset($employees))
												@foreach($employees->where('role_id', 4) as $emp)
													<option value="{{ $emp->id }}">{{ $emp->name }}</option>
												@endforeach
											@endif
										</select>

										<select id="dateRangeFilter"
											class="form-select form-select-solid form-select-sm w-125px">
											<option value="today">Today</option>
											<option value="yesterday">Yesterday</option>
											<option value="last_week">Last Week</option>
											<option value="last_month" selected>Last Month</option>
											<option value="custom">Custom</option>
										</select>
									</div>
								</div>

								<div id="customDateSection" class="px-8 mt-3 d-none">
									<div class="d-flex gap-2 align-items-center mb-3">
										<input type="date" id="startDate" class="form-control form-control-sm">
										<input type="date" id="endDate" class="form-control form-control-sm">
										<button id="applyCustomDate" class="btn btn-sm btn-primary">Go</button>
									</div>
								</div>

								<div class="card-body">
									<canvas id="leadTrackingChart" style="height: 350px; width: 100%;"></canvas>
								</div>
							</div>
						</div>
					</div>

					{{-- Due order list --}}
					<div class="card card-xl-stretch mb-5 shadow-sm">
						<div class="card-header border-0 pt-5">
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bolder fs-3 mb-1">Pending Payments</span>
								<span class="text-muted mt-1 fw-bold fs-7">Orders sorted by highest due balance</span>
							</h3>
						</div>
						<div class="card-body py-3">
							<div class="table-responsive">
								<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
									<thead>
										<tr class="fw-bolder text-muted bg-light">
											<th class="ps-4 rounded-start min-w-100px">Order ID</th>
											<th class="min-w-150px">Client</th>
											<th class="min-w-100px">Order Date</th>
											<th class="min-w-125px">Last Payment</th>
											<th>Total Amount</th>
											<th>Received</th>
											<th>Due Amount</th>
											<th class="text-end pe-4 rounded-end">Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach($dueOrdersList as $order)
											<tr>
												<td class="ps-4">
													<span class="text-dark fw-bolder">{{ $order->order_id }}</span>
												</td>
												<td>
													<div class="d-flex flex-column">
														@if(optional($order->user)->name)
															<span class="text-dark fw-bold">{{ $order->user->name }}</span>
														@else
															<div>
																<span class="badge badge-light-danger fs-7 fw-bold"
																	style="width: fit-content; display: inline-block;">User Was
																	Deleted</span>
															</div>
														@endif
														<span class="text-muted fs-7">{{ optional($order->user)->mobile_no }}</span>

													</div>
												</td>

												<td>
													<span class="text-dark fw-bold d-block fs-7">
														@php
															// ERROR FIX 1: Order date ko clean kiya
															$cleanOrderDate = $order->order_date ? trim(str_replace(' hursday', 'Thursday', $order->order_date)) : null;
														@endphp
														{{ $cleanOrderDate ? \Carbon\Carbon::parse($cleanOrderDate)->format('d M Y') : 'N/A' }}
													</span>
												</td>

												<td>
													@php
														$lastPayment = $order->payment->first();
													@endphp

													@if($lastPayment && $lastPayment->payment_date)
														<span class="text-dark fw-bold d-block fs-7">
															@php
																$cleanPaymentDate = trim($lastPayment->payment_date);

																$cleanPaymentDate = str_replace([
																	'onday',
																	'uesday',
																	'ednesday',
																	'hursday',
																	'riday',
																	'aturday',
																	'unday',
																	'monday',
																	'tuesday',
																	'wednesday',
																	'thursday',
																	'friday',
																	'saturday',
																	'sunday',
																], [
																	'Monday',
																	'Tuesday',
																	'Wednesday',
																	'Thursday',
																	'Friday',
																	'Saturday',
																	'Sunday',
																	'Monday',
																	'Tuesday',
																	'Wednesday',
																	'Thursday',
																	'Friday',
																	'Saturday',
																	'Sunday',
																], $cleanPaymentDate);

																try {
																	$formattedPaymentDate = \Carbon\Carbon::parse($cleanPaymentDate)->format('d M Y');
																} catch (\Exception $e) {
																	$formattedPaymentDate = '-';
																}
															@endphp

															{{ $formattedPaymentDate }}
														</span>

														<span class="text-muted fs-8">£{{ $lastPayment->paid_amount }}</span>
													@else
														<span class="badge badge-light-warning fs-8">No Payment Yet</span>
													@endif
												</td>

												<td>£{{ $order->amount }}</td>
												<td class="text-success">£{{ $order->received_amount }}</td>
												<td>
													<span class="badge badge-light-danger fw-bolder fs-6">
														£{{ (float) $order->amount - (float) $order->received_amount }}
													</span>
												</td>
												<td class="text-end pe-4">
													<a href="{{ route('orders.payment.form', $order->id) }}"
														class="btn btn-icon btn-success btn-sm position-relative"
														title="Update Payment">
														<i class="fa fa-money"></i>
													</a>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<div class="d-flex justify-content-center mt-4">
								{{ $dueOrdersList->appends(request()->query())->links() }}
							</div>
						</div>
					</div>

					<div class="row gy-5 g-xl-8 mt-2">
						<div class="col-xl-6">
							<div class="card card-flush shadow-sm mb-5">
								<div class="card-header pt-5">
									<h3 class="card-title align-items-start flex-column">
										<span class="card-label fw-bolder text-dark">Clients by Country</span>
										<span class="text-muted mt-1 fw-bold fs-7">Global User Distribution</span>
									</h3>
									<div class="card-toolbar">
										<a href="{{ route('analytics.report', 'country') }}"
											class="btn btn-sm btn-light-primary">
											<i class="fa fa-eye"></i>
										</a>
									</div>
								</div>
								<div class="card-body">
									<div class="d-flex flex-center" style="height: 300px;">
										<canvas id="countryChart"></canvas>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-6">
							<div class="card card-flush shadow-sm h-md-100 mb-5">

								<!-- CARD HEADER -->
								<div class="card-header pt-5 mb-3">

									<h3 class="card-title align-items-start flex-column">
										<span class="card-label fw-bolder text-dark fs-3">
											SEO Leads Overview
										</span>

										<span class="text-muted mt-1 fw-bold fs-7">
											Conversion status of frontend SEO leads
										</span>
									</h3>

									<div class="card-toolbar">

										<!-- VIEW BUTTON -->
										<a href="{{ route('analytics.report', 'seo') }}"
											class="btn btn-sm btn-light-primary me-3">
											<i class="fa fa-eye"></i>
										</a>

										<!-- FILTER FORM -->
										<form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center"
											id="seoFilterForm">

											<select name="seo_filter"
												class="form-select form-select-sm form-select-solid w-125px me-2"
												onchange="toggleCustomDate(this.value)">

												<option value="last_year" {{ $seoFilter == 'last_year' ? 'selected' : '' }}>
													Last 1 Year
												</option>

												<option value="last_month" {{ $seoFilter == 'last_month' ? 'selected' : '' }}>
													Last Month
												</option>

												<option value="last_week" {{ $seoFilter == 'last_week' ? 'selected' : '' }}>
													Last Week
												</option>

												<option value="custom" {{ $seoFilter == 'custom' ? 'selected' : '' }}>
													Custom
												</option>
											</select>

											<!-- CUSTOM DATE -->
											<div id="customDateWrap"
												class="d-flex align-items-center {{ $seoFilter == 'custom' ? '' : 'd-none' }}">

												<input type="date" name="seo_from" class="form-control form-control-sm me-1"
													value="{{ $seoFrom }}" style="width: 110px;">

												<span class="me-1">-</span>

												<input type="date" name="seo_to" class="form-control form-control-sm me-2"
													value="{{ $seoTo }}" style="width: 110px;">
											</div>

											<button type="submit" class="btn btn-sm btn-primary">
												Apply
											</button>

										</form>
									</div>
								</div>

								<!-- CARD BODY -->
								<div class="card-body py-3 d-flex flex-column align-items-center">

									<!-- CHART -->
									<div style="height: 230px; width: 230px; position: relative;">

										<canvas id="seoLeadsChart"></canvas>

										<div style="
							position: absolute;
							top: 50%;
							left: 50%;
							transform: translate(-50%, -50%);
							text-align: center;
						">
											<span class="fs-1 fw-bolder text-dark">
												{{ $totalSeoLeads }}
											</span>

											<br>

											<span class="fs-8 fw-bold text-muted">
												Total Leads
											</span>
										</div>
									</div>

									<!-- LEGENDS -->
									<div class="d-flex flex-wrap justify-content-center mt-6" style="gap: 12px; width: 100%;">

										@php
											$colors = [
												'Converted' => '#50CD89',
												'Not Converted' => '#F1416C',
											];
										@endphp

										@foreach($seoChartData as $label => $val)

												<div class="border border-dashed border-gray-300 rounded py-2 px-3 d-flex align-items-center"
													style="flex: 1 1 calc(50% - 12px); min-width: 130px;">

													<span class="bullet bullet-dot me-2" style="
												background-color: {{ $colors[$label] }} !important;
												height: 10px;
												width: 10px;
											">
													</span>

													<span class="fw-bolder text-gray-800 fs-7">
														{{ $label }}
													</span>

													<span class="text-gray-500 ms-auto fw-bold fs-8">
														({{ $val }})
													</span>

												</div>

										@endforeach

									</div>

								</div>
							</div>
						</div>
					</div>

					<div class="row gy-5 g-xl-8 mt-2">
						<!--begin::Col-->
						<div class="col-xl-6">
							<!--begin::Card-->
							<div class="card card-flush h-md-100">
								<!--begin::Card header-->
								<div class="card-header pt-5">
									<!--begin::Title-->
									<div class="card-title d-flex flex-column">
										<h3 class="fw-bolder text-dark">Lead Sources Overview</h3>
										<span class="text-muted fw-bold fs-7">Distribution of leads by source</span>
										{{-- <div class="card-toolbar">
											<a href="{{ route('analytics.report', 'sources') }}"
												class="btn btn-sm btn-light-primary">
												<i class="fa fa-eye"></i>
											</a>
										</div> --}}
									</div>
									<!--end::Title-->

									<!--begin::Toolbar-->
									<div class="card-toolbar d-flex align-items-center">

										<a href="{{ route('analytics.report', 'sources') }}"
											class="btn btn-sm btn-light-primary">
											<i class="fa fa-eye"></i>
										</a>
										<!-- Custom Date Inputs (Hidden by default) -->
										<div id="customDateInputs" class="d-none me-2 d-flex align-items-center">
											<input type="date"
												class="form-control form-control-sm form-control-solid w-125px me-2"
												id="source_from" name="source_from">
											<input type="date"
												class="form-control form-control-sm form-control-solid w-125px me-2"
												id="source_to" name="source_to">
										</div>

										<select id="leadSourceFilter"
											class="form-select form-select-solid form-select-sm w-125px me-2">
											<option value="1_year" selected>Last 1 Year</option>
											<option value="6_months">Last 6 Months</option>
											<option value="1_month">Last 1 Month</option>
											<option value="custom">Custom</option> <!-- Naya Custom Option -->
										</select>
										<button type="button" class="btn btn-sm btn-primary">Apply</button>
									</div>
									<!--end::Toolbar-->
								</div>
								<!--end::Card header-->

								<!--begin::Card body-->
								<div class="card-body pt-0 pb-5">
									<!--begin::Chart Wrapper-->
									<div class="position-relative d-flex flex-center mb-7">
										<!-- Canvas for Chart.js -->
										<canvas id="leadSourceChart" style="max-height: 250px; width: 100%;"></canvas>

										<!-- Absolute Center Text -->
										<div class="position-absolute text-center d-flex flex-column justify-content-center"
											style="top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;">
											<span class="fs-1 fw-bolder text-dark">{{ $totalSourceLeads }}</span>
											<span class="fs-7 fw-bold text-muted">Total Leads</span>
										</div>
									</div>
									<!--end::Chart Wrapper-->

									<!--begin::Custom Legend-->
									<div class="row g-3">
										<!-- Source 106 -->
										<div class="col-6 col-md-4">
											<div
												class="border border-dashed rounded px-3 py-2 d-flex align-items-center justify-content-between">
												<div class="d-flex align-items-center">
													<span class="bullet bullet-dot bg-success me-2 h-10px w-10px"></span>
													<span class="fw-bolder fs-6 text-dark">Source 106</span>
												</div>
												<span class="text-muted fw-bold">({{ $sourceData['Source 106'] ?? 0 }})</span>
											</div>
										</div>

										<!-- Source 1696 -->
										<div class="col-6 col-md-4">
											<div
												class="border border-dashed rounded px-3 py-2 d-flex align-items-center justify-content-between">
												<div class="d-flex align-items-center">
													<span class="bullet bullet-dot bg-primary me-2 h-10px w-10px"></span>
													<span class="fw-bolder fs-6 text-dark">Source 1696</span>
												</div>
												<span class="text-muted fw-bold">({{ $sourceData['Source 1696'] ?? 0 }})</span>
											</div>
										</div>

										<!-- Source insta -->
										<div class="col-6 col-md-4">
											<div
												class="border border-dashed rounded px-3 py-2 d-flex align-items-center justify-content-between">
												<div class="d-flex align-items-center">
													<span class="bullet bullet-dot bg-warning me-2 h-10px w-10px"></span>
													<span class="fw-bolder fs-6 text-dark">Source insta</span>
												</div>
												<span class="text-muted fw-bold">({{ $sourceData['Source insta'] ?? 0 }})</span>
											</div>
										</div>

										<!-- Source facebook -->
										<div class="col-6 col-md-4">
											<div
												class="border border-dashed rounded px-3 py-2 d-flex align-items-center justify-content-between">
												<div class="d-flex align-items-center">
													<span class="bullet bullet-dot bg-danger me-2 h-10px w-10px"></span>
													<span class="fw-bolder fs-6 text-dark">Source facebook</span>
												</div>
												<span
													class="text-muted fw-bold">({{ $sourceData['Source facebook'] ?? 0 }})</span>
											</div>
										</div>

										<!-- Source whatsapp -->
										<div class="col-6 col-md-4">
											<div
												class="border border-dashed rounded px-3 py-2 d-flex align-items-center justify-content-between">
												<div class="d-flex align-items-center">
													<span class="bullet bullet-dot bg-secondary me-2 h-10px w-10px"></span>
													<span class="fw-bolder fs-6 text-dark">Source whatsapp</span>
												</div>
												<span
													class="text-muted fw-bold">({{ $sourceData['Source whatsapp'] ?? 0 }})</span>
											</div>
										</div>
									</div>
									<!--end::Custom Legend-->
								</div>
								<!--end::Card body-->
							</div>
							<!--end::Card-->
						</div>
						<!--end::Col-->

						<div class="col-xl-6">
							<div class="card card-flush shadow-sm h-md-100 mb-5">
								<div class="card-header pt-5 mb-3">
									<h3 class="card-title align-items-start flex-column">
										<span class="card-label fw-bolder text-dark fs-3">Conversion Ratio</span>
										<span class="text-muted mt-1 fw-bold fs-7">Lead Created vs Converted</span>
									</h3>
									{{-- <div class="card-toolbar">
										<a href="{{ route('analytics.report', 'conversion') }}"
											class="btn btn-sm btn-light-primary">
											<i class="fa fa-eye"></i>
										</a>
									</div> --}}

									<div class="card-toolbar">
										{{-- <a href="{{ route('analytics.report', 'conversion') }}"
											class="btn btn-sm btn-light-primary">
											<i class="fa fa-eye"></i>
										</a> --}}
										<select id="conversionFilter"
											class="form-select form-select-sm form-select-solid w-150px">
											<option value="current_month" selected>Current Month</option>
											<option value="last_month">Last Month</option>
											<option value="last_week">Last Week</option>
											<option value="custom">Custom</option>
										</select>
									</div>
								</div>

								<div id="conversionCustomDateWrap" class="px-8 d-none">
									<div class="d-flex gap-2 align-items-center mb-3">
										<input type="date" id="conversionFrom" class="form-control form-control-sm">
										<input type="date" id="conversionTo" class="form-control form-control-sm">
										<button type="button" id="applyConversionDate"
											class="btn btn-sm btn-primary">Go</button>
									</div>
								</div>

								<div class="card-body py-3 d-flex flex-column align-items-center justify-content-center">

									<div style="height: 230px; width: 230px; position: relative;">
										<canvas id="conversionRatioChart"></canvas>

										<div
											style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
											<span class="fs-1 fw-bolder text-success"
												id="conversionRatioText">{{ $conversionRatio }}%</span><br>
											<span class="fs-8 fw-bold text-muted">Converted</span>
										</div>
									</div>

									<div class="d-flex flex-wrap justify-content-center mt-6" style="gap: 12px; width: 100%;">

										<div class="border border-dashed border-gray-300 rounded py-2 px-3 d-flex align-items-center"
											style="flex: 1 1 calc(50% - 12px); min-width: 140px;">
											<span class="bullet bullet-dot me-2"
												style="background-color: #50CD89 !important; height: 10px; width: 10px;"></span>
											<span class="fw-bolder text-gray-800 fs-7">Converted Leads</span>
											<span class="text-gray-500 ms-auto fw-bold fs-8"
												id="convertedLeadsText">({{ $convertedOrders1Year }})</span>
										</div>

										<div class="border border-dashed border-gray-300 rounded py-2 px-3 d-flex align-items-center"
											style="flex: 1 1 calc(50% - 12px); min-width: 140px;">
											<span class="bullet bullet-dot me-2"
												style="background-color: #E4E6EF !important; height: 10px; width: 10px;"></span>
											<span class="fw-bolder text-gray-800 fs-7">Not Converted</span>
											<span class="text-gray-500 ms-auto fw-bold fs-8"
												id="notConvertedLeadsText">({{ $notConvertedCount }})</span>
										</div>

									</div>

									<div class="mt-4 w-100 text-center">
										<span class="badge badge-light-primary fs-6 fw-bolder px-4 py-2">
											Total : <span
												id="totalLeadsText">{{ $convertedOrders1Year + $notConvertedCount}}</span>
										</span>
									</div>

								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

		<!-- ... (previous content) ... -->

		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

		<script>
			$(document).ready(function () {
				// ... aapka purana team member fetch karne ka logic ...
			});
		</script>

		<script>
			$(document).ready(function () {
				var canvas = document.getElementById('leadSourceChart');

				if (canvas) {
					var ctx = canvas.getContext('2d');

					// Controller se data fetch kar rahe hain
					var chartData = [
								{{ $sourceData['Source 106'] ?? 0 }},
								{{ $sourceData['Source 1696'] ?? 0 }},
								{{ $sourceData['Source insta'] ?? 0 }},
								{{ $sourceData['Source facebook'] ?? 0 }},
						{{ $sourceData['Source whatsapp'] ?? 0 }}
					];

					new Chart(ctx, {
						type: 'doughnut',
						data: {
							labels: ['Source 106', 'Source 1696', 'Source insta', 'Source facebook', 'Source whatsapp'],
							datasets: [{
								data: chartData,
								backgroundColor: [
									'#50CD89', // Success (Green) for 106
									'#009EF7', // Primary (Blue) for 1696
									'#FFC700', // Warning (Yellow) for insta
									'#F1416C', // Danger (Red) for facebook
									'#E4E6EF'  // Secondary (Gray) for whatsapp
								],
								borderWidth: 0,
								hoverOffset: 4
							}]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							cutout: '75%', // Center hole space banata hai
							plugins: {
								legend: {
									display: false // Default legend hide kar rahe hain kyunki custom HTML boxes banaye hain
								},
								tooltip: {
									enabled: true
								}
							}
						}
					});
				}
			});
			document.getElementById('leadSourceFilter').addEventListener('change', function () {
				var dateInputs = document.getElementById('customDateInputs');
				if (this.value === 'custom') {
					dateInputs.classList.remove('d-none'); // Show inputs
				} else {
					dateInputs.classList.add('d-none'); // Hide inputs
				}
			});
		</script>

		{{-- country wise user distribution ke liye chart --}}
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				const countryCanvas = document.getElementById('countryChart');

				if (!countryCanvas) {
					return;
				}

				const countryData = @json($countryStats ?? ['labels' => [], 'data' => []]);

				if (!countryData.labels.length || !countryData.data.length) {
					console.log('No country data found');
					return;
				}

				const baseColors = [
					'#0095E8', '#50CD89', '#F1416C', '#FFC700', '#7239EA',
					'#009EF7', '#181C32', '#3F4254', '#E4E6EF', '#47BE7D',
					'#FF9F43', '#EA5455', '#28C76F', '#7367F0'
				];

				const backgroundColors = countryData.data.map((_, index) => {
					return baseColors[index % baseColors.length];
				});

				new Chart(countryCanvas.getContext('2d'), {
					type: 'doughnut',
					data: {
						labels: countryData.labels,
						datasets: [{
							data: countryData.data,
							backgroundColor: backgroundColors,
							borderWidth: 2,
							borderColor: '#ffffff',
							hoverOffset: 10
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						cutout: '65%',
						plugins: {
							legend: {
								position: 'bottom',
								display: true,
								labels: {
									usePointStyle: true,
									padding: 10,
									font: {
										size: 12,
										family: 'Poppins'
									}
								}
							},
							tooltip: {
								callbacks: {
									label: function (context) {
										return context.label + ': ' + context.raw + ' Users';
									}
								}
							}
						}
					}
				});
			});
		</script>

		<script>
			// 1. Chart ko globally define kiya taaki baad me track ho sake
			let userTrackingChartInstance = null;

			// 2. DOM load hone ke baad hi chart render ho (Errors se bachne ke liye)
			document.addEventListener('DOMContentLoaded', function () {
				const canvasElem = document.getElementById('userTrackingChart');

				if (canvasElem) {
					const ctx = canvasElem.getContext('2d');

					// 3. OPTIMIZATION: Agar purana chart pehle se bana hai, toh use destroy karo (No Memory Leaks / No Flickering)
					if (userTrackingChartInstance) {
						userTrackingChartInstance.destroy();
					}

					// 4. OPTIMIZATION: JSON data ko pehle variables me store kar liya (Clean parsing)
					const chartLabels = {!! json_encode($chartData['labels'] ?? []) !!};
					const newUsersData = {!! json_encode($chartData['newUsers'] ?? []) !!};
					const retainedUsersData = {!! json_encode($chartData['retained'] ?? []) !!};
					const loyalUsersData = {!! json_encode($chartData['loyal'] ?? []) !!};

					userTrackingChartInstance = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: chartLabels,
							datasets: [
								{
									label: 'New Users',
									data: newUsersData,
									backgroundColor: '#4A69FF', // Blue
									borderRadius: 4,
									barThickness: 12,
								},
								{
									label: 'Retained Users',
									data: retainedUsersData,
									backgroundColor: '#E4E6EF', // Grey
									borderRadius: 4,
									barThickness: 12,
								},
								{
									label: 'Loyal Customers',
									data: loyalUsersData,
									backgroundColor: '#50CD89', // Green
									borderRadius: 4,
									barThickness: 12,
								}
							]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							// 5. OPTIMIZATION: Tooltip interaction ko smooth kiya
							interaction: {
								mode: 'index',
								intersect: false,
							},
							scales: {
								y: {
									beginAtZero: true,
									ticks: {
										callback: function (value) {
											return value >= 1000 ? (value / 1000) + 'K' : value;
										}
									}
								}
							}
						}
					});
				}
			});
		</script>

		<script>
			// SEO Custom Date Hide/Show function (Global scope for inline onchange)
			function toggleCustomDate(val) {
				const wrap = document.getElementById('customDateWrap');
				if (val === 'custom') {
					wrap.classList.remove('d-none');
				} else {
					wrap.classList.add('d-none');
				}
			}

			$(document).ready(function () {
				// ========================================================
				// 1. Existing Logic: Lead Tracking Bar Chart (Employee Data)
				// ========================================================
				let leadChart;
				let currentLeadData = null; // To store response for table view
				const ctxLeadElem = document.getElementById('leadTrackingChart');

				if (ctxLeadElem) {
					const ctxLead = ctxLeadElem.getContext('2d');

					function initLeadChart(labels, workedData, convertedData) {
						if (leadChart) leadChart.destroy();
						leadChart = new Chart(ctxLead, {
							type: 'bar',
							data: {
								labels: labels,
								datasets: [
									{ label: 'Leads Worked', data: workedData, backgroundColor: '#009EF7', borderRadius: 4 },
									{ label: 'Converted', data: convertedData, backgroundColor: '#50CD89', borderRadius: 4 }
								]
							},
							options: {
								responsive: true,
								maintainAspectRatio: false,
								scales: { y: { beginAtZero: true } }
							}
						});
					}

					function fetchLeadData() {
						const employee = $('#employeeFilter').val();
						const range = $('#dateRangeFilter').val();
						const start = $('#startDate').val();
						const end = $('#endDate').val();

						$.ajax({
							url: "{{ route('leads.tracking.data') }}",
							method: "GET",
							data: { employee_id: employee, range: range, start_date: start, end_date: end },
							success: function (res) {
								currentLeadData = res; // Save data for table use
								initLeadChart(res.labels, res.worked, res.converted);
							},
							error: function (err) {
								console.error("Graph Error:", err);
							}
						});
					}

					// --- New Function to Show Table ---
					$('#viewTableBtn').on('click', function () {
						if (!currentLeadData) return;

						let tableHtml = '';
						currentLeadData.labels.forEach((label, index) => {
							const worked = currentLeadData.worked[index];
							const converted = currentLeadData.converted[index];
							// Calculate conversion percentage safely
							const percentage = worked > 0 ? ((converted / worked) * 100).toFixed(1) : 0;

							tableHtml += `
										<tr>
											<td class="text-dark fw-bolder">${label}</td>
											<td class="text-center"><span class="badge badge-light-primary fs-7">${worked}</span></td>
											<td class="text-center"><span class="badge badge-light-success fs-7">${converted}</span></td>

										</tr>
									`;
						});

						if (tableHtml === '') tableHtml = '<tr><td colspan="4" class="text-center">No data available</td></tr>';

						$('#leadTableBody').html(tableHtml);
						$('#leadTableModal').modal('show');
					});

					// Listeners
					$('#employeeFilter, #dateRangeFilter').on('change', function () {
						if ($(this).val() === 'custom') {
							$('#customDateSection').removeClass('d-none');
						} else {
							$('#customDateSection').addClass('d-none');
							fetchLeadData();
						}
					});

					$('#applyCustomDate').on('click', fetchLeadData);

					// Initial Load
					fetchLeadData();
				}

				// ========================================================
				// 2. New Logic: SEO Frontend Leads Doughnut Chart
				// ========================================================
				const seoCtx = document.getElementById('seoLeadsChart');

				new Chart(seoCtx, {
					type: 'doughnut',

					data: {
						labels: {!! json_encode(array_keys($seoChartData)) !!},

						datasets: [{
							data: {!! json_encode(array_values($seoChartData)) !!},

							backgroundColor: [
								'#50CD89',
								'#F1416C'
							],

							borderWidth: 0
						}]
					},

					options: {
						responsive: true,
						cutout: '70%',

						plugins: {
							legend: {
								display: false
							}
						}
					}
				});

				// CUSTOM DATE TOGGLE
				function toggleCustomDate(value) {

					const wrap = document.getElementById('customDateWrap');

					if (value === 'custom') {
						wrap.classList.remove('d-none');
					} else {
						wrap.classList.add('d-none');
					}
				}

				// ========================================================
				// 3. New Logic: Conversion Ratio Doughnut Chart
				// ========================================================

			});
		</script>
		<script>
			$(document).ready(function () {
				let conversionChart = null;

				const convCtxElem = document.getElementById('conversionRatioChart');

				function renderConversionChart(converted, notConverted) {
					if (!convCtxElem) return;

					const convCtx = convCtxElem.getContext('2d');

					if (conversionChart) {
						conversionChart.destroy();
					}

					conversionChart = new Chart(convCtx, {
						type: 'doughnut',
						data: {
							labels: ['Converted Leads', 'Not Converted'],
							datasets: [{
								data: [converted, notConverted],
								backgroundColor: ['#50CD89', '#E4E6EF'],
								borderWidth: 0
							}]
						},
						options: {
							cutout: '80%',
							responsive: true,
							maintainAspectRatio: false,
							plugins: {
								legend: {
									display: false
								},
								tooltip: {
									callbacks: {
										label: function (context) {
											return ' ' + context.label + ': ' + context.raw;
										}
									}
								}
							}
						}
					});
				}

				function fetchConversionData() {
					const filter = $('#conversionFilter').val();
					const from = $('#conversionFrom').val();
					const to = $('#conversionTo').val();

					$.ajax({
						url: "{{ route('dashboard.conversion.ratio.data') }}",
						method: "GET",
						data: {
							filter: filter,
							from: from,
							to: to
						},
						success: function (res) {
							$('#conversionRatioText').text(res.conversion_ratio + '%');
							$('#convertedLeadsText').text('(' + res.converted_leads + ')');
							$('#notConvertedLeadsText').text('(' + res.not_converted_leads + ')');
							$('#totalLeadsText').text(res.total_leads);

							renderConversionChart(res.converted_leads, res.not_converted_leads);
						},
						error: function (err) {
							console.error('Conversion Ratio Error:', err);
						}
					});
				}

				$('#conversionFilter').on('change', function () {
					if ($(this).val() === 'custom') {
						$('#conversionCustomDateWrap').removeClass('d-none');
					} else {
						$('#conversionCustomDateWrap').addClass('d-none');
						fetchConversionData();
					}
				});

				$('#applyConversionDate').on('click', function () {
					fetchConversionData();
				});

				renderConversionChart({{ $convertedOrders1Year }}, {{ $notConvertedCount }});
			});
		</script>

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
					data: { role_id: roleId },
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
				$('#teamMembersContainer').html('');
				teamMembers.forEach(function (member) {
					var photoUrl = member.photo ? member.photo : 'assets/media/avatars/blank.png';
					var memberHtml = '<div class="d-flex align-items-center mb-7">' +
						'<div class="symbol symbol-50px me-5">' +
						'<img src="' + photoUrl + '" class="" alt="" />' +
						'</div>' +
						'<div class="flex-grow-1">' +
						'<a href="#" class="text-dark fw-bolder text-hover-primary fs-6">' + member.name + '</a>' +
						'</div>' +
						'</div>';
					$('#teamMembersContainer').append(memberHtml);
				});
			}
		</script>

		<!-- ... (remaining content) ... -->
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			google.charts.load("current", { packages: ["corechart"] });
			google.charts.setOnLoadCallback(drawChart);
			function drawChart() {
				var data = google.visualization.arrayToDataTable([
					['Task', 'Hours per Day'],
					['Other', <?php echo $otherOrder ?>],
					['Cancelled', <?php echo $cancelledOrder ?>],
					['Pending', <?php echo $pendingOrder ?>],
					['Hold Work', <?php echo $holdWorkOrder ?>],
					['In Progress', <?php echo $inprogressOrder ?>]
				]);

				var options = {
					title: 'Orders by Status',
					is3D: true,
				};

				var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
				chart.draw(data, options);
			}
		</script>
		<?php
			// Calculate total orders
			$totalOrders = 0;

				?>

		<script type="text/javascript">
			google.charts.load("current", { packages: ["corechart"] });
			google.charts.setOnLoadCallback(drawChart);
			function drawChart() {
				var totalOrders = 0; // Initialize total orders counter
				var data = google.visualization.arrayToDataTable([
					['Writer', 'Order Assign'],
					<?php foreach ($userByWid as $item) {
				if ($item['user'] !== 'Total Orders') { ?>
					['<?php echo $item['user']; ?>', <?php echo $item['orders']; ?>],
					<?php
					// Add orders to the total if the user is not "Total Orders"
					$totalOrders += $item['orders'];
				}
			} ?>
				]);

				var options = {
					title: 'Orders by month (Total: <?php echo $totalOrders; ?>)',
					is3D: true,
				};

				var chart = new google.visualization.PieChart(document.getElementById('piechart_3d-2'));
				chart.draw(data, options);
			}
		</script>
		<?php
			// Calculate total orders
			$totalOrders2 = 0;

				?>

		<script type="text/javascript">
			google.charts.load("current", { packages: ["corechart"] });
			google.charts.setOnLoadCallback(drawChart);
			function drawChart() {
				var totalOrders2 = 0; // Initialize total orders counter
				var data = google.visualization.arrayToDataTable([
					['Writer', 'Order Assign'],
					<?php foreach ($userByWid2 as $item) {
				if ($item['user'] !== 'Total Orders') { ?>
					['<?php echo $item['user']; ?>', <?php echo $item['orders']; ?>],
					<?php
					// Add orders to the total if the user is not "Total Orders"
					$totalOrders2 += $item['orders'];
				}
			} ?>
				]);

				var options = {
					title: 'Orders by month for team (Total: <?php echo $totalOrders2; ?>)',
					is3D: true,
				};

				var chart = new google.visualization.PieChart(document.getElementById('piechart_3d-3'));
				chart.draw(data, options);
			}
		</script>




	@else
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
									<h1>Coming Soon...</h1>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>




	@endif


@endsection