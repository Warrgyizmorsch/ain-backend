@php
    $dataRetention = [
        [
            'type' => 'Contact Info',
            'purpose' => 'Order updates & support',
            'retention' => '6 years (Legal/Tax)',
        ],
        [
            'type' => 'Order Details',
            'purpose' => 'Service delivery',
            'retention' => 'Duration of the project + 2 years',
        ],
        [
            'type' => 'Payment Data',
            'purpose' => 'Secure transactions',
            'retention' => 'Handled by 3rd party (Stripe/PayPal)',
        ],
        [
            'type' => 'Technical Data',
            'purpose' => 'Website optimisation',
            'retention' => '26 months (Google Analytics)',
        ],
    ];
@endphp

<table class="data-table">
    <thead>
        <tr>
            <th>Data Type</th>
            <th>Purpose</th>
            <th>Retention Period</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataRetention as $item)
            <tr>
                <td><strong>{{ $item['type'] }}</strong></td>
                <td>{{ $item['purpose'] }}</td>
                <td>{{ $item['retention'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<style>
    .data-table {
    width: 100%;
    border-collapse: collapse; /* VERY IMPORTANT */
    margin: 20px 0;
}

.data-table th,
.data-table td {
    border: 2px solid #000;   /* visible borders */
    padding: 14px 16px;
    vertical-align: top;
    text-align: left;
}

.data-table th {
    font-weight: 700;
    background-color: #fff;
}

.data-table td strong {
    font-weight: 700;
}

</style>