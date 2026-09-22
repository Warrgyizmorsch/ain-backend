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
        padding: 16px 20px 60px 20px;
        max-width: 100%;
        margin: 0;
        width: 100%;
        overflow-x: hidden !important;
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
        margin-left: 54px;
        margin-right: 54px;
        padding: 0;
        word-break: normal;
        overflow-wrap: break-word;
        width: auto !important;
        box-sizing: border-box;
        overflow-x: hidden !important;
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

    /* Gmail Attachment Cards & Hover Overlay */
    .gmail-att-card {
        width: 195px;
        background: #f8fafc;
        border: 1px solid #dadce0;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        position: relative;
        cursor: pointer;
        transition: box-shadow 0.15s ease, border-color 0.15s ease, transform 0.1s ease;
    }

    .gmail-att-card:hover {
        border-color: #0b57d0;
        box-shadow: 0 4px 12px rgba(60, 64, 67, 0.18);
        transform: translateY(-1px);
    }

    .gmail-att-card-preview {
        height: 85px;
        background: #eef2f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0b57d0;
        font-size: 26px;
        position: relative;
        overflow: hidden;
    }

    .gmail-att-card-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gmail-att-card-overlay {
        position: absolute;
        inset: 0;
        background: rgba(32, 33, 36, 0.65);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        opacity: 0;
        transition: opacity 0.2s ease;
        backdrop-filter: blur(1px);
    }

    .gmail-att-card:hover .gmail-att-card-overlay {
        opacity: 1;
    }

    .gmail-att-action-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.95);
        color: #202124;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.15s ease, background 0.15s ease, color 0.15s ease;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    }

    .gmail-att-action-btn:hover {
        transform: scale(1.1);
        background: #ffffff;
        color: #0b57d0;
    }

    .gmail-att-card-footer {
        padding: 8px 10px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #f1f3f4;
    }

    .gmail-att-name {
        font-size: 12px;
        font-weight: 500;
        color: #202124;
        max-width: 130px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .gmail-att-size {
        font-size: 11px;
        color: #5f6368;
    }

    .gmail-att-dl-btn {
        color: #5f6368;
        padding: 5px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
    }

    .gmail-att-dl-btn:hover {
        color: #0b57d0;
        background: #eaf1fb;
    }

    /* Composer Attachment Chips */
    .gmail-composer-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 8px 12px;
        background: #f8fafc;
        border-top: 1px solid #edf2f7;
        border-bottom: 1px solid #edf2f7;
        margin-bottom: 8px;
        border-radius: 6px;
    }

    .gmail-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        border: 1px solid #dadce0;
        border-radius: 16px;
        padding: 3px 10px;
        font-size: 12px;
        color: #3c4043;
        font-weight: 500;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        max-width: 280px;
    }

    .gmail-chip.forwarded {
        border-color: #c2e7ff;
        background: #f0f7ff;
    }

    .gmail-chip-name {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 150px;
    }

    .gmail-chip-size {
        color: #70757a;
        font-size: 11px;
    }

    .gmail-chip-remove {
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        color: #5f6368;
        font-size: 14px;
        line-height: 1;
        transition: background 0.1s, color 0.1s;
    }

    .gmail-chip-remove:hover {
        background: #dadce0;
        color: #c5221f;
    }

    /* Gmail Collapsible Quote Bar */
    .gmail-quote-collapsible-bar {
        margin-top: 8px;
        margin-bottom: 10px;
    }

    .gmail-quote-toggle-btn {
        background: #f1f3f4;
        border: 1px solid #dadce0;
        border-radius: 4px;
        padding: 2px 8px;
        font-size: 12px;
        font-weight: bold;
        color: #5f6368;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        line-height: 1.4;
        transition: background 0.15s;
    }

    .gmail-quote-toggle-btn:hover {
        background: #e8eaed;
        color: #202124;
    }

    .gmail-quote-ellipsis {
        letter-spacing: 2px;
        font-size: 14px;
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

    @media (max-width: 768px) {
        .duralux-message-body,
        .duralux-inline-composer {
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
        }
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
                            $effectiveWhatsAppUrl = $clientWhatsAppUrl;
                            $isSuperAdmin = Auth::check() && (int) Auth::user()->role_id === 1;
                        @endphp
                        @if(!empty($effectiveWhatsAppUrl))
                        <a href="{{ $effectiveWhatsAppUrl }}"
                           target="_blank"
                           class="btn btn-sm btn-light-success d-inline-flex align-items-center gap-1"
                           title="WhatsApp: {{ $clientContact?->name ?: ($whatsAppPhone ?: 'Open Chat') }}">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="#25D366"><path fill="#25D366" d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                            <span>WhatsApp</span>
                        </a>
                        @endif
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
                            @if(!empty($effectiveWhatsAppUrl))
                            <a href="{{ $effectiveWhatsAppUrl }}"
                               target="_blank"
                               class="text-success me-2 d-inline-flex align-items-center justify-content-center"
                               style="color: #25D366 !important; display: inline-flex !important;"
                               title="WhatsApp: {{ $clientContact?->name ?: ($whatsAppPhone ?: 'Open Chat') }}"
                               onclick="event.stopPropagation();">
                                <svg width="15" height="15" viewBox="0 0 16 16" fill="#25D366"><path fill="#25D366" d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                            </a>
                            @endif
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
                                            @if(!empty($effectiveWhatsAppUrl))
                                            <a href="{{ $effectiveWhatsAppUrl }}" 
                                               target="_blank" 
                                               class="btn btn-icon btn-sm p-0 flex-shrink-0 ms-1 gmail-wa-btn" 
                                               style="width: 22px; height: 22px; min-width: 22px;" 
                                               title="WhatsApp: {{ $clientContact?->name ?: ($whatsAppPhone ?: 'Open Chat') }}" 
                                               onclick="event.stopPropagation();">
                                                <svg width="13" height="13" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                                            </a>
                                            @endif
                                        </div>
                                        <div class="text-muted fs-8">to {{ $isSuperAdmin ? ($msg->to_name ?: ($msg->to_email ?: 'me')) : mask_email_for_display($msg->to_email ?: 'me') }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2" onclick="event.stopPropagation();">
                                    <span class="text-muted fs-8">{{ optional($msg->received_at ?: $msg->created_at)->format('M d, Y, h:i A') }}</span>
                                    <button type="button" class="gmail-icon-btn" onclick="setComposerMode('reply', '{{ $msg->from_email }}', '{{ addslashes($msg->subject) }}', {{ $msg->id }})" title="Reply to this message">
                                        <i class="fa fa-reply"></i>
                                    </button>
                                    <button type="button" class="gmail-icon-btn" onclick="toggleShowMessage({{ $msg->id }})" title="Collapse / Close message">
                                        <i class="fa fa-chevron-up"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="duralux-message-body" id="show-msg-body-{{ $msg->id }}"></div>

                            @if($msg->attachments && $msg->attachments->count() > 0)
                                <div class="gmail-attachments-section" style="margin-left: 54px; margin-right: 54px; margin-top: 20px; padding-top: 14px; border-top: 1px solid #f1f3f4;">
                                    <div class="gmail-att-heading d-flex align-items-center gap-2 mb-3 text-muted fs-8 fw-bold">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5c0-1.38 1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z"/></svg>
                                        <span>{{ $msg->attachments->count() }} Attachment{{ $msg->attachments->count() > 1 ? 's' : '' }}</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-3">
                                        @foreach($msg->attachments as $att)
                                            @php
                                                $cleanName = iconv_mime_decode($att->filename, 0, 'UTF-8') ?: $att->filename;
                                                $ext = strtolower(pathinfo($cleanName, PATHINFO_EXTENSION));
                                                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']) || str_starts_with($att->mime_type ?? '', 'image/');
                                                $viewUrl = route('emails.attachment.view', $att->id);
                                                $dlUrl = route('emails.attachment.download', $att->id);
                                                $previewHtmlUrl = route('emails.attachment.preview-html', $att->id);
                                                $sizeStr = $att->formatted_size;
                                                $safeName = addslashes($cleanName);
                                            @endphp
                                            <div class="gmail-att-card" onclick="openAttachmentPreview({{ $att->id }}, '{{ $safeName }}', '{{ $sizeStr }}', '{{ $att->mime_type }}', '{{ $viewUrl }}', '{{ $dlUrl }}', '{{ $previewHtmlUrl }}')">
                                                <div class="gmail-att-card-preview">
                                                    @if($isImg)
                                                        <img src="{{ $viewUrl }}" alt="{{ $cleanName }}">
                                                    @elseif($ext === 'pdf' || str_contains($att->mime_type ?? '', 'pdf'))
                                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="#c5221f"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zm4.5 2h1v-3h-1v3z"/></svg>
                                                    @elseif(in_array($ext, ['doc', 'docx']) || str_contains($att->mime_type ?? '', 'word'))
                                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="#1a73e8"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                                                    @elseif(in_array($ext, ['xls', 'xlsx', 'csv']) || str_contains($att->mime_type ?? '', 'excel'))
                                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="#137333"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-2h2v2zm0-4H7v-2h2v2zm0-4H7V7h2v2zm4 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2zm4 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2z"/></svg>
                                                    @elseif(in_array($ext, ['zip', 'rar', '7z', 'tar', 'gz']))
                                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="#b06000"><path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-6 10h-2v-2h2v2zm0-4h-2v-2h2v2z"/></svg>
                                                    @else
                                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="#5f6368"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                                                    @endif
                                                    <div class="gmail-att-card-overlay">
                                                        <button type="button" class="gmail-att-action-btn" title="Preview" onclick="event.stopPropagation(); openAttachmentPreview({{ $att->id }}, '{{ $safeName }}', '{{ $sizeStr }}', '{{ $att->mime_type }}', '{{ $viewUrl }}', '{{ $dlUrl }}', '{{ $previewHtmlUrl }}')">
                                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                                        </button>
                                                        <a href="{{ $dlUrl }}" target="_blank" download class="gmail-att-action-btn" title="Download" onclick="event.stopPropagation();">
                                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="gmail-att-card-footer">
                                                    <div class="d-flex flex-column" style="max-width: 130px;">
                                                        <span class="gmail-att-name" title="{{ $cleanName }}">{{ $cleanName }}</span>
                                                        <span class="gmail-att-size">{{ $sizeStr }}</span>
                                                    </div>
                                                    <a href="{{ $dlUrl }}" target="_blank" download class="gmail-att-dl-btn" title="Download" onclick="event.stopPropagation();">
                                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                {{-- Action Pills --}}
                <div class="d-flex align-items-center gap-2 my-4" style="margin-left: 54px; margin-right: 54px;">
                    <button type="button" class="duralux-action-pill" onclick="setComposerMode('reply', '{{ $email->from_email }}', '{{ addslashes($email->subject) }}', {{ $email->id }})">
                        <i class="fa fa-reply text-muted"></i>
                        <span>Reply</span>
                    </button>
                    <button type="button" class="duralux-action-pill" onclick="setComposerMode('forward', '', '{{ addslashes($email->subject) }}', {{ $email->id }})">
                        <i class="fa fa-share text-muted"></i>
                        <span>Forward</span>
                    </button>
                </div>

                {{-- Dedicated Inline Reply / Forward Composer Box --}}
                <div class="duralux-inline-composer" id="inlineComposerBox" style="margin-left: 54px; margin-right: 54px;">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="mode-tab-btn active" id="replyTabBtn" onclick="setComposerMode('reply', '{{ $email->from_email }}', '{{ addslashes($email->subject) }}', {{ $email->id }})">
                                <i class="fa fa-reply me-1"></i> Reply
                            </button>
                            <button type="button" class="mode-tab-btn" id="forwardTabBtn" onclick="setComposerMode('forward', '', '{{ addslashes($email->subject) }}', {{ $email->id }})">
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
                        <div class="mb-2">
                            <div id="showQuillEditor" style="height: 150px; background: #ffffff; border-radius: 8px;"></div>
                        </div>

                        {{-- Gmail Collapsible Quoted Email Preview Bar --}}
                        <div class="gmail-quote-collapsible-bar" id="showQuoteCollapsibleBar" style="display: none;">
                            <button type="button" class="gmail-quote-toggle-btn" onclick="toggleShowQuotedBlock()" title="Show/hide trimmed content">
                                <span class="gmail-quote-ellipsis">•••</span>
                                <span class="fs-8 text-muted ms-2" id="showQuoteSummary"></span>
                            </button>
                            <div class="gmail-quote-preview-content p-2 mt-2 bg-light rounded border fs-8 text-muted" id="showQuotePreviewContent" style="display: none; max-height: 200px; overflow-y: auto;"></div>
                        </div>
                        <input type="hidden" name="quoted_html" id="showComposerQuotedHtml">

                        {{-- Selected & Forwarded Attachment Chips Container --}}
                        <div class="gmail-composer-chips" id="showComposerAttachmentChips" style="display: none;"></div>

                        {{-- Action Buttons --}}
                        <div class="d-flex align-items-center justify-content-between pt-2">
                            <div class="d-flex align-items-center gap-2">
                                <label class="gmail-icon-btn" title="Attach file" style="cursor: pointer;">
                                    <i class="fa fa-paperclip"></i>
                                    <input type="file" name="files[]" id="composerFileInput" multiple style="display: none;" onchange="handleShowFileSelected(this)">
                                </label>
                                <span class="text-muted fs-8" id="fileCountBadge"></span>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-light" onclick="discardComposer()">Discard</button>
                                <button type="submit" class="btn btn-sm btn-gmail-send" id="composerSendBtn">
                                    <i class="fa fa-paper-plane me-1"></i> Send
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
{{-- Gmail-Style Attachment Preview Modal --}}
<div class="modal fade" id="emailAttachmentPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 92vw; height: 90vh;">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; height: 100%; display: flex; flex-direction: column; overflow: hidden; background: #202124; color: #ffffff;">
            <div class="modal-header border-0 py-3 px-4 d-flex align-items-center justify-content-between" style="background: rgba(0,0,0,0.5); z-index: 10;">
                <div class="d-flex align-items-center gap-3 overflow-hidden">
                    <span id="previewModalIcon" class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"></span>
                    <div class="overflow-hidden">
                        <div class="modal-title text-white fw-bold fs-6 text-truncate" id="previewModalTitle" style="max-width: 65vw;">Attachment</div>
                        <div class="text-white-50 fs-8" id="previewModalSize"></div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="previewModalDownloadBtn" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2" download>
                        <i class="fa fa-download"></i> <span>Download</span>
                    </a>
                    <button type="button" class="btn btn-sm btn-icon btn-dark text-white rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="background: rgba(255,255,255,0.15); width: 32px; height: 32px;">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body p-0 flex-grow-1 d-flex align-items-center justify-content-center position-relative" id="previewModalBody" style="background: #18191c; overflow: auto;">
                <!-- dynamic preview -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="{{ asset('assets/plugins/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/docx-preview.min.js') }}"></script>
@php
    $jsThreadMessages = collect($allThreadMsgs ?? [$email])->map(function($m) {
        return [
            'id' => $m->id,
            'thread_id' => $m->thread_id,
            'message_id' => $m->message_id,
            'from_name' => $m->from_name ?: $m->from_email,
            'from_email' => $m->from_email,
            'to_email' => $m->to_email ?: '',
            'subject' => $m->subject ?: '',
            'date' => optional($m->received_at ?: $m->created_at)->format("D, M d, Y \\a\\t h:i A"),
            'body_html' => $m->body_html ? app(\App\Services\EmailHtmlSanitizer::class)->sanitize($m->body_html) : nl2br(e($m->body_plain)),
            'attachments' => $m->attachments->map(function($att) {
                return [
                    'id' => $att->id,
                    'filename' => iconv_mime_decode($att->filename, 0, 'UTF-8') ?: $att->filename,
                    'file_size' => $att->formatted_size,
                    'mime_type' => $att->mime_type,
                    'url' => route('emails.attachment.download', $att->id),
                    'view_url' => route('emails.attachment.view', $att->id),
                    'preview_html_url' => route('emails.attachment.preview-html', $att->id),
                ];
            })->values()->all(),
        ];
    })->values()->all();
@endphp
<script>
let showQuill = null;
let currentMode = 'reply';
let activeReplyTargetMsg = null;
let showUploadedFiles = [];
let showForwardedAttachments = [];
let showQuotedHtml = '';

window.threadMessagesData = @json($jsThreadMessages);
const originalEmail = window.threadMessagesData.find(m => m.id == {{ $email->id }}) || window.threadMessagesData[0] || {};

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

    // Initialize default reply mode quoted block
    setComposerMode('reply', '{{ $email->from_email }}', '{{ addslashes($email->subject) }}', {{ $email->id }});
});

function formatFileSize(bytes) {
    if (!bytes || isNaN(bytes)) return '0 B';
    bytes = parseInt(bytes);
    if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
    if (bytes >= 1024) return (bytes / 1024).toFixed(0) + ' KB';
    return bytes + ' B';
}

function getAttachmentIconSvg(mime, filename) {
    const ext = (filename || '').split('.').pop().toLowerCase();
    if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext) || (mime && mime.startsWith('image/'))) {
        return `<svg width="28" height="28" viewBox="0 0 24 24" fill="#0b57d0"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>`;
    } else if (ext === 'pdf' || (mime && mime.includes('pdf'))) {
        return `<svg width="28" height="28" viewBox="0 0 24 24" fill="#c5221f"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zm4.5 2h1v-3h-1v3z"/></svg>`;
    } else if (['zip', 'rar', 'tar', 'gz', '7z'].includes(ext) || (mime && (mime.includes('zip') || mime.includes('compressed')))) {
        return `<svg width="28" height="28" viewBox="0 0 24 24" fill="#b06000"><path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-6 10h-2v-2h2v2zm0-4h-2v-2h2v2z"/></svg>`;
    } else if (['doc', 'docx'].includes(ext) || (mime && mime.includes('word'))) {
        return `<svg width="28" height="28" viewBox="0 0 24 24" fill="#1a73e8"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>`;
    } else if (['xls', 'xlsx', 'csv'].includes(ext) || (mime && (mime.includes('excel') || mime.includes('spreadsheet')))) {
        return `<svg width="28" height="28" viewBox="0 0 24 24" fill="#137333"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-2h2v2zm0-4H7v-2h2v2zm0-4H7V7h2v2zm4 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2zm4 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2z"/></svg>`;
    }
    return `<svg width="28" height="28" viewBox="0 0 24 24" fill="#5f6368"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>`;
}

function openAttachmentPreview(attId, filename, sizeStr, mimeType, viewUrl, downloadUrl, previewHtmlUrl) {
    const modalEl = document.getElementById('emailAttachmentPreviewModal');
    if (!modalEl) {
        window.open(viewUrl || downloadUrl, '_blank');
        return;
    }

    previewHtmlUrl = previewHtmlUrl || (viewUrl ? viewUrl.replace(/\/view$/, '/preview-html') : '');

    document.getElementById('previewModalTitle').textContent = filename || 'Attachment';
    document.getElementById('previewModalSize').textContent = sizeStr ? `Size: ${sizeStr}` : '';
    const dlBtn = document.getElementById('previewModalDownloadBtn');
    if (dlBtn) {
        dlBtn.href = downloadUrl || viewUrl;
        dlBtn.setAttribute('download', filename || 'attachment');
    }

    const iconWrap = document.getElementById('previewModalIcon');
    if (iconWrap && typeof getAttachmentIconSvg === 'function') {
        iconWrap.innerHTML = getAttachmentIconSvg(mimeType, filename);
    }

    const bodyWrap = document.getElementById('previewModalBody');
    const ext = (filename || '').split('.').pop().toLowerCase();
    const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext) || (mimeType && mimeType.startsWith('image/'));
    const isPdf = ext === 'pdf' || (mimeType && mimeType.includes('pdf'));
    const isText = ['txt', 'log', 'csv', 'json', 'xml'].includes(ext);
    const isDocx = ['docx', 'dotx'].includes(ext) || (mimeType && mimeType.includes('wordprocessingml'));
    const isDoc = ['doc', 'dot'].includes(ext) || (mimeType && mimeType.includes('msword'));
    const isOdt = ['odt'].includes(ext) || (mimeType && mimeType.includes('opendocument.text'));

    if (isImage) {
        bodyWrap.innerHTML = `
            <div class="p-3 d-flex align-items-center justify-content-center w-100 h-100">
                <img src="${viewUrl}" alt="${filename}" style="max-width: 95%; max-height: 75vh; object-fit: contain; border-radius: 8px; box-shadow: 0 8px 30px rgba(0,0,0,0.5);" />
            </div>
        `;
    } else if (isPdf) {
        bodyWrap.innerHTML = `
            <iframe src="${viewUrl}" style="width: 100%; height: 75vh; border: none; background: #525659;"></iframe>
        `;
    } else if (isText) {
        bodyWrap.innerHTML = `
            <iframe src="${viewUrl}" style="width: 100%; height: 75vh; border: none; background: #ffffff; color: #111;"></iframe>
        `;
    } else if (isDocx || isDoc || isOdt) {
        bodyWrap.innerHTML = `
            <div class="w-100 h-100 d-flex flex-column" style="min-height: 550px; background: #3c4043;">
                <div id="showDocxLoadingSpinner" class="text-center p-8 d-flex flex-column align-items-center justify-content-center flex-grow-1" style="color: #ffffff; min-height: 400px;">
                    <i class="fa fa-circle-o-notch fa-spin fa-2x text-primary mb-3"></i>
                    <h5 class="fw-bold text-white mb-1">Rendering Document Preview...</h5>
                    <p class="text-white-50 fs-8 mb-0">Formatting Word pages and content</p>
                </div>
                <div id="showDocxScrollArea" style="display: none; width: 100%; height: 75vh; overflow-y: auto; padding: 24px 16px;">
                    <div id="showDocxContentBox" style="background: #ffffff; color: #202124; max-width: 850px; margin: 0 auto; box-shadow: 0 4px 20px rgba(0,0,0,0.35); border-radius: 4px; min-height: 500px; padding: 40px 48px; font-family: 'Segoe UI', Arial, sans-serif; font-size: 14px; line-height: 1.65;"></div>
                </div>
            </div>
        `;

        const spinner = document.getElementById('showDocxLoadingSpinner');
        const scrollArea = document.getElementById('showDocxScrollArea');
        const contentBox = document.getElementById('showDocxContentBox');

        const renderBackendHtmlFallback = () => {
            fetch(previewHtmlUrl)
                .then(r => r.json())
                .then(res => {
                    if (res && res.success && res.html) {
                        if (contentBox) contentBox.innerHTML = res.html;
                        if (spinner) spinner.style.display = 'none';
                        if (scrollArea) scrollArea.style.display = 'block';
                    } else {
                        showUnsupportedDocFallback(bodyWrap, filename, sizeStr, mimeType, downloadUrl || viewUrl, res?.message || 'Could not parse document.');
                    }
                })
                .catch(err => {
                    showUnsupportedDocFallback(bodyWrap, filename, sizeStr, mimeType, downloadUrl || viewUrl, err.message);
                });
        };

        if (isDocx && window.docx && typeof window.docx.renderAsync === 'function') {
            fetch(viewUrl)
                .then(res => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.blob();
                })
                .then(blob => {
                    window.docx.renderAsync(blob, contentBox, null, {
                        className: 'docx-rendered-page',
                        inWrapper: false,
                        ignoreWidth: false,
                        ignoreHeight: false,
                        breakPages: true
                    })
                    .then(() => {
                        if (spinner) spinner.style.display = 'none';
                        if (scrollArea) scrollArea.style.display = 'block';
                    })
                    .catch(renderErr => {
                        console.warn('docx-preview failed, falling back to server-side parser:', renderErr);
                        renderBackendHtmlFallback();
                    });
                })
                .catch(fetchErr => {
                    console.warn('docx fetch failed, falling back to preview-html:', fetchErr);
                    renderBackendHtmlFallback();
                });
        } else {
            renderBackendHtmlFallback();
        }
    } else {
        showUnsupportedDocFallback(bodyWrap, filename, sizeStr, mimeType, downloadUrl || viewUrl);
    }

    if (window.bootstrap && bootstrap.Modal) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    } else {
        $(modalEl).modal('show');
    }
}

function showUnsupportedDocFallback(bodyWrap, filename, sizeStr, mimeType, downloadUrl, errorMsg) {
    bodyWrap.innerHTML = `
        <div class="text-center p-8 d-flex flex-column align-items-center justify-content-center" style="min-height: 400px;">
            <div class="mb-4" style="transform: scale(2.2); transform-origin: center;">
                ${getAttachmentIconSvg(mimeType, filename)}
            </div>
            <h4 class="text-white fw-bold mt-4 mb-2">${filename}</h4>
            <p class="text-white-50 fs-7 mb-4">${sizeStr || ''} &bull; ${mimeType || 'Document'}</p>
            ${errorMsg ? `<div class="alert alert-warning py-2 px-3 fs-8 mb-4" style="max-width: 500px;">${errorMsg}</div>` : ''}
            <div class="d-flex align-items-center gap-3">
                <a href="${downloadUrl}" class="btn btn-primary px-6 py-3 fw-bold" download>
                    <i class="fa fa-download me-2"></i> Download File
                </a>
            </div>
            <p class="text-muted fs-8 mt-4 mb-0">Direct in-browser preview is not available for this specific format. Click download to open locally.</p>
        </div>
    `;
}

function renderShowAttachmentChips() {
    const container = document.getElementById('showComposerAttachmentChips');
    const badge = document.getElementById('fileCountBadge');
    if (!container) return;

    const totalCount = showForwardedAttachments.length + showUploadedFiles.length;
    if (badge) {
        badge.textContent = totalCount > 0 ? `${totalCount} attachment${totalCount > 1 ? 's' : ''}` : '';
    }

    if (totalCount === 0) {
        container.style.display = 'none';
        container.innerHTML = '';
        return;
    }

    let html = '';
    // Forwarded attachments
    showForwardedAttachments.forEach(att => {
        const cleanName = att.filename || 'Attachment';
        html += `
            <span class="gmail-chip forwarded" title="${cleanName}">
                <i class="fa fa-share text-primary me-1"></i>
                <span class="gmail-chip-name">${cleanName}</span>
                <span class="gmail-chip-size">(${att.file_size || ''})</span>
                <span class="badge bg-primary text-white fs-9 ms-1 py-0 px-1" style="font-size: 9px;">Forwarded</span>
                <span class="gmail-chip-remove ms-1" onclick="removeShowForwardedAttachment(${att.id})" title="Remove attachment">&times;</span>
            </span>
        `;
    });

    // Freshly uploaded files
    showUploadedFiles.forEach((file, index) => {
        html += `
            <span class="gmail-chip" title="${file.name}">
                <i class="fa fa-paperclip text-muted me-1"></i>
                <span class="gmail-chip-name">${file.name}</span>
                <span class="gmail-chip-size">(${formatFileSize(file.size)})</span>
                <span class="gmail-chip-remove ms-1" onclick="removeShowUploadedFile(${index})" title="Remove attachment">&times;</span>
            </span>
        `;
    });

    container.innerHTML = html;
    container.style.display = 'flex';
}

function handleShowFileSelected(input) {
    if (input.files && input.files.length > 0) {
        Array.from(input.files).forEach(f => {
            if (!showUploadedFiles.some(existing => existing.name === f.name && existing.size === f.size)) {
                showUploadedFiles.push(f);
            }
        });
    }
    input.value = '';
    renderShowAttachmentChips();
}

function removeShowUploadedFile(index) {
    showUploadedFiles.splice(index, 1);
    renderShowAttachmentChips();
}

function removeShowForwardedAttachment(attId) {
    showForwardedAttachments = showForwardedAttachments.filter(a => a.id != attId);
    renderShowAttachmentChips();
}

function toggleShowQuotedBlock() {
    const content = document.getElementById('showQuotePreviewContent');
    if (!content) return;
    content.style.display = (content.style.display === 'none') ? 'block' : 'none';
}

function setComposerMode(mode, toEmail = null, subject = null, messageId = null) {
    currentMode = mode;
    const box = document.getElementById('inlineComposerBox');
    const toInput = document.getElementById('composerToInput');
    const subjInput = document.getElementById('composerSubjectInput');
    const modeInput = document.getElementById('composerModeInput');
    const modeLabel = document.getElementById('composerModeLabel');
    const replyTab = document.getElementById('replyTabBtn');
    const forwardTab = document.getElementById('forwardTabBtn');
    const quoteBar = document.getElementById('showQuoteCollapsibleBar');
    const quoteSummary = document.getElementById('showQuoteSummary');
    const quotePreview = document.getElementById('showQuotePreviewContent');

    if (box) {
        box.classList.add('focused');
        box.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Reset attachments
    showUploadedFiles = [];
    showForwardedAttachments = [];

    // Find target message if specified, or fallback to originalEmail
    let targetMsg = null;
    if (messageId && window.threadMessagesData && window.threadMessagesData.length) {
        targetMsg = window.threadMessagesData.find(m => m.id == messageId);
    }
    if (!targetMsg) targetMsg = originalEmail;
    activeReplyTargetMsg = targetMsg;

    const targetSenderEmail = targetMsg.from_email || '';
    const targetSenderName = targetMsg.from_name || targetSenderEmail;
    const senderDisplay = targetSenderName ? `${targetSenderName} &lt;${targetSenderEmail}&gt;` : targetSenderEmail;
    const targetDateStr = targetMsg.date || '';
    const baseSubj = subject || targetMsg.subject || '';
    const cleanSubj = baseSubj.replace(/^((Re|Fwd):\s*)+/i, '').trim();
    const targetBody = targetMsg.body_html || '';

    if (mode === 'reply') {
        if (replyTab) replyTab.classList.add('active');
        if (forwardTab) forwardTab.classList.remove('active');
        if (modeInput) modeInput.value = 'reply';
        if (toInput) toInput.value = toEmail || targetSenderEmail;
        if (subjInput) subjInput.value = cleanSubj ? ('Re: ' + cleanSubj) : 'Re:';
        if (modeLabel) modeLabel.textContent = 'Replying to ' + (toInput ? toInput.value : targetSenderEmail);

        // Quoted block for reply
        showQuotedHtml = `
            <div class="gmail_quote" style="margin-top: 18px; color: #5f6368; font-size: 13px;">
                <div dir="ltr" class="gmail_attr">On ${targetDateStr}, ${senderDisplay} wrote:</div>
                <blockquote class="gmail_quote" style="margin: 4px 0 0 0.8ex; border-left: 2px solid #dadce0; padding-left: 10px; color: #3c4043;">
                    ${targetBody}
                </blockquote>
            </div>
        `;

        if (quoteBar) {
            quoteBar.style.display = 'block';
            if (quoteSummary) quoteSummary.textContent = `On ${targetDateStr}, ${targetSenderName} wrote:`;
            if (quotePreview) {
                quotePreview.innerHTML = `<div><strong>On ${targetDateStr}, ${senderDisplay} wrote:</strong></div><div class="mt-2">${targetBody}</div>`;
                quotePreview.style.display = 'none';
            }
        }
    } else {
        if (forwardTab) forwardTab.classList.add('active');
        if (replyTab) replyTab.classList.remove('active');
        if (modeInput) modeInput.value = 'forward';
        if (toInput) {
            toInput.value = '';
            toInput.placeholder = 'Enter recipient email to forward...';
        }
        if (subjInput) subjInput.value = cleanSubj ? ('Fwd: ' + cleanSubj) : 'Fwd:';
        if (modeLabel) modeLabel.textContent = 'Forwarding message to new recipient';

        // Automatically include original attachments in forwarded email
        if (targetMsg && targetMsg.attachments && targetMsg.attachments.length > 0) {
            showForwardedAttachments = [...targetMsg.attachments];
        }

        // Quoted block for forward
        showQuotedHtml = `
            <div class="gmail_quote" style="margin-top: 20px; border-left: 2px solid #dadce0; padding-left: 12px; color: #475569; font-size: 13px;">
                <div style="margin-bottom: 10px;">
                    <strong>---------- Forwarded message ---------</strong><br>
                    <strong>From:</strong> ${senderDisplay}<br>
                    <strong>Date:</strong> ${targetDateStr}<br>
                    <strong>Subject:</strong> ${baseSubj}<br>
                    <strong>To:</strong> ${targetMsg.to_email || ''}<br>
                </div>
                <div>${targetBody}</div>
            </div>
        `;

        if (quoteBar) {
            quoteBar.style.display = 'block';
            if (quoteSummary) quoteSummary.textContent = `Forwarded message from ${targetSenderName}`;
            if (quotePreview) {
                quotePreview.innerHTML = `<div><strong>---------- Forwarded message ---------</strong><br><strong>From:</strong> ${senderDisplay}<br><strong>Date:</strong> ${targetDateStr}<br><strong>Subject:</strong> ${baseSubj}</div><div class="mt-2">${targetBody}</div>`;
                quotePreview.style.display = 'none';
            }
        }
    }

    renderShowAttachmentChips();

    // Reset quill with empty text so user writes clean message
    if (showQuill) {
        showQuill.setText('');
        setTimeout(() => {
            if (mode === 'forward' && toInput) {
                toInput.focus();
            } else {
                showQuill.focus();
            }
        }, 200);
    }
}

function discardComposer() {
    if (showQuill) showQuill.setText('');
    showUploadedFiles = [];
    showForwardedAttachments = [];
    showQuotedHtml = '';
    renderShowAttachmentChips();
    const quoteBar = document.getElementById('showQuoteCollapsibleBar');
    if (quoteBar) quoteBar.style.display = 'none';
    const quotePreview = document.getElementById('showQuotePreviewContent');
    if (quotePreview) quotePreview.style.display = 'none';
    const box = document.getElementById('inlineComposerBox');
    if (box) box.classList.remove('focused');
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
    const originalText = sendBtn ? sendBtn.innerHTML : '';

    const toEmail = (document.getElementById('composerToInput')?.value || '').trim();
    const subject = (document.getElementById('composerSubjectInput')?.value || '').trim();
    if (!toEmail) {
        showEmailResultToast('Please specify a recipient email.', false);
        return;
    }

    if (sendBtn) {
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Sending...';
    }

    const userHtml = showQuill ? showQuill.root.innerHTML : '';
    const userPlain = showQuill ? showQuill.getText().trim() : '';

    let finalBodyHtml = '';
    if (userHtml && userHtml !== '<p><br></p>') {
        finalBodyHtml += `<div style="font-family: Roboto, Arial, Helvetica, sans-serif; font-size: 14px; color: #202124; line-height: 1.6;">${userHtml}</div><br>`;
    }
    if (showQuotedHtml) {
        finalBodyHtml += showQuotedHtml;
    }

    const formData = new FormData();
    formData.append('to', toEmail);
    formData.append('to_email', toEmail);
    formData.append('subject', subject || (currentMode === 'forward' ? 'Fwd:' : 'Re:'));
    formData.append('body_html', finalBodyHtml);
    formData.append('body_plain', userPlain);
    formData.append('thread_id', '{{ $email->thread_id }}');
    const parentMsgPk = (activeReplyTargetMsg && activeReplyTargetMsg.id) ? activeReplyTargetMsg.id : '{{ $email->id }}';
    formData.append('parent_message_id', parentMsgPk);
    const replyMsgId = (activeReplyTargetMsg && activeReplyTargetMsg.message_id) ? activeReplyTargetMsg.message_id : '{{ $email->message_id }}';
    if (replyMsgId) {
        formData.append('in_reply_to', replyMsgId);
    }
    formData.append('account_id', '{{ optional($currentAccount ?? null)->id }}');
    formData.append('composer_mode', currentMode);

    // Newly uploaded files
    showUploadedFiles.forEach(file => {
        formData.append('attachments[]', file);
    });

    // Forwarded attachments
    showForwardedAttachments.forEach(att => {
        formData.append('forwarded_attachment_ids[]', att.id);
    });

    sendEmailFormData(formData)
    .then(parseEmailSendResponse)
    .then(() => {
        showEmailResultToast('Email sent successfully.', true);
        if (sendBtn) sendBtn.innerHTML = '<i class="fa fa-check me-1"></i> Sent';
        setTimeout(() => window.location.reload(), 1200);
    })
    .catch(err => {
        showEmailResultToast('Email failed: ' + (err.message || 'Unable to send email'), false);
        if (sendBtn) {
            sendBtn.disabled = false;
            sendBtn.innerHTML = originalText;
        }
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

    container.style.boxSizing = 'border-box';

    const escapeEmailHtml = value => String(value ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));

    let content = rawHtml;
    if (content) {
        content = content.replace(/^\s*\*\s*\d+\s+FETCH\s*\([^\r\n]*\r?\n?/i, '')
                         .replace(/\r?\n\)\s*$/, '')
                         .replace(/=3D/g, '=')
                         .replace(/=\r?\n/g, '')
                         .replace(/width=["']?120["']?([0-9.]+%?)"?/gi, 'width="$1"')
                         .replace(/<p><\/p>/gi, '')
                         .replace(/(<p[^>]*>(?:&nbsp;|\s| )*<\/p>\s*){2,}/gi, '<p style="margin: 4px 0;">&nbsp;</p>');

        // Decode escaped HTML tags like &lt;b&gt;, &lt;/b&gt;, &lt;/tr&gt;, &lt;/html&gt;
        content = content.replace(/&lt;(\/?[a-zA-Z0-9_-]+(?:[\s\S]*?)?)&gt;/gi, function(match, inner) {
            if (/^\/?(html|body|head|table|tbody|thead|tr|td|th|p|div|span|b|strong|i|em|u|br|hr|img|a)(?:\s+[^>]*)?$/i.test(inner)) {
                return '<' + inner + '>';
            }
            return match;
        });

        // Scale email containers responsively (up to 780px card max-width) without stretching to infinity
        content = content.replace(/max-width\s*:\s*(?:5[0-9]{2}|6[0-9]{2}|7[0-9]{2}|8[0-9]{2})px/gi, 'max-width: 780px');

        // Strip duplicate consecutive closing tags e.g. </tr>\s*</tr>
        content = content.replace(/(<\/tr>\s*){2,}/gi, '</tr>');
        content = content.replace(/(<\/table>\s*){2,}/gi, '</table>');
        content = content.replace(/(<\/div>\s*){2,}/gi, '</div>');

        // Remove stray <html>, </html>, <body>, </body> inside the body
        content = content.replace(/<\/?(html|body|head)[^>]*>/gi, '');

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
    iframe.style.minWidth = '0';
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
            const root = doc.getElementById('email-inner-root') || doc.body;
            if (!root) return;

            const rootRectHeight = root.getBoundingClientRect ? root.getBoundingClientRect().height : 0;
            const exactHeight = Math.ceil(Math.max(root.offsetHeight || 0, root.scrollHeight || 0, rootRectHeight, 30));
            iframe.style.height = (exactHeight + 6) + 'px';
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
                    overflow-x: hidden !important;
                    background: transparent;
                }
                #email-inner-root {
                    display: flow-root !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    height: auto !important;
                    min-height: 0 !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    overflow-x: hidden !important;
                }
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                    font-size: 14px;
                    line-height: 1.6;
                    color: #1e293b;
                    word-break: normal !important;
                    overflow-wrap: break-word !important;
                }
                p {
                    margin: 0 0 8px 0;
                }
                .MsoNormal, li.MsoNormal, div.MsoNormal {
                    margin: 0 0 6px 0 !important;
                }
                div[align="center"], center {
                    text-align: center !important;
                }
                div[align="center"] > table, center > table {
                    margin: 12px auto !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                    max-width: 780px !important;
                }
                /* Email templates and cards: centered, responsive max-width */
                .wrapper, div.wrapper, table.wrapper, [class*="wrapper"] {
                    width: 100% !important;
                    max-width: 100% !important;
                    margin: 0 auto !important;
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                    background-color: transparent !important;
                }
                .main-table, table.main-table, [class*="main-table"],
                .email-container, [class*="container"],
                .content-table, [class*="content-table"],
                table[align="center"],
                table.nl2go-body-table,
                table[width="595"], table[width="600"], table[width="640"], table[width="650"], table[width="700"], table[width="800"] {
                    width: 100% !important;
                    max-width: 780px !important;
                    margin: 16px auto !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                    box-sizing: border-box !important;
                }
                [style*="max-width: 600px"], [style*="max-width:600px"],
                [style*="max-width: 520px"], [style*="max-width:520px"],
                [style*="max-width: 640px"], [style*="max-width:640px"],
                [style*="max-width: 650px"], [style*="max-width:650px"],
                [style*="max-width: 700px"], [style*="max-width:700px"],
                [style*="max-width: 800px"], [style*="max-width:800px"] {
                    max-width: 780px !important;
                    width: 100% !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                }
                table {
                    border-collapse: collapse !important;
                    max-width: 100% !important;
                    box-sizing: border-box !important;
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
        <body><div id="email-inner-root">${content}</div></body>
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

                // Center email cards & templates responsively (max 780px) inside document
                doc.querySelectorAll('.wrapper').forEach(el => {
                    el.style.width = '100%';
                    el.style.maxWidth = '100%';
                    el.style.marginLeft = 'auto';
                    el.style.marginRight = 'auto';
                });
                doc.querySelectorAll('.main-table, table[align="center"], table[width="595"], table[width="600"], table[width="640"], table[width="650"], table[width="700"], table[width="800"]').forEach(el => {
                    el.style.width = '100%';
                    el.style.maxWidth = '780px';
                    el.style.marginLeft = 'auto';
                    el.style.marginRight = 'auto';
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
