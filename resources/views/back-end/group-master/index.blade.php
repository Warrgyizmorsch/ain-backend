@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header border-0 pt-6 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bolder mb-1">Group Master</h3>
            <span class="text-muted fs-7">
                Manage all groups here
            </span>
        </div>

        <button class="btn btn-light-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addGroupModal" style="border-radius:8px; padding:10px 18px;">
            <i class="fa fa-plus me-1"></i>Add Group
        </button>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Sr.</th>
                    <th>Group Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th width="150">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($groups as $index => $group)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->description ?? '-' }}</td>
                        <td>
                            @if($group->status == 1)
                                <span class="badge badge-light-success">Active</span>
                            @else
                                <span class="badge badge-light-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editGroupModal{{ $group->id }}">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteGroupModal{{ $group->id }}">Delete
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="editGroupModal{{ $group->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('group.master.update', $group->id) }}">
                                @csrf

                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Edit Group</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Group Name</label>
                                            <input type="text" name="name" class="form-control"
                                                   value="{{ $group->name }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control">{{ $group->description }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label>Status</label>
                                            <select name="status" class="form-select">
                                                <option value="1" {{ $group->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $group->status == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteGroupModal{{ $group->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered mw-400px">
                            <div class="modal-content">

                                <div class="modal-header border-0 pb-0">
                                    <h3 class="modal-title fw-bold text-danger">
                                        Delete Group
                                    </h3>

                                    <button type="button"
                                            class="btn btn-sm btn-icon"
                                            data-bs-dismiss="modal">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>

                                <div class="modal-body text-center py-10 px-10">

                                    <div class="mb-5">
                                        <i class="fa fa-trash text-danger"
                                        style="font-size:55px;"></i>
                                    </div>

                                    <h4 class="fw-bold mb-3">
                                        Are you sure?
                                    </h4>

                                    <div class="text-muted fs-6 mb-7">
                                        You want to delete
                                        <span class="fw-bold text-dark">
                                            {{ $group->group_name }}
                                        </span>
                                    </div>

                                    <form action="{{ route('group.master.delete', $group->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <div class="d-flex justify-content-center gap-3">

                                            <button type="button"
                                                    class="btn btn-light"
                                                    data-bs-dismiss="modal">
                                                Cancel
                                            </button>

                                            <button type="submit"
                                                    class="btn btn-danger">
                                                Yes, Delete
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addGroupModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('group.master.store') }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Group Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection