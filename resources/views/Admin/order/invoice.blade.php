{{--
<!DOCTYPE html>
<html>

<head>
    <title>Invoice #{{ $order->id }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background: #f5f5f5;
        }

        .right {
            text-align: right;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>DARITA MART</h1>
        <h3>INVOICE #{{ $order->id }}</h3>
    </div>

    <p>
        <strong>Date:</strong>
        {{ $order->created_at->format('d M Y h:i A') }}
    </p>

    <hr>

    <h3>Customer Information</h3>

    <p>
        <strong>Name:</strong>
        {{ $order->user->full_name }}
    </p>

    <p>
        <strong>Phone:</strong>
        {{ $order->user->phone }}
    </p>

    <p>
        <strong>Address:</strong>
        {{ $order->delivery_address }}
    </p>

    <hr>

    <h3>Payment Information</h3>

    <p>
        <strong>Method:</strong>
        {{ strtoupper($order->payment->payment_method ?? $order->payment_method) }}
    </p>

    <p>
        <strong>Status:</strong>
        {{ strtoupper($order->payment->payment_status ?? 'UNPAID') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>

            @foreach($order->orderItems as $item)

            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->qty }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>
                    ${{ number_format($item->price * $item->qty, 2) }}
                </td>
            </tr>

            @endforeach

        </tbody>
    </table>

    <br>

    <div class="right total">
        Total:
        ${{ number_format($order->total_amount, 2) }}
    </div>

    <br><br>

    <div style="text-align:center">
        Thank you for shopping with us.
    </div>

    <script>
        window.onload = function () {
            window.print();
        }
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

</html>