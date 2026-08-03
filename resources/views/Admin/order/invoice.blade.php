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
    @if($order->note)
    <div class="section">
        <div class="section-title">
            Customer Note
        </div>

        <div class="note-box">
            {{ $order->note }}
        </div>
    </div>
    @endif

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

</html> --}}

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

        @page {
            size: A4;
            margin: 18mm 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
        }

        /* dompdf centers a fixed-width table with margin:auto reliably;
           flexbox is NOT supported, so everything below is table/block based */
        .receipt-wrap {
            width: 100mm;
            margin: 0 auto;
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
            font-size: 0;
            line-height: 0;
        }

        .double-divider {
            border-top: 2px solid #000;
            margin: 8px 0;
            font-size: 0;
            line-height: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding-bottom: 4px;
            border-bottom: 1px dashed #000;
            font-size: 12px;
        }

        td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 12px;
        }

        .small {
            font-size: 10px;
        }

        .total {
            font-size: 15px;
            font-weight: bold;
        }

        h2 {
            font-size: 18px;
            letter-spacing: 1px;
        }

        .note-box {
            border: 1px dashed #000;
            padding: 5px 7px;
            margin-top: 4px;
        }

        /* keep each block from splitting across a page edge */
        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <div class="receipt-wrap">

        @php
            $subtotal = $order->orderItems->sum(fn($item) => $item->qty * $item->price);

            $paymentMethod = strtoupper($order->payment->payment_method ?? $order->payment_method ?? 'CASH');
            $paymentStatus = strtoupper($order->payment->payment_status ?? 'UNPAID');
        @endphp

        <div class="center no-break">
            <h2>DARITA MART</h2>

            <div class="small">
                Phnom Penh, Cambodia
            </div>

            <div class="small">
                Tel: +855 xx xxx xxx
            </div>
        </div>

        <div class="divider"></div>

        <div class="no-break">
            <div>
                <b>Receipt #{{ $order->id }}</b>
            </div>

            <div>
                {{ $order->created_at->format('d-m-Y h:i A') }}
            </div>
        </div>

        <div class="divider"></div>

        <div class="no-break">
            <div class="bold">
                CUSTOMER
            </div>

            <div>
                {{ $order->user->full_name }}
            </div>

            <div>
                {{ $order->user->phone }}
            </div>
        </div>

        <div class="divider"></div>

        <div class="no-break">
            <div class="bold">
                PAYMENT
            </div>

            <div>
                Method: {{ $paymentMethod }}
            </div>

            <div>
                Status: {{ $paymentStatus }}
            </div>
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
                        <td>{{ \Illuminate\Support\Str::limit($item->product->name, 18) }}</td>
                        <td>{{ $item->qty }}</td>
                        <td class="right">${{ number_format($item->qty * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <table class="no-break">
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

            <tr>
                <td>Delivery Fee</td>
                <td class="right">
                    @if(($order->delivery_fee ?? 0) <= 0)
                        Free
                    @else
                        ${{ number_format($order->delivery_fee, 2) }}
                    @endif
                </td>
            </tr>

        </table>

        <div class="double-divider"></div>

        <table class="no-break">
            <tr>
                <td class="total">TOTAL</td>
                <td class="right total">${{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>

        <div class="double-divider"></div>

        <div class="no-break">
            <div class="bold">
                DELIVERY ADDRESS
            </div>

            <div>
                {{ $order->delivery_address }}
            </div>
        </div>

        <div class="divider"></div>

        @if($order->note)
            <div class="no-break">
                <div class="bold">
                    Customer Note
                </div>

                <div class="note-box">
                    {{ $order->note }}
                </div>
            </div>

            <div class="divider"></div>
        @endif

        <div class="center no-break">
            @if($paymentMethod === 'CASH' && $paymentStatus !== 'PAID')
                <b>CASH ON DELIVERY</b>
            @else
                <b>PAID</b>
            @endif
        </div>

        <div class="divider"></div>

        <div class="center no-break">
            Thank You.
            <br>
            Darita Mart
        </div>

    </div>

</body>

</html>