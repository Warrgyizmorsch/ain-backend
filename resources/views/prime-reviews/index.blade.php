@extends('layouts.app')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div id="kt_content_container" class="container-xxl">
            <div class="d-flex flex-stack mb-5">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Prime Review List
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Prime Assignment</small>
                </h1>
                <a href="{{ route('prime-reviews.create') }}" class="btn btn-sm btn-primary">New Prime Review</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card mb-5 mb-xl-8">
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bolder text-muted">
                                    <th class="min-w-120px">Name</th>
                                    <th class="min-w-120px">Page</th>
                                    <th class="min-w-150px">University / Course</th>
                                    <th class="min-w-120px">Service</th>
                                    <th class="min-w-100px">Location</th>
                                    <th class="min-w-100px">Rating</th>
                                    <th class="min-w-200px">Description</th>
                                    <th class="min-w-100px text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td class="text-dark fw-bolder fs-6">{{ $review->name }}</td>
                                        <td>{{ $review->page }}</td>
                                        <td>{{ collect([$review->university, $review->course])->filter()->implode(', ') }}</td>
                                        <td>{{ $review->services_type }}</td>
                                        <td>{{ $review->location }}</td>
                                        <td>{{ str_repeat('⭐', (int) $review->customer_rating) }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($review->description, 90) }}</td>
                                        <td class="text-end text-nowrap">
                                            <a href="{{ route('prime-reviews.edit', $review->id) }}"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="{{ route('prime-reviews.destroy', $review->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Delete this prime review?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-10">No prime reviews yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
