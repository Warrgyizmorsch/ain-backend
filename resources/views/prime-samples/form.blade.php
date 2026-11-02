@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl py-6">
@php($editing = $sample->exists)
<form method="POST" action="{{ $editing ? route('prime-samples.update', $sample) : route('prime-samples.store') }}">
@csrf
@if($editing) @method('PUT') @endif

<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">{{ $editing ? 'Edit' : 'Add' }} Prime Sample</h3>
        <div class="card-toolbar">
            <a href="{{ route('prime-samples.index') }}" class="btn btn-light me-2">Back</a>
            @if($editing)
                <button type="button" class="btn btn-light-info me-2 btn-preview" data-url="{{ route('prime-samples.preview', $sample) }}">Preview</button>
            @endif
            <button class="btn btn-primary">Save Sample</button>
        </div>
    </div>
    <div class="card-body">
        @include('layouts.flash')
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-5">
            <div class="col-md-8">
                <label class="form-label required">Sample Title</label>
                <input name="title" id="sampleTitle" class="form-control form-control-lg" value="{{ old('title', $sample->title) }}" placeholder="e.g. Impact of Artificial Intelligence in Healthcare: A Critical Review" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select form-select-lg">
                    <option value="">Select Existing Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $sample->category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <div class="mt-2">
                    <input type="text" name="new_category" class="form-control form-control-sm" placeholder="Or type new category name to create...">
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label required">URL Slug</label>
                <div class="input-group">
                    <span class="input-group-text">/sample/</span>
                    <input name="slug" id="sampleSlug" class="form-control" value="{{ old('slug', $sample->slug) }}" placeholder="impact-of-artificial-intelligence-in-healthcare" required>
                </div>
                <div class="form-text fs-8 text-muted">Auto-generated from title, editable manually anytime.</div>
            </div>

            <div class="col-md-3">
                <label class="form-label">Number of Pages</label>
                <div class="input-group">
                    <input type="number" min="1" step="1" name="pages_count" id="pagesCount" class="form-control" value="{{ old('pages_count', $sample->pages_count ?? 1) }}">
                    <span class="input-group-text">pages</span>
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label">Words Count</label>
                <div class="input-group">
                    <input type="number" min="1" step="1" name="words_count" id="wordsCount" class="form-control" value="{{ old('words_count', $sample->words_count ?? 250) }}">
                    <span class="input-group-text">words</span>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="col-12">
                <div class="separator separator-dashed my-4"></div>
                <h5 class="fw-bold mb-3">SEO Meta Information</h5>
            </div>

            <div class="col-md-6">
                <label class="form-label">Meta Title</label>
                <input name="meta_title" class="form-control" value="{{ old('meta_title', $sample->meta_title) }}" placeholder="Meta Title for Google search...">
            </div>

            <div class="col-md-6">
                <label class="form-label">Meta Description</label>
                <textarea name="meta_description" class="form-control" rows="2" placeholder="Brief summary for search engine snippet...">{{ old('meta_description', $sample->meta_description) }}</textarea>
            </div>

            <!-- Content -->
            <div class="col-12">
                <div class="separator separator-dashed my-4"></div>
                <label class="form-label required fw-bold fs-5">Sample Academic Paper Content</label>
                <textarea name="content" id="sample_content" class="form-control" rows="15">{{ old('content', $sample->content) }}</textarea>
            </div>

            <!-- Status -->
            <div class="col-12">
                <div class="form-check form-switch form-check-custom form-check-solid mt-3">
                    <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishSwitch" @checked(old('is_published', $sample->is_published ?? true))>
                    <label class="form-check-label fw-bold" for="publishSwitch">
                        Publish on Prime Assignment website
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('prime-samples.index') }}" class="btn btn-light me-2">Cancel</a>
        <button class="btn btn-primary">Save Sample</button>
    </div>
</div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.getElementById('sampleTitle');
    const slugInput  = document.getElementById('sampleSlug');
    const pagesInput = document.getElementById('pagesCount');
    const wordsInput = document.getElementById('wordsCount');

    let manualSlug = {{ $editing ? 'true' : 'false' }};

    slugInput.addEventListener('input', function() {
        manualSlug = true;
    });

    titleInput.addEventListener('input', function() {
        if (!manualSlug) {
            slugInput.value = this.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });

    // Auto calculate words based on pages if words hasn't been manually adjusted
    let manualWords = {{ $editing ? 'true' : 'false' }};
    wordsInput.addEventListener('input', function() {
        manualWords = true;
    });

    pagesInput.addEventListener('input', function() {
        if (!manualWords) {
            const pages = parseInt(this.value) || 1;
            wordsInput.value = pages * 250;
        }
    });
});
</script>

<!-- Summernote WYSIWYG -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script>
document.addEventListener('DOMContentLoaded', function () {
    function loadScript(src, callback) {
        let script = document.createElement('script');
        script.src = src;
        script.onload = callback;
        document.body.appendChild(script);
    }

    loadScript('https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js', function () {
        $('#sample_content').summernote({
            placeholder: 'Paste or write the academic paper sample content here...',
            tabsize: 2,
            height: 450,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['codeview', 'help']]
            ]
        });
    });
});
</script>

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
</div>
</div>
@endsection

