<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Report Analysis PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <h2>Transaction Report</h2>
    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $txn)
            <tr>
                <td>{{ $txn->id }}</td>
                <td>{{ ucfirst($txn->type) }}</td>
                <td>{{ number_format($txn->amount, 2) }}</td>
                <td>{{ ucfirst($txn->status) }}</td>
                <td>{{ $txn->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>