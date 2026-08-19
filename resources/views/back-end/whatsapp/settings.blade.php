@extends('layouts.app')

@section('content')
@php
    $activeProvider = $activeProvider ?? 'twilio';
    $providerSettings = $providerSettings ?? [];
    $webhookUrl = $webhookUrl ?? url('/api/webhooks/whatsapp');
    $settingValue = function ($provider, $key, $default = '') use ($providerSettings) {
        return old("settings.{$provider}.{$key}", data_get($providerSettings, "{$provider}.{$key}", $default));
    };
@endphp
<div class="container-fluid py-5 whatsapp-settings-page">
    <form id="whatsappSettingsForm" method="POST" action="{{ route('whatsapp.settings.save') }}">
        @csrf
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
        <div>
            <h1 class="fs-2 fw-bolder text-dark mb-1">WhatsApp Integration Settings</h1>
            <div class="text-muted fw-bold fs-7">Choose one app/provider and configure its connection details.</div>
        </div>
        <button type="submit" id="saveSettingsBtn" class="btn btn-sm btn-success">
            <i class="fa fa-save me-2"></i><span id="saveBtnText">Save Settings</span>
        </button>
    </div>

    <div id="settingsAlertContainer"></div>

    @if(session('success'))
        <div class="alert alert-success fw-bold">{{ session('success') }}</div>
    @endif

    <div class="row g-5">
        <div class="col-xl-8">
            <div class="card integration-card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title d-block">
                        <h3 class="fw-bolder mb-1">Select WhatsApp App</h3>
                        <div class="text-muted fs-7">Select the active provider used for WhatsApp send and receive.</div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-4">
                        @php
                            $providers = [
                                ['key' => 'ai-sense', 'name' => 'AiSensy', 'tag' => 'Official API', 'icon' => 'fa-bolt', 'desc' => 'Use AiSensy Project API for live WhatsApp messaging, template broadcasts, and real-time webhook sync.', 'active' => $activeProvider === 'ai-sense'],
                                ['key' => 'wati', 'name' => 'WATI', 'tag' => 'Team Inbox', 'icon' => 'fa-comments', 'desc' => 'Use WATI for shared inbox, templates, broadcast campaigns, and agent assignment.', 'active' => $activeProvider === 'wati'],
                                ['key' => 'twilio', 'name' => 'Twilio', 'tag' => 'API Gateway', 'icon' => 'fa-random', 'desc' => 'Use Twilio WhatsApp API for programmable messaging and webhook-based flows.', 'active' => $activeProvider === 'twilio'],
                                ['key' => 'interakt', 'name' => 'Interakt', 'tag' => 'CRM Commerce', 'icon' => 'fa-briefcase', 'desc' => 'Use Interakt for CRM-style WhatsApp conversations, campaigns, and catalog journeys.', 'active' => $activeProvider === 'interakt'],
                            ];
                        @endphp

                        @foreach($providers as $provider)
                            <div class="col-md-6 col-xxl-3">
                                <label class="whatsapp-provider-card {{ $provider['active'] ? 'is-selected' : '' }}" data-provider-card="{{ $provider['key'] }}">
                                    <input type="radio" name="provider" value="{{ $provider['key'] }}" {{ $provider['active'] ? 'checked' : '' }}>
                                    <span class="provider-icon">
                                        <i class="fa {{ $provider['icon'] }}"></i>
                                    </span>
                                    <span class="d-flex justify-content-between align-items-start gap-3 mt-4">
                                        <span class="min-w-0">
                                            <span class="d-block fw-bolder fs-5 text-dark">{{ $provider['name'] }}</span>
                                            <span class="badge badge-light-success mt-2">{{ $provider['tag'] }}</span>
                                        </span>
                                        <span class="provider-check">
                                            <i class="fa fa-check"></i>
                                        </span>
                                    </span>
                                    <span class="d-block text-muted fs-8 mt-4">{{ $provider['desc'] }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mt-5 integration-card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title d-block">
                        <h3 class="fw-bolder mb-1">Integration Credentials & Options</h3>
                        <div class="text-muted fs-7">Configure API connection details according to your selected provider.</div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="provider-config {{ $activeProvider === 'ai-sense' ? 'is-active' : '' }}" data-provider-panel="ai-sense">
                        <div class="integration-heading">
                            <span class="provider-icon small"><i class="fa fa-bolt"></i></span>
                            <div>
                                <div class="fw-bolder text-dark">AiSensy Project Setup</div>
                                <div class="text-muted fs-8">AiSensy Project API connection for chat & real-time webhook updates.</div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">AiSensy API Key / Secret (X-AiSensy-Project-API-Pwd)</label>
                                <input type="password" name="settings[ai-sense][api_key]" class="form-control form-control-solid font-monospace" placeholder="e.g. 222488aa8678e32a..." value="{{ $settingValue('ai-sense', 'api_key', env('AISENSY_API_KEY')) }}">
                                <div class="text-muted fs-8 mt-1">Found in AiSensy Dashboard &gt; Project Settings &gt; API Keys.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Project ID</label>
                                <input type="text" name="settings[ai-sense][project_id]" class="form-control form-control-solid font-monospace" placeholder="e.g. 67e109077c4b230bed2fb1ff" value="{{ $settingValue('ai-sense', 'project_id') }}">
                                <div class="text-muted fs-8 mt-1">Your unique AiSensy project identifier.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">API Endpoint URL</label>
                                <div class="input-group">
                                    <input type="text" id="aisensyApiUrlInput" name="settings[ai-sense][api_url]" class="form-control form-control-solid font-monospace" placeholder="https://apis.aisensy.com/project-apis/v1/project/{project_id}/messages" value="{{ $settingValue('ai-sense', 'api_url', env('AISENSY_API_URL', 'https://apis.aisensy.com/project-apis/v1/project/messages')) }}">
                                    <button type="button" class="btn btn-light-primary btn-sm px-3" title="Copy Endpoint" onclick="navigator.clipboard.writeText(document.getElementById('aisensyApiUrlInput').value); alert('Endpoint URL copied!');">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                                <div class="text-muted fs-8 mt-1">Default: <code>https://apis.aisensy.com/project-apis/v1/project/{project_id}/messages</code></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Webhook URL (Paste in AiSensy Dashboard)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-solid font-monospace" value="{{ $webhookUrl }}" readonly>
                                    <button type="button" class="btn btn-primary btn-sm px-3" onclick="navigator.clipboard.writeText('{{ $webhookUrl }}'); alert('Webhook URL copied to clipboard!');">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                                <div class="text-muted fs-8 mt-1">AiSensy Webhook Settings me is endpoint ko set karein.</div>
                            </div>
                        </div>

                        <div class="mt-6 border-top pt-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="fw-bolder text-dark mb-1"><i class="fa fa-code-fork text-success me-2"></i>AiSensy Webhook Hooks & Events</h5>
                                    <div class="text-muted fs-8">These are the specific webhook topics supported by your AiSensy WhatsApp integration:</div>
                                </div>
                                <span class="badge badge-light-success fw-bolder fs-8">5 AiSensy Hooks Active</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 rounded bg-light border border-dashed h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <code class="text-primary fw-bold fs-7">message.created</code>
                                                <span class="badge badge-success py-1 px-2 fs-9">Inbound/Outbound</span>
                                            </div>
                                            <div class="text-gray-700 fs-8 mt-1">Har naye message (text ya media) ko database me save karta hai aur live chat screen update karta hai.</div>
                                        </div>
                                        <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                            <span class="text-muted fs-9 font-monospace text-truncate me-2">{{ $webhookUrl }}</span>
                                            <button type="button" class="btn btn-xs btn-light-primary py-1 px-2" onclick="navigator.clipboard.writeText('{{ $webhookUrl }}'); alert('Webhook URL copied!');">
                                                <i class="fa fa-copy me-1"></i>Copy URL
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded bg-light border border-dashed h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <code class="text-primary fw-bold fs-7">message.sender.user</code>
                                                <span class="badge badge-warning py-1 px-2 fs-9">Customer Reply</span>
                                            </div>
                                            <div class="text-gray-700 fs-8 mt-1">Customer ke incoming reply, button click aur interactive list select hone par message receive karta hai.</div>
                                        </div>
                                        <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                            <span class="text-muted fs-9 font-monospace text-truncate me-2">{{ $webhookUrl }}</span>
                                            <button type="button" class="btn btn-xs btn-light-primary py-1 px-2" onclick="navigator.clipboard.writeText('{{ $webhookUrl }}'); alert('Webhook URL copied!');">
                                                <i class="fa fa-copy me-1"></i>Copy URL
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded bg-light border border-dashed h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <code class="text-primary fw-bold fs-7">message.status.updated</code>
                                                <span class="badge badge-info py-1 px-2 fs-9">Ticks & Status</span>
                                            </div>
                                            <div class="text-gray-700 fs-8 mt-1">Message ke delivery status (`SENT`, `DELIVERED`, `READ` Blue Tick, `FAILED`) ko real-time sync karta hai.</div>
                                        </div>
                                        <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                            <span class="text-muted fs-9 font-monospace text-truncate me-2">{{ $webhookUrl }}</span>
                                            <button type="button" class="btn btn-xs btn-light-primary py-1 px-2" onclick="navigator.clipboard.writeText('{{ $webhookUrl }}'); alert('Webhook URL copied!');">
                                                <i class="fa fa-copy me-1"></i>Copy URL
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded bg-light border border-dashed h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <code class="text-primary fw-bold fs-7">contact.first_message.updated</code>
                                                <span class="badge badge-danger py-1 px-2 fs-9">Auto Lead</span>
                                            </div>
                                            <div class="text-gray-700 fs-8 mt-1">Jab koi naya customer pehli baar message karta hai to automatically CRM `Leads` table me new lead generate karta hai.</div>
                                        </div>
                                        <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                            <span class="text-muted fs-9 font-monospace text-truncate me-2">{{ $webhookUrl }}</span>
                                            <button type="button" class="btn btn-xs btn-light-primary py-1 px-2" onclick="navigator.clipboard.writeText('{{ $webhookUrl }}'); alert('Webhook URL copied!');">
                                                <i class="fa fa-copy me-1"></i>Copy URL
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 rounded bg-light border border-dashed d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <code class="text-primary fw-bold fs-7">contact.tag.updated</code>
                                                <span class="badge badge-dark py-1 px-2 fs-9">Chat Labels Sync</span>
                                            </div>
                                            <div class="text-gray-700 fs-8 mt-1">AiSensy me contact par lagaye gaye Tags (e.g. `Hot Lead`, `Order Confirmed`) ko WhatsApp chat sidebar labels ke sath sync karta hai.</div>
                                        </div>
                                        <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                            <span class="text-muted fs-9 font-monospace text-truncate me-2">Target Webhook: {{ $webhookUrl }}</span>
                                            <button type="button" class="btn btn-xs btn-light-primary py-1 px-2" onclick="navigator.clipboard.writeText('{{ $webhookUrl }}'); alert('Webhook URL copied!');">
                                                <i class="fa fa-copy me-1"></i>Copy URL
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="provider-config {{ $activeProvider === 'wati' ? 'is-active' : '' }}" data-provider-panel="wati">
                        <div class="integration-heading">
                            <span class="provider-icon small"><i class="fa fa-comments"></i></span>
                            <div>
                                <div class="fw-bolder text-dark">WATI Setup</div>
                                <div class="text-muted fs-8">Team inbox, templates, and broadcast integration.</div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">WATI API Endpoint</label>
                                <input type="text" name="settings[wati][api_endpoint]" class="form-control form-control-solid" placeholder="https://live-server.wati.io" value="{{ $settingValue('wati', 'api_endpoint') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tenant / Instance ID</label>
                                <input type="text" name="settings[wati][tenant_id]" class="form-control form-control-solid" placeholder="Enter WATI tenant id" value="{{ $settingValue('wati', 'tenant_id') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Bearer Token</label>
                                <input type="password" name="settings[wati][bearer_token]" class="form-control form-control-solid" placeholder="Paste WATI access token" value="{{ $settingValue('wati', 'bearer_token') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Template Namespace</label>
                                <input type="text" name="settings[wati][template_namespace]" class="form-control form-control-solid" placeholder="Optional namespace" value="{{ $settingValue('wati', 'template_namespace') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Webhook URL</label>
                                <input type="text" name="settings[wati][webhook_url]" class="form-control form-control-solid" value="{{ $webhookUrl }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="provider-config {{ $activeProvider === 'twilio' ? 'is-active' : '' }}" data-provider-panel="twilio">
                        <div class="integration-heading">
                            <span class="provider-icon small"><i class="fa fa-random"></i></span>
                            <div>
                                <div class="fw-bolder text-dark">Twilio Setup</div>
                                <div class="text-muted fs-8">Programmable WhatsApp messaging through Twilio.</div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Account SID</label>
                                <input type="text" name="settings[twilio][account_sid]" class="form-control form-control-solid" placeholder="ACxxxxxxxxxxxxxxxx" value="{{ $settingValue('twilio', 'account_sid') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Auth Token</label>
                                <input type="password" name="settings[twilio][auth_token]" class="form-control form-control-solid" placeholder="Twilio auth token" value="{{ $settingValue('twilio', 'auth_token') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">WhatsApp From Number</label>
                                <input type="text" name="settings[twilio][whatsapp_from_number]" class="form-control form-control-solid" placeholder="whatsapp:+14155238886" value="{{ $settingValue('twilio', 'whatsapp_from_number') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Messaging Service SID</label>
                                <input type="text" name="settings[twilio][messaging_service_sid]" class="form-control form-control-solid" placeholder="MGxxxxxxxxxxxxxxxx" value="{{ $settingValue('twilio', 'messaging_service_sid') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Incoming Webhook URL</label>
                                <input type="text" name="settings[twilio][webhook_url]" class="form-control form-control-solid" value="{{ $webhookUrl }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="provider-config {{ $activeProvider === 'interakt' ? 'is-active' : '' }}" data-provider-panel="interakt">
                        <div class="integration-heading">
                            <span class="provider-icon small"><i class="fa fa-briefcase"></i></span>
                            <div>
                                <div class="fw-bolder text-dark">Interakt Setup</div>
                                <div class="text-muted fs-8">CRM campaigns, customer journey, and WhatsApp commerce.</div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">API Base URL</label>
                                <input type="text" name="settings[interakt][api_base_url]" class="form-control form-control-solid" placeholder="https://api.interakt.ai" value="{{ $settingValue('interakt', 'api_base_url') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">API Key</label>
                                <input type="password" name="settings[interakt][api_key]" class="form-control form-control-solid" placeholder="Interakt API key" value="{{ $settingValue('interakt', 'api_key') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Business Number</label>
                                <input type="text" name="settings[interakt][business_number]" class="form-control form-control-solid" placeholder="+44 0000 000000" value="{{ $settingValue('interakt', 'business_number') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Campaign Source</label>
                                <input type="text" name="settings[interakt][campaign_source]" class="form-control form-control-solid" placeholder="AIN Backend" value="{{ $settingValue('interakt', 'campaign_source') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Webhook URL</label>
                                <input type="text" name="settings[interakt][webhook_url]" class="form-control form-control-solid" value="{{ $webhookUrl }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card mb-5">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bolder mb-0"><i class="fa fa-plug text-primary me-2"></i>Webhook Endpoint</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-2">
                        <label class="form-label fw-bold text-dark fs-7">Global Webhook URL</label>
                        <div class="input-group">
                            <input type="text" id="globalWebhookUrlInput" class="form-control form-control-solid fs-8 font-monospace" value="{{ $webhookUrl }}" readonly>
                            <button type="button" class="btn btn-primary btn-sm px-4" onclick="navigator.clipboard.writeText('{{ $webhookUrl }}'); alert('Webhook URL copied to clipboard!');">
                                <i class="fa fa-copy"></i>
                            </button>
                        </div>
                        <div class="text-muted fs-8 mt-2">Paste this URL into your active provider dashboard (AiSensy, Twilio, etc.) to receive real-time updates.</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bolder mb-0">Setup Checklist</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="timeline-label">
                        @foreach(['Choose app/provider', 'Add provider credentials', 'Set webhook URL in provider', 'Enable webhook topics/events', 'Test send & receive'] as $step)
                            <div class="timeline-item">
                                <div class="timeline-label fw-bold text-gray-800 fs-7">{{ $loop->iteration }}</div>
                                <div class="timeline-badge">
                                    <i class="fa fa-circle text-primary fs-9"></i>
                                </div>
                                <div class="fw-bold text-gray-700 ps-3">{{ $step }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<style>
    .whatsapp-settings-page .card {
        border-radius: 8px;
    }

    .integration-card {
        border: 1px solid #e4e6ef;
        box-shadow: 0 10px 26px rgba(17, 27, 33, 0.05);
    }

    .whatsapp-provider-card {
        display: block;
        height: 100%;
        border: 1px solid #e4e6ef;
        border-radius: 8px;
        padding: 18px;
        cursor: pointer;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease;
    }

    .whatsapp-provider-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .whatsapp-provider-card:hover,
    .whatsapp-provider-card.is-selected {
        border-color: #00a884;
        box-shadow: 0 8px 22px rgba(0, 168, 132, 0.12);
    }

    .whatsapp-provider-card:hover {
        transform: translateY(-1px);
    }

    .provider-icon {
        width: 42px;
        height: 42px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e7f8f2;
        color: #00a884;
        font-size: 18px;
    }

    .provider-icon.small {
        width: 36px;
        height: 36px;
        font-size: 15px;
        flex: 0 0 auto;
    }

    .provider-check {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e7f8f2;
        color: #00a884;
        font-size: 12px;
    }

    .provider-config {
        display: none;
    }

    .provider-config.is-active {
        display: block;
    }

    .integration-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px;
        border: 1px solid #d7eee5;
        border-radius: 8px;
        background: #f0fbf7;
        margin-bottom: 18px;
    }
</style>

<script>
    document.querySelectorAll('[data-provider-card]').forEach(function (card) {
        card.addEventListener('click', function () {
            const provider = card.getAttribute('data-provider-card');

            document.querySelectorAll('[data-provider-card]').forEach(function (item) {
                item.classList.toggle('is-selected', item === card);
                const input = item.querySelector('input[type="radio"]');
                if (input) {
                    input.checked = item === card;
                }
            });

            document.querySelectorAll('[data-provider-panel]').forEach(function (panel) {
                panel.classList.toggle('is-active', panel.getAttribute('data-provider-panel') === provider);
            });
        });
    });

    const settingsForm = document.getElementById('whatsappSettingsForm');
    const saveBtn = document.getElementById('saveSettingsBtn');
    const saveBtnText = document.getElementById('saveBtnText');
    const alertContainer = document.getElementById('settingsAlertContainer');

    if (settingsForm) {
        settingsForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const originalBtnHtml = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
            alertContainer.innerHTML = '';

            const formData = new FormData(settingsForm);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token');

            fetch(settingsForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (response.ok && data.success) {
                    alertContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show fw-bold" role="alert">
                            <i class="fa fa-check-circle me-2"></i>${data.message || 'WhatsApp settings saved successfully!'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    const errorMsg = data.message || 'Failed to save settings. Please try again.';
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show fw-bold" role="alert">
                            <i class="fa fa-exclamation-triangle me-2"></i>${errorMsg}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            })
            .catch(error => {
                // If fetch fails, fallback to regular submit
                settingsForm.submit();
            })
            .finally(() => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalBtnHtml;
            });
        });
    }
</script>
@endsection
