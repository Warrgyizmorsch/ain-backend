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

    /* Eliminate Metronic toolbar-fixed empty gap & sync background */
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

    /* Outer Wrapper - Strict Viewport Lock */
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
        height: 100%;
        min-height: 0;
        background: #f6f8fc;
        border: none;
        overflow: hidden;
        position: relative;
    }

    /* 1. Left Sidebar (Google Mail Style) */
    .duralux-sidebar {
        width: 256px;
        background: #f6f8fc;
        border-right: none;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        height: 100%;
        overflow: hidden;
    }

    .duralux-sidebar-top,
    .gmail-sidebar-top {
        padding: 16px 16px 12px 16px;
        flex-shrink: 0;
        background: var(--gmail-sidebar);
    }

    /* Iconic Gmail Pill Compose Button */
    .btn-gmail-compose,
    .btn-duralux-compose {
        background: #c2e7ff !important;
        color: #001d35 !important;
        font-weight: 600;
        font-size: 14px;
        border-radius: 16px !important;
        padding: 13px 22px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 10px !important;
        border: none !important;
        box-shadow: 0 1px 3px 0 rgba(60, 64, 67, 0.3), 0 4px 8px 3px rgba(60, 64, 67, 0.15) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer;
        width: 146px !important;
    }

    .btn-gmail-compose:hover,
    .btn-duralux-compose:hover {
        background: #b3def7 !important;
        box-shadow: 0 2px 6px 2px rgba(60, 64, 67, 0.2), 0 6px 10px 4px rgba(60, 64, 67, 0.15) !important;
        transform: translateY(-1px);
    }

    .btn-gmail-compose svg,
    .btn-duralux-compose svg {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
    }

    .duralux-sidebar-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 8px 0 16px 0;
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
        margin: 0 0 16px 0;
    }

    .duralux-section-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #747775;
        padding: 14px 24px 6px 24px;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .duralux-nav-item {
        margin-bottom: 2px;
    }

    /* Google Pill Sidebar Link */
    .duralux-nav-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 16px 8px 24px;
        border-radius: 0 24px 24px 0;
        margin-right: 12px;
        color: #444746;
        font-size: 13.5px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .duralux-nav-link i.nav-icon {
        width: 20px;
        font-size: 15px;
        margin-right: 10px;
        text-align: center;
        display: inline-block;
    }

    /* Colorful Mail Icons */
    .duralux-nav-link .icon-inbox { color: #1a73e8; }
    .duralux-nav-link .icon-sent { color: #1e8e3e; }
    .duralux-nav-link .icon-drafts { color: #e37400; }
    .duralux-nav-link .icon-starred { color: #f29900; }
    .duralux-nav-link .icon-trash { color: #d93025; }

    .duralux-nav-link:hover {
        background: var(--gmail-hover);
        color: var(--gmail-text);
    }

    .duralux-nav-link.active {
        background: var(--gmail-active);
        color: var(--gmail-active-text);
        font-weight: 700;
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

    .duralux-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        flex-shrink: 0;
    }

    /* 2. Main Area (Right Content - Gmail Floating Canvas) */
    .duralux-main-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: calc(100% - 8px);
        min-width: 0;
        overflow: hidden;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e0e2e7;
        margin: 0 8px 8px 0;
        box-shadow: 0 1px 3px rgba(60, 64, 67, 0.08);
        position: relative;
    }

    /* Deadline Type Toolbar & Pill Filter Styles */
    .deadline-type-header-badge {
        background: #f58220;
        color: #ffffff;
        font-weight: 700;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 2px rgba(245, 130, 32, 0.25);
        user-select: none;
        white-space: nowrap;
    }
    .deadline-type-header-badge i {
        font-size: 12px;
    }
    .deadline-pill-container {
        display: inline-flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #d9d9d9;
        border-radius: 8px;
        padding: 3px;
        gap: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .deadline-pill-btn {
        border: 1px solid transparent;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 11px;
        line-height: 1.2;
        cursor: pointer;
        white-space: nowrap;
        outline: none;
        transition: all 0.15s ease-in-out;
    }
    .deadline-pill-btn:hover {
        filter: brightness(0.96);
        transform: translateY(-1px);
    }
    .deadline-pill-btn.active {
        box-shadow: 0 0 0 2px currentColor;
        font-weight: 800;
        filter: brightness(0.94);
    }
    .deadline-pill-less2 {
        background: #fff0f3 !important;
        color: #e11d48 !important;
    }
    .deadline-pill-35 {
        background: #fffbeb !important;
        color: #b45309 !important;
    }
    .deadline-pill-615 {
        background: #eff6ff !important;
        color: #2563eb !important;
    }
    .deadline-pill-above15 {
        background: #ecfdf5 !important;
        color: #059669 !important;
    }

    /* Google Mail Top Header: Search Bar & Account Chip */
    .gmail-top-header {
        height: 60px;
        padding: 0 20px;
        border-bottom: 1px solid var(--gmail-border-subtle);
        border-radius: 16px 16px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        flex-shrink: 0;
        gap: 16px;
        position: relative;
        z-index: 1050;
        overflow: visible;
    }

    .gmail-search-wrapper {
        position: relative;
        width: 680px;
        max-width: 100%;
        z-index: 1060;
    }

    /* 1. Closed state search pill */
    .gmail-search-box {
        position: relative;
        width: 100%;
        background: #eaf1fb;
        border-radius: 24px;
        height: 48px;
        display: flex;
        align-items: center;
        padding: 0 18px;
        border: 1px solid transparent;
        transition: background 0.15s ease, border-radius 0.15s ease, box-shadow 0.15s ease;
        z-index: 1062;
    }

    .gmail-search-box:hover {
        background: #e1e9f5;
    }

    /* 2. Open state: Search box turns pure white, bottom corners flat, seamless with dropdown */
    .gmail-search-wrapper.has-dropdown-open .gmail-search-box,
    .gmail-search-box.has-dropdown-open {
        background: #ffffff !important;
        border-radius: 24px 24px 0 0 !important;
        border: 1px solid #dadce0 !important;
        border-bottom: 1px solid transparent !important;
        box-shadow: 0 4px 16px rgba(60, 64, 67, 0.15) !important;
    }

    .gmail-search-icon {
        color: #5f6368;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .gmail-search-input {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        font-size: 15px;
        color: #1f1f1f;
        padding: 0 14px;
        height: 100%;
    }

    .gmail-search-input::placeholder {
        color: #5f6368;
        font-size: 14.5px;
    }

    .gmail-clear-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #5f6368;
        cursor: pointer;
        background: transparent;
        border: none;
        transition: background 0.15s ease;
        flex-shrink: 0;
    }

    .gmail-clear-btn:hover {
        background: rgba(60, 64, 67, 0.08);
        color: #1f1f1f;
    }

    .gmail-filter-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #5f6368;
        cursor: pointer;
        transition: background 0.15s ease, color 0.15s ease;
        flex-shrink: 0;
        background: transparent;
        border: none;
    }

    .gmail-filter-btn:hover {
        background: rgba(60, 64, 67, 0.08);
        color: #1f1f1f;
    }

    .gmail-filter-btn.active {
        background: #c2e7ff;
        color: #001d35;
    }

    /* 3. Live Search Dropdown: Seamlessly joined below search box */
    .gmail-search-dropdown {
        position: absolute;
        top: 47px;
        left: 0;
        right: 0;
        background: #ffffff;
        border-radius: 0 0 24px 24px;
        border: 1px solid #dadce0;
        border-top: none;
        box-shadow: 0 12px 24px rgba(60, 64, 67, 0.18);
        z-index: 1061;
        max-height: 520px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* 4. Filter Chips Row */
    .gmail-search-chips-row {
        padding: 8px 18px 12px 18px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        border-bottom: 1px solid #e0e2e7;
        background: #ffffff;
    }

    .gmail-filter-chip {
        display: inline-flex;
        align-items: center;
        padding: 5px 14px;
        border-radius: 8px;
        border: 1px solid #747775;
        background: #ffffff;
        color: #1f1f1f;
        font-size: 13.5px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
        white-space: nowrap;
        line-height: 1.35;
    }

    .gmail-filter-chip:hover {
        background: #f8f9fa;
        border-color: #1f1f1f;
    }

    .gmail-filter-chip.is-active {
        background: #c2e7ff;
        color: #001d35;
        border-color: #001d35;
        font-weight: 600;
    }

    /* 5. Live Search Email Result Item */
    .gmail-search-result-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 18px;
        cursor: pointer;
        transition: background 0.12s ease;
        border-bottom: 1px solid #f8f9fa;
        text-decoration: none;
        color: inherit;
    }

    .gmail-search-result-item:hover,
    .gmail-search-result-item.selected {
        background: #f2f6fc;
    }

    .gmail-search-result-item:last-child {
        border-bottom: none;
    }

    .gmail-search-result-item .item-icon {
        color: #5f6368;
        width: 20px;
        flex-shrink: 0;
        margin-right: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gmail-search-result-item .item-main {
        flex: 1;
        min-width: 0;
    }

    .gmail-search-result-item .item-subject {
        font-size: 13.5px;
        color: #1f1f1f;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .gmail-search-result-item .item-subject strong {
        color: #1f1f1f;
        font-weight: 700;
    }

    mark.gmail-search-highlight,
    .gmail-search-highlight {
        background-color: #fef08a !important;
        color: #111827 !important;
        padding: 1px 3px !important;
        border-radius: 2px !important;
        font-weight: 700 !important;
        box-shadow: 0 0 0 1px rgba(234, 179, 8, 0.25) !important;
        display: inline !important;
    }

    .gmail-search-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 22px 16px;
        color: #5f6368;
        font-size: 13.5px;
        font-weight: 500;
    }

    .gmail-search-spinner {
        width: 18px;
        height: 18px;
        border: 2.5px solid #e0e2e7;
        border-top-color: #0b57d0;
        border-right-color: #0b57d0;
        border-radius: 50%;
        animation: gmailSpinnerRotate 0.65s linear infinite;
        flex-shrink: 0;
    }

    .gmail-search-result-item .item-participants {
        font-size: 12px;
        color: #5f6368;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 2px;
    }

    .gmail-search-result-item .item-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
        margin-left: 14px;
        font-size: 12px;
        color: #5f6368;
    }

    /* 6. Recent Searches */
    .gmail-recent-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 18px;
        cursor: pointer;
        color: #1f1f1f;
        font-size: 14px;
        transition: background 0.12s ease;
    }

    .gmail-recent-item:hover,
    .gmail-recent-item.selected {
        background: #f2f6fc;
    }

    .gmail-recent-item .recent-remove {
        opacity: 0;
        transition: opacity 0.15s ease;
        padding: 2px 6px;
        font-size: 16px;
        line-height: 1;
    }

    .gmail-recent-item:hover .recent-remove {
        opacity: 1;
    }

    /* 7. Bottom Search Action Row */
    .gmail-search-bottom-action {
        padding: 12px 18px 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        border-top: 1px solid #e0e2e7;
        transition: background 0.12s ease;
        background: #ffffff;
    }

    .gmail-search-bottom-action:hover,
    .gmail-search-bottom-action.selected {
        background: #f8f9fa;
    }

    .gmail-search-bottom-action .bottom-text-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
        color: #1f1f1f;
        font-size: 14px;
    }

    .gmail-press-enter-label {
        font-size: 12px;
        color: #5f6368;
        font-weight: 400;
        user-select: none;
    }

    /* Advanced Filter Popup */
    .gmail-advanced-filter-popup {
        position: absolute;
        top: 48px;
        left: 0;
        width: 540px;
        max-width: 95vw;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(60, 64, 67, 0.28);
        z-index: 1065;
        border: 1px solid #dadce0;
        animation: fadeInDown 0.15s ease-out;
    }

    .gmail-adv-input {
        border-radius: 6px;
        border: 1px solid #dadce0;
        font-size: 13px;
        padding: 6px 10px;
    }

    .gmail-adv-input:focus {
        border-color: #0b57d0;
        box-shadow: 0 0 0 2px rgba(11, 87, 208, 0.2);
    }

    .gmail-account-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        background: #edf2fc;
        color: #0b57d0;
        font-size: 12.5px;
        font-weight: 600;
        border: 1px solid #d3e3fd;
    }

    .gmail-account-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10b981;
    }

    /* Mail Actions Toolbar (Directly above email rows) */
    .duralux-area-header,
    .gmail-action-toolbar {
        height: 46px;
        padding: 0 16px;
        border-bottom: 1px solid var(--gmail-border);
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
        gap: 4px;
    }

    .duralux-toolbar-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .gmail-icon-btn,
    .duralux-btn-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #444746;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: background-color 0.15s ease, color 0.15s ease;
        text-decoration: none;
        font-size: 13.5px;
    }

    .gmail-icon-btn:hover,
    .duralux-btn-icon:hover {
        background-color: #e8ecf4;
        color: #1f1f1f;
    }

    .gmail-caret-btn {
        background: none;
        border: none;
        color: #444746;
        padding: 4px 6px;
        border-radius: 4px;
        font-size: 11px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }

    .gmail-caret-btn:hover {
        background: #e8ecf4;
    }

    .gmail-v-sep,
    .duralux-divider-v {
        width: 1px;
        height: 20px;
        background: var(--gmail-border);
        margin: 0 4px;
    }

    /* Google Material Linear Progress Bar */
    .gmail-progress-bar {
        height: 3px;
        width: 100%;
        background-color: #e8ecf4;
        position: relative;
        overflow: hidden;
        z-index: 15;
    }

    .gmail-progress-bar-value {
        width: 100%;
        height: 100%;
        background-color: #0b57d0;
        position: absolute;
        animation: gmailProgressIndeterminate 1.2s infinite ease-in-out;
        transform-origin: 0% 50%;
    }

    @keyframes gmailProgressIndeterminate {
        0% { transform: translateX(-100%) scaleX(0.2); }
        50% { transform: translateX(0%) scaleX(0.7); }
        100% { transform: translateX(100%) scaleX(0.2); }
    }

    /* Gmail Centered Preloader */
    .gmail-list-preloader {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 260px;
        width: 100%;
        gap: 14px;
        padding: 50px 0;
        user-select: none;
    }

    .gmail-spinner {
        width: 36px;
        height: 36px;
        border: 3.5px solid #e0e2e7;
        border-top-color: #0b57d0;
        border-right-color: #0b57d0;
        border-radius: 50%;
        animation: gmailSpinnerRotate 0.75s linear infinite;
    }

    @keyframes gmailSpinnerRotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .gmail-loading-text {
        color: #5f6368;
        font-size: 13.5px;
        font-weight: 500;
        letter-spacing: 0.1px;
    }

    /* Google Category Tabs */
    .gmail-category-tabs {
        display: flex;
        align-items: stretch;
        background: #ffffff;
        border-bottom: 1px solid var(--gmail-border);
        user-select: none;
        flex-shrink: 0;
    }

    .gmail-tab-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 24px;
        cursor: pointer;
        position: relative;
        font-size: 13.5px;
        font-weight: 500;
        color: #5f6368;
        transition: color 0.15s, background-color 0.15s;
        border-bottom: 3px solid transparent;
        margin-bottom: -1px;
    }

    .gmail-tab-item:hover {
        background-color: #f6f8fc;
        color: #202124;
    }

    .gmail-tab-item.active {
        color: #0b57d0;
        font-weight: 600;
        border-bottom-color: #0b57d0;
    }

    .gmail-tab-item svg {
        width: 18px;
        height: 18px;
        fill: #5f6368;
        transition: fill 0.15s;
    }

    .gmail-tab-item.active svg {
        fill: #0b57d0;
    }

    /* Gmail Selected Toolbar */
    .gmail-selected-bar {
        display: none;
        align-items: center;
        width: 100%;
        gap: 4px;
    }

    .gmail-selected-bar.active {
        display: flex;
    }

    .gmail-selected-count {
        font-size: 13px;
        font-weight: 600;
        color: #1f1f1f;
        padding: 0 8px;
        min-width: 76px;
    }

    /* Gmail Undo Toast / Snackbar */
    .gmail-snackbar {
        position: fixed;
        bottom: 24px;
        left: 280px;
        background: #202124;
        color: #ffffff;
        padding: 10px 18px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 13.5px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
        z-index: 10000;
        transition: opacity 0.2s ease, transform 0.2s ease;
        opacity: 0;
        pointer-events: none;
        transform: translateY(12px);
    }

    .gmail-snackbar.active {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0);
    }

    .gmail-snackbar-undo {
        color: #c2e7ff;
        font-weight: 600;
        cursor: pointer;
        background: none;
        border: none;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 13.5px;
        transition: background 0.15s;
    }

    .gmail-snackbar-undo:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
    }

    .gmail-snackbar-close {
        color: #9aa0a6;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 15px;
        display: flex;
        align-items: center;
        padding: 2px;
    }

    .gmail-snackbar-close:hover {
        color: #ffffff;
    }

    /* Row Animations & Keyboard Focus */
    .gmail-row.keyboard-focused,
    .duralux-email-item.keyboard-focused {
        box-shadow: inset 3px 0 0 #0b57d0, inset 0 0 0 1px #0b57d0 !important;
        background-color: #eaf1fb !important;
    }

    .gmail-row.row-fade-out,
    .duralux-email-item.row-fade-out {
        opacity: 0;
        transform: translateX(-30px);
        max-height: 0 !important;
        min-height: 0 !important;
        height: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        margin: 0 !important;
        border: none !important;
        overflow: hidden;
        transition: all 0.25s ease-out;
    }

    /* Scrollable Email List */
    .duralux-email-list-wrapper {
        flex: 1;
        overflow-y: auto;
        height: 100%;
        position: relative;
        padding: 0;
        background: #ffffff;
    }

    .duralux-email-list-wrapper::-webkit-scrollbar {
        width: 6px;
    }
    .duralux-email-list-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    /* =========================================================
       AUTHENTIC GMAIL ROW DESIGN (Zero Table Borders!)
       ========================================================= */
    .gmail-row,
    .duralux-email-item {
        display: flex;
        align-items: center;
        height: 44px;
        min-height: 44px;
        padding: 0 16px;
        border-bottom: 1px solid var(--gmail-border-subtle);
        background: #ffffff;
        cursor: pointer;
        transition: background-color 0.15s ease, box-shadow 0.15s ease;
        position: relative;
        user-select: none;
        margin: 0;
        box-sizing: border-box;
    }

    .gmail-row.is-read,
    .duralux-email-item:not(.unread) {
        background: #f2f6fc !important;
    }

    .gmail-row.unread,
    .duralux-email-item.unread {
        background: #ffffff !important;
    }

    .gmail-row:hover,
    .duralux-email-item:hover {
        background: #eaf1fb !important;
        box-shadow: inset 1px 0 0 #dadce0, inset -1px 0 0 #dadce0, 0 1px 2px 0 rgba(60, 64, 67, 0.15);
        z-index: 2;
    }

    .gmail-row.selected,
    .duralux-email-item.selected {
        background-color: #c2e7ff !important;
    }

    /* Row Left: Checkbox & Star */
    .gmail-row-controls,
    .duralux-item-left {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 68px;
        flex-shrink: 0;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        min-height: auto !important;
    }

    .gmail-checkbox-wrap {
        display: flex;
        align-items: center;
        cursor: pointer;
        margin: 0;
    }

    .gmail-star-btn,
    .duralux-star-btn {
        background: none;
        border: none;
        padding: 0;
        color: #c4c7c5;
        cursor: pointer;
        font-size: 15px;
        display: flex;
        align-items: center;
        transition: color 0.15s ease, transform 0.1s ease;
    }

    .gmail-star-btn:hover,
    .duralux-star-btn:hover {
        color: #444746;
        transform: scale(1.1);
    }

    .gmail-star-btn.active,
    .duralux-star-btn.active {
        color: var(--gmail-star) !important;
    }

    .gmail-unread-dot,
    .duralux-unread-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: var(--gmail-blue);
        display: inline-block;
        flex-shrink: 0;
    }

    /* Row Sender: Clean Name with Ellipsis */
    .gmail-row-sender,
    .duralux-email-sender {
        width: 190px;
        max-width: 190px;
        padding: 0 14px 0 0 !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 13.5px;
        color: #444746;
        flex-shrink: 0;
        border: none !important;
        margin: 0 !important;
    }

    .gmail-row.unread .gmail-row-sender,
    .duralux-email-item.unread .duralux-email-sender {
        font-weight: 700 !important;
        color: var(--gmail-text) !important;
    }

    /* Row Content: Seamless Subject + Snippet on ONE line */
    .gmail-row-content,
    .duralux-item-content {
        flex: 1;
        min-width: 0;
        display: flex;
        align-items: center;
        padding: 0 12px 0 0 !important;
        border: none !important;
    }

    .gmail-content-line,
    .duralux-email-body-preview {
        display: flex;
        align-items: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
        font-size: 13.5px;
        gap: 5px;
        padding: 0 !important;
        border: none !important;
    }

    .gmail-row-subject,
    .duralux-email-subject {
        color: var(--gmail-text);
        font-weight: 500;
        flex-shrink: 0;
        margin: 0 !important;
    }

    .gmail-row.unread .gmail-row-subject,
    .duralux-email-item.unread .duralux-email-subject {
        font-weight: 700 !important;
        color: var(--gmail-text) !important;
    }

    .gmail-row-snippet {
        color: var(--gmail-text-muted);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 400;
    }

    .gmail-row-labels {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-right: 6px;
        flex-shrink: 0;
    }

    .gmail-label-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        padding: 1px 7px;
        border-radius: 4px;
        background-color: rgba(68, 71, 70, 0.08);
        color: var(--gmail-text);
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    .gmail-label-chip .label-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: var(--label-color, #1a73e8);
    }

    .gmail-label-chip.more {
        background: #e2e8f0;
        color: #475569;
    }

    /* Row Right: Date + Gmail Hover Actions */
    .gmail-row-right,
    .duralux-item-right {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        width: 175px;
        flex-shrink: 0;
        position: relative;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .gmail-date-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--gmail-text-muted);
        white-space: nowrap;
        transition: opacity 0.15s ease;
    }

    .gmail-row.unread .gmail-date-wrap,
    .duralux-email-item.unread .duralux-email-time {
        font-weight: 700 !important;
        color: var(--gmail-text) !important;
    }

    .gmail-clip-icon {
        font-size: 13px;
        color: #747775;
    }

    /* Gmail Hover Actions Overlay */
    .gmail-hover-actions {
        display: none;
        align-items: center;
        gap: 4px;
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        background: #eaf1fb;
        padding: 2px 4px;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(60, 64, 67, 0.15);
        z-index: 10;
    }

    .gmail-row:hover .gmail-date-wrap,
    .duralux-email-item:hover .gmail-date-wrap {
        opacity: 0;
        pointer-events: none;
    }

    .gmail-row:hover .gmail-hover-actions,
    .duralux-email-item:hover .gmail-hover-actions {
        display: flex !important;
    }

    .gmail-hover-btn {
        width: 30px;
        height: 30px;
        min-width: 30px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: #444746;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    .gmail-hover-btn:hover {
        background-color: #d3e3fd;
        color: #041e49;
    }

    .gmail-hover-btn svg {
        display: block;
        pointer-events: none;
    }

    .gmail-hover-btn.gmail-hover-wa-btn {
        color: #25D366 !important;
    }
    .gmail-hover-btn.gmail-hover-wa-btn:hover {
        background-color: #dcfce7 !important;
        color: #15803d !important;
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

    .fa-whatsapp,
    .fa-whatsapp::before,
    i.fa.fa-whatsapp {
        font-family: "FontAwesome", "Font Awesome 5 Brands" !important;
        font-weight: normal !important;
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
        height: 48px;
        padding: 0 16px;
        border-bottom: 1px solid #e0e2e7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        flex-shrink: 0;
        z-index: 10;
    }

    .duralux-detail-body {
        padding: 14px 16px 60px 16px;
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden !important;
        max-width: 100%;
        margin: 0;
        width: 100%;
        box-sizing: border-box;
        background: #ffffff;
    }

    .duralux-detail-body::-webkit-scrollbar {
        width: 8px;
    }
    .duralux-detail-body::-webkit-scrollbar-thumb {
        background: #dadce0;
        border-radius: 4px;
    }

    /* Gmail Subject Header */
    .gmail-thread-header-wrap {
        padding: 6px 0 16px 0;
        border-bottom: 1px solid #f1f3f4;
        margin-bottom: 16px;
    }

    .gmail-thread-subject-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
    }

    .gmail-detail-subject-title {
        font-size: 22px;
        font-weight: 400;
        color: #1f1f1f;
        line-height: 1.35;
        letter-spacing: -0.2px;
        margin: 0;
        flex: 1;
        word-break: break-word;
    }

    .gmail-subject-labels {
        display: inline-flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 6px;
    }

    .gmail-subject-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11.5px;
        font-weight: 500;
        background: #e8eaed;
        color: #3c4043;
    }

    .gmail-subject-badge .badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }

    /* Gmail Message Thread Item */
    .gmail-thread-message {
        border-bottom: 1px solid #e8eaed;
        position: relative;
        transition: background-color 0.15s ease;
    }

    .gmail-thread-message:last-child {
        border-bottom: none;
    }

    /* Collapsed state */
    .gmail-thread-message.collapsed {
        padding: 0;
    }

    .gmail-thread-message.collapsed .gmail-msg-expanded-content {
        display: none !important;
    }

    .gmail-thread-message.collapsed .gmail-msg-collapsed-strip {
        display: flex !important;
    }

    /* Expanded state */
    .gmail-thread-message:not(.collapsed) .gmail-msg-collapsed-strip {
        display: none !important;
    }

    .gmail-thread-message:not(.collapsed) .gmail-msg-expanded-content {
        display: block !important;
        padding: 16px 0 24px 0;
    }

    /* Collapsed Strip - Authentic Gmail UI matching user screenshot */
    .gmail-msg-collapsed-strip {
        display: none;
        align-items: center;
        gap: 14px;
        padding: 10px 14px;
        cursor: pointer;
        user-select: none;
        border-radius: 6px;
        transition: background-color 0.15s ease, box-shadow 0.15s ease;
        min-height: 44px;
        background: #ffffff;
    }

    .gmail-msg-collapsed-strip:hover {
        background-color: #f2f6fc;
    }

    .gmail-collapsed-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        color: #ffffff;
        flex-shrink: 0;
    }

    .gmail-collapsed-sender {
        font-size: 14px;
        font-weight: 700;
        color: #202124;
        flex-shrink: 0;
        width: 170px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .gmail-collapsed-snippet {
        font-size: 13px;
        color: #5f6368;
        flex: 1;
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: inherit;
    }

    .gmail-collapsed-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
        margin-left: auto;
    }

    .gmail-collapsed-date {
        font-size: 12px;
        color: #5f6368;
        white-space: nowrap;
    }

    .gmail-msg-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
        cursor: pointer;
    }

    .gmail-msg-sender-group {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        flex: 1;
        min-width: 0;
    }

    .gmail-msg-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
        color: #ffffff;
        flex-shrink: 0;
    }

    .gmail-msg-sender-details {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .gmail-msg-from-line {
        display: flex;
        align-items: baseline;
        gap: 6px;
        flex-wrap: wrap;
    }

    .gmail-msg-from-name {
        font-size: 14.5px;
        font-weight: 700;
        color: #202124;
    }

    .gmail-msg-from-email {
        font-size: 12px;
        color: #5f6368;
    }

    /* Iconic "to me" button & details card */
    .gmail-to-me-btn {
        background: none;
        border: none;
        padding: 0;
        font-size: 12px;
        color: #5f6368;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        margin-top: 2px;
    }

    .gmail-to-me-btn:hover {
        color: #202124;
    }

    .gmail-details-card {
        border-radius: 8px !important;
        border: 1px solid #dadce0 !important;
        min-width: 380px;
        font-size: 12.5px;
        padding: 14px !important;
        background: #ffffff;
    }

    .gmail-details-grid {
        display: grid;
        grid-template-columns: 60px 1fr;
        row-gap: 6px;
        column-gap: 8px;
        color: #3c4043;
    }

    .gmail-details-grid .text-muted {
        color: #5f6368 !important;
        text-align: right;
    }

    .gmail-msg-right-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .gmail-msg-date-str {
        font-size: 12px;
        color: #5f6368;
        white-space: nowrap;
    }

    /* Message Body */
    .gmail-msg-body-wrapper {
        margin-left: 54px;
        margin-right: 54px;
        color: #202124;
        font-size: 14px;
        line-height: 1.6;
        min-height: 40px;
        width: auto !important;
        max-width: calc(100% - 108px) !important;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    /* Gmail Attachment Cards */
    .gmail-attachments-section {
        margin-left: 54px;
        margin-right: 54px;
        width: auto !important;
        max-width: calc(100% - 108px) !important;
        box-sizing: border-box;
        margin-top: 20px;
        padding-top: 14px;
        border-top: 1px solid #f1f3f4;
    }

    .gmail-att-heading {
        font-size: 12px;
        font-weight: 600;
        color: #5f6368;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .gmail-att-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

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

    /* Gmail Bottom Reply Pills */
    .gmail-thread-bottom-pills {
        margin-left: 54px;
        margin-right: 54px;
        margin-top: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .gmail-bottom-pill-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 24px;
        border-radius: 20px;
        border: 1px solid #747775;
        background: #ffffff;
        color: #1f1f1f;
        font-weight: 500;
        font-size: 13.5px;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .gmail-bottom-pill-btn:hover {
        background: #f8fafc;
        border-color: #1f1f1f;
        color: #1f1f1f;
    }

    .gmail-bottom-pill-btn svg {
        fill: #444746;
    }

    /* Authentic Gmail Inline Reply Box */
    .duralux-quick-reply {
        margin-left: 54px;
        margin-right: 54px;
        border: 1px solid #dadce0;
        border-radius: 16px;
        padding: 16px 20px;
        background: #ffffff;
        margin-top: 20px;
        box-shadow: 0 1px 3px rgba(60, 64, 67, 0.12), 0 1px 2px rgba(60, 64, 67, 0.08);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .duralux-quick-reply.highlight-focus {
        border-color: #0b57d0 !important;
        box-shadow: 0 1px 3px rgba(60, 64, 67, 0.2), 0 0 0 2px rgba(11, 87, 208, 0.18) !important;
    }

    .mode-tab-btn {
        background: transparent;
        border: none;
        padding: 6px 12px;
        font-weight: 500;
        font-size: 13px;
        border-radius: 6px;
        color: #444746;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: all 0.15s ease;
    }

    .mode-tab-btn:hover {
        background: #eaf1fb;
        color: #041e49;
    }

    .mode-tab-btn.active {
        background: #d3e3fd;
        color: #041e49;
        font-weight: 600;
    }

    .gmail-reply-send-btn {
        background-color: #0b57d0;
        color: #ffffff;
        border: none;
        border-radius: 18px;
        padding: 0 24px;
        height: 36px;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: background-color 0.15s ease, box-shadow 0.15s ease;
    }

    .gmail-reply-send-btn:hover {
        background-color: #0842a0;
        box-shadow: 0 1px 2px 0 rgba(60, 64, 67, 0.3), 0 1px 3px 1px rgba(60, 64, 67, 0.15);
    }

    @media (max-width: 768px) {
        .duralux-detail-body {
            padding: 12px 16px 40px 16px !important;
        }
        .gmail-msg-body-wrapper,
        .gmail-attachments-section,
        .gmail-thread-bottom-pills,
        .duralux-quick-reply {
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .gmail-details-card {
            min-width: 280px !important;
        }
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

    .gmail-recipient-shell {
        flex: 1;
        min-width: 0;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 5px;
        padding: 2px 0;
    }

    .gmail-recipient-shell .gmail-field-input {
        flex: 1 1 150px;
        min-width: 120px;
    }

    .gmail-recipient-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        max-width: 260px;
        padding: 3px 7px 3px 9px;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        background: #f1f5f9;
        color: #334155;
        font-size: 12px;
        line-height: 18px;
    }

    .gmail-recipient-chip-name {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .gmail-recipient-chip-remove {
        border: 0;
        background: transparent;
        color: #64748b;
        padding: 0;
        line-height: 1;
        cursor: pointer;
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

    /* Modern Incoming Email Alert Toast */
    .incoming-email-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 380px;
        max-width: calc(100vw - 32px);
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 16px;
        box-shadow: 0 20px 35px -5px rgba(15, 23, 42, 0.16), 0 10px 15px -5px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(226, 232, 240, 0.85);
        padding: 16px 18px 14px;
        z-index: 999999;
        display: none;
        opacity: 0;
        transform: translateY(20px) scale(0.97);
        transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1), transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
    }
    .incoming-email-toast.show {
        display: block;
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    .incoming-email-toast .toast-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.25);
    }
    .incoming-email-toast .toast-badge {
        background: #eef2ff;
        color: #4338ca;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .incoming-email-toast .toast-badge i {
        font-size: 10px;
    }
    .incoming-email-toast .toast-time {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 500;
    }
    .incoming-email-toast .toast-sender {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 3px;
    }
    .incoming-email-toast .toast-subject {
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 1px;
    }
    .incoming-email-toast .toast-preview {
        font-size: 11.5px;
        color: #64748b;
        line-height: 1.4;
        margin-top: 2px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-word;
    }
    .incoming-email-toast .toast-close-btn {
        background: transparent;
        border: none;
        width: 24px;
        height: 24px;
        border-radius: 6px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
        transition: background-color 0.15s, color 0.15s;
        flex-shrink: 0;
    }
    .incoming-email-toast .toast-close-btn:hover {
        background: #f1f5f9;
        color: #334155;
    }
    .incoming-email-toast .toast-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 12px;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
    }
    .incoming-email-toast .btn-toast-dismiss {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .incoming-email-toast .btn-toast-dismiss:hover {
        background: #f1f5f9;
        color: #1e293b;
    }
    .incoming-email-toast .btn-toast-view {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: none;
        color: #ffffff;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
        transition: all 0.15s ease;
    }
    .incoming-email-toast .btn-toast-view:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
    }
    .incoming-email-toast .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #6366f1);
        width: 100%;
        border-radius: 0 0 16px 16px;
    }
    @media (max-width: 576px) {
        .incoming-email-toast {
            bottom: 12px;
            right: 12px;
            left: 12px;
            width: auto;
            max-width: none;
        }
    }

    .pending-email-item {
        background: #fffbeb !important;
        border-color: #fbbf24 !important;
        border-left: 3px solid #f59e0b !important;
        animation: pulsePending 2s infinite ease-in-out;
    }

    @media (max-width: 900px) {
        .duralux-sidebar { width: 70px; }
        .duralux-sidebar .btn-gmail-compose span,
        .duralux-sidebar .duralux-nav-link span,
        .duralux-sidebar .duralux-section-label,
        .duralux-sidebar .duralux-badge { display: none !important; }
        .duralux-sidebar .btn-gmail-compose { width: 44px !important; padding: 10px !important; }
        .duralux-sidebar .duralux-nav-link { padding: 10px; justify-content: center; margin-right: 0; }
        .duralux-sidebar .duralux-nav-link i.nav-icon { margin-right: 0; font-size: 18px; }
        .gmail-row-sender, .duralux-email-sender { width: 120px !important; max-width: 120px !important; }
        .gmail-search-box { width: 100% !important; }
    }

    @keyframes pulsePending {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
</style>
@endpush

@section('content')
<div class="duralux-email-wrapper">
    {{-- Main Email App UI --}}
    <div class="duralux-email-app">
        {{-- 1. Left Sidebar (Google Mail Style) --}}
        <div class="duralux-sidebar">
            {{-- Gmail Compose Pill Button Area --}}
            <div class="duralux-sidebar-top gmail-sidebar-top">
                <button type="button" class="btn-gmail-compose btn-duralux-compose" onclick="openComposeModal()" title="Compose">
                    <svg class="gmail-plus-icon" width="22" height="22" viewBox="0 0 24 24">
                        <path fill="#EA4335" d="M11 5v6H5v2h6v6h2v-6h6v-2h-6V5z"></path>
                    </svg>
                    <span>Compose</span>
                </button>
            </div>

            {{-- Independent Scrollable Navigation Area --}}
            <div class="duralux-sidebar-scroll">
                {{-- Folders List --}}
                <div class="duralux-section-label">
                    <span>Folders</span>
                </div>
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
                        <a href="javascript:void(0);" class="duralux-nav-link {{ request('folder') === 'starred' ? 'active' : '' }}" onclick="filterFolder('starred', this)">
                            <span><i class="fa fa-star nav-icon icon-starred"></i> Starred</span>
                            @if(($counts['starred'] ?? 0) > 0)
                                <span class="duralux-badge">{{ $counts['starred'] }}</span>
                            @endif
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
                            <span class="duralux-badge" id="unreadDraftsBadge" style="{{ ($counts['drafts'] ?? 0) > 0 ? '' : 'display: none;' }}">{{ $counts['drafts'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ request('folder') === 'all' ? 'active' : '' }}" onclick="filterFolder('all', this)">
                            <span><i class="fa fa-envelope-o nav-icon text-muted"></i> All Mail</span>
                            <span class="duralux-badge">{{ $counts['all'] ?? 0 }}</span>
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

                {{-- Labels / Tags --}}
                <div class="duralux-section-label">
                    <span>Tags & Labels</span>
                    <a href="{{ route('labels.index') }}" target="_blank" title="Manage Labels" class="text-muted"><i class="fa fa-cog"></i></a>
                </div>
                <ul class="duralux-nav-list">
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ empty($selectedLabelId) ? 'active' : '' }}" onclick="filterLabel(null, this)">
                            <span><i class="fa fa-tags nav-icon text-muted"></i> All Labels</span>
                        </a>
                    </li>
                    @php
                        $sidebarLabels = $allLabels ?? \App\Models\WhatsappChatLabel::forEmailAccount($currentAccount ?? null)->ordered()->get();
                    @endphp
                    @forelse($sidebarLabels as $lbl)
                        @php
                            $isActiveLabel = ($selectedLabelId == $lbl->id);
                        @endphp
                        <li class="duralux-nav-item">
                            <a href="javascript:void(0);" class="duralux-nav-link {{ $isActiveLabel ? 'active' : '' }}" onclick="filterLabel({{ $lbl->id }}, this)">
                                <span class="text-truncate" style="max-width: 170px;">
                                    <span class="duralux-dot" style="background: {{ $lbl->color }};"></span>
                                    {{ $lbl->name }}
                                </span>
                            </a>
                        </li>
                    @empty
                        <li class="px-4 py-2 text-muted fs-8">No labels found</li>
                    @endforelse
                </ul>

                @if($isWriterEmail ?? false)
                {{-- Deadline Type Section (Only for Writer Email) --}}
                <div class="duralux-section-label mt-4">
                    <span>Deadline Type</span>
                </div>
                <ul class="duralux-nav-list" id="deadlineTypeSidebarList">
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ empty($deadlineType) ? 'active' : '' }}" data-deadline="" onclick="filterDeadlineType('', this)">
                            <span class="duralux-dot" style="background: #6c757d;"></span>
                            <span class="duralux-nav-text">All Deadlines</span>
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ $deadlineType == 'less_2' ? 'active' : '' }}" data-deadline="less_2" onclick="filterDeadlineType('less_2', this)">
                            <span class="duralux-dot" style="background: #e11d48;"></span>
                            <span class="duralux-nav-text">&lt; 2 Days</span>
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ $deadlineType == '3_5' ? 'active' : '' }}" data-deadline="3_5" onclick="filterDeadlineType('3_5', this)">
                            <span class="duralux-dot" style="background: #d97706;"></span>
                            <span class="duralux-nav-text">3-5 Days</span>
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ $deadlineType == '6_15' ? 'active' : '' }}" data-deadline="6_15" onclick="filterDeadlineType('6_15', this)">
                            <span class="duralux-dot" style="background: #2563eb;"></span>
                            <span class="duralux-nav-text">6-15 Days</span>
                        </a>
                    </li>
                    <li class="duralux-nav-item">
                        <a href="javascript:void(0);" class="duralux-nav-link {{ $deadlineType == 'above_15' ? 'active' : '' }}" data-deadline="above_15" onclick="filterDeadlineType('above_15', this)">
                            <span class="duralux-dot" style="background: #059669;"></span>
                            <span class="duralux-nav-text">15 Days &amp; Above</span>
                        </a>
                    </li>
                </ul>
                @endif
            </div>
        </div>

        {{-- 2. Right Main Area --}}
        <div class="duralux-main-area">
            {{-- Google Mail Top Header: Search Bar & Account Chip --}}
            <div class="gmail-top-header">
                <div class="gmail-search-wrapper position-relative" id="gmailSearchWrapper">
                    <div class="gmail-search-box" id="gmailSearchBox">
                        <svg class="gmail-search-icon" width="20" height="20" viewBox="0 0 24 24" fill="#5f6368">
                            <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                        </svg>
                        @php
                            $initialSearchVal = request('search', '');
                            if ($initialSearchVal && (!Auth::check() || (int)Auth::user()->role_id !== 1)) {
                                if (filter_var($initialSearchVal, FILTER_VALIDATE_EMAIL) || strpos($initialSearchVal, '@') !== false) {
                                    $initialSearchVal = mask_email_for_display($initialSearchVal);
                                }
                            }
                        @endphp
                        <input type="text" class="gmail-search-input" id="emailSearchInput" placeholder="Search in mail" autocomplete="off" value="{{ $initialSearchVal }}" onfocus="handleSearchFocus(event)" oninput="handleSearchInput(event)" onkeydown="handleSearchKeyDown(event)">
                        <button type="button" class="gmail-clear-btn" id="clearSearchBtn" style="display: {{ request('search') ? 'inline-block' : 'none' }};" onclick="clearEmailSearch()" title="Clear search">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                            </svg>
                        </button>
                        {{-- Tune / Filter Icon Button --}}
                        <button type="button" class="gmail-filter-btn ms-1" id="gmailFilterBtn" onclick="toggleAdvancedFilterPopup(event)" title="Show search options">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 17v2h6v-2H3zM3 5v2h10V5H3zm10 16v-2h8v-2h-8v-2h-2v6h2zM7 9v2H3v2h4v2h2V9H7zm14 4v-2H11v2h10zm-6-4h2V7h4V5h-4V3h-2v6z"/>
                            </svg>
                        </button>
                    </div>

                    {{-- 1. Gmail Live Autocomplete & Search Results Dropdown --}}
                    <div class="gmail-search-dropdown d-none" id="gmailSearchDropdown">
                        {{-- Quick Filter Chips --}}
                        <div class="gmail-search-chips-row">
                            <button type="button" class="gmail-filter-chip" id="chip_has_attachment" data-chip="has_attachment" onclick="toggleFilterChip('has_attachment')">Has attachment</button>
                            <button type="button" class="gmail-filter-chip" id="chip_last_7_days" data-chip="last_7_days" onclick="toggleFilterChip('last_7_days')">Last 7 days</button>
                            <button type="button" class="gmail-filter-chip" id="chip_from_me" data-chip="from_me" onclick="toggleFilterChip('from_me')">From me</button>
                            <button type="button" class="gmail-filter-chip" id="chip_unread" data-chip="unread" onclick="toggleFilterChip('unread')">Unread</button>
                        </div>

                        {{-- Recent Searches List --}}
                        <div class="gmail-recent-searches-section" id="gmailRecentSearchesSection"></div>

                        {{-- Live Search Email Results Container --}}
                        <div class="gmail-search-results-list" id="gmailSearchResultsList"></div>

                        {{-- Bottom Action Row --}}
                        <div class="gmail-search-bottom-action" id="gmailSearchAllAction" onclick="submitSearchFromDropdown()">
                            <div class="bottom-text-wrap">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="#5f6368" style="flex-shrink: 0;">
                                    <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                                </svg>
                                <span id="gmailSearchBottomText">Search in all messages</span>
                            </div>
                            <span class="gmail-press-enter-label">Press ENTER</span>
                        </div>
                    </div>

                    {{-- 2. Gmail Advanced Filter Popup (Opened via sliders icon) --}}
                    <div class="gmail-advanced-filter-popup shadow-xl d-none" id="gmailAdvancedFilterPopup">
                        <form id="gmailAdvancedFilterForm" onsubmit="applyAdvancedFilter(event)">
                            <div class="p-3 px-4 d-flex flex-column gap-2.5">
                                <div class="row align-items-center g-2">
                                    <label class="col-sm-3 col-form-label text-muted fs-8 py-0">From</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm gmail-adv-input" id="advFilterFrom" placeholder="Sender email or name">
                                    </div>
                                </div>
                                <div class="row align-items-center g-2">
                                    <label class="col-sm-3 col-form-label text-muted fs-8 py-0">To</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm gmail-adv-input" id="advFilterTo" placeholder="Recipient email">
                                    </div>
                                </div>
                                <div class="row align-items-center g-2">
                                    <label class="col-sm-3 col-form-label text-muted fs-8 py-0">Subject</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm gmail-adv-input" id="advFilterSubject" placeholder="Subject keyword">
                                    </div>
                                </div>
                                <div class="row align-items-center g-2">
                                    <label class="col-sm-3 col-form-label text-muted fs-8 py-0">Has the words</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm gmail-adv-input" id="advFilterWords" placeholder="Search keywords">
                                    </div>
                                </div>
                                <div class="row align-items-center g-2">
                                    <label class="col-sm-3 col-form-label text-muted fs-8 py-0">Doesn't have</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm gmail-adv-input" id="advFilterDoesntHave" placeholder="Excluded words">
                                    </div>
                                </div>
                                {{-- Deadline Type Filter (< 2 Days, 3-5 Days, 6-15 Days, 15 Days & Above) --}}
                                <div class="row align-items-center g-2">
                                    <label class="col-sm-3 col-form-label text-muted fs-8 py-0">Deadline Type</label>
                                    <div class="col-sm-9">
                                        <select class="form-select form-select-sm gmail-adv-input" id="advFilterDeadlineType">
                                            <option value="">All Deadlines</option>
                                            <option value="less_2" {{ request('deadline_type') == 'less_2' ? 'selected' : '' }}>&lt; 2 Days</option>
                                            <option value="3_5" {{ request('deadline_type') == '3_5' ? 'selected' : '' }}>3-5 Days</option>
                                            <option value="6_15" {{ request('deadline_type') == '6_15' ? 'selected' : '' }}>6-15 Days</option>
                                            <option value="above_15" {{ request('deadline_type') == 'above_15' ? 'selected' : '' }}>15 Days &amp; Above</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row align-items-center g-2">
                                    <label class="col-sm-3 col-form-label text-muted fs-8 py-0">Order Code</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm gmail-adv-input" id="advFilterOrderCode" list="advOrderCodeDatalist" placeholder="Order Code (e.g. UKS60312)" autocomplete="off" value="{{ request('order_code') }}">
                                        <datalist id="advOrderCodeDatalist">
                                            @foreach(($recentOrderCodes ?? []) as $ordCode)
                                                <option value="{{ $ordCode }}">{{ $ordCode }}</option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row align-items-center g-2">
                                    <label class="col-sm-3 col-form-label text-muted fs-8 py-0">Date within</label>
                                    <div class="col-sm-4">
                                        <select class="form-select form-select-sm gmail-adv-input" id="advFilterDateWithin">
                                            <option value="1d">1 day</option>
                                            <option value="3d">3 days</option>
                                            <option value="7d" selected>1 week</option>
                                            <option value="14d">2 weeks</option>
                                            <option value="1m">1 month</option>
                                            <option value="2m">2 months</option>
                                            <option value="6m">6 months</option>
                                            <option value="1y">1 year</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="date" class="form-control form-control-sm gmail-adv-input" id="advFilterDateRef" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="row align-items-center g-2">
                                    <label class="col-sm-3 col-form-label text-muted fs-8 py-0">Search in</label>
                                    <div class="col-sm-9">
                                        <select class="form-select form-select-sm gmail-adv-input" id="advFilterFolder">
                                            <option value="all">All Mail</option>
                                            <option value="inbox" selected>Inbox</option>
                                            <option value="starred">Starred</option>
                                            <option value="sent">Sent</option>
                                            <option value="drafts">Drafts</option>
                                            <option value="trash">Trash</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row align-items-center g-2">
                                    <div class="col-sm-9 offset-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="advFilterHasAttachment">
                                            <label class="form-check-label text-dark fs-8" for="advFilterHasAttachment">
                                                Has attachment
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 px-4 bg-light rounded-bottom d-flex justify-content-between align-items-center border-top">
                                <button type="button" class="btn btn-sm btn-link text-muted p-0 text-decoration-none fs-8" onclick="resetAdvancedFilter()">
                                    Reset filters
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-light py-1.5 px-3" onclick="closeAdvancedFilterPopup()">Cancel</button>
                                    <button type="submit" class="btn btn-sm btn-primary py-1.5 px-4 rounded-pill fw-semibold">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if(isset($currentAccount) && $currentAccount)
                        <div class="dropdown">
                            <button type="button" class="gmail-account-badge border-0" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;" title="Switch Account">
                                <span class="gmail-account-dot"></span>
                                <span>{{ $currentAccount->name }}</span>
                                <i class="fa fa-caret-down ms-1" style="font-size: 11px; opacity: 0.7;"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end p-2 shadow-lg" style="min-width: 240px; border-radius: 12px; border: 1px solid #e0e2e7;">
                                <div class="px-3 py-2 border-bottom mb-1">
                                    <div class="fw-bold fs-7 text-dark">{{ $currentAccount->name }}</div>
                                    <div class="text-muted fs-8">{{ $currentAccount->email_address }}</div>
                                </div>
                                @foreach(($configurations ?? []) as $cfg)
                                    @php $isActive = (optional($currentAccount ?? null)->id == $cfg->id); @endphp
                                    <a href="{{ route('emails.index', ['account_id' => $cfg->id]) }}" class="dropdown-item d-flex align-items-center justify-content-between py-2 px-3 rounded {{ $isActive ? 'bg-light-primary text-primary fw-bold' : '' }}">
                                        <div class="d-flex align-items-center gap-2 text-truncate">
                                            <span class="duralux-dot" style="background: #10b981;"></span>
                                            <span class="text-truncate">{{ $cfg->name }}</span>
                                        </div>
                                        @if($isActive)
                                            <i class="fa fa-check text-primary fs-8"></i>
                                        @endif
                                    </a>
                                @endforeach
                                <div class="border-top my-1"></div>
                                <a href="{{ route('emails.settings') }}" class="dropdown-item py-2 px-3 text-muted fs-8 d-flex align-items-center gap-2">
                                    <i class="fa fa-cog"></i> Email Channels & Settings
                                </a>
                            </div>
                        </div>
                    @endif
                    <a href="{{ route('emails.settings') }}" class="gmail-icon-btn" title="Email Settings & Channels">
                        <i class="fa fa-cog"></i>
                    </a>
                </div>
            </div>

            {{-- Gmail Action Toolbar (Directly above email rows) --}}
            <div class="duralux-area-header gmail-action-toolbar">
                {{-- 1. Default Toolbar (When no rows are selected) --}}
                <div class="d-flex align-items-center justify-content-between w-100" id="gmailDefaultToolbar">
                    <div class="duralux-toolbar-left d-flex align-items-center gap-1">
                        <div class="dropdown d-inline-flex align-items-center">
                            <label class="gmail-checkbox-wrap me-1 mb-0" title="Select">
                                <input class="form-check-input" type="checkbox" id="selectAllEmails" onchange="toggleSelectAll(this)">
                            </label>
                            <button type="button" class="gmail-caret-btn" data-bs-toggle="dropdown" title="Select Filter">
                                <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu shadow-sm fs-8">
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="selectEmailsFilter('all')">All</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="selectEmailsFilter('none')">None</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="selectEmailsFilter('read')">Read</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="selectEmailsFilter('unread')">Unread</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="selectEmailsFilter('starred')">Starred</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="selectEmailsFilter('unstarred')">Unstarred</a></li>
                            </ul>
                        </div>

                        <button type="button" class="gmail-icon-btn ms-2" title="Refresh Messages" onclick="reloadEmailList()">
                            <i class="fa fa-refresh" id="mainRefreshIcon"></i>
                        </button>
                        
                        <div class="dropdown d-inline-block">
                            <button type="button" class="gmail-icon-btn" data-bs-toggle="dropdown" title="More Actions">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu shadow-sm fs-8">
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="markAllAsRead()"><i class="fa fa-envelope-open-o me-2 text-muted"></i> Mark all as read</a></li>
                            </ul>
                        </div>

                        @if($isWriterEmail ?? false)
                        {{-- Deadline Type Header Filter Bar (Only for Writer Email) --}}
                        <div class="d-inline-flex align-items-center gap-2 ms-3" id="deadlineTypeToolbarGroup">
                            <div class="deadline-type-header-badge">
                                <i class="fa fa-clock-o"></i>
                                <span>Deadline Type</span>
                            </div>
                            <div class="deadline-pill-container">
                                <button type="button" class="deadline-pill-btn deadline-pill-less2 {{ request('deadline_type') == 'less_2' ? 'active' : '' }}" 
                                        data-deadline="less_2" onclick="toggleDeadlineType('less_2')" title="Deadline <= 2 Days">
                                    &lt; 2 Days
                                </button>
                                <button type="button" class="deadline-pill-btn deadline-pill-35 {{ request('deadline_type') == '3_5' ? 'active' : '' }}" 
                                        data-deadline="3_5" onclick="toggleDeadlineType('3_5')" title="Deadline 3-5 Days">
                                    3-5 Days
                                </button>
                                <button type="button" class="deadline-pill-btn deadline-pill-615 {{ request('deadline_type') == '6_15' ? 'active' : '' }}" 
                                        data-deadline="6_15" onclick="toggleDeadlineType('6_15')" title="Deadline 6-15 Days">
                                    6-15 Days
                                </button>
                                <button type="button" class="deadline-pill-btn deadline-pill-above15 {{ request('deadline_type') == 'above_15' ? 'active' : '' }}" 
                                        data-deadline="above_15" onclick="toggleDeadlineType('above_15')" title="Deadline 15 Days & Above">
                                    15 Days &amp; Above
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="duralux-toolbar-right d-flex align-items-center gap-2">
                        <span class="text-muted fs-8 fw-semibold" id="emailPaginationInfo">
                            Showing {{ count($emails ?? []) }} conversations
                        </span>
                        <div class="d-flex align-items-center gap-1 ms-1">
                            <button type="button" class="gmail-icon-btn" title="Previous page" onclick="navigatePagination(-1)">
                                <i class="fa fa-chevron-left" style="font-size: 11px;"></i>
                            </button>
                            <button type="button" class="gmail-icon-btn" title="Next page" onclick="navigatePagination(1)">
                                <i class="fa fa-chevron-right" style="font-size: 11px;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- 2. Selected Actions Toolbar (Appears dynamically when 1+ checkboxes are checked) --}}
                <div class="gmail-selected-bar" id="gmailSelectedToolbar">
                    <div class="d-flex align-items-center gap-1">
                        <div class="dropdown d-inline-flex align-items-center">
                            <label class="gmail-checkbox-wrap me-1 mb-0" title="Select">
                                <input class="form-check-input" type="checkbox" id="masterCheckboxSelected" checked onchange="toggleSelectAll(this)">
                            </label>
                            <button type="button" class="gmail-caret-btn" data-bs-toggle="dropdown" title="Select Filter">
                                <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu shadow-sm fs-8">
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="selectEmailsFilter('all')">All</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="selectEmailsFilter('none')">None</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="selectEmailsFilter('read')">Read</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="selectEmailsFilter('unread')">Unread</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="selectEmailsFilter('starred')">Starred</a></li>
                            </ul>
                        </div>

                        <span class="gmail-selected-count" id="selectedCountBadge">1 selected</span>

                        <div class="gmail-v-sep"></div>

                        {{-- Archive --}}
                        <button type="button" class="gmail-icon-btn" title="Archive (e)" onclick="bulkArchive()">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M20.54 5.23l-1.39-1.68C18.88 3.21 18.47 3 18 3H6c-.47 0-.88.21-1.16.55L3.46 5.23C3.17 5.57 3 6.02 3 6.5V19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6.5c0-.48-.17-.93-.46-1.27zM12 17.5L6.5 12H10v-2h4v2h3.5L12 17.5zM5.12 5l.81-1h12l.94 1H5.12z"/></svg>
                        </button>

                        {{-- Report Spam --}}
                        <button type="button" class="gmail-icon-btn" title="Report spam" onclick="bulkMoveToFolder('spam')">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M15.73 3H8.27L3 8.27v7.46L8.27 21h7.46L21 15.73V8.27L15.73 3zM12 17.3c-.72 0-1.3-.58-1.3-1.3s.58-1.3 1.3-1.3 1.3.58 1.3 1.3-.58 1.3-1.3 1.3zm1-4.3h-2V7h2v6z"/></svg>
                        </button>

                        {{-- Delete / Move to Trash --}}
                        <button type="button" class="gmail-icon-btn" title="Delete (#)" onclick="bulkDelete()">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M15 4V3H9v1H4v2h1v13c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6h1V4h-5zm2 15H7V6h10v13zM9 8h2v9H9zm4 0h2v9h-2z"/></svg>
                        </button>

                        <div class="gmail-v-sep"></div>

                        {{-- Mark as Read --}}
                        <button type="button" class="gmail-icon-btn" title="Mark as read" onclick="bulkMarkRead()">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M21.99 8c0-.72-.37-1.35-.94-1.7L12 1 2.95 6.3C2.38 6.65 2 7.28 2 8v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2l-.01-10zM12 3.32L19.99 8v.01L12 13 4 8.01V8l8-4.68zM4 18v-8.2l7.46 4.66c.16.1.35.15.54.15s.38-.05.54-.15L20 9.8V18H4z"/></svg>
                        </button>

                        {{-- Mark as Unread --}}
                        <button type="button" class="gmail-icon-btn" title="Mark as unread" onclick="bulkMarkUnread()">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </button>

                        {{-- Star / Unstar --}}
                        <button type="button" class="gmail-icon-btn" title="Add star (s)" onclick="bulkStar()">
                            <i class="fa fa-star text-warning"></i>
                        </button>

                        <div class="gmail-v-sep"></div>

                        {{-- Move to Folder Dropdown --}}
                        <div class="dropdown d-inline-block">
                            <button type="button" class="gmail-icon-btn" data-bs-toggle="dropdown" title="Move to">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
                            </button>
                            <ul class="dropdown-menu shadow-sm fs-8">
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="bulkMoveToFolder('inbox')"><i class="fa fa-inbox me-2 text-muted"></i> Inbox</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="bulkMoveToFolder('archive')"><i class="fa fa-archive me-2 text-muted"></i> Archive</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="bulkMoveToFolder('spam')"><i class="fa fa-ban me-2 text-muted"></i> Spam</a></li>
                                <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="bulkMoveToFolder('trash')"><i class="fa fa-trash-o me-2"></i> Trash</a></li>
                            </ul>
                        </div>

                        {{-- Labels Dropdown --}}
                        <div class="dropdown d-inline-block">
                            <button type="button" class="gmail-icon-btn" data-bs-toggle="dropdown" title="Labels">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58s1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41s-.23-1.06-.59-1.42zM13 20.01L4 11V4h7v-.01l9 9-7 7.02z"/><circle cx="6.5" cy="6.5" r="1.5"/></svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-start p-3 shadow-lg" style="min-width: 220px;" onclick="event.stopPropagation();">
                                <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                                    <h6 class="fs-8 text-muted fw-bold text-uppercase m-0">Label As</h6>
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    @foreach(($allLabels ?? []) as $lbl)
                                        <button type="button" class="btn btn-sm btn-light d-flex align-items-center justify-content-start gap-2 py-1 px-2 border-0 text-start" onclick="bulkAssignLabel({{ $lbl->id }})">
                                            <span class="badge px-2 py-0.5 fs-9 fw-bold" style="background-color: {{ $lbl->color }}; color: #ffffff;">{{ $lbl->name }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ms-auto d-flex align-items-center">
                        <button type="button" class="gmail-icon-btn" title="Deselect all" onclick="selectEmailsFilter('none')">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Google Material Linear Progress Bar -->
            <div id="emailListProgressBar" class="gmail-progress-bar" style="display: none;">
                <div class="gmail-progress-bar-value"></div>
            </div>

            <!-- Gmail Category Tabs (Primary, Promotions, Social, Updates) -->
            <div class="gmail-category-tabs" id="gmailCategoryTabs" style="{{ (isset($folder) && $folder !== 'inbox') ? 'display: none;' : '' }}">
                <div class="gmail-tab-item active" data-category="primary" onclick="switchCategoryTab('primary', this)">
                    <svg viewBox="0 0 24 24"><path d="M19 3H4.99c-1.11 0-1.98.89-1.98 2L3 19c0 1.1.88 2 1.99 2H19c1.1 0 2-.9 2-2V5c0-1.11-.9-2-2-2zm0 12h-4c0 1.66-1.35 3-3 3s-3-1.34-3-3H4.99V5H19v10z"/></svg>
                    <span>Primary</span>
                </div>
                <div class="gmail-tab-item" data-category="promotions" onclick="switchCategoryTab('promotions', this)">
                    <svg viewBox="0 0 24 24"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58s1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41s-.23-1.06-.59-1.42zM13 20.01L4 11V4h7v-.01l9 9-7 7.02z"/><circle cx="6.5" cy="6.5" r="1.5"/></svg>
                    <span>Promotions</span>
                </div>
                <div class="gmail-tab-item" data-category="social" onclick="switchCategoryTab('social', this)">
                    <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    <span>Social</span>
                </div>
                <div class="gmail-tab-item" data-category="updates" onclick="switchCategoryTab('updates', this)">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span>Updates</span>
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
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="gmail-icon-btn" title="Back to list (u / Esc)" onclick="closeEmailThread()">
                            <i class="fa fa-arrow-left"></i>
                        </button>
                        <div class="gmail-v-sep"></div>
                        <button type="button" class="gmail-icon-btn" title="Archive (e)" onclick="archiveActiveThread()">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M20.54 5.23l-1.39-1.68C18.88 3.21 18.47 3 18 3H6c-.47 0-.88.21-1.16.55L3.46 5.23C3.17 5.57 3 6.02 3 6.5V19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6.5c0-.48-.17-.93-.46-1.27zM12 17.5L6.5 12H10v-2h4v2h3.5L12 17.5zM5.12 5l.81-1h12l.94 1H5.12z"/></svg>
                        </button>
                        <button type="button" class="gmail-icon-btn" title="Report spam" onclick="spamActiveThread()">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M15.73 3H8.27L3 8.27v7.46L8.27 21h7.46L21 15.73V8.27L15.73 3zM12 17.3c-.72 0-1.3-.58-1.3-1.3s.58-1.3 1.3-1.3 1.3.58 1.3 1.3-.58 1.3-1.3 1.3zm1-4.3h-2V7h2v6z"/></svg>
                        </button>
                        <button type="button" class="gmail-icon-btn text-danger" title="Delete message (#)" onclick="deleteActiveThread()">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M15 4V3H9v1H4v2h1v13c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6h1V4h-5zm2 15H7V6h10v13zM9 8h2v9H9zm4 0h2v9h-2z"/></svg>
                        </button>
                        <div class="gmail-v-sep"></div>
                        <button type="button" class="gmail-icon-btn" title="Mark as unread" onclick="markActiveThreadUnread()">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </button>
                        <button type="button" class="gmail-icon-btn" title="Star (s)" id="detailStarBtn" onclick="toggleDetailStar()">
                            <i class="fa fa-star-o"></i>
                        </button>
                        {{-- Move to folder dropdown --}}
                        <div class="dropdown d-inline-block">
                            <button type="button" class="gmail-icon-btn" data-bs-toggle="dropdown" title="Move to">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
                            </button>
                            <ul class="dropdown-menu shadow-sm fs-8">
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="moveActiveThreadToFolder('inbox')"><i class="fa fa-inbox me-2 text-muted"></i> Inbox</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="moveActiveThreadToFolder('archive')"><i class="fa fa-archive me-2 text-muted"></i> Archive</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="moveActiveThreadToFolder('spam')"><i class="fa fa-ban me-2 text-muted"></i> Spam</a></li>
                                <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="moveActiveThreadToFolder('trash')"><i class="fa fa-trash-o me-2"></i> Trash</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="dropdown">
                            <button type="button" class="gmail-icon-btn" data-bs-toggle="dropdown" id="detailLabelsDropdownBtn" title="Labels">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16zM16 17H5V7h11l3.55 5L16 17z"/></svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg" style="min-width: 220px;" onclick="event.stopPropagation()">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="fs-8 text-muted fw-bold text-uppercase m-0">Assign Labels</h6>
                                    <a href="{{ route('labels.index') }}" target="_blank" class="fs-9 text-primary fw-semibold"><i class="fa fa-cog"></i> Master</a>
                                </div>
                                <div id="threadLabelsChecklist" class="d-flex flex-column gap-1">
                                    @foreach(($allLabels ?? []) as $lbl)
                                        <label class="form-check form-check-custom form-check-solid d-flex align-items-center gap-2 p-1.5 rounded hover-bg-light cursor-pointer mb-0">
                                            <input class="form-check-input label-assign-checkbox" type="checkbox" value="{{ $lbl->id }}" id="label-chk-{{ $lbl->id }}" data-name="{{ $lbl->name }}" data-color="{{ $lbl->color }}" onchange="toggleActiveThreadLabel({{ $lbl->id }}, this.checked)">
                                            <span class="badge px-2 py-1 fs-8 fw-bold" style="background-color: {{ $lbl->color }}; color: #ffffff;">{{ $lbl->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="gmail-v-sep"></div>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="gmail-icon-btn" title="Newer conversation" onclick="navigateDetailThread(-1)">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <button type="button" class="gmail-icon-btn" title="Older conversation" onclick="navigateDetailThread(1)">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Scrollable Detail Body --}}
                <div class="duralux-detail-body">
                    <div class="gmail-thread-header-wrap">
                        <div class="gmail-thread-subject-row">
                            <div class="d-flex align-items-center gap-2 flex-wrap flex-grow-1">
                                <h2 class="gmail-detail-subject-title" id="detailSubject">Loading Subject...</h2>
                                <div class="gmail-subject-labels" id="detailLabelsBadges"></div>
                            </div>
                            <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                <a href="#"
                                   target="_blank"
                                   class="gmail-icon-btn text-success align-items-center justify-content-center"
                                   id="detailWhatsAppBtn"
                                   title="Open client in WhatsApp"
                                   aria-label="Open client in WhatsApp"
                                   style="display: none; color: #25D366 !important;">
                                    <svg width="18" height="18" viewBox="0 0 16 16" fill="#25D366"><path fill="#25D366" d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                                </a>
                                <button type="button" class="gmail-icon-btn" id="threadExpandAllBtn" onclick="toggleAllThreadMessages()" title="Expand / Collapse all">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M12 5.83L15.17 9l1.41-1.41L12 3 7.41 7.59 8.83 9 12 5.83zm0 12.34L8.83 15l-1.41 1.41L12 21l4.59-4.59L15.17 15 12 18.17z"/></svg>
                                </button>
                                <button type="button" class="gmail-icon-btn" onclick="window.print()" title="Print all">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
                                </button>
                                <button type="button" class="gmail-icon-btn" onclick="openActiveThreadInNewWindow()" title="In new window">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>
                                </button>
                            </div>
                        </div>
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
                    <div class="gmail-thread-bottom-pills" id="detailBottomActionPills">
                        <button type="button" class="gmail-bottom-pill-btn" onclick="openInlineComposer('reply')">
                            <svg width="17" height="17" viewBox="0 0 24 24"><path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"/></svg>
                            <span>Reply</span>
                        </button>
                        <button type="button" class="gmail-bottom-pill-btn" onclick="openInlineComposer('forward')">
                            <svg width="17" height="17" viewBox="0 0 24 24"><path d="M14 9V5l7 7-7 7v-4.1c-5 0-8.5 1.6-11 5.1 1-5 4-10 11-11z"/></svg>
                            <span>Forward</span>
                        </button>
                    </div>

                    {{-- Dedicated Inline Reply / Forward Composer Box (Google Mail authentic style) --}}
                    <div class="duralux-quick-reply" id="inlineComposerContainer" style="display: none;">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="mode-tab-btn active" id="inlineReplyTabBtn" onclick="setInlineComposerMode('reply')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="me-1"><path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"/></svg> Reply
                                </button>
                                <button type="button" class="mode-tab-btn" id="inlineForwardTabBtn" onclick="setInlineComposerMode('forward')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="me-1"><path d="M14 9V5l7 7-7 7v-4.1c-5 0-8.5 1.6-11 5.1 1-5 4-10 11-11z"/></svg> Forward
                                </button>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted fs-8 fw-semibold" id="inlineComposerModeLabel">Replying to sender</span>
                                <button type="button" class="gmail-icon-btn" title="Pop out to new window" onclick="popoutInlineComposer()">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>
                                </button>
                            </div>
                        </div>

                        <form id="inlineComposerForm" onsubmit="submitInlineComposer(event)">
                            {{-- Recipient Field --}}
                            <div class="mb-2">
                                <input type="email" class="form-control form-control-sm border-0 border-bottom rounded-0 px-1" name="to_email" id="inlineComposerToInput" required placeholder="Recipients">
                            </div>

                            {{-- Subject Field --}}
                            <div class="mb-2">
                                <input type="text" class="form-control form-control-sm border-0 border-bottom rounded-0 px-1" name="subject" id="inlineComposerSubjectInput" required placeholder="Subject">
                            </div>

                            {{-- Quill Rich Editor --}}
                            <div class="mb-2">
                                <div id="inlineQuillEditor" style="height: 140px; background: #ffffff; border: 1px solid #e0e2e7; border-radius: 8px;"></div>
                            </div>

                            {{-- Gmail Collapsible Quoted Email Preview Bar --}}
                            <div class="gmail-quote-collapsible-bar" id="inlineQuoteCollapsibleBar" style="display: none;">
                                <button type="button" class="gmail-quote-toggle-btn" onclick="toggleInlineQuotedBlock()" title="Show/hide trimmed content">
                                    <span class="gmail-quote-ellipsis">•••</span>
                                    <span class="fs-8 text-muted ms-2" id="inlineQuoteSummary"></span>
                                </button>
                                <div class="gmail-quote-preview-content p-2 mt-2 bg-light rounded border fs-8 text-muted" id="inlineQuotePreviewContent" style="display: none; max-height: 200px; overflow-y: auto;"></div>
                            </div>
                            <input type="hidden" name="quoted_html" id="inlineComposerQuotedHtml">

                            {{-- Selected & Forwarded Attachment Chips Container --}}
                            <div class="gmail-composer-chips" id="inlineComposerAttachmentChips" style="display: none;"></div>
                            <div id="inlineForwardedAttachmentsHiddenInputs"></div>

                            {{-- Action Controls --}}
                            <div class="d-flex align-items-center justify-content-between pt-2">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="submit" class="gmail-reply-send-btn" id="inlineComposerSendBtn">
                                        <span>Send</span>
                                    </button>
                                    <label class="gmail-icon-btn mb-0" title="Attach files" style="cursor: pointer;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5c0-1.38 1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z"/></svg>
                                        <input type="file" name="files[]" id="inlineComposerFileInput" multiple style="display: none;" onchange="handleInlineFileSelected(this)">
                                    </label>
                                    <span class="text-muted fs-8" id="inlineFileCountBadge"></span>
                                </div>

                                <button type="button" class="gmail-icon-btn" onclick="discardInlineComposer()" title="Discard draft">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M15 4V3H9v1H4v2h1v13c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6h1V4h-5zm2 15H7V6h10v13zM9 8h2v9H9zm4 0h2v9h-2z"/></svg>
                                </button>
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

    {{-- Compose Preloader Overlay --}}
    <div id="composeWidgetLoader" style="display: none; position: absolute; top: 42px; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.95); z-index: 50; flex-direction: column; align-items: center; justify-content: center; backdrop-filter: blur(1px);">
        <div class="gmail-spinner mb-2"></div>
        <div class="text-muted fs-7 fw-semibold" id="composeLoaderText">Loading draft...</div>
    </div>

    {{-- Compose Form Body --}}
    <form id="composeEmailForm" onsubmit="handleSendCompose(event)" class="d-flex flex-column flex-grow-1 overflow-hidden m-0" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="draft_id" id="composeDraftId" value="">
        <input type="hidden" name="thread_id" id="composeThreadId" value="">
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
                <div class="gmail-recipient-shell" id="composeToRecipientShell" onclick="document.getElementById('composeToEmail').focus()">
                    <span id="composeToRecipientChips" class="d-inline-flex align-items-center flex-wrap gap-1"></span>
                    <input type="text" id="composeToEmail" class="gmail-field-input" placeholder="Search name or enter email" autocomplete="off">
                    <input type="hidden" name="to_email" id="composeToEmailValue">
                </div>
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
                <input type="text" name="subject" id="composeSubject" class="gmail-field-input fw-semibold" placeholder="Subject" autocomplete="off">
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
                <div class="btn-group align-items-center">
                    <button type="submit" class="btn-gmail-send" id="btnSendCompose">
                        <span>Send</span>
                        <i class="fa fa-paper-plane fs-8"></i>
                    </button>
                    <span id="composeDraftStatus" class="fs-8 text-muted ms-3" style="display: none;">Saved</span>
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
<div id="incomingEmailAlert" class="incoming-email-toast">
    <div class="d-flex align-items-start gap-3">
        <div id="incomingAlertAvatar" class="toast-avatar">U</div>
        <div style="flex: 1; min-width: 0;">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="toast-badge"><i class="fa fa-envelope"></i> New Email</span>
                    <span class="toast-time" id="incomingAlertTime">Just now</span>
                </div>
                <button type="button" class="toast-close-btn" onclick="closeIncomingAlert()" title="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="toast-sender" id="incomingAlertSender">Sender Name</div>
            <div class="toast-subject" id="incomingAlertSubject">Subject Text</div>
            <div class="toast-preview" id="incomingAlertPreview"></div>
        </div>
    </div>
    <div class="toast-actions">
        <button type="button" class="btn-toast-dismiss" onclick="closeIncomingAlert()">Dismiss</button>
        <button type="button" class="btn-toast-view" id="incomingAlertOpenBtn" onclick="openIncomingEmailFromAlert()">
            <i class="fa fa-envelope-open-o"></i> View Message
        </button>
    </div>
    <div id="incomingAlertProgress" class="toast-progress"></div>
</div>

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

{{-- Gmail Bottom-Left Undo Toast Snackbar --}}
<div id="gmailSnackbar" class="gmail-snackbar">
    <span id="gmailSnackbarText">Conversation moved to Trash.</span>
    <button type="button" id="gmailSnackbarUndoBtn" class="gmail-snackbar-undo" onclick="executeSnackbarUndo()">Undo</button>
    <button type="button" class="gmail-snackbar-close" onclick="hideGmailSnackbar()">&times;</button>
</div>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="{{ asset('assets/plugins/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/docx-preview.min.js') }}"></script>

<script>
let currentFolder = @json($folder ?? 'inbox');
let currentAccountId = '{{ $currentAccount?->id ?? (request("account_id") ?: 1) }}';
let currentLabelId = @json($selectedLabelId ?? null);
let emailFolderHtmlCache = @json($folderHtmlCache ?? []);
let activeThreadId = null;
let activeEmailData = null;
let activeThreadMessages = [];
let activeReplyTargetMsg = null;
let inlineComposerMode = 'reply';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
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

        // Wire auto-save: trigger draft save on editor content change
        composeQuill.on('text-change', function() {
            scheduleComposeDraftAutoSave();
        });
    }

    // Wire auto-save on Subject, To, CC, and BCC fields
    const composeSubjectEl = document.getElementById('composeSubject');
    if (composeSubjectEl) {
        composeSubjectEl.addEventListener('input', scheduleComposeDraftAutoSave);
    }
    const composeToEl = document.getElementById('composeToEmail');
    if (composeToEl) {
        composeToEl.addEventListener('input', scheduleComposeDraftAutoSave);
    }
    const composeCcEl = document.getElementById('composeCcEmail');
    if (composeCcEl) {
        composeCcEl.addEventListener('input', scheduleComposeDraftAutoSave);
    }
    const composeBccEl = document.getElementById('composeBccEmail');
    if (composeBccEl) {
        composeBccEl.addEventListener('input', scheduleComposeDraftAutoSave);
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

let composeToRecipients = [];

function isValidRecipientEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(email || '').trim());
}

function addComposeRecipient(email, name = '') {
    const cleanEmail = String(email || '').trim().replace(/^<|>$/g, '').toLowerCase();
    if (!isValidRecipientEmail(cleanEmail)) return false;
    if (!composeToRecipients.some(recipient => recipient.email === cleanEmail)) {
        composeToRecipients.push({ email: cleanEmail, name: String(name || '').trim() });
    }
    renderComposeRecipientChips();
    scheduleComposeDraftAutoSave();
    return true;
}

function removeComposeRecipient(email) {
    composeToRecipients = composeToRecipients.filter(recipient => recipient.email !== email);
    renderComposeRecipientChips();
    document.getElementById('composeToEmail')?.focus();
    scheduleComposeDraftAutoSave();
}

function renderComposeRecipientChips() {
    const chips = document.getElementById('composeToRecipientChips');
    const hidden = document.getElementById('composeToEmailValue');
    if (!chips || !hidden) return;

    chips.innerHTML = composeToRecipients.map(recipient => {
        const label = recipient.name || recipient.email;
        return `<span class="gmail-recipient-chip" title="${escapeHtml(recipient.email)}">
            <span class="gmail-recipient-chip-name">${escapeHtml(label)}</span>
            <button type="button" class="gmail-recipient-chip-remove" aria-label="Remove ${escapeHtml(label)}" onclick="event.stopPropagation(); removeComposeRecipient('${recipient.email.replace(/'/g, "\\'")}')">&times;</button>
        </span>`;
    }).join('');
    hidden.value = composeToRecipients.map(recipient => recipient.email).join(',');
}

function clearComposeRecipients() {
    composeToRecipients = [];
    const input = document.getElementById('composeToEmail');
    if (input) input.value = '';
    renderComposeRecipientChips();
}

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

        dropdown.style.display = 'block';
        dropdown.innerHTML = `
            <div class="p-3 text-center text-muted fs-8 d-flex align-items-center justify-content-center gap-2">
                <div class="gmail-search-spinner" style="width: 15px; height: 15px; border-width: 2px;"></div>
                <span>Searching contacts...</span>
            </div>
        `;

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('emails.contacts.suggest') }}?q=${encodeURIComponent(query)}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                currentUsers = data.users || [];
                if (currentUsers.length === 0) {
                    dropdown.innerHTML = '<div class="p-2 text-center text-muted fs-8">No contacts found</div>';
                    return;
                }

                let html = '';
                currentUsers.forEach((u, idx) => {
                    const name = u.name || 'User';
                    const email = u.email || '';
                    const displayEmail = u.display_email || email;
                    const initials = (name || email).charAt(0).toUpperCase();
                    html += `
                        <div class="email-autocomplete-item" data-index="${idx}" data-email="${escapeHtml(email)}" data-name="${escapeHtml(name)}">
                            <div class="email-autocomplete-avatar">${escapeHtml(initials)}</div>
                            <div class="d-flex flex-column" style="min-width:0;">
                                <div class="email-autocomplete-name text-truncate">${escapeHtml(name)}</div>
                                <div class="email-autocomplete-email text-truncate">${escapeHtml(displayEmail)}</div>
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
                        if (inputId === 'composeToEmail') {
                            addComposeRecipient(this.dataset.email, this.dataset.name);
                            input.value = '';
                        } else {
                            input.value = this.dataset.email;
                        }
                        dropdown.style.display = 'none';
                        dropdown.innerHTML = '';
                        input.focus();
                    });
                });
            })
            .catch(err => console.warn('Recipient suggestion error', err));
        }, 180);
    });

    input.addEventListener('keydown', function(e) {
        const items = dropdown.querySelectorAll('.email-autocomplete-item');

        if (inputId === 'composeToEmail' && ['Enter', ',', 'Tab'].includes(e.key) && (selectedIndex < 0 || items.length === 0)) {
            const typedEmail = input.value.trim().replace(/,$/, '');
            if (typedEmail && addComposeRecipient(typedEmail)) {
                input.value = '';
                dropdown.style.display = 'none';
                dropdown.innerHTML = '';
                if (e.key !== 'Tab') e.preventDefault();
                return;
            }
        }

        if (inputId === 'composeToEmail' && e.key === 'Backspace' && !input.value && composeToRecipients.length) {
            removeComposeRecipient(composeToRecipients[composeToRecipients.length - 1].email);
            e.preventDefault();
            return;
        }

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

    if (inputId === 'composeToEmail') {
        input.addEventListener('blur', function() {
            setTimeout(() => {
                const typedEmail = input.value.trim();
                if (typedEmail && addComposeRecipient(typedEmail)) input.value = '';
            }, 200);
        });
    }

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

let composeDraftTimer = null;
let isSavingDraft = false;
let pendingDraftSaveRequested = false;

function scheduleComposeDraftAutoSave() {
    if (composeDraftTimer) clearTimeout(composeDraftTimer);
    composeDraftTimer = setTimeout(() => {
        saveComposeDraft();
    }, 2000);
}

function hasComposeContent() {
    const to = (document.getElementById('composeToEmailValue')?.value || '').trim();
    const typedTo = (document.getElementById('composeToEmail')?.value || '').trim();
    const subject = (document.getElementById('composeSubject')?.value || '').trim();
    const bodyText = composeQuill ? composeQuill.getText().trim() : '';
    const bodyHtml = composeQuill ? composeQuill.root.innerHTML : '';
    const hasHtml = bodyHtml && bodyHtml !== '<p><br></p>' && bodyHtml !== '<p></p>';
    return Boolean(to || typedTo || subject || bodyText || hasHtml);
}

function updateDraftsBadgeCount(count) {
    const badge = document.getElementById('unreadDraftsBadge');
    if (!badge) return;
    if (typeof count === 'number') {
        badge.textContent = count;
        badge.style.display = count > 0 ? '' : 'none';
    } else {
        let current = parseInt(badge.textContent) || 0;
        let next = Math.max(0, current - 1);
        badge.textContent = next;
        badge.style.display = next > 0 ? '' : 'none';
    }
}

function saveComposeDraft() {
    if (isSavingDraft) {
        pendingDraftSaveRequested = true;
        return;
    }
    if (!hasComposeContent()) return;

    const typedRecipient = document.getElementById('composeToEmail')?.value.trim();
    if (typedRecipient) addComposeRecipient(typedRecipient);

    const toEmail = document.getElementById('composeToEmailValue')?.value || '';
    const subject = document.getElementById('composeSubject')?.value || '';
    const bodyHtml = composeQuill ? composeQuill.root.innerHTML : '';
    const cc = document.getElementById('composeCcEmail')?.value || '';
    const bcc = document.getElementById('composeBccEmail')?.value || '';
    const accountId = document.getElementById('composeAccountId')?.value || currentAccountId;
    const draftId = document.getElementById('composeDraftId')?.value || '';
    const threadId = document.getElementById('composeThreadId')?.value || '';

    const draftStatus = document.getElementById('composeDraftStatus');
    if (draftStatus) {
        draftStatus.style.display = 'inline';
        draftStatus.textContent = 'Saving...';
    }

    isSavingDraft = true;

    const data = new FormData();
    data.append('_token', currentEmailCsrfToken());
    data.append('to', toEmail);
    data.append('subject', subject);
    data.append('body_html', bodyHtml);
    data.append('cc', cc);
    data.append('bcc', bcc);
    data.append('account_id', accountId);
    if (draftId) data.append('draft_id', draftId);
    if (threadId) data.append('thread_id', threadId);

    fetch('{{ route("emails.draft") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': currentEmailCsrfToken(),
            'Accept': 'application/json'
        },
        body: data
    })
    .then(res => res.json())
    .then(res => {
        isSavingDraft = false;
        if (res.success && res.draft_id) {
            const draftIdInput = document.getElementById('composeDraftId');
            if (draftIdInput && (!draftIdInput.value || draftIdInput.value == res.draft_id)) {
                draftIdInput.value = res.draft_id;
            }
            const threadIdInput = document.getElementById('composeThreadId');
            if (threadIdInput && res.thread_id) threadIdInput.value = res.thread_id;
            if (draftStatus) draftStatus.textContent = 'Draft saved';
            if (typeof res.drafts_count === 'number') {
                updateDraftsBadgeCount(res.drafts_count);
            }
            if (currentFolder === 'drafts') {
                reloadEmailList(false);
            }
        }
        if (pendingDraftSaveRequested) {
            pendingDraftSaveRequested = false;
            saveComposeDraft();
        }
    })
    .catch(err => {
        isSavingDraft = false;
        if (draftStatus) draftStatus.textContent = 'Could not save draft';
        if (pendingDraftSaveRequested) {
            pendingDraftSaveRequested = false;
            saveComposeDraft();
        }
    });
}

function openDraft(id) {
    if (!id) return;
    const inboxPath = window.location.pathname.replace(/\/+$/, '');
    const detailUrl = `${inboxPath}/${encodeURIComponent(id)}`;

    // Instantly open compose modal and show preloader
    openComposeModal({ draftId: id, isLoading: true });
    const loader = document.getElementById('composeWidgetLoader');
    if (loader) {
        loader.style.display = 'flex';
        const loaderText = document.getElementById('composeLoaderText');
        if (loaderText) loaderText.textContent = 'Loading draft...';
    }
    const winTitle = document.getElementById('composeWindowTitle');
    if (winTitle) winTitle.textContent = 'Loading Draft...';

    fetch(detailUrl, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    })
    .then(data => {
        if (loader) loader.style.display = 'none';
        if (data.email) {
            const em = data.email;
            openComposeModal({
                draftId: em.id,
                threadId: em.thread_id,
                accountId: em.email_configuration_id || currentAccountId,
                to: em.raw_to_email || em.to_email || '',
                subject: em.subject || '',
                body: em.body_html || em.body_plain || '',
                cc: em.cc || '',
                bcc: em.bcc || ''
            });
            if (winTitle) winTitle.textContent = em.subject ? ('Draft: ' + em.subject.substring(0, 30)) : 'Draft';
        }
    })
    .catch(err => {
        if (loader) loader.style.display = 'none';
        showSendingToast('Failed to load draft: ' + err.message, false, true);
        closeComposeWidgetUI();
    });
}

/* Gmail Floating Compose Controls */
function openComposeModal(prefill = {}) {
    const widget = document.getElementById('gmailComposeWidget');
    widget.classList.remove('minimized');
    widget.classList.add('active');

    const loader = document.getElementById('composeWidgetLoader');
    if (loader && !prefill.isLoading) {
        loader.style.display = 'none';
    }

    const winTitle = document.getElementById('composeWindowTitle');
    if (winTitle && !prefill.isLoading) {
        winTitle.textContent = prefill.draftId ? 'Edit Draft' : 'New Message';
    }

    const draftIdInput = document.getElementById('composeDraftId');
    if (draftIdInput) draftIdInput.value = prefill.draftId || '';

    const threadIdInput = document.getElementById('composeThreadId');
    if (threadIdInput) threadIdInput.value = prefill.threadId || '';

    if (prefill.accountId) {
        document.getElementById('composeAccountId').value = prefill.accountId;
    } else if (currentAccountId) {
        document.getElementById('composeAccountId').value = currentAccountId;
    }

    clearComposeRecipients();
    if (prefill.to) {
        String(prefill.to).split(',').forEach(email => addComposeRecipient(email));
    }
    if (prefill.subject) {
        document.getElementById('composeSubject').value = prefill.subject;
    } else if (!prefill.draftId) {
        document.getElementById('composeSubject').value = '';
    }
    if (prefill.body && composeQuill) {
        composeQuill.root.innerHTML = prefill.body;
    } else if (!prefill.draftId && composeQuill) {
        composeQuill.root.innerHTML = '';
    }

    if (!prefill.draftId) {
        removeSelectedFile();
    }

    if (prefill.cc) {
        document.getElementById('composeCcRow').style.display = 'flex';
        document.getElementById('composeCcEmail').value = prefill.cc;
    } else {
        document.getElementById('composeCcRow').style.display = 'none';
        document.getElementById('composeCcEmail').value = '';
    }

    if (prefill.bcc) {
        document.getElementById('composeBccRow').style.display = 'flex';
        document.getElementById('composeBccEmail').value = prefill.bcc;
    } else {
        document.getElementById('composeBccRow').style.display = 'none';
        document.getElementById('composeBccEmail').value = '';
    }

    const draftStatus = document.getElementById('composeDraftStatus');
    if (draftStatus) {
        draftStatus.textContent = prefill.draftId ? 'Draft saved' : '';
        draftStatus.style.display = prefill.draftId ? 'inline' : 'none';
    }

    if (!prefill.isLoading) {
        setTimeout(() => {
            if (!prefill.to) {
                document.getElementById('composeToEmail')?.focus();
            } else if (composeQuill) {
                composeQuill.focus();
            }
        }, 100);
    }
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

    // 1. Instantly close widget UI without waiting (<1ms)
    closeComposeWidgetUI();

    // 2. Hide loader if active
    const loader = document.getElementById('composeWidgetLoader');
    if (loader) loader.style.display = 'none';

    // 3. Save draft in background if content exists
    if (hasComposeContent()) {
        saveComposeDraft();
    }
}

function closeComposeWidgetUI() {
    if (composeDraftTimer) clearTimeout(composeDraftTimer);
    const widget = document.getElementById('gmailComposeWidget');
    if (widget) {
        widget.classList.remove('active', 'minimized', 'maximized');
    }
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
    pendingDraftSaveRequested = false;
    if (composeDraftTimer) clearTimeout(composeDraftTimer);
    const draftId = document.getElementById('composeDraftId')?.value;
    if (draftId) {
        if (!confirm('Discard this draft?')) return;
        fetch('{{ route("emails.delete") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': currentEmailCsrfToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id: draftId, permanent: true })
        })
        .then(() => {
            updateDraftsBadgeCount();
            if (currentFolder === 'drafts') reloadEmailList(false);
        });
    }

    document.getElementById('composeEmailForm').reset();
    document.getElementById('composeDraftId').value = '';
    document.getElementById('composeThreadId').value = '';
    const draftStatus = document.getElementById('composeDraftStatus');
    if (draftStatus) {
        draftStatus.textContent = '';
        draftStatus.style.display = 'none';
    }
    clearComposeRecipients();
    if (composeQuill) composeQuill.root.innerHTML = '';
    removeSelectedFile();
    closeComposeWidgetUI();
    showSendingToast('Draft discarded.', true);
}

function filterFolder(folder, el) {
    currentFolder = folder;
    currentLabelId = null;
    document.querySelectorAll('.duralux-sidebar .duralux-nav-link').forEach(link => link.classList.remove('active'));
    if (el) el.classList.add('active');
    closeEmailThread();

    const searchInput = document.getElementById('emailSearchInput');
    const searchValue = searchInput ? searchInput.value.trim() : '';

    if (!searchValue && !currentLabelId && emailFolderHtmlCache && emailFolderHtmlCache[folder]) {
        renderEmailRows(emailFolderHtmlCache[folder]);
        updateEmailBrowserUrl('');
    }
    reloadEmailList(true);
}

function filterLabel(labelId, el) {
    currentLabelId = labelId || null;
    document.querySelectorAll('.duralux-sidebar .duralux-nav-link').forEach(link => link.classList.remove('active'));
    if (el) el.classList.add('active');
    closeEmailThread();
    reloadEmailList(true);
}

// ── GMAIL SEARCH & FILTER ENGINE ──────────────────────────────────────────
let activeFilterChips = {
    has_attachment: false,
    last_7_days: false,
    from_me: false,
    unread: false
};
let activeAdvancedFilters = null;
let searchSuggestTimer = null;
let searchSuggestController = null;
let liveSearchResults = [];
let selectedLiveIndex = -1;

function handleSearchFocus(e) {
    openSearchDropdown();
    renderRecentSearches();
    const input = document.getElementById('emailSearchInput');
    const val = input ? input.value.trim() : '';
    if (val.length > 0) {
        fetchSearchSuggestions(val);
    }
}

function handleSearchInput(e) {
    const input = document.getElementById('emailSearchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    const val = input ? input.value.trim() : '';

    if (clearBtn) {
        clearBtn.style.display = val.length > 0 ? 'inline-block' : 'none';
    }

    const bottomText = document.getElementById('gmailSearchBottomText');
    if (bottomText) {
        bottomText.textContent = val.length > 0 ? `All search results for "${val}"` : 'Search in all messages';
    }

    openSearchDropdown();

    const listContainer = document.getElementById('gmailSearchResultsList');
    if (listContainer && val.length > 0) {
        listContainer.innerHTML = `
            <div class="gmail-search-loading">
                <div class="gmail-search-spinner"></div>
                <span>Searching in emails...</span>
            </div>`;
    }

    clearTimeout(searchSuggestTimer);
    searchSuggestTimer = setTimeout(() => {
        fetchSearchSuggestions(val);
    }, 180);
}

function handleSearchKeyDown(e) {
    const dropdown = document.getElementById('gmailSearchDropdown');
    const items = dropdown ? dropdown.querySelectorAll('.gmail-search-result-item, .gmail-recent-item') : [];

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (!items.length) return;
        selectedLiveIndex = (selectedLiveIndex + 1) % items.length;
        updateActiveSuggestionItem(items);
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (!items.length) return;
        selectedLiveIndex = (selectedLiveIndex - 1 + items.length) % items.length;
        updateActiveSuggestionItem(items);
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (selectedLiveIndex >= 0 && selectedLiveIndex < items.length) {
            items[selectedLiveIndex].click();
        } else {
            submitSearchFromDropdown();
        }
    } else if (e.key === 'Escape') {
        closeSearchDropdown();
        closeAdvancedFilterPopup();
    }
}

function updateActiveSuggestionItem(items) {
    items.forEach((it, idx) => {
        if (idx === selectedLiveIndex) {
            it.classList.add('selected');
            it.scrollIntoView({ block: 'nearest' });
        } else {
            it.classList.remove('selected');
        }
    });
}

function openSearchDropdown() {
    const dropdown = document.getElementById('gmailSearchDropdown');
    const searchBox = document.getElementById('gmailSearchBox');
    const searchWrapper = document.getElementById('gmailSearchWrapper');
    if (dropdown) dropdown.classList.remove('d-none');
    if (searchBox) searchBox.classList.add('has-dropdown-open');
    if (searchWrapper) searchWrapper.classList.add('has-dropdown-open');
    selectedLiveIndex = -1;
}

function closeSearchDropdown() {
    const dropdown = document.getElementById('gmailSearchDropdown');
    const searchBox = document.getElementById('gmailSearchBox');
    const searchWrapper = document.getElementById('gmailSearchWrapper');
    if (dropdown) dropdown.classList.add('d-none');
    if (searchBox) searchBox.classList.remove('has-dropdown-open');
    if (searchWrapper) searchWrapper.classList.remove('has-dropdown-open');
    selectedLiveIndex = -1;
}

function fetchSearchSuggestions(val) {
    if (searchSuggestController) {
        searchSuggestController.abort();
    }
    searchSuggestController = new AbortController();

    const listContainer = document.getElementById('gmailSearchResultsList');
    if (!listContainer) return;

    if (!val && !activeFilterChips.has_attachment && !activeFilterChips.last_7_days && !activeFilterChips.from_me && !activeFilterChips.unread) {
        listContainer.innerHTML = '';
        renderRecentSearches();
        return;
    }

    const recentContainer = document.getElementById('gmailRecentSearchesSection');
    if (recentContainer && val.length > 0) {
        recentContainer.innerHTML = '';
    }

    listContainer.innerHTML = `
        <div class="gmail-search-loading">
            <div class="gmail-search-spinner"></div>
            <span>Searching in emails...</span>
        </div>`;

    const url = new URL('{{ route("emails.search.suggest", [], false) }}', window.location.origin);
    url.searchParams.set('q', val);
    if (currentAccountId) url.searchParams.set('account_id', currentAccountId);
    if (activeFilterChips.has_attachment) url.searchParams.set('has_attachment', '1');
    if (activeFilterChips.last_7_days) url.searchParams.set('date_range', 'last_7_days');
    if (activeFilterChips.from_me) url.searchParams.set('from_me', '1');
    if (activeFilterChips.unread) url.searchParams.set('is_read', 'unread');

    fetch(url, {
        signal: searchSuggestController.signal,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (!data || !data.results) {
            listContainer.innerHTML = '';
            return;
        }
        liveSearchResults = data.results;
        renderLiveSearchResults(data.results, val);
    })
    .catch(err => {
        if (err.name !== 'AbortError') {
            console.warn('Search suggestions error', err);
            listContainer.innerHTML = '';
        }
    });
}

function renderLiveSearchResults(results, query) {
    const container = document.getElementById('gmailSearchResultsList');
    if (!container) return;

    if (!results.length) {
        container.innerHTML = `
            <div class="p-3 text-center text-muted" style="font-size: 13px;">
                <i class="fa fa-search me-1 text-muted"></i> No matching messages found for "<strong>${escapeHtml(query)}</strong>"
            </div>`;
        return;
    }

    let html = '';
    results.forEach(item => {
        const highlightedSubject = highlightMatch(item.subject, query);
        const highlightedParticipants = highlightMatch(item.participants, query);
        const iconSvg = `<svg width="18" height="18" viewBox="0 0 24 24" fill="${item.is_read ? '#747775' : '#0b57d0'}"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>`;
        const clipSvg = item.has_attachments ? `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#747775" stroke-width="2"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l7.9-7.9"/></svg>` : '';
        html += `
            <div class="gmail-search-result-item" data-id="${item.id}" onclick="selectLiveEmail(${item.id})">
                <span class="item-icon">${iconSvg}</span>
                <div class="item-main">
                    <div class="item-subject">${highlightedSubject}</div>
                    <div class="item-participants">${highlightedParticipants}</div>
                </div>
                <div class="item-meta">
                    ${clipSvg}
                    <span>${escapeHtml(item.date_formatted)}</span>
                </div>
            </div>`;
    });

    container.innerHTML = html;
}

function escapeHtml(value) {
    if (value === null || value === undefined) return '';
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function highlightMatch(text, query) {
    if (!query || !text) return escapeHtml(text || '');
    const cleanText = escapeHtml(text);
    const escapedQuery = String(query).trim().replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    if (!escapedQuery) return cleanText;
    const words = escapedQuery.split(/\s+/).filter(Boolean);
    if (!words.length) return cleanText;
    const regex = new RegExp(`(${words.join('|')})`, 'gi');
    return cleanText.replace(regex, '<mark class="gmail-search-highlight">$1</mark>');
}

function highlightSearchTermsInElement(rootElement, query) {
    if (!rootElement || !query) return;
    const words = String(query).trim().split(/\s+/).filter(w => w.length > 0);
    if (!words.length) return;
    const escapedWords = words.map(w => w.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'));
    const regex = new RegExp(`(${escapedWords.join('|')})`, 'gi');

    const walkTextNodes = (node) => {
        if (node.nodeType === Node.TEXT_NODE) {
            const text = node.nodeValue;
            if (regex.test(text)) {
                regex.lastIndex = 0;
                const doc = node.ownerDocument || document;
                const frag = doc.createDocumentFragment();
                let lastIdx = 0;
                let match;
                while ((match = regex.exec(text)) !== null) {
                    if (match.index > lastIdx) {
                        frag.appendChild(doc.createTextNode(text.substring(lastIdx, match.index)));
                    }
                    const mark = doc.createElement('mark');
                    mark.className = 'gmail-search-highlight';
                    mark.textContent = match[0];
                    frag.appendChild(mark);
                    lastIdx = regex.lastIndex;
                }
                if (lastIdx < text.length) {
                    frag.appendChild(doc.createTextNode(text.substring(lastIdx)));
                }
                node.parentNode.replaceChild(frag, node);
            }
        } else if (node.nodeType === Node.ELEMENT_NODE && !['SCRIPT', 'STYLE', 'BUTTON', 'INPUT', 'MARK', 'SELECT'].includes(node.tagName)) {
            Array.from(node.childNodes).forEach(walkTextNodes);
        }
    };

    walkTextNodes(rootElement);
}

function highlightSearchTermsInList(query) {
    const listContainer = document.getElementById('emailListContainer');
    if (!listContainer || !query) return;
    const targets = listContainer.querySelectorAll('.gmail-row-subject, .gmail-sender-text, .gmail-row-snippet');
    targets.forEach(el => highlightSearchTermsInElement(el, query));
}

function toggleFilterChip(chipKey) {
    activeFilterChips[chipKey] = !activeFilterChips[chipKey];
    const btn = document.getElementById(`chip_${chipKey}`);
    if (btn) {
        btn.classList.toggle('is-active', activeFilterChips[chipKey]);
    }

    closeEmailThread();
    reloadEmailList(true);

    const input = document.getElementById('emailSearchInput');
    const val = input ? input.value.trim() : '';
    fetchSearchSuggestions(val);
}

function selectLiveEmail(id) {
    const input = document.getElementById('emailSearchInput');
    const val = input ? input.value.trim() : '';
    if (val) saveRecentSearch(val);
    closeSearchDropdown();
    openEmailThread(id);
}

function submitSearchFromDropdown() {
    const input = document.getElementById('emailSearchInput');
    let val = input ? input.value.trim() : '';
    if (val && !window.canViewFullPhone && val.includes('@') && !val.includes('*') && window.maskEmailForDisplay) {
        val = window.maskEmailForDisplay(val);
        if (input) input.value = val;
    }
    if (val) saveRecentSearch(val);
    closeSearchDropdown();
    closeEmailThread();
    reloadEmailList(true);
}

function clearEmailSearch() {
    const searchInput = document.getElementById('emailSearchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    if (searchInput) searchInput.value = '';
    if (clearBtn) clearBtn.style.display = 'none';

    // Reset filter chips
    Object.keys(activeFilterChips).forEach(k => {
        activeFilterChips[k] = false;
        const btn = document.getElementById(`chip_${k}`);
        if (btn) btn.classList.remove('is-active');
    });

    activeAdvancedFilters = null;
    closeSearchDropdown();
    closeEmailThread();
    reloadEmailList(true);
}

// ── RECENT SEARCHES (LocalStorage) ────────────────────────────────────────
function getRecentSearches() {
    try {
        const stored = localStorage.getItem('gmail_recent_searches');
        return stored ? JSON.parse(stored) : [];
    } catch(e) {
        return [];
    }
}

function saveRecentSearch(term) {
    const clean = String(term || '').trim();
    if (clean.length < 2) return;
    let list = getRecentSearches().filter(item => item.toLowerCase() !== clean.toLowerCase());
    list.unshift(clean);
    list = list.slice(0, 5);
    try {
        localStorage.setItem('gmail_recent_searches', JSON.stringify(list));
    } catch(e) {}
}

function removeRecentSearch(e, term) {
    if (e) e.stopPropagation();
    let list = getRecentSearches().filter(item => item.toLowerCase() !== term.toLowerCase());
    try {
        localStorage.setItem('gmail_recent_searches', JSON.stringify(list));
    } catch(e) {}
    renderRecentSearches();
}

function renderRecentSearches() {
    const container = document.getElementById('gmailRecentSearchesSection');
    if (!container) return;
    const list = getRecentSearches();
    if (!list.length) {
        container.innerHTML = '';
        return;
    }

    let html = '';
    list.forEach(term => {
        html += `
            <div class="gmail-recent-item" onclick="applyRecentSearch('${escapeHtml(term)}')">
                <div class="d-flex align-items-center gap-3">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#747775" stroke-width="2" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span>${escapeHtml(term)}</span>
                </div>
                <button type="button" class="btn btn-sm text-muted border-0 bg-transparent recent-remove ms-auto" onclick="removeRecentSearch(event, '${escapeHtml(term)}')" title="Remove from history">
                    <i class="fa fa-times fs-8"></i>
                </button>
            </div>`;
    });

    container.innerHTML = html;
}

function applyRecentSearch(term) {
    const input = document.getElementById('emailSearchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    if (input) input.value = term;
    if (clearBtn) clearBtn.style.display = 'inline-block';
    saveRecentSearch(term);
    closeSearchDropdown();
    closeEmailThread();
    reloadEmailList(true);
}

// ── ADVANCED FILTER POPUP ────────────────────────────────────────────────
function toggleAdvancedFilterPopup(e) {
    if (e) e.stopPropagation();
    const popup = document.getElementById('gmailAdvancedFilterPopup');
    const btn = document.getElementById('gmailFilterBtn');
    if (!popup) return;

    const isClosed = popup.classList.contains('d-none');
    if (isClosed) {
        closeSearchDropdown();
        popup.classList.remove('d-none');
        if (btn) btn.classList.add('active');
    } else {
        closeAdvancedFilterPopup();
    }
}

function closeAdvancedFilterPopup() {
    const popup = document.getElementById('gmailAdvancedFilterPopup');
    const btn = document.getElementById('gmailFilterBtn');
    if (popup) popup.classList.add('d-none');
    if (btn) btn.classList.remove('active');
}

function applyAdvancedFilter(e) {
    if (e) e.preventDefault();
    activeAdvancedFilters = {
        filter_from: document.getElementById('advFilterFrom')?.value.trim() || '',
        filter_to: document.getElementById('advFilterTo')?.value.trim() || '',
        filter_subject: document.getElementById('advFilterSubject')?.value.trim() || '',
        filter_words: document.getElementById('advFilterWords')?.value.trim() || '',
        filter_doesnt_have: document.getElementById('advFilterDoesntHave')?.value.trim() || '',
        filter_date_within: document.getElementById('advFilterDateWithin')?.value || '',
        filter_date_ref: document.getElementById('advFilterDateRef')?.value || '',
        filter_order_code: document.getElementById('advFilterOrderCode')?.value.trim() || '',
        deadline_type: document.getElementById('advFilterDeadlineType')?.value || '',
    };

    // Synchronize pills and sidebar
    const dt = activeAdvancedFilters.deadline_type;
    document.querySelectorAll('.deadline-pill-btn').forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-deadline') === dt);
    });
    document.querySelectorAll('#deadlineTypeSidebarList .duralux-nav-link').forEach(link => {
        link.classList.toggle('active', (link.getAttribute('data-deadline') || '') === dt);
    });

    const hasAttachment = document.getElementById('advFilterHasAttachment')?.checked || false;
    activeFilterChips.has_attachment = hasAttachment;
    const chipBtn = document.getElementById('chip_has_attachment');
    if (chipBtn) chipBtn.classList.toggle('is-active', hasAttachment);

    const folderVal = document.getElementById('advFilterFolder')?.value || 'inbox';
    if (folderVal !== 'all' && folderVal !== currentFolder) {
        currentFolder = folderVal;
        document.querySelectorAll('.duralux-sidebar .duralux-nav-link').forEach(link => link.classList.remove('active'));
    }

    closeAdvancedFilterPopup();
    closeEmailThread();
    reloadEmailList(true);
}

function resetAdvancedFilter() {
    const form = document.getElementById('gmailAdvancedFilterForm');
    if (form) form.reset();

    if (activeAdvancedFilters) {
        delete activeAdvancedFilters.deadline_type;
        delete activeAdvancedFilters.filter_order_code;
    }
    activeAdvancedFilters = null;

    document.querySelectorAll('.deadline-pill-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('#deadlineTypeSidebarList .duralux-nav-link').forEach(link => {
        link.classList.toggle('active', (link.getAttribute('data-deadline') || '') === '');
    });

    closeAdvancedFilterPopup();
    closeEmailThread();
    reloadEmailList(true);
}

// ── DEADLINE TYPE FILTER (< 2 Days, 3-5 Days, 6-15 Days, 15 Days & Above) ────
function toggleDeadlineType(type) {
    const current = (activeAdvancedFilters && activeAdvancedFilters.deadline_type) || '';
    const newType = (current === type) ? '' : type;

    activeAdvancedFilters = activeAdvancedFilters || {};
    if (newType) {
        activeAdvancedFilters.deadline_type = newType;
    } else {
        delete activeAdvancedFilters.deadline_type;
    }

    // Synchronize popup input
    const advSel = document.getElementById('advFilterDeadlineType');
    if (advSel) advSel.value = newType;

    // Synchronize toolbar pills
    document.querySelectorAll('.deadline-pill-btn').forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-deadline') === newType);
    });

    // Synchronize sidebar links
    document.querySelectorAll('#deadlineTypeSidebarList .duralux-nav-link').forEach(link => {
        const linkType = link.getAttribute('data-deadline') || '';
        link.classList.toggle('active', linkType === newType);
    });

    closeEmailThread();
    reloadEmailList(true);
}

function filterDeadlineType(type, element) {
    activeAdvancedFilters = activeAdvancedFilters || {};
    if (type) {
        activeAdvancedFilters.deadline_type = type;
    } else {
        delete activeAdvancedFilters.deadline_type;
    }

    // Synchronize popup input
    const advSel = document.getElementById('advFilterDeadlineType');
    if (advSel) advSel.value = type;

    // Synchronize toolbar pills
    document.querySelectorAll('.deadline-pill-btn').forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-deadline') === (type || ''));
    });

    // Synchronize sidebar links
    if (element) {
        document.querySelectorAll('#deadlineTypeSidebarList .duralux-nav-link').forEach(link => link.classList.remove('active'));
        element.classList.add('active');
    }

    closeEmailThread();
    reloadEmailList(true);
}

// Click outside to close dropdown and advanced popup
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('gmailSearchWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        closeSearchDropdown();
        closeAdvancedFilterPopup();
    }
});

function appendSearchFilterParams(url, searchVal) {
    if (searchVal) url.searchParams.set('search', searchVal);
    if (activeFilterChips.has_attachment) url.searchParams.set('has_attachment', '1');
    if (activeFilterChips.last_7_days) url.searchParams.set('date_range', 'last_7_days');
    if (activeFilterChips.from_me) url.searchParams.set('from_me', '1');
    if (activeFilterChips.unread) url.searchParams.set('is_read', 'unread');

    if (activeAdvancedFilters) {
        if (activeAdvancedFilters.filter_from) url.searchParams.set('filter_from', activeAdvancedFilters.filter_from);
        if (activeAdvancedFilters.filter_to) url.searchParams.set('filter_to', activeAdvancedFilters.filter_to);
        if (activeAdvancedFilters.filter_subject) url.searchParams.set('filter_subject', activeAdvancedFilters.filter_subject);
        if (activeAdvancedFilters.filter_words) url.searchParams.set('filter_words', activeAdvancedFilters.filter_words);
        if (activeAdvancedFilters.filter_doesnt_have) url.searchParams.set('filter_doesnt_have', activeAdvancedFilters.filter_doesnt_have);
        if (activeAdvancedFilters.filter_date_within) url.searchParams.set('filter_date_within', activeAdvancedFilters.filter_date_within);
        if (activeAdvancedFilters.filter_date_ref) url.searchParams.set('filter_date_ref', activeAdvancedFilters.filter_date_ref);
        if (activeAdvancedFilters.filter_order_code) url.searchParams.set('order_code', activeAdvancedFilters.filter_order_code);
        if (activeAdvancedFilters.deadline_type) url.searchParams.set('deadline_type', activeAdvancedFilters.deadline_type);
    }
}

let currentPage = 1;
let hasMorePages = {{ ($emails->hasMorePages() ?? false) ? 'true' : 'false' }};
let isLoadingMore = false;

document.addEventListener('DOMContentLoaded', function() {
    initInfiniteScroll();

    // Check for initial URL query parameters for deadline_type or order_code
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('deadline_type') || urlParams.has('order_code')) {
        activeAdvancedFilters = activeAdvancedFilters || {};
        if (urlParams.get('deadline_type')) {
            const dt = urlParams.get('deadline_type');
            activeAdvancedFilters.deadline_type = dt;
            document.querySelectorAll('.deadline-pill-btn').forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-deadline') === dt);
            });
            document.querySelectorAll('#deadlineTypeSidebarList .duralux-nav-link').forEach(link => {
                link.classList.toggle('active', (link.getAttribute('data-deadline') || '') === dt);
            });
        }
        if (urlParams.get('order_code')) activeAdvancedFilters.filter_order_code = urlParams.get('order_code');
    }

    const searchInput = document.getElementById('emailSearchInput');
    const initSearchVal = searchInput ? searchInput.value.trim() : '';
    if (initSearchVal) {
        const clearBtn = document.getElementById('clearSearchBtn');
        if (clearBtn) clearBtn.style.display = 'inline-block';
        highlightSearchTermsInList(initSearchVal);
    }
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

    const searchInput = document.getElementById('emailSearchInput');
    const searchVal = searchInput ? searchInput.value.trim() : '';
    const nextPage = currentPage + 1;
    const url = new URL('{{ route("emails.index") }}', window.location.origin);
    url.searchParams.set('folder', searchVal ? 'all' : currentFolder);
    if (currentAccountId) url.searchParams.set('account_id', currentAccountId);
    if (currentLabelId) url.searchParams.set('label_id', currentLabelId);
    appendSearchFilterParams(url, searchVal);
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
                if (searchVal) {
                    highlightSearchTermsInList(searchVal);
                }
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
    
    const rowCount = listContainer.querySelectorAll('.duralux-email-row').length;
    const paginationEl = document.getElementById('emailPaginationInfo');
    if (paginationEl) {
        paginationEl.textContent = `Showing ${rowCount} conversations`;
    }
    initInfiniteScroll();

    const searchInput = document.getElementById('emailSearchInput');
    const searchVal = searchInput ? searchInput.value.trim() : '';
    if (searchVal) {
        highlightSearchTermsInList(searchVal);
    }
}

function updateEmailBrowserUrl(searchVal) {
    const browserUrl = new URL(window.location.href);
    browserUrl.searchParams.set('folder', currentFolder);
    if (currentAccountId) browserUrl.searchParams.set('account_id', currentAccountId);
    else browserUrl.searchParams.delete('account_id');
    if (currentLabelId) browserUrl.searchParams.set('label_id', currentLabelId);
    else browserUrl.searchParams.delete('label_id');
    if (searchVal) {
        let finalSearch = searchVal;
        if (!window.canViewFullPhone && finalSearch.includes('@') && !finalSearch.includes('*') && window.maskEmailForDisplay) {
            finalSearch = window.maskEmailForDisplay(finalSearch);
        }
        browserUrl.searchParams.set('search', finalSearch);
    } else {
        browserUrl.searchParams.delete('search');
    }
    history.replaceState(history.state, '', browserUrl);
}

function reloadEmailList(showLoader = true) {
    if (!showLoader && emailListRequestPromise) return emailListRequestPromise;
    if (emailListRequestController) emailListRequestController.abort();

    const requestController = new AbortController();
    emailListRequestController = requestController;
    const listContainer = document.getElementById('emailListContainer');
    const progressBar = document.getElementById('emailListProgressBar');
    const refreshIcon = document.getElementById('mainRefreshIcon');

    if (showLoader) {
        if (progressBar) progressBar.style.display = 'block';
        if (refreshIcon) refreshIcon.classList.add('fa-spin');
        if (listContainer) {
            listContainer.classList.add('is-filter-loading');
            listContainer.innerHTML = `
                <div class="gmail-list-preloader">
                    <div class="gmail-spinner"></div>
                    <div class="gmail-loading-text">Loading messages...</div>
                </div>`;
        }
    }

    currentPage = 1;
    hasMorePages = true;
    isLoadingMore = false;

    const searchInput = document.getElementById('emailSearchInput');
    const searchVal = searchInput ? searchInput.value.trim() : '';
    const url = new URL('{{ route("emails.index", [], false) }}', window.location.origin);
    url.searchParams.set('folder', searchVal ? 'all' : currentFolder);
    url.searchParams.set('partial', '1');
    if (currentAccountId) url.searchParams.set('account_id', currentAccountId);
    if (currentLabelId) url.searchParams.set('label_id', currentLabelId);
    appendSearchFilterParams(url, searchVal);

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
        if (!searchVal && !currentLabelId) emailFolderHtmlCache[currentFolder] = html;
        updateEmailBrowserUrl(searchVal);
    })
    .catch(error => {
        if (error.name !== 'AbortError') console.error('Email list refresh failed', error);
    })
    .finally(() => {
        if (emailListRequestController === requestController) {
            if (listContainer) listContainer.classList.remove('is-filter-loading');
            if (progressBar) progressBar.style.display = 'none';
            if (refreshIcon) refreshIcon.classList.remove('fa-spin');
            emailListRequestController = null;
            emailListRequestPromise = null;
        }
    });

    return emailListRequestPromise;
}

// Background sync: Only fetches new incoming messages without blocking the UI
let isSyncing = false;
let lastHiddenSyncTime = 0;

function autoSyncLiveInbox(force = false) {
    if (isSyncing || !currentAccountId) return;

    // If tab is in background, throttle to every 25s instead of stopping completely
    if (document.hidden && !force) {
        const now = Date.now();
        if (now - lastHiddenSyncTime < 25000) return;
        lastHiddenSyncTime = now;
    }

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
    .then(data => {
        if (data && data.synced_count > 0) {
            checkEmailUpdates();
            reloadEmailList(false);
        }
    })
    .catch(() => {})
    .finally(() => {
        isSyncing = false;
    });
}

setTimeout(() => autoSyncLiveInbox(true), 1500);

if (window.Echo && currentAccountId) {
    window.Echo.private(`emails.account.${currentAccountId}`)
        .listen('.email.received', () => {
            autoSyncLiveInbox(true);
            reloadEmailList(false);
        });
}

setInterval(() => autoSyncLiveInbox(false), 8000);

// Instantly sync when user switches back to this tab
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        autoSyncLiveInbox(true);
        checkEmailUpdates();
    }
});

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

    // Sender name / address
    const sender = email.from_name || email.from_email || 'New Message';
    const senderEl = document.getElementById('incomingAlertSender');
    if (senderEl) senderEl.textContent = sender;

    // Subject
    const subjectEl = document.getElementById('incomingAlertSubject');
    if (subjectEl) subjectEl.textContent = email.subject || '(No Subject)';

    // Clean preview snippet (strip raw markdown asterisks, hashes, backticks, repetitive spaces)
    const previewEl = document.getElementById('incomingAlertPreview');
    if (previewEl) {
        if (email.preview) {
            let cleanPreview = String(email.preview)
                .replace(/[*_#`~]/g, '')
                .replace(/\s+/g, ' ')
                .trim();
            previewEl.textContent = cleanPreview;
            previewEl.style.display = '-webkit-box';
        } else {
            previewEl.textContent = '';
            previewEl.style.display = 'none';
        }
    }

    // Relative / creation time
    const timeEl = document.getElementById('incomingAlertTime');
    if (timeEl) timeEl.textContent = email.created_at || 'Just now';
    
    // Aesthetic Avatar by initial character
    const avatar = document.getElementById('incomingAlertAvatar');
    if (avatar) {
        const char = (email.from_name || email.from_email || 'U').trim().charAt(0).toUpperCase();
        avatar.textContent = char;
        const gradients = [
            'linear-gradient(135deg, #3b82f6, #1d4ed8)',
            'linear-gradient(135deg, #8b5cf6, #6d28d9)',
            'linear-gradient(135deg, #ec4899, #be185d)',
            'linear-gradient(135deg, #06b6d4, #0e7490)',
            'linear-gradient(135deg, #10b981, #047857)',
            'linear-gradient(135deg, #f59e0b, #d97706)'
        ];
        const colorIdx = (char.charCodeAt(0) || 0) % gradients.length;
        avatar.style.background = gradients[colorIdx];
    }

    // Reset countdown progress bar animation
    const progressBar = document.getElementById('incomingAlertProgress');
    if (progressBar) {
        progressBar.style.transition = 'none';
        progressBar.style.width = '100%';
        setTimeout(() => {
            progressBar.style.transition = 'width 8s linear';
            progressBar.style.width = '0%';
        }, 50);
    }

    // Trigger smooth slide & fade animation
    alertBox.style.display = 'block';
    setTimeout(() => {
        alertBox.classList.add('show');
    }, 20);

    if (incomingAlertTimeout) clearTimeout(incomingAlertTimeout);
    incomingAlertTimeout = setTimeout(closeIncomingAlert, 8000);
}

function closeIncomingAlert() {
    const alertBox = document.getElementById('incomingEmailAlert');
    if (alertBox) {
        alertBox.classList.remove('show');
        setTimeout(() => {
            if (!alertBox.classList.contains('show')) {
                alertBox.style.display = 'none';
            }
        }, 300);
    }
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

function openEmailThread(id, pushToHistory = true) {
    if (!id) return;

    // Cancel an older detail request when the user quickly selects another email.
    if (emailDetailRequestController) emailDetailRequestController.abort();
    const requestController = new AbortController();
    emailDetailRequestController = requestController;
    const requestTimeout = setTimeout(() => requestController.abort(), 15000);

    activeThreadId = id;
    const detailPane = document.getElementById('emailDetailPane');
    if (!detailPane) return;

    const loadingWhatsAppBtn = document.getElementById('detailWhatsAppBtn');
    if (loadingWhatsAppBtn) {
        loadingWhatsAppBtn.removeAttribute('href');
        loadingWhatsAppBtn.style.display = 'none';
    }

    // 1. Instantly show detail view pane (<1ms)
    detailPane.classList.add('active');

    // 2. Mark row read visually & remove unread dot immediately
    const row = document.getElementById(`email-row-` + id);
    if (row) {
        row.classList.remove('unread');
        row.classList.add('is-read');
        row.querySelector('.duralux-unread-dot')?.remove();
        const rowSubject = row.querySelector('.duralux-email-subject');
        if (rowSubject) {
            document.getElementById('detailSubject').textContent = rowSubject.textContent;
        }
    }

    // 3. Show instant clean skeleton placeholder (Gmail style)
    const convList = document.getElementById('detailConversationList');
    convList.innerHTML = `
        <div class="gmail-thread-message" style="opacity: 0.75;">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="gmail-msg-avatar" style="background: #e8eaed; color: #5f6368;"><i class="fa fa-envelope"></i></div>
                <div>
                    <div class="fw-bold text-gray-800 fs-6">Loading conversation...</div>
                    <div class="text-muted fs-8">Fetching latest messages</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 py-4 text-muted fs-7" style="margin-left: 54px;">
                <i class="fa fa-circle-o-notch fa-spin text-primary"></i> Loading content...
            </div>
        </div>
    `;

    // Reset inline composer & show bottom pills
    const inlineComp = document.getElementById('inlineComposerContainer');
    if (inlineComp) inlineComp.style.display = 'none';
    const bottomPills = document.getElementById('detailBottomActionPills');
    if (bottomPills) bottomPills.style.display = 'flex';

    // 4. Update URL cleanly with history push so browser Back button works naturally
    if (pushToHistory) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('email_id', id);
        history.pushState({ emailId: id }, '', currentUrl.pathname + currentUrl.search);
    }

    // 5. Fetch JSON and render in ~20ms
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
            if (data.email.is_draft || data.email.folder === 'drafts') {
                closeEmailThread();
                openDraft(data.email.id);
                return;
            }
            activeEmailData = data.email;
            activeThreadMessages = (data.messages && data.messages.length > 0) ? data.messages : [data.email];
            activeReplyTargetMsg = activeEmailData;
            document.getElementById('detailSubject').textContent = data.email.subject || '(No Subject)';

            const detailWhatsAppBtn = document.getElementById('detailWhatsAppBtn');
            if (detailWhatsAppBtn) {
                if (data.email && data.email.whatsapp_url) {
                    detailWhatsAppBtn.href = data.email.whatsapp_url;
                    detailWhatsAppBtn.title = data.email.whatsapp_phone
                        ? `WhatsApp: ${data.email.client_name || data.email.customer_email || 'Client'} (${data.email.whatsapp_phone})`
                        : `WhatsApp: ${data.email.client_name || data.email.customer_email || 'Open Chat'}`;
                    detailWhatsAppBtn.style.setProperty('display', 'inline-flex', 'important');
                } else {
                    detailWhatsAppBtn.removeAttribute('href');
                    detailWhatsAppBtn.style.setProperty('display', 'none', 'important');
                }
            }

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
            const decodeMimeHeader = (str) => {
                if (!str || typeof str !== 'string') return '';
                try {
                    return str.replace(/=\?([^?]+)\?([bBqQ])\?([^?]+)\?=/g, (match, charset, enc, text) => {
                        try {
                            if (enc.toUpperCase() === 'B') {
                                return decodeURIComponent(escape(atob(text)));
                            } else if (enc.toUpperCase() === 'Q') {
                                return decodeURIComponent(escape(text.replace(/=/g, '%').replace(/_/g, ' ')));
                            }
                        } catch (e) {
                            try { return atob(text); } catch (e2) { return text; }
                        }
                        return match;
                    });
                } catch (e) {
                    return str;
                }
            };
            const avatarColors = ['#0b57d0', '#c5221f', '#137333', '#b06000', '#9334e6', '#129eaf'];

            const getAttachmentIconSvg = (mime, filename) => {
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
            };

            // If multiple messages in thread, determine which one to expand:
            // "jis me krenge click vo hi khule": expand the clicked id, or fallback to the latest message!
            let targetExpandId = id;
            const hasTarget = messages.some(msgItem => msgItem.id == id);
            if (!hasTarget) {
                targetExpandId = messages[messages.length - 1].id;
            }

            convList.innerHTML = messages.map((m, idx) => {
                const isCollapsed = (messages.length > 1) && (m.id != targetExpandId);
                const colorIdx = Math.abs((m.from_email || 'u').split('').reduce((acc, char) => acc + char.charCodeAt(0), 0)) % avatarColors.length;
                const avatarBg = m.direction === 'outbound' ? 'background-color: #0b57d0;' : `background-color: ${avatarColors[colorIdx]};`;
                const avatarLetter = (m.from_name || m.from_email || 'U').charAt(0).toUpperCase();
                const isSuperAdmin = !!window.canViewFullPhone;
                const rawFromEmail = m.raw_from_email || m.from_email || '';
                const rawToEmail = m.raw_to_email || m.to_email || '';
                const displayFromEmail = (!isSuperAdmin && window.maskEmailForDisplay) ? window.maskEmailForDisplay(rawFromEmail) : (m.from_email || rawFromEmail);
                const displayToEmail = (!isSuperAdmin && window.maskEmailForDisplay) ? window.maskEmailForDisplay(rawToEmail) : (m.to_email || rawToEmail);

                let rawFromName = m.from_name || displayFromEmail;
                if (!isSuperAdmin && rawFromName && rawFromName.includes('@') && window.maskEmailForDisplay) {
                    rawFromName = window.maskEmailForDisplay(rawFromName);
                }
                let rawToName = m.to_name || displayToEmail || 'me';
                if (!isSuperAdmin && rawToName && rawToName.includes('@') && window.maskEmailForDisplay) {
                    rawToName = window.maskEmailForDisplay(rawToName);
                }

                const fromName = escapeEmailText(rawFromName);
                const fromEmail = escapeEmailText(displayFromEmail);
                const toName = escapeEmailText(rawToName);
                const toEmail = escapeEmailText(displayToEmail);
                const copyFromEmail = isSuperAdmin ? rawFromEmail : displayFromEmail;
                const copyToEmail = isSuperAdmin ? rawToEmail : (displayToEmail || (rawToName && rawToName.includes('@') ? rawToName : ''));
                const dateStr = escapeEmailText(m.date_formatted || m.received_at || 'Just now');
                const subject = escapeEmailText(m.subject || data.email.subject || '(No Subject)');
                let cleanSnippet = m.snippet;
                if (!cleanSnippet) {
                    const rawPlain = (m.body_plain || '').replace(/(On\s+[\s\S]*?wrote:[\s\S]*|-----Original Message-----[\s\S]*)/gi, '').trim();
                    cleanSnippet = (rawPlain || (m.body_plain ? m.body_plain : (m.body_html ? m.body_html.replace(/<[^>]*>?/gm, ' ') : ''))).replace(/\s+/g, ' ').trim().slice(0, 140);
                }
                const snippet = cleanSnippet;

                const waUrl = m.whatsapp_url || data.email.whatsapp_url || null;
                const waPhone = m.whatsapp_phone || data.email.whatsapp_phone || '';
                const waTitle = waPhone ? `WhatsApp: ${data.email.client_name || data.email.customer_email || 'Client'} (${waPhone})` : 'WhatsApp: Open Chat';

                let attachmentsHtml = '';
                if (m.attachments && m.attachments.length > 0) {
                    attachmentsHtml = `
                        <div class="gmail-attachments-section">
                            <div class="gmail-att-heading">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5c0-1.38 1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z"/></svg>
                                <span>${m.attachments.length} Attachment${m.attachments.length > 1 ? 's' : ''}</span>
                            </div>
                            <div class="gmail-att-grid">
                                ${m.attachments.map(att => {
                                    const cleanName = escapeEmailText(decodeMimeHeader(att.filename || 'Attachment'));
                                    const ext = (att.filename || '').split('.').pop().toLowerCase();
                                    const isImg = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext) || (att.mime_type && att.mime_type.startsWith('image/'));
                                    const isPdf = ext === 'pdf' || (att.mime_type && att.mime_type.includes('pdf'));
                                    const viewUrl = att.view_url || att.url || '#';
                                    const dlUrl = att.url || '#';
                                    const previewHtmlUrl = att.preview_html_url || (viewUrl ? viewUrl.replace(/\/view$/, '/preview-html') : '');
                                    const sizeStr = escapeEmailText(att.file_size || '');
                                    const safeNameArg = cleanName.replace(/'/g, "\\'");

                                    return `
                                        <div class="gmail-att-card" onclick="openAttachmentPreview(${att.id}, '${safeNameArg}', '${sizeStr}', '${att.mime_type || ''}', '${viewUrl}', '${dlUrl}', '${previewHtmlUrl}')">
                                            <div class="gmail-att-card-preview">
                                                ${isImg ? `
                                                    <img src="${viewUrl}" alt="" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';" />
                                                    <div class="gmail-att-fallback-icon" style="display:none;width:100%;height:100%;align-items:center;justify-content:center;">
                                                        ${getAttachmentIconSvg(att.mime_type, att.filename)}
                                                    </div>
                                                ` : getAttachmentIconSvg(att.mime_type, att.filename)}
                                                <div class="gmail-att-card-overlay">
                                                    <button type="button" class="gmail-att-action-btn" title="Preview" onclick="event.stopPropagation(); openAttachmentPreview(${att.id}, '${safeNameArg}', '${sizeStr}', '${att.mime_type || ''}', '${viewUrl}', '${dlUrl}', '${previewHtmlUrl}')">
                                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                                    </button>
                                                    <a href="${dlUrl}" target="_blank" download class="gmail-att-action-btn" title="Download" onclick="event.stopPropagation();">
                                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="gmail-att-card-footer">
                                                <div class="d-flex flex-column" style="max-width: 130px;">
                                                    <span class="gmail-att-name" title="${cleanName}">${cleanName}</span>
                                                    <span class="gmail-att-size">${sizeStr}</span>
                                                </div>
                                                <a href="${dlUrl}" target="_blank" download class="gmail-att-dl-btn" title="Download" onclick="event.stopPropagation();">
                                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                                                </a>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    `;
                }

                return `
                    <div class="gmail-thread-message ${isCollapsed ? 'collapsed' : ''}" id="thread-msg-${m.id}">
                        <!-- 1. Collapsed Strip (Matches Gmail Screenshot) -->
                        <div class="gmail-msg-collapsed-strip" onclick="toggleThreadMessage(${m.id})">
                            <div class="gmail-collapsed-avatar" style="${avatarBg}">
                                ${avatarLetter}
                            </div>
                            <div class="gmail-collapsed-sender" title="${fromName}">
                                ${fromName}
                            </div>
                            <div class="gmail-collapsed-snippet" title="${escapeEmailText(snippet)}">
                                ${escapeEmailText(snippet)}
                            </div>
                            <div class="gmail-collapsed-meta">
                                ${waUrl ? `
                                <a href="${waUrl}" 
                                   target="_blank" 
                                   class="text-success me-1 d-inline-flex align-items-center justify-content-center" 
                                   style="text-decoration: none; color: #25D366 !important; display: inline-flex !important;" 
                                   title="${escapeEmailText(waTitle)}" 
                                   onclick="event.stopPropagation();">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="#25D366"><path fill="#25D366" d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                                </a>` : ''}
                                <span class="gmail-collapsed-date">${dateStr}</span>
                                <button type="button" class="gmail-icon-btn ${m.is_starred ? 'text-warning' : ''}" onclick="event.stopPropagation(); toggleStar(${m.id}, this)" title="Star">
                                    <i class="fa ${m.is_starred ? 'fa-star' : 'fa-star-o'}"></i>
                                </button>
                            </div>
                        </div>

                        <!-- 2. Expanded Message Content -->
                        <div class="gmail-msg-expanded-content">
                            <div class="gmail-msg-header" onclick="toggleThreadMessage(${m.id})">
                                <div class="gmail-msg-sender-group">
                                    <div class="gmail-msg-avatar" style="${avatarBg}">
                                        ${avatarLetter}
                                    </div>
                                    <div class="gmail-msg-sender-details">
                                        <div class="gmail-msg-from-line">
                                            <span class="gmail-msg-from-name">${fromName}</span>
                                            <span class="gmail-msg-from-email">&lt;${fromEmail}&gt;</span>
                                            <button type="button" 
                                                    class="btn btn-icon btn-sm p-0 flex-shrink-0 ms-1" 
                                                    style="width: 20px; height: 20px; min-width: 20px; border: none; background: transparent; color: #5f6368;" 
                                                    title="Copy Email: ${fromEmail}" 
                                                    onclick="event.stopPropagation(); crmCopyToClipboard('${copyFromEmail}', 'Email copied!');">
                                                <i class="fa fa-clone" style="font-size: 11px;"></i>
                                            </button>
                                            ${waUrl ? `
                                            <a href="${waUrl}" 
                                               target="_blank" 
                                               class="btn btn-icon btn-sm p-0 flex-shrink-0 ms-1 gmail-wa-btn" 
                                               style="width: 22px; height: 22px; min-width: 22px;" 
                                               title="${escapeEmailText(waTitle)}" 
                                               onclick="event.stopPropagation();">
                                                <svg width="13" height="13" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                                            </a>` : ''}
                                        </div>
                                        <div class="d-inline-flex align-items-center" onclick="event.stopPropagation();">
                                            <div class="dropdown">
                                                <button type="button" class="gmail-to-me-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span>to ${toName}</span>
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
                                                </button>
                                                <div class="dropdown-menu gmail-details-card shadow-lg">
                                                    <div class="gmail-details-grid">
                                                        <span class="text-muted">from:</span>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <span><b>${fromName}</b> &lt;${fromEmail}&gt;</span>
                                                            <button type="button" 
                                                                    class="btn btn-icon btn-sm p-0 flex-shrink-0 ms-1" 
                                                                    style="width: 18px; height: 18px; min-width: 18px; border: none; background: transparent; color: #5f6368;" 
                                                                    title="Copy Email: ${fromEmail}" 
                                                                    onclick="crmCopyToClipboard('${copyFromEmail}', 'Email copied!');">
                                                                <i class="fa fa-clone" style="font-size: 11px;"></i>
                                                            </button>
                                                        </div>
                                                        <span class="text-muted">to:</span>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <span>${toEmail || toName}</span>
                                                            ${(copyToEmail || toEmail || (toName && toName.includes('@'))) ? `
                                                            <button type="button" 
                                                                    class="btn btn-icon btn-sm p-0 flex-shrink-0 ms-1" 
                                                                    style="width: 18px; height: 18px; min-width: 18px; border: none; background: transparent; color: #5f6368;" 
                                                                    title="Copy Email: ${toEmail || toName}" 
                                                                    onclick="crmCopyToClipboard('${copyToEmail || toEmail || toName}', 'Email copied!');">
                                                                <i class="fa fa-clone" style="font-size: 11px;"></i>
                                                            </button>` : ''}
                                                        </div>
                                                        <span class="text-muted">date:</span>
                                                        <div>${dateStr}</div>
                                                        <span class="text-muted">subject:</span>
                                                        <div>${subject}</div>
                                                        <span class="text-muted">security:</span>
                                                        <div class="text-success"><svg width="13" height="13" viewBox="0 0 24 24" fill="#137333" class="me-1"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg> Standard encryption (TLS)</div>
                                                    </div>
                                                </div>
                                            </div>
                                            ${(copyToEmail || toEmail || (toName && toName.includes('@'))) ? `
                                            <button type="button" 
                                                    class="btn btn-icon btn-sm p-0 flex-shrink-0 ms-1" 
                                                    style="width: 20px; height: 20px; min-width: 20px; border: none; background: transparent; color: #5f6368;" 
                                                    title="Copy Email: ${toEmail || toName}" 
                                                    onclick="event.stopPropagation(); crmCopyToClipboard('${copyToEmail || toEmail || toName}', 'Email copied!');">
                                                <i class="fa fa-clone" style="font-size: 11px;"></i>
                                            </button>` : ''}
                                        </div>
                                    </div>
                                </div>
                                <div class="gmail-msg-right-meta">
                                    <span class="gmail-msg-date-str">${dateStr}</span>
                                    <button type="button" class="gmail-icon-btn ${data.email.is_starred ? 'text-warning' : ''}" onclick="event.stopPropagation(); toggleStar(${m.id}, this)" title="Star">
                                        <i class="fa ${data.email.is_starred ? 'fa-star' : 'fa-star-o'}"></i>
                                    </button>
                                    <button type="button" class="gmail-icon-btn" onclick="event.stopPropagation(); openInlineComposer('reply', '${(rawFromEmail || fromEmail).replace(/'/g, "\\'")}', '${subject.replace(/'/g, "\\'")}', ${m.id})" title="Reply">
                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"/></svg>
                                    </button>
                                    <div class="dropdown d-inline-block" onclick="event.stopPropagation();">
                                        <button type="button" class="gmail-icon-btn" data-bs-toggle="dropdown" title="More options">
                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm fs-8">
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openInlineComposer('reply', '${(rawFromEmail || fromEmail).replace(/'/g, "\\'")}', '${subject.replace(/'/g, "\\'")}', ${m.id})"><i class="fa fa-reply me-2 text-muted"></i> Reply</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openInlineComposer('forward', '', '${subject.replace(/'/g, "\\'")}', ${m.id})"><i class="fa fa-share me-2 text-muted"></i> Forward</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="window.print()"><i class="fa fa-print me-2 text-muted"></i> Print</a></li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteEmail(${m.id})"><i class="fa fa-trash-o me-2"></i> Delete this message</a></li>
                                        </ul>
                                    </div>
                                    <button type="button" class="gmail-icon-btn btn-collapse-msg" onclick="event.stopPropagation(); toggleThreadMessage(${m.id})" title="Collapse / Close message">
                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="gmail-msg-body-wrapper" id="email-msg-body-${m.id}"></div>

                            ${attachmentsHtml}
                        </div>
                    </div>
                `;
            }).join('');

            // Render each email body inside an isolated iframe to completely eliminate CSS and font conflicts
            messages.forEach(m => {
                const container = document.getElementById(`email-msg-body-${m.id}`);
                if (container) {
                    renderIsolatedEmailBody(container, m.body_html, m.body_plain);
                }
            });

            // Highlight matching search keywords in thread view
            const currentSearchInput = document.getElementById('emailSearchInput');
            const threadSearchVal = currentSearchInput ? currentSearchInput.value.trim() : '';
            if (threadSearchVal) {
                const detailSubjectEl = document.getElementById('detailSubject');
                if (detailSubjectEl) {
                    highlightSearchTermsInElement(detailSubjectEl, threadSearchVal);
                }
                const convListEl = document.getElementById('detailConversationList');
                if (convListEl) {
                    convListEl.querySelectorAll('.gmail-collapsed-snippet, .gmail-collapsed-sender, .gmail-msg-from-name, .gmail-msg-from-email, .gmail-att-name').forEach(el => {
                        highlightSearchTermsInElement(el, threadSearchVal);
                    });
                }
            }

            // Pre-populate inline composer in Reply mode
            setInlineComposerMode('reply', data.email.from_email, data.email.subject);

            // Rebuild threadLabelsChecklist if account-specific all_labels provided
            if (data.all_labels && Array.isArray(data.all_labels)) {
                const list = document.getElementById('threadLabelsChecklist');
                if (list) {
                    list.innerHTML = data.all_labels.map(lbl => `
                        <label class="form-check form-check-custom form-check-solid d-flex align-items-center gap-2 p-1.5 rounded hover-bg-light cursor-pointer mb-0">
                            <input class="form-check-input label-assign-checkbox" type="checkbox" value="${lbl.id}" id="label-chk-${lbl.id}" data-name="${(lbl.name || '').replace(/"/g, '&quot;')}" data-color="${lbl.color}" onchange="toggleActiveThreadLabel(${lbl.id}, this.checked)">
                            <span class="badge px-2 py-1 fs-8 fw-bold" style="background-color: ${lbl.color}; color: #ffffff;">${(lbl.name || '').replace(/[&<>"']/g, '')}</span>
                        </label>
                    `).join('');
                }
            }

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

let inlineUploadedFiles = [];
let inlineForwardedAttachments = [];
let inlineQuotedHtml = '';

function formatFileSize(bytes) {
    if (!bytes || isNaN(bytes)) return '0 B';
    bytes = parseInt(bytes);
    if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
    if (bytes >= 1024) return (bytes / 1024).toFixed(0) + ' KB';
    return bytes + ' B';
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
                <img src="${viewUrl}" alt="${filename}" style="max-width: 95%; max-height: 75vh; object-fit: contain; border-radius: 8px; box-shadow: 0 8px 30px rgba(0,0,0,0.5);" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';" />
                <div style="display:none; flex-direction:column; align-items:center; justify-content:center; color:#9aa0a6; padding: 40px;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="#9aa0a6" class="mb-3"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                    <div style="font-size:15px; font-weight:600; color:#fff; margin-bottom:4px;">Image Preview Unavailable</div>
                    <div style="font-size:12px; color:#bdc1c6;">The image file could not be loaded from server.</div>
                </div>
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
                <div id="docxLoadingSpinner" class="text-center p-8 d-flex flex-column align-items-center justify-content-center flex-grow-1" style="color: #ffffff; min-height: 400px;">
                    <i class="fa fa-circle-o-notch fa-spin fa-2x text-primary mb-3"></i>
                    <h5 class="fw-bold text-white mb-1">Rendering Document Preview...</h5>
                    <p class="text-white-50 fs-8 mb-0">Formatting Word pages and content</p>
                </div>
                <div id="docxScrollArea" style="display: none; width: 100%; height: 75vh; overflow-y: auto; padding: 24px 16px;">
                    <div id="docxContentBox" style="background: #ffffff; color: #202124; max-width: 850px; margin: 0 auto; box-shadow: 0 4px 20px rgba(0,0,0,0.35); border-radius: 4px; min-height: 500px; padding: 40px 48px; font-family: 'Segoe UI', Arial, sans-serif; font-size: 14px; line-height: 1.65;"></div>
                </div>
            </div>
        `;

        const spinner = document.getElementById('docxLoadingSpinner');
        const scrollArea = document.getElementById('docxScrollArea');
        const contentBox = document.getElementById('docxContentBox');

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

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
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

function renderInlineAttachmentChips() {
    const container = document.getElementById('inlineComposerAttachmentChips');
    const badge = document.getElementById('inlineFileCountBadge');
    if (!container) return;

    const totalCount = inlineForwardedAttachments.length + inlineUploadedFiles.length;
    if (badge) {
        badge.textContent = totalCount > 0 ? `${totalCount} attachment${totalCount > 1 ? 's' : ''}` : '';
    }

    if (totalCount === 0) {
        container.style.display = 'none';
        container.innerHTML = '';
        return;
    }

    let html = '';
    // Forwarded attachments from original email
    inlineForwardedAttachments.forEach(att => {
        const cleanName = att.filename || 'Attachment';
        html += `
            <span class="gmail-chip forwarded" title="${cleanName}">
                <i class="fa fa-share text-primary me-1"></i>
                <span class="gmail-chip-name">${cleanName}</span>
                <span class="gmail-chip-size">(${att.file_size || ''})</span>
                <span class="badge bg-primary text-white fs-9 ms-1 py-0 px-1" style="font-size: 9px;">Forwarded</span>
                <span class="gmail-chip-remove ms-1" onclick="removeInlineForwardedAttachment(${att.id})" title="Remove attachment">&times;</span>
            </span>
        `;
    });

    // Freshly attached files
    inlineUploadedFiles.forEach((file, index) => {
        html += `
            <span class="gmail-chip" title="${file.name}">
                <i class="fa fa-paperclip text-muted me-1"></i>
                <span class="gmail-chip-name">${file.name}</span>
                <span class="gmail-chip-size">(${formatFileSize(file.size)})</span>
                <span class="gmail-chip-remove ms-1" onclick="removeInlineUploadedFile(${index})" title="Remove attachment">&times;</span>
            </span>
        `;
    });

    container.innerHTML = html;
    container.style.display = 'flex';
}

function handleInlineFileSelected(input) {
    if (input.files && input.files.length > 0) {
        Array.from(input.files).forEach(f => {
            if (!inlineUploadedFiles.some(existing => existing.name === f.name && existing.size === f.size)) {
                inlineUploadedFiles.push(f);
            }
        });
    }
    input.value = '';
    renderInlineAttachmentChips();
}

function removeInlineUploadedFile(index) {
    inlineUploadedFiles.splice(index, 1);
    renderInlineAttachmentChips();
}

function removeInlineForwardedAttachment(attId) {
    inlineForwardedAttachments = inlineForwardedAttachments.filter(a => a.id != attId);
    renderInlineAttachmentChips();
}

function toggleInlineQuotedBlock() {
    const content = document.getElementById('inlineQuotePreviewContent');
    if (!content) return;
    content.style.display = (content.style.display === 'none') ? 'block' : 'none';
}

function setInlineComposerMode(mode, toEmail = null, subject = null, messageId = null) {
    inlineComposerMode = mode;
    const box = document.getElementById('inlineComposerContainer');
    const toInput = document.getElementById('inlineComposerToInput');
    const subjInput = document.getElementById('inlineComposerSubjectInput');
    const modeLabel = document.getElementById('inlineComposerModeLabel');
    const replyTab = document.getElementById('inlineReplyTabBtn');
    const forwardTab = document.getElementById('inlineForwardTabBtn');
    const quoteBar = document.getElementById('inlineQuoteCollapsibleBar');
    const quoteSummary = document.getElementById('inlineQuoteSummary');
    const quotePreview = document.getElementById('inlineQuotePreviewContent');

    if (!box) return;
    box.classList.add('highlight-focus');

    // Reset attachments
    inlineUploadedFiles = [];
    inlineForwardedAttachments = [];

    let targetMsg = null;
    if (messageId && activeThreadMessages && activeThreadMessages.length) {
        targetMsg = activeThreadMessages.find(m => m.id == messageId);
    }
    if (!targetMsg) {
        targetMsg = activeEmailData;
    }
    activeReplyTargetMsg = targetMsg;

    const targetDateStr = targetMsg && (targetMsg.date_formatted || targetMsg.received_at || targetMsg.created_at)
        ? (targetMsg.date_formatted || targetMsg.received_at || new Date(targetMsg.created_at).toLocaleString()) 
        : 'Recently';
    const targetSenderEmail = targetMsg ? (targetMsg.from_email || '') : '';
    const targetSenderName = targetMsg ? (targetMsg.from_name || targetSenderEmail) : '';
    const senderDisplay = targetSenderName && targetSenderName !== targetSenderEmail 
        ? `${targetSenderName} &lt;${targetSenderEmail}&gt;` 
        : targetSenderEmail;

    let targetBody = targetMsg ? (targetMsg.body_html || (targetMsg.body_plain ? targetMsg.body_plain.replace(/\n/g, '<br>') : '')) : '';
    targetBody = targetBody.replace(/^\s*\*\s*\d+\s+FETCH\s*\([^\r\n]*\r?\n?/i, '')
                           .replace(/\r?\n\)\s*$/, '')
                           .replace(/=3D/g, '=')
                           .replace(/=\r?\n/g, '');

    const baseSubj = subject || (targetMsg ? targetMsg.subject : '') || '';
    const cleanSubj = baseSubj.replace(/^(Re:\s*|Fwd:\s*)+/i, '').trim();

    if (mode === 'reply') {
        replyTab.classList.add('active');
        forwardTab.classList.remove('active');
        toInput.value = toEmail || targetSenderEmail || '';
        subjInput.value = cleanSubj ? ('Re: ' + cleanSubj) : 'Re:';
        modeLabel.textContent = 'Replying to ' + toInput.value;

        // Quoted block for reply
        inlineQuotedHtml = `
            <div class="gmail_quote" style="margin-top: 20px; border-left: 2px solid #dadce0; padding-left: 12px; color: #5f6368; font-size: 13px;">
                <div dir="ltr" class="gmail_attr" style="margin-bottom: 8px; color: #70757a;">On ${targetDateStr}, ${senderDisplay} wrote:</div>
                <blockquote class="gmail_quote" style="margin: 0; padding: 0; color: inherit;">
                    ${targetBody}
                </blockquote>
            </div>
        `;

        if (quoteBar) {
            quoteBar.style.display = 'block';
            if (quoteSummary) quoteSummary.textContent = `On ${targetDateStr}, ${targetSenderName || targetSenderEmail} wrote:`;
            if (quotePreview) {
                quotePreview.innerHTML = `<div><strong>On ${targetDateStr}, ${senderDisplay} wrote:</strong></div><div class="mt-2">${targetBody}</div>`;
                quotePreview.style.display = 'none';
            }
        }
    } else {
        forwardTab.classList.add('active');
        replyTab.classList.remove('active');
        toInput.value = '';
        toInput.placeholder = 'Enter recipient email to forward...';
        subjInput.value = cleanSubj ? ('Fwd: ' + cleanSubj) : 'Fwd:';
        modeLabel.textContent = 'Forwarding message to new recipient';

        // Automatically include original attachments in forwarded email
        if (targetMsg && targetMsg.attachments && targetMsg.attachments.length > 0) {
            inlineForwardedAttachments = [...targetMsg.attachments];
        }

        // Quoted block for forward
        inlineQuotedHtml = `
            <div class="gmail_quote" style="margin-top: 20px; border-left: 2px solid #dadce0; padding-left: 12px; color: #475569; font-size: 13px;">
                <div style="margin-bottom: 10px;">
                    <strong>---------- Forwarded message ---------</strong><br>
                    <strong>From:</strong> ${senderDisplay}<br>
                    <strong>Date:</strong> ${targetDateStr}<br>
                    <strong>Subject:</strong> ${baseSubj}<br>
                    <strong>To:</strong> ${targetMsg ? (targetMsg.to_email || '') : ''}<br>
                </div>
                <div>${targetBody}</div>
            </div>
        `;

        if (quoteBar) {
            quoteBar.style.display = 'block';
            if (quoteSummary) quoteSummary.textContent = `Forwarded message from ${targetSenderName || targetSenderEmail}`;
            if (quotePreview) {
                quotePreview.innerHTML = `<div><strong>---------- Forwarded message ---------</strong><br><strong>From:</strong> ${senderDisplay}<br><strong>Date:</strong> ${targetDateStr}<br><strong>Subject:</strong> ${baseSubj}</div><div class="mt-2">${targetBody}</div>`;
                quotePreview.style.display = 'none';
            }
        }
    }

    renderInlineAttachmentChips();

    // Reset quill with empty text so user writes clean message
    if (inlineQuill) {
        inlineQuill.setText('');
        setTimeout(() => {
            if (mode === 'forward') {
                toInput.focus();
            } else {
                inlineQuill.focus();
            }
            box.classList.remove('highlight-focus');
        }, 200);
    }
}

function openInlineComposer(mode = 'reply', toEmail = null, subject = null, messageId = null) {
    const box = document.getElementById('inlineComposerContainer');
    const bottomPills = document.getElementById('detailBottomActionPills');
    if (bottomPills) bottomPills.style.display = 'none';
    if (box) {
        box.style.display = 'block';
        setInlineComposerMode(mode, toEmail, subject, messageId);
        setTimeout(() => {
            box.scrollIntoView({ behavior: 'smooth', block: 'end' });
        }, 50);
    }
}

function discardInlineComposer() {
    if (inlineQuill) inlineQuill.setText('');
    inlineUploadedFiles = [];
    inlineForwardedAttachments = [];
    inlineQuotedHtml = '';
    renderInlineAttachmentChips();
    const quoteBar = document.getElementById('inlineQuoteCollapsibleBar');
    if (quoteBar) quoteBar.style.display = 'none';
    const quotePreview = document.getElementById('inlineQuotePreviewContent');
    if (quotePreview) quotePreview.style.display = 'none';
    const box = document.getElementById('inlineComposerContainer');
    if (box) box.style.display = 'none';
    const bottomPills = document.getElementById('detailBottomActionPills');
    if (bottomPills) bottomPills.style.display = 'flex';
}

function toggleThreadMessage(id) {
    const msgEl = document.getElementById(`thread-msg-${id}`);
    if (!msgEl) return;

    const isCollapsed = msgEl.classList.contains('collapsed');
    if (isCollapsed) {
        msgEl.classList.remove('collapsed');
        const iframe = msgEl.querySelector('iframe');
        if (iframe && typeof iframe.__adjustHeight === 'function') {
            setTimeout(iframe.__adjustHeight, 30);
            setTimeout(iframe.__adjustHeight, 150);
            setTimeout(iframe.__adjustHeight, 400);
        }
    } else {
        msgEl.classList.add('collapsed');
    }
}

function toggleAllThreadMessages() {
    const threadMsgs = document.querySelectorAll('.gmail-thread-message');
    if (!threadMsgs.length) return;

    const anyCollapsed = Array.from(threadMsgs).some(el => el.classList.contains('collapsed'));
    threadMsgs.forEach((el) => {
        if (anyCollapsed) {
            el.classList.remove('collapsed');
            const iframe = el.querySelector('iframe');
            if (iframe && typeof iframe.__adjustHeight === 'function') {
                setTimeout(iframe.__adjustHeight, 30);
                setTimeout(iframe.__adjustHeight, 200);
            }
        } else {
            el.classList.add('collapsed');
        }
    });
}

function popoutInlineComposer() {
    const toEmail = document.getElementById('inlineComposerToInput')?.value || '';
    const subject = document.getElementById('inlineComposerSubjectInput')?.value || '';
    const bodyHtml = inlineQuill ? inlineQuill.root.innerHTML : '';

    // Close inline draft UI
    discardInlineComposer();

    // Open floating compose window with draft state
    openComposeWidget();
    const toField = document.getElementById('composeToEmail');
    const subjField = document.getElementById('composeSubject');
    if (toField) toField.value = toEmail;
    if (subjField) subjField.value = subject;
    if (composeQuill) composeQuill.root.innerHTML = bodyHtml;
}

function openActiveThreadInNewWindow() {
    if (activeThreadId) {
        const inboxPath = window.location.pathname.replace(/\/+$/, '');
        window.open(`${inboxPath}/${encodeURIComponent(activeThreadId)}`, '_blank');
    }
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

    const toEmail = document.getElementById('inlineComposerToInput').value.trim();
    const subject = document.getElementById('inlineComposerSubjectInput').value.trim();
    if (!toEmail) {
        showSendingToast('Please specify a recipient email.', false, true);
        return;
    }

    const userHtml = inlineQuill ? inlineQuill.root.innerHTML : '';
    const userPlain = inlineQuill ? inlineQuill.getText().trim() : '';
    const previewText = userPlain.substring(0, 70) || (inlineComposerMode === 'forward' ? 'Forwarded message' : 'Quick reply');

    // Build authentic HTML email format (user message + pristine quoted chain)
    let finalBodyHtml = '';
    if (userHtml && userHtml !== '<p><br></p>') {
        finalBodyHtml += `<div style="font-family: Roboto, Arial, Helvetica, sans-serif; font-size: 14px; color: #202124; line-height: 1.6;">${userHtml}</div><br>`;
    }
    if (inlineQuotedHtml) {
        finalBodyHtml += inlineQuotedHtml;
    }

    const formData = new FormData();
    formData.append('to', toEmail);
    formData.append('to_email', toEmail);
    formData.append('subject', subject || (inlineComposerMode === 'forward' ? 'Fwd:' : 'Re:'));
    formData.append('body_html', finalBodyHtml);
    formData.append('body_plain', userPlain);

    // Resolve true Thread ID, parent Message ID, and Reply Message ID
    const realThreadId = (activeEmailData && activeEmailData.thread_id) ? activeEmailData.thread_id : activeThreadId;
    if (realThreadId) {
        formData.append('thread_id', realThreadId);
    }

    const replyMsgId = (activeReplyTargetMsg && activeReplyTargetMsg.message_id) 
        ? activeReplyTargetMsg.message_id 
        : (activeEmailData && activeEmailData.message_id ? activeEmailData.message_id : null);
    if (replyMsgId) {
        formData.append('in_reply_to', replyMsgId);
    }

    const parentMsgPk = (activeReplyTargetMsg && activeReplyTargetMsg.id) 
        ? activeReplyTargetMsg.id 
        : (activeEmailData && activeEmailData.id ? activeEmailData.id : activeThreadId);
    if (parentMsgPk) {
        formData.append('parent_message_id', parentMsgPk);
    }

    if (currentAccountId) formData.append('account_id', currentAccountId);

    // Append newly uploaded files
    inlineUploadedFiles.forEach(file => {
        formData.append('attachments[]', file);
    });

    // Append forwarded attachment IDs
    inlineForwardedAttachments.forEach(att => {
        formData.append('forwarded_attachment_ids[]', att.id);
    });

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
    const typedRecipient = document.getElementById('composeToEmail').value.trim();
    if (typedRecipient) addComposeRecipient(typedRecipient);
    if (composeToRecipients.length === 0) {
        showSendingToast('Please add at least one valid recipient email.', false, true);
        document.getElementById('composeToEmail').focus();
        return;
    }
    document.getElementById('composeBodyHtml').value = composeQuill.root.innerHTML;
    const formData = new FormData(form);
    const toEmail = document.getElementById('composeToEmailValue').value;
    const subject = document.getElementById('composeSubject').value;
    const previewText = composeQuill ? composeQuill.getText().substring(0, 70) : '';

    // 1. Clear draft ID and any pending saves before closing
    pendingDraftSaveRequested = false;
    const draftIdInput = document.getElementById('composeDraftId');
    if (draftIdInput) draftIdInput.value = '';
    if (composeDraftTimer) clearTimeout(composeDraftTimer);

    // 2. Instantly close compose modal & reset (<5ms)
    closeComposeWidgetUI();
    form.reset();
    clearComposeRecipients();
    if (composeQuill) composeQuill.root.innerHTML = '';
    removeSelectedFile();

    // 3. Show non-blocking floating status toast
    showSendingToast('Sending message to ' + toEmail + '...');

    // 4. Add optimistic pending item with clock icon in list
    const tempId = addOptimisticPendingEmail(toEmail, subject, previewText);

    // 5. Background Async Send
    sendEmailFormData(formData)
    .then(parseEmailSendResponse)
    .then(() => {
        showSendingToast('Email sent successfully to ' + toEmail, true);
        updateOptimisticPendingEmail(tempId, true);
        // Refresh drafts count after send (in case draft was linked)
        if (typeof updateDraftsBadgeCount === 'function') updateDraftsBadgeCount();
        if (currentFolder === 'drafts' || currentFolder === 'sent') {
            reloadEmailList(false);
        }
    })
    .catch(err => {
        showSendingToast('Email failed: ' + (err.message || 'Unable to send email'), false, true);
        updateOptimisticPendingEmail(tempId, false, true);
    });
}

function scrollReplyBox() {
    openInlineComposer('reply');
}

function closeEmailThread(pushToHistory = true) {
    if (emailDetailRequestController) {
        emailDetailRequestController.abort();
        emailDetailRequestController = null;
    }
    activeThreadId = null;
    activeEmailData = null;
    activeThreadMessages = [];
    activeReplyTargetMsg = null;

    const detailPane = document.getElementById('emailDetailPane');
    if (detailPane) {
        detailPane.classList.remove('active');
    }

    // Reset inline composer and bottom action pills
    const inlineComp = document.getElementById('inlineComposerContainer');
    if (inlineComp) inlineComp.style.display = 'none';
    const bottomPills = document.getElementById('detailBottomActionPills');
    if (bottomPills) bottomPills.style.display = 'flex';

    // Cleanly remove only 'email_id' & 'thread_id' from current URL while preserving active folder, account, search, and labels
    if (pushToHistory) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.delete('email_id');
        currentUrl.searchParams.delete('thread_id');
        history.pushState({ emailId: null }, '', currentUrl.pathname + currentUrl.search);
    }
}

window.addEventListener('popstate', function(e) {
    if (e.state && e.state.emailId) {
        openEmailThread(e.state.emailId, false);
    } else {
        closeEmailThread(false);
    }
});

function replyEmail(id) {
    fetch(`{{ url('emails') }}/` + id, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.email) {
            const email = data.email;
            const dateStr = email.created_at ? new Date(email.created_at).toLocaleString() : 'Recently';
            const senderName = email.from_name || email.from_email;
            const senderDisplay = senderName && senderName !== email.from_email ? `${senderName} &lt;${email.from_email}&gt;` : email.from_email;
            let body = email.body_html || (email.body_plain ? email.body_plain.replace(/\n/g, '<br>') : '');
            body = body.replace(/^\s*\*\s*\d+\s+FETCH\s*\([^\r\n]*\r?\n?/i, '')
                       .replace(/\r?\n\)\s*$/, '')
                       .replace(/=3D/g, '=')
                       .replace(/=\r?\n/g, '');

            const replyQuote = `<p><br></p><div class="gmail_quote" style="margin-top: 18px; color: #5f6368; font-size: 13px;"><div dir="ltr" class="gmail_attr">On ${dateStr}, ${senderDisplay} wrote:</div><blockquote class="gmail_quote" style="margin: 4px 0 0 0.8ex; border-left: 2px solid #dadce0; padding-left: 10px; color: #3c4043;">${body}</blockquote></div>`;

            openComposeModal({
                to: email.from_email,
                subject: 'Re: ' + (email.subject || '').replace(/^(Re:\s*)+/i, ''),
                body: replyQuote
            });
            setTimeout(() => {
                if (composeQuill) {
                    composeQuill.setSelection(0, 0);
                }
            }, 150);
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

// -------------------------------------------------------------
// GMAIL SELECTION TOOLBAR & ROW HANDLING
// -------------------------------------------------------------
function handleRowCheckboxChange(cb, event) {
    if (event) event.stopPropagation();
    const row = cb.closest('.duralux-email-item') || cb.closest('.gmail-row');
    if (row) {
        if (cb.checked) row.classList.add('selected');
        else row.classList.remove('selected');
    }
    updateSelectedToolbar();
}

function updateSelectedToolbar() {
    const checkedBoxes = document.querySelectorAll('.email-item-checkbox:checked');
    const count = checkedBoxes.length;
    const defaultBar = document.getElementById('gmailDefaultToolbar');
    const selectedBar = document.getElementById('gmailSelectedToolbar');
    const badge = document.getElementById('selectedCountBadge');
    const masterDefault = document.getElementById('selectAllEmails');
    const masterSelected = document.getElementById('masterCheckboxSelected');

    if (count > 0) {
        if (defaultBar) defaultBar.style.display = 'none';
        if (selectedBar) {
            selectedBar.style.display = 'flex';
            selectedBar.classList.add('active');
        }
        if (badge) badge.textContent = count + ' selected';
        if (masterDefault) masterDefault.checked = true;
        if (masterSelected) masterSelected.checked = true;
    } else {
        if (defaultBar) defaultBar.style.display = 'flex';
        if (selectedBar) {
            selectedBar.style.display = 'none';
            selectedBar.classList.remove('active');
        }
        if (masterDefault) masterDefault.checked = false;
        if (masterSelected) masterSelected.checked = false;
    }
}

function toggleSelectAll(master) {
    const isChecked = master.checked;
    document.querySelectorAll('.email-item-checkbox').forEach(cb => {
        cb.checked = isChecked;
        const row = cb.closest('.duralux-email-item') || cb.closest('.gmail-row');
        if (row) {
            if (isChecked) row.classList.add('selected');
            else row.classList.remove('selected');
        }
    });
    updateSelectedToolbar();
}

function selectEmailsFilter(type) {
    const checkboxes = document.querySelectorAll('.email-item-checkbox');
    checkboxes.forEach(cb => {
        const row = cb.closest('.duralux-email-item') || cb.closest('.gmail-row');
        if (!row) return;
        let match = false;
        if (type === 'all') match = true;
        else if (type === 'none') match = false;
        else if (type === 'read') match = !row.classList.contains('unread');
        else if (type === 'unread') match = row.classList.contains('unread');
        else if (type === 'starred') match = !!row.querySelector('.duralux-star-btn.active, .gmail-star-btn.active, .fa-star');
        else if (type === 'unstarred') match = !row.querySelector('.duralux-star-btn.active, .gmail-star-btn.active, .fa-star');

        cb.checked = match;
        if (match) row.classList.add('selected');
        else row.classList.remove('selected');
    });
    updateSelectedToolbar();
}

// -------------------------------------------------------------
// GMAIL SNACKBAR & UNDO SYSTEM
// -------------------------------------------------------------
let currentUndoHandler = null;
let snackbarTimer = null;

function showGmailToast(message, undoCallback = null) {
    const bar = document.getElementById('gmailSnackbar');
    const text = document.getElementById('gmailSnackbarText');
    const undoBtn = document.getElementById('gmailSnackbarUndoBtn');
    if (!bar || !text) return;

    if (snackbarTimer) clearTimeout(snackbarTimer);
    text.textContent = message;
    currentUndoHandler = undoCallback;
    if (undoBtn) {
        undoBtn.style.display = undoCallback ? 'inline-block' : 'none';
    }
    bar.classList.add('active');
    snackbarTimer = setTimeout(hideGmailSnackbar, 7000);
}

function hideGmailSnackbar() {
    const bar = document.getElementById('gmailSnackbar');
    if (bar) bar.classList.remove('active');
    currentUndoHandler = null;
}

function executeSnackbarUndo() {
    if (typeof currentUndoHandler === 'function') {
        const fn = currentUndoHandler;
        hideGmailSnackbar();
        fn();
    }
}

function undoLastAction(ids, restoreFolder = 'inbox') {
    fetch('{{ route("emails.undo") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids, folder: restoreFolder })
    }).then(() => {
        showGmailToast('Action undone.');
        reloadEmailList(true);
    });
}

// -------------------------------------------------------------
// SINGLE & BULK ACTIONS
// -------------------------------------------------------------
function archiveEmail(id) {
    const row = document.getElementById('email-row-' + id);
    if (row) {
        row.classList.add('row-fade-out');
        setTimeout(() => row.remove(), 260);
    }
    if (activeThreadId == id) closeEmailThread();

    fetch('{{ route("emails.archive") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: [id] })
    }).then(() => {
        showGmailToast('Conversation archived.', () => undoLastAction([id], 'inbox'));
        checkEmailUpdates();
    });
}

function deleteEmail(id) {
    const row = document.getElementById('email-row-' + id);
    if (row) {
        row.classList.add('row-fade-out');
        setTimeout(() => row.remove(), 260);
    }
    if (activeThreadId == id) closeEmailThread();

    fetch('{{ route("emails.delete") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id, ids: [id] })
    }).then(() => {
        showGmailToast('Conversation moved to Trash.', () => undoLastAction([id], 'inbox'));
        checkEmailUpdates();
    });
}

function archiveActiveThread() {
    if (activeThreadId) archiveEmail(activeThreadId);
}

function deleteActiveThread() {
    if (activeThreadId) deleteEmail(activeThreadId);
}

function spamActiveThread() {
    if (!activeThreadId) return;
    const id = activeThreadId;
    closeEmailThread();
    const row = document.getElementById('email-row-' + id);
    if (row) {
        row.classList.add('row-fade-out');
        setTimeout(() => row.remove(), 260);
    }
    fetch('{{ route("emails.move-folder") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: [id], folder: 'spam' })
    }).then(() => {
        showGmailToast('Conversation marked as spam.', () => undoLastAction([id], 'inbox'));
    });
}

function moveActiveThreadToFolder(folder) {
    if (!activeThreadId) return;
    const id = activeThreadId;
    closeEmailThread();
    const row = document.getElementById('email-row-' + id);
    if (row) {
        row.classList.add('row-fade-out');
        setTimeout(() => row.remove(), 260);
    }
    fetch('{{ route("emails.move-folder") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: [id], folder: folder })
    }).then(() => {
        showGmailToast('Conversation moved to ' + folder + '.', () => undoLastAction([id], 'inbox'));
    });
}

function markActiveThreadUnread() {
    if (activeThreadId) {
        toggleReadStatus(activeThreadId, false);
        closeEmailThread();
        showGmailToast('Conversation marked as unread.');
    }
}

function toggleReadStatus(id, isRead) {
    const shouldBeRead = Boolean(isRead);
    const row = document.getElementById(`email-row-` + id);
    if (row) {
        if (shouldBeRead) {
            row.classList.remove('unread');
            row.classList.add('is-read');
            row.querySelector('.gmail-unread-dot, .duralux-unread-dot')?.remove();
        } else {
            row.classList.add('unread');
            row.classList.remove('is-read');
            const left = row.querySelector('.gmail-row-controls, .duralux-item-left');
            if (left && !left.querySelector('.gmail-unread-dot')) {
                left.insertAdjacentHTML('beforeend', '<span class="gmail-unread-dot duralux-unread-dot" title="Unread email"></span>');
            }
        }

        const toggleBtn = document.getElementById(`btn-read-toggle-${id}`);
        if (toggleBtn) {
            toggleBtn.title = shouldBeRead ? 'Mark as Unread' : 'Mark as Read';
            toggleBtn.setAttribute('onclick', `toggleReadStatus(${id}, ${shouldBeRead ? 'false' : 'true'})`);
            toggleBtn.innerHTML = shouldBeRead 
                ? '<svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>'
                : '<svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M21.99 8c0-.72-.37-1.35-.94-1.7L12 1 2.95 6.3C2.38 6.65 2 7.28 2 8v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2l-.01-10zM12 3.32L19.99 8v.01L12 13 4 8.01V8l8-4.68zM4 18v-8.2l7.46 4.66c.16.1.35.15.54.15s.38-.05.54-.15L20 9.8V18H4z"/></svg>';
        }
    }

    fetch('{{ route("emails.mark-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id, ids: [id], is_read: shouldBeRead })
    }).then(() => {
        checkEmailUpdates();
    });
}

function bulkArchive() {
    const ids = Array.from(document.querySelectorAll('.email-item-checkbox:checked')).map(cb => cb.value);
    if (!ids.length) return;

    ids.forEach(id => {
        const row = document.getElementById('email-row-' + id);
        if (row) {
            row.classList.add('row-fade-out');
            setTimeout(() => row.remove(), 260);
        }
    });
    selectEmailsFilter('none');

    fetch('{{ route("emails.archive") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids })
    }).then(() => {
        showGmailToast(`${ids.length} conversations archived.`, () => undoLastAction(ids, 'inbox'));
        checkEmailUpdates();
    });
}

function bulkDelete() {
    const ids = Array.from(document.querySelectorAll('.email-item-checkbox:checked')).map(cb => cb.value);
    if (!ids.length) return;

    ids.forEach(id => {
        const row = document.getElementById('email-row-' + id);
        if (row) {
            row.classList.add('row-fade-out');
            setTimeout(() => row.remove(), 260);
        }
    });
    selectEmailsFilter('none');

    fetch('{{ route("emails.delete") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids })
    }).then(() => {
        showGmailToast(`${ids.length} conversations moved to Trash.`, () => undoLastAction(ids, 'inbox'));
        checkEmailUpdates();
    });
}

function bulkMarkRead() {
    const ids = Array.from(document.querySelectorAll('.email-item-checkbox:checked')).map(cb => cb.value);
    if (!ids.length) return;

    ids.forEach(id => {
        const row = document.getElementById('email-row-' + id);
        if (row) {
            row.classList.remove('unread');
            row.classList.add('is-read');
            row.querySelector('.gmail-unread-dot, .duralux-unread-dot')?.remove();
        }
    });
    selectEmailsFilter('none');

    fetch('{{ route("emails.mark-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids, is_read: true })
    }).then(() => {
        showGmailToast('Marked as read.');
        checkEmailUpdates();
    });
}

function bulkMarkUnread() {
    const ids = Array.from(document.querySelectorAll('.email-item-checkbox:checked')).map(cb => cb.value);
    if (!ids.length) return;

    ids.forEach(id => {
        const row = document.getElementById('email-row-' + id);
        if (row) {
            row.classList.add('unread');
            row.classList.remove('is-read');
            const left = row.querySelector('.gmail-row-controls, .duralux-item-left');
            if (left && !left.querySelector('.gmail-unread-dot')) {
                left.insertAdjacentHTML('beforeend', '<span class="gmail-unread-dot duralux-unread-dot" title="Unread email"></span>');
            }
        }
    });
    selectEmailsFilter('none');

    fetch('{{ route("emails.mark-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids, is_read: false })
    }).then(() => {
        showGmailToast('Marked as unread.');
        checkEmailUpdates();
    });
}

function bulkStar() {
    const ids = Array.from(document.querySelectorAll('.email-item-checkbox:checked')).map(cb => cb.value);
    if (!ids.length) return;

    ids.forEach(id => {
        const row = document.getElementById('email-row-' + id);
        if (row) {
            const star = row.querySelector('.gmail-star-btn');
            if (star) toggleStar(id, star);
        }
    });
    selectEmailsFilter('none');
    showGmailToast('Star updated.');
}

function bulkMoveToFolder(folder) {
    const ids = Array.from(document.querySelectorAll('.email-item-checkbox:checked')).map(cb => cb.value);
    if (!ids.length) return;

    ids.forEach(id => {
        const row = document.getElementById('email-row-' + id);
        if (row) {
            row.classList.add('row-fade-out');
            setTimeout(() => row.remove(), 260);
        }
    });
    selectEmailsFilter('none');

    fetch('{{ route("emails.move-folder") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids, folder: folder })
    }).then(() => {
        showGmailToast(`${ids.length} conversations moved to ${folder}.`, () => undoLastAction(ids, 'inbox'));
        checkEmailUpdates();
    });
}

function bulkAssignLabel(labelId) {
    const ids = Array.from(document.querySelectorAll('.email-item-checkbox:checked')).map(cb => cb.value);
    if (!ids.length) return;

    selectEmailsFilter('none');
    fetch('{{ route("emails.bulk-labels") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids, label_id: labelId, action: 'add' })
    }).then(() => {
        showGmailToast('Labels updated.');
        reloadEmailList(false);
    });
}

function markAllAsRead() {
    const rows = document.querySelectorAll('.duralux-email-item.unread, .gmail-row.unread');
    const ids = Array.from(rows).map(r => r.id.replace('email-row-', ''));
    if (!ids.length) {
        showGmailToast('No unread conversations.');
        return;
    }
    ids.forEach(id => {
        const row = document.getElementById('email-row-' + id);
        if (row) {
            row.classList.remove('unread');
            row.classList.add('is-read');
            row.querySelector('.gmail-unread-dot, .duralux-unread-dot')?.remove();
        }
    });
    fetch('{{ route("emails.mark-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids, is_read: true })
    }).then(() => {
        showGmailToast('All conversations marked as read.');
        checkEmailUpdates();
    });
}

// -------------------------------------------------------------
// GMAIL CATEGORY TABS & DETAIL NAVIGATION
// -------------------------------------------------------------
let activeCategory = 'primary';
function switchCategoryTab(cat, el) {
    activeCategory = cat;
    document.querySelectorAll('.gmail-category-tabs .gmail-tab-item').forEach(tab => tab.classList.remove('active'));
    if (el) el.classList.add('active');

    // Filter rows or trigger reload
    reloadEmailList(true);
}

function navigateDetailThread(direction) {
    if (!activeThreadId) return;
    const rows = Array.from(document.querySelectorAll('.duralux-email-item, .gmail-row'));
    if (!rows.length) return;
    const currentIndex = rows.findIndex(r => r.id === 'email-row-' + activeThreadId);
    if (currentIndex === -1) return;
    const targetIndex = currentIndex + direction;
    if (targetIndex >= 0 && targetIndex < rows.length) {
        const targetRow = rows[targetIndex];
        const targetId = targetRow.id.replace('email-row-', '');
        if (targetId) openEmailThread(targetId);
    }
}

function navigatePagination(direction) {
    if (direction > 0 && hasMorePages) {
        loadMoreEmails();
    } else if (direction < 0 && currentPage > 1) {
        currentPage = Math.max(1, currentPage - 1);
        reloadEmailList(true);
    }
}

// -------------------------------------------------------------
// GMAIL POWER KEYBOARD SHORTCUTS
// -------------------------------------------------------------
let keyboardFocusedEmailIndex = -1;

function getEmailRows() {
    return Array.from(document.querySelectorAll('.duralux-email-item, .gmail-row'));
}

function navigateEmailRows(delta) {
    const rows = getEmailRows();
    if (!rows.length) return;

    if (keyboardFocusedEmailIndex === -1) {
        keyboardFocusedEmailIndex = delta > 0 ? 0 : rows.length - 1;
    } else {
        keyboardFocusedEmailIndex = Math.max(0, Math.min(rows.length - 1, keyboardFocusedEmailIndex + delta));
    }

    rows.forEach((r, idx) => {
        if (idx === keyboardFocusedEmailIndex) {
            r.classList.add('keyboard-focused');
            r.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        } else {
            r.classList.remove('keyboard-focused');
        }
    });
}

function getFocusedEmailId() {
    const rows = getEmailRows();
    if (keyboardFocusedEmailIndex >= 0 && keyboardFocusedEmailIndex < rows.length) {
        return rows[keyboardFocusedEmailIndex].id.replace('email-row-', '');
    }
    return null;
}

function openFocusedEmail() {
    const id = getFocusedEmailId();
    if (id) openEmailThread(id);
}

document.addEventListener('keydown', function(e) {
    const tag = (e.target.tagName || '').toLowerCase();
    if (tag === 'input' || tag === 'textarea' || tag === 'select' || e.target.isContentEditable || e.target.closest('.ql-editor')) {
        if (e.key === 'Escape') e.target.blur();
        return;
    }

    if (e.key === 'c' && !e.ctrlKey && !e.metaKey) {
        e.preventDefault();
        openComposeModal();
    } else if (e.key === 'u' || e.key === 'Escape') {
        e.preventDefault();
        if (document.getElementById('emailDetailPane')?.classList.contains('active')) {
            closeEmailThread();
        } else {
            closeComposeWidget();
        }
    } else if (e.key === 'j' || e.key === 'ArrowDown') {
        e.preventDefault();
        navigateEmailRows(1);
    } else if (e.key === 'k' || e.key === 'ArrowUp') {
        e.preventDefault();
        navigateEmailRows(-1);
    } else if (e.key === 'Enter' || e.key === 'o') {
        e.preventDefault();
        openFocusedEmail();
    } else if (e.key === 'e') {
        e.preventDefault();
        if (activeThreadId && document.getElementById('emailDetailPane')?.classList.contains('active')) {
            archiveActiveThread();
        } else {
            const focused = getFocusedEmailId();
            if (focused) archiveEmail(focused);
            else bulkArchive();
        }
    } else if (e.key === '#' || (e.key === 'Delete' && !e.ctrlKey)) {
        e.preventDefault();
        if (activeThreadId && document.getElementById('emailDetailPane')?.classList.contains('active')) {
            deleteActiveThread();
        } else {
            const focused = getFocusedEmailId();
            if (focused) deleteEmail(focused);
            else bulkDelete();
        }
    } else if (e.key === 's') {
        e.preventDefault();
        if (activeThreadId && document.getElementById('emailDetailPane')?.classList.contains('active')) {
            toggleDetailStar();
        } else {
            const focused = getFocusedEmailId();
            if (focused) {
                const row = document.getElementById('email-row-' + focused);
                const starBtn = row?.querySelector('.gmail-star-btn');
                if (starBtn) toggleStar(focused, starBtn);
            }
        }
    } else if (e.key === 'r') {
        if (activeThreadId && document.getElementById('emailDetailPane')?.classList.contains('active')) {
            e.preventDefault();
            setInlineComposerMode('reply');
            scrollReplyBox();
        }
    } else if (e.key === 'f') {
        if (activeThreadId && document.getElementById('emailDetailPane')?.classList.contains('active')) {
            e.preventDefault();
            setInlineComposerMode('forward');
            scrollReplyBox();
        }
    } else if (e.key === '/') {
        e.preventDefault();
        const search = document.getElementById('emailSearchInput');
        if (search) {
            search.focus();
            search.select();
        }
    }
});

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
                mark.gmail-search-highlight, .gmail-search-highlight {
                    background-color: #fef08a !important;
                    color: #111827 !important;
                    padding: 1px 3px !important;
                    border-radius: 2px !important;
                    font-weight: 700 !important;
                    box-shadow: 0 0 0 1px rgba(234, 179, 8, 0.25) !important;
                    display: inline !important;
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

                const searchInput = document.getElementById('emailSearchInput');
                const searchVal = searchInput ? searchInput.value.trim() : '';
                if (searchVal && doc.body) {
                    highlightSearchTermsInElement(doc.body, searchVal);
                }
            }
        } catch (err) {}

        adjustHeight();
        setTimeout(adjustHeight, 100);
        setTimeout(adjustHeight, 500);
        setTimeout(adjustHeight, 1200);
    };
}
</script>
@endpush
@endsection
