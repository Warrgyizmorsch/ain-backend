@extends('layouts.app')

@section('content')
    <style>
        #kt_docs_quill_autosave {
            height: 400px;
        }
    </style>

    <div class="docs-content d-flex flex-column flex-column-fluid" id="kt_docs_content">
        <div class="container" id="kt_docs_content_container">
            <div class="card card-docs mb-2">
                <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                    <div class="pt-10">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form id="expertForm"
                            action="{{ isset($expert) ? route('new-expert.update', $expert->id) : route('new-expert.store') }}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            @isset($expert)
                                @method('PUT')
                            @endisset

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">Profile</label>
                                <div class="col-lg-8">
                                    <div class="image-input image-input-outline" data-kt-image-input="true"
                                        style="background-image: url(assets/media/avatars/blank.png)">
                                        <div class="image-input-wrapper w-125px h-125px"
                                            style="width: 200px !important; height:150px; background-image: url({{ $expert->image ?? 'assets/media/avatars/blank.png' }})">
                                        </div>
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            title="Change avatar">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <input type="file" name="photo" accept=".png, .jpg, .jpeg">
                                        </label>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            title="Cancel avatar">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            title="Remove avatar">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                    </div>
                                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                        value="{{ $expert->name ?? '' }}">
                                </div>
                                <div class="col-lg-3">
                                    <label for="finish_order" class="form-label">Finish Order</label>
                                    <input type="text" class="form-control" id="finish_order" name="finish_order"
                                        value="{{ $expert->finish_order ?? '' }}">
                                </div>
                                <div class="col-lg-3">
                                    <label for="inprogress_order" class="form-label">In-progress Order</label>
                                    <input type="text" class="form-control" id="inprogress_order" name="inprogress_order"
                                        value="{{ $expert->inprogress_order ?? '' }}">
                                </div>
                                <div class="col-lg-2">
                                    <label for="success_rate" class="form-label">Success Rate %</label>
                                    <input type="text" class="form-control" id="success" name="success"
                                        value="{{ $expert->success ?? '' }} ">
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-lg-4">
                                    <label for="subject" class="form-label">Subject</label>
                                    <select id="subject" name="subject" class="form-select">
                                        <option value="" {{ empty($expert->subject) ? 'selected' : '' }}>Select Subject
                                        </option>
                                        <option value="NULL" {{ isset($expert->subject) && $expert->subject == 'NULL' ? 'selected' : '' }}>NULL</option>
                                        <option value="Math" {{ isset($expert->subject) && $expert->subject == 'Math' ? 'selected' : '' }}>Math</option>
                                        <option value="Chemistry" {{ isset($expert->subject) && $expert->subject == 'Chemistry' ? 'selected' : '' }}>Chemistry</option>
                                        <option value="Economics" {{ isset($expert->subject) && $expert->subject == 'Economics' ? 'selected' : '' }}>Economics</option>
                                        <option value="English" {{ isset($expert->subject) && $expert->subject == 'English' ? 'selected' : '' }}>English</option>
                                        <option value="History" {{ isset($expert->subject) && $expert->subject == 'History' ? 'selected' : '' }}>History</option>
                                        <option value="Geography" {{ isset($expert->subject) && $expert->subject == 'Geography' ? 'selected' : '' }}>Geography</option>
                                        <option value="Law" {{ isset($expert->subject) && $expert->subject == 'Law' ? 'selected' : '' }}>Law</option>
                                        <option value="Linguistic" {{ isset($expert->subject) && $expert->subject == 'Linguistic' ? 'selected' : '' }}>Linguistic</option>
                                        <option value="Nursing" {{ isset($expert->subject) && $expert->subject == 'Nursing' ? 'selected' : '' }}>Nursing</option>
                                        <option value="Physics" {{ isset($expert->subject) && $expert->subject == 'Physics' ? 'selected' : '' }}>Physics</option>
                                        <option value="Sociology" {{ isset($expert->subject) && $expert->subject == 'Sociology' ? 'selected' : '' }}>Sociology</option>
                                        <option value="Philosophy" {{ isset($expert->subject) && $expert->subject == 'Philosophy' ? 'selected' : '' }}>Philosophy</option>
                                        <option value="Statistics" {{ isset($expert->subject) && $expert->subject == 'Statistics' ? 'selected' : '' }}>Statistics</option>
                                        <option value="Accounting" {{ isset($expert->subject) && $expert->subject == 'Accounting' ? 'selected' : '' }}>Accounting</option>
                                        <option value="Marketing" {{ isset($expert->subject) && $expert->subject == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                        <option value="Computer Science" {{ isset($expert->subject) && $expert->subject == 'Computer Science' ? 'selected' : '' }}>Computer Science
                                        </option>
                                        <option value="Engineering" {{ isset($expert->subject) && $expert->subject == 'Economics' ? 'Engineering' : '' }}>Engineering</option>
                                        <option value="Finance" {{ isset($expert->subject) && $expert->subject == 'Finance' ? 'selected' : '' }}>Finance</option>
                                        <option value="Programming" {{ isset($expert->subject) && $expert->subject == 'Programming' ? 'selected' : '' }}>Programming</option>
                                        <option value="Management" {{ isset($expert->subject) && $expert->subject == 'Management' ? 'selected' : '' }}>Management</option>
                                        <option value="Business" {{ isset($expert->subject) && $expert->subject == 'Business' ? 'selected' : '' }}>Business</option>
                                        <option value="Social Sciences" {{ isset($expert->subject) && $expert->subject == 'Social Sciences' ? 'selected' : '' }}>Social Sciences
                                        </option>
                                        <option value="Health" {{ isset($expert->subject) && $expert->subject == 'Health' ? 'selected' : '' }}>Health</option>
                                        <option value="Science" {{ isset($expert->subject) && $expert->subject == 'Science' ? 'selected' : '' }}>Science</option>
                                        <option value="medical" {{ isset($expert->subject) && $expert->subject == 'medical' ? 'selected' : '' }}>medical</option>
                                        <option value="psychology" {{ isset($expert->subject) && $expert->subject == 'psychology' ? 'selected' : '' }}>psychology</option>
                                        <option value="Political Science" {{ isset($expert->subject) && $expert->subject == 'Political Science' ? 'selected' : '' }}>Political Science
                                        </option>
                                        <option value="Literature" {{ isset($expert->subject) && $expert->subject == 'Literature' ? 'selected' : '' }}>Literature</option>
                                        <option value="Multiple Disciplines" {{ isset($expert->subject) && $expert->subject == 'Multiple Disciplines' ? 'selected' : '' }}>Multiple
                                            Disciplines</option>
                                        <option value="STEM " {{ isset($expert->subject) && $expert->subject == 'STEM ' ? 'selected' : '' }}>STEM </option>
                                        <option value="Social Research " {{ isset($expert->subject) && $expert->subject == 'Social Research ' ? 'selected' : '' }}>Social Research
                                        </option>
                                        <option value="Humanities" {{ isset($expert->subject) && $expert->subject == 'Humanities' ? 'selected' : '' }}>Humanities</option>
                                        <option value="Behavioral Sciences" {{ isset($expert->subject) && $expert->subject == 'Behavioral Sciences' ? 'selected' : '' }}>Behavioral Sciences
                                        </option>
                                        <option value="Behavioral Sciences" {{ isset($expert->subject) && $expert->subject == 'Behavioral Sciences' ? 'selected' : '' }}>Behavioral Sciences
                                        </option>
                                    </select>
                                </div>
                                <div class="mb-3 col-lg-4">
                                    <label for="services" class="form-label">Services</label>
                                    <select id="services" name="services" class="form-select">
                                        <option value="" {{ empty($expert->service) ? 'selected' : '' }}>Select Service
                                        </option>
                                        <option value="NULL" {{ isset($expert->service) && $expert->service == 'NULL' ? 'selected' : '' }}>NULL</option>
                                        <option value="Assignment" {{ isset($expert->service) && $expert->service == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                                        <option value="Essay" {{ isset($expert->service) && $expert->service == 'Essay' ? 'selected' : '' }}>Essay</option>
                                        <option value="Homework" {{ isset($expert->service) && $expert->service == 'Homework' ? 'selected' : '' }}>Homework</option>
                                        <option value="Academic" {{ isset($expert->service) && $expert->service == 'Academic' ? 'selected' : '' }}>Academic</option>
                                        <option value="Coursework" {{ isset($expert->service) && $expert->service == 'Coursework' ? 'selected' : '' }}>Coursework</option>
                                        <option value="University " {{ isset($expert->service) && $expert->service == 'University ' ? 'selected' : '' }}>University </option>
                                        <option value="Thesis" {{ isset($expert->service) && $expert->service == 'Thesis' ? 'selected' : '' }}>Thesis</option>
                                        <option value="Summary" {{ isset($expert->service) && $expert->service == 'Summary' ? 'selected' : '' }}>Summary</option>
                                        <option value="Term Paper" {{ isset($expert->service) && $expert->service == 'Term Paper' ? 'selected' : '' }}>Term Paper</option>
                                        <option value="Personal statement" {{ isset($expert->service) && $expert->service == 'Personal statement' ? 'selected' : '' }}>Personal statement
                                        </option>
                                        <option value="Literature review " {{ isset($expert->service) && $expert->service == 'Literature review ' ? 'selected' : '' }}>Literature review
                                        </option>
                                        <option value="Resume Writing" {{ isset($expert->service) && $expert->service == 'Resume Writing' ? 'selected' : '' }}>Resume Writing </option>
                                        <option value="Case study" {{ isset($expert->service) && $expert->service == 'Case study' ? 'selected' : '' }}>Case study</option>
                                        <option value="Proofreading & Editing" {{ isset($expert->service) && $expert->service == 'Proofreading & Editing' ? 'selected' : '' }}>Proofreading &
                                            Editing</option>
                                        <option value="Online Exam" {{ isset($expert->service) && $expert->service == 'Online Exam' ? 'selected' : '' }}>Online Exam</option>
                                        <option value="Dissertation" {{ isset($expert->service) && $expert->service == 'Dissertation' ? 'selected' : '' }}>Dissertation</option>
                                        <option value="Research Paper" {{ isset($expert->service) && $expert->service == 'Research Paper' ? 'selected' : '' }}>Research Paper</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-lg-4">
                                    <label for="location" class="form-label">Location</label>
                                    <select id="location" name="location" class="form-select">
                                        <option value="" {{ empty($expert->location) ? 'selected' : '' }}>Select Location
                                        </option>
                                        <option value="Uk" {{ isset($expert->location) && $expert->location == 'Uk' ? 'selected' : '' }}>Uk</option>
                                        <option value="London" {{ isset($expert->location) && $expert->location == 'London' ? 'selected' : '' }}>London</option>
                                        <option value="Birmingham" {{ isset($expert->location) && $expert->location == 'Birmingham' ? 'selected' : '' }}>Birmingham</option>
                                        <option value="Manchester" {{ isset($expert->location) && $expert->location == 'Manchester' ? 'selected' : '' }}>Manchester</option>'
                                        <option value="AU" {{ isset($expert->location) && $expert->location == 'AU' ? 'selected' : '' }}>AU</option>
                                        <option value="Sydney" {{ isset($expert->location) && $expert->location == 'Sydney' ? 'selected' : '' }}>Sydney</option>
                                        <option value="Melbourne" {{ isset($expert->location) && $expert->location == 'Melbourne' ? 'selected' : '' }}>Melbourne</option>
                                        <option value="Barcelona" {{ isset($expert->location) && $expert->location == 'Barcelona' ? 'selected' : '' }}>Barcelona</option>
                                        <option value="CANADA" {{ isset($expert->location) && $expert->location == 'CANADA' ? 'selected' : '' }}>CANADA</option>
                                        <option value="UAE" {{ isset($expert->location) && $expert->location == 'UAE' ? 'selected' : '' }}>UAE</option>
                                        <option value="Spain" {{ isset($expert->location) && $expert->location == 'Spain' ? 'selected' : '' }}>Spain</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Skills Section -->
                                <div class="col-lg-4">
                                    <label for="skills" class="form-label">Skills</label>
                                    <div id="skills-container">
                                        @if(isset($expert->skills) && is_array($expert->skills))
                                            @foreach($expert->skills as $skill)
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control" name="skills[]" value="{{ $skill }}">
                                                    <button type="button" class="btn btn-danger remove-field">-</button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" name="skills[]" value="">
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-success mt-2 add-skill">+ Add Skill</button>
                                </div>

                                <!-- Help With Section -->
                                <div class="col-lg-4">
                                    <label for="helpus" class="form-label">Help with is</label>
                                    <div id="help-container">
                                        @if(isset($expert->helpus) && is_array($expert->helpus))
                                            @foreach($expert->helpus as $help)
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control" name="helpus[]" value="{{ $help }}">
                                                    <button type="button" class="btn btn-danger remove-field">-</button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" name="helpus[]" value="">
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-success mt-2 add-help">+ Add Help</button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="blogContent" class="form-label">Description</label>
                                <div id="kt_docs_quill_editor"></div>
                                <input type="hidden" id="blogContent" name="description">
                            </div>

                            <!-- Description -->
                            <h2>Customer Reviews</h2>
                            <div id="reviews-container">
                                @php
                                    $customerReviews = isset($expert->customer_review) ? (is_array($expert->customer_review) ? $expert->customer_review : json_decode($expert->customer_review, true)) : [];
                                @endphp

                                @if(!empty($customerReviews))
                                    @foreach($customerReviews as $review)
                                        <div class="review-entry mb-3">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label class="form-label">Customer Name</label>
                                                    <input type="text" class="form-control" name="customer_name[]"
                                                        value="{{ $review['name'] ?? '' }}">
                                                </div>

                                                <div class="col-lg-4">
                                                    <label class="form-label">Rating</label>
                                                    <select class="form-control" name="customer_rating[]">
                                                        <option value="5" {{ isset($review['rating']) && $review['rating'] == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5)</option>
                                                        <option value="4" {{ isset($review['rating']) && $review['rating'] == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4)</option>
                                                        <option value="3" {{ isset($review['rating']) && $review['rating'] == 3 ? 'selected' : '' }}>⭐⭐⭐ (3)</option>
                                                        <option value="2" {{ isset($review['rating']) && $review['rating'] == 2 ? 'selected' : '' }}>⭐⭐ (2)</option>
                                                        <option value="1" {{ isset($review['rating']) && $review['rating'] == 1 ? 'selected' : '' }}>⭐ (1)</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger remove-review">-</button>
                                                </div>
                                                <div class="col-lg-12">
                                                    <label class="form-label">Review</label>
                                                    <textarea class="form-control"
                                                        name="customer_review[]">{{ $review['review'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="review-entry mb-3">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="form-label">Customer Name</label>
                                                <input type="text" class="form-control" name="customer_name[]">
                                            </div>

                                            <div class="col-lg-4">
                                                <label class="form-label">Rating</label>
                                                <select class="form-control" name="customer_rating[]">
                                                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                                    <option value="4">⭐⭐⭐⭐ (4)</option>
                                                    <option value="3">⭐⭐⭐ (3)</option>
                                                    <option value="2">⭐⭐ (2)</option>
                                                    <option value="1">⭐ (1)</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger remove-review">-</button>
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="form-label">Review</label>
                                                <textarea class="form-control" name="customer_review[]"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="blogTitle" class="form-label">Meta Tag</label>
                                <input type="text" class="form-control" value="{{ $expert->meta_tag ?? '' }}" name="MetaTag"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="blogTitle" class="form-label">Meta Description</label>
                                <textarea class="form-control" name="Metadescription"
                                    id="">{{ $expert->meta_description ?? '' }}</textarea>
                            </div>
                            <button type="button" class="btn btn-success mt-2 add-review">+ Add Review</button>
                            <button type="submit"
                                class="btn btn-primary">{{ isset($expert) ? 'Update' : 'Submit' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function addField(containerId, inputName) {
                let container = document.getElementById(containerId);
                let div = document.createElement("div");
                div.classList.add("input-group", "mb-2");
                div.innerHTML = `
                                <input type="text" class="form-control" name="${inputName}[]" >
                                <button type="button" class="btn btn-danger remove-field">-</button>
                            `;
                container.appendChild(div);
            }

            document.querySelector(".add-skill").addEventListener("click", function () {
                addField("skills-container", "skills");
            });

            document.querySelector(".add-help").addEventListener("click", function () {
                addField("help-container", "helpus");
            });

            document.addEventListener("click", function (e) {
                if (e.target.classList.contains("remove-field")) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function addReview(reviewData = {}) {
                let container = document.getElementById("reviews-container");
                let div = document.createElement("div");
                div.classList.add("review-entry", "mb-3");

                let nameValue = reviewData.name || "";
                let reviewText = reviewData.review || "";
                let selectedRating = reviewData.rating || "5"; // Default to 5 stars

                div.innerHTML = `
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" name="customer_name[]"  value="${nameValue}">
                        </div>

                        <div class="col-lg-4">
                            <label class="form-label">Rating</label>
                            <select class="form-control" name="customer_rating[]">
                                <option value="5" ${selectedRating == 5 ? 'selected' : ''}>⭐⭐⭐⭐⭐ (5)</option>
                                <option value="4" ${selectedRating == 4 ? 'selected' : ''}>⭐⭐⭐⭐ (4)</option>
                                <option value="3" ${selectedRating == 3 ? 'selected' : ''}>⭐⭐⭐ (3)</option>
                                <option value="2" ${selectedRating == 2 ? 'selected' : ''}>⭐⭐ (2)</option>
                                <option value="1" ${selectedRating == 1 ? 'selected' : ''}>⭐ (1)</option>
                            </select>
                        </div>

                        <div class="col-lg-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-review">-</button>
                            <button type="button" class="btn btn-secondary clone-review ms-2">⧉</button>
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label">Review</label>
                            <textarea class="form-control" name="customer_review[]" >${reviewText}</textarea>
                        </div>
                    </div>
                `;
                container.appendChild(div);
            }

            // Event delegation for dynamically added elements
            document.addEventListener("click", function (e) {
                if (e.target.classList.contains("add-review")) {
                    addReview(); // Adds a blank review
                } else if (e.target.classList.contains("remove-review")) {
                    e.target.closest(".review-entry").remove();
                } else if (e.target.classList.contains("clone-review")) {
                    let reviewEntry = e.target.closest(".review-entry");
                    let name = reviewEntry.querySelector("input[name='customer_name[]']").value;
                    let rating = reviewEntry.querySelector("select[name='customer_rating[]']").value;
                    let review = reviewEntry.querySelector("textarea[name='customer_review[]']").value;

                    addReview({ name, rating, review }); // Clone with same data
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <script>
        var quill = new Quill('#kt_docs_quill_editor', {
            theme: 'snow', // or 'bubble'
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        // Set the initial content of the Quill editor
        quill.root.innerHTML = {!! json_encode($expert->content ?? '') !!};

        // Capture Quill editor content and update the hidden input
        quill.on('text-change', function () {
            var quillContent = quill.root.innerHTML;
            document.getElementById('blogContent').value = quillContent;
        });
    </script>

@endsection