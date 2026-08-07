@forelse($nextLeads as $index => $item)
    @php
        $formattedMonth = \Carbon\Carbon::createFromFormat('Y-m', $item->target_month)->format('F Y');
    @endphp
    <tr>
        <td class="text-center fw-bold fs-7">{{ $index + 1 }}</td>
        <td class="text-center">
            <span class="fw-bold text-gray-800">{{ $item->user_name }}</span>
            <br>
            <span class="text-muted fs-8">{{ $item->email }}</span>
        </td>
        <td class="text-center">
            <span class="badge badge-light-dark fs-8 fw-semibold">{{ $item->countrycode }} {{ $item->mobile }}</span>
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
