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

    /* Eliminate Metronic toolbar-fixed empty gap */
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

    /* Outer Wrapper - Strict Viewport Lock with exact 5px gap below header */
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
        height: 100%;
        min-height: 0;
        background: var(--duralux-white);
        border: 1.5px solid var(--duralux-border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        position: relative;
    }

    /* 1. Left Duralux Sidebar */
    .duralux-sidebar {
        width: 255px;
        background: #f8fafc;
        border-right: 1.5px solid var(--duralux-border);
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        height: 100%;
        overflow: hidden;
    }

    .duralux-sidebar-top {
        padding: 14px 14px 12px 14px;
        flex-shrink: 0;
        background: #f8fafc;
        border-bottom: 1px solid var(--duralux-border);
    }

    .btn-duralux-compose {
        background: linear-gradient(135deg, #3454d1 0%, #4361ee 100%);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13.5px;
        border-radius: 8px;
        padding: 11px 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        border: none;
        box-shadow: 0 4px 12px rgba(52, 84, 209, 0.32);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-duralux-compose:hover {
        background: linear-gradient(135deg, #2844b8 0%, #3454d1 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(52, 84, 209, 0.42);
    }

    .duralux-sidebar-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 10px 10px 16px 10px;
    }

    .duralux-sidebar-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .duralux-sidebar-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .duralux-nav-list {
        list-style: none;
        padding: 0;
        margin: 0 0 14px 0;
    }

    .duralux-section-label {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        padding: 12px 10px 4px;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .duralux-nav-item {
        margin-bottom: 3px;
    }

    .duralux-nav-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        border-radius: 8px;
        color: #334155;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .duralux-nav-link i.nav-icon {
        width: 20px;
        font-size: 14.5px;
        margin-right: 8px;
        text-align: center;
        display: inline-block;
    }

    /* Colorful Icons */
    .duralux-nav-link .icon-inbox { color: #2563eb; }
    .duralux-nav-link .icon-sent { color: #059669; }
    .duralux-nav-link .icon-drafts { color: #d97706; }
    .duralux-nav-link .icon-starred { color: #eab308; }
    .duralux-nav-link .icon-trash { color: #ef4444; }

    .duralux-nav-link:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .duralux-nav-link.active {
        background: #e0e7ff;
        color: #1e40af;
        font-weight: 600;
        border: 1px solid #c7d2fe;
    }

    .duralux-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 10px;
        background: #e2e8f0;
        color: #334155;
    }

    .duralux-badge-primary {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35);
    }

    .duralux-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        flex-shrink: 0;
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.8);
    }

    /* 2. Right Duralux Main Area */
    .duralux-main-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        background: var(--duralux-white);
        position: relative;
    }

    /* Sticky Non-Scrolling Toolbar Header */
    .duralux-area-header {
        height: 56px;
        padding: 0 18px;
        border-bottom: 1.5px solid var(--duralux-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        flex-shrink: 0;
        z-index: 5;
    }

    .duralux-toolbar-left {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .duralux-toolbar-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .duralux-btn-icon {
        width: 34px;
        height: 34px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        background: #f8fafc;
        border: 1px solid var(--duralux-border);
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
        font-size: 13.5px;
    }

    .duralux-btn-icon:hover {
        background: #eff6ff;
        color: #2563eb;
        border-color: #93c5fd;
    }

    .duralux-btn-icon.btn-refresh:hover { background: #eff6ff; color: #2563eb; border-color: #93c5fd; }
    .duralux-btn-icon.btn-read:hover { background: #f0fdf4; color: #16a34a; border-color: #86efac; }
    .duralux-btn-icon.btn-delete:hover { background: #fef2f2; color: #dc2626; border-color: #fca5a5; }
    .duralux-btn-icon.btn-settings:hover { background: #faf5ff; color: #7e22ce; border-color: #d8b4fe; }

    .duralux-divider-v {
        width: 1.5px;
        height: 22px;
        background: var(--duralux-border);
        margin: 0 5px;
    }

    /* Search Box */
    .duralux-search-box {
        position: relative;
        width: 280px;
    }

    .duralux-search-input {
        width: 100%;
        padding: 7px 12px 7px 34px;
        border-radius: 20px;
        border: 1.5px solid var(--duralux-border);
        background: #f8fafc;
        font-size: 12.5px;
        color: #0f172a;
        outline: none;
        transition: all 0.2s ease;
    }

    .duralux-search-input:focus {
        background: #ffffff;
        border-color: #3454d1;
        box-shadow: 0 0 0 3px rgba(52, 84, 209, 0.15);
    }

    .duralux-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 13px;
        pointer-events: none;
    }

    /* Scrollable Email List */
    .duralux-email-list-wrapper {
        flex: 1;
        overflow-y: auto;
        height: 100%;
        position: relative;
        padding: 10px 12px;
        background: #f8fafc;
    }

    .duralux-email-list-wrapper.is-filter-loading::before {
        content: '';
        position: absolute;
        inset: 0;
        z-index: 50;
        background: rgba(255, 255, 255, 0.78);
        backdrop-filter: blur(1px);
    }

    .duralux-email-list-wrapper.is-filter-loading::after {
        content: '';
        position: absolute;
        z-index: 51;
        top: 54px;
        left: 50%;
        width: 32px;
        height: 32px;
        margin-left: -16px;
        border: 3px solid #dbeafe;
        border-top-color: #2563eb;
        border-radius: 50%;
        animation: emailFilterSpin .65s linear infinite;
    }

    @keyframes emailFilterSpin { to { transform: rotate(360deg); } }

    .duralux-email-list-wrapper::-webkit-scrollbar {
        width: 6px;
    }
    .duralux-email-list-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .duralux-email-item {
        display: grid;
        grid-template-columns: 110px 190px minmax(0, 1fr) 210px;
        align-items: center;
        padding: 0 18px;
        min-height: 58px;
        border: 1px solid #dbe3ee;
        cursor: pointer;
        transition: all 0.15s ease;
        position: relative;
        border-left: 3.5px solid transparent;
        background: #ffffff;
        margin-top: -1px;
    }

    .duralux-email-item:hover {
        background-color: #f8fafc;
    }

    .duralux-email-item.unread {
        background-color: #f0f6ff !important;
        border-left: 4px solid #2563eb !important;
        font-weight: 600;
    }

    .duralux-email-item.unread .duralux-email-sender {
        font-weight: 800 !important;
        color: #0f172a !important;
    }

    .duralux-email-item.unread .duralux-email-subject {
        color: #0f172a !important;
        font-weight: 800 !important;
    }

    .duralux-email-item.unread .duralux-email-time {
        color: #2563eb !important;
        font-weight: 700 !important;
    }

    .duralux-unread-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #2563eb;
        display: inline-block;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.25);
        flex-shrink: 0;
        margin-left: 4px;
    }

    .duralux-email-item.selected {
        background-color: #e0e7ff;
        border-left-color: #4338ca;
    }

    .duralux-item-left {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-right: 0;
        width: auto;
        min-height: 58px;
        padding-right: 12px;
        border-right: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .duralux-star-btn {
        background: none;
        border: none;
        color: #cbd5e1;
        font-size: 14px;
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
        transition: color 0.15s ease;
    }

    .duralux-star-btn:hover,
    .duralux-star-btn.active {
        color: var(--duralux-star);
    }

    .duralux-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        color: #ffffff;
        background: linear-gradient(135deg, #3454d1, #6366f1);
        flex-shrink: 0;
    }

    .duralux-item-content {
        display: contents;
    }

    .duralux-email-sender {
        width: auto;
        min-width: 0;
        padding: 12px;
        border-right: 1px solid #e2e8f0;
        font-size: 13px;
        color: var(--duralux-dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex-shrink: 0;
    }

    .duralux-email-body-preview {
        flex: 1;
        min-width: 0;
        font-size: 12.5px;
        color: var(--duralux-gray);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 12px;
    }

    .duralux-email-subject {
        color: var(--duralux-dark);
        font-weight: 600;
        margin-right: 6px;
    }

    .duralux-item-right {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-left: 0;
        flex-shrink: 0;
        min-width: 0;
        padding-left: 12px;
        border-left: 1px solid #e2e8f0;
        justify-content: flex-end;
    }

    .duralux-email-time {
        font-size: 11.5px;
        color: var(--duralux-gray);
        white-space: nowrap;
    }

    /* 3. Detail Reading View Pane (Slide-in / Overlay) */
    .duralux-detail-view {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #ffffff;
        display: none;
        flex-direction: column;
        z-index: 20;
        overflow: hidden;
    }

    .duralux-detail-view.active {
        display: flex;
    }

    .duralux-detail-header {
        height: 60px;
        padding: 0 24px;
        border-bottom: 1px solid var(--duralux-border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        flex-shrink: 0;
        z-index: 10;
    }

    .duralux-detail-body {
        padding: 24px 32px 60px 32px;
        flex: 1;
        overflow-y: auto;
        max-width: 1020px;
        margin: 0 auto;
        width: 100%;
    }

    .duralux-detail-body::-webkit-scrollbar {
        width: 6px;
    }
    .duralux-detail-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .duralux-thread-subject {
        font-size: 22px;
        font-weight: 800;
        color: var(--duralux-dark);
        line-height: 1.35;
        letter-spacing: -0.3px;
    }

    .duralux-thread-card {
        border: 1.5px solid var(--duralux-border-light);
        border-radius: 12px;
        padding: 22px 24px;
        margin-bottom: 20px;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
        transition: box-shadow 0.2s ease;
    }

    .duralux-thread-card:hover {
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    }

    .duralux-sender-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .duralux-sender-meta {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .duralux-email-message-text {
        font-size: 14px;
        line-height: 1.7;
        color: #334155;
        padding-left: 54px;
        word-break: break-word;
    }

    .duralux-email-message-text blockquote,
    .duralux-email-message-text .gmail_quote {
        border-left: 3px solid #cbd5e1 !important;
        background: #f8fafc !important;
        padding: 10px 14px !important;
        margin: 14px 0 !important;
        border-radius: 0 8px 8px 0 !important;
        color: #64748b !important;
    }

    .duralux-action-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 22px;
        border-radius: 24px;
        border: 1.5px solid var(--duralux-border);
        background: #ffffff;
        color: var(--duralux-gray);
        font-weight: 600;
        font-size: 13.5px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .duralux-action-pill:hover {
        background: var(--duralux-primary-light);
        color: var(--duralux-primary);
        border-color: var(--duralux-primary);
        transform: translateY(-1px);
    }

    /* Modern Inline Composer */
    .duralux-quick-reply {
        border: 2px solid var(--duralux-border);
        border-radius: 12px;
        padding: 22px;
        background: #ffffff;
        margin-top: 24px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
        transition: all 0.25s ease;
    }

    .duralux-quick-reply.highlight-focus {
        border-color: var(--duralux-primary) !important;
        box-shadow: 0 0 0 4px rgba(52, 84, 209, 0.18) !important;
    }

    .mode-tab-btn {
        background: transparent;
        border: none;
        padding: 7px 16px;
        font-weight: 700;
        font-size: 13px;
        border-radius: 8px;
        color: var(--duralux-gray);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .mode-tab-btn.active {
        background: var(--duralux-primary-light);
        color: var(--duralux-primary);
    }

    /* Gmail-Style Floating / Expandable Compose Window */
    .gmail-compose-widget {
        position: fixed;
        bottom: 0;
        right: 30px;
        width: 580px;
        height: 540px;
        background: #ffffff;
        border: 1px solid var(--duralux-border);
        border-bottom: none;
        border-radius: 10px 10px 0 0;
        box-shadow: 0 12px 36px rgba(15, 23, 42, 0.25);
        display: none;
        flex-direction: column;
        z-index: 1060;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .gmail-compose-widget.active {
        display: flex;
    }

    /* Minimized State */
    .gmail-compose-widget.minimized {
        height: 42px !important;
        width: 260px !important;
        cursor: pointer;
    }

    .gmail-compose-widget.minimized .gmail-compose-body,
    .gmail-compose-widget.minimized .gmail-compose-footer {
        display: none !important;
    }

    /* Maximized / Fullscreen State */
    .gmail-compose-widget.maximized {
        width: 88vw !important;
        height: 88vh !important;
        right: 6vw !important;
        bottom: 6vh !important;
        border-radius: 12px !important;
        border-bottom: 1px solid var(--duralux-border);
    }

    /* Compose Header Bar */
    .gmail-compose-header {
        height: 42px;
        background: #f1f5f9;
        border-bottom: 1px solid var(--duralux-border);
        padding: 0 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-radius: 10px 10px 0 0;
        user-select: none;
    }

    .gmail-compose-title {
        font-size: 13.5px;
        font-weight: 600;
        color: #1e293b;
    }

    .gmail-compose-controls {
        display: flex;
        align-items: center;
        gap: 2px;
    }

    .gmail-ctrl-btn {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: transparent;
        color: #475569;
        font-size: 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .gmail-ctrl-btn:hover {
        background: #cbd5e1;
        color: #0f172a;
    }

    .gmail-ctrl-btn.btn-close-compose:hover {
        background: #ef4444;
        color: #ffffff;
    }

    /* Compose Body */
    .gmail-compose-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: #ffffff;
    }

    .gmail-field-row {
        padding: 6px 14px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        position: relative;
    }

    .gmail-field-label {
        width: 60px;
        font-size: 12.5px;
        color: #64748b;
        font-weight: 500;
        flex-shrink: 0;
    }

    .gmail-field-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 13px;
        color: #1e293b;
        background: transparent;
        padding: 4px 0;
    }

    .gmail-field-select {
        flex: 1;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 12.5px;
        color: #1e293b;
        padding: 3px 8px;
        outline: none;
    }

    .gmail-cc-bcc-toggle {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        display: flex;
        gap: 8px;
        user-select: none;
    }

    .gmail-cc-bcc-toggle span:hover {
        color: var(--duralux-primary);
        text-decoration: underline;
    }

    /* Quill Editor container in Floating widget */
    .gmail-editor-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        padding: 0;
    }

    .gmail-editor-container .ql-container {
        font-family: inherit;
        font-size: 13.5px;
        border: none !important;
        flex: 1;
    }

    .gmail-editor-container .ql-editor {
        padding: 12px 14px;
        min-height: 100%;
    }

    .gmail-editor-container .ql-toolbar {
        border: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
        background: #f8fafc;
        padding: 6px 10px;
    }

    /* Attached files chip list */
    .gmail-attachment-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding: 6px 14px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
    }

    .gmail-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e2e8f0;
        color: #1e293b;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11.5px;
        font-weight: 500;
    }

    .gmail-chip-remove {
        cursor: pointer;
        color: #ef4444;
    }

    /* Auto-complete recipient suggestions dropdown */
    .email-autocomplete-dropdown {
        position: absolute;
        top: 100%;
        left: 42px;
        right: 12px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        z-index: 1050;
        max-height: 240px;
        overflow-y: auto;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }

    .email-autocomplete-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        cursor: pointer;
        transition: background 0.15s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .email-autocomplete-item:last-child {
        border-bottom: none;
    }

    .email-autocomplete-item:hover,
    .email-autocomplete-item.active {
        background: #f0f6ff;
    }

    .email-autocomplete-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
    }

    .email-autocomplete-name {
        font-weight: 600;
        font-size: 13px;
        color: #1e293b;
    }

    .email-autocomplete-email {
        font-size: 11.5px;
        color: #64748b;
    }

    /* Bottom Action Footer */
    .gmail-compose-footer {
        padding: 10px 14px;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .btn-gmail-send {
        background: var(--duralux-primary);
        color: #ffffff;
        font-weight: 600;
        font-size: 13px;
        border-radius: 18px;
        padding: 7px 18px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(52, 84, 209, 0.3);
        transition: all 0.15s ease;
    }

    .btn-gmail-send:hover {
        background: var(--duralux-primary-hover);
        box-shadow: 0 4px 12px rgba(52, 84, 209, 0.4);
    }

    .gmail-footer-tools {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .gmail-tool-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.15s ease;
    }

    .gmail-tool-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    /* Floating Async Email Sending Toast (Gmail Style) */
    .email-sending-toast {
        position: fixed;
        bottom: 24px;
        left: 24px;
        background: #1e293b;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        display: flex;
        align-items: center;
        gap: 14px;
        z-index: 9999;
        font-size: 13.5px;
        font-weight: 500;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }

    .email-sending-toast.active {
        transform: translateY(0);
        opacity: 1;
        pointer-events: auto;
    }

    .email-sending-toast.toast-success {
        background: #065f46 !important;
    }

    .email-sending-toast.toast-error {
        background: #991b1b !important;
    }

    .pending-email-item {
        background: #fffbeb !important;
        border-color: #fbbf24 !important;
        border-left: 3px solid #f59e0b !important;
        animation: pulsePending 2s infinite ease-in-out;
    }

    .email-table-header {
        display: grid;
        grid-template-columns: 110px 190px minmax(0, 1fr) 210px;
        align-items: center;
        min-height: 38px;
        padding: 0 18px;
        border: 1px solid #cbd5e1;
        background: #eef2f7;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        position: sticky;
        top: 0;
        z-index: 40;
        box-shadow: 0 2px 5px rgba(15, 23, 42, .08);
    }

    .email-table-header > span:not(:first-child) {
        border-left: 1px solid #d6dee9;
        padding-left: 12px;
    }

    @media (max-width: 900px) {
        .email-table-header { display: none; }
        .duralux-email-list-wrapper { padding: 0; }
        .duralux-email-item { display: flex; padding: 12px; min-height: auto; }
        .duralux-item-left { width: auto; border-right: 0; }
        .duralux-item-content { display: flex; flex: 1; min-width: 0; align-items: center; gap: 10px; }
        .duralux-email-sender { width: 130px; padding: 0; border-right: 0; }
        .duralux-email-body-preview { padding: 0; }
        .duralux-item-right { min-width: auto; border-left: 0; }
    }

    @keyframes pulsePending {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
</style>
@endpush

@section('content')
<div class="duralux-email-wrapper">
    {{-- Main Duralux Email App UI --}}
    <div class="duralux-email-app">
        {{-- 1. Left Duralux Sidebar --}}
        <div class="duralux-sidebar">
            {{-- Sticky Compose Button Area --}}
            <div class="duralux-sidebar-top">
                <button type="button" class="btn-duralux-compose" onclick="openComposeModal()">
                    <i class="fa fa-pencil"></i>
                    <span>Compose</span>
                </button>
            </div>

            {{-- Independent Scrollable Navigation Area --}}
            <div class="duralux-sidebar-scroll">
                {{-- Folders List --}}
                <ul class="duralux-nav-list">
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ (!request('folder') || request('folder') === 'inbox') ? 'active' : '' }}" onclick="filterFolder('inbox', this)">
                            <span><i class="fa fa-inbox nav-icon icon-inbox"></i> Inbox</span>
                            @if(($counts['inbox'] ?? 0) > 0)
                                <span class="duralux-badge duralux-badge-primary" id="unreadInboxBadge">{{ $counts['inbox'] }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ request('folder') === 'all' ? 'active' : '' }}" onclick="filterFolder('all', this)">
                            <span><i class="fa fa-envelope-o nav-icon text-muted"></i> All Mail</span>
                            <span class="duralux-badge">{{ $counts['all'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ request('folder') === 'sent' ? 'active' : '' }}" onclick="filterFolder('sent', this)">
                            <span><i class="fa fa-paper-plane-o nav-icon icon-sent"></i> Sent</span>
                            @if(($counts['sent'] ?? 0) > 0)
                                <span class="duralux-badge">{{ $counts['sent'] }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ in_array(request('folder'), ['draft', 'drafts']) ? 'active' : '' }}" onclick="filterFolder('drafts', this)">
                            <span><i class="fa fa-file-text-o nav-icon icon-drafts"></i> Drafts</span>
                            @if(($counts['drafts'] ?? 0) > 0)
                                <span class="duralux-badge">{{ $counts['drafts'] }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ request('folder') === 'starred' ? 'active' : '' }}" onclick="filterFolder('starred', this)">
                            <span><i class="fa fa-star nav-icon icon-starred"></i> Starred</span>
                            @if(($counts['starred'] ?? 0) > 0)
                                <span class="duralux-badge">{{ $counts['starred'] }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ request('folder') === 'trash' ? 'active' : '' }}" onclick="filterFolder('trash', this)">
                            <span><i class="fa fa-trash-o nav-icon icon-trash"></i> Trash</span>
                            @if(($counts['trash'] ?? 0) > 0)
                                <span class="duralux-badge">{{ $counts['trash'] }}</span>
                            @endif
                        </a>
                    </li>
                </ul>

                {{-- Email Accounts / Channels --}}
                <div class="duralux-section-label">
                    <span>Email Accounts</span>
                    <a href="{{ route('emails.settings') }}" title="Manage Settings" class="text-muted"><i class="fa fa-cog"></i></a>
                </div>
                <ul class="duralux-nav-list">
                    @foreach(($configurations ?? []) as $index => $cfg)
                        @php
                            $isActiveAccount = (request('account_id') == $cfg->id) || (!request('account_id') && $index === 0);
                            $dotColors = ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ec4899'];
                            $dotColor = $dotColors[$index % count($dotColors)];
                        @endphp
                        <li class="duralux-nav-item">
                            <a href="{{ route('emails.index', ['account_id' => $cfg->id]) }}" class="duralux-nav-link {{ $isActiveAccount ? 'active' : '' }}" onclick="filterAccount({{ $cfg->id }}, this)">
                                <span class="text-truncate" title="{{ $cfg->email_address }}"><span class="duralux-dot" style="background: {{ $dotColor }};"></span> {{ $cfg->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- Labels / Tags --}}
                <div class="duralux-section-label">
                    <span>Tags & Labels</span>
                </div>
                <ul class="duralux-nav-list">
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link" onclick="filterTag('Order', this)">
                            <span><span class="duralux-dot" style="background: #f59e0b;"></span> Orders</span>
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link" onclick="filterTag('Support', this)">
                            <span><span class="duralux-dot" style="background: #0ea5e9;"></span> Support</span>
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link" onclick="filterTag('Urgent', this)">
                            <span><span class="duralux-dot" style="background: #e11d48;"></span> Urgent</span>
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link" onclick="filterTag('Payment', this)">
                            <span><span class="duralux-dot" style="background: #10b981;"></span> Payments</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- 2. Right Duralux Main Area --}}
        <div class="duralux-main-area">
            {{-- Perfect Fixed Toolbar Header --}}
            <div class="duralux-area-header">
                {{-- Left Action Icons --}}
                <div class="duralux-toolbar-left">
                    <div class="form-check m-0 me-2">
                        <input class="form-check-input" type="checkbox" id="selectAllEmails" onchange="toggleSelectAll(this)" title="Select All">
                    </div>
                    <button type="button" class="duralux-btn-icon btn-refresh" title="Refresh Messages" onclick="reloadEmailList()">
                        <i class="fa fa-refresh"></i>
                    </button>
                    <button type="button" class="duralux-btn-icon btn-read" title="Mark as Read" onclick="bulkMarkRead()">
                        <i class="fa fa-check"></i>
                    </button>
                    <button type="button" class="duralux-btn-icon btn-delete" title="Move to Trash" onclick="bulkDelete()">
                        <i class="fa fa-trash-o"></i>
                    </button>
                    <div class="duralux-divider-v"></div>
                    <a href="{{ route('emails.settings') }}" class="duralux-btn-icon btn-settings" title="Email Settings & Channels">
                        <i class="fa fa-sliders"></i>
                    </a>
                    @if(isset($currentAccount) && $currentAccount)
                        <span class="badge bg-light-primary text-primary border border-primary px-3 py-2 fw-bold fs-8 d-none d-md-inline-flex align-items-center gap-1">
                            <i class="fa fa-envelope text-primary"></i> {{ $currentAccount->name }}
                        </span>
                    @endif
                </div>

                {{-- Right Search Box & Pagination --}}
                <div class="duralux-toolbar-right">
                    <div class="duralux-search-box">
                        <i class="fa fa-search duralux-search-icon"></i>
                        <input type="text" class="duralux-search-input" id="emailSearchInput" placeholder="Search sender, subject..." onkeyup="handleSearch(event)">
                    </div>
                </div>
            </div>

            {{-- Email List Container (Only this part scrolls) --}}
            <div class="duralux-email-list-wrapper" id="emailListContainer">
                @include('emails._rows', ['emails' => $emails])
                <div id="infiniteScrollSpinner" style="display: none;" class="text-center py-3 text-muted fs-8">
                    <i class="fa fa-circle-o-notch fa-spin text-primary me-1"></i> Loading more emails...
                </div>
            </div>

            {{-- 3. Detail Reading View Pane (Slide-in / Overlay) --}}
            <div class="duralux-detail-view" id="emailDetailPane">
                {{-- Sticky Detail Header --}}
                <div class="duralux-detail-header">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="duralux-btn-icon" title="Back to list" onclick="closeEmailThread()">
                            <i class="fa fa-arrow-left"></i>
                        </button>
                        <button type="button" class="duralux-btn-icon text-danger" title="Delete message" onclick="deleteActiveThread()">
                            <i class="fa fa-trash-o"></i>
                        </button>
                        <button type="button" class="duralux-btn-icon" title="Star" id="detailStarBtn" onclick="toggleDetailStar()">
                            <i class="fa fa-star-o"></i>
                        </button>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-light-info fw-bold d-flex align-items-center gap-1" data-bs-toggle="dropdown" id="detailLabelsDropdownBtn" title="Manage Labels">
                                <i class="fa fa-tags me-1"></i> <span class="d-none d-sm-inline">Labels</span> <i class="fa fa-caret-down ms-1"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg" style="min-width: 220px;" onclick="event.stopPropagation()">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="fs-8 text-muted fw-bold text-uppercase m-0">Assign Labels</h6>
                                    <a href="{{ route('labels.index') }}" target="_blank" class="fs-9 text-primary fw-semibold"><i class="fa fa-cog"></i> Master</a>
                                </div>
                                <div id="threadLabelsChecklist" class="d-flex flex-column gap-1">
                                    @foreach(($allLabels ?? []) as $lbl)
                                        <label class="form-check form-check-custom form-check-solid d-flex align-items-center gap-2 p-1.5 rounded hover-bg-light cursor-pointer mb-0">
                                            <input class="form-check-input label-assign-checkbox" type="checkbox" value="{{ $lbl->id }}" id="label-chk-{{ $lbl->id }}" onchange="toggleActiveThreadLabel({{ $lbl->id }}, this.checked)">
                                            <span class="badge px-2 py-1 fs-8 fw-bold" style="background-color: {{ $lbl->color }}; color: #ffffff;">{{ $lbl->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-light-primary fw-bold" onclick="scrollReplyBox()">
                            <i class="fa fa-reply me-1"></i> Quick Reply
                        </button>
                    </div>
                </div>

                {{-- Scrollable Detail Body --}}
                <div class="duralux-detail-body">
                    <div class="d-flex flex-column mb-4 pb-2 border-bottom">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="fw-bolder text-gray-900 m-0" id="detailSubject" style="font-size: 20px;">Loading Subject...</h4>
                                <span class="badge bg-light-dark text-muted border px-2 py-1 fs-8">Inbox</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-icon btn-light" onclick="window.print()" title="Print Thread">
                                    <i class="fa fa-print"></i>
                                </button>
                            </div>
                        </div>
                        {{-- Label Badges Container --}}
                        <div class="d-flex flex-wrap gap-1 mt-2" id="detailLabelsBadges"></div>
                    </div>

                    {{-- Dynamic Thread Messages Container (Gmail-style chronologically stacked) --}}
                    <div id="detailConversationList">
                        <div class="text-center py-16 text-muted">
                            <div class="d-inline-flex p-4 rounded-circle bg-light-primary mb-3">
                                <i class="fa fa-envelope-open-o fs-2x text-primary"></i>
                            </div>
                            <h5 class="fw-bold text-gray-800">Select an email to read</h5>
                            <p class="text-muted fs-7">Choose a conversation from the list to view messages and reply.</p>
                        </div>
                    </div>

                    {{-- Action Pills --}}
                    <div class="d-flex align-items-center gap-3 my-4">
                        <button type="button" class="duralux-action-pill" onclick="setInlineComposerMode('reply')">
                            <i class="fa fa-reply text-primary"></i>
                            <span>Reply</span>
                        </button>
                        <button type="button" class="duralux-action-pill" onclick="setInlineComposerMode('forward')">
                            <i class="fa fa-share text-primary"></i>
                            <span>Forward</span>
                        </button>
                    </div>

                    {{-- Dedicated Inline Reply / Forward Composer Box --}}
                    <div class="duralux-quick-reply" id="inlineComposerContainer">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="mode-tab-btn active" id="inlineReplyTabBtn" onclick="setInlineComposerMode('reply')">
                                    <i class="fa fa-reply me-1"></i> Reply
                                </button>
                                <button type="button" class="mode-tab-btn" id="inlineForwardTabBtn" onclick="setInlineComposerMode('forward')">
                                    <i class="fa fa-share me-1"></i> Forward
                                </button>
                            </div>
                            <span class="text-muted fs-8 fw-semibold" id="inlineComposerModeLabel">Replying to sender</span>
                        </div>

                        <form id="inlineComposerForm" onsubmit="submitInlineComposer(event)">
                            {{-- Recipient Field --}}
                            <div class="mb-3">
                                <label class="form-label fs-8 fw-bold text-gray-700 mb-1">To:</label>
                                <input type="email" class="form-control form-control-sm" name="to_email" id="inlineComposerToInput" required placeholder="recipient@example.com">
                            </div>

                            {{-- Subject Field --}}
                            <div class="mb-3">
                                <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Subject:</label>
                                <input type="text" class="form-control form-control-sm" name="subject" id="inlineComposerSubjectInput" required>
                            </div>

                            {{-- Quill Rich Editor --}}
                            <div class="mb-3">
                                <div id="inlineQuillEditor" style="height: 160px; background: #ffffff; border-radius: 8px;"></div>
                            </div>

                            {{-- Action Controls --}}
                            <div class="d-flex align-items-center justify-content-between pt-2">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="btn btn-sm btn-icon btn-light" title="Attach file">
                                        <i class="fa fa-paperclip text-muted"></i>
                                        <input type="file" name="files[]" id="inlineComposerFileInput" multiple style="display: none;" onchange="handleInlineFileSelected(this)">
                                    </label>
                                    <span class="text-muted fs-8" id="inlineFileCountBadge"></span>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-light" onclick="discardInlineComposer()">Discard</button>
                                    <button type="submit" class="btn btn-sm btn-primary fw-bold px-5" id="inlineComposerSendBtn">
                                        <i class="fa fa-paper-plane me-1"></i> Send
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Gmail-Style Floating & Expandable Compose Widget --}}
<div class="gmail-compose-widget" id="gmailComposeWidget">
    {{-- Compose Header Bar --}}
    <div class="gmail-compose-header" onclick="handleHeaderClick(event)">
        <span class="gmail-compose-title" id="composeWindowTitle">New Message</span>
        <div class="gmail-compose-controls">
            <button type="button" class="gmail-ctrl-btn" title="Minimize" onclick="toggleMinimizeCompose(event)">
                <i class="fa fa-minus"></i>
            </button>
            <button type="button" class="gmail-ctrl-btn" title="Maximize / Fullscreen" onclick="toggleMaximizeCompose(event)">
                <i class="fa fa-arrows-alt" id="maximizeIcon"></i>
            </button>
            <button type="button" class="gmail-ctrl-btn btn-close-compose" title="Save & Close" onclick="closeComposeWidget(event)">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>

    {{-- Compose Form Body --}}
    <form id="composeEmailForm" onsubmit="handleSendCompose(event)" class="d-flex flex-column flex-grow-1 overflow-hidden m-0" enctype="multipart/form-data">
        @csrf
        <div class="gmail-compose-body">
            {{-- From Channel --}}
            <div class="gmail-field-row">
                <span class="gmail-field-label">From:</span>
                <select name="account_id" id="composeAccountId" class="gmail-field-select" onchange="currentAccountId = this.value">
                    @foreach(($configurations ?? []) as $cfg)
                        @php
                            $isSelected = (request('account_id') == $cfg->id) || (isset($currentAccount) && $currentAccount && $currentAccount->id == $cfg->id);
                        @endphp
                        <option value="{{ $cfg->id }}" {{ $isSelected ? 'selected' : '' }}>
                            {{ $cfg->name }} ({{ $cfg->email_address }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- To Recipient --}}
            <div class="gmail-field-row position-relative">
                <span class="gmail-field-label">To:</span>
                <input type="email" name="to_email" id="composeToEmail" class="gmail-field-input" placeholder="Recipients" required autocomplete="off">
                <div class="gmail-cc-bcc-toggle ms-auto">
                    <span onclick="toggleCcField()">Cc</span>
                    <span onclick="toggleBccField()">Bcc</span>
                </div>
                <div id="composeToEmailSuggestions" class="email-autocomplete-dropdown shadow-lg" style="display: none;"></div>
            </div>

            {{-- CC Row (Hidden by default) --}}
            <div class="gmail-field-row position-relative" id="composeCcRow" style="display: none;">
                <span class="gmail-field-label">Cc:</span>
                <input type="email" name="cc" id="composeCcEmail" class="gmail-field-input" placeholder="Cc recipients" autocomplete="off">
                <div id="composeCcEmailSuggestions" class="email-autocomplete-dropdown shadow-lg" style="display: none;"></div>
            </div>

            {{-- BCC Row (Hidden by default) --}}
            <div class="gmail-field-row position-relative" id="composeBccRow" style="display: none;">
                <span class="gmail-field-label">Bcc:</span>
                <input type="email" name="bcc" id="composeBccEmail" class="gmail-field-input" placeholder="Bcc recipients" autocomplete="off">
                <div id="composeBccEmailSuggestions" class="email-autocomplete-dropdown shadow-lg" style="display: none;"></div>
            </div>

            {{-- Subject Row --}}
            <div class="gmail-field-row">
                <input type="text" name="subject" id="composeSubject" class="gmail-field-input fw-semibold" placeholder="Subject" required autocomplete="off">
            </div>

            {{-- Rich Quill Editor Area --}}
            <div class="gmail-editor-container">
                <div id="composeQuillEditor"></div>
                <textarea name="body_html" id="composeBodyHtml" style="display: none;"></textarea>
            </div>

            {{-- Selected Attachments Chips --}}
            <div class="gmail-attachment-chips" id="composeAttachmentChips" style="display: none;"></div>
            <input type="file" id="composeFileInput" name="attachments[]" multiple style="display: none;" onchange="handleFileSelected(event)">
        </div>

        {{-- Compose Action Footer --}}
        <div class="gmail-compose-footer">
            <div class="d-flex align-items-center gap-3">
                <div class="btn-group">
                    <button type="submit" class="btn-gmail-send" id="btnSendCompose">
                        <span>Send</span>
                        <i class="fa fa-paper-plane fs-8"></i>
                    </button>
                </div>
                <div class="gmail-footer-tools">
                    <button type="button" class="gmail-tool-btn" title="Formatting Options" onclick="toggleQuillToolbar()">
                        <span class="fw-bold" style="font-size: 13px;">Aa</span>
                    </button>
                    <button type="button" class="gmail-tool-btn" title="Attach files" onclick="document.getElementById('composeFileInput').click()">
                        <i class="fa fa-paperclip"></i>
                    </button>
                    <button type="button" class="gmail-tool-btn" title="Insert Link" onclick="promptInsertLink()">
                        <i class="fa fa-link"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center gap-1">
                <button type="button" class="gmail-tool-btn text-danger" title="Discard Draft" onclick="discardCompose()">
                    <i class="fa fa-trash-o fs-6"></i>
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Non-Blocking Async Sending Notification Toast --}}
<div id="emailSendingToast" class="email-sending-toast">
    <i class="fa fa-circle-o-notch fa-spin fs-5 text-warning" id="toastStatusIcon"></i>
    <div id="toastStatusText">Sending message...</div>
</div>

{{-- Real-time Incoming Email Floating Notification Card --}}
<div id="incomingEmailAlert" style="position: fixed; top: 75px; right: 24px; z-index: 99999; max-width: 380px; width: 100%; display: none; background: #ffffff; border: 1.5px solid #3454d1; border-radius: 12px; box-shadow: 0 12px 32px rgba(15, 23, 42, 0.18); padding: 14px 16px;">
    <div class="d-flex align-items-start justify-content-between gap-2">
        <div class="d-flex align-items-center gap-3">
            <div id="incomingAlertAvatar" style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #3454d1, #6366f1); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; flex-shrink: 0;">U</div>
            <div style="min-width: 0;">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary fs-9 px-2 py-0.5">New Email</span>
                    <span class="text-muted fs-9" id="incomingAlertTime">Just now</span>
                </div>
                <div class="fw-bold text-gray-900 fs-7 text-truncate mt-1" id="incomingAlertSender">Sender Name</div>
                <div class="text-gray-700 fs-8 fw-semibold text-truncate" id="incomingAlertSubject">Subject Text</div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-icon btn-light" onclick="closeIncomingAlert()" style="width: 24px; height: 24px; border-radius: 50%;">
            <i class="fa fa-times text-muted fs-8"></i>
        </button>
    </div>
    <div class="d-flex align-items-center justify-content-end gap-2 mt-2 pt-2 border-top">
        <button type="button" class="btn btn-xs btn-light" onclick="closeIncomingAlert()">Dismiss</button>
        <button type="button" class="btn btn-xs btn-primary fw-bold" id="incomingAlertOpenBtn" onclick="openIncomingEmailFromAlert()">
            <i class="fa fa-envelope-open-o me-1"></i> View Message
        </button>
    </div>
</div>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
let currentFolder = @json($folder ?? 'inbox');
let currentAccountId = '{{ request("account_id") }}';
let emailFolderHtmlCache = @json($folderHtmlCache ?? []);
let activeThreadId = null;
let activeEmailData = null;
let inlineComposerMode = 'reply';
let composeQuill = null;
let inlineQuill = null;
let replyQuill = null;
let isQuillToolbarVisible = true;

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);

    // Init Quill for compose with rich toolbar options
    if (document.getElementById('composeQuillEditor')) {
        composeQuill = new Quill('#composeQuillEditor', {
            theme: 'snow',
            placeholder: 'Write your message here...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'header': [1, 2, 3, false] }],
                    [{ 'color': [] }, { 'background': [] }],
                    ['link', 'clean']
                ]
            }
        });
    }

    // Init Quill for inline reply/forward composer
    if (document.getElementById('inlineQuillEditor')) {
        inlineQuill = new Quill('#inlineQuillEditor', {
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
    }

    // Initialize Recipient Search Autocomplete
    initEmailAutocomplete('composeToEmail', 'composeToEmailSuggestions');
    initEmailAutocomplete('composeCcEmail', 'composeCcEmailSuggestions');
    initEmailAutocomplete('composeBccEmail', 'composeBccEmailSuggestions');

    // Check if ?compose=1 is in URL
    if (urlParams.get('compose') === '1') {
        openComposeModal();
    }

    const initialEmailId = urlParams.get('email_id') || urlParams.get('thread_id');
    if (initialEmailId) {
        openEmailThread(initialEmailId);
    }
});

function initEmailAutocomplete(inputId, dropdownId) {
    const input = document.getElementById(inputId);
    const dropdown = document.getElementById(dropdownId);
    if (!input || !dropdown) return;

    let debounceTimer = null;
    let selectedIndex = -1;
    let currentUsers = [];

    input.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(debounceTimer);
        selectedIndex = -1;

        if (query.length < 1) {
            dropdown.style.display = 'none';
            dropdown.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('emails.contacts.suggest') }}?q=${encodeURIComponent(query)}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                currentUsers = data.users || [];
                if (currentUsers.length === 0) {
                    dropdown.style.display = 'none';
                    dropdown.innerHTML = '';
                    return;
                }

                let html = '';
                currentUsers.forEach((u, idx) => {
                    const name = u.name || 'User';
                    const email = u.email || '';
                    const initials = (name || email).charAt(0).toUpperCase();
                    html += `
                        <div class="email-autocomplete-item" data-index="${idx}" data-email="${email}" data-name="${name}">
                            <div class="email-autocomplete-avatar">${initials}</div>
                            <div class="d-flex flex-column" style="min-width:0;">
                                <div class="email-autocomplete-name text-truncate">${escapeHtml(name)}</div>
                                <div class="email-autocomplete-email text-truncate">${escapeHtml(email)}</div>
                            </div>
                            ${u.mobile_no ? `<span class="badge bg-light text-muted border ms-auto fs-9">${escapeHtml(u.mobile_no)}</span>` : ''}
                        </div>
                    `;
                });

                dropdown.innerHTML = html;
                dropdown.style.display = 'block';

                dropdown.querySelectorAll('.email-autocomplete-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.stopPropagation();
                        input.value = this.dataset.email;
                        dropdown.style.display = 'none';
                        dropdown.innerHTML = '';
                        if (inputId === 'composeToEmail') {
                            document.getElementById('composeSubject')?.focus();
                        }
                    });
                });
            })
            .catch(err => console.warn('Recipient suggestion error', err));
        }, 180);
    });

    input.addEventListener('keydown', function(e) {
        const items = dropdown.querySelectorAll('.email-autocomplete-item');
        if (dropdown.style.display === 'none' || items.length === 0) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = (selectedIndex + 1) % items.length;
            updateActiveItem(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
            updateActiveItem(items);
        } else if (e.key === 'Enter') {
            if (selectedIndex >= 0 && selectedIndex < items.length) {
                e.preventDefault();
                items[selectedIndex].click();
            }
        } else if (e.key === 'Escape') {
            dropdown.style.display = 'none';
        }
    });

    function updateActiveItem(items) {
        items.forEach((it, idx) => {
            it.classList.toggle('active', idx === selectedIndex);
            if (idx === selectedIndex) {
                it.scrollIntoView({ block: 'nearest' });
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
}

/* Gmail Floating Compose Controls */
function openComposeModal(prefill = {}) {
    const widget = document.getElementById('gmailComposeWidget');
    widget.classList.remove('minimized');
    widget.classList.add('active');

    if (prefill.accountId) {
        document.getElementById('composeAccountId').value = prefill.accountId;
    } else if (currentAccountId) {
        document.getElementById('composeAccountId').value = currentAccountId;
    }

    if (prefill.to) {
        document.getElementById('composeToEmail').value = prefill.to;
    }
    if (prefill.subject) {
        document.getElementById('composeSubject').value = prefill.subject;
    }
    if (prefill.body && composeQuill) {
        composeQuill.root.innerHTML = prefill.body;
    }

    setTimeout(() => {
        if (!prefill.to) {
            document.getElementById('composeToEmail').focus();
        } else {
            composeQuill.focus();
        }
    }, 100);
}

function toggleMinimizeCompose(e) {
    if (e) e.stopPropagation();
    const widget = document.getElementById('gmailComposeWidget');
    widget.classList.toggle('minimized');
}

function toggleMaximizeCompose(e) {
    if (e) e.stopPropagation();
    const widget = document.getElementById('gmailComposeWidget');
    const icon = document.getElementById('maximizeIcon');
    widget.classList.remove('minimized');
    widget.classList.toggle('maximized');

    if (widget.classList.contains('maximized')) {
        icon.className = 'fa fa-compress';
    } else {
        icon.className = 'fa fa-arrows-alt';
    }
}

function closeComposeWidget(e) {
    if (e) e.stopPropagation();
    const widget = document.getElementById('gmailComposeWidget');
    widget.classList.remove('active', 'minimized', 'maximized');
}

function handleHeaderClick(e) {
    const widget = document.getElementById('gmailComposeWidget');
    if (widget.classList.contains('minimized')) {
        widget.classList.remove('minimized');
    }
}

function toggleCcField() {
    const row = document.getElementById('composeCcRow');
    row.style.display = row.style.display === 'none' ? 'flex' : 'none';
    if (row.style.display === 'flex') document.getElementById('composeCcEmail').focus();
}

function toggleBccField() {
    const row = document.getElementById('composeBccRow');
    row.style.display = row.style.display === 'none' ? 'flex' : 'none';
    if (row.style.display === 'flex') document.getElementById('composeBccEmail').focus();
}

function toggleQuillToolbar() {
    const tb = document.querySelector('#gmailComposeWidget .ql-toolbar');
    if (tb) {
        isQuillToolbarVisible = !isQuillToolbarVisible;
        tb.style.display = isQuillToolbarVisible ? 'block' : 'none';
    }
}

function promptInsertLink() {
    const url = prompt('Enter Web URL:');
    if (url && composeQuill) {
        const range = composeQuill.getSelection(true);
        composeQuill.insertText(range.index, url, 'link', url);
    }
}

function handleFileSelected(e) {
    const files = e.target.files;
    const chipContainer = document.getElementById('composeAttachmentChips');
    if (!files.length) {
        chipContainer.style.display = 'none';
        chipContainer.innerHTML = '';
        return;
    }

    chipContainer.style.display = 'flex';
    chipContainer.innerHTML = Array.from(files).map((f, i) => `
        <span class="gmail-chip">
            <i class="fa fa-file-o"></i>
            <span>${f.name}</span>
            <span class="gmail-chip-remove" onclick="removeSelectedFile(${i})">&times;</span>
        </span>
    `).join('');
}

function removeSelectedFile(index) {
    const input = document.getElementById('composeFileInput');
    input.value = '';
    document.getElementById('composeAttachmentChips').style.display = 'none';
    document.getElementById('composeAttachmentChips').innerHTML = '';
}

function discardCompose() {
    if (confirm('Discard this draft?')) {
        document.getElementById('composeEmailForm').reset();
        if (composeQuill) composeQuill.root.innerHTML = '';
        removeSelectedFile();
        closeComposeWidget();
    }
}

function filterFolder(folder, el) {
    currentFolder = folder;
    document.querySelectorAll('.duralux-sidebar .duralux-nav-link').forEach(link => link.classList.remove('active'));
    if (el) el.classList.add('active');
    closeEmailThread();

    const searchInput = document.getElementById('emailSearchInput');
    const searchValue = searchInput ? searchInput.value.trim() : '';

    if (!searchValue && emailFolderHtmlCache && emailFolderHtmlCache[folder]) {
        renderEmailRows(emailFolderHtmlCache[folder]);
        updateEmailBrowserUrl('');
    }
    reloadEmailList(true);
}

function filterAccount(accountId, el) {
    currentAccountId = accountId;
    lastEmailFingerprint = null;
    emailFolderHtmlCache = {};
    document.querySelectorAll('.duralux-sidebar .duralux-nav-link').forEach(link => link.classList.remove('active'));
    if (el) el.classList.add('active');
    closeEmailThread();
    reloadEmailList(true);
}

function filterTag(tag, el) {
    document.querySelectorAll('.duralux-sidebar .duralux-nav-link').forEach(link => link.classList.remove('active'));
    if (el) el.classList.add('active');
    const searchInput = document.getElementById('emailSearchInput');
    if (searchInput) searchInput.value = tag;
    reloadEmailList(true);
}

let emailSearchTimer = null;
function handleSearch(e) {
    clearTimeout(emailSearchTimer);
    emailSearchTimer = setTimeout(reloadEmailList, e.key === 'Enter' ? 0 : 250);
}

let currentPage = 1;
let hasMorePages = {{ ($emails->hasMorePages() ?? false) ? 'true' : 'false' }};
let isLoadingMore = false;

document.addEventListener('DOMContentLoaded', function() {
    initInfiniteScroll();
});

function initInfiniteScroll() {
    const container = document.getElementById('emailListContainer');
    if (!container || container.dataset.scrollReady === '1') return;
    container.dataset.scrollReady = '1';

    container.addEventListener('scroll', function() {
        if (isLoadingMore || !hasMorePages) return;
        if (container.scrollTop + container.clientHeight >= container.scrollHeight - 80) {
            loadMoreEmails();
        }
    });
}

function loadMoreEmails() {
    if (isLoadingMore || !hasMorePages) return;
    isLoadingMore = true;
    const spinner = document.getElementById('infiniteScrollSpinner');
    if (spinner) spinner.style.display = 'block';

    const nextPage = currentPage + 1;
    const url = new URL('{{ route("emails.index") }}', window.location.origin);
    url.searchParams.set('folder', currentFolder);
    if (currentAccountId) url.searchParams.set('account_id', currentAccountId);
    const searchInput = document.getElementById('emailSearchInput');
    const searchVal = searchInput ? searchInput.value : '';
    if (searchVal) url.searchParams.set('search', searchVal);
    url.searchParams.set('page', nextPage);
    url.searchParams.set('scroll', '1');

    return fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.html) {
            currentPage = data.current_page;
            hasMorePages = data.has_more;
            const spinnerEl = document.getElementById('infiniteScrollSpinner');
            if (spinnerEl) {
                spinnerEl.insertAdjacentHTML('beforebegin', data.html);
            }
        } else {
            hasMorePages = false;
        }
    })
    .catch(() => {
        hasMorePages = false;
    })
    .finally(() => {
        isLoadingMore = false;
        if (spinner) spinner.style.display = 'none';
    });
}

let emailListRequestController = null;
let emailListRequestPromise = null;

function renderEmailRows(html) {
    const listContainer = document.getElementById('emailListContainer');
    if (!listContainer) return;
    listContainer.innerHTML = html + `
        <div id="infiniteScrollSpinner" style="display:none" class="text-center py-3 text-muted fs-8">
            <i class="fa fa-circle-o-notch fa-spin text-primary me-1"></i> Loading more emails...
        </div>`;
    initInfiniteScroll();
}

function updateEmailBrowserUrl(searchVal) {
    const browserUrl = new URL(window.location.href);
    browserUrl.searchParams.set('folder', currentFolder);
    if (currentAccountId) browserUrl.searchParams.set('account_id', currentAccountId);
    else browserUrl.searchParams.delete('account_id');
    if (searchVal) browserUrl.searchParams.set('search', searchVal);
    else browserUrl.searchParams.delete('search');
    history.replaceState(history.state, '', browserUrl);
}

function reloadEmailList(showLoader = true) {
    if (!showLoader && emailListRequestPromise) return emailListRequestPromise;
    if (emailListRequestController) emailListRequestController.abort();

    const requestController = new AbortController();
    emailListRequestController = requestController;
    const listContainer = document.getElementById('emailListContainer');
    if (showLoader && listContainer) listContainer.classList.add('is-filter-loading');

    currentPage = 1;
    hasMorePages = true;
    isLoadingMore = false;

    const searchInput = document.getElementById('emailSearchInput');
    const searchVal = searchInput ? searchInput.value : '';
    const url = new URL('{{ route("emails.index") }}', window.location.origin);
    url.searchParams.set('folder', currentFolder);
    url.searchParams.set('partial', '1');
    if (currentAccountId) url.searchParams.set('account_id', currentAccountId);
    if (searchVal) url.searchParams.set('search', searchVal);

    // Fast instant AJAX list reload
    emailListRequestPromise = fetch(url, {
        signal: requestController.signal,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.text();
    })
    .then(html => {
        renderEmailRows(html);
        if (!searchVal) emailFolderHtmlCache[currentFolder] = html;
        updateEmailBrowserUrl(searchVal);
    })
    .catch(error => {
        if (error.name !== 'AbortError') console.error('Email list refresh failed', error);
    })
    .finally(() => {
        if (emailListRequestController === requestController) {
            if (listContainer) listContainer.classList.remove('is-filter-loading');
            emailListRequestController = null;
            emailListRequestPromise = null;
        }
    });

    return emailListRequestPromise;
}

// Background sync: Only fetches new incoming messages without blocking the UI
let isSyncing = false;
function autoSyncLiveInbox() {
    if (isSyncing || document.hidden || !currentAccountId) return;
    isSyncing = true;

    fetch('{{ route("emails.sync") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ account_id: currentAccountId })
    })
    .then(response => response.json())
    .catch(() => {})
    .finally(() => {
        isSyncing = false;
    });
}

setTimeout(autoSyncLiveInbox, 3000);
if (window.Echo && currentAccountId) {
    window.Echo.private(`emails.account.${currentAccountId}`)
        .listen('.email.received', () => {
            autoSyncLiveInbox();
            reloadEmailList(false);
        });
}
setInterval(autoSyncLiveInbox, 25000);

let lastEmailFingerprint = null;
let lastKnownEmailId = null;
let isCheckingEmailUpdates = false;
let incomingAlertEmailId = null;
let incomingAlertTimeout = null;

// Clean synthesized notification chime (no audio asset load required)
function playEmailNotificationSound() {
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
        osc.frequency.setValueAtTime(880, audioCtx.currentTime + 0.08); // A5
        gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.4);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + 0.4);
    } catch (e) {
        // audio context blocked or not supported
    }
}

// Request desktop notification permissions on user click/interaction
if ("Notification" in window && Notification.permission === "default") {
    document.addEventListener('click', function requestOnce() {
        Notification.requestPermission();
        document.removeEventListener('click', requestOnce);
    }, { once: true });
}

function showDesktopEmailNotification(title, body, emailId) {
    if ("Notification" in window && Notification.permission === "granted") {
        try {
            const notif = new Notification(title, {
                body: body,
                icon: "https://cdn-icons-png.flaticon.com/512/732/732200.png"
            });
            notif.onclick = function() {
                window.focus();
                if (emailId) openEmailThread(emailId);
                notif.close();
            };
        } catch (e) {}
    }
}

function showIncomingEmailAlertCard(email) {
    const alertBox = document.getElementById('incomingEmailAlert');
    if (!alertBox || !email) return;

    incomingAlertEmailId = email.id;
    document.getElementById('incomingAlertSender').textContent = email.from_name || email.from_email || 'New Message';
    document.getElementById('incomingAlertSubject').textContent = (email.subject || '(No Subject)') + (email.preview ? ' - ' + email.preview : '');
    document.getElementById('incomingAlertTime').textContent = email.created_at || 'Just now';
    
    const avatar = document.getElementById('incomingAlertAvatar');
    if (avatar) {
        avatar.textContent = (email.from_name || email.from_email || 'U').charAt(0).toUpperCase();
    }

    alertBox.style.display = 'block';

    if (incomingAlertTimeout) clearTimeout(incomingAlertTimeout);
    incomingAlertTimeout = setTimeout(closeIncomingAlert, 7000);
}

function closeIncomingAlert() {
    const alertBox = document.getElementById('incomingEmailAlert');
    if (alertBox) alertBox.style.display = 'none';
    if (incomingAlertTimeout) clearTimeout(incomingAlertTimeout);
}

function openIncomingEmailFromAlert() {
    if (incomingAlertEmailId) {
        openEmailThread(incomingAlertEmailId);
        closeIncomingAlert();
    }
}

function checkEmailUpdates() {
    if (isCheckingEmailUpdates) return;
    isCheckingEmailUpdates = true;
    const url = new URL('{{ route("emails.updates") }}', window.location.origin);
    if (currentAccountId) url.searchParams.set('account_id', currentAccountId);

    fetch(url, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
        .then(response => response.json())
        .then(data => {
            // Live update sidebar unread count badge
            if (typeof data.unread_count !== 'undefined') {
                const inboxBadge = document.querySelector('.duralux-badge-primary');
                if (inboxBadge) inboxBadge.textContent = data.unread_count;
            }

            const isNewIncomingEmail = lastKnownEmailId !== null 
                && data.latest_id > lastKnownEmailId 
                && data.latest_email 
                && data.latest_email.direction === 'inbound';

            if (isNewIncomingEmail) {
                // 1. Play sound chime
                playEmailNotificationSound();

                // 2. Desktop notification if page in background
                showDesktopEmailNotification(
                    'New Email from ' + (data.latest_email.from_name || data.latest_email.from_email),
                    data.latest_email.subject + (data.latest_email.preview ? '\n' + data.latest_email.preview : ''),
                    data.latest_email.id
                );

                // 3. Show floating toast alert card
                showIncomingEmailAlertCard(data.latest_email);

                // 4. If current open thread is the same thread that received new reply, auto-refresh thread!
                if (activeEmailData && (activeEmailData.id == data.latest_email.id || (data.latest_email.thread_id && activeEmailData.thread_id == data.latest_email.thread_id))) {
                    openEmailThread(activeEmailData.id);
                }
            }

            if (lastEmailFingerprint !== null && data.fingerprint !== lastEmailFingerprint) {
                emailFolderHtmlCache = {};
                reloadEmailList(false);
            }

            lastEmailFingerprint = data.fingerprint;
            lastKnownEmailId = data.latest_id;
        })
        .catch(() => {})
        .finally(() => { isCheckingEmailUpdates = false; });
}

setInterval(checkEmailUpdates, 3000);

let emailDetailRequestController = null;

function openEmailThread(id) {
    if (!id) return;

    // Cancel an older detail request when the user quickly selects another email.
    if (emailDetailRequestController) emailDetailRequestController.abort();
    const requestController = new AbortController();
    emailDetailRequestController = requestController;
    const requestTimeout = setTimeout(() => requestController.abort(), 15000);

    activeThreadId = id;
    const detailPane = document.getElementById('emailDetailPane');
    if (!detailPane) return;

    // 1. Instantly show detail view pane (<1ms)
    detailPane.classList.add('active');

    // 2. Mark row read visually & remove unread dot immediately
    const row = document.getElementById(`email-row-` + id);
    if (row) {
        row.classList.remove('unread');
        row.querySelector('.duralux-unread-dot')?.remove();
        const rowSubject = row.querySelector('.duralux-email-subject');
        if (rowSubject) {
            document.getElementById('detailSubject').textContent = rowSubject.textContent;
        }
    }

    // 3. Show instant clean skeleton placeholder
    const convList = document.getElementById('detailConversationList');
    convList.innerHTML = `
        <div class="duralux-thread-card shadow-sm mb-4" style="opacity: 0.85;">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="duralux-avatar" style="background: #cbd5e1; width: 42px; height: 42px;"><i class="fa fa-envelope text-white"></i></div>
                <div>
                    <div class="fw-bold text-gray-800 fs-6">Loading conversation...</div>
                    <div class="text-muted fs-8">Fetching latest messages</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 py-4 text-muted fs-7">
                <i class="fa fa-circle-o-notch fa-spin text-primary"></i> Loading content...
            </div>
        </div>
    `;

    // 4. Update URL cleanly so refreshing stays on inbox view with active conversation
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('folder', currentFolder);
    if (currentAccountId) currentUrl.searchParams.set('account_id', currentAccountId);
    currentUrl.searchParams.set('email_id', id);
    history.replaceState({ emailId: id }, '', currentUrl);

    // 5. Fetch JSON and render in ~20ms
    // Build from the currently opened inbox path so installations served from
    // an XAMPP/project subdirectory do not accidentally request root /emails.
    const inboxPath = window.location.pathname.replace(/\/+$/, '');
    const detailUrl = `${inboxPath}/${encodeURIComponent(id)}`;
    fetch(detailUrl, {
        signal: requestController.signal,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('HTTP error ' + res.status);
        return res.json();
    })
    .then(data => {
        if (data.email) {
            activeEmailData = data.email;
            document.getElementById('detailSubject').textContent = data.email.subject || '(No Subject)';

            // Star state
            const starBtn = document.getElementById('detailStarBtn');
            if (starBtn) {
                const starIcon = starBtn.querySelector('i');
                if (data.email.is_starred) {
                    starBtn.classList.add('text-warning');
                    if (starIcon) starIcon.className = 'fa fa-star';
                } else {
                    starBtn.classList.remove('text-warning');
                    if (starIcon) starIcon.className = 'fa fa-star-o';
                }
            }

            // Render all messages in thread chronologically
            const messages = (data.messages && data.messages.length > 0) ? data.messages : [data.email];

            const escapeEmailText = value => String(value ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
            convList.innerHTML = messages.map((m, idx) => {
                const avatarBg = m.direction === 'outbound' ? 'background: linear-gradient(135deg, #2563eb, #6366f1);' : 'background: linear-gradient(135deg, #ea580c, #f97316);';
                const avatarLetter = (m.from_name || m.from_email || 'U').charAt(0).toUpperCase();

                let attachmentsHtml = '';
                if (m.attachments && m.attachments.length > 0) {
                    attachmentsHtml = `
                        <div class="d-flex flex-wrap gap-2 mt-3 pt-2 border-top" style="padding-left: 54px;">
                            ${m.attachments.map(att => `
                                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 bg-light border rounded">
                                    <i class="fa fa-paperclip text-primary"></i>
                                    <span class="fs-8 fw-semibold">${escapeEmailText(att.filename || 'File')}</span>
                                    <span class="text-muted fs-9">(${escapeEmailText(att.file_size || '')})</span>
                                    <a href="${att.url || '#'}" target="_blank" class="btn btn-xs btn-light-primary ms-2"><i class="fa fa-download"></i></a>
                                </div>
                            `).join('')}
                        </div>
                    `;
                }

                return `
                    <div class="duralux-thread-card shadow-sm mb-4" id="thread-msg-${m.id}">
                        <div class="duralux-sender-info mb-3 d-flex align-items-center justify-content-between">
                            <div class="duralux-sender-meta d-flex align-items-center gap-3">
                                <div class="duralux-avatar" style="${avatarBg} width: 42px; height: 42px; font-size: 15px; font-weight: bold; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center;">${avatarLetter}</div>
                                <div>
                                    <div class="fw-bold text-gray-900 fs-6">${escapeEmailText(m.from_name || m.from_email)} <span class="text-muted fs-8 fw-normal">&lt;${escapeEmailText(m.from_email)}&gt;</span></div>
                                    <div class="text-muted fs-8">to ${escapeEmailText(m.to_name || m.to_email || 'me')}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-muted fs-8">${m.date_formatted || m.received_at || 'Just now'}</span>
                                <button type="button" class="btn btn-sm btn-icon btn-light" onclick="setInlineComposerMode('reply', '${m.from_email}', '${(m.subject || '').replace(/'/g, "\\'")}')" title="Reply">
                                    <i class="fa fa-reply text-muted"></i>
                                </button>
                            </div>
                        </div>

                        <div class="duralux-email-message-text" id="email-msg-body-${m.id}"></div>

                        ${attachmentsHtml}
                    </div>
                `;
            }).join('');

            // Render each email body inside an isolated iframe to completely eliminate CSS and font conflicts
            data.messages.forEach(m => {
                const container = document.getElementById(`email-msg-body-${m.id}`);
                if (container) {
                    renderIsolatedEmailBody(container, m.body_html, m.body_plain);
                }
            });

            // Pre-populate inline composer in Reply mode
            setInlineComposerMode('reply', data.email.from_email, data.email.subject);

            // Render active thread labels
            renderDetailLabels(data.labels || (data.email ? data.email.labels : []) || []);
        } else {
            throw new Error('Email details empty');
        }
    })
    .catch(err => {
        // Ignore requests cancelled because a different email was selected.
        if (err.name === 'AbortError' && emailDetailRequestController !== requestController) return;
        console.error('Error fetching email details', err);
        const errorMessage = err.name === 'AbortError'
            ? 'The request took too long. Please retry.'
            : 'The email details could not be loaded.';
        convList.innerHTML = `
            <div class="alert alert-danger d-flex align-items-center justify-content-between p-4 rounded-3 shadow-sm">
                <div>
                    <i class="fa fa-exclamation-triangle me-2"></i>
                    <strong>${errorMessage}</strong>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="openEmailThread(${id})">
                    <i class="fa fa-refresh me-1"></i> Retry
                </button>
            </div>
        `;
    })
    .finally(() => {
        clearTimeout(requestTimeout);
        if (emailDetailRequestController === requestController) {
            emailDetailRequestController = null;
        }
    });
}

let currentThreadLabels = [];

function renderDetailLabels(labels) {
    currentThreadLabels = labels || [];
    const container = document.getElementById('detailLabelsBadges');
    if (!container) return;

    if (currentThreadLabels.length === 0) {
        container.innerHTML = '';
    } else {
        container.innerHTML = currentThreadLabels.map(l => `
            <span class="badge px-2.5 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-sm" style="background-color: ${l.color}; color: #ffffff;">
                <i class="fa fa-tag text-white opacity-75" style="font-size: 10px;"></i> ${(l.name || '').replace(/[&<>"']/g, '')}
            </span>
        `).join('');
    }

    // Update checkboxes in dropdown
    const activeIds = new Set(currentThreadLabels.map(l => parseInt(l.id)));
    document.querySelectorAll('.label-assign-checkbox').forEach(chk => {
        chk.checked = activeIds.has(parseInt(chk.value));
    });
}

function toggleActiveThreadLabel(labelId, isChecked) {
    if (!activeEmailData) return;

    const checkedBoxes = Array.from(document.querySelectorAll('.label-assign-checkbox:checked'));
    const checkedIds = checkedBoxes.map(c => parseInt(c.value));
    const labelsData = checkedBoxes.map(c => ({
        id: parseInt(c.value),
        name: c.dataset.name,
        color: c.dataset.color
    }));

    // Optimistic instant UI update (0ms real-time)
    renderDetailLabels(labelsData);

    if (activeEmailId) {
        const rowBadges = document.getElementById(`row-labels-badges-${activeEmailId}`);
        if (rowBadges) {
            const chips = labelsData.slice(0, 4).map(l => `
                <span class="badge px-2 py-0.5 fs-9 fw-bold d-inline-flex align-items-center gap-1" style="background-color: ${l.color}; color: #ffffff; font-size: 10.5px; border-radius: 4px;">
                    <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:#ffffff;"></span> ${l.name}
                </span>
            `).join('');
            const extra = labelsData.length > 4 ? `<span class="badge bg-light text-muted border fs-9 px-1" style="font-size: 10px;">+${labelsData.length - 4}</span>` : '';
            rowBadges.innerHTML = chips + extra;
        }
    }

    const saveUrl = '{{ route("emails.labels.save") }}';
    const customerEmail = activeEmailData.customer_email || ((activeEmailData.direction === 'outbound' || activeEmailData.folder === 'sent' || activeEmailData.folder === 'drafts')
        ? (activeEmailData.to_email || activeEmailData.from_email)
        : (activeEmailData.from_email || activeEmailData.to_email));

    fetch(saveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            thread_id: activeEmailData.thread_id,
            email: customerEmail,
            labels: checkedIds
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.labels) {
            renderDetailLabels(data.labels);
            if (activeEmailId) {
                const rowBadges = document.getElementById(`row-labels-badges-${activeEmailId}`);
                if (rowBadges) {
                    const chips = data.labels.slice(0, 4).map(l => `
                        <span class="badge px-2 py-0.5 fs-9 fw-bold d-inline-flex align-items-center gap-1" style="background-color: ${l.color}; color: #ffffff; font-size: 10.5px; border-radius: 4px;">
                            <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:#ffffff;"></span> ${l.name}
                        </span>
                    `).join('');
                    const extra = data.labels.length > 4 ? `<span class="badge bg-light text-muted border fs-9 px-1" style="font-size: 10px;">+${data.labels.length - 4}</span>` : '';
                    rowBadges.innerHTML = chips + extra;
                }
            }
        }
    })
    .catch(err => {
        console.error('Failed to save email labels', err);
    });
}

function saveRowEmailLabels(threadId, email, rowEmailId) {
    const checkedBoxes = Array.from(document.querySelectorAll(`.row-label-chk-${rowEmailId}:checked`));
    const labelIds = checkedBoxes.map(c => parseInt(c.value));
    const labelsData = checkedBoxes.map(c => ({
        id: parseInt(c.value),
        name: c.dataset.name,
        color: c.dataset.color
    }));

    // Optimistic instant UI update (0ms real-time)
    const badgesWrap = document.getElementById(`row-labels-badges-${rowEmailId}`);
    if (badgesWrap) {
        const chips = labelsData.slice(0, 4).map(l => `
            <span class="badge px-2 py-0.5 fs-9 fw-bold d-inline-flex align-items-center gap-1" style="background-color: ${l.color}; color: #ffffff; font-size: 10.5px; border-radius: 4px;">
                <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:#ffffff;"></span> ${l.name}
            </span>
        `).join('');
        const extra = labelsData.length > 4 ? `<span class="badge bg-light text-muted border fs-9 px-1" style="font-size: 10px;">+${labelsData.length - 4}</span>` : '';
        badgesWrap.innerHTML = chips + extra;
    }

    // If active email in reading pane is this one, sync detail pane badges immediately
    if (activeEmailData && (activeEmailData.thread_id === threadId || activeEmailId === rowEmailId)) {
        renderDetailLabels(labelsData);
    }

    const saveUrl = '{{ route("emails.labels.save") }}';

    fetch(saveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            thread_id: threadId,
            email: email,
            labels: labelIds
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.labels) {
            if (badgesWrap) {
                const chips = data.labels.slice(0, 4).map(l => `
                    <span class="badge px-2 py-0.5 fs-9 fw-bold d-inline-flex align-items-center gap-1" style="background-color: ${l.color}; color: #ffffff; font-size: 10.5px; border-radius: 4px;">
                        <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:#ffffff;"></span> ${l.name}
                    </span>
                `).join('');
                const extra = data.labels.length > 4 ? `<span class="badge bg-light text-muted border fs-9 px-1" style="font-size: 10px;">+${data.labels.length - 4}</span>` : '';
                badgesWrap.innerHTML = chips + extra;
            }
            if (activeEmailData && (activeEmailData.thread_id === threadId || activeEmailId === rowEmailId)) {
                renderDetailLabels(data.labels);
            }
        }
    })
    .catch(err => {
        console.error('Failed to save row labels', err);
    });
}

function setInlineComposerMode(mode, toEmail = null, subject = null) {
    inlineComposerMode = mode;
    const box = document.getElementById('inlineComposerContainer');
    const toInput = document.getElementById('inlineComposerToInput');
    const subjInput = document.getElementById('inlineComposerSubjectInput');
    const modeLabel = document.getElementById('inlineComposerModeLabel');
    const replyTab = document.getElementById('inlineReplyTabBtn');
    const forwardTab = document.getElementById('inlineForwardTabBtn');

    if (!box) return;
    box.classList.add('highlight-focus');

    if (mode === 'reply') {
        replyTab.classList.add('active');
        forwardTab.classList.remove('active');
        toInput.value = toEmail || (activeEmailData ? activeEmailData.from_email : '');
        const baseSubj = subject || (activeEmailData ? activeEmailData.subject : '');
        subjInput.value = baseSubj ? ('Re: ' + baseSubj.replace(/^(Re:\s*)+/i, '')) : 'Re:';
        modeLabel.textContent = 'Replying to ' + toInput.value;
        if (inlineQuill) inlineQuill.setText('');
    } else {
        forwardTab.classList.add('active');
        replyTab.classList.remove('active');
        toInput.value = '';
        toInput.placeholder = 'Enter recipient email to forward...';
        const baseSubj = subject || (activeEmailData ? activeEmailData.subject : '');
        subjInput.value = baseSubj ? ('Fwd: ' + baseSubj.replace(/^(Fwd:\s*)+/i, '')) : 'Fwd:';
        modeLabel.textContent = 'Forwarding message to new recipient';

        // Prepopulate standard forwarded message block
        if (activeEmailData && inlineQuill) {
            const fwdHtml = `
                <br><br>
                <div style="border-left: 3px solid #cbd5e1; padding-left: 14px; margin-top: 14px; color: #475569;">
                    <strong>---------- Forwarded message ---------</strong><br>
                    <strong>From:</strong> ${activeEmailData.from_name || activeEmailData.from_email} &lt;${activeEmailData.from_email}&gt;<br>
                    <strong>Date:</strong> ${activeEmailData.created_at || 'Recently'}<br>
                    <strong>Subject:</strong> ${activeEmailData.subject || ''}<br>
                    <strong>To:</strong> ${activeEmailData.to_email || ''}<br><br>
                    ${activeEmailData.body_html || activeEmailData.body_plain || ''}
                </div>
            `;
            inlineQuill.root.innerHTML = fwdHtml;
        }
    }

    setTimeout(() => {
        if (mode === 'forward') {
            toInput.focus();
        } else if (inlineQuill) {
            inlineQuill.focus();
        }
        box.classList.remove('highlight-focus');
    }, 400);
}

function handleInlineFileSelected(input) {
    const badge = document.getElementById('inlineFileCountBadge');
    if (input.files && input.files.length > 0) {
        badge.textContent = `${input.files.length} file(s) attached`;
    } else {
        badge.textContent = '';
    }
}

function discardInlineComposer() {
    if (inlineQuill) inlineQuill.setText('');
    document.getElementById('inlineFileCountBadge').textContent = '';
    document.getElementById('inlineComposerFileInput').value = '';
}

let toastTimeout = null;

function showSendingToast(message, isSuccess = false, isError = false) {
    const toast = document.getElementById('emailSendingToast');
    const icon = document.getElementById('toastStatusIcon');
    const text = document.getElementById('toastStatusText');

    if (!toast || !icon || !text) return;
    if (toastTimeout) clearTimeout(toastTimeout);

    text.textContent = message;
    toast.className = 'email-sending-toast active';

    if (isSuccess) {
        toast.classList.add('toast-success');
        icon.className = 'fa fa-check-circle fs-5 text-white';
        toastTimeout = setTimeout(() => {
            hideSendingToast();
        }, 3500);
    } else if (isError) {
        toast.classList.add('toast-error');
        icon.className = 'fa fa-exclamation-circle fs-5 text-white';
        toastTimeout = setTimeout(() => {
            hideSendingToast();
        }, 5000);
    } else {
        icon.className = 'fa fa-circle-o-notch fa-spin fs-5 text-warning';
    }
}

function hideSendingToast() {
    const toast = document.getElementById('emailSendingToast');
    if (toast) toast.classList.remove('active');
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

function addOptimisticPendingEmail(toEmail, subject, previewText) {
    const tempId = 'temp-msg-' + Date.now();
    const listContainer = document.getElementById('emailListContainer');
    if (!listContainer) return tempId;

    const pendingHtml = `
        <div class="duralux-email-item unread pending-email-item" id="${tempId}">
            <div class="duralux-item-left" onclick="event.stopPropagation();">
                <span class="badge bg-light-warning text-warning border border-warning px-2 py-1 fs-8 d-inline-flex align-items-center gap-1">
                    <i class="fa fa-clock-o fa-spin"></i> Sending...
                </span>
            </div>

            <div class="duralux-item-content">
                <div class="duralux-email-sender">To: ${toEmail}</div>
                <div class="duralux-email-body-preview">
                    <span class="duralux-email-subject">${subject || '(No Subject)'}</span>
                    <span>- ${previewText || 'Sending in background...'}</span>
                </div>
            </div>

            <div class="duralux-item-right" onclick="event.stopPropagation();">
                <div class="duralux-email-time text-warning fw-semibold"><i class="fa fa-spinner fa-spin me-1"></i> Pending</div>
            </div>
        </div>
    `;

    listContainer.insertAdjacentHTML('afterbegin', pendingHtml);
    return tempId;
}

function updateOptimisticPendingEmail(tempId, isSuccess, isError = false) {
    const el = document.getElementById(tempId);
    if (!el) return;

    if (isSuccess) {
        el.classList.remove('pending-email-item');
        const left = el.querySelector('.duralux-item-left');
        if (left) {
            left.innerHTML = `
                <span class="badge bg-light-success text-success border border-success px-2 py-1 fs-8 d-inline-flex align-items-center gap-1">
                    <i class="fa fa-check"></i> Sent
                </span>
            `;
        }
        const right = el.querySelector('.duralux-item-right');
        if (right) {
            right.innerHTML = `<div class="duralux-email-time text-success fw-semibold">Just now</div>`;
        }
    } else if (isError) {
        el.classList.remove('pending-email-item');
        el.style.background = '#fef2f2';
        const left = el.querySelector('.duralux-item-left');
        if (left) {
            left.innerHTML = `
                <span class="badge bg-light-danger text-danger border border-danger px-2 py-1 fs-8 d-inline-flex align-items-center gap-1">
                    <i class="fa fa-exclamation-triangle"></i> Failed
                </span>
            `;
        }
    }
}

function submitInlineComposer(e) {
    e.preventDefault();

    const form = document.getElementById('inlineComposerForm');
    const formData = new FormData(form);
    const toEmail = document.getElementById('inlineComposerToInput').value;
    const subject = document.getElementById('inlineComposerSubjectInput').value;
    const previewText = inlineQuill ? inlineQuill.getText().substring(0, 70) : '';

    if (inlineQuill) {
        formData.append('body_html', inlineQuill.root.innerHTML);
        formData.append('body_plain', inlineQuill.getText());
    }
    if (activeThreadId) formData.append('thread_id', activeThreadId);
    if (currentAccountId) formData.append('account_id', currentAccountId);

    // 1. Instantly reset & clear UI (<5ms)
    discardInlineComposer();

    // 2. Show non-blocking floating status toast
    showSendingToast('Sending message to ' + toEmail + '...');

    // 3. Add optimistic pending item with clock icon in list
    const tempId = addOptimisticPendingEmail(toEmail, subject, previewText);

    // 4. Background Async Send
    sendEmailFormData(formData)
    .then(parseEmailSendResponse)
    .then(() => {
        showSendingToast('Email sent successfully to ' + toEmail, true);
        updateOptimisticPendingEmail(tempId, true);
        if (activeThreadId) {
            openEmailThread(activeThreadId);
        }
    })
    .catch(err => {
        showSendingToast('Email failed: ' + (err.message || 'Unable to send email'), false, true);
        updateOptimisticPendingEmail(tempId, false, true);
    });
}

function handleSendCompose(e) {
    e.preventDefault();

    const form = document.getElementById('composeEmailForm');
    document.getElementById('composeBodyHtml').value = composeQuill.root.innerHTML;
    const formData = new FormData(form);
    const toEmail = document.getElementById('composeToEmail').value;
    const subject = document.getElementById('composeSubject').value;
    const previewText = composeQuill ? composeQuill.getText().substring(0, 70) : '';

    // 1. Instantly close compose modal & reset (<5ms)
    closeComposeWidget();
    form.reset();
    if (composeQuill) composeQuill.root.innerHTML = '';
    removeSelectedFile();

    // 2. Show non-blocking floating status toast
    showSendingToast('Sending message to ' + toEmail + '...');

    // 3. Add optimistic pending item with clock icon in list
    const tempId = addOptimisticPendingEmail(toEmail, subject, previewText);

    // 4. Background Async Send
    sendEmailFormData(formData)
    .then(parseEmailSendResponse)
    .then(() => {
        showSendingToast('Email sent successfully to ' + toEmail, true);
        updateOptimisticPendingEmail(tempId, true);
    })
    .catch(err => {
        showSendingToast('Email failed: ' + (err.message || 'Unable to send email'), false, true);
        updateOptimisticPendingEmail(tempId, false, true);
    });
}

function scrollReplyBox() {
    const box = document.getElementById('inlineComposerContainer');
    if (box) box.scrollIntoView({ behavior: 'smooth' });
}

function closeEmailThread() {
    activeThreadId = null;
    const detailPane = document.getElementById('emailDetailPane');
    if (detailPane) detailPane.classList.remove('active');

    // Restore URL
    const accParam = currentAccountId ? ('?account_id=' + currentAccountId) : '';
    history.pushState(null, '', `{{ route('emails.index') }}${accParam}`);
}

window.addEventListener('popstate', function(e) {
    if (e.state && e.state.emailId) {
        openEmailThread(e.state.emailId);
    } else {
        const detailPane = document.getElementById('emailDetailPane');
        if (detailPane) detailPane.classList.remove('active');
    }
});

function replyEmail(id) {
    fetch(`{{ url('emails') }}/` + id, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.email) {
            openComposeModal({
                to: data.email.from_email,
                subject: 'Re: ' + (data.email.subject || '')
            });
        }
    });
}

function toggleStar(id, el) {
    const icon = el.querySelector('i');
    el.classList.toggle('active');
    if (el.classList.contains('active')) {
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

function toggleDetailStar() {
    if (!activeThreadId) return;
    const starBtn = document.getElementById('detailStarBtn');
    const icon = starBtn.querySelector('i');
    starBtn.classList.toggle('text-warning');
    if (starBtn.classList.contains('text-warning')) {
        icon.className = 'fa fa-star';
    } else {
        icon.className = 'fa fa-star-o';
    }

    fetch(`{{ url('emails') }}/` + activeThreadId + `/star`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    });
}

function toggleSelectAll(master) {
    document.querySelectorAll('.email-item-checkbox').forEach(cb => {
        cb.checked = master.checked;
    });
}

function deleteEmail(id) {
    if (!confirm('Are you sure you want to move this email to Trash?')) return;
    fetch(`{{ url('emails') }}/` + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(() => {
        const row = document.getElementById('email-row-' + id);
        if (row) row.remove();
        closeEmailThread();
    });
}

function deleteActiveThread() {
    if (activeThreadId) deleteEmail(activeThreadId);
}

function toggleReadStatus(id, isRead) {
    const row = document.getElementById(`email-row-` + id);
    if (row) {
        if (isRead) {
            row.classList.add('unread');
            const left = row.querySelector('.duralux-item-left');
            if (left && !left.querySelector('.duralux-unread-dot')) {
                left.insertAdjacentHTML('beforeend', '<span class="duralux-unread-dot" title="Unread email"></span>');
            }
        } else {
            row.classList.remove('unread');
            row.querySelector('.duralux-unread-dot')?.remove();
        }
    }

    fetch('{{ route("emails.mark-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id, is_read: isRead })
    }).then(() => {
        checkEmailUpdates();
    });
}

function bulkMarkRead() {
    const ids = Array.from(document.querySelectorAll('.email-item-checkbox:checked')).map(cb => cb.value);
    if (!ids.length) {
        alert('Please select at least one email.');
        return;
    }
    ids.forEach(id => {
        const row = document.getElementById('email-row-' + id);
        if (row) {
            row.classList.remove('unread');
            row.querySelector('.duralux-unread-dot')?.remove();
        }
        fetch('{{ route("emails.mark-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id, is_read: true })
        });
    });
    setTimeout(checkEmailUpdates, 500);
}

function bulkDelete() {
    const ids = Array.from(document.querySelectorAll('.email-item-checkbox:checked')).map(cb => cb.value);
    if (!ids.length) {
        alert('Please select emails to delete.');
        return;
    }
    if (!confirm(`Delete ${ids.length} selected emails?`)) return;
    ids.forEach(id => deleteEmail(id));
}

function renderIsolatedEmailBody(container, rawHtml, plainText) {
    if (!container) return;

    const content = rawHtml || ('<pre style="font-family: inherit; white-space: pre-wrap; margin: 0; color: #334155; font-size: 14px;">' + escapeEmailText(plainText || '') + '</pre>');

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
</script>
@endpush
@endsection
