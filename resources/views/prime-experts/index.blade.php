@extends('layouts.app')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div id="kt_content_container" class="container-xxl">
            <div class="d-flex flex-stack mb-5">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Prime Expert List
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Prime Assignment</small>
                </h1>
                <a href="{{ route('prime-experts.create') }}" class="btn btn-sm btn-primary">New Prime Expert</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card mb-5 mb-xl-8">
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bolder text-muted">
                                    <th class="min-w-80px">Image</th>
                                    <th class="min-w-140px">Name</th>
                                    <th class="min-w-140px">Image Alt</th>
                                    <th class="min-w-100px">Finish</th>
                                    <th class="min-w-100px">In-progress</th>
                                    <th class="min-w-140px">Subject</th>
                                    <th class="min-w-140px">Service</th>
                                    <th class="min-w-100px">Location</th>
                                    <th class="min-w-100px text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($experts as $expert)
                                    <tr>
                                        <td>
                                            <div class="symbol symbol-45px">
                                                <img src="{{ asset($expert->image ?: 'assets/media/avatars/blank.png') }}" alt="{{ $expert->image_alt }}">
                                            </div>
                                        </td>
                                        <td class="text-dark fw-bolder fs-6">{{ $expert->name }}</td>
                                        <td>{{ $expert->image_alt }}</td>
                                        <td>{{ $expert->finish_order }}</td>
                                        <td>{{ $expert->inprogress_order }}</td>
                                        <td>{{ $expert->subject }}</td>
                                        <td>{{ $expert->service }}</td>
                                        <td>{{ $expert->location }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('prime-experts.edit', $expert->id) }}"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="{{ route('prime-experts.destroy', $expert->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Delete this prime expert?');">
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
                                        <td colspan="9" class="text-center text-muted py-10">No prime experts yet.</td>
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
