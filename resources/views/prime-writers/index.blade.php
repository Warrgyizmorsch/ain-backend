@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl py-6">
        <div class="row g-5 g-xl-8">
            <div class="col-xl-4">
                <div class="card mb-xl-8">
                    <div class="card-header border-0 py-5">
                        <h3 class="card-title">Create Prime Writer</h3>
                    </div>
                    <form action="{{ route('prime-writers.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <label for="writerName" class="form-label required fw-bold">Writer Name</label>
                            <input
                                type="text"
                                id="writerName"
                                name="name"
                                class="form-control form-control-lg form-control-solid mb-4 @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                maxlength="255"
                                required
                            >
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            <label for="writerEmail" class="form-label required fw-bold">Writer Email</label>
                            <input
                                type="email"
                                id="writerEmail"
                                name="email"
                                class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                maxlength="255"
                                required
                            >
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary" type="submit">Create Writer</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title">Prime Writer Master</h3>
                    </div>
                    <div class="card-body py-3">
                        @include('layouts.flash')
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bolder text-muted">
                                        <th>Writer Name</th>
                                        <th>Writer Email</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($writers as $writer)
                                        <tr>
                                            <td>{{ $writer->name }}</td>
                                            <td>{{ $writer->email }}</td>
                                            <td>
                                                <span class="badge badge-light-{{ $writer->is_active ? 'success' : 'secondary' }}">
                                                    {{ $writer->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editPrimeWriter{{ $writer->id }}"
                                                    aria-label="Edit {{ $writer->name }}"
                                                >
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                @if ($writer->is_active)
                                                    <form
                                                        class="d-inline"
                                                        method="POST"
                                                        action="{{ route('prime-writers.destroy', $writer) }}"
                                                        onsubmit="return confirm('Deactivate this Prime writer?')"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" aria-label="Deactivate {{ $writer->name }}">
                                                            <i class="fa fa-ban"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-6">No Prime writers found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @foreach ($writers as $writer)
                            <div class="modal fade" id="editPrimeWriter{{ $writer->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('prime-writers.update', $writer) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h2 class="modal-title fs-5">Edit Prime Writer</h2>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <label for="writerName{{ $writer->id }}" class="form-label required">Writer Name</label>
                                                <input type="text" id="writerName{{ $writer->id }}" name="name" class="form-control mb-4" value="{{ $writer->name }}" maxlength="255" required>

                                                <label for="writerEmail{{ $writer->id }}" class="form-label required">Writer Email</label>
                                                <input type="email" id="writerEmail{{ $writer->id }}" name="email" class="form-control mb-4" value="{{ $writer->email }}" maxlength="255" required>

                                                <input type="hidden" name="is_active" value="0">
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked($writer->is_active)>
                                                    <span class="form-check-label fw-bold">Active</span>
                                                </label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
