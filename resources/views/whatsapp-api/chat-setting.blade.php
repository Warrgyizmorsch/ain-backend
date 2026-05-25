@extends('layouts.app')

@section('content')
<div class="container-xxl">
    <!-- Welcome Message Card -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">Welcome Message</h3>
                <label class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" checked />
                </label>
            </div>

            <p class="text-muted mb-4">Configure automated reply for user's first query during working hours</p>

            <button class="btn btn-sm btn-primary mb-5">Configure</button>

            <div class="d-flex align-items-start bg-light-primary p-5 rounded">
                <div class="symbol symbol-30px me-4">
                    <span class="symbol-label bg-success text-white fw-bold">💬</span>
                </div>
                <div class="fw-semibold text-gray-800 fs-6">
                    Hi! Thanks for connecting. Someone from our team will get in touch soon.
                </div>
            </div>
        </div>
    </div>

    <!-- Working Hours Card -->
    <div class="card mb-6">
        <div class="card-body">
            <h3 class="fw-bold mb-4">Working Hours</h3>
            <p class="text-muted mb-6">Configure day-wise working hours for automated replies</p>

            <!-- Timezone Selection -->
            <div class="mb-6">
                <label for="timezone" class="form-label fw-semibold">Timezone</label>
                <select id="timezone" class="form-select form-select-solid">
                    <option selected>Asia/Calcutta (+05:30)</option>
                    <option>UTC (+00:00)</option>
                    <option>America/New_York (-05:00)</option>
                    <option>Europe/London (+01:00)</option>
                </select>
            </div>

            <!-- Day-wise Working Hours -->
            <div class="d-flex flex-column gap-4">
                @php
                    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                @endphp

                @foreach($days as $day)
                    <div class="d-flex align-items-center gap-5">
                        <span class="fw-semibold w-50px">{{ $day }}</span>

                        <label class="form-check form-switch form-check-custom form-check-solid">
                            @if($day == 'Sat')
                                <input class="form-check-input toggle-day" type="checkbox" checked />
                            @else
                                <input class="form-check-input toggle-day" type="checkbox" />
                            @endif
                        </label>

                        @if($day == 'Sat')
                            <div class="d-flex align-items-center gap-3 ms-auto">
                                <input type="time" class="form-control form-control-sm form-control-solid w-100px" value="09:00">
                                <span class="text-muted">to</span>
                                <input type="time" class="form-control form-control-sm form-control-solid w-100px" value="18:00">
                            </div>
                        @else
                            <span class="text-muted ms-auto">Closed</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
