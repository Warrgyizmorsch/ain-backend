@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">
        <div class="toolbar mb-5" id="kt_toolbar">
            <div class="container-fluid d-flex flex-stack" id="kt_toolbar_container">
                <div class="page-title d-flex align-items-center flex-wrap me-3">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ $pageTitle }}</h1>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Prime Assignment</small>
                </div>
            </div>
        </div>

        <div class="row g-5 g-xl-8">
            <div class="col-xl-4">
                <div class="card mb-xl-8">
                    <div class="card-header border-0 py-5">
                        <h3 class="card-title">{{ __('Create') }} {{ $itemLabel }}</h3>
                    </div>
                    <form action="{{ route($routePrefix . '.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <label for="name" class="form-label required fw-bold">{{ $itemLabel }} Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control form-control-lg form-control-solid @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="{{ $itemLabel }}"
                                maxlength="120"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary" type="submit">Create {{ $itemLabel }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title">All {{ $pageTitle }}</h3>
                    </div>
                    <div class="card-body py-3">
                        @include('layouts.flash')
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bolder text-muted">
                                        <th>{{ $itemLabel }}</th>
                                        <th class="text-center">Prime Samples</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td class="text-center">{{ $item->samples_count }}</td>
                                            <td class="text-end">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editItem{{ $item->id }}"
                                                    aria-label="Edit {{ $itemLabel }}"
                                                >
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteItem{{ $item->id }}"
                                                    aria-label="Delete {{ $itemLabel }}"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                                <div class="modal fade" id="editItem{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route($routePrefix . '.update', $item) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-header">
                                                                    <h2 class="modal-title fs-5">Update {{ $itemLabel }}</h2>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-start">
                                                                    <label for="name{{ $item->id }}" class="form-label">{{ $itemLabel }} Name</label>
                                                                    <input type="text" name="name" id="name{{ $item->id }}" value="{{ $item->name }}" class="form-control" maxlength="120" required>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="deleteItem{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route($routePrefix . '.destroy', $item) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="modal-header">
                                                                    <h2 class="modal-title fs-5">Delete {{ $itemLabel }}</h2>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-start">
                                                                    Delete <strong>{{ $item->name }}</strong>? Assigned Prime Samples will keep their other data, but this {{ strtolower($itemLabel) }} will be removed.
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-6">No {{ strtolower($pageTitle) }} found.</td>
                                        </tr>
                                    @endforelse
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
