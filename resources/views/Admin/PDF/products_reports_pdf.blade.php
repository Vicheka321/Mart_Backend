<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Products Report</title>

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

        .profit-positive {
            color: #059669;
            font-weight: bold;
        }

        .profit-negative {
            color: #dc2626;
            font-weight: bold;
        }

        .stock-low {
            color: #dc2626;
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

    <h1>Products Report</h1>

    <div class="subtitle">
        Generated on {{ now()->format('F d, Y h:i A') }}
    </div>

    <div class="meta">

        Total Products :
        <strong>{{ $products->count() }}</strong>

    </div>

    <table>

        <thead>

            <tr>

                <th width="5%">ID</th>

                <th width="16%">Product</th>

                <th width="10%">Category</th>

                <th width="10%">Brand</th>

                <th width="9%">Cost Price</th>

                <th width="9%">Sale Price</th>

                <th width="7%">Stock</th>

                <th width="7%">Sold</th>

                <th width="10%">Revenue</th>

                <th width="10%">Profit</th>

                <th width="7%">Margin</th>

            </tr>

        </thead>

        <tbody>

            @forelse($products as $product)

                <tr>

                    <td class="text-center">

                        #{{ $product->id }}

                    </td>

                    <td>

                        <strong>

                            {{ $product->name }}

                        </strong>

                        @if($product->product_code)

                            <br>

                            <span style="font-size:9px;color:#888;">

                                {{ $product->product_code }}

                            </span>

                        @endif

                    </td>

                    <td>

                        {{ optional($product->category)->name ?? 'N/A' }}

                    </td>

                    <td>

                        {{ optional($product->brand)->name ?? 'N/A' }}

                    </td>

                    <td class="text-right">

                        ${{ number_format($product->cost_price, 2) }}

                    </td>

                    <td class="text-right">

                        ${{ number_format($product->sale_price, 2) }}

                    </td>

                    <td class="text-center {{ $product->quantity <= 5 ? 'stock-low' : '' }}">

                        {{ number_format($product->quantity) }}

                    </td>

                    <td class="text-center">

                        {{ number_format($product->sold_qty) }}

                    </td>

                    <td class="text-right">

                        ${{ number_format($product->revenue, 2) }}

                    </td>

                    <td class="text-right {{ $product->profit >= 0 ? 'profit-positive' : 'profit-negative' }}">

                        ${{ number_format($product->profit, 2) }}

                    </td>

                    <td class="text-center">

                        {{ $product->margin }}%

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="11" class="text-center">

                        No products found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

    <div class="footer">

        Exported from Product Report Management System

    </div>

</body>

</html>