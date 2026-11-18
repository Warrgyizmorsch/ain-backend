@extends('layouts.app')

@section('content')
    @php
        $isEdit = isset($expert) && $expert;
        $selectedSubject = old('subject', $expert->subject ?? '');
        $selectedService = old('services', $expert->service ?? '');
        $selectedLocation = old('location', $expert->location ?? '');
        $skills = old('skills', $expert->skills ?? []) ?: [''];
        $helps = old('helpus', $expert->helpus ?? []) ?: [''];

        if (old('customer_name')) {
            $customerReviews = [];
            foreach (old('customer_name') as $i => $n) {
                $customerReviews[] = [
                    'name' => $n,
                    'rating' => old('customer_rating')[$i] ?? 5,
                    'review' => old('customer_review')[$i] ?? '',
                ];
            }
        } else {
            $customerReviews = $expert->customer_review ?? [];
            if (is_string($customerReviews)) {
                $customerReviews = json_decode($customerReviews, true) ?: [];
            }
        }
        if (empty($customerReviews)) {
            $customerReviews = [['name' => '', 'rating' => 5, 'review' => '']];
        }

        $locations = ['Uk', 'London', 'Birmingham', 'Manchester', 'AU', 'Sydney', 'Melbourne', 'Barcelona', 'CANADA', 'UAE', 'Spain'];
        $imageUrl = !empty($expert->image) ? asset($expert->image) : asset('assets/media/avatars/blank.png');
    @endphp

    <style>
        #kt_docs_quill_editor { height: 300px; }
    </style>

    <div class="docs-content d-flex flex-column flex-column-fluid" id="kt_docs_content">
        <div class="container" id="kt_docs_content_container">
            <div class="card card-docs mb-2">
                <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h2 class="mb-0">{{ $isEdit ? 'Edit Prime Expert' : 'Create Prime Expert' }}</h2>
                        <a href="{{ route('prime-experts.index') }}" class="btn btn-sm btn-light">Back to list</a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="primeExpertForm"
                        action="{{ $isEdit ? route('prime-experts.update', $expert->id) : route('prime-experts.store') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        @if($isEdit)
                            @method('PUT')
                        @endif

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">Profile</label>
                            <div class="col-lg-8">
                                <div class="image-input image-input-outline" data-kt-image-input="true"
                                    style="background-image: url({{ asset('assets/media/avatars/blank.png') }})">
                                    <div class="image-input-wrapper w-125px h-125px"
                                        style="width: 200px !important; height:150px; background-image: url({{ $imageUrl }})">
                                    </div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="photo" accept=".png, .jpg, .jpeg, .webp">
                                        <input type="hidden" name="avatar_remove">
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>
                                <div class="form-text">Allowed file types: png, jpg, jpeg, webp.</div>

                                <div class="mt-4">
                                    <label for="image_alt" class="form-label required">Image Alt Text</label>
                                    <input type="text" class="form-control @error('image_alt') is-invalid @enderror"
                                        id="image_alt" name="image_alt" required maxlength="255"
                                        placeholder="e.g. John Smith - Prime Assignment Expert"
                                        value="{{ old('image_alt', $expert->image_alt ?? '') }}">
                                    @error('image_alt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <label for="name" class="form-label required">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    value="{{ old('name', $expert->name ?? '') }}">
                            </div>
                            <div class="col-lg-3">
                                <label for="finish_order" class="form-label">Finish Order</label>
                                <input type="text" class="form-control" id="finish_order" name="finish_order"
                                    value="{{ old('finish_order', $expert->finish_order ?? '') }}">
                            </div>
                            <div class="col-lg-3">
                                <label for="inprogress_order" class="form-label">In-progress Order</label>
                                <input type="text" class="form-control" id="inprogress_order" name="inprogress_order"
                                    value="{{ old('inprogress_order', $expert->inprogress_order ?? '') }}">
                            </div>
                            <div class="col-lg-2">
                                <label for="success" class="form-label">Success Rate %</label>
                                <input type="text" class="form-control" id="success" name="success"
                                    value="{{ old('success', $expert->success ?? '') }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-2">
                                <label for="gender" class="form-label">Gender</label>
                                <select id="gender" name="gender" class="form-select">
                                    <option value="">Select</option>
                                    @foreach(['Male', 'Female', 'Other'] as $g)
                                        <option value="{{ $g }}" @selected(old('gender', $expert->gender ?? '') === $g)>{{ $g }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-5">
                                <label for="degree" class="form-label">Degree</label>
                                <input type="text" class="form-control" id="degree" name="degree"
                                    placeholder="e.g. PhD in Strategic Management, University of Manchester"
                                    value="{{ old('degree', $expert->degree ?? '') }}">
                            </div>
                            <div class="col-lg-3">
                                <label for="experience" class="form-label">Experience</label>
                                <input type="text" class="form-control" id="experience" name="experience"
                                    placeholder="e.g. 10 years"
                                    value="{{ old('experience', $expert->experience ?? '') }}">
                            </div>
                            <div class="col-lg-2">
                                <label for="rating" class="form-label">Rating (0-5)</label>
                                <input type="number" step="0.1" min="0" max="5" class="form-control" id="rating" name="rating"
                                    value="{{ old('rating', $expert->rating ?? '') }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="mb-3 col-lg-4">
                                <label for="subject" class="form-label">Subject</label>
                                <select id="subject" name="subject" class="form-select">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject }}" @selected($selectedSubject === $subject)>{{ $subject }}</option>
                                    @endforeach
                                    @if($selectedSubject && !$subjects->contains($selectedSubject))
                                        <option value="{{ $selectedSubject }}" selected>{{ $selectedSubject }} (old)</option>
                                    @endif
                                </select>
                                @if($subjects->isEmpty())
                                    <div class="form-text text-warning">No Prime subject pages found. Add them in Prime Assignment &rarr; Dynamic Pages (type: subject).</div>
                                @endif
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label for="services" class="form-label">Services</label>
                                <select id="services" name="services" class="form-select">
                                    <option value="">Select Service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service }}" @selected($selectedService === $service)>{{ $service }}</option>
                                    @endforeach
                                    @if($selectedService && !$services->contains($selectedService))
                                        <option value="{{ $selectedService }}" selected>{{ $selectedService }} (old)</option>
                                    @endif
                                </select>
                                @if($services->isEmpty())
                                    <div class="form-text text-warning">No Prime service pages found. Add them in Prime Assignment &rarr; Dynamic Pages (type: service).</div>
                                @endif
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label for="location" class="form-label">Location</label>
                                <select id="location" name="location" class="form-select">
                                    <option value="">Select Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location }}" @selected($selectedLocation === $location)>{{ $location }}</option>
                                    @endforeach
                                    @if($selectedLocation && !in_array($selectedLocation, $locations, true))
                                        <option value="{{ $selectedLocation }}" selected>{{ $selectedLocation }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <label class="form-label">Skills</label>
                                <div id="skills-container">
                                    @foreach($skills as $skill)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="skills[]" value="{{ $skill }}">
                                            <button type="button" class="btn btn-danger remove-field">-</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-success mt-2 add-skill">+ Add Skill</button>
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label">Help with is</label>
                                <div id="help-container">
                                    @foreach($helps as $help)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="helpus[]" value="{{ $help }}">
                                            <button type="button" class="btn btn-danger remove-field">-</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-success mt-2 add-help">+ Add Help</button>
                            </div>
                        </div>

                        <div class="mb-3 mt-4">
                            <label class="form-label">Description</label>
                            <div id="kt_docs_quill_editor"></div>
                            <input type="hidden" id="expertContent" name="description"
                                value="{{ old('description', $expert->content ?? '') }}">
                        </div>

                        <h2>Customer Reviews</h2>
                        <div id="reviews-container">
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
                                                @for($r = 5; $r >= 1; $r--)
                                                    <option value="{{ $r }}" @selected((int) ($review['rating'] ?? 5) === $r)>{{ str_repeat('⭐', $r) }} ({{ $r }})</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-lg-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-review">-</button>
                                        </div>
                                        <div class="col-lg-12">
                                            <label class="form-label">Review</label>
                                            <textarea class="form-control" name="customer_review[]">{{ $review['review'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="MetaTag" class="form-label mb-0 fw-bold required">Meta Tag</label>
                                <span class="meta-char-counter-badge" id="MetaTag-counter">
                                    <span class="char-count">{{ mb_strlen(old('MetaTag', $expert->meta_tag ?? '')) }}</span> characters
                                </span>
                            </div>
                            <input type="text" class="form-control" id="MetaTag" name="MetaTag" required
                                value="{{ old('MetaTag', $expert->meta_tag ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="Metadescription" class="form-label mb-0 fw-bold required">Meta Description</label>
                                <span class="meta-char-counter-badge" id="Metadescription-counter">
                                    <span class="char-count">{{ mb_strlen(old('Metadescription', $expert->meta_description ?? '')) }}</span> characters
                                </span>
                            </div>
                            <textarea class="form-control" name="Metadescription" id="Metadescription" rows="3" required>{{ old('Metadescription', $expert->meta_description ?? '') }}</textarea>
                        </div>

                        <button type="button" class="btn btn-success mt-2 add-review">+ Add Review</button>
                        <button type="submit" class="btn btn-primary mt-2">{{ $isEdit ? 'Update' : 'Submit' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function addField(containerId, inputName) {
                const div = document.createElement("div");
                div.classList.add("input-group", "mb-2");
                div.innerHTML = `<input type="text" class="form-control" name="${inputName}[]">
                                 <button type="button" class="btn btn-danger remove-field">-</button>`;
                document.getElementById(containerId).appendChild(div);
            }

            function addReview() {
                const div = document.createElement("div");
                div.classList.add("review-entry", "mb-3");
                div.innerHTML = `
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
                    </div>`;
                document.getElementById("reviews-container").appendChild(div);
            }

            document.addEventListener("click", function (e) {
                if (e.target.classList.contains("add-skill")) addField("skills-container", "skills");
                else if (e.target.classList.contains("add-help")) addField("help-container", "helpus");
                else if (e.target.classList.contains("remove-field")) e.target.parentElement.remove();
                else if (e.target.classList.contains("add-review")) addReview();
                else if (e.target.classList.contains("remove-review")) e.target.closest(".review-entry").remove();
            });

            // Live character counters
            ["MetaTag", "Metadescription"].forEach(function (id) {
                const el = document.getElementById(id);
                const counter = document.querySelector("#" + id + "-counter .char-count");
                if (el && counter) el.addEventListener("input", () => counter.textContent = el.value.length);
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <script>
        var quill = new Quill('#kt_docs_quill_editor', {
            theme: 'snow',
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

        var contentInput = document.getElementById('expertContent');
        quill.root.innerHTML = contentInput.value || '';
        quill.on('text-change', function () {
            contentInput.value = quill.root.innerHTML;
        });
        document.getElementById('primeExpertForm').addEventListener('submit', function () {
            contentInput.value = quill.getText().trim().length ? quill.root.innerHTML : '';
        });
    </script>
@endsection
