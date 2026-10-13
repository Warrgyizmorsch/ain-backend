@extends('layouts.app')

@section('content')
    @php
        $spreadsheets = $spreadsheets ?? collect();
        $activeSpreadsheet = $activeSpreadsheet ?? ($spreadsheets->first() ?? null);
        $activeId = $activeSpreadsheet ? $activeSpreadsheet->id : null;
        $activeTitle = $activeSpreadsheet ? $activeSpreadsheet->title : 'Untitled Spreadsheet';
        $activeData =
            $activeSpreadsheet && !empty($activeSpreadsheet->sheet_data)
                ? $activeSpreadsheet->sheet_data
                : json_encode([
                    [
                        'name' => 'Sheet1',
                        'color' => '',
                        'status' => 1,
                        'order' => 0,
                        'data' => [],
                        'config' => [],
                        'index' => 0,
                        'row' => 84,
                        'column' => 26,
                    ],
                ]);
    @endphp

    <!-- Luckysheet CDN Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/plugins/css/pluginsCss.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/plugins/plugins.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/css/luckysheet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/assets/iconfont/iconfont.css" />

    <style>
        /* ──────────────────────────────────────────────────────────
                       EXCEL SHEETS WORKSPACE — WhatsApp-Style 2-Panel UI
                       ────────────────────────────────────────────────────────── */
        .content,
        main.content,
        .flex-column-fluid.content {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        :root {
            --excel-green: #107c41;
            --excel-green-hover: #0b6a36;
            --excel-green-light: #e8f8f0;
            --excel-green-border: #b7ebd0;
            --excel-sidebar-w: 320px;
            --excel-border: #e4e6ef;
            --excel-bg: #f5f8fa;
        }

        .excel-page {
            display: grid;
            grid-template-columns: var(--excel-sidebar-w) 1fr;
            height: calc(100vh - 75px);
            margin-top: 0 !important;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--excel-border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: grid-template-columns 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .excel-page.sidebar-collapsed {
            grid-template-columns: 0px 1fr !important;
        }

        /* ── SIDEBAR PANEL ── */
        .excel-sidebar {
            display: flex;
            flex-direction: column;
            background: #ffffff;
            border-right: 1px solid var(--excel-border);
            height: 100%;
            overflow: hidden;
            transition: all 0.22s ease-in-out;
            position: relative;
        }

        .excel-sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            background: #ffffff;
            border-bottom: 1px solid var(--excel-border);
        }

        .excel-sidebar-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .excel-brand-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--excel-green-light);
            color: var(--excel-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
        }

        .excel-title-text h4 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #181c32;
            line-height: 1.2;
        }

        .excel-title-text span {
            font-size: 11px;
            color: #a1a5b7;
            font-weight: 500;
        }

        .excel-new-btn {
            background: var(--excel-green);
            color: #ffffff !important;
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: background 0.18s;
        }

        .excel-new-btn:hover {
            background: var(--excel-green-hover);
            box-shadow: 0 3px 8px rgba(16, 124, 65, 0.3);
        }

        /* Search wrap */
        .excel-search-wrap {
            padding: 10px 14px;
            border-bottom: 1px solid #f1f1f4;
        }

        .excel-search-box {
            display: flex;
            align-items: center;
            background: #f5f8fa;
            border-radius: 6px;
            padding: 7px 12px;
            border: 1px solid transparent;
            transition: all 0.18s;
        }

        .excel-search-box:focus-within {
            background: #ffffff;
            border-color: var(--excel-green);
            box-shadow: 0 0 0 2px rgba(16, 124, 65, 0.12);
        }

        .excel-search-box input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 12px;
            color: #3f4254;
            margin-left: 8px;
        }

        /* Filter pills */
        .excel-tabs {
            display: flex;
            padding: 8px 14px;
            gap: 6px;
            border-bottom: 1px solid #f1f1f4;
            background: #fafafa;
        }

        .excel-tab-pill {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            color: #5e6278;
            background: transparent;
            border: 1px solid #e4e6ef;
            cursor: pointer;
            transition: all 0.15s;
        }

        .excel-tab-pill.active {
            background: var(--excel-green);
            color: #ffffff;
            border-color: var(--excel-green);
        }

        /* Files list */
        .excel-files-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px 10px;
        }

        .excel-file-card {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            margin-bottom: 6px;
            border-radius: 8px;
            background: #ffffff;
            border: 1px solid #f1f1f4;
            cursor: pointer;
            position: relative;
            transition: all 0.18s;
        }

        .excel-file-card:hover {
            background: #f9fbfd;
            border-color: #d1d5db;
            transform: translateY(-1px);
        }

        .excel-file-card.is-active {
            background: var(--excel-green-light) !important;
            border-color: var(--excel-green-border) !important;
        }

        .excel-card-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: #edf7ed;
            color: var(--excel-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
            margin-right: 10px;
        }

        .excel-card-details {
            flex: 1;
            min-width: 0;
        }

        .excel-card-title {
            font-size: 13px;
            font-weight: 600;
            color: #181c32;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }

        .excel-file-card.is-active .excel-card-title {
            color: var(--excel-green);
        }

        .excel-card-meta {
            font-size: 11px;
            color: #a1a5b7;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 3px;
        }

        .excel-card-actions {
            display: flex;
            align-items: center;
            gap: 4px;
            opacity: 0.6;
            transition: opacity 0.15s;
        }

        .excel-file-card:hover .excel-card-actions {
            opacity: 1;
        }

        .excel-icon-btn {
            border: none;
            background: transparent;
            padding: 4px;
            border-radius: 4px;
            color: #7e8299;
            cursor: pointer;
            transition: all 0.15s;
        }

        .excel-icon-btn:hover {
            background: #e4e6ef;
            color: #181c32;
        }

        .excel-icon-btn.is-fav {
            color: #ffc700 !important;
        }

        /* Sidebar footer */
        .excel-sidebar-footer {
            padding: 10px 14px;
            border-top: 1px solid var(--excel-border);
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .excel-import-btn {
            font-size: 11px;
            font-weight: 600;
            color: #3f4254;
            background: #ffffff;
            border: 1px solid #e4e6ef;
            border-radius: 6px;
            padding: 6px 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.18s;
        }

        .excel-import-btn:hover {
            background: #f1f1f4;
            color: #181c32;
        }

        /* ── WORKSPACE (RIGHT PANEL) ── */
        .excel-workspace {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            background: #ffffff;
            position: relative;
        }

        .excel-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 14px;
            background: #ffffff;
            border-bottom: 1px solid var(--excel-border);
            height: 44px;
            min-height: 44px;
            flex-shrink: 0;
            position: relative;
            z-index: 50;
        }

        .excel-topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

        .excel-toggle-sidebar-btn {
            border: 1px solid #e4e6ef;
            background: #ffffff;
            color: #5e6278;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.18s;
            flex-shrink: 0;
        }

        .excel-toggle-sidebar-btn:hover {
            background: #f5f8fa;
            color: #181c32;
            border-color: #b5b5c3;
        }

        .excel-title-container {
            display: flex;
            align-items: center;
            gap: 8px;
            max-width: 450px;
        }

        .excel-title-input {
            font-size: 15px;
            font-weight: 700;
            color: #181c32;
            border: 1px solid transparent;
            border-radius: 4px;
            padding: 3px 8px;
            background: transparent;
            outline: none;
            transition: all 0.15s;
            width: 100%;
        }

        .excel-title-input:hover {
            background: #f5f8fa;
            border-color: #e4e6ef;
        }

        .excel-title-input:focus {
            background: #ffffff;
            border-color: var(--excel-green);
            box-shadow: 0 0 0 2px rgba(16, 124, 65, 0.15);
        }

        .excel-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 12px;
            background: #e8f8f0;
            color: #0d8a4e;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .excel-status-badge.saving {
            background: #fff8dd;
            color: #b58100;
        }

        .excel-status-badge.unsaved {
            background: #fff5f5;
            color: #f1416c;
        }

        .excel-topbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .excel-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            border: 1px solid #e4e6ef;
            background: #ffffff;
            color: #3f4254;
            cursor: pointer;
            transition: all 0.18s;
        }

        .excel-btn:hover {
            background: #f5f8fa;
            color: #181c32;
            border-color: #b5b5c3;
        }

        .excel-btn-primary {
            background: var(--excel-green) !important;
            color: #ffffff !important;
            border-color: var(--excel-green) !important;
        }

        .excel-btn-primary:hover {
            background: var(--excel-green-hover) !important;
            box-shadow: 0 3px 8px rgba(16, 124, 65, 0.3);
        }

        /* Canvas Container */
        .excel-canvas-wrap {
            flex: 1 1 auto;
            position: relative;
            width: 100%;
            height: calc(100% - 44px);
            min-height: 0;
            overflow: hidden;
            background: #ffffff;
        }

        #luckysheet {
            margin: 0px;
            padding: 0px;
            position: relative !important;
            width: 100% !important;
            height: 100% !important;
            border: none !important;
        }

        /* Luckysheet aesthetic adjustments */
        .luckysheet-share-logo {
            display: none !important;
        }

        .luckysheet-wa-editor {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif !important;
        }

        #luckysheet-icon-morebtn-div {
            z-index: 99999 !important;
            top: 120px !important;
            background: #ffffff !important;
            border: 1px solid #d4d4d4 !important;
            border-radius: 6px !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
        }

        /* Empty state */
        .excel-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #a1a5b7;
            text-align: center;
            padding: 20px;
        }

        .excel-empty-state i {
            font-size: 48px;
            color: #d1d5db;
            margin-bottom: 12px;
        }

        body>.dropdown-menu.excel-body-dropdown {
            z-index: 99999 !important;
        }
    </style>

    <div class="excel-page" id="excelPageContainer">

        <!-- ── LEFT SIDEBAR (SAVED SPREADSHEETS LIST) ── -->
        <aside class="excel-sidebar" id="excelSidebar">
            <!-- Header -->
            <div class="excel-sidebar-header">
                <div class="excel-sidebar-title">
                    <div class="excel-brand-icon">
                        <i class="fa fa-file-excel"></i>
                    </div>
                    <div class="excel-title-text">
                        <h4>Excel Sheets</h4>
                        <span id="excelSheetsCountText">{{ $spreadsheets->count() }} Files</span>
                    </div>
                </div>
                <button type="button" class="excel-new-btn" id="btnCreateNewSheet" title="Create New Spreadsheet">
                    <i class="fa fa-plus"></i> New
                </button>
            </div>

            <!-- Search Wrap -->
            <div class="excel-search-wrap">
                <div class="excel-search-box">
                    <i class="fa fa-search text-muted fs-7"></i>
                    <input type="text" id="excelSearchInput" placeholder="Search spreadsheets..." autocomplete="off">
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="excel-tabs">
                <button type="button" class="excel-tab-pill active" data-filter="all">All</button>
                <button type="button" class="excel-tab-pill" data-filter="favorite">Starred</button>
                <button type="button" class="excel-tab-pill" data-filter="mine">My Sheets</button>
            </div>

            <!-- Files List -->
            <div class="excel-files-list" id="excelFilesList">
                @forelse($spreadsheets as $sheet)
                    <div class="excel-file-card {{ $activeId == $sheet->id ? 'is-active' : '' }}"
                        id="sheetCard_{{ $sheet->id }}" data-id="{{ $sheet->id }}" data-title="{{ $sheet->title }}"
                        onclick="loadSpreadsheet({{ $sheet->id }})">
                        <div class="excel-card-icon">
                            <i class="fa fa-file-excel"></i>
                        </div>
                        <div class="excel-card-details">
                            <div class="excel-card-title" title="{{ $sheet->title }}">{{ $sheet->title }}</div>
                            <div class="excel-card-meta">
                                <span><i class="fa fa-clock-o fs-9"></i> {{ $sheet->updated_at->diffForHumans() }}</span>
                                <span>• {{ $sheet->updater?->name ?? ($sheet->creator?->name ?? 'System') }}</span>
                            </div>
                        </div>
                        <div class="excel-card-actions" onclick="event.stopPropagation()">
                            <button type="button" class="excel-icon-btn {{ $sheet->is_favorite ? 'is-fav' : '' }}"
                                onclick="toggleFavorite({{ $sheet->id }}, event)"
                                title="{{ $sheet->is_favorite ? 'Unstar' : 'Star' }}">
                                <i class="fa {{ $sheet->is_favorite ? 'fa-star' : 'fa-star-o' }}"></i>
                            </button>
                            <div class="dropdown">
                                <button type="button" class="excel-icon-btn" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li>
                                        <a class="dropdown-item py-2 fs-7" href="javascript:void(0)"
                                            onclick="promptRenameSheet({{ $sheet->id }}, '{{ addslashes($sheet->title) }}')">
                                            <i class="fa fa-pencil text-primary me-2"></i> Rename
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 fs-7" href="javascript:void(0)"
                                            onclick="duplicateSheet({{ $sheet->id }})">
                                            <i class="fa fa-copy text-info me-2"></i> Duplicate
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 fs-7" href="javascript:void(0)"
                                            onclick="exportToXlsx()">
                                            <i class="fa fa-download text-success me-2"></i> Download (.xlsx)
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 fs-7 text-danger" href="javascript:void(0)"
                                            onclick="deleteSheet({{ $sheet->id }}, '{{ addslashes($sheet->title) }}')">
                                            <i class="fa fa-trash me-2"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="excel-empty-state" id="emptyFilesState">
                        <i class="fa fa-file-excel-o"></i>
                        <p class="mb-2 fs-7">No spreadsheets yet.</p>
                        <button class="btn btn-sm btn-light-success fw-bold" onclick="createNewSheet()">
                            <i class="fa fa-plus me-1"></i> Create First Sheet
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Sidebar Footer -->
            <div class="excel-sidebar-footer">
                <label class="excel-import-btn mb-0" title="Upload and open an existing Excel (.xlsx) file">
                    <i class="fa fa-upload text-success"></i> Import .xlsx
                    <input type="file" id="excelFileInput" accept=".xlsx" style="display: none;"
                        onchange="handleExcelFileImport(this)">
                </label>
                <span class="fs-9 text-muted">Auto-saves to DB</span>
            </div>
        </aside>

        <!-- ── RIGHT WORKSPACE (EXCEL EDITOR) ── -->
        <main class="excel-workspace">
            <!-- Top Toolbar -->
            <div class="excel-topbar">
                <div class="excel-topbar-left">
                    <button type="button" class="excel-toggle-sidebar-btn" id="btnToggleExcelSidebar"
                        onclick="toggleSidebarView()" title="Toggle Files Panel">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div class="excel-title-container">
                        <input type="text" id="activeSheetTitle" class="excel-title-input"
                            value="{{ $activeTitle }}" title="Click to rename"
                            onchange="saveActiveSheetTitle(this.value)">
                    </div>
                    <div class="excel-status-badge" id="saveStatusBadge">
                        <i class="fa fa-check-circle"></i>
                        <span id="saveStatusText">All changes saved</span>
                    </div>
                </div>

                <div class="excel-topbar-actions">
                    <button type="button" class="excel-btn excel-btn-primary" id="btnManualSave"
                        onclick="saveActiveSpreadsheet(true)" title="Save changes immediately">
                        <i class="fa fa-floppy-o"></i> Save
                    </button>
                    <button type="button" class="excel-btn" onclick="exportToXlsx()" title="Download as Excel .xlsx">
                        <i class="fa fa-download text-success"></i> Export XLSX
                    </button>
                    <button type="button" class="excel-btn" onclick="document.getElementById('excelFileInput').click()"
                        title="Import Excel File">
                        <i class="fa fa-upload"></i> Import
                    </button>
                    <button type="button" class="excel-btn" onclick="toggleFullScreen()" title="Fullscreen Mode">
                        <i class="fa fa-arrows-alt" id="fullscreenIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Luckysheet Canvas -->
            <div class="excel-canvas-wrap">
                <div id="luckysheet"></div>
            </div>
        </main>

    </div>

    <!-- Luckysheet Core Dependencies & XLSX Helpers -->
    <script src="https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/plugins/js/plugin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/luckysheet.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luckyexcel@latest/dist/luckyexcel.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/exceljs@4.3.0/dist/exceljs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>

    <script>
        // ──────────────────────────────────────────────────────────
        // SPREADSHEETS CORE ENGINE JAVASCRIPT
        // ──────────────────────────────────────────────────────────
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        let currentSheetId = @json($activeId);
        let currentSheetTitle = @json($activeTitle);
        let initialSheetData = @json(json_decode($activeData, true));
        let autoSaveTimer = null;
        let hasUnsavedChanges = false;
        let currentFilter = 'all';

        // Setup Default Luckysheet fallback if initial data is blank
        if (!initialSheetData || !Array.isArray(initialSheetData) || initialSheetData.length === 0) {
            initialSheetData = [{
                name: "Sheet1",
                color: "",
                status: 1,
                order: 0,
                data: [],
                config: {},
                index: 0,
                row: 84,
                column: 26
            }];
        }

        // 1. Initialize Luckysheet
        function initLuckysheet(sheetData, title) {
            if (window.luckysheet) {
                try {
                    window.luckysheet.destroy();
                } catch (e) {}
            }

            const options = {
                container: 'luckysheet',
                title: title || 'Untitled Spreadsheet',
                lang: 'en',
                showinfobar: false, // We have our own sleek header
                showsheetbar: true, // Bottom sheet tabs (Sheet1, Sheet2, +)
                showsheetbarConfig: {
                    add: true,
                    menu: true,
                    sheet: true
                },
                sheetFormulaBar: true, // Formula bar (fx)
                showstatisticBar: true, // Summary bar at bottom right
                enableAddRow: true,
                enableAddBackTop: true,
                data: sheetData,
                hook: {
                    updated: function() {
                        markUnsavedChanges();
                    },
                    cellUpdated: function() {
                        markUnsavedChanges();
                    },
                    sheetActivate: function() {
                        markUnsavedChanges();
                    }
                }
            };

            window.luckysheet.create(options);
            updateStatusBadge('saved');
        }

        // 2. Mark Unsaved and Trigger Auto-Save
        function markUnsavedChanges() {
            hasUnsavedChanges = true;
            updateStatusBadge('unsaved');

            if (autoSaveTimer) {
                clearTimeout(autoSaveTimer);
            }
            // Auto-save after 2.5 seconds of inactivity
            autoSaveTimer = setTimeout(() => {
                saveActiveSpreadsheet(false);
            }, 2500);
        }

        // 3. Status Badge Updates
        function updateStatusBadge(status, text) {
            const badge = document.getElementById('saveStatusBadge');
            const badgeText = document.getElementById('saveStatusText');
            if (!badge || !badgeText) return;

            badge.className = 'excel-status-badge';

            if (status === 'saved') {
                badge.innerHTML = '<i class="fa fa-check-circle text-success"></i> <span>' + (text || 'All changes saved') +
                    '</span>';
            } else if (status === 'saving') {
                badge.classList.add('saving');
                badge.innerHTML = '<i class="fa fa-spinner fa-spin"></i> <span>Saving to DB...</span>';
            } else if (status === 'unsaved') {
                badge.classList.add('unsaved');
                badge.innerHTML = '<i class="fa fa-pencil"></i> <span>Unsaved changes</span>';
            }
        }

        // 4. Save Active Spreadsheet to Database
        async function saveActiveSpreadsheet(isManual = false) {
            if (!currentSheetId) return;

            updateStatusBadge('saving');

            try {
                const sheetData = window.luckysheet ? window.luckysheet.getAllSheets() : null;
                const titleInput = document.getElementById('activeSheetTitle');
                const title = titleInput ? titleInput.value.trim() : currentSheetTitle;

                const response = await fetch(`{{ url('spreadsheets') }}/${currentSheetId}/save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        sheet_data: sheetData,
                        title: title
                    })
                });

                const res = await response.json();
                if (res.success) {
                    hasUnsavedChanges = false;
                    updateStatusBadge('saved', 'Saved ' + (res.updated_at_human || 'just now'));

                    // Update sidebar card title & meta if needed
                    const card = document.getElementById(`sheetCard_${currentSheetId}`);
                    if (card) {
                        const titleEl = card.querySelector('.excel-card-title');
                        if (titleEl) titleEl.innerText = title;
                        const metaEl = card.querySelector('.excel-card-meta');
                        if (metaEl) {
                            metaEl.innerHTML =
                                `<span><i class="fa fa-clock-o fs-9"></i> ${res.updated_at_human}</span><span>• ${res.updater_name}</span>`;
                        }
                    }
                } else {
                    updateStatusBadge('unsaved', 'Save failed');
                }
            } catch (err) {
                console.error('Error saving spreadsheet:', err);
                updateStatusBadge('unsaved', 'Error saving');
            }
        }

        // 5. Load a Spreadsheet by ID
        async function loadSpreadsheet(id) {
            if (id === currentSheetId) return;

            if (hasUnsavedChanges) {
                await saveActiveSpreadsheet();
            }

            // Set active class on card
            document.querySelectorAll('.excel-file-card').forEach(el => el.classList.remove('is-active'));
            const targetCard = document.getElementById(`sheetCard_${id}`);
            if (targetCard) targetCard.classList.add('is-active');

            updateStatusBadge('saving');

            try {
                const response = await fetch(`{{ url('spreadsheets') }}/${id}/data`, {
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    }
                });

                const res = await response.json();
                if (res.success && res.spreadsheet) {
                    currentSheetId = res.spreadsheet.id;
                    currentSheetTitle = res.spreadsheet.title;

                    const titleInput = document.getElementById('activeSheetTitle');
                    if (titleInput) titleInput.value = currentSheetTitle;

                    let sheetData = null;
                    try {
                        sheetData = typeof res.spreadsheet.sheet_data === 'string' ?
                            JSON.parse(res.spreadsheet.sheet_data) :
                            res.spreadsheet.sheet_data;
                    } catch (e) {}

                    if (!sheetData || !Array.isArray(sheetData) || sheetData.length === 0) {
                        sheetData = [{
                            name: "Sheet1",
                            color: "",
                            status: 1,
                            order: 0,
                            data: [],
                            config: {},
                            index: 0,
                            row: 84,
                            column: 26
                        }];
                    }

                    initLuckysheet(sheetData, currentSheetTitle);

                    // Update URL without refresh
                    const newUrl = new URL(window.location);
                    newUrl.searchParams.set('id', currentSheetId);
                    window.history.replaceState({}, '', newUrl);
                }
            } catch (err) {
                console.error('Error loading spreadsheet:', err);
                alert('Could not load spreadsheet.');
            }
        }

        // 6. Create New Spreadsheet
        async function createNewSheet() {
            const title = prompt('Enter Spreadsheet Title:', 'Untitled Spreadsheet');
            if (title === null) return;

            try {
                const response = await fetch(`{{ route('spreadsheets.create') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        title: title.trim() || 'Untitled Spreadsheet'
                    })
                });

                const res = await response.json();
                if (res.success && res.spreadsheet) {
                    await refreshFilesList();
                    loadSpreadsheet(res.spreadsheet.id);
                }
            } catch (err) {
                console.error('Error creating spreadsheet:', err);
                alert('Could not create spreadsheet.');
            }
        }
        document.getElementById('btnCreateNewSheet')?.addEventListener('click', createNewSheet);

        // 7. Save Title Change
        async function saveActiveSheetTitle(newTitle) {
            if (!currentSheetId || !newTitle.trim()) return;

            try {
                const response = await fetch(`{{ url('spreadsheets') }}/${currentSheetId}/rename`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        title: newTitle.trim()
                    })
                });

                const res = await response.json();
                if (res.success) {
                    currentSheetTitle = res.title;
                    const card = document.getElementById(`sheetCard_${currentSheetId}`);
                    if (card) {
                        const titleEl = card.querySelector('.excel-card-title');
                        if (titleEl) titleEl.innerText = res.title;
                    }
                }
            } catch (err) {
                console.error('Error renaming sheet:', err);
            }
        }

        function promptRenameSheet(id, oldTitle) {
            const newTitle = prompt('Rename Spreadsheet:', oldTitle);
            if (!newTitle || newTitle === oldTitle) return;

            if (id === currentSheetId) {
                const titleInput = document.getElementById('activeSheetTitle');
                if (titleInput) titleInput.value = newTitle;
                saveActiveSheetTitle(newTitle);
            } else {
                fetch(`{{ url('spreadsheets') }}/${id}/rename`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        title: newTitle.trim()
                    })
                }).then(r => r.json()).then(res => {
                    if (res.success) refreshFilesList();
                });
            }
        }

        // 8. Duplicate Sheet
        async function duplicateSheet(id) {
            try {
                const response = await fetch(`{{ url('spreadsheets') }}/${id}/duplicate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    }
                });

                const res = await response.json();
                if (res.success && res.spreadsheet) {
                    await refreshFilesList();
                    loadSpreadsheet(res.spreadsheet.id);
                }
            } catch (err) {
                console.error('Error duplicating spreadsheet:', err);
            }
        }

        // 9. Toggle Favorite
        async function toggleFavorite(id, event) {
            if (event) event.stopPropagation();

            try {
                const response = await fetch(`{{ url('spreadsheets') }}/${id}/favorite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    }
                });

                const res = await response.json();
                if (res.success) {
                    refreshFilesList();
                }
            } catch (err) {
                console.error('Error toggling favorite:', err);
            }
        }

        // 10. Delete Sheet
        async function deleteSheet(id, title) {
            if (!confirm(`Are you sure you want to delete "${title}"?`)) return;

            try {
                const response = await fetch(`{{ url('spreadsheets') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    }
                });

                const res = await response.json();
                if (res.success) {
                    await refreshFilesList();
                    if (id === currentSheetId) {
                        const firstCard = document.querySelector('.excel-file-card');
                        if (firstCard) {
                            loadSpreadsheet(firstCard.dataset.id);
                        } else {
                            createNewSheet();
                        }
                    }
                }
            } catch (err) {
                console.error('Error deleting sheet:', err);
            }
        }

        // 11. Refresh Files List via AJAX
        async function refreshFilesList() {
            const searchVal = document.getElementById('excelSearchInput')?.value || '';
            const params = new URLSearchParams({
                search: searchVal,
                filter: currentFilter
            });

            try {
                const response = await fetch(`{{ route('spreadsheets.list') }}?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const res = await response.json();

                if (res.success && Array.isArray(res.spreadsheets)) {
                    const container = document.getElementById('excelFilesList');
                    const countText = document.getElementById('excelSheetsCountText');
                    if (countText) countText.innerText = `${res.spreadsheets.length} Files`;

                    if (res.spreadsheets.length === 0) {
                        container.innerHTML = `
                    <div class="excel-empty-state">
                        <i class="fa fa-file-excel-o"></i>
                        <p class="mb-2 fs-7">No spreadsheets match filter.</p>
                    </div>`;
                        return;
                    }

                    let html = '';
                    res.spreadsheets.forEach(sheet => {
                        const isActive = sheet.id == currentSheetId ? 'is-active' : '';
                        const isFav = sheet.is_favorite ? 'is-fav' : '';
                        const starIcon = sheet.is_favorite ? 'fa-star' : 'fa-star-o';

                        html += `
                <div class="excel-file-card ${isActive}" 
                     id="sheetCard_${sheet.id}" 
                     data-id="${sheet.id}"
                     data-title="${sheet.title}"
                     onclick="loadSpreadsheet(${sheet.id})">
                    <div class="excel-card-icon">
                        <i class="fa fa-file-excel"></i>
                    </div>
                    <div class="excel-card-details">
                        <div class="excel-card-title" title="${sheet.title}">${sheet.title}</div>
                        <div class="excel-card-meta">
                            <span><i class="fa fa-clock-o fs-9"></i> ${sheet.updated_at_human}</span>
                            <span>• ${sheet.updater_name}</span>
                        </div>
                    </div>
                    <div class="excel-card-actions" onclick="event.stopPropagation()">
                        <button type="button" class="excel-icon-btn ${isFav}" 
                                onclick="toggleFavorite(${sheet.id}, event)" title="${sheet.is_favorite ? 'Unstar' : 'Star'}">
                            <i class="fa ${starIcon}"></i>
                        </button>
                        <div class="dropdown">
                            <button type="button" class="excel-icon-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item py-2 fs-7" href="javascript:void(0)" onclick="promptRenameSheet(${sheet.id}, '${sheet.title.replace(/'/g, "\\'")}')">
                                        <i class="fa fa-pencil text-primary me-2"></i> Rename
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 fs-7" href="javascript:void(0)" onclick="duplicateSheet(${sheet.id})">
                                        <i class="fa fa-copy text-info me-2"></i> Duplicate
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 fs-7" href="javascript:void(0)" onclick="exportToXlsx()">
                                        <i class="fa fa-download text-success me-2"></i> Download (.xlsx)
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item py-2 fs-7 text-danger" href="javascript:void(0)" onclick="deleteSheet(${sheet.id}, '${sheet.title.replace(/'/g, "\\'")}')">
                                        <i class="fa fa-trash me-2"></i> Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>`;
                    });
                    container.innerHTML = html;
                }
            } catch (err) {
                console.error('Error refreshing files list:', err);
            }
        }

        // 12. Search & Filter Listeners
        let searchDebounce = null;
        document.getElementById('excelSearchInput')?.addEventListener('input', () => {
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(refreshFilesList, 300);
        });

        document.querySelectorAll('.excel-tab-pill').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.excel-tab-pill').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.filter;
                refreshFilesList();
            });
        });

        // 13. Sidebar Collapse Toggle
        function toggleSidebarView() {
            const page = document.getElementById('excelPageContainer');
            if (page) {
                page.classList.toggle('sidebar-collapsed');
            }
            // Resize Luckysheet to fill width
            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 250);
        }

        // 14. Fullscreen Toggle
        function toggleFullScreen() {
            const page = document.getElementById('excelPageContainer');
            const icon = document.getElementById('fullscreenIcon');
            if (!document.fullscreenElement) {
                if (page.requestFullscreen) page.requestFullscreen();
                if (icon) {
                    icon.classList.remove('fa-arrows-alt');
                    icon.classList.add('fa-compress');
                }
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
                if (icon) {
                    icon.classList.remove('fa-compress');
                    icon.classList.add('fa-arrows-alt');
                }
            }
            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 200);
        }

        // 15. Import .xlsx File
        function handleExcelFileImport(input) {
            const file = input.files[0];
            if (!file) return;

            if (!window.LuckyExcel) {
                alert('Excel Import library is still loading, please wait a moment.');
                return;
            }

            updateStatusBadge('saving');

            window.LuckyExcel.transformExcelToLucky(file, async function(exportJson, luckysheetfile) {
                if (!exportJson.sheets || exportJson.sheets.length === 0) {
                    alert('Failed to read the Excel file.');
                    updateStatusBadge('saved');
                    return;
                }

                const fileName = file.name.replace(/\.[^/.]+$/, "");

                // Create a new spreadsheet entry for imported file
                try {
                    const response = await fetch(`{{ route('spreadsheets.create') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            title: fileName,
                            sheet_data: exportJson.sheets
                        })
                    });

                    const res = await response.json();
                    if (res.success && res.spreadsheet) {
                        await refreshFilesList();
                        loadSpreadsheet(res.spreadsheet.id);
                    }
                } catch (e) {
                    console.error('Import save failed:', e);
                    // Fallback load into active sheet
                    initLuckysheet(exportJson.sheets, fileName);
                    saveActiveSpreadsheet();
                }

                input.value = ''; // Reset input
            });
        }

        // 16. Export to .xlsx via ExcelJS
        async function exportToXlsx() {
            if (!window.ExcelJS || !window.saveAs) {
                alert('Export library is still loading. Please wait a few seconds.');
                return;
            }

            const sheets = window.luckysheet ? window.luckysheet.getAllSheets() : [];
            if (!sheets || sheets.length === 0) {
                alert('No data to export.');
                return;
            }

            const workbook = new ExcelJS.Workbook();
            workbook.creator = 'AIN Portal';
            workbook.created = new Date();

            sheets.forEach(sheet => {
                const worksheet = workbook.addWorksheet(sheet.name || 'Sheet1');
                const cellData = sheet.data || [];

                // Populate cell values and simple styles
                for (let r = 0; r < cellData.length; r++) {
                    const rowData = cellData[r] || [];
                    const row = worksheet.getRow(r + 1);

                    for (let c = 0; c < rowData.length; c++) {
                        const cell = rowData[c];
                        if (cell !== null && cell !== undefined) {
                            const excelCell = row.getCell(c + 1);

                            // Formula or value
                            if (cell.f) {
                                excelCell.value = {
                                    formula: cell.f.replace(/^=/, '')
                                };
                            } else if (cell.v !== null && cell.v !== undefined) {
                                excelCell.value = cell.v;
                            } else if (cell.m !== null && cell.m !== undefined) {
                                excelCell.value = cell.m;
                            }

                            // Bold / Italic / Color
                            if (cell.bl || cell.it || cell.fc) {
                                excelCell.font = {
                                    bold: !!cell.bl,
                                    italic: !!cell.it,
                                    color: cell.fc ? {
                                        argb: cell.fc.replace('#', 'FF')
                                    } : undefined
                                };
                            }

                            // Background color
                            if (cell.bg) {
                                excelCell.fill = {
                                    type: 'pattern',
                                    pattern: 'solid',
                                    fgColor: {
                                        argb: cell.bg.replace('#', 'FF')
                                    }
                                };
                            }
                        }
                    }
                    row.commit();
                }
            });

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            const filename = (currentSheetTitle || 'Spreadsheet') + '.xlsx';
            window.saveAs(blob, filename);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initLuckysheet(initialSheetData, currentSheetTitle);

            // Prompt before closing window if unsaved changes exist
            window.addEventListener('beforeunload', function(e) {
                if (hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = 'You have unsaved changes.';
                }
            });

            // Fix "More features" dropdown positioning directly below the More button
            document.addEventListener('click', function(e) {
                const moreBtn = e.target.closest('#luckysheet-icon-morebtn');
                if (moreBtn) {
                    setTimeout(function() {
                        const moreDiv = document.getElementById('luckysheet-icon-morebtn-div');
                        if (moreDiv) {
                            const rect = moreBtn.getBoundingClientRect();
                            moreDiv.style.position = 'fixed';
                            moreDiv.style.top = (rect.bottom + 2) + 'px';
                            moreDiv.style.right = (window.innerWidth - rect.right) + 'px';
                            moreDiv.style.left = 'auto';
                            moreDiv.style.zIndex = '99999';
                        }
                    }, 10);
                }
            });
        });

        // ── Files list ke dropdown ko body me move karo taaki clipping na ho ──
        (function() {
            const originMap = new WeakMap();

            document.addEventListener('show.bs.dropdown', function(e) {
                const toggle = e.target;
                if (!toggle.closest('#excelFilesList')) return;

                const menu = toggle.parentElement.querySelector('.dropdown-menu');
                if (!menu) return;

                originMap.set(menu, toggle.parentElement);
                menu.classList.add('excel-body-dropdown');
                document.body.appendChild(menu);

                const rect = toggle.getBoundingClientRect();
                menu.style.position = 'fixed';
                menu.style.inset = 'auto';
                menu.style.margin = '0';
                menu.style.top = (rect.bottom + 4) + 'px';
                menu.style.left = 'auto';
                menu.style.right = (window.innerWidth - rect.right) + 'px';
                menu.style.transform = 'none';
            });

            document.addEventListener('hidden.bs.dropdown', function(e) {
                const menu = document.querySelector('body > .dropdown-menu.excel-body-dropdown');
                const origin = menu && originMap.get(menu);
                if (menu && origin) {
                    menu.classList.remove('excel-body-dropdown');
                    menu.removeAttribute('style');
                    origin.appendChild(menu);
                }
            });

            document.getElementById('excelFilesList')?.addEventListener('scroll', function() {
                document.querySelectorAll('#excelFilesList [data-bs-toggle="dropdown"].show').forEach(btn => {
                    bootstrap.Dropdown.getInstance(btn)?.hide();
                });
            });
        })();
    </script>
@endsection
