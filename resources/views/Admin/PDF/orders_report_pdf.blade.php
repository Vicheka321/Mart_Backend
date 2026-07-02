<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Orders Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }

        h1 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 4px;
        }

        .subtitle {
            text-align: center;
            font-size: 11px;
            color: #777;
            margin-bottom: 18px;
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

        th {
            border: 1px solid #d1d5db;
            padding: 8px 6px;
            font-size: 10px;
            font-weight: bold;
            text-align: left;
        }

        td {
            border: 1px solid #d1d5db;
            padding: 7px 6px;
            font-size: 10px;
            vertical-align: top;
        }

        tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .status {
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            color: #888;
            font-size: 10px;
        }
    </style>

</head>

<body>

    <h1>Orders Report</h1>

    <div class="subtitle">
        Generated on {{ now()->format('F d, Y h:i A') }}
    </div>

    <div class="meta">

        Total Orders :
        <strong>{{ $orders->count() }}</strong>

    </div>

    <table>

        <thead>

            <tr>

                <th width="5%">ID</th>

                <th width="18%">Customer</th>

                <th width="10%">Phone</th>

                <th width="12%">Payment</th>

                <th width="12%">Payment Status</th>

                <th width="10%">Order Status</th>

                <th width="8%">Items</th>

                <th width="10%">Total</th>

                <th width="15%">Created</th>

            </tr>

        </thead>

        <tbody>

            @forelse($orders as $order)

                <tr>

                    <td class="text-center">

                        #{{ $order->id }}

                    </td>

                    <td>

                        <strong>

                            {{ optional($order->user)->full_name ?? 'Guest' }}

                        </strong>

                        <br>

                        <span style="font-size:9px;color:#888;">

                            {{ optional($order->user)->email }}

                        </span>

                    </td>

                    <td>

                        {{ optional($order->user)->phone }}

                    </td>

                    <td class="text-center">

                        {{ strtoupper($order->payment_method) }}

                    </td>

                    <td class="text-center">

                        {{ ucfirst(optional($order->payment)->payment_status ?? '-') }}

                    </td>

                    <td class="text-center">

                        {{ ucfirst($order->status) }}

                    </td>

                    <td class="text-center">

                        {{ $order->orderItems->sum('qty') }}

                    </td>

                    <td class="text-right">

                        ${{ number_format($order->total_amount, 2) }}

                    </td>

                    <td>

                        {{ $order->created_at->format('Y-m-d') }}

                        <br>

                        <span style="font-size:9px;color:#888;">

                            {{ $order->created_at->format('h:i A') }}

                        </span>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="9" class="text-center">

                        No orders found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

    <div class="footer">

        Exported from Order Report Management System

    </div>

</body>

</html>