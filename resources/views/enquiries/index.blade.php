@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div id="kt_content_container" class="container-xxl py-6">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title">Enquiry Master</h3>
            <div>
                <form method="GET" action="{{ route('enquiries.index') }}" class="d-flex align-items-center gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                    <button class="btn btn-sm btn-primary">Search</button>
                    @if(request('search'))
                        <a href="{{ route('enquiries.index') }}" class="btn btn-sm btn-secondary">Clear</a>
                    @endif
                </form>
            </div>
        </div>
        <div class="card-body">
            @include('layouts.flash')
            <div class="table-responsive">
                <table class="table table-row-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Subject</th>
                            <th>Inquiry Type</th>
                            <th>Message</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($enquiries as $enquiry)
                        <tr>
                            <td>{{ $enquiry->created_at->format('d M Y, h:i A') }}</td>
                            <td><strong>{{ $enquiry->name }}</strong></td>
                            <td><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></td>
                            <td>
                                @if($enquiry->country_code)
                                    <code>{{ $enquiry->country_code }}</code>
                                @endif
                                {{ $enquiry->mobile ?: '-' }}
                            </td>
                            <td><span class="badge badge-light-primary">{{ $enquiry->subject }}</span></td>
                            <td><span class="badge badge-light-success">{{ $enquiry->inquiry_type }}</span></td>
                            <td>
                                @if(strlen($enquiry->message) > 40)
                                    <span title="{{ $enquiry->message }}">{{ substr($enquiry->message, 0, 40) }}...</span>
                                    <button class="btn btn-link btn-sm p-0 ms-1" data-bs-toggle="modal" data-bs-target="#msgModal{{ $enquiry->id }}">Read more</button>
                                @else
                                    {{ $enquiry->message }}
                                @endif

                                <!-- Modal -->
                                <div class="modal fade" id="msgModal{{ $enquiry->id }}" tabindex="-1" aria-labelledby="msgModalLabel{{ $enquiry->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="msgModalLabel{{ $enquiry->id }}">Enquiry Message Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <p><strong>From:</strong> {{ $enquiry->name }} ({{ $enquiry->email }})</p>
                                                <p><strong>Subject:</strong> {{ $enquiry->subject }}</p>
                                                <p><strong>Inquiry Type:</strong> {{ $enquiry->inquiry_type }}</p>
                                                <hr>
                                                <p style="white-space: pre-wrap;">{{ $enquiry->message }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <form class="d-inline" method="POST" action="{{ route('enquiries.destroy', $enquiry) }}" onsubmit="return confirm('Delete this enquiry?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No enquiries found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-4">
                {{ $enquiries->links() }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
