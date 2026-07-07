@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl py-6">

@include('layouts.flash')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dynamic Subject Pages</h3>
        <div class="card-toolbar">
            <a href="{{ route('subject-pages.create') }}" class="btn btn-primary">Add Subject Page</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-row-bordered table-hover align-middle gs-0 gy-3 mb-0">
            <thead class="bg-light">
                <tr class="fw-bold text-muted">
                    <th class="ps-4">Subject</th>
                    <th>Slug</th>
                    <th>Meta Title</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr>
                    <td class="ps-4"><span class="badge badge-light-primary">{{ $page->subject->name ?? '—' }}</span></td>
                    <td><code>/{{ $page->slug }}</code></td>
                    <td>{{ Str::limit($page->meta_title, 60) }}</td>
                    <td>
                        @if($page->is_published)
                            <span class="badge badge-light-success">Published</span>
                        @else
                            <span class="badge badge-light-warning">Draft</span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <button type="button" class="btn btn-sm btn-light-info btn-preview" data-url="{{ route('subject-pages.preview', $page) }}">Preview</button>
                        <a href="{{ route('subject-pages.edit', $page) }}" class="btn btn-sm btn-light-primary">Edit</a>
                        <form class="d-inline" method="POST" action="{{ route('subject-pages.destroy', $page) }}" onsubmit="return confirm('Delete this page?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-6 text-muted">No dynamic subject pages found. <a href="{{ route('subject-pages.create') }}">Create one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
            iframe.src = this.getAttribute('data-url');
            modal.show();
        });
    });
    document.getElementById('previewModal').addEventListener('hidden.bs.modal', function () {
        iframe.src = '';
    });
});
</script>
@endsection
