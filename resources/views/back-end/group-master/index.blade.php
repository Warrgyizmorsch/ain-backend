@extends('layouts.app')
@section('content')
<style>
.gm-card{border:1px solid #e8eaf0;border-radius:14px;transition:.2s;background:#fff;height:100%}.gm-card:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(0,0,0,.07)}.gm-users{max-height:330px;overflow:auto}.gm-user{border-top:1px solid #f0f1f5;padding:12px 0}.gm-avatar{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#eef6ff;color:#1683e8;font-weight:700;flex:0 0 auto}
</style>
<div class="container-xxl">
    <div class="card mb-6"><div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-4">
        <div><h2 class="fw-bolder mb-1">Group Master</h2><div class="text-muted">Search groups, manage users and view their orders.</div></div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal"><i class="fa fa-plus me-1"></i>Add Group</button>
    </div></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <div class="card mb-6"><div class="card-body"><form method="GET" class="d-flex gap-3"><div class="position-relative flex-grow-1"><i class="fa fa-search position-absolute text-muted" style="left:16px;top:15px"></i><input name="search" value="{{ request('search') }}" class="form-control ps-12" placeholder="Search group by name..."></div><button class="btn btn-primary">Search</button>@if(request('search'))<a href="{{ route('group.master.index') }}" class="btn btn-light-danger">Clear</a>@endif</form></div></div>
    <div class="row g-5">
        @forelse($groups as $group)
        <div class="col-xl-4 col-md-6"><div class="gm-card p-5">
            <div class="d-flex justify-content-between align-items-start mb-3"><div><div class="d-flex align-items-center gap-2"><h4 class="fw-bolder mb-0">{{ $group->name }}</h4><span class="badge {{ $group->status ? 'badge-light-success' : 'badge-light-danger' }}">{{ $group->status ? 'Active' : 'Inactive' }}</span></div><div class="text-muted fs-7 mt-2">{{ $group->description ?: 'No description added' }}</div></div><div class="dropdown"><button class="btn btn-sm btn-icon btn-light" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button><div class="dropdown-menu dropdown-menu-end"><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editGroup{{ $group->id }}"><i class="fa fa-edit me-2 text-warning"></i>Edit</button><button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteGroup{{ $group->id }}"><i class="fa fa-trash me-2"></i>Delete</button></div></div></div>
            <div class="d-flex justify-content-between bg-light-primary rounded p-3 mb-3"><span class="fw-bold text-primary"><i class="fa fa-users me-2"></i>Users</span><span class="badge badge-primary">{{ $group->users_count }}</span></div>
            <div class="gm-users">
                @forelse($group->users as $user)
                <div class="gm-user d-flex align-items-center gap-3"><div class="gm-avatar">{{ strtoupper(substr($user->name ?: 'U',0,1)) }}</div><div class="flex-grow-1 overflow-hidden"><div class="fw-bold text-truncate">{{ $user->name ?: 'Unnamed User' }}</div><div class="text-muted fs-8 text-truncate">{{ $user->email ?: $user->mobile_no }}</div></div><div class="text-end"><span class="badge badge-light-info mb-1">{{ $user->orders_count }} Orders</span><br><a href="{{ route('orders.index',['uid'=>$user->id]) }}" class="fs-8 fw-bold">View Orders</a></div></div>
                @empty<div class="text-center text-muted py-8"><i class="fa fa-user-slash fs-2 mb-2"></i><div>No users in this group</div></div>@endforelse
            </div>
        </div></div>
        <div class="modal fade" id="editGroup{{ $group->id }}"><div class="modal-dialog modal-dialog-centered"><form class="modal-content" method="POST" action="{{ route('group.master.update',$group->id) }}">@csrf<div class="modal-header"><h5>Edit Group</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">@include('back-end.group-master.form',['editingGroup'=>$group])</div><div class="modal-footer"><button class="btn btn-primary">Update Group</button></div></form></div></div>
        <div class="modal fade" id="deleteGroup{{ $group->id }}"><div class="modal-dialog modal-dialog-centered modal-sm"><form class="modal-content" method="POST" action="{{ route('group.master.delete',$group->id) }}">@csrf @method('DELETE')<div class="modal-body text-center p-8"><i class="fa fa-trash text-danger fs-2x mb-4"></i><h4>Delete {{ $group->name }}?</h4><p class="text-muted">Users will not be deleted.</p><button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button><button class="btn btn-danger">Delete</button></div></form></div></div>
        @empty<div class="col-12"><div class="card"><div class="card-body text-center py-15 text-muted"><i class="fa fa-search fs-2x mb-4"></i><h4>No groups found</h4></div></div></div>@endforelse
    </div><div class="mt-6">{{ $groups->links() }}</div>
</div>
<div class="modal fade" id="addGroupModal"><div class="modal-dialog modal-dialog-centered"><form class="modal-content" method="POST" action="{{ route('group.master.store') }}">@csrf<div class="modal-header"><h5>Add Group</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">@include('back-end.group-master.form',['editingGroup'=>null])</div><div class="modal-footer"><button class="btn btn-primary">Create Group</button></div></form></div></div>
@endsection
