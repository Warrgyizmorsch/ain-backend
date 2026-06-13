@extends('layouts.app')

@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl">
	<div class="toolbar" id="kt_toolbar">
		<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
			<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
				<h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Coupon Codes Master
				<span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
				<small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In Need</small>
			</div>
			
		</div>
	</div>
	<div class="row g-5 g-xl-8">
		<div class="col-xl-4">
			<div class="card card-xxl-stretch mb-xl-8">
				<div class="card-header border-0 py-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bolder fs-3 mb-1">Create</span>
						<span class="text-muted fw-bold fs-7">Coupon Code</span>
					</h3>
    				</div>
					<form action="{{ route('store.coupon') }}" method="post">
                		@csrf
                    <div class="card-body d-flex flex-column">
                        <div class="row mb-12">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container mt-2">
                                        <label class="col-lg-12 col-form-label required fw-bold fs-6">Coupon Code</label>
                                        <input required type="text" name="coupon_code" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="e.g. SAVE20" value="">
                                    </div>
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container mt-4">
                                        <label class="col-lg-12 col-form-label required fw-bold fs-6">Discount Type</label>
                                        <select name="discount_type" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                            <option value="percentage">Percentage (%)</option>
                                            <option value="fixed">Fixed Amount (£)</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container mt-4">
                                        <label class="col-lg-12 col-form-label required fw-bold fs-6">Discount Value</label>
                                        <input required type="number" step="0.01" name="discount_value" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="e.g. 20" value="">
                                    </div>
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container mt-4">
                                        <label class="col-lg-12 col-form-label fw-bold fs-6">Is Active?</label>
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked="checked" />
                                            <label class="form-check-label">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-toolbar mt-6">
                                        <button class="btn btn-primary" type="submit"> Submit </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>   
                </div>
			</div>
		</div>
	<div class="col-xl-8">
		<div class="card card-xxl-stretch mb-5 mb-xl-8">
			<div class="card-header border-0 pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">All Coupon Codes</span>
				</h3>
			</div>
			<div class="card-body py-3">
				<div class="table-responsive">
					<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
						<thead>
							<tr class="fw-bolder text-muted">
								<th class="min-w-50px">Sr.No.</th>
								<th class="min-w-100px ">Coupon Code</th>
								<th class="min-w-100px ">Discount Type</th>
								<th class="min-w-100px ">Discount Value</th>
								<th class="min-w-100px ">Status</th>
								<th class="min-w-100px text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
                           @php $counter = 1; @endphp
                           @foreach($coupons as $coupon)
							<tr>
								<td>
									<div class="d-flex align-items-center">
										<div class="d-flex justify-content-start flex-column">
											<span class="text-dark fw-bolder fs-6">{{ $counter++ }}</span>
										</div>
									</div>
								</td>
								<td><span class="badge badge-light-primary fs-6 fw-bolder">{{ $coupon->coupon_code }}</span></td>
								<td>{{ ucfirst($coupon->discount_type) }}</td>
								<td>{{ $coupon->discount_type == 'percentage' ? rtrim(rtrim($coupon->discount_value, '0'), '.') . '%' : '£' . rtrim(rtrim($coupon->discount_value, '0'), '.') }}</td>
                                <td>
                                    @if($coupon->is_active)
                                        <span class="badge badge-light-success fs-7 fw-bold">Active</span>
                                    @else
                                        <span class="badge badge-light-danger fs-7 fw-bold">Inactive</span>
                                    @endif
                                </td>
								<td>
									<div class="d-flex justify-content-end flex-shrink-0">
										<!-- Edit Button -->
                                        <a href="#"  data-bs-toggle="modal" data-bs-target="#kt_modal_edit_coupon_{{$coupon->id}}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
											<span class="svg-icon svg-icon-3">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path>
													<path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path>
												</svg>
											</span>
										</a>

                                        <!-- Edit Modal -->
										<div class="modal fade" id="kt_modal_edit_coupon_{{$coupon->id}}" tabindex="-1" aria-hidden="true">
											<div class="modal-dialog modal-dialog-centered mw-600px">
												<div class="modal-content">
													<div class="modal-header">
														<h2>Edit Coupon Code</h2>
														<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
															<span class="svg-icon svg-icon-1">
																<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																	<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
																	<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
																</svg>
															</span>
														</div>
													</div>
													<form method="POST" action="{{ route('update.coupon', ['id' => $coupon->id]) }}">
														@csrf
														@method('PUT')
														<div class="modal-body text-start">
															<div class="row">
																<div class="col-lg-12 fv-row mt-2">
																	<label class="col-form-label required fw-bold fs-6">Coupon Code</label>
																	<input type="text" name="coupon_code" class="form-control form-control-lg form-control-solid mb-3" placeholder="e.g. SAVE20" value="{{ $coupon->coupon_code }}">
																</div>
                                                                <div class="col-lg-12 fv-row mt-2">
                                                                    <label class="col-form-label required fw-bold fs-6">Discount Type</label>
                                                                    <select name="discount_type" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                                                        <option value="percentage" {{ $coupon->discount_type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                                                        <option value="fixed" {{ $coupon->discount_type == 'fixed' ? 'selected' : '' }}>Fixed Amount (£)</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-12 fv-row mt-2">
                                                                    <label class="col-form-label required fw-bold fs-6">Discount Value</label>
                                                                    <input type="number" step="0.01" name="discount_value" class="form-control form-control-lg form-control-solid mb-3" placeholder="e.g. 20" value="{{ $coupon->discount_value }}">
                                                                </div>
                                                                <div class="col-lg-12 fv-row mt-2">
                                                                    <label class="col-form-label fw-bold fs-6">Is Active?</label>
                                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $coupon->is_active ? 'checked' : '' }} />
                                                                        <label class="form-check-label">Active</label>
                                                                    </div>
                                                                </div>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
															<button type="submit" class="btn btn-primary">Update</button>
														</div>
													</form>
												</div>
											</div>
										</div>

                                        <!-- Delete Button -->
										<a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_delete_coupon_{{$coupon->id}}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
											<span class="svg-icon svg-icon-3">
												<svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"></path>
													<path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"></path>
													<path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"></path>
												</svg>
											</span>
										</a>

                                        <!-- Delete Modal -->
										<div class="modal fade" id="kt_modal_delete_coupon_{{$coupon->id}}" tabindex="-1" aria-hidden="true">
											<div class="modal-dialog modal-dialog-centered mw-600px">
												<div class="modal-content">
													<div class="modal-header">
														<h2>Delete Coupon</h2>
														<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
															<span class="svg-icon svg-icon-1">
																<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																	<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
																	<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
																</svg>
															</span>
														</div>
													</div>
													<form action="{{ route('delete.coupon', ['id' => $coupon->id]) }}" method="POST">
                										@csrf
														@method('delete')
														<div class="modal-body text-start">
															Are You Sure To Delete Coupon Code 
                                                            <h4 style="color:red">{{ $coupon->coupon_code }}</h4>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
															<button type="submit" class="btn btn-primary">Yes, Delete</button>
														</div>
													</form>
												</div>
											</div>
										</div>

									</div>
								</td>
							</tr>
                            @endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
</div>
@endsection
