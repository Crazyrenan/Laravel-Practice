<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f3f3f3;
        }
    </style>
</head>
<body>
    <h2>Purchase Table</h2>

    <table>  
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer ID</th>
                <th>Product Name</th>
                <th>Product Code</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Purchase Date</th>
                <th>Year</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->id }}</td>
                    <td>{{ $purchase->customer_id }}</td>
                    <td>{{ $purchase->product_name }}</td>
                    <td>{{ $purchase->product_code }}</td>
                    <td>{{ $purchase->quantity }}</td>
                    <td>Rp {{ number_format($purchase->unit_price, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($purchase->total_price, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                    <td>{{ $purchase->year }}</td>
                    <td>{{ $purchase->created_at }}</td>
                    <td>{{ $purchase->updated_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11">No purchases found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
