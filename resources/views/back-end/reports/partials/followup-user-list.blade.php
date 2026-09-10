<div class="p-3 bg-light rounded">
    <h5 class="fw-bold mb-3">{{ $title }}</h5>

    <div class="table-responsive" style="max-height: 420px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-sm table-bordered align-middle mb-0">
            <thead style="position: sticky; top: 0; z-index: 5;">
                <tr class="fw-bold bg-white">
                    <th>#</th>
                    <th>User</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Order ID</th>
                    <th>Follow Status</th>
                    <th>Comment</th>
                    <th>Follow Up By</th>
                    <th>Follow Up Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $key => $user)
                    @php
                        $rawName = $user->name ?? '';
                        $rawEmail = $user->email ?? '';
                        $rawMobile = $user->mobile_no ?? '';
                        $rawCC = $user->countrycode ?? '';
                        $cleanCC = preg_replace('/\D+/', '', (string)$rawCC);
                        $maskedEmail = $rawEmail ? mask_email_for_display($rawEmail) : '';
                        $maskedMobile = $rawMobile ? mask_mobile_only($cleanCC, $rawMobile) : '';
                        $orderId = $user->order_id ?? '';
                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>

                        <td class="fw-bold text-gray-800">
                            <div class="d-inline-flex align-items-center">
                                <span>{{ $rawName ?: 'N/A' }}</span>
                                @if(!empty($rawName))
                                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Name" onclick="event.stopPropagation(); crmCopyToClipboard('{{ addslashes($rawName) }}', 'Customer name copied!');">
                                        <i class="fa fa-clone fs-8 text-muted"></i>
                                    </button>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if(!empty($maskedMobile))
                                <div class="d-inline-flex align-items-center gap-1">
                                    <span class="badge badge-light-danger">
                                        @if(!empty($cleanCC))+{{ $cleanCC }} @endif{{ $maskedMobile }}
                                    </span>
                                    <button type="button" class="btn btn-icon btn-sm btn-active-light-danger p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Mobile" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $maskedMobile }}', 'Mobile number copied!');">
                                        <i class="fa fa-clone fs-8 text-danger"></i>
                                    </button>
                                </div>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>

                        <td>
                            @if(!empty($maskedEmail))
                                <div class="d-inline-flex align-items-center">
                                    <span class="text-gray-600 fs-8">{{ $maskedEmail }}</span>
                                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Email" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $maskedEmail }}', 'Email copied!');">
                                        <i class="fa fa-clone fs-8 text-muted"></i>
                                    </button>
                                </div>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>

                        <td>
                            <div class="d-inline-flex align-items-center">
                                <span class="badge badge-light-primary">{{ $orderId ?: 'N/A' }}</span>
                                @if(!empty($orderId))
                                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Order ID" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $orderId }}', 'Order ID copied!');">
                                        <i class="fa fa-clone fs-8 text-muted"></i>
                                    </button>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if(!empty($user->follow_status))
                                <span class="badge badge-light-success">
                                    {{ $user->follow_status }}
                                </span>
                            @else
                                <span class="badge badge-light-danger">N/A</span>
                            @endif
                        </td>

                        <td>{{ $user->follow_comment ?? 'N/A' }}</td>

                        <td>{{ $user->follow_up_user ?? 'N/A' }}</td>

                        <td>{{ $user->followupdate ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>