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
                    <form id="reviewForm"
                        action="{{ isset($review) ? route('new-review.update', $review->id) : route('new-review.store') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        @isset($review)
                            @method('PUT')
                        @endisset

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="name" class="form-label">Name</label> 
                                <input type="text" class="form-control" id="name" name="name"  required
                                    value="{{ $review->name ?? '' }}">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="finish_order" class="form-label">Deadline (in Days)</label>
                                <input type="text" class="form-control" id="finish_order" name="deadline" 
                                    value="{{ $review->deadline ?? '' }}">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="inprogress_order" class="form-label">Submission Date</label>
                                <input type="date" class="form-control" id="inprogress_order" name="submission_date" 
                                    value="{{ $review->submission_date ?? '' }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="services" class="form-label">Type of Services</label>
                                <select id="services" name="services_type" class="form-select">
                                    <option value="" {{ empty($review->services_type) ? 'selected' : '' }}>Select Service</option>
                                    @php
                                        $services = [
                                            'NULL', 'Assignment', 'Essay', 'Homework', 'Academic', 'Coursework', 'University ',
                                            'Thesis', 'Summary', 'Term Paper', 'Personal statement', 'Literature review ',
                                            'Resume Writing', 'Case study', 'Proofreading & Editing', 'Online Exam',
                                            'Dissertation', 'Research Paper',
                                        ];
                                    @endphp
                                    @foreach($services as $service)
                                        <option value="{{ $service }}" {{ isset($review->services_type) && $review->services_type === $service ? 'selected' : '' }}>
                                            {{ $service }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <select id="location" name="location" class="form-select">
                                    <option value="" {{ empty($review->location) ? 'selected' : '' }}>Select Location</option>
                                    @php
                                        $locations = ['Uk', 'London', 'Birmingham', 'Manchester', 'AU', 'Sydney', 'Melbourne', 'Barcelona', 'CANADA', 'UAE', 'Spain' ,'Liverpool','Leeds','Newcastle upon Tyne','Sheffield',
                                            'Bristol','Nottingham','Leicester','Southampton','Portsmouth', 'Portsmouth', 'Cambridge', 'Oxford',
                                            'Edinburgh', 'Glasgow', 'Aberdeen'];
                                    @endphp
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc }}" {{ isset($review->location) && $review->location === $loc ? 'selected' : '' }}>
                                            {{ $loc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Rating</label>
                                <select class="form-control" name="customer_rating">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ isset($review['customer_rating']) && $review['customer_rating'] == $i ? 'selected' : '' }}>
                                            {{ str_repeat('⭐', $i) }} ({{ $i }})
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description">{{ $review->description ?? '' }}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="Review Replay" class="form-label">Review Reply</label>
                            <textarea class="form-control" id="review_reply" name="review_reply">{{ $review->review_reply ?? '' }}</textarea>
                        </div>


                        <button type="submit" class="btn btn-primary">
                            {{ isset($review) ? 'Update' : 'Submit' }}
                        </button>
                    </form>
                </div>
            </div>

            </div>
        </div>
    </div>


@endsection