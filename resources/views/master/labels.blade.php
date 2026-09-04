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
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Cross-Channel WhatsApp & Email Tags</small>
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
                            <span class="text-muted fw-bold fs-7">Used across WhatsApp Chats & Emails</span>
                        </h3>
                    </div>

                    <form action="{{ route('labels.store') }}" method="POST">
                        @csrf
                        <div class="card-body pt-5">
                            {{-- Label Name --}}
                            <div class="fv-row mb-5">
                                <label class="required fw-bold fs-6 mb-2">Label Name</label>
                                <input type="text" name="name" id="createLabelName" required
                                    class="form-control form-control-solid"
                                    placeholder="e.g. VIP Client, Urgent, Orders"
                                    oninput="updateCreatePreview()">
                            </div>

                            {{-- Preset Color Palette (5-6 Options) --}}
                            <div class="fv-row mb-5">
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
                            <div class="fv-row mb-6 p-4 rounded bg-light border border-dashed">
                                <label class="fs-8 text-muted fw-bold text-uppercase d-block mb-2">Live Badge Preview</label>
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
                                        <th class="ps-4 min-w-50px">#</th>
                                        <th class="min-w-150px">Label Preview</th>
                                        <th class="min-w-100px">Color Code</th>
                                        <th class="min-w-120px">WhatsApp Uses</th>
                                        <th class="min-w-120px">Email Uses</th>
                                        <th class="min-w-100px text-end pe-4">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($labels as $index => $label)
                                        @php
                                            $waCount = \App\Models\WhatsappChatContactLabel::where('label_id', $label->id)->count();
                                            $emailCount = \App\Models\EmailThreadLabel::where('label_id', $label->id)->count();
                                        @endphp
                                        <tr>
                                            <td class="ps-4 fw-bold text-gray-600">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="badge px-3 py-2 fs-7 fw-bold" style="background-color: {{ $label->color }}; color: #ffffff;">
                                                    <i class="fa fa-tag me-1 text-white opacity-75"></i> {{ $label->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="d-inline-block rounded-circle" style="width: 14px; height: 14px; background-color: {{ $label->color }};"></span>
                                                    <code class="text-dark">{{ $label->color }}</code>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-success fw-bold">
                                                    <i class="fa fa-whatsapp me-1"></i> {{ $waCount }} chats
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-primary fw-bold">
                                                    <i class="fa fa-envelope me-1"></i> {{ $emailCount }} threads
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <button type="button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                    data-bs-toggle="modal" data-bs-target="#editLabelModal{{ $label->id }}" title="Edit Label">
                                                    <i class="fa fa-pencil fs-6"></i>
                                                </button>

                                                <button type="button" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#deleteLabelModal{{ $label->id }}" title="Delete Label">
                                                    <i class="fa fa-trash fs-6"></i>
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
                                                                <div class="text-muted fw-semibold fs-7">Modify label name and color</div>
                                                            </div>

                                                            <div class="fv-row mb-5">
                                                                <label class="required fw-bold fs-6 mb-2">Label Name</label>
                                                                <input type="text" name="name" id="editLabelName{{ $label->id }}" required
                                                                    class="form-control form-control-solid" value="{{ $label->name }}"
                                                                    oninput="updateEditPreview({{ $label->id }})">
                            </div>

                                                            <div class="fv-row mb-5">
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

                                                            <div class="fv-row mb-6 p-4 rounded bg-light border border-dashed text-center">
                                                                <label class="fs-8 text-muted fw-bold text-uppercase d-block mb-2">Preview</label>
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
