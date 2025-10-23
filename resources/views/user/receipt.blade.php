<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { border: 1px solid #ccc; padding: 20px; border-radius: 8px; width: 500px; }
        h2 { text-align: center; }
        .row { margin: 8px 0; }
        .label { font-weight: bold; width: 120px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Transaction Receipt</h2>
        <div class="row"><span class="label">Transaction ID:</span> {{ $transaction->id }}</div>
        <div class="row"><span class="label">Type:</span> {{ ucfirst($transaction->type) }}</div>
        <div class="row"><span class="label">Amount:</span> ₱{{ number_format($transaction->amount, 2) }}</div>
        <div class="row"><span class="label">Status:</span> {{ ucfirst($transaction->status) }}</div>
        <div class="row"><span class="label">Date:</span> {{ $transaction->created_at->format('F d, Y h:i A') }}</div>
    </div>
</body>
</html>
