<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Products Report</title>

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

        .text-center {
            text-align: center;
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

    <h1>Products Report</h1>

    <div class="subtitle">
        Generated on {{ now()->format('F d, Y h:i A') }}
    </div>

    <div class="meta">
        Total Products: <strong>{{ $products->count() }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th width="6%">ID</th>
                <th width="10%">Image</th>
                <th width="24%">Name</th>
                <th width="14%">Category</th>
                <th width="14%">Brand</th>
                <th width="8%">Unit</th>
                <th width="8%">Qty</th>
                <th width="10%">Price</th>
                <th width="16%">Created At</th>
            </tr>
        </thead>

        <tbody>
            @forelse($products as $p)
                @php
                    $image = $p->firstImage;
                @endphp
                <tr>
                    <td class="text-center">
                        {{ $p->id }}
                    </td>
                    <td class="text-center">
                        @if($image)
                            <img
                                src="{{ $image->image_url }}"
                                width="32"
                                height="32">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>
                        {{ $p->name }}
                    </td>
                    <td>
                        {{ $p->category->name ?? '-' }}
                    </td>
                    <td>
                        {{ $p->brand->name ?? '-' }}
                    </td>
                    <td class="text-center">
                        {{ $p->unit }}
                    </td>
                    <td class="text-center">
                        {{ number_format($p->quantity) }}
                    </td>
                    <td>
                        ${{ number_format($p->sale_price, 2) }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($p->created_at)->format('Y-m-d') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">
                        No products found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Exported from Product Management System
    </div>

</body>

</html>