@extends('layouts.app')

@section('content')
    <style>
        .shadow-sm {
            display: none;
        }

        .text-gray-700 {
            margin-top: revert;
        }

        @media (min-width: 992px) {
            .content {
                padding: 9px 0;
            }
        }
    </style>

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div id="kt_content_container" class="">
            {{-- TOP TOOLBAR --}}
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                        data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                            Career Applications
                            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                            <small class="text-muted fs-7 fw-bold my-1 ms-1">
                                Candidates applied from website
                            </small>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                {{-- FILTER CARD --}}
                <div class="card card-xxl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 ">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Filter</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="row mb-3">

                                {{-- Search (name / email / phone / role) --}}
                                <div class="col-md-3 fv-row">
                                    <input type="search" name="search" id="search" value="{{ request('search') }}"
                                        class="form-control form-control-solid"
                                        placeholder="Search by name, email, phone, role">
                                </div>

                                {{-- Application Type (job / internship) --}}
                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="role_mode" id="role_mode" data-control="select2"
                                        data-placeholder="Application Type"
                                        class="form-select form-select-solid form-select-lg">
                                        <option value=""></option>
                                        <option value="job" {{ request('role_mode') === 'job' ? 'selected' : '' }}>
                                            Job
                                        </option>
                                        <option value="internship" {{ request('role_mode') === 'internship' ? 'selected' : '' }}>
                                            Internship
                                        </option>
                                    </select>
                                </div>

                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="role" id="role" data-control="select2"
                                        data-placeholder="Filter by Role"
                                        class="form-select form-select-solid form-select-lg">

                                        <option value="">Filter by Role</option>

                                        <option value="React Developer" {{ request('role') == "React Developer" ? 'selected' : '' }}>React Developer</option>
                                        <option value="Next.js Developer" {{ request('role') == "Next.js Developer" ? 'selected' : '' }}>Next.js Developer</option>
                                        <option value="Laravel Developer" {{ request('role') == "Laravel Developer" ? 'selected' : '' }}>Laravel Developer</option>
                                        <option value="Full Stack Developer" {{ request('role') == "Full Stack Developer" ? 'selected' : '' }}>Full Stack Developer</option>
                                        <option value="UI/UX Designer" {{ request('role') == "UI/UX Designer" ? 'selected' : '' }}>UI/UX Designer</option>
                                        <option value="Graphic Designer" {{ request('role') == "Graphic Designer" ? 'selected' : '' }}>Graphic Designer</option>
                                        <option value="Video Editor" {{ request('role') == "Video Editor" ? 'selected' : '' }}>Video Editor</option>
                                        <option value="Digital Marketing" {{ request('role') == "Digital Marketing" ? 'selected' : '' }}>Digital Marketing</option>
                                        <option value="Content Writer" {{ request('role') == "Content Writer" ? 'selected' : '' }}>Content Writer</option>
                                        <option value="Business Development" {{ request('role') == "Business Development" ? 'selected' : '' }}>Business Development</option>
                                        <option value="HR Executive" {{ request('role') == "HR Executive" ? 'selected' : '' }}>HR Executive</option>
                                        <option value="Intern" {{ request('role') == "Intern" ? 'selected' : '' }}>Intern</option>

                                    </select>
                                </div>


                                <!-- {{-- Status (optional – agar column hai) --}}
                                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                    <select name="status" id="status" data-control="select2" data-placeholder="Status"
                                        class="form-select form-select-solid form-select-lg">
                                        <option value=""></option>
                                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                                        <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>In
                                            Review</option>
                                        <option value="shortlisted" {{ request('status') === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                            Rejected</option>
                                        <option value="hired" {{ request('status') === 'hired' ? 'selected' : '' }}>Hired
                                        </option>
                                    </select>
                                </div> -->

                                {{-- From Date --}}
                                <div class="col-md-3 fv-row">
                                    <input type="date" name="fromDate" id="fromDate" value="{{ request('fromDate') }}"
                                        class="form-control form-control-solid" placeholder="From date">
                                </div>

                                {{-- To Date --}}
                                <div class="col-md-3 fv-row">
                                    <input type="date" name="toDate" id="toDate" value="{{ request('toDate') }}"
                                        class="form-control form-control-solid" placeholder="To date">
                                </div>

                                {{-- Buttons --}}
                                <div class="col-md-3 fv-row fv-plugins-icon-container mt-2">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Search
                                    </button>
                                    <a id="resetFiltersBtn" class="btn btn-sm btn-danger" href="{{ url()->current() }}">
                                        Reset
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- LIST CARD --}}
                <div class="card card-xl-stretch mb-xl-">
                 	<div class="card-body py-3">
						<div class="table-responsive">
							 <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead class="p-2">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-20px text-center">SR</th>
                                    <th class="min-w-150px">Candidate</th>
                                    <th class="min-w-150px">Contact</th>
                                    <th class="min-w-120px">Role</th>
                                    <th class="min-w-150px">CTC / Notice</th>
                                    <th class="min-w-100px">Source</th>
                                    <th class="min-w-130px">Applied On</th>
                                    <!-- <th class="min-w-100px text-center">Status</th> -->
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($applications as $index => $app)
                                    <tr>
                                        {{-- SR with pagination offset --}}
                                        <td class="text-center">
                                            {{ ($applications->currentPage() - 1) * $applications->perPage() + $index + 1 }}
                                        </td>

                                        {{-- Candidate --}}
                                        <td>
                                            <div class="fw-bolder text-dark">
                                                {{ $app->name ?? '-' }}
                                            </div>
                                            <div class="text-muted fs-7">
                                                @if($app->role_mode === 'internship')
                                                    <span class="badge badge-light-info fs-8 fw-bold">
                                                        Internship
                                                    </span>
                                                @else
                                                    <span class="badge badge-light-primary fs-8 fw-bold">
                                                        Job
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Contact --}}
                                        <td>
                                            <div class="text-muted fs-7">
                                                <i class="bi bi-envelope me-1"></i>
                                                {{ $app->email ?? '-' }}
                                            </div>
                                            <div class="text-muted fs-7">
                                                <i class="bi bi-telephone me-1"></i>
                                                {{ $app->phone ?? '-' }}
                                            </div>
                                        </td>

                                        {{-- Role --}}
                                        <td>
                                            <div class="fw-semibold">
                                                {{ $app->role_title ?? '-' }}
                                            </div>
                                            @if(!empty($app->current_location))
                                                <div class="text-muted fs-8">
                                                    <i class="bi bi-geo-alt fs-8 me-1"></i>
                                                    {{ $app->current_location }}
                                                </div>
                                            @endif
                                        </td>

                                        {{-- CTC / Notice --}}
                                        <td>
                                            <div class="text-muted fs-7">
                                                <span class="fw-bold">Current:</span>
                                                {{ $app->current_ctc ?? '-' }}
                                            </div>
                                            <div class="text-muted fs-7">
                                                <span class="fw-bold">Expected:</span>
                                                {{ $app->expected_ctc ?? '-' }}
                                            </div>
                                            <div class="text-muted fs-7">
                                                <span class="fw-bold">Notice:</span>
                                                {{ $app->notice_period ?? '-' }}
                                            </div>
                                        </td>

                                        {{-- Source --}}
                                        <td>
                                            <span class="badge badge-light fs-8 fw-bold">
                                                {{ $app->source ?? 'Website' }}
                                            </span>
                                        </td>

                                        {{-- Applied On --}}
                                        <td>
                                            <div class="text-muted fs-7">
                                                {{ optional($app->created_at)->format('d M Y') }}
                                            </div>
                                            <div class="text-muted fs-8">
                                                {{ optional($app->created_at)->format('H:i A') }}
                                            </div>
                                        </td>

                                        {{-- Status --}}
                                        <!-- <td class="text-center">
                                            @php
                                                $status = $app->status ?? 'new';
                                                $badgeClass = match($status) {
                                                    'new' => 'badge-light-secondary',
                                                    'in_review' => 'badge-light-warning',
                                                    'shortlisted' => 'badge-light-info',
                                                    'rejected' => 'badge-light-danger',
                                                    'hired' => 'badge-light-success',
                                                    default => 'badge-light'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} fs-8 fw-bold">
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </span>
                                        </td> -->
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <span class="text-muted fw-bold">
                                                No applications found.
                                            </span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $applications->withQueryString()->links() }}
                        </div>
						</div>
					</div>
                </div>

            </div> {{-- col-xl-12 --}}
        </div> {{-- kt_content_container --}}
    </div> {{-- kt_content --}}

@endsection