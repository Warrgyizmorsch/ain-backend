@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
        <div>
            <h1 class="fs-2 fw-bolder text-dark mb-1">WhatsApp Settings</h1>
            <div class="text-muted fw-bold fs-7">Select provider and prepare app connection details.</div>
        </div>
        <button type="button" class="btn btn-sm btn-primary">
            <i class="fa fa-save me-2"></i>Save Draft
        </button>
    </div>

    <div class="row g-5">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bolder mb-0">Provider</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-4">
                        @php
                            $providers = [
                                ['name' => 'Twilio', 'tag' => 'API Gateway', 'desc' => 'For WhatsApp Business API through Twilio messaging services.', 'active' => true],
                                ['name' => 'AIN Sense', 'tag' => 'Internal', 'desc' => 'Placeholder for custom AIN WhatsApp connector.', 'active' => false],
                                ['name' => 'WATI', 'tag' => 'Inbox + API', 'desc' => 'For WATI team inbox, templates, and automation.', 'active' => false],
                                ['name' => 'Interakt', 'tag' => 'Commerce', 'desc' => 'For CRM-style WhatsApp campaigns and customer chat.', 'active' => false],
                                ['name' => 'Gupshup', 'tag' => 'Cloud API', 'desc' => 'For provider-managed WhatsApp API integration.', 'active' => false],
                                ['name' => 'Meta Cloud API', 'tag' => 'Direct', 'desc' => 'For direct app, phone number ID, and access token setup.', 'active' => false],
                            ];
                        @endphp

                        @foreach($providers as $provider)
                            <div class="col-md-6">
                                <label class="whatsapp-provider-card {{ $provider['active'] ? 'is-selected' : '' }}">
                                    <input type="radio" name="provider" value="{{ $provider['name'] }}" {{ $provider['active'] ? 'checked' : '' }}>
                                    <span class="d-flex justify-content-between align-items-start gap-3">
                                        <span>
                                            <span class="d-block fw-bolder fs-5 text-dark">{{ $provider['name'] }}</span>
                                            <span class="badge badge-light-primary mt-2">{{ $provider['tag'] }}</span>
                                        </span>
                                        <span class="provider-check">
                                            <i class="fa fa-check"></i>
                                        </span>
                                    </span>
                                    <span class="d-block text-muted fs-7 mt-4">{{ $provider['desc'] }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bolder mb-0">Connection Details</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Business Phone Number</label>
                            <input type="text" class="form-control form-control-solid" placeholder="+44 0000 000000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">App / Account SID</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Enter app id or SID">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number ID</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Provider phone number id">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Webhook URL</label>
                            <input type="text" class="form-control form-control-solid" value="{{ url('/api/webhooks/whatsapp') }}" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Access Token / API Key</label>
                            <input type="password" class="form-control form-control-solid" placeholder="Paste token here">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bolder mb-0">Setup Checklist</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="timeline-label">
                        @foreach(['Choose provider', 'Add phone number', 'Add credentials', 'Verify webhook', 'Send test message'] as $step)
                            <div class="timeline-item">
                                <div class="timeline-label fw-bold text-gray-800 fs-7">{{ $loop->iteration }}</div>
                                <div class="timeline-badge">
                                    <i class="fa fa-circle text-primary fs-9"></i>
                                </div>
                                <div class="fw-bold text-gray-700 ps-3">{{ $step }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="notice bg-light-primary rounded border-primary border border-dashed p-5 mt-8">
                        <div class="fw-bolder text-dark mb-2">Static preview</div>
                        <div class="text-muted fs-7">This screen is UI only. Provider saving and live API connection can be wired after final provider selection.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .whatsapp-provider-card {
        display: block;
        height: 100%;
        border: 1px solid #e4e6ef;
        border-radius: 8px;
        padding: 18px;
        cursor: pointer;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .whatsapp-provider-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .whatsapp-provider-card:hover,
    .whatsapp-provider-card.is-selected {
        border-color: #1b84ff;
        box-shadow: 0 8px 22px rgba(27, 132, 255, 0.11);
    }

    .provider-check {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef6ff;
        color: #1b84ff;
        font-size: 12px;
    }
</style>
@endsection
