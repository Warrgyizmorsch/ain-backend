@extends('layouts.app')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --ain-navy: #101828;
            --ain-indigo: #4f46e5;
            --ain-blue: #2563eb;
            --ain-surface: #ffffff;
            --ain-border: #e7ecf3;
            --ain-muted: #667085;
        }

        .ain-admin-dashboard {
            background: #f5f7fb;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            min-height: 100vh;
        }

        .ain-admin-dashboard #kt_toolbar {
            background: transparent;
            padding: 18px 0 8px;
        }

        .ain-admin-dashboard #kt_toolbar_container {
            background: linear-gradient(125deg, #101828 0%, #243b72 58%, #4f46e5 100%);
            border-radius: 18px;
            min-height: 96px;
            padding: 22px 28px;
            box-shadow: 0 16px 35px rgba(16, 24, 40, .18);
        }

        .ain-admin-dashboard #kt_toolbar h1,
        .ain-admin-dashboard #kt_toolbar h1 small {
            color: #fff !important;
        }

        .ain-admin-dashboard #kt_toolbar h1 {
            font-size: 1.65rem !important;
            letter-spacing: -.025em;
        }

        .ain-admin-dashboard #kt_toolbar h1 small {
            opacity: .65;
            font-weight: 500 !important;
        }

        .ain-admin-dashboard #kt_toolbar h1 .border-start {
            border-color: rgba(255, 255, 255, .3) !important;
        }

        .ain-admin-dashboard #kt_post {
            padding-top: 20px;
        }

        .ain-admin-dashboard .row.gy-5 {
            row-gap: 22px;
        }

        .ain-admin-dashboard .card {
            border: 1px solid var(--ain-border);
            border-radius: 16px;
            background: var(--ain-surface);
            box-shadow: 0 7px 24px rgba(16, 24, 40, .055) !important;
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .ain-admin-dashboard .card:hover {
            border-color: #d9e1ee;
            box-shadow: 0 12px 32px rgba(16, 24, 40, .085) !important;
        }

        .ain-admin-dashboard .card-header {
            min-height: 72px;
            padding-left: 24px;
            padding-right: 24px;
            border-bottom: 1px solid #eef1f6 !important;
        }

        .ain-admin-dashboard .card-title,
        .ain-admin-dashboard .card-label {
            color: var(--ain-navy) !important;
            letter-spacing: -.018em;
        }

        .ain-admin-dashboard .card-body {
            padding: 24px;
        }

        .ain-admin-dashboard .card-header.bg-danger {
            background: linear-gradient(120deg, #1d4ed8, #4f46e5) !important;
            border-bottom: 0 !important;
        }

        .ain-admin-dashboard .card-header.bg-danger .card-title {
            color: #fff !important;
            font-size: 1.2rem;
        }

        .ain-admin-dashboard .mixed-widget-2-chart.bg-danger {
            background: linear-gradient(120deg, #1d4ed8, #4f46e5) !important;
            height: 145px !important;
        }

        .ain-admin-dashboard .card-p {
            padding: 0 24px 24px;
        }

        .ain-admin-dashboard .card-p>.row {
            gap: 14px;
            flex-wrap: nowrap;
        }

        .ain-admin-dashboard .card-p>.row>.col {
            margin: 0 0 14px !important;
            padding: 20px 22px !important;
            border: 1px solid rgba(15, 23, 42, .055);
            border-radius: 13px !important;
            min-height: 116px;
        }

        .ain-admin-dashboard .card-p h2 {
            font-size: 2rem;
            font-weight: 750;
            letter-spacing: -.045em;
            color: var(--ain-navy);
            margin: 0 !important;
        }

        .ain-admin-dashboard .card-p .svg-icon {
            margin-top: 0 !important;
        }

        .ain-admin-dashboard .card-p a {
            color: #475467 !important;
            font-size: .82rem !important;
        }

        .ain-admin-dashboard .form-select,
        .ain-admin-dashboard .form-control {
            border: 1px solid #dbe2ec;
            border-radius: 9px;
            background-color: #fff;
            min-height: 40px;
        }

        .ain-admin-dashboard .btn {
            border-radius: 9px;
            font-weight: 650;
        }

        .ain-admin-dashboard .btn-primary {
            background: var(--ain-indigo);
            border-color: var(--ain-indigo);
        }

        .ain-admin-dashboard .table {
            margin-bottom: 0;
        }

        .ain-admin-dashboard .table thead th {
            background: #f8fafc;
            color: #667085;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .045em;
            border-bottom: 1px solid #e8edf4;
            padding-top: 14px;
            padding-bottom: 14px;
        }

        .ain-admin-dashboard .table tbody td {
            border-color: #eef2f6;
            padding-top: 15px;
            padding-bottom: 15px;
        }

        .ain-admin-dashboard .table tbody tr:hover {
            background: #fafbff;
        }

        .ain-admin-dashboard canvas {
            max-width: 100%;
        }

        .ain-admin-dashboard .text-muted {
            color: var(--ain-muted) !important;
        }

        .ain-admin-dashboard .badge {
            border-radius: 7px;
        }

        .ain-admin-dashboard #teamMembersContainer {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        @media (max-width: 991.98px) {
            .ain-admin-dashboard #kt_toolbar_container {
                margin: 0 16px;
                padding: 20px;
                min-height: 84px;
            }

            .ain-admin-dashboard .card-p>.row {
                flex-wrap: wrap;
            }

            .ain-admin-dashboard .card-p>.row>.col {
                flex: 1 1 calc(50% - 10px);
            }
        }

        @media (max-width: 575.98px) {
            .ain-admin-dashboard #kt_toolbar h1 {
                font-size: 1.35rem !important;
            }

            .ain-admin-dashboard #kt_toolbar h1 small,
            .ain-admin-dashboard #kt_toolbar h1 .border-start {
                display: none;
            }

            .ain-admin-dashboard .card-header,
            .ain-admin-dashboard .card-body {
                padding-left: 17px;
                padding-right: 17px;
            }

            .ain-admin-dashboard .card-p {
                padding-left: 15px;
                padding-right: 15px;
            }

            .ain-admin-dashboard .card-p>.row>.col {
                flex: 1 1 100%;
                min-width: 100%;
            }
        }

        #teamMembersContainer {
            max-height: 500px;
            /* Set a maximum height for the container */
            overflow-y: auto;
            /* Enable vertical scrolling if content exceeds the container height */
        }

        .member-container {
            white-space: nowrap;
            /* Prevent member names from wrapping to the next line */
            overflow: hidden;
            /* Hide the overflowed content (e.g., long names) */
            text-overflow: ellipsis;
            /* Display an ellipsis (...) for truncated text */
        }

        /* ===== Order Statistics block ===== */
        .ain-stats {
            background: #fff;
            border: 1px solid #e6e8f2;
            border-radius: 24px;
            padding: 28px 30px 30px;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .03);
        }

        .ain-stats__title {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -.2px;
            color: #151a33;
        }

        .ain-stats__sub {
            margin: 4px 0 0;
            font-size: 14px;
            color: #5b6280;
        }

        .ain-stats__grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-top: 20px;
        }

        .ain-stat {
            display: flex;
            flex-direction: column;
            padding: 24px;
            border: 1px solid #e6e8f2;
            border-radius: 20px;
            background: #fff;
        }

        .ain-stat__top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ain-stat__chip {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ain-stat__arrow {
            color: #5b6280;
            display: flex;
        }

        .ain-stat__value {
            margin: 14px 0 0;
            font-size: 44px;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1px;
            color: #151a33;
            font-variant-numeric: tabular-nums;
        }

        .ain-stat__label {
            margin-top: 2px;
            font-size: 16px;
            font-weight: 600;
            color: #151a33;
        }

        .ain-stat__hint {
            margin-top: 4px;
            font-size: 14px;
            color: #5b6280;
        }

        @media (max-width: 1199.98px) {
            .ain-stats__grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .ain-stats {
                padding: 20px 16px;
                border-radius: 18px;
            }

            .ain-stats__grid {
                grid-template-columns: 1fr;
            }

            .ain-stat__value {
                font-size: 36px;
            }
        }

        .ain-stat {
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }

        .ain-stat:hover {
            border-color: #4f46e5;
            box-shadow: 0 8px 24px rgba(79, 70, 229, .14);
            transform: translateY(-2px);
        }

        .ain-stat:hover .ain-stat__arrow {
            color: #4f46e5;
        }

        /* mk 12 10 26 */
        /* ===== Team + Orders by status ===== */
        .ain-chip {
            display: inline-block;
            margin-left: 10px;
            padding: 4px 12px;
            border-radius: 999px;
            background: #eef0fe;
            color: #3730a3;
            font-size: 12px;
            font-weight: 700;
            vertical-align: middle
        }

        .ain-search {
            width: 100%;
            min-height: 40px;
            padding: 0 14px;
            margin-bottom: 12px;
            border: 1px solid #dbe2ec;
            border-radius: 10px;
            font-size: 14px;
            color: #151a33;
            background: #fff;
            font-family: inherit
        }

        .ain-search:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .15);
            outline: none
        }

        .ain-trow {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 10px 12px;
            margin: 0 0 2px;
            border: 1px solid transparent;
            border-radius: 14px;
            background: transparent;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s ease, border-color .15s ease
        }

        .ain-trow:hover {
            background: #f6f7fd
        }

        .ain-trow.sel {
            background: #eef0fe;
            border-color: #4f46e5
        }

        .ain-trow:focus-visible {
            outline: 2px solid #4f46e5;
            outline-offset: 2px
        }

        .ain-tav {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            border-radius: 50%;
            overflow: hidden;
            background: #eef0f6
        }

        .ain-tav img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block
        }

        .ain-tname {
            flex: 1;
            font-size: 14px;
            font-weight: 600;
            color: #151a33
        }

        .ain-trow .ain-chk {
            opacity: 0;
            transition: opacity .15s ease
        }

        .ain-trow.sel .ain-chk {
            opacity: 1
        }

        .ain-nomatch {
            padding: 24px 8px;
            text-align: center;
            font-size: 14px;
            color: #667085
        }

        .ain-seg {
            display: flex;
            background: #f4f5fa;
            border-radius: 10px;
            padding: 4px;
            gap: 2px
        }

        .ain-seg button {
            min-height: 34px;
            padding: 0 16px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: #5b6280;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit
        }

        .ain-seg button.on {
            background: #fff;
            color: #4338ca;
            box-shadow: 0 1px 3px rgba(21, 26, 51, .12)
        }

        .ain-os {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
            align-items: center
        }

        .ain-os__chart {
            min-width: 0
        }

        .ain-os__chart svg {
            display: block;
            width: 100%;
            max-width: 460px;
            margin: 0 auto;
            font-family: inherit
        }

        .ain-os__side {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 10px
        }

        .ain-sl {
            transition: transform .28s ease, opacity .2s ease;
            cursor: pointer
        }

        .ain-detail {
            padding: 18px 20px;
            border-radius: 16px;
            background: #0e1330;
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 4px
        }

        .ain-detail__name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #c9cee6
        }

        .ain-detail__pct {
            font-size: 40px;
            font-weight: 800;
            letter-spacing: -1.2px;
            line-height: 1.1;
            font-variant-numeric: tabular-nums
        }

        .ain-detail__txt {
            font-size: 13px;
            color: #aeb5d3
        }

        .ain-detail__rank {
            font-size: 12px;
            color: #8f97bc;
            margin-top: 2px
        }

        .ain-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0
        }

        .ain-lrow {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e6e8f2;
            border-radius: 14px;
            background: #fff;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s ease, border-color .15s ease, transform .15s ease
        }

        .ain-lrow:hover,
        .ain-lrow.on,
        .ain-lrow:focus-visible {
            background: #f8f9fe;
            border-color: #4f46e5;
            transform: translateX(3px);
            outline: none
        }

        .ain-lrow__top {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            width: 100%
        }

        .ain-lrow__name {
            flex: 1;
            font-weight: 600;
            color: #151a33
        }

        .ain-lrow__cnt {
            color: #667085;
            font-size: 12px;
            font-variant-numeric: tabular-nums
        }

        .ain-lrow__pct {
            min-width: 52px;
            text-align: right;
            font-weight: 800;
            color: #151a33;
            font-variant-numeric: tabular-nums
        }

        .ain-lrow__bar {
            display: block;
            width: 100%;
            height: 6px;
            border-radius: 999px;
            background: #f0f1f8;
            overflow: hidden
        }

        @media (max-width:575.98px) {
            .ain-detail__pct {
                font-size: 32px
            }
        }
    </style>
    @if (auth()->check() && auth()->user()->role_id == 1)
        <div class="content d-flex flex-column flex-column-fluid ain-admin-dashboard" id="kt_content">
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                        data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard
                            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                            <small class="text-muted fs-7 fw-bold my-1 ms-1">Assingment In Need</small>
                        </h1>
                    </div>
                    <!-- <div class="d-flex align-items-center py-1">
                                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app" id="kt_toolbar_primary_button">Create</a>
                                       </div> -->
                </div>
            </div>

            <div class="post d-flex flex-column-fluid" id="kt_post">
                <div id="kt_content_container" class="container-xxl">
                    <div class="row gy-5 g-xl-8" style="margin-bottom:20px">
                        <div class="col-xl-12">
                            <div class="ain-stats">
                                <h2 class="ain-stats__title">Order statistics</h2>
                                <p class="ain-stats__sub">Overview of orders, users and feedback</p>

                                <div class="ain-stats__grid">

                                    {{-- Total orders --}}
                                    <div class="ain-stat">
                                        <div class="ain-stat__top">
                                            <div class="ain-stat__chip" style="background:#FFF1CC;color:#8A5A00;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                    stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M21 8l-9-5-9 5v8l9 5 9-5z M3 8l9 5 9-5 M12 13v8"></path>
                                                </svg>
                                            </div>
                                            <span class="ain-stat__arrow">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M7 17L17 7 M8 7h9v9"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="ain-stat__value">{{ number_format((float) $totalOrderCount) }}</div>
                                        <div class="ain-stat__label">Total orders</div>
                                        <div class="ain-stat__hint">All time</div>
                                    </div>

                                    {{-- Total users --}}
                                    <div class="ain-stat">
                                        <div class="ain-stat__top">
                                            <div class="ain-stat__chip" style="background:#E0EAFF;color:#2B4FC4;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                    stroke-linejoin="round" aria-hidden="true">
                                                    <path
                                                        d="M16 20v-1a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v1 M9.5 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z M21 20v-1a4 4 0 0 0-3-3.9 M16 4.1a3.5 3.5 0 0 1 0 6.8">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span class="ain-stat__arrow">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M7 17L17 7 M8 7h9v9"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="ain-stat__value">{{ number_format((float) $totalUserCount) }}</div>
                                        <div class="ain-stat__label">Total users</div>
                                        <div class="ain-stat__hint">All time</div>
                                    </div>

                                    {{-- Today's orders --}}
                                    <div class="ain-stat">
                                        <div class="ain-stat__top">
                                            <div class="ain-stat__chip" style="background:#FFE1EA;color:#A3224B;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                    stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M3 9V6h18v3a3 3 0 0 0 0 6v3H3v-3a3 3 0 0 0 0-6z M13 6v12">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span class="ain-stat__arrow">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M7 17L17 7 M8 7h9v9"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="ain-stat__value">{{ number_format((float) $todayOrdersCount) }}</div>
                                        <div class="ain-stat__label">Today's orders</div>
                                        <div class="ain-stat__hint">
                                            {{ (float) $todayOrdersCount > 0 ? 'Received today' : 'No orders yet today' }}
                                        </div>
                                    </div>

                                    {{-- Feedback orders --}}
                                    <div class="ain-stat">
                                        <div class="ain-stat__top">
                                            <div class="ain-stat__chip" style="background:#D9F5E8;color:#0F6B43;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                    stroke-linejoin="round" aria-hidden="true">
                                                    <path
                                                        d="M12 3l2.8 5.7 6.2.9-4.5 4.4 1 6.2-5.5-2.9-5.5 2.9 1-6.2L3 9.6l6.2-.9z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span class="ain-stat__arrow">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M7 17L17 7 M8 7h9v9"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="ain-stat__value">{{ number_format((float) $feedbackCount) }}</div>
                                        <div class="ain-stat__label">Feedback orders</div>
                                        <div class="ain-stat__hint">With feedback</div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-5 g-xl-8">
                        <div class="col-xl-4">
                            <div class="card card-xl-stretch mb-xl-8">
                                <div class="card-header border-0">
                                    <h3 class="card-title fw-bolder text-dark">Team <span id="teamRoleChip"
                                            class="ain-chip">Marketing Team</span></h3>
                                    <div class="card-toolbar">
                                        <button type="button"
                                            class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <span class="svg-icon svg-icon-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                    viewBox="0 0 24 24">
                                                    <g stroke="none" stroke-width="1" fill="none"
                                                        fill-rule="evenodd">
                                                        <rect x="5" y="5" width="5" height="5" rx="1"
                                                            fill="#000000" />
                                                        <rect x="14" y="5" width="5" height="5" rx="1"
                                                            fill="#000000" opacity="0.3" />
                                                        <rect x="5" y="14" width="5" height="5" rx="1"
                                                            fill="#000000" opacity="0.3" />
                                                        <rect x="14" y="14" width="5" height="5" rx="1"
                                                            fill="#000000" opacity="0.3" />
                                                    </g>
                                                </svg>
                                            </span>
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px"
                                            data-kt-menu="true">


                                            <div class="card card-xl-stretch mb-xl-8">
                                                <div class="card-header border-0">
                                                    <h3 class="card-title fw-bolder text-dark" style="margin-top: 20px;">
                                                        Role
                                                    </h3>
                                                    <div class="card-toolbar">
                                                        <select id="roleSelector"
                                                            class="form-select form-select-sm form-select-white">
                                                            <option value="" selected disabled>Select a Role</option>
                                                            <option value="1">Super Admin</option>
                                                            <option value="3">Sub Admin</option>
                                                            <option value="4">Marketing Team</option>
                                                            <option value="5">Project Team</option>
                                                            <option value="6">Writer TL</option>
                                                            <option value="7">Sub Writer</option>
                                                            <option value="8">Writer Admin</option>
                                                            <!-- Add options for other roles as needed -->
                                                        </select>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-2">
                                    <input id="teamSearch" type="search" class="ain-search"
                                        placeholder="Search team member" aria-label="Search team member">

                                    <!-- show the team member here -->
                                    <div id="teamMembersContainer"></div>
                                    <div id="teamNoMatch" class="ain-nomatch d-none">No member matches your search.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <div class="card card-xl-stretch mb-5 mb-xl-8">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder fs-3 mb-1">Orders by status</span>
                                        <span class="text-muted fw-bold fs-7">More than {{ $currentMonthOrdersCount }}+ new
                                            orders
                                            this month. Hover a slice to explore.</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <div class="ain-seg">
                                            <button type="button" id="ainBtn3d" class="on">3D</button>
                                            <button type="button" id="ainBtnFlat">Flat</button>
                                        </div>
                                        <div class="d-none" data-kt-buttons="true">
                                            <a class="btn btn-sm" id="kt_charts_widget_6_sales_btn"></a>
                                            <a class="btn btn-sm" id="kt_charts_widget_6_expenses_btn"></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="ain-os">
                                        <div class="ain-os__chart">
                                            <svg id="ainPie" viewBox="0 0 440 330" role="img"
                                                aria-label="Orders by status pie chart"></svg>
                                        </div>
                                        <div class="ain-os__side">
                                            <div class="ain-detail">
                                                <div class="ain-detail__name"><span id="ainDDot"
                                                        class="ain-dot"></span><span id="ainDName"></span></div>
                                                <div class="ain-detail__pct" id="ainDPct"></div>
                                                <div class="ain-detail__txt" id="ainDCount"></div>
                                                <div class="ain-detail__txt" id="ainDDesc"></div>
                                                <div class="ain-detail__rank" id="ainDRank"></div>
                                            </div>
                                            <div id="ainRows" style="display:flex;flex-direction:column;gap:10px"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-5 g-xl-8">
                        <div class="col-xl-12">
                            <form id="dateForm" action="{{ route('dashboard') }}" method="GET">
                                @csrf
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label for="dateSelector" class="form-label">Select Month and Year:</label>
                                        <input type="month" id="dateSelector" name="selectedDate"
                                            class="form-control">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit/Reset</button>
                            </form>

                        </div>

                        <div class="col-xl-6">

                            <div id="piechart_3d-2" style="width: 100%; height: 400px;"></div>
                        </div>
                        <div class="col-xl-6">

                            <div id="piechart_3d-3" style="width: 100%; height: 400px;"></div>
                        </div>

                    </div>

                    <div class="row gy-5 g-xl-8 mt-2">
                        <div class="col-xl-6">
                            <div class="card card-xl-stretch mb-5">
                                <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-start">

                                    <!-- Left Side -->
                                    <h3 class="card-title align-items-start flex-column m-0">
                                        <span class="card-label fw-bolder fs-3 mb-1">
                                            Inquiry Tracking
                                        </span>
                                        <span class="text-muted fw-bold fs-7">
                                            User Growth vs Retention
                                        </span>
                                    </h3>

                                    <!-- Right Side Button -->
                                    {{-- <div class="card-toolbar">
										<a href="{{ route('analytics.report', 'inquiry') }}"
											class="btn btn-sm btn-light-primary">
											<i class="fa fa-eye"></i>
										</a>
									</div> --}}

                                </div>

                                <div class="card-body">
                                    <canvas id="userTrackingChart" style="height: 350px; width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card card-xl-stretch mb-5">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder fs-3 mb-1">Lead Tracking</span>
                                        <span class="text-muted fw-bold fs-7">Worked vs Converted Leads</span>
                                    </h3>
                                    <div class="card-toolbar gap-3">
                                        <!-- Eye Button Added Here -->
                                        <a href="/analytics-report/lead-tracking?from_date=2026-05-15&to_date={{ now()->format('Y-m-d') }}&name=&country="
                                            class="btn btn-sm btn-light-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <select id="employeeFilter"
                                            class="form-select form-select-solid form-select-sm w-125px">
                                            <option value="all">All Employees</option>
                                            @if (isset($employees))
                                                @foreach ($employees->where('role_id', 4) as $emp)
                                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                        <select id="dateRangeFilter"
                                            class="form-select form-select-solid form-select-sm w-125px">
                                            <option value="today">Today</option>
                                            <option value="yesterday">Yesterday</option>
                                            <option value="last_week">Last Week</option>
                                            <option value="last_month" selected>Last Month</option>
                                            <option value="custom">Custom</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="customDateSection" class="px-8 mt-3 d-none">
                                    <div class="d-flex gap-2 align-items-center mb-3">
                                        <input type="date" id="startDate" class="form-control form-control-sm">
                                        <input type="date" id="endDate" class="form-control form-control-sm">
                                        <button id="applyCustomDate" class="btn btn-sm btn-primary">Go</button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <canvas id="leadTrackingChart" style="height: 350px; width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Due order list --}}
                    <div class="card card-xl-stretch mb-5 shadow-sm">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Pending Payments</span>
                                <span class="text-muted mt-1 fw-bold fs-7">Orders sorted by highest due balance</span>
                            </h3>
                        </div>
                        <div class="card-body py-3">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bolder text-muted bg-light">
                                            <th class="ps-4 rounded-start min-w-100px">Order ID</th>
                                            <th class="min-w-150px">Client</th>
                                            <th class="min-w-100px">Order Date</th>
                                            <th class="min-w-125px">Last Payment</th>
                                            <th>Total Amount</th>
                                            <th>Received</th>
                                            <th>Due Amount</th>
                                            <th class="text-end pe-4 rounded-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dueOrdersList as $order)
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="text-dark fw-bolder">{{ $order->order_id }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        @if (optional($order->user)->name)
                                                            <span
                                                                class="text-dark fw-bold">{{ $order->user->name }}</span>
                                                        @else
                                                            <div>
                                                                <span class="badge badge-light-danger fs-7 fw-bold"
                                                                    style="width: fit-content; display: inline-block;">User
                                                                    Was
                                                                    Deleted</span>
                                                            </div>
                                                        @endif
                                                        <span
                                                            class="text-muted fs-7">{{ optional($order->user)->mobile_no }}</span>

                                                    </div>
                                                </td>

                                                <td>
                                                    <span class="text-dark fw-bold d-block fs-7">
                                                        @php
                                                            // ERROR FIX 1: Order date ko clean kiya
                                                            $cleanOrderDate = $order->order_date
                                                                ? trim(
                                                                    str_replace(
                                                                        ' hursday',
                                                                        'Thursday',
                                                                        $order->order_date,
                                                                    ),
                                                                )
                                                                : null;
                                                        @endphp
                                                        {{ $cleanOrderDate ? \Carbon\Carbon::parse($cleanOrderDate)->format('d M Y') : 'N/A' }}
                                                    </span>
                                                </td>

                                                <td>
                                                    @php
                                                        $lastPayment = $order->payment->first();
                                                    @endphp

                                                    @if ($lastPayment && $lastPayment->payment_date)
                                                        <span class="text-dark fw-bold d-block fs-7">
                                                            @php
                                                                $cleanPaymentDate = trim($lastPayment->payment_date);

                                                                $cleanPaymentDate = str_replace(
                                                                    [
                                                                        'onday',
                                                                        'uesday',
                                                                        'ednesday',
                                                                        'hursday',
                                                                        'riday',
                                                                        'aturday',
                                                                        'unday',
                                                                        'monday',
                                                                        'tuesday',
                                                                        'wednesday',
                                                                        'thursday',
                                                                        'friday',
                                                                        'saturday',
                                                                        'sunday',
                                                                    ],
                                                                    [
                                                                        'Monday',
                                                                        'Tuesday',
                                                                        'Wednesday',
                                                                        'Thursday',
                                                                        'Friday',
                                                                        'Saturday',
                                                                        'Sunday',
                                                                        'Monday',
                                                                        'Tuesday',
                                                                        'Wednesday',
                                                                        'Thursday',
                                                                        'Friday',
                                                                        'Saturday',
                                                                        'Sunday',
                                                                    ],
                                                                    $cleanPaymentDate,
                                                                );

                                                                try {
                                                                    $formattedPaymentDate = \Carbon\Carbon::parse(
                                                                        $cleanPaymentDate,
                                                                    )->format('d M Y');
                                                                } catch (\Exception $e) {
                                                                    $formattedPaymentDate = '-';
                                                                }
                                                            @endphp

                                                            {{ $formattedPaymentDate }}
                                                        </span>

                                                        <span
                                                            class="text-muted fs-8">£{{ $lastPayment->paid_amount }}</span>
                                                    @else
                                                        <span class="badge badge-light-warning fs-8">No Payment Yet</span>
                                                    @endif
                                                </td>

                                                <td>£{{ $order->amount }}</td>
                                                <td class="text-success">£{{ $order->received_amount }}</td>
                                                <td>
                                                    <span class="badge badge-light-danger fw-bolder fs-6">
                                                        £{{ (float) $order->amount - (float) $order->received_amount }}
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="{{ route('orders.payment.form', $order->id) }}"
                                                        class="btn btn-icon btn-success btn-sm position-relative"
                                                        title="Update Payment">
                                                        <i class="fa fa-money"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $dueOrdersList->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>

                    <div class="row gy-5 g-xl-8 mt-2">
                        <div class="col-xl-6">
                            <div class="card card-flush shadow-sm mb-5">
                                <div class="card-header pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder text-dark">Clients by Country</span>
                                        <span class="text-muted mt-1 fw-bold fs-7">Global User Distribution</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <a href="{{ route('analytics.report', 'country') }}"
                                            class="btn btn-sm btn-light-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-center" style="height: 300px;">
                                        <canvas id="countryChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card card-flush shadow-sm h-md-100 mb-5">

                                <!-- CARD HEADER -->
                                <div class="card-header pt-5 mb-3">

                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder text-dark fs-3">
                                            SEO Leads Overview
                                        </span>

                                        <span class="text-muted mt-1 fw-bold fs-7">
                                            Conversion status of frontend SEO leads
                                        </span>
                                    </h3>

                                    <div class="card-toolbar">

                                        <!-- VIEW BUTTON -->
                                        <a href="{{ route('analytics.report', ['type' => 'seo', 'from_date' => $seoReportFrom, 'to_date' => $seoReportTo]) }}"
                                            class="btn btn-sm btn-light-primary me-3">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <!-- FILTER FORM -->
                                        <form method="GET" action="{{ url()->current() }}"
                                            class="d-flex align-items-center" id="seoFilterForm">

                                            <select name="seo_filter"
                                                class="form-select form-select-sm form-select-solid w-125px me-2"
                                                onchange="toggleCustomDate(this.value)">

                                                <option value="last_year"
                                                    {{ $seoFilter == 'last_year' ? 'selected' : '' }}>
                                                    Last 1 Year
                                                </option>

                                                <option value="last_month"
                                                    {{ $seoFilter == 'last_month' ? 'selected' : '' }}>
                                                    Last Month
                                                </option>

                                                <option value="last_week"
                                                    {{ $seoFilter == 'last_week' ? 'selected' : '' }}>
                                                    Last Week
                                                </option>

                                                <option value="custom" {{ $seoFilter == 'custom' ? 'selected' : '' }}>
                                                    Custom
                                                </option>
                                            </select>

                                            <!-- CUSTOM DATE -->
                                            <div id="customDateWrap"
                                                class="d-flex align-items-center {{ $seoFilter == 'custom' ? '' : 'd-none' }}">

                                                <input type="date" name="seo_from"
                                                    class="form-control form-control-sm me-1" value="{{ $seoFrom }}"
                                                    style="width: 110px;">

                                                <span class="me-1">-</span>

                                                <input type="date" name="seo_to"
                                                    class="form-control form-control-sm me-2" value="{{ $seoTo }}"
                                                    style="width: 110px;">
                                            </div>

                                            <button type="submit" class="btn btn-sm btn-primary">
                                                Apply
                                            </button>

                                        </form>
                                    </div>
                                </div>

                                <!-- CARD BODY -->
                                <div class="card-body py-3 d-flex flex-column align-items-center">

                                    <!-- CHART -->
                                    <div style="height: 230px; width: 230px; position: relative;">

                                        <canvas id="seoLeadsChart"></canvas>

                                        <div
                                            style="
							position: absolute;
							top: 50%;
							left: 50%;
							transform: translate(-50%, -50%);
							text-align: center;
						">
                                            <span class="fs-1 fw-bolder text-dark">
                                                {{ $totalSeoLeads }}
                                            </span>

                                            <br>

                                            <span class="fs-8 fw-bold text-muted">
                                                Total Leads
                                            </span>
                                        </div>
                                    </div>

                                    <!-- LEGENDS -->
                                    <div class="d-flex flex-wrap justify-content-center mt-6"
                                        style="gap: 12px; width: 100%;">

                                        @php
                                            $colors = [
                                                'Converted' => '#50CD89',
                                                'Not Converted' => '#F1416C',
                                            ];
                                        @endphp

                                        @foreach ($seoChartData as $label => $val)
                                            <div class="border border-dashed border-gray-300 rounded py-2 px-3 d-flex align-items-center"
                                                style="flex: 1 1 calc(50% - 12px); min-width: 130px;">

                                                <span class="bullet bullet-dot me-2"
                                                    style="
												background-color: {{ $colors[$label] }} !important;
												height: 10px;
												width: 10px;
											">
                                                </span>

                                                <span class="fw-bolder text-gray-800 fs-7">
                                                    {{ $label }}
                                                </span>

                                                <span class="text-gray-500 ms-auto fw-bold fs-8">
                                                    ({{ $val }})
                                                </span>

                                            </div>
                                        @endforeach

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gy-5 g-xl-8 mt-2">
                        <!--begin::Col-->
                        <div class="col-xl-6">
                            <!--begin::Card-->
                            <div class="card card-flush h-md-100">
                                <!--begin::Card header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <h3 class="fw-bolder text-dark">Lead Sources Overview</h3>
                                        <span class="text-muted fw-bold fs-7">Distribution of leads by source</span>
                                        {{-- <div class="card-toolbar">
											<a href="{{ route('analytics.report', 'sources') }}"
												class="btn btn-sm btn-light-primary">
												<i class="fa fa-eye"></i>
											</a>
										</div> --}}
                                    </div>
                                    <!--end::Title-->

                                    <!--begin::Toolbar-->
                                    <div class="card-toolbar d-flex align-items-center">

                                        <a href="{{ route('analytics.report', 'sources') }}"
                                            class="btn btn-sm btn-light-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <!-- Custom Date Inputs (Hidden by default) -->
                                        <div id="customDateInputs" class="d-none me-2 d-flex align-items-center">
                                            <input type="date"
                                                class="form-control form-control-sm form-control-solid w-125px me-2"
                                                id="source_from" name="source_from">
                                            <input type="date"
                                                class="form-control form-control-sm form-control-solid w-125px me-2"
                                                id="source_to" name="source_to">
                                        </div>

                                        <select id="leadSourceFilter"
                                            class="form-select form-select-solid form-select-sm w-125px me-2">
                                            <option value="1_year" selected>Last 1 Year</option>
                                            <option value="6_months">Last 6 Months</option>
                                            <option value="1_month">Last 1 Month</option>
                                            <option value="custom">Custom</option> <!-- Naya Custom Option -->
                                        </select>
                                        <button type="button" class="btn btn-sm btn-primary">Apply</button>
                                    </div>
                                    <!--end::Toolbar-->
                                </div>
                                <!--end::Card header-->

                                <!--begin::Card body-->
                                <div class="card-body pt-0 pb-5">
                                    <!--begin::Chart Wrapper-->
                                    <div class="position-relative d-flex flex-center mb-7">
                                        <!-- Canvas for Chart.js -->
                                        <canvas id="leadSourceChart" style="max-height: 250px; width: 100%;"></canvas>

                                        <!-- Absolute Center Text -->
                                        <div class="position-absolute text-center d-flex flex-column justify-content-center"
                                            style="top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;">
                                            <span class="fs-1 fw-bolder text-dark">{{ $totalSourceLeads }}</span>
                                            <span class="fs-7 fw-bold text-muted">Total Leads</span>
                                        </div>
                                    </div>
                                    <!--end::Chart Wrapper-->

                                    <!--begin::Custom Legend-->
                                    <div class="row g-3">
                                        <!-- Source 106 -->
                                        <div class="col-6 col-md-4">
                                            <div
                                                class="border border-dashed rounded px-3 py-2 d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <span class="bullet bullet-dot bg-success me-2 h-10px w-10px"></span>
                                                    <span class="fw-bolder fs-6 text-dark">Source 106</span>
                                                </div>
                                                <span
                                                    class="text-muted fw-bold">({{ $sourceData['Source 106'] ?? 0 }})</span>
                                            </div>
                                        </div>

                                        <!-- Source 1696 -->
                                        <div class="col-6 col-md-4">
                                            <div
                                                class="border border-dashed rounded px-3 py-2 d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <span class="bullet bullet-dot bg-primary me-2 h-10px w-10px"></span>
                                                    <span class="fw-bolder fs-6 text-dark">Source 1696</span>
                                                </div>
                                                <span
                                                    class="text-muted fw-bold">({{ $sourceData['Source 1696'] ?? 0 }})</span>
                                            </div>
                                        </div>

                                        <!-- Source insta -->
                                        <div class="col-6 col-md-4">
                                            <div
                                                class="border border-dashed rounded px-3 py-2 d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <span class="bullet bullet-dot bg-warning me-2 h-10px w-10px"></span>
                                                    <span class="fw-bolder fs-6 text-dark">Source insta</span>
                                                </div>
                                                <span
                                                    class="text-muted fw-bold">({{ $sourceData['Source insta'] ?? 0 }})</span>
                                            </div>
                                        </div>

                                        <!-- Source facebook -->
                                        <div class="col-6 col-md-4">
                                            <div
                                                class="border border-dashed rounded px-3 py-2 d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <span class="bullet bullet-dot bg-danger me-2 h-10px w-10px"></span>
                                                    <span class="fw-bolder fs-6 text-dark">Source facebook</span>
                                                </div>
                                                <span
                                                    class="text-muted fw-bold">({{ $sourceData['Source facebook'] ?? 0 }})</span>
                                            </div>
                                        </div>

                                        <!-- Source whatsapp -->
                                        <div class="col-6 col-md-4">
                                            <div
                                                class="border border-dashed rounded px-3 py-2 d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <span class="bullet bullet-dot bg-secondary me-2 h-10px w-10px"></span>
                                                    <span class="fw-bolder fs-6 text-dark">Source whatsapp</span>
                                                </div>
                                                <span
                                                    class="text-muted fw-bold">({{ $sourceData['Source whatsapp'] ?? 0 }})</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Custom Legend-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end::Col-->

                        <div class="col-xl-6">
                            <div class="card card-flush shadow-sm h-md-100 mb-5">
                                <div class="card-header pt-5 mb-3">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder text-dark fs-3">Conversion Ratio</span>
                                        <span class="text-muted mt-1 fw-bold fs-7">Orders vs Active Leads</span>
                                    </h3>
                                    {{-- <div class="card-toolbar">
										<a href="{{ route('analytics.report', 'conversion') }}"
											class="btn btn-sm btn-light-primary">
											<i class="fa fa-eye"></i>
										</a>
									</div> --}}

                                    <div class="card-toolbar">
                                        {{-- <a href="{{ route('analytics.report', 'conversion') }}"
											class="btn btn-sm btn-light-primary">
											<i class="fa fa-eye"></i>
										</a> --}}
                                        <select id="conversionFilter"
                                            class="form-select form-select-sm form-select-solid w-150px">
                                            <option value="current_month" selected>Current Month</option>
                                            <option value="last_month">Last Month</option>
                                            <option value="last_week">Last Week</option>
                                            <option value="custom">Custom</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="conversionCustomDateWrap" class="px-8 d-none">
                                    <div class="d-flex gap-2 align-items-center mb-3">
                                        <input type="date" id="conversionFrom" class="form-control form-control-sm">
                                        <input type="date" id="conversionTo" class="form-control form-control-sm">
                                        <button type="button" id="applyConversionDate"
                                            class="btn btn-sm btn-primary">Go</button>
                                    </div>
                                </div>

                                <div class="card-body py-3 d-flex flex-column align-items-center justify-content-center">

                                    <div style="height: 230px; width: 230px; position: relative;">
                                        <canvas id="conversionRatioChart"></canvas>

                                        <div
                                            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                            <span class="fs-1 fw-bolder text-success"
                                                id="conversionRatioText">{{ $conversionRatio }}%</span><br>
                                            <span class="fs-8 fw-bold text-muted">Converted</span>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap justify-content-center mt-6"
                                        style="gap: 12px; width: 100%;">

                                        <div class="border border-dashed border-gray-300 rounded py-2 px-3 d-flex align-items-center"
                                            style="flex: 1 1 calc(50% - 12px); min-width: 140px;">
                                            <span class="bullet bullet-dot me-2"
                                                style="background-color: #50CD89 !important; height: 10px; width: 10px;"></span>
                                            <span class="fw-bolder text-gray-800 fs-7">Converted Orders</span>
                                            <span class="text-gray-500 ms-auto fw-bold fs-8"
                                                id="convertedLeadsText">({{ $convertedOrders1Year }})</span>
                                        </div>

                                        <div class="border border-dashed border-gray-300 rounded py-2 px-3 d-flex align-items-center"
                                            style="flex: 1 1 calc(50% - 12px); min-width: 140px;">
                                            <span class="bullet bullet-dot me-2"
                                                style="background-color: #E4E6EF !important; height: 10px; width: 10px;"></span>
                                            <span class="fw-bolder text-gray-800 fs-7">Active Leads</span>
                                            <span class="text-gray-500 ms-auto fw-bold fs-8"
                                                id="notConvertedLeadsText">({{ $notConvertedCount }})</span>
                                        </div>

                                    </div>

                                    <div class="mt-4 w-100 text-center">
                                        <span class="badge badge-light-primary fs-6 fw-bolder px-4 py-2">
                                            Total : <span
                                                id="totalLeadsText">{{ $convertedOrders1Year + $notConvertedCount }}</span>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <!-- ... (previous content) ... -->

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            $(document).ready(function() {
                // ... aapka purana team member fetch karne ka logic ...
            });
        </script>

        <script>
            $(document).ready(function() {
                var canvas = document.getElementById('leadSourceChart');

                if (canvas) {
                    var ctx = canvas.getContext('2d');

                    // Controller se data fetch kar rahe hain
                    var chartData = [
                        {{ $sourceData['Source 106'] ?? 0 }},
                        {{ $sourceData['Source 1696'] ?? 0 }},
                        {{ $sourceData['Source insta'] ?? 0 }},
                        {{ $sourceData['Source facebook'] ?? 0 }},
                        {{ $sourceData['Source whatsapp'] ?? 0 }}
                    ];

                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Source 106', 'Source 1696', 'Source insta', 'Source facebook',
                                'Source whatsapp'
                            ],
                            datasets: [{
                                data: chartData,
                                backgroundColor: [
                                    '#50CD89', // Success (Green) for 106
                                    '#009EF7', // Primary (Blue) for 1696
                                    '#FFC700', // Warning (Yellow) for insta
                                    '#F1416C', // Danger (Red) for facebook
                                    '#E4E6EF' // Secondary (Gray) for whatsapp
                                ],
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '75%', // Center hole space banata hai
                            plugins: {
                                legend: {
                                    display: false // Default legend hide kar rahe hain kyunki custom HTML boxes banaye hain
                                },
                                tooltip: {
                                    enabled: true
                                }
                            }
                        }
                    });
                }
            });
            document.getElementById('leadSourceFilter').addEventListener('change', function() {
                var dateInputs = document.getElementById('customDateInputs');
                if (this.value === 'custom') {
                    dateInputs.classList.remove('d-none'); // Show inputs
                } else {
                    dateInputs.classList.add('d-none'); // Hide inputs
                }
            });
        </script>

        {{-- country wise user distribution ke liye chart --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const countryCanvas = document.getElementById('countryChart');

                if (!countryCanvas) {
                    return;
                }

                const countryData = @json($countryStats ?? ['labels' => [], 'data' => []]);

                if (!countryData.labels.length || !countryData.data.length) {
                    console.log('No country data found');
                    return;
                }

                const baseColors = [
                    '#0095E8', '#50CD89', '#F1416C', '#FFC700', '#7239EA',
                    '#009EF7', '#181C32', '#3F4254', '#E4E6EF', '#47BE7D',
                    '#FF9F43', '#EA5455', '#28C76F', '#7367F0'
                ];

                const backgroundColors = countryData.data.map((_, index) => {
                    return baseColors[index % baseColors.length];
                });

                new Chart(countryCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: countryData.labels,
                        datasets: [{
                            data: countryData.data,
                            backgroundColor: backgroundColors,
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                display: true,
                                labels: {
                                    usePointStyle: true,
                                    padding: 10,
                                    font: {
                                        size: 12,
                                        family: 'Poppins'
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.raw + ' Users';
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>

        <script>
            // 1. Chart ko globally define kiya taaki baad me track ho sake
            let userTrackingChartInstance = null;

            // 2. DOM load hone ke baad hi chart render ho (Errors se bachne ke liye)
            document.addEventListener('DOMContentLoaded', function() {
                const canvasElem = document.getElementById('userTrackingChart');

                if (canvasElem) {
                    const ctx = canvasElem.getContext('2d');

                    // 3. OPTIMIZATION: Agar purana chart pehle se bana hai, toh use destroy karo (No Memory Leaks / No Flickering)
                    if (userTrackingChartInstance) {
                        userTrackingChartInstance.destroy();
                    }

                    // 4. OPTIMIZATION: JSON data ko pehle variables me store kar liya (Clean parsing)
                    const chartLabels = {!! json_encode($chartData['labels'] ?? []) !!};
                    const newUsersData = {!! json_encode($chartData['newUsers'] ?? []) !!};
                    const retainedUsersData = {!! json_encode($chartData['retained'] ?? []) !!};
                    const loyalUsersData = {!! json_encode($chartData['loyal'] ?? []) !!};

                    userTrackingChartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: chartLabels,
                            datasets: [{
                                    label: 'New Users',
                                    data: newUsersData,
                                    backgroundColor: '#4A69FF', // Blue
                                    borderRadius: 4,
                                    barThickness: 12,
                                },
                                {
                                    label: 'Retained Users',
                                    data: retainedUsersData,
                                    backgroundColor: '#E4E6EF', // Grey
                                    borderRadius: 4,
                                    barThickness: 12,
                                },
                                {
                                    label: 'Loyal Customers',
                                    data: loyalUsersData,
                                    backgroundColor: '#50CD89', // Green
                                    borderRadius: 4,
                                    barThickness: 12,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            // 5. OPTIMIZATION: Tooltip interaction ko smooth kiya
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value >= 1000 ? (value / 1000) + 'K' : value;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>

        <script>
            // SEO Custom Date Hide/Show function (Global scope for inline onchange)
            function toggleCustomDate(val) {
                const wrap = document.getElementById('customDateWrap');
                if (val === 'custom') {
                    wrap.classList.remove('d-none');
                } else {
                    wrap.classList.add('d-none');
                }
            }

            $(document).ready(function() {
                // ========================================================
                // 1. Existing Logic: Lead Tracking Bar Chart (Employee Data)
                // ========================================================
                let leadChart;
                let currentLeadData = null; // To store response for table view
                const ctxLeadElem = document.getElementById('leadTrackingChart');

                if (ctxLeadElem) {
                    const ctxLead = ctxLeadElem.getContext('2d');

                    function initLeadChart(labels, workedData, convertedData) {
                        if (leadChart) leadChart.destroy();
                        leadChart = new Chart(ctxLead, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                        label: 'Leads Worked',
                                        data: workedData,
                                        backgroundColor: '#009EF7',
                                        borderRadius: 4
                                    },
                                    {
                                        label: 'Converted',
                                        data: convertedData,
                                        backgroundColor: '#50CD89',
                                        borderRadius: 4
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    }

                    function fetchLeadData() {
                        const employee = $('#employeeFilter').val();
                        const range = $('#dateRangeFilter').val();
                        const start = $('#startDate').val();
                        const end = $('#endDate').val();

                        $.ajax({
                            url: "{{ route('leads.tracking.data') }}",
                            method: "GET",
                            data: {
                                employee_id: employee,
                                range: range,
                                start_date: start,
                                end_date: end
                            },
                            success: function(res) {
                                currentLeadData = res; // Save data for table use
                                initLeadChart(res.labels, res.worked, res.converted);
                            },
                            error: function(err) {
                                console.error("Graph Error:", err);
                            }
                        });
                    }

                    // --- New Function to Show Table ---
                    $('#viewTableBtn').on('click', function() {
                        if (!currentLeadData) return;

                        let tableHtml = '';
                        currentLeadData.labels.forEach((label, index) => {
                            const worked = currentLeadData.worked[index];
                            const converted = currentLeadData.converted[index];
                            // Calculate conversion percentage safely
                            const percentage = worked > 0 ? ((converted / worked) * 100).toFixed(1) : 0;

                            tableHtml += `
										<tr>
											<td class="text-dark fw-bolder">${label}</td>
											<td class="text-center"><span class="badge badge-light-primary fs-7">${worked}</span></td>
											<td class="text-center"><span class="badge badge-light-success fs-7">${converted}</span></td>

										</tr>
									`;
                        });

                        if (tableHtml === '') tableHtml =
                            '<tr><td colspan="4" class="text-center">No data available</td></tr>';

                        $('#leadTableBody').html(tableHtml);
                        $('#leadTableModal').modal('show');
                    });

                    // Listeners
                    $('#employeeFilter, #dateRangeFilter').on('change', function() {
                        if ($(this).val() === 'custom') {
                            $('#customDateSection').removeClass('d-none');
                        } else {
                            $('#customDateSection').addClass('d-none');
                            fetchLeadData();
                        }
                    });

                    $('#applyCustomDate').on('click', fetchLeadData);

                    // Initial Load
                    fetchLeadData();
                }

                // ========================================================
                // 2. New Logic: SEO Frontend Leads Doughnut Chart
                // ========================================================
                const seoCtx = document.getElementById('seoLeadsChart');

                new Chart(seoCtx, {
                    type: 'doughnut',

                    data: {
                        labels: {!! json_encode(array_keys($seoChartData)) !!},

                        datasets: [{
                            data: {!! json_encode(array_values($seoChartData)) !!},

                            backgroundColor: [
                                '#50CD89',
                                '#F1416C'
                            ],

                            borderWidth: 0
                        }]
                    },

                    options: {
                        responsive: true,
                        cutout: '70%',

                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                // CUSTOM DATE TOGGLE
                function toggleCustomDate(value) {

                    const wrap = document.getElementById('customDateWrap');

                    if (value === 'custom') {
                        wrap.classList.remove('d-none');
                    } else {
                        wrap.classList.add('d-none');
                    }
                }

                // ========================================================
                // 3. New Logic: Conversion Ratio Doughnut Chart
                // ========================================================

            });
        </script>
        <script>
            $(document).ready(function() {
                let conversionChart = null;

                const convCtxElem = document.getElementById('conversionRatioChart');

                function renderConversionChart(converted, notConverted) {
                    if (!convCtxElem) return;

                    const convCtx = convCtxElem.getContext('2d');

                    if (conversionChart) {
                        conversionChart.destroy();
                    }

                    conversionChart = new Chart(convCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Converted Orders', 'Active Leads'],
                            datasets: [{
                                data: [converted, notConverted],
                                backgroundColor: ['#50CD89', '#E4E6EF'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            cutout: '80%',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return ' ' + context.label + ': ' + context.raw;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                function fetchConversionData() {
                    const filter = $('#conversionFilter').val();
                    const from = $('#conversionFrom').val();
                    const to = $('#conversionTo').val();

                    $.ajax({
                        url: "{{ route('dashboard.conversion.ratio.data') }}",
                        method: "GET",
                        data: {
                            filter: filter,
                            from: from,
                            to: to
                        },
                        success: function(res) {
                            $('#conversionRatioText').text(res.conversion_ratio + '%');
                            $('#convertedLeadsText').text('(' + res.converted_leads + ')');
                            $('#notConvertedLeadsText').text('(' + res.not_converted_leads + ')');
                            $('#totalLeadsText').text(res.total_leads);

                            renderConversionChart(res.converted_leads, res.not_converted_leads);
                        },
                        error: function(err) {
                            console.error('Conversion Ratio Error:', err);
                        }
                    });
                }

                $('#conversionFilter').on('change', function() {
                    if ($(this).val() === 'custom') {
                        $('#conversionCustomDateWrap').removeClass('d-none');
                    } else {
                        $('#conversionCustomDateWrap').addClass('d-none');
                        fetchConversionData();
                    }
                });

                $('#applyConversionDate').on('click', function() {
                    fetchConversionData();
                });

                renderConversionChart({{ $convertedOrders1Year }}, {{ $notConvertedCount }});
            });
        </script>

        <script>
            $(document).ready(function() {
                // Event handler for role selection
                $('#roleSelector').change(function() {
                    var roleId = $(this).val();

                    // Check if a role is selected
                    if (roleId) {
                        // Make an AJAX request to fetch team members
                        $.ajax({
                            url: '/fetch-team-members', // The URL of the route that handles the AJAX request
                            type: 'GET',
                            data: {
                                role_id: roleId
                            },
                            success: function(response) {
                                // Handle the response and update the team members container
                                updateTeamMembers(response.teamMembers);
                                // Close the dropdown
                                $('.btn.btn-color-primary.btn-active-light-primary').trigger(
                                    'click');
                            },
                            error: function(error) {
                                console.error('Error fetching team members:', error);
                            }
                        });
                    } else {
                        // Clear team members container if no role is selected
                        updateTeamMembers([]);
                    }
                });

                // Initial fetch for default role (you can change this to the desired default role)
                fetchTeamMembers(4); // Assuming "Marketing Team" has roleId 4
            });

            function fetchTeamMembers(roleId) {
                // Make an initial AJAX request to fetch team members for the default role
                $.ajax({
                    url: '/fetch-team-members', // The URL of the route that handles the AJAX request
                    type: 'GET',
                    data: {
                        role_id: roleId
                    },
                    success: function(response) {
                        // Handle the response and update the team members container
                        updateTeamMembers(response.teamMembers);
                    },
                    error: function(error) {
                        console.error('Error fetching team members:', error);
                    }
                });
            }

            function updateTeamMembers(teamMembers) {
                $('#teamMembersContainer').html('');
                teamMembers.forEach(function(member) {
                    var photoUrl = member.photo ? member.photo : 'assets/media/avatars/blank.png';
                    var safeName = $('<div>').text(member.name || '').html();
                    var memberHtml =
                        '<button type="button" class="ain-trow" data-name="' + safeName.toLowerCase().replace(/"/g,
                            '&quot;') + '">' +
                        '<span class="ain-tav"><img src="' + photoUrl +
                        '" alt="" onerror="this.onerror=null;this.src=\'assets/media/avatars/blank.png\';" /></span>' +
                        '<span class="ain-tname">' + safeName + '</span>' +
                        '<svg class="ain-chk" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5 9-10"></path></svg>' +
                        '</button>';
                    $('#teamMembersContainer').append(memberHtml);
                });
                if (window.ainFilterTeam) window.ainFilterTeam();
            }
        </script>

        <!-- ... (remaining content) ... -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script>
            (function() {
                var raw = [{
                        name: 'Cancelled',
                        v: {{ (int) ($cancelledOrder ?? 0) }},
                        color: '#F1416C',
                        side: '#B92A50',
                        desc: 'Orders that were cancelled'
                    },
                    {
                        name: 'In Progress',
                        v: {{ (int) ($inprogressOrder ?? 0) }},
                        color: '#4F46E5',
                        side: '#3730A3',
                        desc: 'Orders being worked on right now'
                    },
                    {
                        name: 'Hold Work',
                        v: {{ (int) ($holdWorkOrder ?? 0) }},
                        color: '#009EF7',
                        side: '#0072B8',
                        desc: 'Work that is paused for now'
                    },
                    {
                        name: 'Pending',
                        v: {{ (int) ($pendingOrder ?? 0) }},
                        color: '#F5B301',
                        side: '#B88300',
                        desc: 'Orders waiting to be picked up'
                    },
                    {
                        name: 'Other',
                        v: {{ (int) ($otherOrder ?? 0) }},
                        color: '#94A3B8',
                        side: '#64748B',
                        desc: 'Everything else'
                    }
                ];
                raw.sort(function(a, b) {
                    return b.v - a.v;
                });

                var total = raw.reduce(function(s, r) {
                    return s + r.v;
                }, 0);
                var flat = false,
                    hover = null,
                    groups = [],
                    moves = [],
                    rowEls = [];
                var svg = document.getElementById('ainPie');
                var rowsBox = document.getElementById('ainRows');
                if (!svg || !rowsBox) return;

                function pctOf(v) {
                    return total > 0 ? v / total * 100 : 0;
                }

                function fmt(p) {
                    if (p > 0 && p < 0.1) return '<0.1%';
                    return p.toFixed(1) + '%';
                }

                function f(n) {
                    return n.toFixed(2);
                }

                function build() {
                    var rx = flat ? 128 : 150,
                        ry = flat ? 128 : 88,
                        h = flat ? 0 : 26,
                        cx = 220,
                        cy = flat ? 160 : 130;
                    var acc = -Math.PI / 2,
                        items = [];

                    function P(a, k) {
                        k = k || 1;
                        return [cx + rx * k * Math.cos(a), cy + ry * k * Math.sin(a)];
                    }

                    raw.forEach(function(r, i) {
                        var frac = Math.min(total > 0 ? r.v / total : 0, 0.9999);
                        var a0 = acc,
                            a1 = acc + frac * 2 * Math.PI;
                        acc = a1;
                        var mid = (a0 + a1) / 2;
                        var top = 'M 0 0',
                            side = 'M 0 0';
                        if (frac > 0) {
                            var p0 = P(a0),
                                p1 = P(a1);
                            var laf = (a1 - a0) > Math.PI ? 1 : 0;
                            top = 'M ' + f(cx) + ' ' + f(cy) + ' L ' + f(p0[0]) + ' ' + f(p0[1]) +
                                ' A ' + rx + ' ' + ry + ' 0 ' + laf + ' 1 ' + f(p1[0]) + ' ' + f(p1[1]) + ' Z';
                            var lo = Math.max(a0, 0),
                                hi = Math.min(a1, Math.PI);
                            if (!flat && lo < hi - 0.000001) {
                                var q0 = P(lo),
                                    q1 = P(hi);
                                side = 'M ' + f(q0[0]) + ' ' + f(q0[1]) + ' L ' + f(q0[0]) + ' ' + f(q0[1] + h) +
                                    ' A ' + rx + ' ' + ry + ' 0 0 1 ' + f(q1[0]) + ' ' + f(q1[1] + h) +
                                    ' L ' + f(q1[0]) + ' ' + f(q1[1]) +
                                    ' A ' + rx + ' ' + ry + ' 0 0 0 ' + f(q0[0]) + ' ' + f(q0[1]) + ' Z';
                            }
                        }
                        var lp = P(mid, 0.62);
                        var p = pctOf(r.v);
                        items.push({
                            i: i,
                            sin: Math.sin(mid),
                            top: top,
                            side: side,
                            lx: lp[0],
                            ly: lp[1] + (flat ? 0 : 2),
                            label: p >= 5 ? p.toFixed(1) + '%' : '',
                            dx: Math.cos(mid) * (flat ? 12 : 16),
                            dy: Math.sin(mid) * (flat ? 12 : 9) - (flat ? 0 : 4)
                        });
                    });

                    moves = [];
                    items.forEach(function(it) {
                        moves[it.i] = it;
                    });
                    items.sort(function(a, b) {
                        return a.sin - b.sin;
                    });

                    var html = flat ?
                        '<ellipse cx="220" cy="300" rx="100" ry="8" fill="#151A33" opacity="0.1"></ellipse>' :
                        '<ellipse cx="220" cy="' + (cy + h + 14) + '" rx="' + (rx - 6) + '" ry="' + (ry - 40) +
                        '" fill="#151A33" opacity="0.1"></ellipse>';
                    items.forEach(function(it) {
                        var r = raw[it.i];
                        html += '<g class="ain-sl" data-i="' + it.i + '">' +
                            '<path d="' + it.side + '" fill="' + r.side + '"></path>' +
                            '<path d="' + it.top + '" fill="' + r.color +
                            '" stroke="#FFFFFF" stroke-width="2" stroke-linejoin="round"></path>' +
                            '<text x="' + f(it.lx) + '" y="' + f(it.ly) +
                            '" text-anchor="middle" dominant-baseline="middle" fill="#FFFFFF" font-size="15" font-weight="800" style="pointer-events:none">' +
                            it.label + '</text></g>';
                    });
                    svg.innerHTML = html;

                    groups = [];
                    Array.prototype.forEach.call(svg.querySelectorAll('.ain-sl'), function(g) {
                        var i = +g.getAttribute('data-i');
                        groups[i] = g;
                        g.addEventListener('mouseenter', function() {
                            setHover(i);
                        });
                        g.addEventListener('click', function() {
                            setHover(i);
                        });
                    });
                }

                function setHover(idx) {
                    hover = idx;
                    groups.forEach(function(g, i) {
                        if (!g) return;
                        var on = hover === i;
                        g.style.transform = on ?
                            'translate(' + moves[i].dx.toFixed(1) + 'px,' + moves[i].dy.toFixed(1) + 'px)' :
                            'translate(0px,0px)';
                        g.style.opacity = (hover === null || on) ? 1 : 0.45;
                    });
                    rowEls.forEach(function(el, i) {
                        el.classList.toggle('on', hover === i);
                    });
                    var ai = hover === null ? 0 : hover;
                    var r = raw[ai];
                    document.getElementById('ainDDot').style.background = r.color;
                    document.getElementById('ainDName').textContent = r.name;
                    document.getElementById('ainDPct').textContent = fmt(pctOf(r.v));
                    document.getElementById('ainDCount').textContent = r.v + ' orders';
                    document.getElementById('ainDDesc').textContent = r.desc;
                    document.getElementById('ainDRank').textContent = hover === null ?
                        'Largest share. Hover a slice to compare.' :
                        'Rank ' + (ai + 1) + ' of ' + raw.length + ' statuses';
                }

                rowsBox.innerHTML = raw.map(function(r, i) {
                    var p = pctOf(r.v);
                    return '<button type="button" class="ain-lrow" data-i="' + i + '">' +
                        '<span class="ain-lrow__top"><span class="ain-dot" style="background:' + r.color +
                        '"></span>' +
                        '<span class="ain-lrow__name">' + r.name + '</span>' +
                        '<span class="ain-lrow__cnt">' + r.v + '</span>' +
                        '<span class="ain-lrow__pct">' + fmt(p) + '</span></span>' +
                        '<span class="ain-lrow__bar"><span style="display:block;height:100%;border-radius:999px;background:' +
                        r.color + ';width:' + p.toFixed(1) + '%"></span></span></button>';
                }).join('');
                rowEls = Array.prototype.slice.call(rowsBox.querySelectorAll('.ain-lrow'));
                rowEls.forEach(function(el, i) {
                    el.addEventListener('mouseenter', function() {
                        setHover(i);
                    });
                    el.addEventListener('mouseleave', function() {
                        setHover(null);
                    });
                    el.addEventListener('focus', function() {
                        setHover(i);
                    });
                    el.addEventListener('blur', function() {
                        setHover(null);
                    });
                });

                svg.addEventListener('mouseleave', function() {
                    setHover(null);
                });

                var b3d = document.getElementById('ainBtn3d'),
                    bFlat = document.getElementById('ainBtnFlat');

                function mode(isFlat) {
                    flat = isFlat;
                    b3d.classList.toggle('on', !flat);
                    bFlat.classList.toggle('on', flat);
                    build();
                    setHover(null);
                }
                b3d.addEventListener('click', function() {
                    mode(false);
                });
                bFlat.addEventListener('click', function() {
                    mode(true);
                });

                build();
                setHover(null);
            })();

            // Team: search, select, role chip
            window.ainFilterTeam = function() {
                var q = ($('#teamSearch').val() || '').toLowerCase();
                var rows = $('#teamMembersContainer .ain-trow');
                var shown = 0;
                rows.each(function() {
                    var ok = ($(this).attr('data-name') || '').indexOf(q) !== -1;
                    $(this).toggle(ok);
                    if (ok) shown++;
                });
                $('#teamNoMatch').toggleClass('d-none', shown > 0 || rows.length === 0);
            };

            $(document).ready(function() {
                $('#teamSearch').on('input', window.ainFilterTeam);
                $(document).on('click', '#teamMembersContainer .ain-trow', function() {
                    $(this).siblings().removeClass('sel');
                    $(this).toggleClass('sel');
                });
                $('#roleSelector').on('change', function() {
                    $('#teamRoleChip').text(this.value ? $(this).find('option:selected').text() :
                        'Marketing Team');
                });
            });
        </script>
        <?php
        // Calculate total orders
        $totalOrders = 0;
        
        ?>

        <script type="text/javascript">
            google.charts.load("current", {
                packages: ["corechart"]
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var totalOrders = 0; // Initialize total orders counter
                var data = google.visualization.arrayToDataTable([
                    ['Writer', 'Order Assign'],
                    <?php foreach ($userByWid as $item) {
				if ($item['user'] !== 'Total Orders') { ?>['<?php echo $item['user']; ?>', <?php echo $item['orders']; ?>],
                    <?php
					// Add orders to the total if the user is not "Total Orders"
					$totalOrders += $item['orders'];
				}
			} ?>
                ]);

                var options = {
                    title: 'Orders by month (Total: <?php echo $totalOrders; ?>)',
                    is3D: true,
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart_3d-2'));
                chart.draw(data, options);
            }
        </script>
        <?php
        // Calculate total orders
        $totalOrders2 = 0;
        
        ?>

        <script type="text/javascript">
            google.charts.load("current", {
                packages: ["corechart"]
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var totalOrders2 = 0; // Initialize total orders counter
                var data = google.visualization.arrayToDataTable([
                    ['Writer', 'Order Assign'],
                    <?php foreach ($userByWid2 as $item) {
				if ($item['user'] !== 'Total Orders') { ?>['<?php echo $item['user']; ?>', <?php echo $item['orders']; ?>],
                    <?php
					// Add orders to the total if the user is not "Total Orders"
					$totalOrders2 += $item['orders'];
				}
			} ?>
                ]);

                var options = {
                    title: 'Orders by month for team (Total: <?php echo $totalOrders2; ?>)',
                    is3D: true,
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart_3d-3'));
                chart.draw(data, options);
            }
        </script>
    @else
        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                        data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard
                            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                            <small class="text-muted fs-7 fw-bold my-1 ms-1">Assingment In Need</small>
                        </h1>
                    </div>
                    <!-- <div class="d-flex align-items-center py-1">
                                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app" id="kt_toolbar_primary_button">Create</a>
                                       </div> -->
                </div>
            </div>

            <div class="post d-flex flex-column-fluid" id="kt_post">
                <div id="kt_content_container" class="container-xxl">
                    @php
                        $currentUser = auth()->user();
                        $roleName = function_exists('getUserRoleName')
                            ? getUserRoleName($currentUser->role_id)
                            : 'Team Member';
                    @endphp

                    <div class="card mb-8">
                        <div class="card-body py-8">
                            <div
                                class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-5">
                                <div class="d-flex align-items-center gap-4">
                                    <div class="symbol symbol-60px">
                                        <img src="{{ asset($currentUser->photo ?? 'assets/media/avatars/blank.png') }}"
                                            alt="user" />
                                    </div>
                                    <div>
                                        <h2 class="fw-bolder text-dark mb-1">Welcome back, {{ $currentUser->name }}.</h2>
                                        <div class="text-muted fw-bold">{{ $roleName }}</div>
                                    </div>
                                </div>
                                <a href="{{ url('/profile') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-user me-2"></i>My Profile
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 g-xl-8">
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-45px me-4">
                                            <span class="symbol-label bg-light-primary">
                                                <i class="fa fa-id-badge text-primary fs-3"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-muted fw-bold fs-7">Role</div>
                                            <div class="fw-bolder fs-5 text-dark">{{ $roleName }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-45px me-4">
                                            <span class="symbol-label bg-light-success">
                                                <i class="fa fa-envelope text-success fs-3"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-muted fw-bold fs-7">Email</div>
                                            <div class="fw-bolder fs-6 text-dark text-truncate">{{ $currentUser->email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-45px me-4">
                                            <span class="symbol-label bg-light-warning">
                                                <i class="fa fa-calendar text-warning fs-3"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-muted fw-bold fs-7">Today</div>
                                            <div class="fw-bolder fs-5 text-dark">{{ now()->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 g-xl-8 mt-1">
                        <div class="col-xl-8">
                            <div class="card h-100">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title fw-bolder text-dark">Quick Actions</h3>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="d-flex flex-wrap gap-3">
                                        <a href="{{ url('/profile') }}" class="btn btn-light-primary">
                                            <i class="fa fa-user me-2"></i>Profile
                                        </a>
                                        @if ($currentUser->role_id != 2)
                                            <a href="{{ url('/order') }}" class="btn btn-light-success">
                                                <i class="fa fa-list me-2"></i>Orders
                                            </a>
                                            <a href="{{ url('/leads') }}" class="btn btn-light-info">
                                                <i class="fa fa-address-book me-2"></i>Leads
                                            </a>
                                            <a href="{{ url('/follow-up') }}" class="btn btn-light-warning">
                                                <i class="fa fa-clock-o me-2"></i>Follow Up
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card h-100">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title fw-bolder text-dark">Status</h3>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <span class="text-muted fw-bold">Login</span>
                                        <span class="badge badge-light-success">Active</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-muted fw-bold">Account</span>
                                        <span class="badge badge-light-primary">{{ $roleName }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif


@endsection
