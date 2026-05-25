@extends('layouts.app') <!-- Or your Metronic layout -->

@section('content')
<div class="container-fluid">

    {{-- Search & Create Card --}}
    <div class="card mb-5">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="w-50">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control form-control-solid" placeholder="Search by name & category">
                </div>
            </div>
            <a href="#" class="btn btn-dark fw-bold">
                <i class="fas fa-plus me-2"></i> Create
            </a>
        </div>
    </div>


    {{-- Tags Table Card --}}
    <div class="card">
        <div class="card-header border-0">
            <h3 class="card-title fw-bold text-gray-800">Tags</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-row-bordered align-middle gs-0 gy-4">
                    <thead class="bg-light">
                        <tr class="fw-bold text-muted text-uppercase">
                            <th class="ps-5">Status</th>
                            <th>Tag Name</th>
                            <th>Category</th>
                            <th>Customer Journey</th>
                            <th>First Message</th>
                            <th>Created At</th>
                            <th class="pe-5">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="text-center py-10 text-muted fs-6">
                                No tags created yet!
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
