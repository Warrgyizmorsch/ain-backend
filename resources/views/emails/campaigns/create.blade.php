@extends('layouts.app')

@push('head')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
.audience-tab-card {
    border: 2px solid #edf2f7;
    border-radius: 10px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.audience-tab-card.active, .audience-tab-card:hover {
    border-color: #0b57d0;
    background-color: #f8faff;
}
.merge-tag-badge {
    cursor: pointer;
    background: #eef2ff;
    color: #4338ca;
    border: 1px solid #c7d2fe;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    display: inline-block;
    transition: all 0.15s;
}
.merge-tag-badge:hover {
    background: #4338ca;
    color: #fff;
}
#quillEditor .ql-editor img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    margin: 8px 0;
    display: inline-block;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h1 class="fs-2 fw-bolder text-dark mb-1">
                <i class="fa fa-plus-circle text-primary me-2"></i>Create Email Campaign
            </h1>
            <div class="text-muted fw-bold fs-7">
                Targeted bulk email campaign with merge tags, scheduling, and anti-spam rate limiting.
            </div>
        </div>
        <a href="{{ route('emails.campaigns.index') }}" class="btn btn-sm btn-light">
            <i class="fa fa-arrow-left me-1"></i> Back to Campaigns
        </a>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="campaignForm" method="POST" action="{{ route('emails.campaigns.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-5">
            <!-- Left Column: Setup & Audience -->
            <div class="col-lg-5">
                <!-- 1. Campaign Basics Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header border-0 pt-4 px-5">
                        <h4 class="fw-bolder text-dark mb-0 fs-6">
                            <span class="badge badge-circle badge-primary text-white me-2">1</span> Campaign Information
                        </h4>
                    </div>
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <label class="form-label fw-bold required">Campaign Name</label>
                            <input type="text" name="name" class="form-control form-control-solid" placeholder="e.g. Diwali Offer 2026 / Inactive Leads Followup" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold required">Sending Account (SMTP)</label>
                            <select name="email_configuration_id" class="form-select form-select-solid" required>
                                @foreach($emailConfigurations as $acc)
                                    <option value="{{ $acc->id }}" {{ $acc->is_default ? 'selected' : '' }}>
                                        {{ $acc->name }} ({{ $acc->email_address }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-muted fs-8 mt-1">Configured in Setting > Email Settings</div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-bold required">Email Subject Line</label>
                            <input type="text" id="campaignSubject" name="subject" class="form-control form-control-solid" placeholder="e.g. Exclusive 20% discount on your next assignment, @{{name}}" value="{{ old('subject') }}" required>
                            <div class="text-muted fs-8 mt-1">Tip: Use <code>@{{name}}</code> in subject for higher open rates.</div>
                        </div>
                    </div>
                </div>

                <!-- 2. Audience Selection Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header border-0 pt-4 px-5 d-flex align-items-center justify-content-between">
                        <h4 class="fw-bolder text-dark mb-0 fs-6">
                            <span class="badge badge-circle badge-primary text-white me-2">2</span> Target Audience
                        </h4>
                        <span id="audienceBadge" class="badge badge-light-success fw-bold fs-8">
                            <i class="fa fa-users me-1 text-success"></i><span id="audienceCountText">Calculating...</span>
                        </span>
                    </div>
                    <div class="card-body p-5">
                        <input type="hidden" name="audience_type" id="audienceTypeInput" value="all_users">

                        <!-- Audience Tabs -->
                        <div class="row g-2 mb-4">
                            <div class="col-12">
                                <div class="audience-tab-card active text-center py-3" onclick="setAudienceType('all_users', this)">
                                    <i class="fa fa-users text-primary fs-2 mb-1"></i>
                                    <div class="fw-bolder fs-6 text-dark">All CRM Users</div>
                                    <div class="text-muted fs-8">Send to all registered customers / user accounts in CRM</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="audience-tab-card text-center" onclick="setAudienceType('leads', this)">
                                    <i class="fa fa-user-friends text-info fs-3 mb-1"></i>
                                    <div class="fw-bold fs-7">Leads Only</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="audience-tab-card text-center" onclick="setAudienceType('orders', this)">
                                    <i class="fa fa-shopping-bag text-success fs-3 mb-1"></i>
                                    <div class="fw-bold fs-7">Orders / Clients</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="audience-tab-card text-center" onclick="setAudienceType('writers', this)">
                                    <i class="fa fa-pen-nib text-warning fs-3 mb-1"></i>
                                    <div class="fw-bold fs-7">Writers</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="audience-tab-card text-center" onclick="setAudienceType('csv', this)">
                                    <i class="fa fa-file-csv text-secondary fs-3 mb-1"></i>
                                    <div class="fw-bold fs-7">CSV Upload</div>
                                </div>
                            </div>
                        </div>

                        <!-- All CRM Users Panel (Active by default) -->
                        <div id="allUsersFilterPanel" class="audience-panel">
                            <div class="mb-3">
                                <label class="form-label fw-bold fs-8 text-uppercase">Target Scope</label>
                                <select name="filters[user_scope]" class="form-select form-select-sm form-select-solid audience-filter-input" onchange="updateAudienceCount()">
                                    <option value="clients" selected>All CRM Clients & Customers (role_id: 2 - Recommended)</option>
                                    <option value="all">Entire CRM Database (All Registered Accounts)</option>
                                </select>
                                <div class="text-muted fs-9 mt-1">Targets valid email addresses in the CRM users table.</div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-bold fs-8">Registered From</label>
                                    <input type="date" name="filters[date_from]" class="form-control form-control-sm form-control-solid audience-filter-input" onchange="updateAudienceCount()">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold fs-8">Registered To</label>
                                    <input type="date" name="filters[date_to]" class="form-control form-control-sm form-control-solid audience-filter-input" onchange="updateAudienceCount()">
                                </div>
                            </div>
                        </div>

                        <!-- Leads Filters Panel (Hidden by default) -->
                        <div id="leadsFilterPanel" class="audience-panel d-none">
                            <div class="mb-3">
                                <label class="form-label fw-bold fs-8 text-uppercase">Lead Status</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($leadStatuses as $status)
                                        <div class="form-check form-check-custom form-check-solid form-check-sm">
                                            <input class="form-check-input audience-filter-input" type="checkbox" name="filters[statuses][]" value="{{ $status }}" id="st_{{ $status }}" checked onchange="updateAudienceCount()">
                                            <label class="form-check-label fs-8 text-capitalize" for="st_{{ $status }}">{{ $status }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-bold fs-8">From Date</label>
                                    <input type="date" name="filters[date_from]" class="form-control form-control-sm form-control-solid audience-filter-input" onchange="updateAudienceCount()">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold fs-8">To Date</label>
                                    <input type="date" name="filters[date_to]" class="form-control form-control-sm form-control-solid audience-filter-input" onchange="updateAudienceCount()">
                                </div>
                            </div>
                        </div>

                        <!-- Orders Filters Panel (Hidden by default) -->
                        <div id="ordersFilterPanel" class="audience-panel d-none">
                            <div class="mb-3">
                                <label class="form-label fw-bold fs-8 text-uppercase">Order Status</label>
                                <select name="filters[order_status]" class="form-select form-select-sm form-select-solid audience-filter-input" onchange="updateAudienceCount()">
                                    <option value="">All Orders</option>
                                    <option value="Completed">Completed Orders</option>
                                    <option value="In Progress">In Progress Orders</option>
                                    <option value="Confirmed">Confirmed Orders</option>
                                </select>
                            </div>
                        </div>

                        <!-- Writers Filters Panel (Hidden by default) -->
                        <div id="writersFilterPanel" class="audience-panel d-none">
                            <p class="text-muted fs-8 mb-2">Target all registered assignment writers.</p>
                        </div>

                        <!-- CSV Upload Panel (Hidden by default) -->
                        <div id="csvFilterPanel" class="audience-panel d-none">
                            <label class="form-label fw-bold fs-8">Upload CSV File (Column: email, name)</label>
                            <input type="file" name="csv_file" class="form-control form-control-sm form-control-solid" accept=".csv, .txt">
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <span class="text-muted fs-8">Live database estimate</span>
                            <button type="button" class="btn btn-xs btn-light-primary fw-bold" onclick="updateAudienceCount()">
                                <i class="fa fa-sync me-1"></i> Refresh Count
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 3. Dispatch & Throttle Options Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header border-0 pt-4 px-5">
                        <h4 class="fw-bolder text-dark mb-0 fs-6">
                            <span class="badge badge-circle badge-primary text-white me-2">3</span> Sending & Throttling
                        </h4>
                    </div>
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <label class="form-label fw-bold required">When to send?</label>
                            <div class="d-flex flex-column gap-2">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="send_action" value="now" id="sendNowRadio" checked onchange="toggleScheduleInput(false)">
                                    <label class="form-check-label fw-bold text-dark fs-7" for="sendNowRadio">
                                        Send Immediately
                                        <div class="text-muted fs-9 fw-normal">Starts processing in background via queue</div>
                                    </label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="send_action" value="schedule" id="sendScheduleRadio" onchange="toggleScheduleInput(true)">
                                    <label class="form-check-label fw-bold text-dark fs-7" for="sendScheduleRadio">
                                        Schedule for Later
                                    </label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="send_action" value="draft" id="sendDraftRadio" onchange="toggleScheduleInput(false)">
                                    <label class="form-check-label fw-bold text-dark fs-7" for="sendDraftRadio">
                                        Save as Draft (Don't send)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Date/Time picker -->
                        <div id="scheduleTimeContainer" class="mb-4 d-none">
                            <label class="form-label fw-bold fs-8">Schedule Date & Time</label>
                            <input type="datetime-local" name="scheduled_at" class="form-control form-control-solid">
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold fs-8 mb-0">Sending Speed (Anti-Spam Throttling)</label>
                                <span id="throttleLabel" class="badge badge-light-primary fw-bold fs-9">30 emails / min</span>
                            </div>
                            <input type="range" class="form-range" name="throttle_rate" min="5" max="150" step="5" value="30" oninput="$('#throttleLabel').text(this.value + ' emails / min')">
                            <div class="text-muted fs-9">Lower speeds ensure maximum inbox delivery and avoid SMTP spam flags.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Email Content & Rich Editor -->
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="card-header border-0 pt-4 px-6 d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <h4 class="fw-bolder text-dark mb-0 fs-6">
                                <span class="badge badge-circle badge-primary text-white me-2">4</span> Email Content & Personalization
                            </h4>
                            <div class="d-flex align-items-center gap-2">
                                <select id="templateSelector" class="form-select form-select-sm form-select-solid" onchange="loadTemplate(this.value)" style="width: 200px;">
                                    <option value="">Load Template...</option>
                                    @foreach($templates as $tmpl)
                                        <option value="{{ $tmpl->id }}" data-subject="{{ $tmpl->subject }}" data-body="{{ htmlspecialchars($tmpl->body_html) }}">
                                            {{ $tmpl->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="card-body p-6">
                            <!-- Dynamic Merge Tags -->
                            <div class="mb-3 p-3 bg-light rounded border">
                                <div class="fs-8 fw-bold text-gray-700 mb-2">Click to insert dynamic merge tag:</div>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="merge-tag-badge" onclick="insertMergeTag('@{{name}}')"><i class="fa fa-user me-1"></i>@{{name}}</span>
                                    <span class="merge-tag-badge" onclick="insertMergeTag('@{{email}}')"><i class="fa fa-envelope me-1"></i>@{{email}}</span>
                                    <span class="merge-tag-badge" onclick="insertMergeTag('@{{phone}}')"><i class="fa fa-phone me-1"></i>@{{phone}}</span>
                                </div>
                            </div>

                            <!-- Rich Text Editor Container -->
                            <div class="mb-4">
                                <label class="form-label fw-bold required">Email Body (HTML)</label>
                                <div id="quillEditor" style="height: 350px; background: #fff;"></div>
                                <textarea name="body_html" id="bodyHtmlInput" style="display:none;"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer p-6 border-top d-flex justify-content-between align-items-center bg-light">
                        <a href="{{ route('emails.campaigns.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" id="submitCampaignBtn" class="btn btn-primary fw-bold px-6">
                            <i class="fa fa-paper-plane me-1"></i> Save & Launch Campaign
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
let quill;
document.addEventListener('DOMContentLoaded', function() {
    quill = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Write your email content here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image', 'clean']
            ]
        }
    });

    // Custom toolbar image button handler
    quill.getModule('toolbar').addHandler('image', function() {
        selectAndUploadImage();
    });

    // Support drag-and-drop image upload directly into editor
    quill.root.addEventListener('drop', function(e) {
        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
            const file = e.dataTransfer.files[0];
            if (file.type && file.type.match(/^image\//)) {
                e.preventDefault();
                uploadCampaignImage(file);
            }
        }
    });

    // Support paste image (e.g. screenshot via Ctrl+V)
    quill.root.addEventListener('paste', function(e) {
        if (e.clipboardData && e.clipboardData.files && e.clipboardData.files.length) {
            const file = e.clipboardData.files[0];
            if (file.type && file.type.match(/^image\//)) {
                e.preventDefault();
                uploadCampaignImage(file);
            }
        }
    });

    // Form submit validation & sync
    $('#campaignForm').on('submit', function(e) {
        // 1. Sync Quill editor HTML into hidden textarea
        let htmlContent = '';
        let textContent = '';
        if (quill) {
            htmlContent = (quill.root.innerHTML || '').trim();
            textContent = (quill.getText() || '').trim();
        }

        // 2. Validate Campaign Name
        const nameInput = $('input[name="name"]');
        if (!nameInput.val() || !nameInput.val().trim()) {
            e.preventDefault();
            alert('Please enter a Campaign Name before launching.');
            nameInput.focus();
            return false;
        }

        // 3. Validate Subject Line
        const subjectInput = $('#campaignSubject');
        if (!subjectInput.val() || !subjectInput.val().trim()) {
            e.preventDefault();
            alert('Please enter an Email Subject Line.');
            subjectInput.focus();
            return false;
        }

        // 4. Validate Email Body
        const hasContent = textContent.length > 0 || htmlContent.includes('<img') || htmlContent.includes('<table');
        if (!hasContent || htmlContent === '<p><br></p>') {
            e.preventDefault();
            alert('Please enter email content or insert an image into the Email Body.');
            if (quill) quill.focus();
            return false;
        }

        $('#bodyHtmlInput').val(htmlContent);

        // 5. Validate Audience: if CSV is selected, ensure a file is chosen
        const audienceType = $('#audienceTypeInput').val();
        if (audienceType === 'csv') {
            const csvInput = $('input[name="csv_file"]')[0];
            if (!csvInput || !csvInput.files || !csvInput.files.length) {
                e.preventDefault();
                alert('Please choose a CSV file with recipient email addresses.');
                $('input[name="csv_file"]').focus();
                return false;
            }
        }

        // 6. Show spinner on button
        const submitBtn = $('#submitCampaignBtn');
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Saving & Launching...');
        return true;
    });

    updateAudienceCount();
});

function selectAndUploadImage() {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml');
    input.click();

    input.onchange = function() {
        const file = input.files[0];
        if (file) {
            uploadCampaignImage(file);
        }
    };
}

function uploadCampaignImage(file) {
    if (!file) return;
    if (file.size > 10 * 1024 * 1024) {
        alert('Image size exceeds 10MB limit.');
        return;
    }

    const range = quill.getSelection(true) || { index: quill.getLength() };
    const loadingText = '[ Uploading image... ]';
    quill.insertText(range.index, loadingText, { 'italic': true, 'color': '#0b57d0' });

    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: "{{ route('emails.campaigns.upload-image') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        success: function(res) {
            quill.deleteText(range.index, loadingText.length);
            if (res.success && res.url) {
                quill.insertEmbed(range.index, 'image', res.url);
                quill.setSelection(range.index + 1);
            } else {
                alert(res.message || 'Image upload failed.');
            }
        },
        error: function(xhr) {
            quill.deleteText(range.index, loadingText.length);
            let errMsg = 'Failed to upload image.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errMsg = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.image) {
                errMsg = xhr.responseJSON.errors.image[0];
            } else if (xhr.status === 419) {
                errMsg = 'Session expired. Please refresh the page.';
            } else if (xhr.status === 413) {
                errMsg = 'Image file size exceeds server limit.';
            } else if (xhr.status === 403) {
                errMsg = 'Permission denied for uploading images.';
            }
            alert(errMsg);
        }
    });
}

function insertMergeTag(tag) {
    if (quill) {
        const range = quill.getSelection(true);
        quill.insertText(range.index, tag);
    }
}

function loadTemplate(templateId) {
    if (!templateId) return;
    const opt = $('#templateSelector option:selected');
    const subject = opt.attr('data-subject');
    const body = opt.attr('data-body');

    if (subject) $('#campaignSubject').val(subject);
    if (body && quill) quill.root.innerHTML = body;
}

function setAudienceType(type, elem) {
    $('#audienceTypeInput').val(type);
    $('.audience-tab-card').removeClass('active');
    if (elem) {
        $(elem).closest('.audience-tab-card').addClass('active');
    } else if (typeof event !== 'undefined' && event.target) {
        $(event.target).closest('.audience-tab-card').addClass('active');
    }

    $('.audience-panel').addClass('d-none');
    if (type === 'all_users') $('#allUsersFilterPanel').removeClass('d-none');
    else if (type === 'leads') $('#leadsFilterPanel').removeClass('d-none');
    else if (type === 'orders') $('#ordersFilterPanel').removeClass('d-none');
    else if (type === 'writers') $('#writersFilterPanel').removeClass('d-none');
    else if (type === 'csv') $('#csvFilterPanel').removeClass('d-none');

    updateAudienceCount();
}

function toggleScheduleInput(show) {
    if (show) $('#scheduleTimeContainer').removeClass('d-none');
    else $('#scheduleTimeContainer').addClass('d-none');
}

function updateAudienceCount() {
    const type = $('#audienceTypeInput').val();
    if (type === 'csv') {
        $('#audienceCountText').text('From File');
        return;
    }

    $('#audienceCountText').text('Calculating...');
    const formData = $('#campaignForm').serialize();

    $.ajax({
        url: "{{ route('emails.campaigns.audience-count') }}",
        type: "POST",
        data: formData,
        success: function(res) {
            if (res.success) {
                $('#audienceCountText').text(res.count.toLocaleString() + ' recipients');
            }
        },
        error: function() {
            $('#audienceCountText').text('Count unavailable');
        }
    });
}
</script>
@endpush
@endsection
