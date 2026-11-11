<div class="p-3 bg-light rounded shadow-sm border">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-gray-800">
            <i class="fa fa-users text-primary me-1"></i> {{ $title }} ({{ count($users) }})
        </h5>
        @if(auth()->check() && (int) auth()->user()->role_id === 1)
        <button type="button" class="btn btn-sm btn-danger" onclick="exportRetentionUserList(this, '{{ addslashes($title) }}')">
            <i class="fa fa-file-excel me-1"></i> Export to CSV
        </button>
        @endif
    </div>

    <div class="table-responsive" style="max-height: 440px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-sm table-bordered table-hover align-middle mb-0 bg-white retention-user-table">
            <thead style="position: sticky; top: 0; z-index: 5;">
                <tr class="fw-bold bg-light text-gray-700 fs-7">
                    <th style="width: 45px;">#</th>
                    <th>Customer</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th class="text-center">Lifetime Orders</th>
                    <th>Last Order Date</th>
                    <th>Inactivity</th>
                    <th class="text-center no-export" style="width: 140px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $key => $user)
                    @php
                        $cleanPhone = preg_replace('/\D+/', '', ($user->countrycode ?? '') . ($user->mobile_no ?? ''));
                        $lastDate = $user->last_order_date ? \Carbon\Carbon::parse($user->last_order_date) : null;
                        $daysDiff = $lastDate ? $lastDate->diffInDays(now()) : null;
                    @endphp
                    <tr>
                        <td class="text-muted fs-8">{{ $key + 1 }}</td>
                        <td class="fw-bold text-dark">
                            {{ $user->name ?? 'N/A' }}
                        </td>
                        <td>
                            @if(!empty($user->mobile_no))
                                <span class="badge badge-light fw-bold text-dark fs-8">
                                    {{ $user->countrycode ? '+' . ltrim($user->countrycode, '+') . ' ' : '' }}{{ $user->mobile_no }}
                                </span>
                            @else
                                <span class="text-muted fs-8">N/A</span>
                            @endif
                        </td>
                        <td class="text-muted fs-8">
                            {{ $user->email ?? 'N/A' }}
                        </td>
                        <td class="text-center">
                            <span class="badge badge-light-primary fw-bolder fs-8">
                                {{ $user->total_orders ?? $user->month_orders ?? 1 }}
                            </span>
                        </td>
                        <td class="fs-8">
                            @if($lastDate)
                                <span class="fw-bold text-dark">{{ $lastDate->format('d M Y') }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td class="fs-8">
                            @if($daysDiff !== null)
                                @if($daysDiff > 90)
                                    <span class="badge badge-light-danger fs-9 fw-bold">{{ $daysDiff }} days ago</span>
                                @elseif($daysDiff > 30)
                                    <span class="badge badge-light-warning fs-9 fw-bold">{{ $daysDiff }} days ago</span>
                                @else
                                    <span class="badge badge-light-success fs-9 fw-bold">{{ $daysDiff }} days ago</span>
                                @endif
                            @else
                                <span class="text-muted fs-9">N/A</span>
                            @endif
                        </td>
                        <td class="text-center no-export">
                            <div class="d-inline-flex align-items-center gap-1">
                                @if(!empty($cleanPhone))
                                    <a href="{{ route('whatsapp.chat', ['phone' => $cleanPhone]) }}" target="_blank"
                                        class="btn btn-icon btn-sm btn-light-success" style="width: 28px; height: 28px;"
                                        title="Chat on WhatsApp">
                                        <i class="fab fa-whatsapp fs-6"></i>
                                    </a>
                                    <button type="button" class="btn btn-icon btn-sm btn-light-primary" style="width: 28px; height: 28px;"
                                        onclick="if(typeof window.initiateCustomerCall === 'function'){ window.initiateCustomerCall('{{ $cleanPhone }}', '{{ addslashes($user->name ?? 'Customer') }}'); } else { window.location.href='tel:+{{ $cleanPhone }}'; }"
                                        title="Call Customer">
                                        <i class="fa fa-phone fs-7"></i>
                                    </button>
                                @endif
                                <a href="{{ route('orders.index', ['user' => $user->mobile_no ?: $user->email]) }}" target="_blank"
                                    class="btn btn-icon btn-sm btn-light-dark" style="width: 28px; height: 28px;"
                                    title="View Customer's Orders">
                                    <i class="fa fa-shopping-cart fs-7"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No customers found for this criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
