<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Products Report</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            background: #fff;
        }

        /* ── Header ── */
        .header {
            padding: 24px 28px 16px;
            border-bottom: 2px solid #6366f1;
            display: flex;
            /* dompdf supports basic flex on block context */
        }

        .header-left {
            float: left;
        }

        .header-right {
            float: right;
            text-align: right;
        }

        .clearfix::after {
            content: '';
            display: table;
            clear: both;
        }

        .brand {
            font-size: 20px;
            font-weight: 700;
            color: #6366f1;
            letter-spacing: -0.5px;
        }

        .report-title {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
            margin-top: 2px;
        }

        .report-meta {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* ── Summary pills ── */
        .summary {
            padding: 12px 28px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-inner {
            overflow: hidden;
        }

        .pill {
            float: left;
            margin-right: 10px;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 10px;
        }

        .pill-label {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            display: block;
            margin-bottom: 1px;
        }

        .pill-value {
            font-size: 16px;
            font-weight: 700;
        }

        .pill-total {
            background: #f3f4f6;
        }

        .pill-active {
            background: #d1fae5;
        }

        .pill-inactive {
            background: #fee2e2;
        }

        .pill-lowstock {
            background: #fef3c7;
        }

        .pill-total .pill-value {
            color: #374151;
        }

        .pill-active .pill-value {
            color: #065f46;
        }

        .pill-inactive .pill-value {
            color: #991b1b;
        }

        .pill-lowstock .pill-value {
            color: #92400e;
        }

        /* ── Table ── */
        .table-wrap {
            padding: 16px 28px 28px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }

        thead tr {
            background: #6366f1;
            color: #fff;
        }

        thead th {
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            white-space: nowrap;
        }

        thead th.center {
            text-align: center;
        }

        thead th.right {
            text-align: right;
        }

        tbody tr {
            border-bottom: 1px solid #f3f4f6;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        td {
            padding: 7px 10px;
            color: #374151;
            vertical-align: middle;
        }

        td.center {
            text-align: center;
        }

        td.right {
            text-align: right;
        }

        td.muted {
            color: #9ca3af;
            font-size: 10px;
        }

        .product-name {
            font-weight: 600;
            color: #111827;
        }

        .product-id {
            font-size: 9.5px;
            color: #9ca3af;
        }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 100px;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge-active {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Stock badge */
        .stock-low {
            color: #b45309;
            font-weight: 700;
        }

        /* ── Footer ── */
        .footer {
            padding: 10px 28px;
            border-top: 1px solid #e5e7eb;
            font-size: 9.5px;
            color: #9ca3af;
            overflow: hidden;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }
    </style>
</head>

<body>

    {{-- ── HEADER ── --}}
    <div class="header clearfix">
        <div class="header-left">
            <div class="brand">Admin Panel</div>
            <div class="report-title">Products Report</div>
            <div class="report-meta">Generated: {{ now()->format('d M Y, H:i') }}</div>
        </div>
        <div class="header-right">
            <div class="report-meta">Total records: {{ $products->count() }}</div>
            <div class="report-meta">Period: All time</div>
        </div>
    </div>

    {{-- ── SUMMARY PILLS ── --}}
    @php
        $total = $products->count();
        $active = $products->where('status', 1)->count();
        $inactive = $products->where('status', 0)->count();
        $lowStock = $products->filter(fn($p) => ($p->quantity ?? 0) < 10)->count();
    @endphp

    <div class="summary">
        <div class="summary-inner">
            <div class="pill pill-total">
                <span class="pill-label">Total</span>
                <span class="pill-value">{{ number_format($total) }}</span>
            </div>
            <div class="pill pill-active">
                <span class="pill-label">Active</span>
                <span class="pill-value">{{ number_format($active) }}</span>
            </div>
            <div class="pill pill-inactive">
                <span class="pill-label">Inactive</span>
                <span class="pill-value">{{ number_format($inactive) }}</span>
            </div>
            <div class="pill pill-lowstock">
                <span class="pill-label">Low Stock</span>
                <span class="pill-value">{{ number_format($lowStock) }}</span>
            </div>
        </div>
    </div>

    {{-- ── TABLE ── --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th class="right">Cost</th>
                    <th class="right">Price</th>
                    <th class="center">Stock</th>
                    <th class="center">Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $i => $p)
                    <tr>
                        {{-- # --}}
                        <td class="muted">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>

                        {{-- Product --}}
                        <td>
                            <div class="product-name">{{ $p->name }}</div>
                            {{-- <div class="product-id">#{{ $p->id }}</div> --}}
                        </td>

                        {{-- Category --}}
                        <td class="muted">{{ $p->category->name ?? '—' }}</td>

                        {{-- Brand --}}
                        <td class="muted">{{ $p->brand->name ?? '—' }}</td>

                        {{-- Cost Price --}}
                        <td class="right muted">${{ number_format($p->cost_price ?? 0, 2) }}</td>

                        {{-- Sale Price --}}
                        <td class="right" style="font-weight:600; color:#111827;">
                            ${{ number_format($p->sale_price ?? 0, 2) }}
                        </td>

                        {{-- Stock --}}
                        <td class="center {{ ($p->quantity ?? 0) < 10 ? 'stock-low' : '' }}">
                            {{ $p->quantity ?? 0 }}
                            @if(($p->quantity ?? 0) < 10)
                                ⚠
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="center">
                            @if(($p->status ?? 1) == 1)
                                <span class="badge badge-active">Active</span>
                            @else
                                <span class="badge badge-inactive">Inactive</span>
                            @endif
                        </td>

                        {{-- Created --}}
                        <td class="muted">{{ $p->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;padding:20px;color:#9ca3af;">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer clearfix">
        <div class="footer-left">Confidential — Admin use only</div>
        <div class="footer-right">{{ now()->format('d M Y') }}</div>
    </div>

</body>

</html>