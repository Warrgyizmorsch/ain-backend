@extends('layouts.app')

@section('content')

<style>
  /* Set the height of the Quill editor for desktop view */
  .note-editor .note-editable {
    min-height: 400px;
  }
</style>

<div class="docs-content d-flex flex-column flex-column-fluid" id="kt_docs_content">
    <div class="container" id="kt_docs_content_container">
        <div class="card card-docs mb-2">
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="pt-10">
                    <form id="blogForm" action="{{ route('submit_blog') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">Thumbnail</label>
                            <div class="col-lg-8">
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(assets/media/avatars/blank.png)">
                                    <div class="image-input-wrapper w-125px h-125px" style="width: 200px !important; height:150px; background-image:  url(assets/media/avatars/blank.png)"></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="photo" accept=".png, .jpg, .jpeg">
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>
                                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="blogUrl" class="form-label">Blog URL</label>
                            <input type="text" class="form-control" id="blogUrl" name="blogUrl" required>
                        </div>
                        <div class="mb-3">
                            <label for="blogTitle" class="form-label">Blog Title</label>
                            <input type="text" class="form-control" id="blogTitle" name="blogTitle" required>
                            <input type="hidden" name="type" value="blog">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Author</label>
                            <select name="author_id" class="form-control" required>
                                <option value="">Select Author</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}">
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="blogContent" class="form-label">Blog Content</label>
                            <textarea id="summernote" name="blogContent" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="blogTitle" class="form-label">Meta Tag</label>
                            <input type="text" class="form-control"  name="MetaTag" required>
                        </div>
                        <div class="mb-3">
                            <label for="blogTitle" class="form-label">Meta Description</label>
                            <textarea class="form-control" name="Metadescription" id=""></textarea>
                        </div>
                        <h2>FAQ</h2>
                        <div id="faq-container">
                            <div class="faq-entry mb-3">
                                <label class="form-label">Question</label>
                                <input type="text" class="form-control faq-question" required>
                                <label class="form-label">Answer</label>
                                <textarea class="form-control faq-answer" required></textarea>
                                <button type="button" class="btn btn-danger remove-faq mt-2">- Remove</button>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <button type="button" class="btn btn-success mt-2 add-faq">+ Add FAQ</button>
                        <input type="hidden" name="faq_data" id="faq_data">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script> --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
{{-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script> --}}
{{-- <script>
$('#summernote').summernote({
    placeholder: 'Write blog content...',
    tabsize: 2,
    height: 400,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'picture']],
        ['view', ['codeview']]
    ],
    callbacks: {
        onPaste: function(e) {
            setTimeout(function() {
                let content = $('#summernote').summernote('code');

                content = content
                    .replace(/<span[^>]*>/gi, '')
                    .replace(/<\/span>/gi, '')
                    .replace(/\sstyle="[^"]*"/gi, '')
                    .replace(/\sclass="[^"]*"/gi, '')
                    .replace(/\sdir="[^"]*"/gi, '');

                $('#summernote').summernote('code', content);
            }, 100);
        }
    }
});
</script> --}}

<script>
document.addEventListener("DOMContentLoaded", function () {
    function loadScript(src, callback) {
        let script = document.createElement('script');
        script.src = src;
        script.onload = callback;
        document.body.appendChild(script);
    }

    loadScript('https://code.jquery.com/jquery-3.7.1.min.js', function () {
        loadScript('https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js', function () {
            $('#summernote').summernote({
                placeholder: 'Write blog content...',
                tabsize: 2,
                height: 400,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['codeview']]
                ]
            });
        });
    });
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
      function addFAQ() {
          let container = document.getElementById("faq-container");
          let div = document.createElement("div");
          div.classList.add("faq-entry", "mb-3");
          div.innerHTML = `
              <label class="form-label">Question</label>
              <input type="text" class="form-control faq-question" required>
              <label class="form-label">Answer</label>
              <textarea class="form-control faq-answer" required></textarea>
              <button type="button" class="btn btn-danger remove-faq mt-2">- Remove</button>
          `;
          container.appendChild(div);
      }

      // Add FAQ dynamically
      document.querySelector(".add-faq").addEventListener("click", function () {
          addFAQ();
      });

      // Remove FAQ dynamically
      document.addEventListener("click", function (e) {
          if (e.target.classList.contains("remove-faq")) {
              e.target.closest(".faq-entry").remove();
          }
      });

      // Convert FAQs to JSON before form submission
      document.getElementById("blogForm").addEventListener("submit", function (e) {
          let faqs = [];
          document.querySelectorAll(".faq-entry").forEach(entry => {
              let question = entry.querySelector(".faq-question").value.trim();
              let answer = entry.querySelector(".faq-answer").value.trim();
              if (question && answer) {
                  faqs.push({ question, answer });
              }
          });
          document.getElementById("faq_data").value = JSON.stringify(faqs);
      });
  });
</script>
@endsection