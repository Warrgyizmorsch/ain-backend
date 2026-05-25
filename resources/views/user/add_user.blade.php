@extends('layouts.app')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

		<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
			<div class="toolbar" id="kt_toolbar">
				<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
					<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
						<h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">New User</h1>
						<span class="h-20px border-gray-200 border-start mx-4"></span>
						<ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
							<li class="breadcrumb-item text-muted">
								<a href="{{route('dashboard')}}" class="text-muted text-hover-primary">Let's Learn Together</a>
							</li>
							<li class="breadcrumb-item">
								<span class="bullet bg-gray-200 w-5px h-2px"></span>
							</li>
							<li class="breadcrumb-item text-muted"></li>
							<li class="breadcrumb-item">
								<span class="bullet bg-gray-200 w-5px h-2px"></span>
							</li>
							<li class="breadcrumb-item text-dark"></li>
						</ul>
					</div>
				</div>
			</div>

			
			<div id="kt_content_container" class="container-xxl">
				<div class="row g-5 g-xl-8">
					<div class="card mb-5 mb-xl-10">
						<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
							<div class="card-title m-0">
								<h3 class="fw-bolder m-0">Profile Details</h3>
							</div>
						</div>
						<div id="kt_account_profile_details" class="collapse show">
							@include('user.section.new-user-form')
						</div>
					</div>
					
				</div>
			</div>
</div>

@endsection