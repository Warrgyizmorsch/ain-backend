@extends('layouts.app')

@section('content')
<div class="card card-flush mt-5">
    <div class="card-header border-0 pt-6">
        <h3 class="card-title fw-bolder">Feedbacks Table Data</h3>
        <div class="card-toolbar">
            <form action="" method="GET" class="d-flex align-items-center">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-solid w-250px" placeholder="Order ID or Experience...">
                <button type="submit" class="btn btn-primary ms-2">Filter</button>
                <a href="{{ route('feedback.list') }}" class="btn btn-light-danger ms-2">Reset</a>
            </form>
        </div>
    </div>
    
    <div class="card-body py-4">
        <div class="table-responsive">
            <table class="table table-bordered align-middle gs-7 gy-4">
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="ps-4 min-w-50px">ID</th>
                        <th class="min-w-100px">Order ID</th>
                        <th class="min-w-150px">Experience</th>
                        <th class="min-w-100px">Scope</th>
                        <th class="min-w-150px">Suggestion</th>
                        <th class="min-w-100px">Date</th>
                        <th class="min-w-100px text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $fb)
                    <tr>
                        <td class="ps-4">{{ $fb->id }}</td>
                        <td><span class="badge badge-light-dark">{{ $fb->order_id }}</span></td>
                        <td>{{ $fb->experience ?? 'N/A' }}</td>
                        <td><span class="badge badge-light-primary">{{ $fb->feedback_scope ?? 'N/A' }}</span></td>
                        <td>{{ $fb->your_suggestion ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($fb->created_at)->format('d M Y') }}</td>
                        <td class="text-end">
                            <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" 
                                    data-bs-toggle="modal" data-bs-target="#editModal{{ $fb->id }}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <a href="{{ route('feedback.delete', $fb->id) }}" 
                               onclick="return confirm('Bhai, pakka delete karna hai?')" 
                               class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $fb->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('feedback.update', $fb->id) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Feedback #{{ $fb->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Experience</label>
                                            <input type="text" name="experience" class="form-control" value="{{ $fb->experience }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Feedback Scope</label>
                                            <input type="text" name="feedback_scope" class="form-control" value="{{ $fb->feedback_scope }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Suggestion</label>
                                            <textarea name="your_suggestion" class="form-control">{{ $fb->your_suggestion }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @empty
                    <tr><td colspan="7" class="text-center text-danger">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $feedbacks->appends(request()->input())->links() }}
        </div>
    </div>
</div>
@endsection