@extends('layouts.app')

@push('head')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<style>
    :root {
        --gmail-blue: #0b57d0;
        --gmail-blue-hover: #0842a0;
        --gmail-surface: #ffffff;
        --gmail-bg: #f6f8fc;
        --gmail-sidebar: #f6f8fc;
        --gmail-hover: #eaf1fb;
        --gmail-active: #d3e3fd;
        --gmail-active-text: #041e49;
        --gmail-border: #e0e2e7;
        --gmail-border-subtle: #f1f3f4;
        --gmail-text: #1f1f1f;
        --gmail-text-muted: #5f6368;
        --gmail-star: #f4b400;
        --gmail-read-row: #f2f6fc;
        --gmail-unread-row: #ffffff;
        --gmail-selected-row: #c2e7ff;
        --duralux-primary: #0b57d0;
        --duralux-primary-light: #d3e3fd;
        --duralux-primary-hover: #0842a0;
        --duralux-dark: #1f1f1f;
        --duralux-gray: #5f6368;
        --duralux-border: #e0e2e7;
        --duralux-border-light: #f1f3f4;
        --duralux-bg: #f6f8fc;
        --duralux-white: #ffffff;
        --duralux-star: #f4b400;
        --duralux-unread-bg: #ffffff;
        --duralux-hover-bg: #eaf1fb;
    }

    .header-fixed.toolbar-fixed #kt_wrapper,
    .header-fixed #kt_wrapper,
    #kt_wrapper {
        padding-top: 65px !important;
        background: #f6f8fc !important;
    }

    body, #kt_body {
        background: #f6f8fc !important;
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
        margin: 4px 10px 6px 10px !important;
        padding: 0 !important;
    }

    .duralux-email-app {
        display: flex;
        flex: 1;
        background: #f6f8fc;
        border: none;
        overflow: hidden;
        height: 100%;
        min-height: 0;
    }

    /* Left Sidebar */
    .duralux-sidebar {
        width: 256px;
        background: #f6f8fc;
        border-right: none;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        height: 100%;
        overflow-y: auto;
    }

    .duralux-sidebar::-webkit-scrollbar {
        width: 4px;
    }
    .duralux-sidebar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .duralux-compose-btn-wrap {
        padding: 16px 16px 12px 16px;
    }

    .duralux-btn-compose {
        background: #c2e7ff !important;
        color: #001d35 !important;
        border: none;
        border-radius: 16px;
        padding: 11px 20px;
        font-weight: 600;
        font-size: 13.5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 1px 3px rgba(60, 64, 67, 0.3);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-decoration: none;
    }

    .duralux-btn-compose:hover {
        background: #b3def7 !important;
        box-shadow: 0 2px 6px rgba(60, 64, 67, 0.2);
        transform: translateY(-1px);
        color: #001d35 !important;
    }

    .duralux-nav-list {
        list-style: none;
        padding: 0;
        margin: 0 0 16px 0;
    }

    .duralux-nav-item {
        margin-bottom: 2px;
    }

    .duralux-nav-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 16px 8px 24px;
        border-radius: 0 24px 24px 0;
        margin-right: 12px;
        color: #444746;
        font-weight: 500;
        font-size: 13.5px;
        text-decoration: none;
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    .duralux-nav-link:hover {
        background: #eaebef;
        color: var(--gmail-text);
    }

    .duralux-nav-link.active {
        background: var(--gmail-active) !important;
        color: var(--gmail-active-text) !important;
        font-weight: 700;
    }

    .duralux-nav-link i {
        font-size: 14px;
        width: 20px;
        text-align: center;
        margin-right: 12px;
    }

    .duralux-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 0;
        background: transparent;
        color: #444746;
    }

    .duralux-badge-primary,
    .duralux-nav-link.active .duralux-badge {
        background: transparent;
        color: #041e49;
        font-weight: 700;
        box-shadow: none;
    }

    .duralux-section-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #747775;
        padding: 12px 24px 6px 24px;
        letter-spacing: 0.6px;
    }

    /* Main Conversation View Area - Floating Canvas */
    .duralux-main-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e0e2e7;
        margin: 0 8px 8px 0;
        box-shadow: 0 1px 3px rgba(60, 64, 67, 0.08);
        overflow-y: auto;
    }

    .duralux-main-area::-webkit-scrollbar {
        width: 6px;
    }
    .duralux-main-area::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .duralux-top-bar {
        height: 48px;
        border-bottom: 1px solid var(--gmail-border-subtle);
        border-radius: 16px 16px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 16px;
        background: #ffffff;
        position: sticky;
        top: 0;
        z-index: 10;
        flex-shrink: 0;
    }

    .gmail-icon-btn,
    .duralux-btn-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: #444746;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.15s ease, color 0.15s ease;
        text-decoration: none;
        font-size: 14px;
    }

    .gmail-icon-btn:hover,
    .duralux-btn-icon:hover {
        background-color: var(--gmail-hover);
        color: var(--gmail-text);
    }

    .gmail-wa-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #25D366 !important;
        background: #e8fff3 !important;
        border: 1px solid #b7f5d0 !important;
        border-radius: 4px !important;
        transition: all 0.2s ease !important;
        line-height: 1 !important;
        text-decoration: none !important;
        padding: 0 !important;
    }
    .gmail-wa-btn:hover {
        background: #25D366 !important;
        border-color: #25D366 !important;
        color: #ffffff !important;
    }
    .gmail-wa-btn svg {
        display: block !important;
        fill: #25D366 !important;
        transition: fill 0.2s ease !important;
    }
    .gmail-wa-btn:hover svg {
        fill: #ffffff !important;
    }

    .duralux-conversation-content {
        padding: 24px 32px 60px 32px;
        max-width: 1020px;
        margin: 0 auto;
        width: 100%;
    }

    .duralux-subject-header {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--gmail-border-subtle);
    }

    .duralux-subject-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--gmail-text);
        margin: 0;
        line-height: 1.35;
        letter-spacing: -0.2px;
    }

    .duralux-message-card {
        border: 1px solid var(--gmail-border);
        border-radius: 12px;
        background: #ffffff;
        padding: 20px 24px;
        margin-bottom: 18px;
        box-shadow: 0 1px 3px rgba(60, 64, 67, 0.08);
        transition: box-shadow 0.2s ease;
    }

    .duralux-message-card:hover {
        box-shadow: 0 2px 8px rgba(60, 64, 67, 0.12);
    }

    .duralux-message-card.collapsed {
        padding: 0;
        border-radius: 8px;
    }
    .duralux-message-card.collapsed .duralux-message-expanded-content {
        display: none !important;
    }
    .duralux-message-card.collapsed .duralux-msg-collapsed-strip {
        display: flex !important;
    }
    .duralux-message-card:not(.collapsed) .duralux-msg-collapsed-strip {
        display: none !important;
    }
    .duralux-msg-collapsed-strip {
        display: none;
        align-items: center;
        gap: 14px;
        padding: 10px 16px;
        cursor: pointer;
        user-select: none;
        border-radius: 8px;
        transition: background-color 0.15s ease;
        background: #ffffff;
    }
    .duralux-msg-collapsed-strip:hover {
        background-color: #f2f6fc;
    }

    .duralux-avatar {
        width: 40px;
        height: 40px;
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
        line-height: 1.65;
        color: var(--gmail-text);
        margin-top: 14px;
        padding-left: 52px;
        word-break: normal;
        overflow-wrap: break-word;
        width: calc(100% - 52px);
        max-width: calc(100% - 52px);
        box-sizing: border-box;
    }

    .duralux-message-body blockquote,
    .duralux-message-body .gmail_quote {
        border-left: 2px solid #cbd5e1 !important;
        background: transparent !important;
        padding: 0 0 0 10px !important;
        margin: 8px 0 !important;
        margin-inline-start: 0 !important;
        margin-inline-end: 0 !important;
        border-radius: 0 !important;
        color: inherit !important;
    }
    .duralux-message-body blockquote blockquote,
    .duralux-message-body .gmail_quote blockquote,
    .duralux-message-body blockquote .gmail_quote,
    .duralux-message-body .gmail_quote .gmail_quote {
        border: none !important;
        border-left: none !important;
        padding: 0 !important;
        padding-left: 0 !important;
        margin: 0 !important;
        margin-inline-start: 0 !important;
        margin-inline-end: 0 !important;
    }

    /* Action Pills */
    .duralux-action-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 22px;
        border-radius: 20px;
        border: 1px solid #747775;
        background: #ffffff;
        color: var(--gmail-text);
        font-weight: 500;
        font-size: 13.5px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .duralux-action-pill:hover {
        background: #f0f4f9;
        color: var(--gmail-blue);
        border-color: var(--gmail-blue);
        transform: translateY(-1px);
    }

    /* Gmail Attachment Chips */
    .gmail-attachment-card {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 14px;
        background: #f8fafc;
        border: 1px solid var(--gmail-border);
        border-radius: 8px;
        transition: all 0.15s ease;
    }

    .gmail-attachment-card:hover {
        background: #f0f4f9;
        border-color: var(--gmail-blue);
    }

    /* Inline Composer Box */
    .duralux-inline-composer {
        border: 1px solid #c4c7c5;
        border-radius: 12px;
        background: #ffffff;
        padding: 20px;
        margin-top: 24px;
        box-shadow: 0 2px 10px rgba(60, 64, 67, 0.08);
        transition: all 0.25s ease;
    }

    .duralux-inline-composer.focused {
        border-color: var(--gmail-blue);
        box-shadow: 0 4px 16px rgba(11, 87, 208, 0.15);
    }

    .mode-tab-btn {
        background: none;
        border: 1px solid #dadce0;
        padding: 5px 16px;
        font-weight: 600;
        font-size: 12.5px;
        border-radius: 16px;
        color: #444746;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .mode-tab-btn.active {
        background: #d3e3fd;
        color: #041e49;
        border-color: #d3e3fd;
    }

    .gmail-compose-input {
        border: none;
        border-bottom: 1px solid #e0e2e7;
        border-radius: 0;
        padding: 8px 4px;
        font-size: 13.5px;
        outline: none;
        width: 100%;
        transition: border-color 0.2s;
    }

    .gmail-compose-input:focus {
        border-bottom-color: var(--gmail-blue);
    }

    .btn-gmail-send {
        background: var(--gmail-blue) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13.5px;
        border-radius: 18px !important;
        padding: 8px 24px !important;
        border: none !important;
        box-shadow: 0 1px 3px rgba(60, 64, 67, 0.3);
        transition: all 0.2s ease;
    }

    .btn-gmail-send:hover {
        background: var(--gmail-blue-hover) !important;
        box-shadow: 0 2px 6px rgba(60, 64, 67, 0.2);
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
                <a href="{{ route('emails.index', ['account_id' => request('account_id')]) }}" class="duralux-btn-compose">
                    <i class="fa fa-arrow-left"></i>
                    <span>Back to Inbox</span>
                </a>
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

            <div class="duralux-section-label mt-2">
                <span>Tags & Labels</span>
                <a href="{{ route('labels.index') }}" target="_blank" title="Manage Labels" class="text-muted"><i class="fa fa-cog"></i></a>
            </div>
            <ul class="duralux-nav-list">
                @php
                    $sidebarLabels = $allLabels ?? \App\Models\WhatsappChatLabel::forEmail()->ordered()->get();
                @endphp
                @foreach($sidebarLabels as $lbl)
                    <li class="duralux-nav-item">
                        <a href="{{ route('emails.index', ['label_id' => $lbl->id, 'account_id' => request('account_id')]) }}" class="duralux-nav-link">
                            <span class="text-truncate" style="max-width: 170px;">
                                <span class="duralux-dot" style="background: {{ $lbl->color }};"></span>
                                {{ $lbl->name }}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        {{-- Main Email Detail / Conversation Area --}}
        <main class="duralux-main-area" id="mainConversationArea">
            {{-- Sticky Top Bar (Gmail Action Toolbar) --}}
            <div class="duralux-top-bar">
                <div class="d-flex align-items-center gap-1">
                    <a href="{{ route('emails.index', ['folder' => $email->folder, 'account_id' => request('account_id')]) }}" class="gmail-icon-btn" title="Back to {{ ucfirst($email->folder) }}">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                    <div style="width: 1px; height: 20px; background: #e0e2e7; margin: 0 6px;"></div>
                    <button type="button" class="gmail-icon-btn text-danger" title="Move to Trash" onclick="deleteThisEmail({{ $email->id }})">
                        <i class="fa fa-trash-o"></i>
                    </button>
                    <button type="button" class="gmail-icon-btn {{ $email->is_starred ? 'text-warning' : '' }}" title="Star Email" onclick="toggleThisStar({{ $email->id }}, this)">
                        <i class="fa {{ $email->is_starred ? 'fa-star' : 'fa-star-o' }}"></i>
                    </button>
                    <button type="button" class="gmail-icon-btn" title="Print Conversation" onclick="window.print()">
                        <i class="fa fa-print"></i>
                    </button>
                    <div class="dropdown d-inline-block">
                        <button type="button" class="gmail-icon-btn" data-bs-toggle="dropdown" id="showLabelsDropdownBtn" title="Manage Labels">
                            <i class="fa fa-tag"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-start p-3 shadow-lg" style="min-width: 230px; border-radius: 12px; border: 1px solid #dadce0;" onclick="event.stopPropagation()">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="fs-8 text-muted fw-bold text-uppercase m-0">Assign Labels</h6>
                                <a href="{{ route('labels.index') }}" target="_blank" class="fs-9 text-primary fw-semibold"><i class="fa fa-cog"></i> Master</a>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                @php
                                    $clientEmail = $email->customer_email ?? '';
                                    $activeLabelIds = $threadLabelIds ?? \App\Models\EmailThreadLabel::where('thread_id', $email->thread_id)->pluck('label_id')->unique()->toArray();
                                    $allLabelsList = $allLabels ?? \App\Models\WhatsappChatLabel::forEmail()->ordered()->get();
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
                    <span class="badge bg-light text-muted fw-semibold px-2.5 py-1.5 fs-8 text-capitalize border">
                        <i class="fa fa-folder-open-o me-1"></i> {{ $email->folder }}
                    </span>
                    <span class="text-muted fs-8">{{ count($threadMessages ?? [$email]) }} message(s)</span>
                </div>
            </div>

            {{-- Conversation Body --}}
            <div class="duralux-conversation-content">
                {{-- Subject Title Header --}}
                <div class="duralux-subject-header">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <h2 class="duralux-subject-title">{{ $email->subject ?: '(No Subject)' }}</h2>
                        <span class="badge bg-light text-muted border px-2 py-1 fs-8 text-capitalize">{{ $email->folder }}</span>
                        @php
                            $effectiveWhatsAppUrl = $clientWhatsAppUrl ?: ($fallbackWhatsAppUrl ?? route('whatsapp.chat'));
                            $isSuperAdmin = Auth::check() && (int) Auth::user()->role_id === 1;
                        @endphp
                        <a href="{{ $effectiveWhatsAppUrl }}"
                           target="_blank"
                           class="btn btn-sm btn-light-success d-inline-flex align-items-center gap-1"
                           title="WhatsApp: {{ $clientContact?->name ?: ($whatsAppPhone ?: 'Open Chat') }}">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="#25D366"><path fill="#25D366" d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                            <span>WhatsApp</span>
                        </a>
                    </div>
                    <div class="d-flex flex-wrap gap-1 mt-1" id="showLabelsBadges">
                        @php
                            $activeThreadLabels = $threadLabels ?? \App\Models\WhatsappChatLabel::whereIn('id', $activeLabelIds)->ordered()->get();
                        @endphp
                        @foreach($activeThreadLabels as $tl)
                            <span class="badge px-2.5 py-1 fs-8 fw-semibold d-inline-flex align-items-center gap-1 shadow-xs" style="background-color: {{ $tl->color }}; color: #ffffff; border-radius: 4px;">
                                <i class="fa fa-tag text-white opacity-75" style="font-size: 9px;"></i> {{ $tl->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                @php
                    $allThreadMsgs = $threadMessages ?? [$email];
                    $isMulti = count($allThreadMsgs) > 1;
                @endphp
                {{-- Chronological Messages List --}}
                @foreach($allThreadMsgs as $msg)
                    @php
                        $isOutbound = $msg->direction === 'outbound';
                        $avatarBg = $isOutbound ? 'background-color: #0b57d0;' : 'background-color: #c026d3;';
                        $displayMsgFromEmail = $isSuperAdmin ? $msg->from_email : mask_email_for_display($msg->from_email);
                        $displayMsgFromName = $isSuperAdmin ? ($msg->from_name ?: $msg->from_email) : (filter_var($msg->from_name, FILTER_VALIDATE_EMAIL) ? mask_email_for_display($msg->from_name) : ($msg->from_name ?: $displayMsgFromEmail));
                        $avatarLetter = strtoupper(substr($displayMsgFromName, 0, 1));
                        $isCollapsed = $isMulti && ($loop->iteration < count($allThreadMsgs));
                        $cleanMsgSnippet = preg_replace('/(On\s+[\s\S]*?wrote:[\s\S]*|-----Original Message-----[\s\S]*)/iu', '', $msg->body_plain ?? '');
                        $cleanMsgSnippet = trim(preg_replace('/\s+/', ' ', $cleanMsgSnippet));
                        if (empty($cleanMsgSnippet)) {
                            $cleanMsgSnippet = trim(preg_replace('/\s+/', ' ', strip_tags($msg->body_plain ?: $msg->body_html)));
                        }
                        $snippet = \Illuminate\Support\Str::limit($cleanMsgSnippet, 120);
                    @endphp
                    <div class="duralux-message-card {{ $isCollapsed ? 'collapsed' : '' }}" id="msg-card-{{ $msg->id }}">
                        {{-- Collapsed Strip (Authentic Gmail Style) --}}
                        <div class="duralux-msg-collapsed-strip" onclick="toggleShowMessage({{ $msg->id }})">
                            <div class="duralux-avatar" style="{{ $avatarBg }}; width: 28px; height: 28px; font-size: 13px;">{{ $avatarLetter }}</div>
                            <div class="fw-bold fs-7 text-truncate" style="width: 170px; color: #202124;">{{ $displayMsgFromName }}</div>
                            <div class="fs-8 text-muted text-truncate flex-grow-1">{{ $snippet }}</div>
                            <a href="{{ $effectiveWhatsAppUrl }}"
                               target="_blank"
                               class="text-success me-2 d-inline-flex align-items-center justify-content-center"
                               style="color: #25D366 !important; display: inline-flex !important;"
                               title="WhatsApp: {{ $clientContact?->name ?: ($whatsAppPhone ?: 'Open Chat') }}"
                               onclick="event.stopPropagation();">
                                <svg width="15" height="15" viewBox="0 0 16 16" fill="#25D366"><path fill="#25D366" d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                            </a>
                            <div class="fs-9 text-muted ms-auto">{{ optional($msg->received_at ?: $msg->created_at)->format('M d, h:i A') }}</div>
                        </div>

                        {{-- Expanded Message Content --}}
                        <div class="duralux-message-expanded-content">
                            <div class="d-flex align-items-center justify-content-between" onclick="toggleShowMessage({{ $msg->id }})" style="cursor: pointer;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="duralux-avatar" style="{{ $avatarBg }}">{{ $avatarLetter }}</div>
                                    <div>
                                        <div class="fw-bold fs-6 d-flex align-items-center gap-1 flex-wrap" style="color: #1f1f1f;">
                                            <span>{{ $displayMsgFromName }}</span>
                                            <span class="text-muted fs-8 fw-normal">&lt;{{ $displayMsgFromEmail }}&gt;</span>
                                            <button type="button" 
                                                    class="btn btn-icon btn-sm p-0 flex-shrink-0 ms-1" 
                                                    style="width: 20px; height: 20px; min-width: 20px; border: none; background: transparent; color: #5f6368;" 
                                                    title="Copy Email: {{ $displayMsgFromEmail }}" 
                                                    onclick="event.stopPropagation(); crmCopyToClipboard('{{ $displayMsgFromEmail }}', 'Email copied!');">
                                                <i class="fa fa-clone" style="font-size: 11px;"></i>
                                            </button>
                                            <a href="{{ $effectiveWhatsAppUrl }}" 
                                               target="_blank" 
                                               class="btn btn-icon btn-sm p-0 flex-shrink-0 ms-1 gmail-wa-btn" 
                                               style="width: 22px; height: 22px; min-width: 22px;" 
                                               title="WhatsApp: {{ $clientContact?->name ?: ($whatsAppPhone ?: 'Open Chat') }}" 
                                               onclick="event.stopPropagation();">
                                                <svg width="13" height="13" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                                            </a>
                                        </div>
                                        <div class="text-muted fs-8">to {{ $isSuperAdmin ? ($msg->to_name ?: ($msg->to_email ?: 'me')) : mask_email_for_display($msg->to_email ?: 'me') }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2" onclick="event.stopPropagation();">
                                    <span class="text-muted fs-8">{{ optional($msg->received_at ?: $msg->created_at)->format('M d, Y, h:i A') }}</span>
                                    <button type="button" class="gmail-icon-btn" onclick="setComposerMode('reply', '{{ $msg->from_email }}', '{{ addslashes($msg->subject) }}')" title="Reply to this message">
                                        <i class="fa fa-reply"></i>
                                    </button>
                                    <button type="button" class="gmail-icon-btn" onclick="toggleShowMessage({{ $msg->id }})" title="Collapse / Close message">
                                        <i class="fa fa-chevron-up"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="duralux-message-body" id="show-msg-body-{{ $msg->id }}"></div>

                            @if($msg->attachments && $msg->attachments->count() > 0)
                                <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top" style="padding-left: 52px;">
                                    @foreach($msg->attachments as $att)
                                        <div class="gmail-attachment-card">
                                            <i class="fa fa-file-text-o text-primary"></i>
                                            <div class="d-flex flex-column">
                                                <span class="fs-8 fw-semibold text-truncate" style="max-width: 220px;">{{ $att->filename }}</span>
                                                <span class="text-muted fs-9">{{ $att->formatted_size }}</span>
                                            </div>
                                            <a href="{{ route('emails.attachment.download', $att->id) }}" target="_blank" class="gmail-icon-btn ms-2" style="width: 28px; height: 28px;" title="Download"><i class="fa fa-download fs-9"></i></a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                {{-- Action Pills --}}
                <div class="d-flex align-items-center gap-2 my-4">
                    <button type="button" class="duralux-action-pill" onclick="setComposerMode('reply', '{{ $email->from_email }}', '{{ addslashes($email->subject) }}')">
                        <i class="fa fa-reply text-muted"></i>
                        <span>Reply</span>
                    </button>
                    <button type="button" class="duralux-action-pill" onclick="setComposerMode('forward', '', '{{ addslashes($email->subject) }}')">
                        <i class="fa fa-share text-muted"></i>
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

                        {{-- To Field --}}
                        <div class="mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-8 fw-semibold text-muted" style="width: 50px;">To:</span>
                                <input type="email" class="gmail-compose-input" name="to_email" id="composerToInput" value="{{ $email->from_email }}" required placeholder="recipient@example.com">
                            </div>
                        </div>

                        {{-- Subject Field --}}
                        <div class="mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-8 fw-semibold text-muted" style="width: 50px;">Subject:</span>
                                <input type="text" class="gmail-compose-input" name="subject" id="composerSubjectInput" value="Re: {{ $email->subject }}" required>
                            </div>
                        </div>

                        {{-- Quill Editor --}}
                        <div class="mb-3">
                            <div id="showQuillEditor" style="height: 180px; background: #ffffff; border-radius: 8px;"></div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex align-items-center justify-content-between pt-2">
                            <div class="d-flex align-items-center gap-2">
                                <label class="gmail-icon-btn" title="Attach file" style="cursor: pointer;">
                                    <i class="fa fa-paperclip"></i>
                                    <input type="file" name="files[]" id="composerFileInput" multiple style="display: none;" onchange="handleFileSelected(this)">
                                </label>
                                <span class="text-muted fs-8" id="fileCountBadge"></span>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-light" onclick="discardComposer()">Discard</button>
                                <button type="submit" class="btn btn-sm btn-gmail-send" id="composerSendBtn">
                                    <i class="fa fa-paper-plane me-1"></i> Send
                                </button>
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

function toggleShowMessage(id) {
    const card = document.getElementById(`msg-card-${id}`);
    if (!card) return;

    if (card.classList.contains('collapsed')) {
        card.classList.remove('collapsed');
        const iframe = card.querySelector('iframe');
        if (iframe && typeof iframe.__adjustHeight === 'function') {
            setTimeout(iframe.__adjustHeight, 30);
            setTimeout(iframe.__adjustHeight, 150);
            setTimeout(iframe.__adjustHeight, 400);
        }
    } else {
        card.classList.add('collapsed');
    }
}

function renderIsolatedEmailBody(container, rawHtml, plainText) {
    if (!container) return;

    container.style.width = '100%';
    container.style.maxWidth = '100%';
    container.style.boxSizing = 'border-box';

    const escapeEmailHtml = value => String(value ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));

    let content = rawHtml;
    if (content) {
        if (!content.includes('class="gmail_quote"') && !content.includes("class='gmail_quote'")) {
            const quoteRegex = /(<div[^>]*>|<p[^>]*>|<br\s*\/?>|\n|^)(\s*(?:On\s+[\s\S]*?wrote:|-----Original Message-----|From:\s+[\s\S]*?Sent:))/i;
            const match = content.match(quoteRegex);
            if (match && match.index !== undefined && match.index > 0) {
                const main = content.substring(0, match.index);
                const quoted = content.substring(match.index);
                content = main + '<div class="gmail_quote">' + quoted + '</div>';
            }
        }
    } else if (plainText) {
        const match = plainText.match(/^([\s\S]*?)(On\s+[\s\S]*?wrote:[\s\S]*|-----Original Message-----[\s\S]*)$/i);
        if (match && match[2]) {
            content = '<pre style="font-family: inherit; white-space: pre-wrap; margin: 0; color: #334155; font-size: 14px; word-break: normal; overflow-wrap: break-word;">' + escapeEmailHtml(match[1]) + '</pre>'
                    + '<div class="gmail_quote"><pre style="font-family: inherit; white-space: pre-wrap; margin: 0; color: #5f6368; font-size: 13px; word-break: normal; overflow-wrap: break-word;">' + escapeEmailHtml(match[2]) + '</pre></div>';
        } else {
            content = '<pre style="font-family: inherit; white-space: pre-wrap; margin: 0; color: #334155; font-size: 14px; word-break: normal; overflow-wrap: break-word;">' + escapeEmailHtml(plainText || '') + '</pre>';
        }
    }

    const iframe = document.createElement('iframe');
    iframe.setAttribute('frameborder', '0');
    iframe.setAttribute('scrolling', 'no');
    iframe.style.width = '100%';
    iframe.style.minWidth = '100%';
    iframe.style.maxWidth = '100%';
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

            const exactHeight = Math.ceil(Math.max(
                body.scrollHeight || 0,
                body.offsetHeight || 0,
                doc.documentElement ? doc.documentElement.scrollHeight : 0,
                30
            ));
            iframe.style.height = exactHeight + 'px';
        } catch (e) {}
    };
    iframe.__adjustHeight = adjustHeight;

    const docContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <base target="_blank">
            <style>
                *, *::before, *::after {
                    box-sizing: border-box !important;
                }
                html, body {
                    margin: 0 !important;
                    padding: 0 !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    height: auto !important;
                    min-height: 0 !important;
                    background: transparent;
                }
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                    font-size: 14px;
                    line-height: 1.6;
                    color: #1e293b;
                    word-break: normal !important;
                    overflow-wrap: break-word !important;
                }
                /* Reset browser default blockquote margins and prevent nested indentation creep */
                blockquote, .gmail_quote, .gmail_default {
                    margin: 8px 0 !important;
                    margin-inline-start: 0 !important;
                    margin-inline-end: 0 !important;
                    margin-block-start: 0 !important;
                    margin-block-end: 0 !important;
                    padding: 0 0 0 10px !important;
                    border-left: 2px solid #dadce0 !important;
                    border-top: none !important;
                    border-right: none !important;
                    border-bottom: none !important;
                    max-width: 100% !important;
                    box-sizing: border-box !important;
                }
                /* All nested blockquotes (reply within reply) have ZERO extra indent/border so text never drifts to the right */
                blockquote blockquote,
                .gmail_quote blockquote,
                blockquote .gmail_quote,
                .gmail_quote .gmail_quote {
                    margin: 0 !important;
                    margin-inline-start: 0 !important;
                    margin-inline-end: 0 !important;
                    margin-block-start: 0 !important;
                    margin-block-end: 0 !important;
                    padding: 0 !important;
                    padding-left: 0 !important;
                    border: none !important;
                    border-left: none !important;
                }
                table, tr, td, div {
                    height: auto !important;
                    min-height: 0 !important;
                    max-width: 100% !important;
                }
                img {
                    max-width: 100% !important;
                    height: auto !important;
                }
                /* Gmail Style Trimmed Content Toggle Button */
                .gmail-trimmed-toggle {
                    display: inline-flex !important;
                    align-items: center;
                    justify-content: center;
                    background: #e8eaed !important;
                    border: 1px solid #dadce0 !important;
                    border-radius: 4px !important;
                    padding: 2px 8px !important;
                    font-size: 14px !important;
                    font-weight: 700 !important;
                    letter-spacing: 1px !important;
                    line-height: 14px !important;
                    color: #5f6368 !important;
                    cursor: pointer !important;
                    margin: 8px 0 !important;
                    user-select: none !important;
                    transition: background 0.15s, border-color 0.15s !important;
                }
                .gmail-trimmed-toggle:hover {
                    background: #dadce0 !important;
                    color: #202124 !important;
                }
                .gmail-trimmed-toggle.is-expanded {
                    background: #d2e3fc !important;
                    border-color: #4285f4 !important;
                    color: #1a73e8 !important;
                }
                .gmail-quote-collapsed {
                    display: none !important;
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
        try {
            const doc = iframe.contentWindow?.document;
            if (doc) {
                const quotes = doc.querySelectorAll('.gmail_quote, blockquote, .gmail_extra');
                quotes.forEach(quote => {
                    if (quote.parentElement && quote.parentElement.closest('.gmail_quote, blockquote, .gmail_extra')) {
                        return;
                    }
                    quote.classList.add('gmail-quote-collapsed');

                    const btn = doc.createElement('button');
                    btn.type = 'button';
                    btn.className = 'gmail-trimmed-toggle';
                    btn.title = 'Show trimmed content';
                    btn.setAttribute('aria-label', 'Show trimmed content');
                    btn.innerHTML = '&hellip;';
                    btn.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const isHidden = quote.classList.contains('gmail-quote-collapsed');
                        if (isHidden) {
                            quote.classList.remove('gmail-quote-collapsed');
                            btn.classList.add('is-expanded');
                            btn.title = 'Hide trimmed content';
                        } else {
                            quote.classList.add('gmail-quote-collapsed');
                            btn.classList.remove('is-expanded');
                            btn.title = 'Show trimmed content';
                        }
                        adjustHeight();
                        setTimeout(adjustHeight, 50);
                        setTimeout(adjustHeight, 200);
                    };
                    quote.parentNode.insertBefore(btn, quote);
                });

                const images = doc.querySelectorAll('img');
                images.forEach(img => {
                    if (!img.complete) {
                        img.addEventListener('load', adjustHeight);
                        img.addEventListener('error', adjustHeight);
                    }
                });
            }
        } catch (err) {}

        adjustHeight();
        setTimeout(adjustHeight, 100);
        setTimeout(adjustHeight, 500);
        setTimeout(adjustHeight, 1200);
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
