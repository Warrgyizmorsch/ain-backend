@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl py-6">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Dynamic Pages</h3><div class="card-toolbar"><a href="{{ route('service-pages.create') }}" class="btn btn-primary">Add Dynamic Page</a></div></div>
        <div class="card-body">
            @include('layouts.flash')
            <div class="table-responsive"><table class="table table-row-bordered align-middle">
                <thead><tr><th>Prefix</th><th>Page</th><th>Slug</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>@forelse($pages as $page)
                    <tr><td>{{ $page->subject?->name ?? 'N/A' }}</td><td>{{ $page->hero_heading }}</td><td><code>/{{ $page->slug }}</code></td><td><span class="badge badge-light-{{ $page->is_published ? 'success' : 'warning' }}">{{ $page->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td class="text-end"><button type="button" class="btn btn-sm btn-light-info btn-preview" data-url="{{ route('service-pages.preview', $page) }}">Preview</button> <a href="{{ route('service-pages.edit', $page) }}" class="btn btn-sm btn-light-primary">Edit</a>
                    <form class="d-inline" method="POST" action="{{ route('service-pages.destroy', $page) }}" onsubmit="return confirm('Delete this page?')">@csrf @method('DELETE')<button class="btn btn-sm btn-light-danger">Delete</button></form></td></tr>
                @empty <tr><td colspan="5" class="text-center text-muted">No dynamic pages added yet.</td></tr> @endforelse</tbody>
            </table></div>
        </div>
    </div>
</div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Page Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="previewIframe" src="" style="width: 100%; height: 80vh; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    const iframe = document.getElementById('previewIframe');

    document.querySelectorAll('.btn-preview').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            iframe.src = url;
            modal.show();
        });
    });

    document.getElementById('previewModal').addEventListener('hidden.bs.modal', function () {
        iframe.src = '';
    });
});
</script>
@endsection
