@extends('layouts.app') {{-- tumhara admin layout --}}

@section('content')
<div class="container-fluid py-4">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header border-0 pb-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h5 class="mb-1 fw-bold">Bulk Wallet Credit</h5>
                    <small class="text-muted">
                        Filter users by date or search, then credit wallet for all selected users in one go.
                    </small>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card-body border-bottom pb-3">
            <form method="GET" action="{{ route('admin.wallet.bulk-credit.form') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1">From Date</label>
                    <input type="date"
                           name="from_date"
                           class="form-control form-control-sm"
                           value="{{ $filters['from_date'] ?? '' }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1">To Date</label>
                    <input type="date"
                           name="to_date"
                           class="form-control form-control-sm"
                           value="{{ $filters['to_date'] ?? '' }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1">Search (Name / Email)</label>
                    <input type="text"
                           name="search"
                           class="form-control form-control-sm"
                           placeholder="Type to search..."
                           value="{{ $filters['search'] ?? '' }}">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary mt-auto">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.wallet.bulk-credit.form') }}"
                       class="btn btn-sm btn-light mt-auto">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Users table + bulk credit form --}}
        <form action="{{ route('admin.wallet.bulk-credit.store') }}" method="POST">
            @csrf

            <div class="card-body">

                {{-- Users list --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0 fw-semibold">Select Users</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="select-all">
                            <label for="select-all" class="form-check-label">
                                Select / Deselect All
                            </label>
                        </div>
                    </div>

                    @if($users->count())
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px;"></th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Joined On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <input
                                                    type="checkbox"
                                                    name="user_ids[]"
                                                    value="{{ $user->id }}"
                                                    class="form-check-input user-checkbox"
                                                    id="user_{{ $user->id }}"
                                                    @if(is_array(old('user_ids')) && in_array($user->id, old('user_ids'))) checked @endif
                                                >
                                            </td>
                                            <td>
                                                <label for="user_{{ $user->id }}" class="mb-0">
                                                    {{ $user->name }}
                                                </label>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            No users found for selected filter. Try changing date or search term.
                        </div>
                    @endif
                </div>

                <hr>

                {{-- Credit details --}}
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Amount (£) <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               step="0.01"
                               min="0.01"
                               name="amount"
                               class="form-control"
                               value="{{ old('amount') }}"
                               placeholder="0.00"
                               required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Expiry Date
                        </label>
                        <input type="date"
                               name="expires_at"
                               class="form-control"
                               value="{{ old('expires_at') }}">
                        <small class="text-muted">
                            Optional – wallet will auto-expire on this date.
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Description
                        </label>
                        <input type="text"
                               name="description"
                               class="form-control"
                               value="{{ old('description') }}"
                               placeholder="e.g. Festive offer, Referral bonus, Special credit, etc.">
                    </div>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary">
                    Credit Selected Wallets
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Simple select-all toggle
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.user-checkbox');

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
            });
        }
    });
</script>
@endpush
