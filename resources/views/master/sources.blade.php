@extends('layouts.app')

@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">

        {{-- TOOLBAR --}}
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                        Sources List
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In Need</small>
                    </h1>
                </div>
            </div>
        </div>

        <div class="row g-5 g-xl-8">

            {{-- CREATE FORM --}}
            <div class="col-xl-4">
                <div class="card card-xxl-stretch mb-xl-8">
                    <div class="card-header border-0 py-5">
                        <h3 class="card-title flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Create</span>
                            <span class="text-muted fw-bold fs-7">Source</span>
                        </h3>
                    </div>

                    <form action="{{ route('sources.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="fv-row mt-5">
                                <label class="required fw-bold fs-6">Source Name</label>
                                <input type="text" name="name" required
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="Source Name">
                            </div>

                            <div class="mt-5">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            {{-- TABLE --}}
            <div class="col-xl-8">
                <div class="card card-xxl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title fw-bolder fs-3">All Sources</h3>
                    </div>

                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle">
                                <thead>
                                    <tr class="fw-bolder text-muted">
                                        <th>Sr.No.</th>
                                        <th>Name</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php $counter = 1; @endphp

                                    @foreach($sources as $source)
                                    @if($source->is_delete == 0)

                                    <tr>
                                        <td>{{ $counter++ }}</td>
                                        <td>{{ $source->source_name }}</td>

                                        <td class="text-end">

                                            {{-- EDIT --}}
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#edit{{$source->id}}"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">

                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path>
                                                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path>
                                                    </svg>
                                                </span>
                                            </a>

                                            {{-- DELETE --}}
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#delete{{$source->id}}"
                                                class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">

                                                🗑️
                                            </a>

                                            {{-- EDIT MODAL --}}
                                            <div class="modal fade" id="edit{{$source->id}}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">

                                                        <form method="POST"
                                                            action="{{ route('sources.update', $source->id) }}">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="modal-header">
                                                                <h3>Edit Source</h3>
                                                            </div>

                                                            <div class="modal-body">
                                                                <input type="text" name="name"
                                                                    value="{{ $source->source_name }}"
                                                                    class="form-control form-control-solid">
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary">Update</button>
                                                            </div>

                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                            {{-- DELETE MODAL --}}
                                            <div class="modal fade" id="delete{{$source->id}}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">

                                                        {{-- HEADER --}}
                                                        <div class="modal-header">
                                                            <h2>Delete Source</h2>

                                                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                                <span class="svg-icon svg-icon-1">
                                                                    ✖
                                                                </span>
                                                            </div>
                                                        </div>

                                                        {{-- FORM --}}
                                                        <form method="POST" action="{{ route('sources.delete', $source->id) }}">
                                                            @csrf
                                                            @method('DELETE')

                                                            {{-- BODY --}}
                                                            <div class="modal-body">
                                                                Are You Sure To Delete Source
                                                                <h4 style="color:red">{{ $source->source_name }}</h4>
                                                            </div>

                                                            {{-- FOOTER --}}
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    Close
                                                                </button>

                                                                <button type="submit" class="btn btn-primary">
                                                                    Ok
                                                                </button>
                                                            </div>

                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>

                                    @endif
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