@extends('layouts.app')

@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">

        {{-- TOOLBAR --}}
        <div class="toolbar py-3" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack p-0">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                        <i class="fa fa-tags text-primary me-2 fs-2"></i> Label Master
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Cross-Channel WhatsApp, Email & CRM Tags</small>
                    </h1>
                </div>
            </div>
        </div>

        <div class="row g-5 g-xl-8">

            {{-- CREATE FORM --}}
            <div class="col-xl-4">
                <div class="card card-xxl-stretch mb-xl-8 shadow-sm border-0">
                    <div class="card-header border-0 py-5 bg-light-primary">
                        <h3 class="card-title flex-column m-0">
                            <span class="card-label fw-bolder fs-4 text-primary">Create New Label</span>
                            <span class="text-muted fw-bold fs-7">Used across WhatsApp, Email & CRM</span>
                        </h3>
                    </div>

                    <form action="{{ route('labels.store') }}" method="POST">
                        @csrf
                        <div class="card-body pt-5">
                            {{-- Label Name --}}
                            <div class="fv-row mb-4">
                                <label class="required fw-bold fs-6 mb-2">Label Name</label>
                                <input type="text" name="name" id="createLabelName" required
                                    class="form-control form-control-solid"
                                    placeholder="e.g. VIP Client, Urgent, Converted"
                                    oninput="updateCreatePreview()">
                            </div>

                            {{-- Sequence / Sort Order --}}
                            <div class="fv-row mb-4">
                                <label class="fw-bold fs-6 mb-2">Sequence (Order)</label>
                                <input type="number" name="sequence" id="createSequence" min="1" value="{{ ($labels->max('sequence') ?? 0) + 1 }}"
                                    class="form-control form-control-solid"
                                    placeholder="1, 2, 3...">
                                <div class="text-muted fs-8 mt-1">Defines display order across WhatsApp, Email & CRM tabs.</div>
                            </div>

                            {{-- Applicable Channels (Auto-sync) --}}
                            <div class="fv-row mb-4">
                                <label class="fw-bold fs-6 mb-2">Applicable Channels</label>
                                <div class="d-flex flex-column gap-2 p-3 bg-light rounded border">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="is_whatsapp" value="1" id="createCheckWhatsapp" checked>
                                        <label class="form-check-label fw-bold text-dark fs-7 cursor-pointer d-flex align-items-center gap-1.5" for="createCheckWhatsapp">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#25D366" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                                            <span>WhatsApp Chat</span>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="is_email" value="1" id="createCheckEmail" checked>
                                        <label class="form-check-label fw-bold text-dark fs-7 cursor-pointer d-flex align-items-center gap-1.5" for="createCheckEmail">
                                            <i class="fa fa-envelope text-primary"></i>
                                            <span>Email Threads</span>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="is_crm" value="1" id="createCheckCrm" checked>
                                        <label class="form-check-label fw-bold text-dark fs-7 cursor-pointer d-flex align-items-center gap-1.5" for="createCheckCrm">
                                            <i class="fa fa-shopping-cart text-warning"></i>
                                            <span>CRM / Orders</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="text-muted fs-8 mt-1">Checked channels will automatically sync when this label is tagged on a customer.</div>
                            </div>

                            {{-- Preset Color Palette (5-6 Options) --}}
                            <div class="fv-row mb-4">
                                <label class="required fw-bold fs-6 mb-2">Select Color</label>
                                <div class="d-flex flex-wrap gap-2 mb-3" id="createColorPalette">
                                    <button type="button" class="btn btn-sm btn-icon color-chip active-chip" data-color="#3454d1" style="background-color: #3454d1;" onclick="selectCreateColor('#3454d1', this)" title="Primary Blue"></button>
                                    <button type="button" class="btn btn-sm btn-icon color-chip" data-color="#10b981" style="background-color: #10b981;" onclick="selectCreateColor('#10b981', this)" title="Success Green"></button>
                                    <button type="button" class="btn btn-sm btn-icon color-chip" data-color="#f59e0b" style="background-color: #f59e0b;" onclick="selectCreateColor('#f59e0b', this)" title="Warning Amber"></button>
                                    <button type="button" class="btn btn-sm btn-icon color-chip" data-color="#ef4444" style="background-color: #ef4444;" onclick="selectCreateColor('#ef4444', this)" title="Danger Red"></button>
                                    <button type="button" class="btn btn-sm btn-icon color-chip" data-color="#8b5cf6" style="background-color: #8b5cf6;" onclick="selectCreateColor('#8b5cf6', this)" title="Purple"></button>
                                    <button type="button" class="btn btn-sm btn-icon color-chip" data-color="#f97316" style="background-color: #f97316;" onclick="selectCreateColor('#f97316', this)" title="Orange"></button>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" id="createCustomColorPicker" value="#3454d1" class="form-control form-control-color border-0 p-1" style="width: 45px; height: 38px; cursor: pointer;" oninput="selectCreateColor(this.value, null)">
                                    <input type="text" name="color" id="createColorInput" value="#3454d1" required class="form-control form-control-solid font-monospace" style="max-width: 140px;" oninput="selectCreateColor(this.value, null)">
                                </div>
                            </div>

                            {{-- Live Badge Preview --}}
                            <div class="fv-row mb-5 p-3 rounded bg-light border border-dashed">
                                <label class="fs-8 text-muted fw-bold text-uppercase d-block mb-1">Live Badge Preview</label>
                                <span class="badge px-3 py-2 fs-7 fw-bold" id="createBadgePreview" style="background-color: #3454d1; color: #ffffff;">
                                    <i class="fa fa-tag me-1 text-white opacity-75"></i> <span id="createBadgePreviewText">Label Preview</span>
                                </span>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary w-100 fw-bold">
                                    <i class="fa fa-plus me-1"></i> Save Label
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            {{-- TABLE --}}
            <div class="col-xl-8">
                <div class="card card-xxl-stretch mb-5 mb-xl-8 shadow-sm border-0">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title fw-bolder fs-3">All Configured Labels ({{ $labels->count() }})</h3>
                    </div>

                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th class="ps-3 min-w-50px text-center">Seq</th>
                                        <th class="min-w-140px">Label Preview</th>
                                        <th class="min-w-140px">Channels</th>
                                        <th class="min-w-100px">
                                            <span class="d-inline-flex align-items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#25D366" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                                                <span>WhatsApp</span>
                                            </span>
                                        </th>
                                        <th class="min-w-90px">
                                            <span class="d-inline-flex align-items-center gap-1.5">
                                                <i class="fa fa-envelope text-primary fs-6"></i>
                                                <span>Email</span>
                                            </span>
                                        </th>
                                        <th class="min-w-100px text-end pe-3">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($labels as $index => $label)
                                        @php
                                            $waCount = \App\Models\WhatsappChatContactLabel::where('label_id', $label->id)->count();
                                            $emailCount = \App\Models\EmailThreadLabel::where('label_id', $label->id)->count();
                                        @endphp
                                        <tr>
                                            <td class="ps-3 text-center">
                                                <span class="badge badge-light-dark fw-bold px-2 py-1 fs-8">{{ $label->sequence ?? ($index + 1) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge px-3 py-2 fs-7 fw-bold" style="background-color: {{ $label->color }}; color: #ffffff;">
                                                    <i class="fa fa-tag me-1 text-white opacity-75"></i> {{ $label->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @if($label->is_whatsapp)
                                                        <span class="badge badge-light-success py-1 px-2 fs-9 d-inline-flex align-items-center gap-1" title="WhatsApp Chat enabled">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="#25D366" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                                                            <span>WA</span>
                                                        </span>
                                                    @endif
                                                    @if($label->is_email)
                                                        <span class="badge badge-light-primary py-1 px-2 fs-9 d-inline-flex align-items-center gap-1" title="Email Threads enabled">
                                                            <i class="fa fa-envelope text-primary" style="font-size: 11px;"></i>
                                                            <span>Email</span>
                                                        </span>
                                                    @endif
                                                    @if($label->is_crm)
                                                        <span class="badge badge-light-warning py-1 px-2 fs-9 d-inline-flex align-items-center gap-1" title="CRM / Orders enabled">
                                                            <i class="fa fa-shopping-cart text-warning" style="font-size: 11px;"></i>
                                                            <span>CRM</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-success fw-bold fs-8 d-inline-flex align-items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="#25D366" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                                                    <span>{{ $waCount }} chats</span>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-primary fw-bold fs-8 d-inline-flex align-items-center gap-1">
                                                    <i class="fa fa-envelope text-primary" style="font-size: 11px;"></i>
                                                    <span>{{ $emailCount }} threads</span>
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <button type="button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                    data-bs-toggle="modal" data-bs-target="#editLabelModal{{ $label->id }}" title="Edit Label">
                                                    <i class="fa fa-edit text-primary fs-5"></i>
                                                </button>

                                                <button type="button" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#deleteLabelModal{{ $label->id }}" title="Delete Label">
                                                    <i class="fa fa-trash text-danger fs-5"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- EDIT MODAL --}}
                                        <div class="modal fade" id="editLabelModal{{ $label->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded">
                                                    <div class="modal-header pb-0 border-0 justify-content-end">
                                                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                            <i class="fa fa-times fs-4"></i>
                                                        </div>
                                                    </div>
                                                    <form action="{{ route('labels.update', $label->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body scroll-y px-10 pb-10 pt-0">
                                                            <div class="mb-5 text-center">
                                                                <h2 class="fw-bolder">Edit Label</h2>
                                                                <div class="text-muted fw-semibold fs-7">Modify label name, channels, and sequence</div>
                                                            </div>

                                                            {{-- Edit Name --}}
                                                            <div class="fv-row mb-4">
                                                                <label class="required fw-bold fs-6 mb-2">Label Name</label>
                                                                <input type="text" name="name" id="editLabelName{{ $label->id }}" required
                                                                    class="form-control form-control-solid" value="{{ $label->name }}"
                                                                    oninput="updateEditPreview({{ $label->id }})">
                                                            </div>

                                                            {{-- Edit Sequence --}}
                                                            <div class="fv-row mb-4">
                                                                <label class="fw-bold fs-6 mb-2">Sequence (Order)</label>
                                                                <input type="number" name="sequence" id="editSequence{{ $label->id }}" min="1" value="{{ $label->sequence ?? 1 }}"
                                                                    class="form-control form-control-solid">
                                                            </div>

                                                            {{-- Edit Channels --}}
                                                            <div class="fv-row mb-4">
                                                                <label class="fw-bold fs-6 mb-2">Applicable Channels</label>
                                                                <div class="d-flex flex-column gap-2 p-3 bg-light rounded border">
                                                                    <div class="form-check form-check-custom form-check-solid">
                                                                        <input class="form-check-input" type="checkbox" name="is_whatsapp" value="1" id="editCheckWhatsapp{{ $label->id }}" {{ $label->is_whatsapp ? 'checked' : '' }}>
                                                                        <label class="form-check-label fw-bold text-dark fs-7 cursor-pointer d-flex align-items-center gap-1.5" for="editCheckWhatsapp{{ $label->id }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#25D366" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                                                                            <span>WhatsApp Chat</span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check form-check-custom form-check-solid">
                                                                        <input class="form-check-input" type="checkbox" name="is_email" value="1" id="editCheckEmail{{ $label->id }}" {{ $label->is_email ? 'checked' : '' }}>
                                                                        <label class="form-check-label fw-bold text-dark fs-7 cursor-pointer d-flex align-items-center gap-1.5" for="editCheckEmail{{ $label->id }}">
                                                                            <i class="fa fa-envelope text-primary"></i>
                                                                            <span>Email Threads</span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check form-check-custom form-check-solid">
                                                                        <input class="form-check-input" type="checkbox" name="is_crm" value="1" id="editCheckCrm{{ $label->id }}" {{ $label->is_crm ? 'checked' : '' }}>
                                                                        <label class="form-check-label fw-bold text-dark fs-7 cursor-pointer d-flex align-items-center gap-1.5" for="editCheckCrm{{ $label->id }}">
                                                                            <i class="fa fa-shopping-cart text-warning"></i>
                                                                            <span>CRM / Orders</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- Color Palette --}}
                                                            <div class="fv-row mb-4">
                                                                <label class="required fw-bold fs-6 mb-2">Color Palette</label>
                                                                <div class="d-flex flex-wrap gap-2 mb-3">
                                                                    @foreach(['#3454d1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#f97316'] as $c)
                                                                        <button type="button" class="btn btn-sm btn-icon color-chip {{ strtolower($label->color) == strtolower($c) ? 'active-chip' : '' }}"
                                                                            style="background-color: {{ $c }};"
                                                                            onclick="selectEditColor({{ $label->id }}, '{{ $c }}', this)"></button>
                                                                    @endforeach
                                                                </div>

                                                                <div class="d-flex align-items-center gap-2">
                                                                    <input type="color" id="editCustomPicker{{ $label->id }}" value="{{ $label->color }}" class="form-control form-control-color border-0 p-1" style="width: 45px; height: 38px; cursor: pointer;" oninput="selectEditColor({{ $label->id }}, this.value, null)">
                                                                    <input type="text" name="color" id="editColorInput{{ $label->id }}" value="{{ $label->color }}" required class="form-control form-control-solid font-monospace" style="max-width: 140px;" oninput="selectEditColor({{ $label->id }}, this.value, null)">
                                                                </div>
                                                            </div>

                                                            <div class="fv-row mb-6 p-3 rounded bg-light border border-dashed text-center">
                                                                <label class="fs-8 text-muted fw-bold text-uppercase d-block mb-1">Preview</label>
                                                                <span class="badge px-3 py-2 fs-7 fw-bold" id="editBadgePreview{{ $label->id }}" style="background-color: {{ $label->color }}; color: #ffffff;">
                                                                    <i class="fa fa-tag me-1 text-white opacity-75"></i> <span id="editBadgePreviewText{{ $label->id }}">{{ $label->name }}</span>
                                                                </span>
                                                            </div>

                                                            <div class="d-flex justify-content-end gap-3">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary fw-bold">Update Label</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- DELETE MODAL --}}
                                        <div class="modal fade" id="deleteLabelModal{{ $label->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                                <div class="modal-content rounded text-center p-6">
                                                    <div class="mb-4">
                                                        <div class="symbol symbol-50px symbol-circle bg-light-danger mb-3 p-3">
                                                            <i class="fa fa-exclamation-triangle fs-1 text-danger"></i>
                                                        </div>
                                                        <h4 class="fw-bolder">Delete Label?</h4>
                                                        <p class="text-muted fs-7 m-0">Are you sure you want to delete <strong>{{ $label->name }}</strong>? It will also be removed from all attached WhatsApp chats and emails.</p>
                                                    </div>
                                                    <form action="{{ route('labels.delete', $label->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-sm btn-danger fw-bold">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-8 text-muted">
                                                <i class="fa fa-tags fs-2x mb-2 text-muted"></i>
                                                <div class="fw-bold">No Labels Created Yet</div>
                                                <div class="fs-8">Use the form on the left to create your first label.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

<style>
    .color-chip {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 2px solid transparent;
        cursor: pointer;
        transition: transform 0.15s, border-color 0.15s;
    }
    .color-chip:hover {
        transform: scale(1.1);
    }
    .color-chip.active-chip {
        border-color: #0f172a !important;
        box-shadow: 0 0 0 2px #ffffff inset, 0 2px 6px rgba(0,0,0,0.25);
    }
</style>

<script>
function selectCreateColor(color, btn) {
    document.getElementById('createColorInput').value = color;
    document.getElementById('createCustomColorPicker').value = color;
    if (btn) {
        document.querySelectorAll('#createColorPalette .color-chip').forEach(c => c.classList.remove('active-chip'));
        btn.classList.add('active-chip');
    }
    updateCreatePreview();
}

function updateCreatePreview() {
    const name = document.getElementById('createLabelName').value.trim() || 'Label Preview';
    const color = document.getElementById('createColorInput').value || '#3454d1';
    document.getElementById('createBadgePreviewText').textContent = name;
    document.getElementById('createBadgePreview').style.backgroundColor = color;
}

function selectEditColor(id, color, btn) {
    document.getElementById('editColorInput' + id).value = color;
    document.getElementById('editCustomPicker' + id).value = color;
    if (btn) {
        btn.parentElement.querySelectorAll('.color-chip').forEach(c => c.classList.remove('active-chip'));
        btn.classList.add('active-chip');
    }
    updateEditPreview(id);
}

function updateEditPreview(id) {
    const name = document.getElementById('editLabelName' + id).value.trim() || 'Label Preview';
    const color = document.getElementById('editColorInput' + id).value || '#3454d1';
    document.getElementById('editBadgePreviewText' + id).textContent = name;
    document.getElementById('editBadgePreview' + id).style.backgroundColor = color;
}
</script>

@endsection
