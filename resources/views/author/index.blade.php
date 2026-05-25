@extends('layouts.app')

@section('content')

<div class="container-fluid py-5">

    <div class="row">

        <!-- ========================================= -->
        <!-- LEFT FORM -->
        <!-- ========================================= -->

        <div class="col-md-4">

            <div class="card shadow-sm border-0 rounded-4">

                <div class="card-header bg-white border-0 pt-4">

                    <h3 class="fw-bold text-primary">
                        Add New Author
                    </h3>

                </div>

                <div class="card-body">

                    @if(session('success'))

                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>

                    @endif

                    <form
                        action="{{ route('authors.store') }}"
                        method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <!-- IMAGE -->

                        <div class="text-center mb-4">

                            <img
                                id="previewImage"
                                src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                                class="rounded-circle shadow-sm"
                                width="150"
                                height="150"
                                style="object-fit:cover;cursor:pointer;">

                            <input
                                type="file"
                                name="photo"
                                id="photoInput"
                                class="d-none"
                                accept="image/*">

                            <div class="mt-3">

                                <label
                                    for="photoInput"
                                    class="fw-bold fs-5 cursor-pointer">

                                    CHOOSE PHOTO
                                </label>

                                <div class="text-muted mt-1">
                                    JPG, PNG (Max 2MB)
                                </div>

                            </div>

                        </div>

                        <!-- NAME -->

                        <div class="mb-4">

                            <label class="fw-bold mb-2">
                                FULL NAME
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control form-control-lg"
                                placeholder="Enter author name"
                                required>

                        </div>

                        <!-- DESCRIPTION -->

                        <div class="mb-4">

                            <label class="fw-bold mb-2">
                                SHORT BIO/DESCRIPTION
                            </label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="5"
                                placeholder="Brief about author..."></textarea>

                        </div>

                        <!-- BUTTON -->

                        <button
                            class="btn btn-primary w-100 py-3 fw-bold rounded-pill">

                            SAVE AUTHOR

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- ========================================= -->
        <!-- RIGHT TABLE -->
        <!-- ========================================= -->

        <div class="col-md-8">

            <div class="card shadow-sm border-0 rounded-4">

                <div class="card-header bg-white border-0 pt-4">

                    <h3 class="fw-bold">
                        Author Directory
                    </h3>

                </div>

                <div class="card-body p-0">

                    <table class="table align-middle">

                        <thead class="bg-light">

                            <tr>

                                <th class="ps-4">Author</th>

                                <th>Description</th>

                                <th width="120">Actions</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($authors as $author)

                                <tr>

                                    <!-- AUTHOR -->

                                    <td class="ps-4">

                                        <div class="d-flex align-items-center">

                                            <img
    src="{{ $author->photo ? url('/' . $author->photo) : asset('assets/media/avatars/blank.png') }}"
    class="rounded-circle me-3"
    width="50"
    height="50"
    style="object-fit:cover;">

                                            <div>

                                                <div class="fw-bold fs-5">
                                                    {{ $author->name }}
                                                </div>

                                                <div class="text-muted">
                                                    ID: #{{ $author->id }}
                                                </div>

                                            </div>

                                        </div>

                                    </td>

                                    <!-- DESCRIPTION -->

                                    <td style="max-width:350px;">

                                        {{ \Illuminate\Support\Str::limit($author->description, 80) }}

                                    </td>

                                    <!-- ACTIONS -->

                                    <td>

                                        <!-- EDIT -->

                                        <button
                                            class="btn btn-sm btn-light-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $author->id }}">

                                            <i class="fa fa-edit"></i>

                                        </button>

                                        <!-- DELETE -->

                                        <form
                                            action="{{ route('authors.delete', $author->id) }}"
                                            method="POST"
                                            class="d-inline-block">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                class="btn btn-sm btn-light-danger"
                                                onclick="return confirm('Delete Author?')">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                                <!-- ================================= -->
                                <!-- EDIT MODAL -->
                                <!-- ================================= -->

                                <div
                                    class="modal fade"
                                    id="editModal{{ $author->id }}"
                                    tabindex="-1">

                                    <div class="modal-dialog">

                                        <div class="modal-content">

                                            <form
                                                action="{{ route('authors.update', $author->id) }}"
                                                method="POST"
                                                enctype="multipart/form-data">

                                                @csrf

                                                <div class="modal-header">

                                                    <h5 class="modal-title">
                                                        Edit Author
                                                    </h5>

                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal">
                                                    </button>

                                                </div>

                                                <div class="modal-body">

                                                    <!-- PHOTO -->

                                                    <div class="mb-3 text-center">

                                                        <img
    src="{{ $author->photo ? url('/' . $author->photo) : asset('assets/media/avatars/blank.png') }}"
    class="rounded-circle mb-3"
    width="120"
    height="120"
    style="object-fit:cover;">

                                                        <input
                                                            type="file"
                                                            name="photo"
                                                            class="form-control">

                                                    </div>

                                                    <!-- NAME -->

                                                    <div class="mb-3">

                                                        <label class="fw-bold">
                                                            Name
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="name"
                                                            class="form-control"
                                                            value="{{ $author->name }}"
                                                            required>

                                                    </div>

                                                    <!-- DESCRIPTION -->

                                                    <div class="mb-3">

                                                        <label class="fw-bold">
                                                            Description
                                                        </label>

                                                        <textarea
                                                            name="description"
                                                            class="form-control"
                                                            rows="5">{{ $author->description }}</textarea>

                                                    </div>

                                                </div>

                                                <div class="modal-footer">

                                                    <button
                                                        type="submit"
                                                        class="btn btn-primary">

                                                        Update Author

                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <tr>

                                    <td colspan="3" class="text-center py-5">

                                        No Authors Found

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

document
    .getElementById('photoInput')
    .addEventListener('change', function(e) {

        let reader = new FileReader();

        reader.onload = function(event) {

            document
                .getElementById('previewImage')
                .src = event.target.result;
        };

        reader.readAsDataURL(e.target.files[0]);

    });

</script>

@endsection