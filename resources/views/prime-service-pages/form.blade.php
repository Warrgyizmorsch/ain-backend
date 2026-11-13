@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl py-6">
@php($editing = $page->exists)
<form method="POST" action="{{ $editing ? route('prime-service-pages.update', $page) : route('prime-service-pages.store') }}" id="primePageForm">
@csrf
@if($editing)
    @method('PUT')
    <input type="hidden" name="id" value="{{ $page->id }}">
@endif

<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">{{ $editing ? 'Edit' : 'Add' }} Prime Dynamic Page</h3>
        <div class="card-toolbar">
            <a href="{{ route('prime-service-pages.index') }}" class="btn btn-light me-2">Back</a>
            @if($editing)
                <button type="button" class="btn btn-light-info me-2 btn-preview" data-url="{{ route('prime-service-pages.preview', $page) }}">Preview</button>
            @endif
            <button type="submit" class="btn btn-primary">Save Page</button>
        </div>
    </div>
    <div class="card-body">
        @include('layouts.flash')
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row g-5">
            <div class="col-md-4">
                <label class="form-label required">Page Type</label>
                <select name="type" id="page_type" class="form-select" required>
                    <option value="service" @selected(old('type', $page->type ?? 'service') === 'service')>Service Page</option>
                    <option value="subject" @selected(old('type', $page->type ?? 'service') === 'subject')>Subject Page</option>
                    <option value="city" @selected(old('type', $page->type ?? 'service') === 'city')>City Page</option>
                </select>
                <div class="form-text fs-8 text-muted">Select type: Service, Subject, or City.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Prefix (Optional)</label>
                <select name="prime_subject_id" id="prime_subject_id" class="form-select">
                    <option value="">None / Manual Prefix</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" data-slug="{{ $subject->slug }}" @selected(old('prime_subject_id', $page->prime_subject_id) == $subject->id)>{{ $subject->name }} ({{ $subject->slug }})</option>
                    @endforeach
                </select>
                <div class="form-text fs-8 text-muted">Optional: select prefix or enter your slug manually below.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label required">SEO Slug</label>
                <div class="input-group">
                    <span class="input-group-text">/</span>
                    <input name="slug" id="slug" class="form-control" value="{{ old('slug', $page->slug) }}" placeholder="math-assignment-help or service/accounting-help" required>
                </div>
                <div class="form-text fs-8 text-muted">Type full manual slug e.g. <code>math-assignment-help</code> or <code>service/assignment-help</code>.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label required">Meta Title</label>
                <input name="meta_title" class="form-control" maxlength="255" value="{{ old('meta_title', $page->meta_title) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label required">Meta Description</label>
                <textarea name="meta_description" class="form-control" rows="2" required>{{ old('meta_description', $page->meta_description) }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Section 1 — Hero</h3>
    </div>
    <div class="card-body">
        <div class="row g-5">
            <div class="col-md-6">
                <label class="form-label required">Hero H1</label>
                <input name="hero_heading" class="form-control" value="{{ old('hero_heading', $page->hero_heading) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Orange highlighted line</label>
                <input name="hero_highlight" class="form-control" value="{{ old('hero_highlight', $page->hero_highlight) }}" placeholder="You Can Rely On">
            </div>
            <div class="col-12">
                <label class="form-label required">Hero Content</label>
                <textarea name="hero_content" class="form-control" rows="4" required>{{ old('hero_content', $page->hero_content) }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="row g-6 mb-6">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Experts to show</h3>
            </div>
            <div class="card-body overflow-auto" style="max-height:350px">
                @forelse($experts as $expert)
                    <label class="form-check form-check-custom form-check-solid mb-3">
                        <input class="form-check-input" type="checkbox" name="expert_ids[]" value="{{ $expert->id }}" @checked(in_array($expert->id, old('expert_ids', $page->expert_ids ?: [])))>
                        <span class="form-check-label">
                            <strong>{{ $expert->name }}</strong> — {{ $expert->subject ?: 'No subject' }}
                        </span>
                    </label>
                @empty
                    <p class="text-muted">Add experts in Expert Master first.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">“What Our Students Say” reviews</h3>
            </div>
            <div class="card-body overflow-auto" style="max-height:350px">
                @forelse($reviews as $review)
                    <label class="form-check form-check-custom form-check-solid mb-3">
                        <input class="form-check-input" type="checkbox" name="review_ids[]" value="{{ $review->id }}" @checked(in_array($review->id, old('review_ids', $page->review_ids ?: [])))>
                        <span class="form-check-label">
                            <strong>{{ $review->name }}</strong> — {{ Str::limit($review->description, 70) }}
                        </span>
                    </label>
                @empty
                    <p class="text-muted">Add reviews in Review Master first.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Dynamic FAQs</h3>
        <div class="card-toolbar">
            <button type="button" id="addFaq" class="btn btn-sm btn-light-primary">Add FAQ</button>
        </div>
    </div>
    <div class="card-body">
        <div id="faqRows"></div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Long Content Section</h3>
        <div class="card-toolbar d-flex align-items-center gap-2">
            <span class="text-muted fs-7 me-1">Mode:</span>
            <div class="btn-group" role="group" aria-label="Editor Mode">
                <button type="button" id="btnModeVisual" class="btn btn-sm btn-primary active">
                    <i class="fa fa-font me-1"></i> Visual / Direct Text
                </button>
                <button type="button" id="btnModeHtml" class="btn btn-sm btn-light-primary">
                    <i class="fa fa-code me-1"></i> Raw HTML Code
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-light-info d-flex align-items-center p-3 mb-4">
            <i class="fa fa-info-circle text-info fs-4 me-3"></i>
            <div class="fs-7 text-gray-700">
                Aap <strong>Visual / Direct Text</strong> mode me direct text format kar sakte hain ya <strong>Raw HTML Code</strong> mode me direct HTML code paste/edit kar sakte hain. Dono mode auto-sync honge.
            </div>
        </div>

        <!-- Raw HTML Code Editor (Monospace Dark Box) -->
        <div id="htmlEditorContainer" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted fs-7"><i class="fa fa-code text-primary me-1"></i> HTML Editor (Paste ya type karein raw HTML code):</span>
                <span class="badge badge-light-primary fs-8">HTML Code View</span>
            </div>
            <textarea id="long_content_raw" class="form-control" rows="18" style="font-family: Consolas, Monaco, 'Courier New', monospace; font-size: 13.5px; line-height: 1.6; background-color: #1e1e2d; color: #5eead4; border: 1px solid #2d3748; border-radius: 8px; padding: 14px;" placeholder="<h2>Apna Heading</h2><p>Apna HTML content yahan paste karein...</p>"></textarea>
        </div>

        <!-- Visual Direct Text Editor Area -->
        <div id="visualEditorContainer">
            <textarea id="long_content" name="long_content" class="form-control" style="min-height: 380px;">{{ old('long_content', $page->long_content) }}</textarea>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <label class="form-check form-switch form-check-custom form-check-solid">
            <input type="hidden" name="is_published" value="0">
            <input class="form-check-input" type="checkbox" name="is_published" value="1" @checked(old('is_published', $page->exists ? $page->is_published : true))>
            <span class="form-check-label fw-bold">Publish on frontend</span>
        </label>
        <button type="submit" class="btn btn-primary px-10">Save Page</button>
    </div>
</div>
</form>

@php($faqValues = old('faqs', $page->faqs ?: [['question' => '', 'answer' => '']]))

<template id="faqTemplate">
    <div class="faq-row border rounded p-4 mb-4">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-light-danger remove-faq">Remove</button>
        </div>
        <label class="form-label required">Question</label>
        <input class="form-control faq-question mb-3">
        <label class="form-label required">Answer</label>
        <textarea class="form-control faq-answer" rows="3"></textarea>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
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



<script>
document.addEventListener('DOMContentLoaded', function () {
    const subjectSelect = document.getElementById('prime_subject_id');
    const slugInput = document.getElementById('slug');
    let lastPrefixSlug = '';

    if (subjectSelect && subjectSelect.value) {
        const selectedOpt = subjectSelect.options[subjectSelect.selectedIndex];
        lastPrefixSlug = selectedOpt.getAttribute('data-slug') || '';
    }

    if (subjectSelect) {
        subjectSelect.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            const newPrefixSlug = (selectedOpt && selectedOpt.value) ? (selectedOpt.getAttribute('data-slug') || '') : '';
            let currentVal = slugInput.value.trim();

            if (lastPrefixSlug && currentVal.startsWith(lastPrefixSlug + '/')) {
                currentVal = currentVal.substring(lastPrefixSlug.length + 1);
            }

            if (newPrefixSlug) {
                if (currentVal) {
                    slugInput.value = newPrefixSlug + '/' + currentVal;
                } else {
                    slugInput.value = newPrefixSlug + '/';
                }
            } else {
                slugInput.value = currentVal;
            }

            lastPrefixSlug = newPrefixSlug;
        });
    }
});
</script>

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

<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        border: 1px solid #e4e6ef !important;
        border-radius: 8px !important;
        box-shadow: none !important;
    }
    .note-editor .note-toolbar {
        background: #f8f9fa !important;
        border-bottom: 1px solid #e4e6ef !important;
        border-top-left-radius: 8px !important;
        border-top-right-radius: 8px !important;
        padding: 6px 10px !important;
    }
    .note-editor .note-editable {
        min-height: 380px !important;
        font-family: inherit !important;
        font-size: 14px !important;
        line-height: 1.7 !important;
        color: #181c32 !important;
        background: #ffffff !important;
        padding: 16px !important;
    }
    .note-editor .note-statusbar {
        border-bottom-left-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
        background: #f8f9fa !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawTextarea = document.getElementById('long_content_raw');
    const visualTextarea = document.getElementById('long_content');
    const visualContainer = document.getElementById('visualEditorContainer');
    const htmlContainer = document.getElementById('htmlEditorContainer');
    const btnVisual = document.getElementById('btnModeVisual');
    const btnHtml = document.getElementById('btnModeHtml');
    const form = document.getElementById('primePageForm');

    let currentMode = 'visual';
    let summernoteReady = false;

    // Initialize raw textarea value with existing content
    if (rawTextarea && visualTextarea) {
        rawTextarea.value = visualTextarea.value || '';
    }

    function getEditorContent() {
        if (summernoteReady && typeof $ !== 'undefined' && $('#long_content').data('summernote')) {
            return $('#long_content').summernote('code');
        }
        return visualTextarea ? visualTextarea.value : '';
    }

    function setEditorContent(content) {
        if (summernoteReady && typeof $ !== 'undefined' && $('#long_content').data('summernote')) {
            $('#long_content').summernote('code', content);
        }
        if (visualTextarea) {
            visualTextarea.value = content;
        }
    }

    function switchToVisual() {
        if (currentMode === 'visual') return;
        const htmlVal = rawTextarea.value;
        setEditorContent(htmlVal);
        currentMode = 'visual';
        if (htmlContainer) htmlContainer.style.display = 'none';
        if (visualContainer) visualContainer.style.display = 'block';

        if (btnVisual) {
            btnVisual.classList.add('btn-primary', 'active');
            btnVisual.classList.remove('btn-light-primary');
        }
        if (btnHtml) {
            btnHtml.classList.remove('btn-primary', 'active');
            btnHtml.classList.add('btn-light-primary');
        }
    }

    function switchToHtml() {
        if (currentMode === 'html') return;
        const visualVal = getEditorContent();
        if (rawTextarea) rawTextarea.value = visualVal;
        currentMode = 'html';
        if (visualContainer) visualContainer.style.display = 'none';
        if (htmlContainer) htmlContainer.style.display = 'block';

        if (btnHtml) {
            btnHtml.classList.add('btn-primary', 'active');
            btnHtml.classList.remove('btn-light-primary');
        }
        if (btnVisual) {
            btnVisual.classList.remove('btn-primary', 'active');
            btnVisual.classList.add('btn-light-primary');
        }
        if (rawTextarea) rawTextarea.focus();
    }

    if (btnVisual) btnVisual.addEventListener('click', switchToVisual);
    if (btnHtml) btnHtml.addEventListener('click', switchToHtml);

    // Sync content to main textarea before form submit
    if (form) {
        form.addEventListener('submit', function () {
            if (currentMode === 'html') {
                if (visualTextarea && rawTextarea) {
                    visualTextarea.value = rawTextarea.value;
                }
                if (summernoteReady && typeof $ !== 'undefined' && $('#long_content').data('summernote')) {
                    try {
                        $('#long_content').summernote('code', rawTextarea.value);
                    } catch (e) {}
                }
            } else {
                if (summernoteReady && typeof $ !== 'undefined' && $('#long_content').data('summernote')) {
                    try {
                        const code = $('#long_content').summernote('code');
                        if (visualTextarea) visualTextarea.value = code;
                        if (rawTextarea) rawTextarea.value = code;
                    } catch (e) {}
                }
            }
        });
    }

    function loadScript(src, callback) {
        const script = document.createElement('script');
        script.src = src;
        script.onload = callback;
        document.body.appendChild(script);
    }

    function initSummernote() {
        if (typeof $ === 'undefined' || typeof $.fn.summernote === 'undefined') return;
        $('#long_content').summernote({
            placeholder: 'Type direct content with headings, lists, tables or switch to HTML Code mode...',
            tabsize: 2,
            height: 380,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['fullscreen', 'codeview']]
            ],
            callbacks: {
                onInit: function () {
                    summernoteReady = true;
                    if (rawTextarea && !rawTextarea.value) {
                        rawTextarea.value = $('#long_content').summernote('code');
                    }
                },
                onChange: function (contents) {
                    if (currentMode === 'visual' && rawTextarea) {
                        rawTextarea.value = contents;
                    }
                }
            }
        });
    }

    if (typeof window.jQuery !== 'undefined') {
        loadScript('https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js', initSummernote);
    } else {
        loadScript('https://code.jquery.com/jquery-3.7.1.min.js', function () {
            loadScript('https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js', initSummernote);
        });
    }
});
</script>
</div>
</div>
@endsection

