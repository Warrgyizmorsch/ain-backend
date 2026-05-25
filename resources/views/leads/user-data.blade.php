<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leads Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        .export-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
        }
        .export-btn:hover {
            background-color: #218838;
        }
        table {
            width: 90%;
            margin: 0 auto 40px;
            border-collapse: collapse;
            font-size: 16px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<h1>User Leads (Jan - May 2024)</h1>


<table>
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Created Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['all_user'] as $index => $lead)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $lead->name ?? 'N/A' }}</td>
                <td>{{ $lead->email ?? 'N/A' }}</td>
                <td>+{{ $lead->countrycode ?? 'N/A' }} {{ $lead->mobile_no ?? 'N/A' }}</td>
                <td>{{ $lead->created_at ? $lead->created_at->format('Y-m-d') : 'N/A' }}</td>
            </tr>
       @endforeach
    </tbody>
</table>

</body>
</html>
