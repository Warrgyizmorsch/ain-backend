@extends('layouts.app')

@section('content')
<div class="container-xxl">
    

    <!-- Attribute Management Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="w-50">
            <input type="text" class="form-control form-control-solid" placeholder="Search by Tag Name" />
        </div>
        <div class="d-flex gap-3">
            <button class="btn btn-outline btn-outline-dashed btn-outline-dark">+ Add attribute</button>
            <button class="btn btn-dark">Save Attributes</button>
        </div>
    </div>

    <!-- Attribute Table -->
    
    <div class="card shadow-sm">
    <!-- Card Header -->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1">Manage Attributes</span>
            <span class="text-muted mt-1 fw-semibold fs-7">Add or update attribute tags for your workflow</span>
        </h3>
    </div>

    <!-- Card Body with Table -->
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-bordered table-rounded border gy-5 gs-5">
                <thead class="bg-light fw-bold text-muted">
                    <tr>
                        <th>Status</th>
                        <th>Name<span class="text-danger ms-1">*</span></th>
                        <th>Action (optional)</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody id="attribute-list">
                    <tr class="align-middle">
                        <!-- Toggle -->
                        <td>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" checked />
                            </label>
                        </td>

                        <!-- Attribute Name Input -->
                        <td>
                            <input type="text" class="form-control form-control-solid" placeholder="Enter attribute name" />
                        </td>

                        <!-- Action Input -->
                        <td>
                            <input type="text" class="form-control form-control-solid" placeholder="Enter action name" />
                        </td>

                        <!-- Trash Icon -->
                        <td class="text-end">
                            <a href="javascript:;" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="fas fa-trash-alt fs-5"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
@endsection
