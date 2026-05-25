@extends('layouts.app')

@section('content')

<div class="container">
<div class="card  mb-5 mb-xxl-8">
  <div class="card-header align-items-center py-5 gap-2 gap-md-5">
    <!-- Search -->
    <div class="w-100 mw-250px">
      <div class="input-group">
        <span class="input-group-text">
          <i class="fas fa-search"></i>
        </span>
        <input type="text" class="form-control" placeholder="Search message by name" />
      </div>
    </div>

    <!-- Create Button -->
    <div class="d-flex justify-content-end">
      <a href="#" class="btn btn-primary">
        <i class="fas fa-plus"></i> Create
      </a>
    </div>
  </div>

  <!-- Table -->
  <div class="card-body pt-0">
    <div class="table-responsive">
      <table class="table align-middle table-row-dashed fs-6 gy-3">
        <thead>
          <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
            <th>Name</th>
            <th>Type</th>
            <th>Text</th>
            <th>Created By</th>
            <th>Action</th>
            <th>Favourite</th>
          </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
          <tr>
            <td colspan="6" class="text-center py-10 text-muted fs-6">
              No canned messages found!
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
@endsection