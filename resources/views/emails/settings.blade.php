@extends('layouts.app')

{{-- This page has its own compact heading, so only it opts out of the
     theme toolbar offset. All other admin screens keep the standard layout. --}}
@section('body-layout-classes', 'email-settings-layout')
@section('body-layout-style', '')

@section('content')
<style>
    .email-settings-page { max-width: 1600px; margin: 0 auto; }
    .email-settings-header { background: #fff; border: 1px solid #eef1f6; border-radius: 14px; padding: 22px 24px; box-shadow: 0 5px 20px rgba(31, 44, 71, .05); }
    .email-settings-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-wrap: wrap; }
    .email-settings-actions .btn { min-height: 40px; white-space: nowrap; }
    .email-direct-card { border: 1px solid #ddecf7 !important; border-radius: 14px; overflow: hidden; }
    .email-direct-url { display: inline-flex; max-width: 100%; overflow-wrap: anywhere; }
    .email-config-card { border: 1px solid #eef1f6 !important; border-radius: 14px; overflow: hidden; }
    .email-config-card .card-header { min-height: 76px; }
    .email-config-table th { white-space: nowrap; font-size: 11px; letter-spacing: .04em; }
    .email-config-table td { vertical-align: middle; }
    .email-account-actions { display: flex; justify-content: flex-end; gap: 7px; flex-wrap: nowrap; }
    .email-account-actions .btn { width: 38px; height: 38px; border-radius: 9px; }
    @media (max-width: 991.98px) {
        .email-settings-actions { justify-content: flex-start; margin-top: 18px; }
        .email-settings-actions .btn { flex: 1 1 210px; }
        .email-direct-card .card-body { padding: 20px !important; }
    }
    @media (max-width: 575.98px) {
        .email-settings-header { padding: 18px; }
        .email-settings-actions .btn { width: 100%; flex-basis: 100%; }
        .email-direct-actions { width: 100%; display: grid !important; grid-template-columns: 1fr 1fr; }
    }
</style>
<div class="container-fluid py-6 email-settings-page">
    {{-- Breadcrumb & Title --}}
    <div class="email-settings-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between mb-6 gap-3">
        <div class="pe-lg-5">
            <h1 class="text-dark fw-bolder fs-2 mb-1">Email Integration & Multi-Account Configurations</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Dashboard</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="{{ route('emails.index') }}" class="text-muted text-hover-primary">Emails</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-dark">Configurations & Plugins</li>
            </ul>
        </div>
        <div class="email-settings-actions">
            <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#createEmailConfigModal">
                <i class="fa fa-plus me-1"></i> Add Email Configuration
            </button>
            <a href="https://myaccount.google.com/apppasswords" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-danger fw-bold" title="Create the 16-digit password used in SMTP and IMAP password fields">
                <i class="fa fa-key me-1"></i> Generate Gmail App Password
            </a>
            <button type="button" class="btn btn-sm btn-light-danger fw-bold border border-danger-subtle" data-bs-toggle="modal" data-bs-target="#gmailGuideModal">
                <i class="fa fa-google me-1 text-danger"></i> Gmail App Password Guide
            </button>
        </div>
    </div>

    {{-- Email Web App Quick Link & Integration Card --}}
    <div class="card shadow-sm border-0 mb-6 bg-light-primary email-direct-card">
        <div class="card-body p-5">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                <div class="d-flex align-items-center gap-4">
                    <div class="symbol symbol-50px symbol-circle bg-primary p-3 text-white">
                        <i class="fa fa-paper-plane fs-2 text-white"></i>
                    </div>
                    <div>
                        <h4 class="fw-bolder text-gray-900 mb-1">Email Web App Direct Link</h4>
                        <div class="text-muted fs-7">
                            Direct Web App URL for browser, desktop wrapper, or app integration:
                            <code class="fw-bold text-primary bg-white px-2 py-1 rounded border ms-md-1 mt-1 mt-md-0 email-direct-url" id="emailAppDirectUrl">{{ url('/emails') }}</code>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 email-direct-actions">
                    <button type="button" class="btn btn-sm btn-white text-primary fw-bold border" onclick="copyEmailAppUrl(this)" title="Copy Link to Clipboard">
                        <i class="fa fa-copy me-1"></i> <span id="copyUrlBtnText">Copy App Link</span>
                    </button>
                    <a href="{{ route('emails.index') }}" class="btn btn-sm btn-primary fw-bold" target="_blank">
                        <i class="fa fa-external-link me-1"></i> Launch Email App
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Configurations Table Card --}}
    <div class="card shadow-sm border-0 mb-8 email-config-card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bolder m-0">Configured Email Accounts & Channels ({{ $configurations->count() }})</h3>
            </div>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#createEmailConfigModal">
                    <i class="fa fa-plus-circle me-1"></i> New Channel
                </button>
            </div>
        </div>
        <div class="card-body pt-2">
            @if($configurations->isEmpty())
                <div class="text-center py-12">
                    <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-4">
                        <i class="fa fa-envelope-open-text fs-1 text-primary"></i>
                    </div>
                    <h4 class="fw-bold text-gray-800">No Email Configurations Found</h4>
                    <p class="text-muted fs-7 mb-6">Click the button below to add your first SMTP / IMAP email configuration.</p>
                    <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#createEmailConfigModal">
                        <i class="fa fa-plus me-1"></i> Add Email Configuration
                    </button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 email-config-table">
                        <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th>Channel Name</th>
                                <th>Email Address & Sender</th>
                                <th>Protocol / Driver</th>
                                <th>Host & Port</th>
                                <th>Status</th>
                                <th class="text-end min-w-150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach($configurations as $config)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-45px me-3">
                                                <span class="symbol-label bg-light-primary text-primary fw-bolder">
                                                    <i class="fa fa-envelope fs-5"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-900 fw-bolder fs-6">{{ $config->name }}</span>
                                                <span class="text-muted fs-8">Channel ID: <code>#{{ $config->id }}</code></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-gray-900 fw-bold">{{ $config->email_address }}</div>
                                        <div class="text-muted fs-8">{{ $config->from_name ?: 'Same as name' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-info text-uppercase fw-bold">{{ $config->driver }}</span>
                                        @if($config->is_default)
                                            <span class="badge badge-light-success fw-bold ms-1">Default</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-gray-800 fs-7">{{ $config->host ?: 'Default Mail Server' }}:{{ $config->port }}</div>
                                        <div class="text-muted fs-8">Enc: {{ strtoupper($config->encryption) }}</div>
                                    </td>
                                    <td>
                                        @if($config->is_active)
                                            <span class="badge badge-success fw-bold">Active</span>
                                        @else
                                            <span class="badge badge-secondary fw-bold">Disabled</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="email-account-actions">
                                            {{-- Open Mailbox Direct --}}
                                            <a href="{{ route('emails.index', ['account_id' => $config->id]) }}" target="_blank" class="btn btn-icon btn-light-success btn-sm" title="Open this Account in Email App">
                                                <i class="fa fa-inbox"></i>
                                            </a>

                                            {{-- Test Connection --}}
                                            <button type="button" class="btn btn-icon btn-light-info btn-sm" title="Test Connection" onclick="testEmailConfig({{ $config->id }}, '{{ addslashes($config->name) }}')">
                                                <i class="fa fa-bolt"></i>
                                            </button>

                                            {{-- Clone Configuration --}}
                                            <button type="button" class="btn btn-icon btn-light-warning btn-sm" title="Clone Account" data-bs-toggle="modal" data-bs-target="#cloneModal{{ $config->id }}">
                                                <i class="fa fa-copy"></i>
                                            </button>

                                            {{-- Edit Configuration --}}
                                            <button type="button" class="btn btn-icon btn-light-primary btn-sm" title="Edit Account" data-bs-toggle="modal" data-bs-target="#editModal{{ $config->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            {{-- Delete Configuration --}}
                                            <form action="{{ route('emails.settings.delete', $config->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete \'{{ $config->name }}\'?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-light-danger btn-sm" title="Delete Account">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Clone Modal for this Config --}}
                                <div class="modal fade" id="cloneModal{{ $config->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('emails.settings.clone', $config->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h4 class="modal-title fw-bolder">Clone Configuration: {{ $config->name }}</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-muted fs-7 mb-4">Duplicate all server, security, and port settings from <strong>{{ $config->name }}</strong> into a new account.</p>
                                                    <div class="mb-4">
                                                        <label class="form-label required fw-bold">New Configuration Name</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $config->name }} (Copy)" required>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label required fw-bold">Email Address</label>
                                                        <input type="email" name="email_address" class="form-control" value="{{ $config->email_address }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-warning fw-bold"><i class="fa fa-copy me-1"></i> Clone Now</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Edit Modal for this Config --}}
                                <div class="modal fade" id="editModal{{ $config->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('emails.settings.update', $config->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h4 class="modal-title fw-bolder">Edit Email Configuration: {{ $config->name }}</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-4">
                                                        <div class="col-md-6">
                                                            <label class="form-label required fw-bold">Channel / Account Name</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $config->name }}" required>
                                                            <div class="text-muted fs-8 mt-1">This name appears in the sidebar submenu.</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label required fw-bold">Email Address</label>
                                                            <input type="email" name="email_address" class="form-control" value="{{ $config->email_address }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Sender Name (From Name)</label>
                                                            <input type="text" name="from_name" class="form-control" value="{{ $config->from_name }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label required fw-bold">Mail Driver</label>
                                                            <select name="driver" class="form-select" required>
                                                                <option value="smtp" {{ $config->driver === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="form-label fw-bold">SMTP Host</label>
                                                            <input type="text" name="host" class="form-control" value="{{ $config->host }}" placeholder="smtp.gmail.com">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label fw-bold">SMTP Port</label>
                                                            <input type="number" name="port" class="form-control" value="{{ $config->port }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Encryption</label>
                                                            <select name="encryption" class="form-select">
                                                                <option value="tls" {{ $config->encryption === 'tls' ? 'selected' : '' }}>TLS</option>
                                                                <option value="ssl" {{ $config->encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                                                                <option value="none" {{ $config->encryption === 'none' ? 'selected' : '' }}>None</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">SMTP Username</label>
                                                            <input type="text" name="username" class="form-control" value="{{ $config->username }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">SMTP Password</label>
                                                            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                                                        </div>

                                                        <div class="col-12"><hr class="text-muted"></div>
                                                        <div class="col-12"><h6 class="fw-bold mb-0">Incoming Mail Server (IMAP / Sync)</h6></div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">IMAP Host</label>
                                                            <input type="text" name="incoming_host" class="form-control" value="{{ $config->incoming_host }}" placeholder="imap.gmail.com">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label fw-bold">IMAP Port</label>
                                                            <input type="number" name="incoming_port" class="form-control" value="{{ $config->incoming_port }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label fw-bold">IMAP Encryption</label>
                                                            <select name="incoming_encryption" class="form-select">
                                                                <option value="ssl" {{ $config->incoming_encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                                                                <option value="none" {{ $config->incoming_encryption === 'none' ? 'selected' : '' }}>None</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">IMAP Username</label>
                                                            <input type="text" name="incoming_username" class="form-control" value="{{ $config->incoming_username }}" autocomplete="username">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">IMAP Password</label>
                                                            <input type="password" name="incoming_password" class="form-control" placeholder="Leave blank to keep current" autocomplete="new-password">
                                                        </div>

                                                        <div class="col-12 d-flex gap-4">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="editDefault{{ $config->id }}" {{ $config->is_default ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold" for="editDefault{{ $config->id }}">Default Outbound Account</label>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="editActive{{ $config->id }}" {{ $config->is_active ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold" for="editActive{{ $config->id }}">Active Channel</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary fw-bold">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Create New Email Config Modal --}}
<div class="modal fade" id="createEmailConfigModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('emails.settings.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title fw-bolder">Add New Email Configuration</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label required fw-bold">Channel / Account Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Support Mail, Order Notification" required>
                            <div class="text-muted fs-8 mt-1">This name will appear as a submenu under the Emails main menu.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required fw-bold">Email Address</label>
                            <input type="email" name="email_address" class="form-control" placeholder="support@assignmentinneed.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Sender Name (From Name)</label>
                            <input type="text" name="from_name" class="form-control" placeholder="Assignment In Need Support">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required fw-bold">Mail Driver</label>
                            <select name="driver" class="form-select" required>
                                <option value="smtp" selected>SMTP Server</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold">SMTP Host</label>
                            <input type="text" name="host" class="form-control" placeholder="smtp.gmail.com">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">SMTP Port</label>
                            <input type="number" name="port" class="form-control" value="587">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Encryption</label>
                            <select name="encryption" class="form-select">
                                <option value="tls" selected>TLS (Port 587)</option>
                                <option value="ssl">SSL (Port 465)</option>
                                <option value="none">None (Port 25)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">SMTP Username</label>
                            <input type="text" name="username" class="form-control" placeholder="apikey / username / email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">SMTP Password</label>
                            <input type="password" name="password" class="form-control" placeholder="App password or secret">
                        </div>

                        <div class="col-12"><hr class="text-muted"></div>
                        <div class="col-12"><h6 class="fw-bold mb-0">Incoming Mail Server (IMAP / Sync Settings)</h6></div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">IMAP Host</label>
                            <input type="text" name="incoming_host" class="form-control" placeholder="imap.gmail.com">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">IMAP Port</label>
                            <input type="number" name="incoming_port" class="form-control" value="993">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">IMAP Encryption</label>
                            <select name="incoming_encryption" class="form-select">
                                <option value="ssl" selected>SSL (Port 993)</option>
                                <option value="none">None</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">IMAP Username</label>
                            <input type="text" name="incoming_username" class="form-control" autocomplete="username" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">IMAP Password</label>
                            <input type="password" name="incoming_password" class="form-control" autocomplete="new-password" required>
                        </div>

                        <div class="col-12 d-flex gap-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="createDefault">
                                <label class="form-check-label fw-bold" for="createDefault">Set as Default Outbound Account</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="createActive" checked>
                                <label class="form-check-label fw-bold" for="createActive">Active Channel</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="fa fa-save me-1"></i> Save & Add to Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     GMAIL APP PASSWORD & INTEGRATION GUIDE MODAL
══════════════════════════════════════════════════ --}}
<div class="modal fade" id="gmailGuideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-danger text-white py-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-40px symbol-circle bg-white text-danger p-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-google fs-3 text-danger"></i>
                    </div>
                    <div>
                        <h4 class="modal-title fw-bolder text-white mb-0">How to Setup Gmail &amp; Generate App Password</h4>
                        <div class="text-white opacity-75 fs-8">Step-by-Step guide to connect your Gmail account via SMTP &amp; IMAP</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <div class="alert alert-warning d-flex align-items-center p-4 mb-5 border border-warning">
                    <i class="fa fa-exclamation-triangle fs-2 text-warning me-3"></i>
                    <div class="fs-7 text-gray-800">
                        <strong>Important:</strong> Google aapke regular Gmail password se third-party login allow nahi karta. Iske liye <strong>16-Digit App Password</strong> generate karna zaroori hai.
                    </div>
                </div>

                {{-- Step by Step --}}
                <div class="timeline-steps">
                    {{-- Step 1 --}}
                    <div class="d-flex gap-4 mb-5">
                        <div class="badge badge-circle badge-danger fs-6 fw-bold p-3" style="width:34px;height:34px;flex-shrink:0;">1</div>
                        <div>
                            <h6 class="fw-bold text-gray-900 mb-1">Turn ON 2-Step Verification</h6>
                            <p class="text-muted fs-7 mb-1">Apne Google Account me 2-Step Verification enable karein agar pehle se enable nahi hai.</p>
                            <a href="https://myaccount.google.com/signinoptions/two-step-verification" target="_blank" class="btn btn-xs btn-light-primary fw-bold">
                                <i class="fa fa-external-link me-1"></i> Open 2-Step Verification Settings
                            </a>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="d-flex gap-4 mb-5">
                        <div class="badge badge-circle badge-danger fs-6 fw-bold p-3" style="width:34px;height:34px;flex-shrink:0;">2</div>
                        <div>
                            <h6 class="fw-bold text-gray-900 mb-1">Generate 16-Digit App Password</h6>
                            <p class="text-muted fs-7 mb-1">Google Security ke "App Passwords" page par jayein:</p>
                            <a href="https://myaccount.google.com/apppasswords" target="_blank" class="btn btn-xs btn-danger fw-bold mb-2">
                                <i class="fa fa-key me-1"></i> Open Google App Passwords Page
                            </a>
                            <ul class="fs-7 text-gray-700 ps-4 mb-0">
                                <li><strong>App name</strong> me <code>AIN Email App</code> ya <code>Portal</code> type karein.</li>
                                <li><strong>Create</strong> button par click karein.</li>
                                <li>Screen par <strong>16-character ka App Password</strong> aayega (Jaise: <code>abcd efgh ijkl mnop</code>).</li>
                                <li>Isko copy karein (spaces hata kar).</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="d-flex gap-4 mb-5">
                        <div class="badge badge-circle badge-danger fs-6 fw-bold p-3" style="width:34px;height:34px;flex-shrink:0;">3</div>
                        <div>
                            <h6 class="fw-bold text-gray-900 mb-1">Enable IMAP in Gmail Settings</h6>
                            <p class="text-muted fs-7 mb-1">Gmail me incoming emails sync karne ke liye IMAP access on karein:</p>
                            <ul class="fs-7 text-gray-700 ps-4 mb-0">
                                <li>Gmail khol kar <strong>Settings (Gear Icon) -> See all settings</strong> me jayein.</li>
                                <li><strong>"Forwarding and POP/IMAP"</strong> tab me jayein.</li>
                                <li><strong>"Enable IMAP"</strong> select karke <strong>Save Changes</strong> karein.</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Step 4 --}}
                    <div class="d-flex gap-4">
                        <div class="badge badge-circle badge-success fs-6 fw-bold p-3" style="width:34px;height:34px;flex-shrink:0;">4</div>
                        <div class="w-100">
                            <h6 class="fw-bold text-gray-900 mb-1">Enter Values in Portal Configuration</h6>
                            <div class="table-responsive mt-2">
                                <table class="table table-sm table-bordered fs-8">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Field</th>
                                            <th>Value for Gmail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Mail Driver</td>
                                            <td><code>SMTP</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">SMTP Host &amp; Port</td>
                                            <td><code>smtp.gmail.com</code> | Port: <code>587</code> (Encryption: <code>TLS</code>)</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">SMTP / IMAP Username</td>
                                            <td><code>your-email@gmail.com</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">SMTP / IMAP Password</td>
                                            <td><span class="badge bg-light-danger text-danger fw-bold">16-digit App Password</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Incoming Host &amp; Port</td>
                                            <td><code>imap.gmail.com</code> | Port: <code>993</code> (Encryption: <code>SSL</code>)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary fw-bold" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#createEmailConfigModal">
                    <i class="fa fa-plus me-1"></i> Add Email Configuration Now
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function testEmailConfig(id, name) {
    Swal.fire({
        title: 'Testing Connection...',
        text: `Validating connection settings for '${name}'`,
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch(`{{ url('/emails/settings') }}/${id}/test`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Connection Successful',
                text: data.message
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Connection Failed',
                text: data.message
            });
        }
    })
    .catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Server connection test request failed.'
        });
    });
}

function copyEmailAppUrl(btn) {
    const url = '{{ url("/emails") }}';
    navigator.clipboard.writeText(url).then(() => {
        const btnText = document.getElementById('copyUrlBtnText');
        if (btnText) btnText.textContent = 'Copied!';
        setTimeout(() => {
            if (btnText) btnText.textContent = 'Copy App Link';
        }, 2000);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Email Web App Link copied to clipboard!',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }).catch(err => {
        prompt('Copy Email App Link:', url);
    });
}
</script>
@endsection
