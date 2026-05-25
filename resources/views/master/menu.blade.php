@extends('layouts.app')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl">
	<div class="toolbar" id="kt_toolbar">
		<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
			<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
				<h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Menu
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
						<span class="text-muted fw-bold fs-7">Menus</span>
					</h3>
    				</div>
                <form action="{{ route('menus') }}" method="post">
                @csrf
                    <div class="card-body d-flex flex-column">
                        <div class="row mb-12">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Menu Name</label>
                                        <input required type="text" name="menu_name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Menu Name" value="">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Route Name</label>
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input required type="text" name="route" class="form-control form-control-lg form-control-solid" placeholder="route Name" value="">
                                        <div class="fv-plugins-message-container invalid-feedback">

                                        </div>
                                    </div>
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Icon Class</label>
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input required type="text" name="icon" class="form-control form-control-lg form-control-solid" placeholder="route Name" value="">
                                        <div class="fv-plugins-message-container invalid-feedback">
                                            
                                        </div>
                                    </div>
                                    <div class="row mb-6" data-select2-id="select2-data-239-d5v8">
                                       
									<label class="col-lg-4 col-form-label fw-bold fs-6">Sort Order</label>
									<div class="col-lg-12 fv-row fv-plugins-icon-container">
										<input type="number" name="sort_order" class="form-control form-control-lg form-control-solid" placeholder="Sort Order">
									</div>

                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                        <span class="required">Status</span>
                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Country of origination" aria-label="Country of origination"></i>
                                    </label>
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container" data-select2-id="select2-data-238-dwgq">
                                        <select required name="status"  data-control="select2"  class="form-select form-select-solid form-select-lg fw-bold select2-hidden-accessible" data-select2-id="select2-data-10-l5lsk" tabindex="-1" aria-hidden="true">
                                            <option value="" data-select2-id="select2-data-12-uzxa">none</option>
                                            <option  value="Y" >Active</option>
                                            <option  value="N" >Deactive</option>
                                        </select>
                                        <div class="fv-plugins-message-container invalid-feedback">

                                        </div>
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <button class="btn btn-primary" type=" submit"> Submit </button>
								</div>
                            </div>
                        </div>
                    </form>   
                </div>
			</div>
		</div>
	</div>
	<div class="col-xl-8">
		<div class="card card-xxl-stretch mb-5 mb-xl-8">
			<div class="card-header border-0 pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">All Menus</span>
				</h3>
			</div>
			<div class="card-body py-3">
				<div class="table-responsive">
					<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
						<thead>
							<tr class="fw-bolder text-muted">
								<th class="min-w-150px">Menu</th>
								<th class="min-w-140px">Routes</th>
								<th class="min-w-120px">Status</th>
								<th class="min-w-100px text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
                            @foreach($menus as $menu)
							<tr>
								<td>
									<div class="d-flex align-items-center">
										
										<div class="d-flex justify-content-start flex-column">
											<a href="#" class="text-dark fw-bolder text-hover-primary fs-6">{{ $menu->menu_name }}</a>
										</div>
									</div>
								</td>
								<td>
									<a href="#" class="text-dark fw-bolder text-hover-primary d-block fs-6">{{ $menu->routes }}</a>
									
								</td>
								<td class="text-end">
									<div class="d-flex flex-column w-100 me-2">

										<div class="d-flex flex-stack mb-2">
                                            @if($menu->show_menu == 'Y')
											<span class="text-muted me-2 fs-7 fw-bold  ">Active</span>
                                            @else
                                            <span class="text-muted me-2 fs-7 fw-bold  ">De Active</span>
                                            @endif
										</div>
										
									</div>
								</td>
								<td>
									<div class="d-flex justify-content-end flex-shrink-0">
										<a href="#"  data-bs-toggle="modal" data-bs-target="#kt_modal_create_appaa{{ $menu->id }} " id="kt_toolbar_primary_button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
											<span class="svg-icon svg-icon-3">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path>
													<path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path>
												</svg>
											</span>
										</a>
										<div class="modal fade" id="kt_modal_create_appaa{{ $menu->id }}" tabindex="-1" aria-hidden="true">
											<div class="modal-dialog modal-dialog-centered mw-900px">
												<div class="modal-content">
													<div class="modal-header">
														<h2>Edit Menu</h2>
														<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
															<span class="svg-icon svg-icon-1">
																<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																	<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
																	<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
																</svg>
															</span>
														</div>
													</div>
													<form id="deleteMenuForm"  method="POST" action="{{ route('update', $menu->id) }}">
														@csrf
														@method('PUT')
														<div class="modal-body">
															<div class="row mb-12">
                            									<div class="col-lg-12">
																<div class="col-lg-12 fv-row fv-plugins-icon-container">
																	<label class="col-lg-4 col-form-label required fw-bold fs-6">Menu Name</label>
																		<input type="text" name="menu_name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Menu Name" value="{{ $menu->menu_name }}">
																		<div class="fv-plugins-message-container invalid-feedback"></div>
																	</div>
																	<label class="col-lg-4 col-form-label required fw-bold fs-6">Route Name</label>
																	<div class="col-lg-12 fv-row fv-plugins-icon-container">
																		<input type="text" name="route" class="form-control form-control-lg form-control-solid" placeholder="route Name" value="{{ $menu->routes }}">
																		<div class="fv-plugins-message-container invalid-feedback">

																		</div>
																	</div>
																	<label class="col-lg-4 col-form-label required fw-bold fs-6">Icon Class</label>
																	<div class="col-lg-12 fv-row fv-plugins-icon-container">
																		<input type="text" name="icon" class="form-control form-control-lg form-control-solid" placeholder="route Name" value="{{ $menu->icon_class }}">
																		<div class="fv-plugins-message-container invalid-feedback">
																			
																		</div>
																	</div>
																	<div class="row mb-6" data-select2-id="select2-data-239-d5v8">
																		
																	<label class="col-lg-4 col-form-label fw-bold fs-6">Sort Order</label>
																	<div class="col-lg-12 fv-row fv-plugins-icon-container">
																		<input type="number" name="sort_order" value="{{ $menu->sort_order }}" class="form-control form-control-lg form-control-solid" placeholder="Sort Order">
																	</div>

																	<label class="col-lg-4 col-form-label fw-bold fs-6">
																		<span class="required">Status</span>
																		<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Country of origination" aria-label="Country of origination"></i>
																	</label>
																	<div class="col-lg-12 fv-row fv-plugins-icon-container" data-select2-id="select2-data-238-dwgqss">
																	<select name="status" data-control="select2" class="form-select form-select-solid form-select-lg fw-bold select2-hidden-accessible" data-select2-id="select2-data-10-l5lsk" tabindex="-1" aria-hidden="true">
																		<option value="" data-select2-id="select2-data-12-uzxaaa">none</option>
																		<option value="Y" @if($menu->show_menu == 'Y') selected @endif>Active</option>
																		<option value="N" @if($menu->show_menu == 'N') selected @endif>Deactive</option>
																	</select>

																		<div class="fv-plugins-message-container invalid-feedback">

																		</div>
																	</div>
                                    							</div>
																</div>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															<button type="submit" class="btn btn-primary">Edit</button>
														</div>
													</form>
												</div>
											</div>
										</div>
										<a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app{{ $menu->id }} " id="kt_toolbar_primary_button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
											<span class="svg-icon svg-icon-3">
												<svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"></path>
													<path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"></path>
													<path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"></path>
												</svg>
											</span>
										</a>

										<div class="modal fade" id="kt_modal_create_app{{ $menu->id }}" tabindex="-1" aria-hidden="true">
											<div class="modal-dialog modal-dialog-centered mw-900px">
												<div class="modal-content">
													<div class="modal-header">
														<h2>Delete Menu</h2>
														<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
															<span class="svg-icon svg-icon-1">
																<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																	<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
																	<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
																</svg>
															</span>
														</div>
													</div>
													<form id="deleteMenuForm" action="{{ route('menus.destroy', ['menu' => $menu->id]) }}" method="POST">
                										@csrf
														@method('DELETE')
														<div class="modal-body">
															Are You Sure To Delete Menu
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															<button type="submit" class="btn btn-primary">Ok</button>
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