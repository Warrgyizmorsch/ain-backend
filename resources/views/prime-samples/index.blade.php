@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl py-6">
    <div class="card">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h3 class="m-0">Prime Assignment — Samples</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('prime-samples.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-1"></i> Add New Sample
                </a>
            </div>
        </div>

        <div class="card-body">
            @include('layouts.flash')

            <!-- Filter & Search Bar -->
            <form method="GET" action="{{ route('prime-samples.index') }}" class="row g-3 mb-6 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fs-7 fw-bold text-muted">Search Title or Slug</label>
                    <input type="text" name="search" class="form-control form-control-solid" placeholder="Search sample title or slug..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-7 fw-bold text-muted">Filter by Category</label>
                    <select name="category_id" class="form-select form-select-solid">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
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
                    @if(request()->hasAny(['search', 'category_id', 'status']))
                        <a href="{{ route('prime-samples.index') }}" class="btn btn-light" title="Reset Filters"><i class="fa fa-undo"></i></a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-row-bordered align-middle">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th>Title</th>
                            <th>Category</th>
                            <th>Slug</th>
                            <th>Pages / Words</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($samples as $sample)
                        <tr>
                            <td>
                                <strong>{{ $sample->title }}</strong>
                            </td>
                            <td>
                                <span class="badge badge-light-primary fw-semibold">
                                    {{ $sample->category?->name ?? 'General' }}
                                </span>
                            </td>
                            <td>
                                <code>/sample/{{ $sample->slug }}</code>
                            </td>
                            <td>
                                <span class="badge badge-light-secondary text-muted">
                                    {{ $sample->pages_count ?? 1 }} {{ Str::plural('page', $sample->pages_count ?? 1) }} &bull; {{ number_format($sample->words_count ?? 250) }} words
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-light-{{ $sample->is_published ? 'success' : 'warning' }}">
                                    {{ $sample->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-light-info btn-preview" data-url="{{ route('prime-samples.preview', $sample) }}">
                                    Preview
                                </button>
                                <a href="{{ route('prime-samples.edit', $sample) }}" class="btn btn-sm btn-light-primary">
                                    Edit
                                </a>
                                <form class="d-inline" method="POST" action="{{ route('prime-samples.destroy', $sample) }}" onsubmit="return confirm('Delete this prime sample?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-6">
                                No prime samples found. Click "Add New Sample" to create one.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($samples->hasPages())
                <div class="d-flex justify-content-between align-items-center flex-wrap pt-4">
                    <div class="text-muted fs-7">
                        Showing {{ $samples->firstItem() }} to {{ $samples->lastItem() }} of {{ $samples->total() }} samples
                    </div>
                    <div>
                        {{ $samples->links() }}
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
                <h5 class="modal-title">Sample Preview — Prime Assignment</h5>
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

