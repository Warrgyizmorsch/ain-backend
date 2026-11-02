@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl py-6">
    <div class="card">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h3 class="m-0">Prime Assignment — Dynamic Pages</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('prime-service-pages.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-1"></i> Add Dynamic Page
                </a>
            </div>
        </div>

        <div class="card-body">
            @include('layouts.flash')

            <!-- Optimized Search & Filter Form for 200+ Pages -->
            <form method="GET" action="{{ route('prime-service-pages.index') }}" class="row g-3 mb-6 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fs-7 fw-bold text-muted">Search Page or Slug</label>
                    <input type="text" name="search" class="form-control form-control-solid" placeholder="Search by title or slug..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-7 fw-bold text-muted">Filter by Prefix</label>
                    <select name="prefix_id" class="form-select form-select-solid">
                        <option value="">All Prefixes</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}" @selected(request('prefix_id') == $sub->id)>{{ $sub->name }} ({{ $sub->slug }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fs-7 fw-bold text-muted">Status</label>
                    <select name="status" class="form-select form-select-solid">
                        <option value="">All Status</option>
                        <option value="published" @selected(request('status') === 'published')>Published</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    @if(request()->hasAny(['search', 'prefix_id', 'status']))
                        <a href="{{ route('prime-service-pages.index') }}" class="btn btn-light" title="Reset Filters"><i class="fa fa-undo"></i></a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-row-bordered align-middle">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th>Prefix</th>
                            <th>Page</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>
                                <span class="badge badge-light-info fw-semibold">{{ $page->primeSubject?->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <strong>{{ $page->hero_heading }}</strong>
                            </td>
                            <td>
                                <code>/{{ $page->slug }}</code>
                            </td>
                            <td>
                                <span class="badge badge-light-{{ $page->is_published ? 'success' : 'warning' }}">
                                    {{ $page->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-light-info btn-preview" data-url="{{ route('prime-service-pages.preview', $page) }}">
                                    Preview
                                </button>
                                <a href="{{ route('prime-service-pages.edit', $page) }}" class="btn btn-sm btn-light-primary">
                                    Edit
                                </a>
                                <form class="d-inline" method="POST" action="{{ route('prime-service-pages.destroy', $page) }}" onsubmit="return confirm('Delete this prime dynamic page?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-6">
                                No prime dynamic pages found. Click "Add Dynamic Page" to create one.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if($pages->hasPages())
                <div class="d-flex justify-content-between align-items-center flex-wrap pt-4">
                    <div class="text-muted fs-7">
                        Showing {{ $pages->firstItem() }} to {{ $pages->lastItem() }} of {{ $pages->total() }} pages
                    </div>
                    <div>
                        {{ $pages->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Page Preview — Prime Assignment</h5>
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

