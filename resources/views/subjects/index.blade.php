@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl py-6">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Prefix</h3></div>
        <div class="card-body">
            @include('layouts.flash')
            <form method="POST" action="{{ route('subjects.store') }}" class="row g-3 mb-8">
                @csrf
                <div class="col-md-9"><label class="form-label required">Prefix name</label><input class="form-control" name="name" value="{{ old('name') }}" required></div>
                <div class="col-md-3 d-flex align-items-end"><button class="btn btn-primary w-100">Add Prefix</button></div>
            </form>
            <div class="table-responsive"><table class="table table-row-bordered align-middle">
                <thead><tr><th>Name</th><th>Slug</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse($subjects as $subject)
                    <tr><form method="POST" action="{{ route('subjects.update', $subject) }}">@csrf @method('PUT')
                        <td><input class="form-control" name="name" value="{{ $subject->name }}" required></td>
                        <td><code>{{ $subject->slug }}</code></td>
                        <td><label class="form-check form-switch"><input type="hidden" name="is_active" value="0"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked($subject->is_active)><span class="form-check-label">Active</span></label></td>
                        <td class="text-end"><button class="btn btn-sm btn-light-primary">Save</button></form>
                            <form class="d-inline" method="POST" action="{{ route('subjects.destroy', $subject) }}" onsubmit="return confirm('Delete this prefix?')">@csrf @method('DELETE')<button class="btn btn-sm btn-light-danger">Delete</button></form>
                        </td>
                    </tr>
                @empty <tr><td colspan="4" class="text-center text-muted">No prefixes added yet.</td></tr> @endforelse
                </tbody>
            </table></div>
        </div>
    </div>
</div>
</div>
@endsection
