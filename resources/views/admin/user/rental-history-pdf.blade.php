<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rental History - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
        }
        .user-info {
            margin-bottom: 15px;
        }
        .user-info h3 {
            font-size: 14px;
            margin: 0 0 10px 0;
        }
        .user-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .status {
            padding: 2px 4px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-canceled { background: #fee2e2; color: #991b1b; }
        .status-returned { background: #dbeafe; color: #1e40af; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rental History Report</h1>
    </div>

    <div class="user-info">
        <h3>User Information</h3>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ ucfirst($user->role->name) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item ID</th>
                <th>Item</th>
                <th>Rental Date</th>
                <th>Return Date</th>
                <th>Returned Date</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Status</th>
                <th>Overdue</th>
                <th>Charges</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rentalHistory as $rental)
                <tr>
                    <td>{{ $rental->clothingItem->id ?? 'N/A' }}</td>
                    <td>{{ $rental->clothingItem->name ?? 'N/A' }}</td>
                    <td>{{ $rental->rental_date ? $rental->rental_date->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $rental->return_date ? $rental->return_date->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $rental->rentalReturn ? $rental->rentalReturn->returned_date->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $rental->quantity ?? 'N/A' }}</td>
                    <td>Rp {{ number_format($rental->total_price ?? 0, 0, ',', '.') }}</td>
                    <td>
                        <span class="status status-{{ $rental->status }}">
                            {{ ucfirst($rental->status ?? 'Unknown') }}
                        </span>
                    </td>
                    <td>{{ $rental->is_overdue ? 'Yes' : 'No' }}</td>
                    <td>Rp {{ number_format($rental->overdue_charges ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format(($rental->total_price ?? 0) + ($rental->overdue_charges ?? 0), 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center;">No rental history available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <p><strong>Generated on:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
