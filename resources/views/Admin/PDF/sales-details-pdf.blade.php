<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Sales Details Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 22px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 16px;
            color: #777;
            font-size: 11px;
        }

        .meta {
            margin-bottom: 14px;
            font-size: 11px;
            color: #666;
            line-height: 1.8;
        }

        .summary-box {
            margin-top: 10px;
            margin-bottom: 14px;
            padding: 10px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
        }

        .summary-row {
            margin-bottom: 4px;
            font-size: 11px;
        }

        .summary-row strong {
            color: #111;
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
            padding: 6px 5px;
            text-align: left;
            vertical-align: top;
        }

        th {
            font-size: 10px;
            font-weight: bold;
        }

        td {
            font-size: 9px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
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

    <h1>Sales Details Report</h1>

    <div class="subtitle">
        Date: {{ \Carbon\Carbon::parse($targetDate)->format('F d, Y') }} |
        Generated on {{ now()->format('F d, Y h:i A') }}
    </div>

    <div class="meta">
        <div>Status:
            <strong>{{ $filters['status'] && $filters['status'] !== 'all' ? ucfirst($filters['status']) : 'All' }}</strong>
        </div>

        <div>Payment Method:
            <strong>
                {{ $filters['payment_method'] && $filters['payment_method'] !== 'all'
                    ? strtoupper($filters['payment_method'])
                    : 'All' }}
            </strong>
        </div>

        <div>Search:
            <strong>{{ $filters['search'] ?: 'None' }}</strong>
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-row">
            Total Orders:
            <strong>{{ number_format($summary['total_orders']) }}</strong>
        </div>

        <div class="summary-row">
            Total Revenue:
            <strong>${{ number_format($summary['total_revenue'], 2) }}</strong>
        </div>

        <div class="summary-row">
            Coupon Discount:
            <strong>${{ number_format($summary['coupon_discount'], 2) }}</strong>
        </div>

        <div class="summary-row">
            Promotion Discount:
            <strong>${{ number_format($summary['promotion_discount'], 2) }}</strong>
        </div>

        <div class="summary-row">
            Total Discount:
            <strong>${{ number_format($summary['total_discount'], 2) }}</strong>
        </div>

        <div class="summary-row">
            Net Revenue:
            <strong>${{ number_format($summary['net_revenue'], 2) }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="6%">Order</th>
                <th width="13%">Customer</th>
                <th width="9%">Phone</th>
                <th width="18%">Address</th>
                <th width="8%">Payment</th>
                <th width="8%">Pay Status</th>
                <th width="8%">Order Status</th>
                <th width="8%">Total</th>
                <th width="7%">Coupon</th>
                <th width="7%">Promo</th>
                <th width="7%">Discount</th>
                <th width="8%">Net</th>
                <th width="11%">Created</th>
            </tr>
        </thead>

        <tbody>
            @forelse($orders as $order)
                @php
                    $coupon = (float) ($order->coupon_discount ?? 0);
                    $promo = (float) ($order->promotion_discount ?? 0);
                    $discount = $coupon + $promo;
                    $net = (float) ($order->total_amount ?? 0) - $discount;
                @endphp
                <tr>
                    <td class="text-center">#{{ $order->id }}</td>
                    <td>{{ $order->user->full_name ?? 'N/A' }}</td>
                    <td>{{ $order->user->phone ?? 'N/A' }}</td>
                    <td>{{ $order->delivery_address ?? 'N/A' }}</td>
                    <td>{{ strtoupper($order->payment->payment_method ?? 'N/A') }}</td>
                    <td>{{ ucfirst($order->payment->payment_status ?? 'N/A') }}</td>
                    <td>{{ ucfirst($order->status ?? 'N/A') }}</td>
                    <td class="text-right">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="text-right">${{ number_format($coupon, 2) }}</td>
                    <td class="text-right">${{ number_format($promo, 2) }}</td>
                    <td class="text-right">${{ number_format($discount, 2) }}</td>
                    <td class="text-right">${{ number_format($net, 2) }}</td>
                    <td>{{ optional($order->created_at)->format('Y-m-d h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center">
                        No sales details found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Exported from Sales Details Report System
    </div>

</body>

</html>