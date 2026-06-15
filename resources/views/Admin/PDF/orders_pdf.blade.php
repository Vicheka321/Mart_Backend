{{-- resources/views/Admin/PDF/orders_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Orders Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 22px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            color: #777;
            font-size: 11px;
        }

        .meta {
            margin-bottom: 15px;
            font-size: 11px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            background: #f3f4f6;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            font-size: 11px;
            font-weight: bold;
        }

        td {
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .status {
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #888;
        }
    </style>
</head>

<body>

    <h1>Orders Report</h1>
    <div class="subtitle">
        Generated on {{ now()->format('F d, Y h:i A') }}
    </div>

    <div class="meta">
        Total Orders: <strong>{{ $orders->count() }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th width="6%">ID</th>
                <th width="22%">Customer</th>
                <th width="15%">Phone</th>
                <th width="12%">Total</th>
                <th width="15%">Payment</th>
                <th width="12%">Status</th>
                <th width="18%">Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="text-center">
                        {{ $order->id }}
                    </td>
                    <td>
                        {{ $order->user->full_name ?? 'Customer' }}
                    </td>
                    <td>
                        {{ $order->user->phone ?: '—' }}
                    </td>
                    <td class="text-right">
                        ${{ number_format($order->total_amount, 2) }}
                    </td>
                    <td>
                        {{ $order->payment_method ?: '—' }}
                    </td>
                    <td class="status">
                        {{ ucfirst($order->status) }}
                    </td>
                    <td>
                        {{ $order->created_at->format('Y-m-d H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        No orders found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Exported from Order Management System
    </div>

</body>

</html>