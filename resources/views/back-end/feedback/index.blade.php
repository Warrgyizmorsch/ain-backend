@extends('layouts.app')

@section('content')
<style>
    .failed-order-box {
        display: inline-block;
        background: #ffeaea !important;
        color: #b50000 !important;
        border: 2px solid #ff0000 !important;
        border-radius: 8px;
        padding: 6px 10px;
        font-weight: 700;
        line-height: 1.35;
    }
</style>
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
                    @php
                        $isFailedOrder = (int) ($fb->order_is_fail ?? 0) === 1;
                    @endphp
                    <tr>
                        <td class="ps-4">{{ $fb->id }}</td>
                        <td>
                            @if($isFailedOrder)
                                <span class="failed-order-box">
                                    {{ $fb->order_id }}<br>
                                    <span class="fs-8">Fail Order</span>
                                    @if($fb->order_failed_at)
                                        <br><span class="fs-9">{{ \Carbon\Carbon::parse($fb->order_failed_at)->format('d M Y h:i A') }}</span>
                                    @endif
                                </span>
                            @else
                                <span class="badge badge-light-dark">{{ $fb->order_id }}</span>
                            @endif
                        </td>
                        <td>{{ $fb->experience ?? 'N/A' }}</td>
                        <td><span class="badge badge-light-primary">{{ $fb->feedback_scope ?? 'N/A' }}</span></td>
                        <td>{{ $fb->your_suggestion ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($fb->created_at)->format('d M Y') }}</td>
                        <td class="text-end">
                            @php
                                $fbPhone = $fb->customer_mobile ?? '';
                                $fbEmail = $fb->customer_email ?? '';
                                $fbCC = preg_replace('/\D+/', '', (string)($fb->customer_countrycode ?? ''));
                                $fbName = addslashes($fb->customer_name ?? 'Customer');
                                $fbWAPhone = $fbCC . $fbPhone;
                                $fbEmailUrl = route('emails.index', array_filter(['account_id' => 2, 'search' => $fbEmail]));
                                $fbWhatsAppUrl = !empty($fbWAPhone) ? route('whatsapp.chat', ['phone' => $fbWAPhone]) : route('whatsapp.chat');
                            @endphp

                            @if(!empty($fbPhone))
                                <a href="#" 
                                   onclick="event.preventDefault(); event.stopPropagation(); initiateCustomerCall('{{ $fbCC . $fbPhone }}', '{{ $fbName }}');"
                                   class="btn btn-icon btn-sm me-1 shadow-sm"
                                   style="width:28px;height:28px;min-width:28px;border-radius:6px;background-color:#25D366;color:#ffffff;display:inline-flex;align-items:center;justify-content:center;transition:transform 0.2s ease,background-color 0.2s ease;"
                                   onmouseover="this.style.backgroundColor='#1ebd58';this.style.transform='scale(1.1)';"
                                   onmouseout="this.style.backgroundColor='#25D366';this.style.transform='scale(1)';"
                                   title="Call: {{ $fbCC . $fbPhone }}">
                                    <i class="fa fa-phone text-white" style="font-size:12px;"></i>
                                </a>

                                <a href="{{ $fbWhatsAppUrl }}" target="_blank" class="btn btn-icon btn-sm me-1 crm-btn-wa" style="width: 28px !important; height: 28px !important; min-width: 28px !important;" title="WhatsApp: {{ $fbWAPhone ?: 'Open Chat' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16">
                                        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/>
                                    </svg>
                                </a>
                            @endif

                            @if(!empty($fbEmail))
                                <a href="{{ $fbEmailUrl }}" target="_blank" class="btn btn-icon btn-sm me-1 crm-btn-email" style="width: 28px !important; height: 28px !important; min-width: 28px !important;" title="Email: {{ $fbEmail ?: 'Open Emails' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                                    </svg>
                                </a>
                            @endif

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
