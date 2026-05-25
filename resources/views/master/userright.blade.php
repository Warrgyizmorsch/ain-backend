@extends('layouts.app')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl">

	<div class="toolbar" id="kt_toolbar">
		<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
			<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
				<h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">User Right
				<span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
				<small class="text-muted fs-7 fw-bold my-1 ms-1">Let's Learn Together</small>
			</div>
			
		</div>
	</div>
	<form action="{{ route('userright') }}" method="post">
    @csrf
	<div class="row g-5 g-xl-8">
		<div class="col-xl-4">
			<div class="card card-xxl-stretch mb-xl-8">
				<div class="card-header border-0 py-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bolder fs-3 mb-1">Create</span>
						<span class="text-muted fw-bold fs-7">User Right</span>
					</h3>
    				</div>
               
                    <div class="card-body d-flex flex-column">
                        <div class="row mb-12">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="row mb-6" data-select2-id="select2-data-239-d5v8">
                                        <label class="col-lg-4 col-form-label fw-bold fs-6">
                                            <span class="">Role</span>
											
                                        </label>
                                        <div  class="col-lg-12 fv-row fv-plugins-icon-container" data-select2-id="select2-data-238-dwgq">
                                            <select id="role_id" name="role_id"  data-control="select2"  class="form-select form-select-solid form-select-lg fw-bold select2-hidden-accessible" data-select2-id="select2-data-10-l5lk" tabindex="-1" aria-hidden="true">
                                                <option value="" data-select2-id="select2-data-12-uzxa">none</option>
                                                @foreach($roles as $role)
           									  	  <option value="{{ $role['id'] }}">{{ $role['role']}}</option>
           										@endforeach
                                            </select>
                                        <div class="fv-plugins-message-container invalid-feedback">
										<div class="card-toolbar">
											<button class="btn btn-primary" type=" submit"> Submit </button>
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
								<th class="min-w-50px">Permission</th>
								<th class="min-w-140px">Menu</th>
								
							</tr>
						</thead>
						<tbody>
						
						
						@foreach ($menus as $menu)
						
							<tr>
								<td>
									<div class="form-check form-check-sm form-check-custom form-check-solid">
										<input class="form-check-input menu-checkbox" name="menu_id[]" type="checkbox" value="{{$menu['id']}}" data-kt-check="true" data-kt-check-target=".widget-9-check">
									</div>
								</td>
								<td>
									@if (count($menu->submenus) > 0)
										<!-- Display as accordion menu with submenus -->
										<div class="menu-item menu-accordion" data-toggle="collapse" data-target="#submenu{{$menu->id}}">
											<span class="menu-link">
												<span class="menu-icon">
													<li class="{{ $menu['icon_class'] }}"></li>
												</span>
												<span class="menu-title">{{ $menu['menu_name'] }}</span>
												<span class="menu-arrow"></span>
											</span>
										</div>
										<div id="submenu{{$menu->id}}" class="collapse" >
											@foreach ($menu->submenus as $submenu)
											
											<div class="menu-item">
												<div style="display: inline-block; margin: 10px 5px;" class="form-check form-check-sm form-check-custom form-check-solid">
													<input name="submenu_id[]" class="form-check-input submenu-checkbox" type="checkbox" value="{{$submenu->id}}" data-kt-check="true" data-kt-check-target=".widget-9-check">
												</div>
												<span>{{ $submenu->sub_menu_name }}</span>
											</div>
												
											@endforeach
										</div>
									@else
										<!-- Display as a regular menu item -->
										<div class="menu-item">
											<a class="menu-link active" href="{{$menu['routes']}}">
												<span class="menu-icon">
													<li class="{{ $menu['icon_class'] }}"></li>
												</span>
												<span class="menu-title">{{ $menu['menu_name'] }}</span>
											</a>
										</div>
									@endif
								</td>

								
								
							</tr>
							
						@endforeach
						
						</form>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	</form>   
	</div>
</div>
</div>

<!-- Include Bootstrap CSS and JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {

        // Handle the change event of the role select input
        $('#role_id').change(function () {
            var selectedRoleId = $(this).val();
            // Make an AJAX request to fetch the menus for the selected role
            $.ajax({
                type: 'GET',
                url: '{{route("rolePermission")}}', // Replace with your actual AJAX endpoint
                data: { role_id: selectedRoleId },
                success: function (response) {
                    // Uncheck all checkboxes first
                    $('.menu-checkbox').prop('checked', false);
                    $('.submenu-checkbox').prop('checked', false);

                    // Check the corresponding checkboxes if permissions exist
                    if (response.menuid.length > 0) {
                        $.each(response.menuid, function (index, id) {
                            $('.menu-checkbox[value="' + id + '"]').prop('checked', true);
                        });
                    }

                    if (response.submenuid.length > 0) {
                        $.each(response.submenuid, function (index, id) {
                            $('.submenu-checkbox[value="' + id + '"]').prop('checked', true);
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                    // Uncheck all checkboxes in case of an error
                    $('.menu-checkbox').prop('checked', false);
                    $('.submenu-checkbox').prop('checked', false);
                    // You can log additional information from the response if needed
                    console.log('Response:', xhr.responseText);
                }
            });
        });
    });
</script>


@endsection