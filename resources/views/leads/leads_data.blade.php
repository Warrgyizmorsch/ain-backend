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
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
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
    </style>
</head>
<body>

    <h1>Leads Data</h1>

    <table>
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Name</th>
                <th>Country Code</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Created On</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name ?? 'N/A' }}</td>
                    <td>{{ $user->countrycode ?? 'N/A' }}</td>
                    <td>{{ $user->mobile_no ?? 'N/A' }}</td>
                    <td>{{ $user->email ?? 'N/A' }}</td>
<td>
    {{ \Carbon\Carbon::parse($user->created_on)->format('d-m-Y') ?? 'N/A' }}
</td>                </tr>
            @endforeach
        </tbody>
    </table>


</body>
</html>
