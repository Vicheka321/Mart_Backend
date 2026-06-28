{{--
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>Receipt #{{ $order->id }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 80mm;
            font-family: monospace;
            font-size: 12px;
            padding: 10px;
        }

        .center {
            text-align: center;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 11px;
            padding-bottom: 4px;
        }

        td {
            font-size: 11px;
            padding: 2px 0;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        @media print {
            @page {
                margin: 0;
            }

            body {
                width: 80mm;
            }
        }
    </style>
</head>

<body>

    <div class="center">

        <h2>DARITA MART</h2>

        <small>
            Phnom Penh, Cambodia
        </small>

    </div>

    <div class="divider"></div>

    <div>
        <b>Order #{{ $order->id }}</b>
    </div>

    <div>
        {{ $order->created_at->format('d-m-Y h:i A') }}
    </div>

    <div class="divider"></div>

    <div>
        <b>Customer</b>
    </div>

    <div>
        {{ $order->user->full_name }}
    </div>

    <div>
        {{ $order->user->phone }}
    </div>

    <div class="divider"></div>

    <div>
        <b>Payment</b>
    </div>

    <div>
        Method:
        {{ strtoupper($order->payment->payment_method ?? $order->payment_method) }}
    </div>

    <div>
        Status:
        {{ strtoupper($order->payment->payment_status ?? 'UNPAID') }}
    </div>

    <div class="divider"></div>

    <table>

        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th class="right">Amt</th>
            </tr>
        </thead>

        <tbody>

            @foreach($order->orderItems as $item)

            <tr>
                <td>
                    {{ Str::limit($item->product->name, 20) }}
                </td>

                <td>
                    {{ $item->qty }}
                </td>

                <td class="right">
                    $
                    {{ number_format($item->qty * $item->price, 2) }}
                </td>
            </tr>

            @endforeach

        </tbody>

    </table>

    <div class="divider"></div>

    @php
    $grandTotal = $order->orderItems->sum(
    fn($item) => $item->qty * $item->price
    );
    @endphp

    <table>

        <tr>
            <td class="bold">
                TOTAL
            </td>

            <td class="right bold">
                $
                {{ number_format($grandTotal, 2) }}
            </td>
        </tr>

    </table>

    <div class="divider"></div>

    <div>
        <b>Delivery Address</b>
    </div>

    <div>
        {{ $order->delivery_address }}
    </div>

    <div class="divider"></div>

    <div class="center">

        @if(($order->payment->payment_method ?? $order->payment_method) == 'cash')

        <b>
            CUSTOMER NEED TO PAY
        </b>

        @else

        <b>
            PAID
        </b>

        @endif

    </div>

    <div class="divider"></div>

    <div class="center">

        Thank You

        <br>

        Darita Mart

    </div>

    <script>
        window.onload = () => {
            window.print();
        };
    </script>

</body>

</html> --}}




{{-- =================================== --}}

{{--
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #222;
            padding: 40px;
            font-size: 13px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .header-left,
        .header-right {
            display: table-cell;
            vertical-align: top;
        }

        .header-right {
            text-align: right;
        }

        h1 {
            font-size: 30px;
            color: #2563eb;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .section {
            margin-top: 25px;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table {
            margin-top: 20px;
        }

        .product-table th {
            background: #2563eb;
            color: white;
            padding: 10px;
            text-align: left;
        }

        .product-table td {
            border-bottom: 1px solid #eee;
            padding: 10px;
        }

        .right {
            text-align: right;
        }

        .summary {
            width: 320px;
            margin-left: auto;
            margin-top: 20px;
        }

        .summary td {
            padding: 8px 0;
        }

        .grand-total {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #2563eb;
            padding-top: 10px;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 12px;
            color: white;
        }

        .pending {
            background: orange;
        }

        .processing {
            background: #2563eb;
        }

        .completed {
            background: green;
        }

        .cancelled {
            background: red;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>

<body>

    @php
    $subtotal = $order->orderItems->sum(
    fn($item) => $item->qty * $item->price
    );

    $paymentMethod =
    strtoupper(
    $order->payment->payment_method
    ?? $order->payment_method
    );

    $paymentStatus =
    strtoupper(
    $order->payment->payment_status
    ?? 'UNPAID'
    );
    @endphp

    <div class="header">

        <div class="header-left">
            <h1>DARITA MART</h1>

            <div>
                Phnom Penh, Cambodia
            </div>

            <div>
                support@daritamart.com
            </div>
        </div>

        <div class="header-right">

            <div class="invoice-title">
                INVOICE
            </div>

            <div>
                Invoice #{{ $order->id }}
            </div>

            <div>
                {{ $order->created_at->format('d M Y h:i A') }}
            </div>

            <br>

            <span class="status {{ $order->status }}">
                {{ strtoupper($order->status) }}
            </span>

        </div>

    </div>

    <div class="section">

        <table>
            <tr>

                <td width="50%">

                    <div class="card">

                        <strong>
                            Customer Information
                        </strong>

                        <br><br>

                        Name:
                        {{ $order->user->full_name }}

                        <br>

                        Phone:
                        {{ $order->user->phone }}

                    </div>

                </td>

                <td width="50%">

                    <div class="card">

                        <strong>
                            Payment Information
                        </strong>

                        <br><br>

                        Method:
                        {{ $paymentMethod }}

                        <br>

                        Status:
                        {{ $paymentStatus }}

                    </div>

                </td>

            </tr>
        </table>

    </div>

    <table class="product-table">

        <thead>

            <tr>
                <th>Product</th>
                <th width="80">Qty</th>
                <th width="120">Price</th>
                <th width="120">Total</th>
            </tr>

        </thead>

        <tbody>

            @foreach($order->orderItems as $item)

            <tr>

                <td>
                    {{ $item->product->name }}
                </td>

                <td>
                    {{ $item->qty }}
                </td>

                <td>
                    ${{ number_format($item->price, 2) }}
                </td>

                <td>
                    $
                    {{ number_format(
                    $item->price * $item->qty,
                    2
                    ) }}
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <table class="summary">

        <tr>
            <td>Subtotal</td>
            <td class="right">
                ${{ number_format($subtotal, 2) }}
            </td>
        </tr>

        @if($order->promotion_discount > 0)

        <tr>
            <td>Promotion Discount</td>
            <td class="right">
                -${{ number_format($order->promotion_discount, 2) }}
            </td>
        </tr>

        @endif

        @if($order->coupon_discount > 0)

        <tr>

            <td>

                Coupon

                @if($order->coupon_code)

                ({{ $order->coupon_code }})

                @endif

            </td>

            <td class="right">
                -${{ number_format($order->coupon_discount, 2) }}
            </td>

        </tr>

        @endif

        <tr class="grand-total">

            <td>
                GRAND TOTAL
            </td>

            <td class="right">
                ${{ number_format($order->total_amount, 2) }}
            </td>

        </tr>

    </table>

    <div class="section">

        <div class="card">

            <strong>
                Delivery Address
            </strong>

            <br><br>

            {{ $order->delivery_address }}

        </div>

    </div>

    <div class="footer">

        Thank you for shopping with Darita Mart

        <br>

        Generated on
        {{ now()->format('d M Y h:i A') }}

    </div>

</body>

</html> --}}


{{-- ===================================================== --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->id }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 80mm;
            font-family: monospace;
            font-size: 12px;
            padding: 10px;
            color: #000;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .double-divider {
            border-top: 2px solid #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding-bottom: 4px;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .small {
            font-size: 10px;
        }

        .total {
            font-size: 14px;
            font-weight: bold;
        }

        @media print {
            @page {
                margin: 0;
                size: 80mm auto;
            }

            body {
                width: 80mm;
            }
        }

        /* @page {
            size: A4;
            margin: 15mm;
        }

        body {
            width: 100%;
            max-width: 100%;
        } */
    </style>
</head>

<body>

    @php

        $subtotal = $order->orderItems->sum(
            fn($item) => $item->qty * $item->price
        );

        $paymentMethod = strtoupper(
            $order->payment->payment_method
            ?? $order->payment_method
        );

        $paymentStatus = strtoupper(
            $order->payment->payment_status
            ?? 'UNPAID'
        );

    @endphp

    <div class="center">

        <h2>DARITA MART</h2>

        <div class="small">
            Phnom Penh, Cambodia
        </div>

        <div class="small">
            Tel: +855 xx xxx xxx
        </div>

    </div>

    <div class="divider"></div>

    <div>
        <b>Receipt #{{ $order->id }}</b>
    </div>

    <div>
        {{ $order->created_at->format('d-m-Y h:i A') }}
    </div>

    <div class="divider"></div>

    <div class="bold">
        CUSTOMER
    </div>

    <div>
        {{ $order->user->full_name }}
    </div>

    <div>
        {{ $order->user->phone }}
    </div>

    <div class="divider"></div>

    <div class="bold">
        PAYMENT
    </div>

    <div>
        Method:
        {{ $paymentMethod }}
    </div>

    <div>
        Status:
        {{ $paymentStatus }}
    </div>

    <div class="divider"></div>

    <table>

        <thead>

            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th class="right">Amt</th>
            </tr>

        </thead>

        <tbody>

            @foreach($order->orderItems as $item)

                        <tr>

                            <td>
                                {{ \Illuminate\Support\Str::limit(
                    $item->product->name,
                    18
                ) }}
                            </td>

                            <td>
                                {{ $item->qty }}
                            </td>

                            <td class="right">
                                $
                                {{ number_format(
                    $item->qty * $item->price,
                    2
                ) }}
                            </td>

                        </tr>

            @endforeach

        </tbody>

    </table>

    <div class="divider"></div>

    <table>

        <tr>
            <td>Subtotal</td>
            <td class="right">
                ${{ number_format($subtotal, 2) }}
            </td>
        </tr>

        @if($order->promotion_discount > 0)

                <tr>
                    <td>Promotion</td>
                    <td class="right">
                        -${{ number_format(
                $order->promotion_discount,
                2
            ) }}
                    </td>
                </tr>

        @endif

        @if($order->coupon_discount > 0)

                <tr>

                    <td>

                        Coupon

                        @if($order->coupon_code)
                            ({{ $order->coupon_code }})
                        @endif

                    </td>

                    <td class="right">
                        -${{ number_format(
                $order->coupon_discount,
                2
            ) }}
                    </td>

                </tr>

        @endif

    </table>

    <div class="double-divider"></div>

    <table>

        <tr>

            <td class="total">
                TOTAL
            </td>

            <td class="right total">
                ${{ number_format(
    $order->total_amount,
    2
) }}
            </td>

        </tr>

    </table>

    <div class="double-divider"></div>

    <div class="bold">
        DELIVERY ADDRESS
    </div>

    <div>
        {{ $order->delivery_address }}
    </div>

    <div class="divider"></div>

    <div class="center">

        @if($paymentMethod === 'CASH')

            <b>
                CUSTOMER NEED TO PAY
            </b>

        @else

            <b>
                PAID
            </b>

        @endif

    </div>

    <div class="divider"></div>

    <div class="center">

        Thank You.

        <br>

        Darita Mart

    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>

</body>

</html>