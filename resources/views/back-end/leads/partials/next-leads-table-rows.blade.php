@forelse($nextLeads as $index => $item)
    @php
        $formattedMonth = \Carbon\Carbon::createFromFormat('Y-m', $item->target_month)->format('F Y');
        $displayMobile = mask_mobile_only($item->countrycode, $item->mobile);
        $displayEmail = mask_email_for_display($item->email);
        $cleanCC = preg_replace('/\D+/', '', (string)$item->countrycode);
    @endphp
    <tr>
        <td class="text-center fw-bold fs-7">{{ $index + 1 }}</td>
        <td class="text-center">
            <span class="fw-bold text-gray-800">{{ $item->user_name }}</span>
            <br>
            <span class="text-muted fs-8">{{ $displayEmail }}</span>
        </td>
        <td class="text-center">
            <div class="d-inline-flex align-items-center gap-1">
                @if(!empty($cleanCC))
                    <span class="badge badge-light-primary fs-8 fw-bold">+{{ $cleanCC }}</span>
                @endif
                <span class="badge badge-light-danger fs-7 fw-bold">{{ $displayMobile }}</span>
                {{-- Twilio Call Button --}}
                <button type="button" class="btn btn-icon btn-xs btn-light-success p-1 ms-1"
                    title="Call via Twilio"
                    onclick="initiateTwilioCall(@js(($cleanCC ? '+' . $cleanCC : '') . $item->mobile), @js($item->user_name ?? 'Customer'))">
                    <i class="fa fa-phone fs-9 text-success"></i>
                </button>

                {{-- Next2Call Button (with red 2 badge) --}}
                <button type="button" class="btn btn-icon btn-xs btn-light-success p-1 ms-1 position-relative"
                    title="Call via Next2Call"
                    onclick="initiateNext2Call(@js(($cleanCC ? '+' . $cleanCC : '') . $item->mobile), @js($item->user_name ?? 'Customer'))">
                    <i class="fa fa-phone fs-9 text-success"></i>
                    <span style="position:absolute;bottom:-2px;right:-1px;background:#e53e3e;color:#ffffff;font-size:7px;font-weight:900;line-height:1;padding:0.5px 1.5px;border-radius:2px;box-shadow:0 1px 2px rgba(0,0,0,0.3);font-family:Arial,sans-serif;pointer-events:none;">2</span>
                </button>
            </div>
        </td>
        <td class="text-center">
            <span class="badge badge-light-primary fs-7 fw-bold">{{ $formattedMonth }}</span>
        </td>
        <td class="text-center">
            <span class="fw-bold text-gray-700 fs-8">{{ $item->creator->name ?? 'System' }}</span>
        </td>
        <td class="text-center fs-8 text-gray-600" style="max-width: 200px;">
            {{ Str::limit($item->message, 50, '...') ?: 'N/A' }}
        </td>
        <td class="text-center fs-8 text-muted">
            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-sm btn-success px-3 py-1.5 fs-8 fw-bold shadow-xs d-inline-flex align-items-center justify-content-center" 
                style="border-radius: 6px; white-space: nowrap;"
                onclick="convertNextLead({{ $item->id }}, this)">
                <i class="fa fa-arrow-right fs-9 me-1 text-white"></i> Convert to Active Lead
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-6 text-muted">
            <i class="fa fa-info-circle me-1"></i> No Next Leads found for the selected filter.
        </td>
    </tr>
@endforelse
