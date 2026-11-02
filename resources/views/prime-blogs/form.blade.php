@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl py-6">
@php($editing = $blog->exists)
<form method="POST" action="{{ $editing ? route('prime-blogs.update', $blog) : route('prime-blogs.store') }}" enctype="multipart/form-data">
@csrf
@if($editing) @method('PUT') @endif

<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">{{ $editing ? 'Edit' : 'Add' }} Prime Blog</h3>
        <div class="card-toolbar">
            <a href="{{ route('prime-blogs.index') }}" class="btn btn-light me-2">Back</a>
            @if($editing)
                <button type="button" class="btn btn-light-info me-2 btn-preview" data-url="{{ route('prime-blogs.preview', $blog) }}">Preview</button>
            @endif
            <button class="btn btn-primary">Save Blog</button>
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
                <label class="form-label required">Blog Title</label>
                <input name="title" id="blogTitle" class="form-control form-control-lg" value="{{ old('title', $blog->title) }}" placeholder="e.g. 10 Tips for Writing Academic Essays" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Category</label>
                <input name="category" class="form-control form-control-lg" value="{{ old('category', $blog->category) }}" placeholder="e.g. Essay Writing, Dissertation, Study Tips">
            </div>

            <div class="col-md-6">
                <label class="form-label required">URL Slug</label>
                <div class="input-group">
                    <span class="input-group-text">/blog/</span>
                    <input name="slug" id="blogSlug" class="form-control" value="{{ old('slug', $blog->slug) }}" placeholder="10-tips-for-writing-academic-essays" required>
                </div>
                <div class="form-text fs-8 text-muted">Auto-generated from title, editable manually anytime.</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Author</label>
                <div class="row g-2">
                    <div class="col-6">
                        <select name="author_id" class="form-select" id="authorSelect">
                            <option value="">Select Author (Optional)</option>
                            @foreach($authors as $auth)
                                <option value="{{ $auth->id }}" @selected(old('author_id', $blog->author_id) == $auth->id)>{{ $auth->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <input name="author_name" class="form-control" value="{{ old('author_name', $blog->author_name) }}" placeholder="Or type manual name...">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image & SEO Card -->
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Featured Image & SEO Details</h3>
    </div>
    <div class="card-body">
        <div class="row g-5">
            <div class="col-md-6">
                <label class="form-label">Featured Thumbnail Image</label>
                <input type="file" name="image_file" class="form-control" accept="image/*" id="imageInput">
                <div class="form-text fs-8 text-muted">Recommended resolution: 1200x630px (JPG, PNG, WEBP max 5MB).</div>

                @if($blog->image)
                    <div class="mt-3 d-flex align-items-center gap-3">
                        <img src="{{ $blog->image_url }}" alt="" class="rounded border" style="width: 100px; height: 60px; object-fit: cover;">
                        <span class="fs-8 text-muted">Current image file</span>
                    </div>
                @endif
            </div>

            <div class="col-md-6">
                <label class="form-label">Meta Title</label>
                <input name="meta_title" class="form-control" maxlength="255" value="{{ old('meta_title', $blog->meta_title) }}" placeholder="SEO Meta Title...">
            </div>

            <div class="col-md-6">
                <label class="form-label">Meta Keywords</label>
                <input name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $blog->meta_keywords) }}" placeholder="e.g. assignment help, academic tips, essay writing">
            </div>

            <div class="col-md-6">
                <label class="form-label">Meta Description</label>
                <textarea name="meta_description" class="form-control" rows="2" placeholder="Short description for search engine snippet...">{{ old('meta_description', $blog->meta_description) }}</textarea>
            </div>
        </div>
    </div>
</div>

<!-- Content Card with WYSIWYG -->
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Blog Content</h3>
    </div>
    <div class="card-body">
        <textarea id="blog_content" name="content" class="form-control">{{ old('content', $blog->content) }}</textarea>
    </div>
</div>

<!-- Dynamic FAQs -->
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Blog FAQs (Optional)</h3>
        <div class="card-toolbar">
            <button type="button" id="addFaq" class="btn btn-sm btn-light-primary">Add FAQ</button>
        </div>
    </div>
    <div class="card-body">
        <div id="faqRows"></div>
    </div>
</div>

<!-- Publish & Save -->
<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <label class="form-check form-switch form-check-custom form-check-solid">
            <input type="hidden" name="is_published" value="0">
            <input class="form-check-input" type="checkbox" name="is_published" value="1" @checked(old('is_published', $blog->exists ? $blog->is_published : true))>
            <span class="form-check-label fw-bold">Publish on website</span>
        </label>
        <button class="btn btn-primary px-10">Save Blog Post</button>
    </div>
</div>
</form>

@php($faqValues = old('faqs', $blog->faqs ?: []))

<template id="faqTemplate">
    <div class="faq-row border rounded p-4 mb-4">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-light-danger remove-faq">Remove</button>
        </div>
        <label class="form-label required">Question</label>
        <input class="form-control faq-question mb-3" placeholder="Enter frequently asked question...">
        <label class="form-label required">Answer</label>
        <textarea class="form-control faq-answer" rows="3" placeholder="Enter answer..."></textarea>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.getElementById('blogTitle');
    const slugInput = document.getElementById('blogSlug');

    // Auto-generate slug on new blog creation if not manually edited yet
    @if(!$editing)
    let slugManual = false;
    slugInput.addEventListener('input', function() { slugManual = true; });
    titleInput.addEventListener('input', function() {
        if (!slugManual) {
            slugInput.value = this.value.toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });
    @endif

    // Dynamic FAQs
    const rows = document.getElementById('faqRows');
    const tpl = document.getElementById('faqTemplate');
    let index = 0;

    function addFaq(faq = {}) {
        const node = tpl.content.cloneNode(true);
        const row = node.querySelector('.faq-row');
        row.querySelector('.faq-question').name = `faqs[${index}][question]`;
        row.querySelector('.faq-answer').name = `faqs[${index}][answer]`;
        row.querySelector('.faq-question').value = faq.question || '';
        row.querySelector('.faq-answer').value = faq.answer || '';
        row.querySelector('.remove-faq').onclick = () => row.remove();
        rows.appendChild(node);
        index++;
    }

    document.getElementById('addFaq').onclick = () => addFaq();
    @json($faqValues).forEach(addFaq);
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
        $('#blog_content').summernote({
            placeholder: 'Write the complete blog article here...',
            tabsize: 2,
            height: 400,
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
                <h5 class="modal-title">Blog Preview — Prime Assignment</h5>
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

