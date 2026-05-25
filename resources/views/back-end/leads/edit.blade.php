@extends('layouts.app')

@section('content')

<style>
    .form-section {
        border: 1px solid #dee2e6;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        background-color: #f8f9fa;
    }

    .form-section h5 {
        margin-bottom: 20px;
        color: #495057;
        font-weight: 600;
    }

    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(138, 145, 155, 0.8);
        z-index: 1050;
        display: none;
        justify-content: center;
        align-items: center;
    }

    .spinner-border.text-light {
        color: white;
    }
</style>

<div id="preloader">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="container mt-5">
    <h2 class="mb-4">Edit Lead <span class="text-muted">#{{ $lead->id }}</span></h2>

    <form id="leadEditForm" method="POST">
        @csrf

        {{-- User Details --}}
       {{-- User Details --}}
<div class="form-section" id="userDetailsSection">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>User Details</h5>
        <div>
            <button type="button" class="btn btn-sm btn-outline-primary" id="editUserDetailsBtn">
                <i class="fas fa-edit me-1"></i> Edit
            </button>
            <button type="button" class="btn btn-sm btn-success d-none" id="saveUserDetailsBtn">
                <i class="fas fa-save me-1"></i> Save Changes
            </button>
        </div>
    </div>

    <div id="userStatusMessage" class="mb-3"></div>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="fs-6 fw-bold mb-2">User Name</label>
            <input type="text" class="form-control user-detail" name="user_name" value="{{ $lead->user->name }}" readonly>
        </div>
        <div class="col-md-6">
            <label class="fs-6 fw-bold mb-2">Email</label>
            <input type="text" class="form-control user-detail" name="email" value="{{ $lead->user->email }}" readonly>
        </div>
        <div class="col-md-2">
            <label class="fs-6 fw-bold mb-2">Country Code</label>
            <input type="text" class="form-control user-detail" name="country_code" value="{{ $lead->user->countrycode }}" readonly>
        </div>
        <div class="col-md-3">
            <label class="fs-6 fw-bold mb-2">Mobile Number</label>
            <input type="text" class="form-control user-detail" name="mobile" value="{{ $lead->user->mobile_no }}" readonly>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-primary w-100 user-detail" id="swapButton" disabled>
                <i class="fas fa-exchange-alt"></i>
            </button>
        </div>
        <div class="col-md-2">
            <label class="fs-6 fw-bold mb-2">Country Code 2</label>
            <input type="text" class="form-control user-detail" name="country_code2" value="{{ $lead->user->countrycode2 }}" readonly>
        </div>
        <div class="col-md-3">
            <label class="fs-6 fw-bold mb-2">Mobile Number 2</label>
            <input type="text" class="form-control user-detail" name="mobile2" value="{{ $lead->user->mobile_no2 }}" readonly>
        </div>
    </div>
</div>


        {{-- Order Details --}}
        <div class="form-section">
            <h5>Order Details</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="fs-6 fw-bold mb-2">Module Code</label>
                    <input type="text" class="form-control" value="{{ $lead->module_code }}" name="module_code">
                </div>
                <div class="col-md-8">
                    <label class="fs-6 fw-bold mb-2">Project Title</label>
                    <input type="text" class="form-control" value="{{ $lead->project_title }}" name="project_title" required>
                </div>
            </div>
        </div>

        {{-- Work & Pricing --}}
        <div class="form-section">
            <h5>Work & Pricing</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="fs-6 fw-bold mb-2">Words</label>
                    <input type="number" class="form-control" value="{{ $lead->pages }}" name="pages">
                </div>
                <div class="col-md-6">
                    <label class="fs-6 fw-bold mb-2">Total Price</label>
                    <input type="number" class="form-control" value="{{ $lead->price }}" name="price">
                </div>
            </div>
        </div>

        {{-- Deadline & Draft --}}
        <div class="form-section">
            <h5>Deadline & Draft</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="fs-6 fw-bold mb-2">Delivery Date</label>
                    <input type="date" class="form-control" name="delivery_date" id="delivery_date" value="{{ $lead->deadline }}">
                </div>
                <div class="col-md-4">
                    <label class="fs-6 fw-bold mb-2">Delivery Time</label>
                    <input type="time" class="form-control" name="delivery_time" value="{{ $lead->delivery_time }}">
                </div>
                <div class="col-md-4">
                    <label class="fs-6 fw-bold mb-2">Draft Required</label>
                    <select name="draft_required" id="draft_required" class="form-select" onchange="toggleDraftFields()">
                        <option value="No" {{ $lead->draft_required !== 'Yes' ? 'selected' : '' }}>No</option>
                        <option value="Yes" {{ $lead->draft_required === 'Yes' ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mt-3" id="draftFields" style="display: none;">
                <div class="col-md-6">
                    <label class="fs-6 fw-bold mb-2">Draft Date</label>
                    <input type="date" name="draft_date" class="form-control" value="{{ $lead->draft_date }}">
                </div>
                <div class="col-md-6">
                    <label class="fs-6 fw-bold mb-2">Draft Time</label>
                    <input type="time" name="draft_time" class="form-control" value="{{ $lead->draft_time }}">
                </div>
            </div>
        </div>

        {{-- Academic Info --}}
        <div class="form-section">
            <h5>Academic Information</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="fs-6 fw-bold mb-2">Lead Status</label>
                    <select name="l_status" class="form-select">
                        <option value="">Select Status</option>
                        <option value="Waiting" {{ $lead->l_status === 'Waiting' ? 'selected' : '' }}>Waiting</option>
                        <option value="Quote" {{ $lead->l_status === 'Quote' ? 'selected' : '' }}>Quote</option>
                        <option value="Confirmation" {{ $lead->l_status === 'Confirmation' ? 'selected' : '' }}>Confirmation</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="fs-6 fw-bold mb-2">Services</label>
                    <select name="service_type" class="form-select">
                        <option value="">Select Service</option>
                        @foreach($service as $s)
                            <option value="{{ $s->service_name }}" {{ $lead->service_type === $s->service_name ? 'selected' : '' }}>{{ $s->service_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="tech" id="tech" {{ $lead->tech === 'on' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tech">Technical Work Required</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="fs-6 fw-bold mb-2">Type Of Paper</label>
                    <select name="paper" class="form-select">
                        <option value="">Not Selected</option>
                        @foreach($papers as $paper)
                            <option value="{{ $paper->paper_type }}" {{ $lead->typeofpaper === $paper->paper_type ? 'selected' : '' }}>{{ $paper->paper_type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="fs-6 fw-bold mb-2">Semester</label>
                     <select name="semester" class="form-select">
                        <option value="">Select Semester</option>
                        <option value="Semester I" {{ $lead->semester == 'Semester I' ? 'selected' : '' }}>Semester I</option>
                        <option value="Semester II" {{ $lead->semester == 'Semester II' ? 'selected' : '' }}>Semester II</option>
                        <option value="Semester III" {{ $lead->semester == 'Semester III' ? 'selected' : '' }}>Semester III</option>
                        <option value="Semester IV" {{ $lead->semester == 'Semester IV' ? 'selected' : '' }}>Semester IV</option>
                        <option value="Semester V" {{ $lead->semester == 'Semester V' ? 'selected' : '' }}>Semester V</option>
                        <option value="Final Semester" {{ $lead->semester == 'Final Semester' ? 'selected' : '' }}>Final Semester</option>
                    </select>

                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="resit" id="resit" {{ $lead->resit === 'on' ? 'checked' : '' }}>
                        <label class="form-check-label" for="resit">Resit Required</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Update Lead</button>
        </div>

        <div id="statusMessage" class="mt-3"></div>
    </form>
</div>

<script>
    function toggleDraftFields() {
        const show = document.getElementById('draft_required').value === 'Yes';
        document.getElementById('draftFields').style.display = show ? 'flex' : 'none';
    }

    function isFutureOrToday(dateStr) {
        const inputDate = new Date(dateStr);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return inputDate >= today;
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleDraftFields();

        const form = document.getElementById('leadEditForm');
        const preloader = document.getElementById('preloader');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const deliveryDate = document.getElementById('delivery_date').value;

            if (deliveryDate && !isFutureOrToday(deliveryDate)) {
                document.getElementById('statusMessage').innerHTML = `<div class="alert alert-danger">Delivery date cannot be in the past.</div>`;
                return;
            }

            preloader.style.display = 'flex';

            const formData = new FormData(form);
            formData.set('tech', form.tech.checked ? 'on' : 'off');
            formData.set('resit', form.resit.checked ? 'on' : 'off');

            try {
                const url = "{{ route('lead.update', $lead->id) }}";

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();
                const statusMessage = document.getElementById('statusMessage');

                if (response.ok && result.success) {
                    statusMessage.innerHTML = `<div class="alert alert-success">Lead updated successfully!</div>`;
                } else {
                    statusMessage.innerHTML = `<div class="alert alert-danger">Error updating lead.</div>`;
                }
            } catch (error) {
                document.getElementById('statusMessage').innerHTML = `<div class="alert alert-danger">An unexpected error occurred.</div>`;
            } finally {
                preloader.style.display = 'none';
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editBtn = document.getElementById('editUserDetailsBtn');
        const saveBtn = document.getElementById('saveUserDetailsBtn');
        const fields = document.querySelectorAll('.user-detail');
        const swapBtn = document.getElementById('swapButton');
        const statusDiv = document.getElementById('userStatusMessage');
        let originalValues = {};

        // Save original values for cancel
        fields.forEach(field => {
            if (field.tagName === 'INPUT') {
                originalValues[field.name] = field.value;
            }
        });

        let isEditing = false;

        editBtn.addEventListener('click', () => {
            isEditing = !isEditing;

            fields.forEach(field => {
                if (field.tagName === 'BUTTON') {
                    field.disabled = !isEditing;
                } else {
                    field.readOnly = !isEditing;
                }
            });

            if (isEditing) {
                editBtn.innerHTML = '<i class="fas fa-times me-1"></i> Cancel';
                saveBtn.classList.remove('d-none');
            } else {
                // Reset values on cancel
                fields.forEach(field => {
                    if (field.tagName === 'INPUT' && originalValues[field.name]) {
                        field.value = originalValues[field.name];
                    }
                });

                editBtn.innerHTML = '<i class="fas fa-edit me-1"></i> Edit';
                saveBtn.classList.add('d-none');
            }
        });

        saveBtn.addEventListener('click', async () => {
            const formData = new FormData();

            fields.forEach(field => {
                if (field.name) {
                    formData.append(field.name, field.value);
                }
            });

            try {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

                const response = await fetch(`{{ route('lead.update.user', $lead->user->id) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    statusDiv.innerHTML = '<div class="alert alert-success">User details updated successfully.</div>';
                    // Update original values
                    fields.forEach(field => {
                        if (field.name) {
                            originalValues[field.name] = field.value;
                        }
                    });

                    isEditing = false;
                    fields.forEach(field => {
                        if (field.tagName === 'BUTTON') {
                            field.disabled = true;
                        } else {
                            field.readOnly = true;
                        }
                    });
                    editBtn.innerHTML = '<i class="fas fa-edit me-1"></i> Edit';
                    saveBtn.classList.add('d-none');
                } else {
                    statusDiv.innerHTML = '<div class="alert alert-danger">Failed to update user details.</div>';
                }
            } catch (error) {
                statusDiv.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Changes';
            }
        });

        // Optional: Swap phone numbers
        swapBtn.addEventListener('click', () => {
            if (!isEditing) return;

            const mobile1 = document.querySelector('input[name="mobile"]');
            const code1 = document.querySelector('input[name="country_code"]');
            const mobile2 = document.querySelector('input[name="mobile2"]');
            const code2 = document.querySelector('input[name="country_code2"]');

            [mobile1.value, mobile2.value] = [mobile2.value, mobile1.value];
            [code1.value, code2.value] = [code2.value, code1.value];
        });
    });
</script>


@endsection
