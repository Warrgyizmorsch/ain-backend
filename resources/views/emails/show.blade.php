@extends('layouts.app')

@push('head')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<style>
    :root {
        --duralux-primary: #3454d1;
        --duralux-primary-light: #edf2fe;
        --duralux-primary-hover: #263fb0;
        --duralux-dark: #0f172a;
        --duralux-gray: #475569;
        --duralux-border: #cbd5e1;
        --duralux-border-light: #e2e8f0;
        --duralux-bg: #f8fafc;
        --duralux-white: #ffffff;
        --duralux-star: #f59e0b;
        --duralux-unread-bg: #f0f6ff;
        --duralux-hover-bg: #f1f5f9;
    }

    .header-fixed.toolbar-fixed #kt_wrapper,
    .header-fixed #kt_wrapper,
    #kt_wrapper {
        padding-top: 65px !important;
    }

    #kt_wrapper .content,
    .content.flex-column-fluid,
    .content,
    #kt_content {
        padding: 0 !important;
        margin: 0 !important;
    }

    .duralux-email-wrapper {
        height: calc(100vh - 75px) !important;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        margin: 5px 12px 5px 12px !important;
        padding: 0 !important;
    }

    .duralux-email-app {
        display: flex;
        flex: 1;
        background: #ffffff;
        border: 1px solid var(--duralux-border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        height: 100%;
    }

    /* Left Sidebar */
    .duralux-sidebar {
        width: 260px;
        background: #ffffff;
        border-right: 1px solid var(--duralux-border-light);
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        overflow-y: auto;
    }

    .duralux-compose-btn-wrap {
        padding: 18px 16px 14px 16px;
    }

    .duralux-btn-compose {
        width: 100%;
        background: var(--duralux-primary);
        color: #ffffff !important;
        border: none;
        border-radius: 8px;
        padding: 11px 16px;
        font-weight: 700;
        font-size: 13.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(52, 84, 209, 0.25);
        transition: all 0.2s;
    }

    .duralux-btn-compose:hover {
        background: var(--duralux-primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(52, 84, 209, 0.35);
    }

    .duralux-nav-list {
        list-style: none;
        padding: 6px 12px;
        margin: 0;
    }

    .duralux-nav-item {
        margin-bottom: 3px;
    }

    .duralux-nav-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 14px;
        border-radius: 8px;
        color: var(--duralux-gray);
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        transition: all 0.15s;
    }

    .duralux-nav-link:hover {
        background: var(--duralux-hover-bg);
        color: var(--duralux-dark);
    }

    .duralux-nav-link.active {
        background: var(--duralux-primary-light);
        color: var(--duralux-primary);
        font-weight: 700;
    }

    .duralux-nav-link i {
        font-size: 15px;
        width: 20px;
        text-align: center;
        margin-right: 10px;
    }

    .duralux-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 10px;
        background: #e2e8f0;
        color: #475569;
    }

    .duralux-badge-primary {
        background: var(--duralux-primary);
        color: #ffffff;
    }

    .duralux-section-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #94a3b8;
        padding: 14px 16px 6px 16px;
        letter-spacing: 0.5px;
    }

    /* Main Conversation View Area */
    .duralux-main-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
        background: #ffffff;
        overflow-y: auto;
    }

    .duralux-top-bar {
        height: 60px;
        border-bottom: 1px solid var(--duralux-border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 24px;
        background: #ffffff;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .duralux-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 13.5px;
        color: var(--duralux-gray);
        background: transparent;
        border: 1px solid var(--duralux-border);
        border-radius: 8px;
        padding: 6px 14px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .duralux-btn-back:hover {
        background: var(--duralux-hover-bg);
        color: var(--duralux-dark);
    }

    .duralux-btn-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: 1px solid var(--duralux-border);
        background: transparent;
        color: var(--duralux-gray);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
    }

    .duralux-btn-icon:hover {
        background: var(--duralux-hover-bg);
        color: var(--duralux-dark);
    }

    .duralux-conversation-content {
        padding: 24px 32px 60px 32px;
        max-width: 1000px;
        margin: 0 auto;
        width: 100%;
    }

    .duralux-subject-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--duralux-border-light);
    }

    .duralux-subject-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--duralux-dark);
        margin: 0;
        line-height: 1.3;
    }

    .duralux-message-card {
        border: 1.5px solid var(--duralux-border-light);
        border-radius: 12px;
        background: #ffffff;
        padding: 22px 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .duralux-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        color: #ffffff;
        flex-shrink: 0;
    }

    .duralux-message-body {
        font-size: 14px;
        line-height: 1.7;
        color: #334155;
        margin-top: 14px;
        padding-left: 54px;
        word-break: break-word;
    }

    .duralux-message-body blockquote,
    .duralux-message-body .gmail_quote {
        border-left: 3px solid #cbd5e1 !important;
        padding-left: 14px !important;
        margin: 12px 0 !important;
        color: #64748b !important;
    }

    /* Action Pills */
    .duralux-action-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 20px;
        border-radius: 20px;
        border: 1.5px solid var(--duralux-border);
        background: #ffffff;
        color: var(--duralux-gray);
        font-weight: 600;
        font-size: 13.5px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .duralux-action-pill:hover {
        background: var(--duralux-primary-light);
        color: var(--duralux-primary);
        border-color: var(--duralux-primary);
    }

    /* Inline Composer Box */
    .duralux-inline-composer {
        border: 2px solid var(--duralux-border);
        border-radius: 12px;
        background: #ffffff;
        padding: 20px;
        margin-top: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        transition: border-color 0.2s;
    }

    .duralux-inline-composer.focused {
        border-color: var(--duralux-primary);
    }

    .mode-tab-btn {
        background: none;
        border: none;
        padding: 6px 14px;
        font-weight: 700;
        font-size: 13px;
        border-radius: 6px;
        color: var(--duralux-gray);
        cursor: pointer;
        transition: all 0.15s;
    }

    .mode-tab-btn.active {
        background: var(--duralux-primary-light);
        color: var(--duralux-primary);
    }
</style>
@endpush

@section('content')
<div id="emailResultToast" style="position:fixed;right:24px;bottom:24px;z-index:99999;display:none;min-width:300px;max-width:460px;padding:14px 18px;border-radius:10px;color:#fff;box-shadow:0 10px 30px rgba(0,0,0,.2);font-weight:600"></div>
<div class="duralux-email-wrapper">
    <div class="duralux-email-app">
        {{-- Left Sidebar --}}
        <aside class="duralux-sidebar">
            <div class="duralux-compose-btn-wrap">
                <button type="button" class="duralux-btn-compose" onclick="window.location.href='{{ route('emails.index', ['account_id' => request('account_id')]) }}'">
                    <i class="fa fa-arrow-left"></i> Back to Inbox
                </button>
            </div>

            <div class="duralux-section-label">Mailboxes</div>
            <ul class="duralux-nav-list">
                <li class="duralux-nav-item">
                    <a href="{{ route('emails.index', ['folder' => 'inbox', 'account_id' => request('account_id')]) }}" class="duralux-nav-link {{ $email->folder === 'inbox' ? 'active' : '' }}">
                        <span><i class="fa fa-inbox text-primary"></i> Inbox</span>
                        <span class="duralux-badge duralux-badge-primary">{{ $counts['inbox'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="duralux-nav-item">
                    <a href="{{ route('emails.index', ['folder' => 'sent', 'account_id' => request('account_id')]) }}" class="duralux-nav-link {{ $email->folder === 'sent' ? 'active' : '' }}">
                        <span><i class="fa fa-paper-plane text-success"></i> Sent</span>
                        <span class="duralux-badge">{{ $counts['sent'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="duralux-nav-item">
                    <a href="{{ route('emails.index', ['folder' => 'starred', 'account_id' => request('account_id')]) }}" class="duralux-nav-link {{ $email->is_starred ? 'active' : '' }}">
                        <span><i class="fa fa-star text-warning"></i> Starred</span>
                        <span class="duralux-badge">{{ $counts['starred'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="duralux-nav-item">
                    <a href="{{ route('emails.index', ['folder' => 'trash', 'account_id' => request('account_id')]) }}" class="duralux-nav-link {{ $email->folder === 'trash' ? 'active' : '' }}">
                        <span><i class="fa fa-trash text-danger"></i> Trash</span>
                        <span class="duralux-badge">{{ $counts['trash'] ?? 0 }}</span>
                    </a>
                </li>
            </ul>

            <div class="duralux-section-label mt-4">Email Channels</div>
            <ul class="duralux-nav-list">
                @foreach(($configurations ?? []) as $config)
                    <li class="duralux-nav-item">
                        <a href="{{ route('emails.index', ['account_id' => $config->id]) }}" class="duralux-nav-link {{ optional($currentAccount ?? null)->id == $config->id ? 'active' : '' }}">
                            <span class="text-truncate" style="max-width: 170px;"><i class="fa fa-circle text-primary fs-8"></i> {{ $config->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        {{-- Main Email Detail / Conversation Area --}}
        <main class="duralux-main-area" id="mainConversationArea">
            {{-- Sticky Top Bar --}}
            <div class="duralux-top-bar">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('emails.index', ['folder' => $email->folder, 'account_id' => request('account_id')]) }}" class="duralux-btn-back">
                        <i class="fa fa-arrow-left"></i>
                        <span>Back</span>
                    </a>
                    <div class="vr h-20px mx-1 text-muted"></div>
                    <button type="button" class="duralux-btn-icon text-danger" title="Delete Email" onclick="deleteThisEmail({{ $email->id }})">
                        <i class="fa fa-trash-o"></i>
                    </button>
                    <button type="button" class="duralux-btn-icon {{ $email->is_starred ? 'text-warning' : '' }}" title="Star Email" onclick="toggleThisStar({{ $email->id }}, this)">
                        <i class="fa {{ $email->is_starred ? 'fa-star' : 'fa-star-o' }}"></i>
                    </button>
                    <button type="button" class="duralux-btn-icon" title="Print" onclick="window.print()">
                        <i class="fa fa-print"></i>
                    </button>
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-light-info fw-bold d-flex align-items-center gap-1" data-bs-toggle="dropdown" id="showLabelsDropdownBtn" title="Manage Labels">
                            <i class="fa fa-tags me-1"></i> <span class="d-none d-sm-inline">Labels</span> <i class="fa fa-caret-down ms-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg" style="min-width: 220px;" onclick="event.stopPropagation()">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="fs-8 text-muted fw-bold text-uppercase m-0">Assign Labels</h6>
                                <a href="{{ route('labels.index') }}" target="_blank" class="fs-9 text-primary fw-semibold"><i class="fa fa-cog"></i> Master</a>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                @php
                                    $clientEmail = $email->customer_email ?? '';
                                    $activeLabelIds = $threadLabelIds ?? \App\Models\EmailThreadLabel::where('thread_id', $email->thread_id)->pluck('label_id')->unique()->toArray();
                                    $allLabelsList = $allLabels ?? \App\Models\WhatsappChatLabel::orderBy('name')->get();
                                @endphp
                                @foreach($allLabelsList as $lbl)
                                    <label class="form-check form-check-custom form-check-solid d-flex align-items-center gap-2 p-1.5 rounded hover-bg-light cursor-pointer mb-0">
                                        <input class="form-check-input show-label-checkbox" type="checkbox" value="{{ $lbl->id }}" data-name="{{ $lbl->name }}" data-color="{{ $lbl->color }}" {{ in_array($lbl->id, $activeLabelIds) ? 'checked' : '' }} onchange="toggleShowThreadLabel({{ $lbl->id }}, this.checked)">
                                        <span class="badge px-2 py-1 fs-8 fw-bold" style="background-color: {{ $lbl->color }}; color: #ffffff;">{{ $lbl->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light-primary text-primary fw-bold px-3 py-2 fs-8 text-capitalize">
                        <i class="fa fa-folder-open-o me-1"></i> {{ $email->folder }}
                    </span>
                    <span class="text-muted fs-8">{{ count($threadMessages ?? [$email]) }} message(s)</span>
                </div>
            </div>

            {{-- Conversation Body --}}
            <div class="duralux-conversation-content">
                {{-- Subject Title Header --}}
                <div class="duralux-subject-header">
                    <div class="d-flex align-items-center gap-3">
                        <h2 class="duralux-subject-title">{{ $email->subject ?: '(No Subject)' }}</h2>
                        <span class="badge bg-light text-dark border px-2 py-1 fs-8">Inbox</span>
                    </div>
                    <div class="d-flex flex-wrap gap-1 mt-2" id="showLabelsBadges">
                        @php
                            $activeThreadLabels = $threadLabels ?? \App\Models\WhatsappChatLabel::whereIn('id', $activeLabelIds)->get();
                        @endphp
                        @foreach($activeThreadLabels as $tl)
                            <span class="badge px-2.5 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-sm" style="background-color: {{ $tl->color }}; color: #ffffff;">
                                <i class="fa fa-tag text-white opacity-75" style="font-size: 10px;"></i> {{ $tl->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Chronological Messages List --}}
                @foreach(($threadMessages ?? [$email]) as $msg)
                    @php
                        $isOutbound = $msg->direction === 'outbound';
                        $avatarBg = $isOutbound ? 'background: linear-gradient(135deg, #2563eb, #6366f1);' : 'background: linear-gradient(135deg, #ea580c, #f97316);';
                        $avatarLetter = strtoupper(substr($msg->from_name ?: ($msg->from_email ?: 'U'), 0, 1));
                    @endphp
                    <div class="duralux-message-card" id="msg-card-{{ $msg->id }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="duralux-avatar" style="{{ $avatarBg }}">{{ $avatarLetter }}</div>
                                <div>
                                    <div class="fw-bold text-gray-900 fs-6">
                                        {{ $msg->from_name ?: $msg->from_email }}
                                        <span class="text-muted fs-8 fw-normal">&lt;{{ $msg->from_email }}&gt;</span>
                                    </div>
                                    <div class="text-muted fs-8">to {{ $msg->to_name ?: ($msg->to_email ?: 'me') }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-muted fs-8">{{ optional($msg->received_at ?: $msg->created_at)->format('M d, Y h:i A') }}</span>
                                <button type="button" class="btn btn-sm btn-icon btn-light" onclick="setComposerMode('reply', '{{ $msg->from_email }}', '{{ addslashes($msg->subject) }}')" title="Reply to this message">
                                    <i class="fa fa-reply text-muted"></i>
                                </button>
                            </div>
                        </div>

                        <div class="duralux-message-body" id="show-msg-body-{{ $msg->id }}"></div>

                        @if($msg->attachments && $msg->attachments->count() > 0)
                            <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top" style="padding-left: 54px;">
                                @foreach($msg->attachments as $att)
                                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 bg-light border rounded">
                                        <i class="fa fa-paperclip text-primary"></i>
                                        <span class="fs-8 fw-semibold">{{ $att->filename }}</span>
                                        <span class="text-muted fs-9">({{ $att->formatted_size }})</span>
                                        <a href="{{ route('emails.attachment.download', $att->id) }}" target="_blank" class="btn btn-xs btn-light-primary ms-2"><i class="fa fa-download"></i></a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach

                {{-- Action Pills --}}
                <div class="d-flex align-items-center gap-3 my-4">
                    <button type="button" class="duralux-action-pill" onclick="setComposerMode('reply', '{{ $email->from_email }}', '{{ addslashes($email->subject) }}')">
                        <i class="fa fa-reply text-primary"></i>
                        <span>Reply</span>
                    </button>
                    <button type="button" class="duralux-action-pill" onclick="setComposerMode('forward', '', '{{ addslashes($email->subject) }}')">
                        <i class="fa fa-share text-primary"></i>
                        <span>Forward</span>
                    </button>
                </div>

                {{-- Dedicated Inline Reply / Forward Composer Box --}}
                <div class="duralux-inline-composer" id="inlineComposerBox">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="mode-tab-btn active" id="replyTabBtn" onclick="setComposerMode('reply', '{{ $email->from_email }}', '{{ addslashes($email->subject) }}')">
                                <i class="fa fa-reply me-1"></i> Reply
                            </button>
                            <button type="button" class="mode-tab-btn" id="forwardTabBtn" onclick="setComposerMode('forward', '', '{{ addslashes($email->subject) }}')">
                                <i class="fa fa-share me-1"></i> Forward
                            </button>
                        </div>
                        <span class="text-muted fs-8" id="composerModeLabel">Replying to {{ $email->from_email }}</span>
                    </div>

                    <form id="composerForm" onsubmit="submitComposer(event)">
                        @csrf
                        <input type="hidden" name="thread_id" value="{{ $email->thread_id }}">
                        <input type="hidden" name="account_id" value="{{ optional($currentAccount ?? null)->id }}">
                        <input type="hidden" name="composer_mode" id="composerModeInput" value="reply">

                        {{-- To Field (Editable on forward or reply) --}}
                        <div class="mb-3">
                            <label class="form-label fs-8 fw-bold text-gray-700 mb-1">To:</label>
                            <input type="email" class="form-control form-control-sm" name="to_email" id="composerToInput" value="{{ $email->from_email }}" required placeholder="recipient@example.com">
                        </div>

                        {{-- Subject Field --}}
                        <div class="mb-3">
                            <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Subject:</label>
                            <input type="text" class="form-control form-control-sm" name="subject" id="composerSubjectInput" value="Re: {{ $email->subject }}" required>
                        </div>

                        {{-- Quill Editor --}}
                        <div class="mb-3">
                            <div id="showQuillEditor" style="height: 180px; background: #ffffff; border-radius: 8px;"></div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex align-items-center justify-content-between pt-2">
                            <div class="d-flex align-items-center gap-2">
                                <label class="btn btn-sm btn-icon btn-light" title="Attach file">
                                    <i class="fa fa-paperclip text-muted"></i>
                                    <input type="file" name="files[]" id="composerFileInput" multiple style="display: none;" onchange="handleFileSelected(this)">
                                </label>
                                <span class="text-muted fs-8" id="fileCountBadge"></span>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-light" onclick="discardComposer()">Discard</button>
                                <button type="submit" class="btn btn-sm btn-primary fw-bold px-5" id="composerSendBtn">
                                    <i class="fa fa-paper-plane me-1"></i> Send
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
let showQuill = null;
let currentMode = 'reply';
const originalEmail = {
    id: {{ $email->id }},
    from_name: '{{ addslashes($email->from_name ?: $email->from_email) }}',
    from_email: '{{ addslashes($email->from_email) }}',
    to_email: '{{ addslashes($email->to_email) }}',
    subject: '{{ addslashes($email->subject) }}',
    date: '{{ optional($email->received_at ?: $email->created_at)->format("D, M d, Y \\a\\t h:i A") }}',
    body_html: @json($email->body_html ? app(\App\Services\EmailHtmlSanitizer::class)->sanitize($email->body_html) : nl2br(e($email->body_plain)))
};

document.addEventListener('DOMContentLoaded', function() {
    showQuill = new Quill('#showQuillEditor', {
        theme: 'snow',
        placeholder: 'Write your message here...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'clean']
            ]
        }
    });
});

function setComposerMode(mode, toEmail, subject) {
    currentMode = mode;
    const box = document.getElementById('inlineComposerBox');
    const toInput = document.getElementById('composerToInput');
    const subjInput = document.getElementById('composerSubjectInput');
    const modeInput = document.getElementById('composerModeInput');
    const modeLabel = document.getElementById('composerModeLabel');
    const replyTab = document.getElementById('replyTabBtn');
    const forwardTab = document.getElementById('forwardTabBtn');

    box.classList.add('focused');
    box.scrollIntoView({ behavior: 'smooth', block: 'center' });

    if (mode === 'reply') {
        replyTab.classList.add('active');
        forwardTab.classList.remove('active');
        modeInput.value = 'reply';
        toInput.value = toEmail || originalEmail.from_email;
        subjInput.value = subject ? ('Re: ' + subject.replace(/^(Re:\s*)+/i, '')) : ('Re: ' + originalEmail.subject);
        modeLabel.textContent = 'Replying to ' + toInput.value;
        if (showQuill) showQuill.setText('');
    } else {
        forwardTab.classList.add('active');
        replyTab.classList.remove('active');
        modeInput.value = 'forward';
        toInput.value = '';
        toInput.placeholder = 'Enter recipient email to forward...';
        toInput.focus();
        subjInput.value = subject ? ('Fwd: ' + subject.replace(/^(Fwd:\s*)+/i, '')) : ('Fwd: ' + originalEmail.subject);
        modeLabel.textContent = 'Forwarding message to new recipient';

        // Prepopulate forwarded message content
        const forwardHeader = `
            <br><br>
            <div style="border-left: 2px solid #cbd5e1; padding-left: 12px; margin-top: 14px; color: #475569;">
                <strong>---------- Forwarded message ---------</strong><br>
                <strong>From:</strong> ${originalEmail.from_name} &lt;${originalEmail.from_email}&gt;<br>
                <strong>Date:</strong> ${originalEmail.date}<br>
                <strong>Subject:</strong> ${originalEmail.subject}<br>
                <strong>To:</strong> ${originalEmail.to_email}<br><br>
                ${originalEmail.body_html}
            </div>
        `;
        if (showQuill) {
            showQuill.root.innerHTML = forwardHeader;
        }
    }

    setTimeout(() => {
        if (mode === 'reply' && showQuill) showQuill.focus();
    }, 200);
}

function handleFileSelected(input) {
    const badge = document.getElementById('fileCountBadge');
    if (input.files && input.files.length > 0) {
        badge.textContent = `${input.files.length} file(s) attached`;
    } else {
        badge.textContent = '';
    }
}

function discardComposer() {
    if (showQuill) showQuill.setText('');
    document.getElementById('fileCountBadge').textContent = '';
    document.getElementById('inlineComposerBox').classList.remove('focused');
}

let emailResultToastTimeout = null;

function showEmailResultToast(message, success) {
    const toast = document.getElementById('emailResultToast');
    if (!toast) return;

    if (emailResultToastTimeout) clearTimeout(emailResultToastTimeout);
    toast.textContent = (success ? '✓ ' : '⚠ ') + message;
    toast.style.background = success ? '#198754' : '#dc3545';
    toast.style.display = 'block';
    emailResultToastTimeout = setTimeout(() => {
        toast.style.display = 'none';
    }, success ? 3500 : 5000);
}

async function parseEmailSendResponse(response) {
    const data = await response.json().catch(() => ({}));
    if (!response.ok || !data.success) {
        throw new Error(data.message || data.error || 'Email could not be sent');
    }
    return data;
}

function currentEmailCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

async function sendEmailFormData(formData, retried = false) {
    formData.set('_token', currentEmailCsrfToken());
    let response = await fetch('{{ route("emails.send") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': currentEmailCsrfToken(),
            'Accept': 'application/json'
        },
        body: formData
    });

    if (response.status === 419 && !retried) {
        const tokenResponse = await fetch('{{ route("emails.csrf-token") }}', {
            headers: { 'Accept': 'application/json' },
            cache: 'no-store'
        });
        const tokenData = await tokenResponse.json();
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta && tokenData.token) meta.content = tokenData.token;
        return sendEmailFormData(formData, true);
    }

    return response;
}

function submitComposer(e) {
    e.preventDefault();
    const sendBtn = document.getElementById('composerSendBtn');
    const originalText = sendBtn.innerHTML;
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Sending...';

    const formData = new FormData(document.getElementById('composerForm'));
    const bodyHtml = showQuill ? showQuill.root.innerHTML : '';
    formData.append('body_html', bodyHtml);
    formData.append('body_plain', showQuill ? showQuill.getText() : '');

    sendEmailFormData(formData)
    .then(parseEmailSendResponse)
    .then(() => {
        showEmailResultToast('Email sent successfully.', true);
        sendBtn.innerHTML = '<i class="fa fa-check me-1"></i> Sent';
        setTimeout(() => window.location.reload(), 1200);
    })
    .catch(err => {
        showEmailResultToast('Email failed: ' + (err.message || 'Unable to send email'), false);
        sendBtn.disabled = false;
        sendBtn.innerHTML = originalText;
    });
}

function deleteThisEmail(id) {
    if (!confirm('Move this email to Trash?')) return;
    fetch(`{{ url('emails') }}/` + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        window.location.href = '{{ route("emails.index", ["account_id" => request("account_id")]) }}';
    });
}

function toggleThisStar(id, btn) {
    const icon = btn.querySelector('i');
    btn.classList.toggle('text-warning');
    if (btn.classList.contains('text-warning')) {
        icon.className = 'fa fa-star';
    } else {
        icon.className = 'fa fa-star-o';
    }

    fetch(`{{ url('emails') }}/` + id + `/star`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    });
}

// Live Updates & Chime Sound for Show Page
let lastKnownShowMsgId = null;
function playShowEmailSound() {
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(587.33, audioCtx.currentTime);
        osc.frequency.setValueAtTime(880, audioCtx.currentTime + 0.08);
        gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.4);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + 0.4);
    } catch (e) {}
}

function checkShowEmailUpdates() {
    const url = new URL('{{ route("emails.updates") }}', window.location.origin);
    const accId = '{{ optional($currentAccount ?? null)->id }}';
    if (accId) url.searchParams.set('account_id', accId);

    fetch(url, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
        .then(r => r.json())
        .then(data => {
            if (lastKnownShowMsgId !== null && data.latest_id > lastKnownShowMsgId && data.latest_email && data.latest_email.direction === 'inbound') {
                playShowEmailSound();
                showEmailResultToast('New reply received: ' + data.latest_email.subject, true);
                if (data.latest_email.thread_id === '{{ $email->thread_id }}') {
                    setTimeout(() => window.location.reload(), 1500);
                }
            }
            lastKnownShowMsgId = data.latest_id;
        })
        .catch(() => {});
}
setTimeout(checkShowEmailUpdates, 2000);
setInterval(checkShowEmailUpdates, 4000);

function renderIsolatedEmailBody(container, rawHtml, plainText) {
    if (!container) return;

    const content = rawHtml || ('<pre style="font-family: inherit; white-space: pre-wrap; margin: 0; color: #334155; font-size: 14px;">' + (plainText || '') + '</pre>');

    const iframe = document.createElement('iframe');
    iframe.setAttribute('frameborder', '0');
    iframe.setAttribute('scrolling', 'no');
    iframe.style.width = '100%';
    iframe.style.height = '60px';
    iframe.style.border = 'none';
    iframe.style.overflow = 'hidden';
    iframe.style.display = 'block';
    iframe.style.background = 'transparent';

    const adjustHeight = () => {
        try {
            if (!iframe.contentWindow || !iframe.contentWindow.document) return;
            const doc = iframe.contentWindow.document;
            const body = doc.body;
            if (!body) return;

            body.style.height = 'auto';
            body.style.minHeight = '0px';
            if (doc.documentElement) {
                doc.documentElement.style.height = 'auto';
                doc.documentElement.style.minHeight = '0px';
            }

            // Find exact bottom of all rendered contents
            let maxBottom = 0;
            const elements = body.querySelectorAll('*');
            elements.forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.bottom > maxBottom) {
                    maxBottom = rect.bottom;
                }
            });

            const exactHeight = Math.ceil(Math.max(body.scrollHeight, maxBottom, 30));
            iframe.style.height = exactHeight + 'px';
        } catch (e) {}
    };

    const docContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <base target="_blank">
            <style>
                html, body {
                    margin: 0 !important;
                    padding: 0 !important;
                    height: auto !important;
                    min-height: 0 !important;
                    background: transparent;
                }
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                    font-size: 14px;
                    line-height: 1.5;
                    color: #1e293b;
                    word-break: break-word;
                    overflow-wrap: break-word;
                }
                table, tr, td, div {
                    height: auto !important;
                    min-height: 0 !important;
                }
                img {
                    max-width: 100% !important;
                    height: auto !important;
                }
            </style>
        </head>
        <body>${content}</body>
        </html>
    `;

    container.innerHTML = '';
    container.appendChild(iframe);
    iframe.srcdoc = docContent;

    iframe.onload = () => {
        adjustHeight();
        setTimeout(adjustHeight, 100);
        setTimeout(adjustHeight, 500);
        setTimeout(adjustHeight, 1200);

        try {
            const doc = iframe.contentWindow?.document;
            if (doc) {
                const images = doc.querySelectorAll('img');
                images.forEach(img => {
                    if (!img.complete) {
                        img.addEventListener('load', adjustHeight);
                        img.addEventListener('error', adjustHeight);
                    }
                });
            }
        } catch (err) {}
    };
}

// Initialize rendering for all thread messages
document.addEventListener('DOMContentLoaded', function() {
    @foreach($threadMessages as $msg)
        renderIsolatedEmailBody(
            document.getElementById('show-msg-body-{{ $msg->id }}'),
            @json($msg->body_html),
            @json($msg->body_plain)
        );
    @endforeach
});

function toggleShowThreadLabel(labelId, isChecked) {
    const checkedBoxes = Array.from(document.querySelectorAll('.show-label-checkbox:checked'));
    const checkedIds = checkedBoxes.map(c => parseInt(c.value));
    const labelsData = checkedBoxes.map(c => ({
        id: parseInt(c.value),
        name: c.dataset.name,
        color: c.dataset.color
    }));

    // Optimistic instant UI update (0ms real-time feedback)
    const container = document.getElementById('showLabelsBadges');
    if (container) {
        if (labelsData.length === 0) {
            container.innerHTML = '';
        } else {
            container.innerHTML = labelsData.map(l => `
                <span class="badge px-2.5 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-sm animate__animated animate__fadeIn" style="background-color: ${l.color}; color: #ffffff;">
                    <i class="fa fa-tag text-white opacity-75" style="font-size: 10px;"></i> ${l.name}
                </span>
            `).join('');
        }
    }

    fetch('{{ route("emails.labels.save") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            thread_id: '{{ $email->thread_id }}',
            email: '{{ $email->customer_email }}',
            labels: checkedIds
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.labels) {
            if (container) {
                container.innerHTML = data.labels.map(l => `
                    <span class="badge px-2.5 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-sm" style="background-color: ${l.color}; color: #ffffff;">
                        <i class="fa fa-tag text-white opacity-75" style="font-size: 10px;"></i> ${l.name}
                    </span>
                `).join('');
            }
        }
    })
    .catch(err => {
        console.error('Failed to save labels', err);
    });
}
</script>
@endpush
