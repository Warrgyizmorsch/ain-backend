@extends('layouts.app')

@section('content')
    @php
        $isEdit = isset($review) && $review;
        $selectedService = old('services_type', $review->services_type ?? '');
        $selectedLocation = old('location', $review->location ?? '');
        $selectedRating = (int) old('customer_rating', $review->customer_rating ?? 5);
        $submissionDate = old('submission_date', optional($review->submission_date ?? null)->format('Y-m-d'));
    @endphp

    <div class="docs-content d-flex flex-column flex-column-fluid" id="kt_docs_content">
        <div class="container" id="kt_docs_content_container">
            <div class="card card-docs mb-2">
                <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h2 class="mb-0">{{ $isEdit ? 'Edit Prime Review' : 'New Prime Review' }}</h2>
                        <a href="{{ route('prime-reviews.index') }}" class="btn btn-sm btn-light">Prime Review List</a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
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

                    <form action="{{ $isEdit ? route('prime-reviews.update', $review->id) : route('prime-reviews.store') }}" method="post">
                        @csrf
                        @if($isEdit)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="name" class="form-label required">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    value="{{ old('name', $review->name ?? '') }}">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="deadline" class="form-label">Deadline (in Days)</label>
                                <input type="text" class="form-control" id="deadline" name="deadline"
                                    value="{{ old('deadline', $review->deadline ?? '') }}">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="submission_date" class="form-label">Submission Date</label>
                                <input type="date" class="form-control" id="submission_date" name="submission_date"
                                    value="{{ $submissionDate }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="services_type" class="form-label">Type of Services</label>
                                <select id="services_type" name="services_type" class="form-select">
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

                            <div class="col-lg-4 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <select id="location" name="location" class="form-select">
                                    <option value="">Select Location</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc }}" @selected($selectedLocation === $loc)>{{ $loc }}</option>
                                    @endforeach
                                    @if($selectedLocation && !in_array($selectedLocation, $locations, true))
                                        <option value="{{ $selectedLocation }}" selected>{{ $selectedLocation }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="customer_rating" class="form-label">Rating</label>
                                <select class="form-control" id="customer_rating" name="customer_rating">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" @selected($selectedRating === $i)>{{ str_repeat('⭐', $i) }} ({{ $i }})</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="page" class="form-label">Show on Page</label>
                                <input type="text" class="form-control" id="page" name="page" list="prime-review-pages"
                                    placeholder="e.g. Homepage, London, Essay Writing Service"
                                    value="{{ old('page', $review->page ?? '') }}">
                                <datalist id="prime-review-pages">
                                    @foreach($pages as $p)
                                        <option value="{{ $p }}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="university" class="form-label">University</label>
                                <input type="text" class="form-control" id="university" name="university"
                                    value="{{ old('university', $review->university ?? '') }}">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="course" class="form-label">Course</label>
                                <input type="text" class="form-control" id="course" name="course"
                                    value="{{ old('course', $review->course ?? '') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $review->description ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="review_reply" class="form-label">Review Reply</label>
                            <textarea class="form-control" id="review_reply" name="review_reply" rows="3">{{ old('review_reply', $review->review_reply ?? '') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Submit' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
