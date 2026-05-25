@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div id="kt_content_container" class="container-xxl">

		<!-- Toolbar with Title and Subtitle -->
		<div class="toolbar mb-5" id="kt_toolbar">
			<div class="container-fluid d-flex flex-stack" id="kt_toolbar_container">
				<div class="page-title d-flex align-items-center flex-wrap me-3">
					<h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Type Of Services</h1>
					<span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
					<small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In Need </small>
				</div>
			</div>
		</div>

		<!-- Form Section -->
		<div class="row g-5 g-xl-8">
			<div class="col-xl-4">
				<div class="card card-xxl-stretch mb-xl-8">
					<div class="card-header border-0 py-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1">Create</span>
							<span class="text-muted fw-bold fs-7">Create Category Leader</span>
						</h3>
					</div>
					<form action="{{ route('free-samples-type') }}" method="POST">
						@csrf
						<div class="card-body d-flex flex-column">
							<div class="row mb-3">
								<div class="col-lg-12">
									<div class="fv-row fv-plugins-icon-container mb-3">
										<label for="name" class="col-form-label required fw-bold fs-6">Category</label>
										<input   type="text" id="name" name="name"
											class="form-control form-control-lg form-control-solid"
											placeholder="Type" value="{{ old('name') }}">
										@if ($errors->has('name'))
											<div class="fv-plugins-message-container invalid-feedback">
												{{ $errors->first('name') }}
											</div>
										@endif
									</div>

									@if (session('success'))
										<div class="alert alert-success mt-3">
											{{ session('success') }}
										</div>
									@endif
									@if (session('error'))
										<div class="alert alert-danger mt-3">
											{{ session('error') }}
										</div>
									@endif
								</div>
							</div>
							<div class="card-toolbar">
								<button class="btn btn-primary" type="submit">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="col-xl-8">
				<div class="card card-xxl-stretch mb-5 mb-xl-8">
					<div class="card-header border-0 pt-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1">All Categories</span>
						</h3>
					</div>
					<div class="card-body py-3">
						<div class="table-responsive">
							<table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
								<thead>
									<tr class="fw-bolder text-muted">
										<th class="min-w-150px">Category</th>
										<th class="min-w-100px text-end">Actions</th>
									</tr>
								</thead>
								@foreach ($CategoryType as $obj)
									<tbody>
										<tr>
											<td>{{$obj->name}}</td>
											<td class="text-end">
												<button type="button" class="btn btn-primary" data-bs-toggle="modal"
													data-bs-target="#exampleModal{{ $obj->id }}">
													<li class="fa fa-edit"></li>
												</button>

												<div class="modal fade" id="exampleModal{{ $obj->id }}" tabindex="-1"
													aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<form action="{{ route('sample-type.update', $obj->id) }}"
																method="POST">
																@csrf
																@method('PUT')
																<div class="modal-header">
																	<h1 class="modal-title fs-5" id="exampleModalLabel">
																		Update {{ $obj->name }}</h1>
																	<button type="button" class="btn-close"
																		data-bs-dismiss="modal" aria-label="Close"></button>
																</div>
																<div class="modal-body">
																	<div class="mb-3">
																		<label for="name" class="form-label">Category
																			Name</label>
																		<input type="text" name="name" id="name"
																			value="{{ $obj->name }}" class="form-control"
																			required>
																	</div>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-secondary"
																		data-bs-dismiss="modal">Close</button>
																	<button type="submit" class="btn btn-primary">Save
																		Changes</button>
																</div>
															</form>
														</div>
													</div>
												</div>

												<!-- Delete Button -->
												<button class="btn btn-sm btn-danger" data-bs-toggle="modal"
													data-bs-target="#exampleModalasa{{ $obj->id }}">
													<li class="fa fa-trash"></li>
												</button>

												<!-- Delete Confirmation Modal -->
												<div class="modal fade" id="exampleModalasa{{ $obj->id }}" tabindex="-1"
													aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<form action="{{ route('sample-type.destroy', $obj->id) }}"
																method="POST">
																@csrf
																@method('DELETE')
																<div class="modal-header">
																	<h1 class="modal-title fs-5" id="exampleModalLabel">
																		Delete Category</h1>
																	<button type="button" class="btn-close"
																		data-bs-dismiss="modal" aria-label="Close"></button>
																</div>
																<div class="modal-body">
																	<p>Are you sure you want to delete the category
																		<strong>{{ $obj->name }}</strong>?</p>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-secondary"
																		data-bs-dismiss="modal">Close</button>
																	<button type="submit"
																		class="btn btn-danger">Delete</button>
																</div>
															</form>
														</div>
													</div>
												</div>

											</td>
										</tr>
									</tbody>
								@endforeach
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection