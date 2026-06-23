@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl py-6">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Dynamic Service Pages</h3><div class="card-toolbar"><a href="{{ route('service-pages.create') }}" class="btn btn-primary">Add Dynamic Page</a></div></div>
        <div class="card-body">
            @include('layouts.flash')
            <div class="table-responsive"><table class="table table-row-bordered align-middle">
                <thead><tr><th>Subject</th><th>Page</th><th>Slug</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>@forelse($pages as $page)
                    <tr><td>{{ $page->subject->name }}</td><td>{{ $page->hero_heading }}</td><td><code>/{{ $page->slug }}</code></td><td><span class="badge badge-light-{{ $page->is_published ? 'success' : 'warning' }}">{{ $page->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td class="text-end"><a target="_blank" href="{{ route('service-pages.preview', $page) }}" class="btn btn-sm btn-light-info">Preview</a> <a href="{{ route('service-pages.edit', $page) }}" class="btn btn-sm btn-light-primary">Edit</a>
                    <form class="d-inline" method="POST" action="{{ route('service-pages.destroy', $page) }}" onsubmit="return confirm('Delete this page?')">@csrf @method('DELETE')<button class="btn btn-sm btn-light-danger">Delete</button></form></td></tr>
                @empty <tr><td colspan="5" class="text-center text-muted">No dynamic pages added yet.</td></tr> @endforelse</tbody>
            </table></div>
        </div>
    </div>
</div>
</div>
@endsection
