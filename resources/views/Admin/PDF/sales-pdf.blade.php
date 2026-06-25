<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Sales Report</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
            padding: 24px;
            background: #ffffff;
        }

        .page {
            width: 100%;
        }

        /* =========================
           HEADER
        ========================= */
        .report-header {
            margin-bottom: 18px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 14px;
        }

        .report-title {
            font-size: 24px;
            font-weight: bold;
            color: #111827;
            margin: 0 0 6px 0;
        }

        .report-subtitle {
            font-size: 11px;
            color: #6b7280;
            margin: 0;
        }

        .report-topbar {
            width: 100%;
            margin-bottom: 18px;
        }

        .report-topbar td {
            vertical-align: top;
        }

        .brand-box {
            text-align: right;
        }

        .brand-badge {
            display: inline-block;
            background: #eef2ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: bold;
        }

        /* =========================
           FILTER BOX
        ========================= */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
        }

        .filter-box {
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 16px;
        }

        .filter-table {
            width: 100%;
            border-collapse: collapse;
        }

        .filter-table td {
            width: 33.33%;
            padding: 4px 6px 4px 0;
            vertical-align: top;
            font-size: 10.5px;
            color: #4b5563;
            border: none;
        }

        .filter-label {
            color: #6b7280;
            font-weight: bold;
        }

        .filter-value {
            color: #111827;
        }

        /* =========================
           SUMMARY CARDS
        ========================= */
        .summary-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin: 0 -10px 16px -10px;
        }

        .summary-grid td {
            width: 25%;
            vertical-align: top;
            border: none;
            padding: 0;
        }

        .summary-card {
            border: 1px solid #e5e7eb;
            background: #ffffff;
            border-radius: 12px;
            padding: 12px;
            min-height: 76px;
        }

        .summary-label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            line-height: 1.2;
        }

        .summary-note {
            margin-top: 6px;
            font-size: 9px;
            color: #9ca3af;
        }

        /* =========================
           TABLE
        ========================= */
        .table-wrap {
            margin-top: 8px;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .report-table thead th {
            background: #111827;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .35px;
            padding: 10px 8px;
            border: 1px solid #1f2937;
            text-align: left;
        }

        .report-table tbody td {
            border: 1px solid #e5e7eb;
            padding: 9px 8px;
            font-size: 10px;
            color: #374151;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .report-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .date-cell {
            font-weight: bold;
            color: #111827;
        }

        .money {
            font-weight: bold;
            color: #111827;
        }

        .paid {
            color: #065f46;
        }

        .discount {
            color: #b45309;
        }

        .total-row td {
            background: #eef2ff !important;
            font-weight: bold;
            color: #111827;
            border-color: #c7d2fe;
        }

        /* =========================
           FOOTER
        ========================= */
        .footer {
            margin-top: 18px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            font-size: 9.5px;
            color: #6b7280;
        }

        .footer table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer td {
            border: none;
            padding: 0;
            font-size: 9.5px;
            color: #6b7280;
        }

        .footer-right {
            text-align: right;
        }

        /* small helper */
        .muted {
            color: #6b7280;
        }
    </style>
</head>

<body>
    @php
        $filters = $filters ?? [];
        $summary = $summary ?? [
            'total_orders' => 0,
            'gross_sales' => 0,
            'paid_revenue' => 0,
            'total_discount' => 0,
            'average_order_value' => 0,
        ];
    @endphp

    <div class="page">
        {{-- ================= HEADER ================= --}}
        <table class="report-topbar">
            <tr>
                <td style="width: 70%;">
                    <div class="report-header">
                        <h1 class="report-title">Sales Report</h1>
                        <p class="report-subtitle">
                            Daily sales performance summary generated on
                            {{ now()->format('F d, Y h:i A') }}
                        </p>
                    </div>
                </td>
                <td class="brand-box" style="width: 30%;">
                    <span class="brand-badge">Exported PDF Report</span>
                </td>
            </tr>
        </table>

        {{-- ================= FILTERS ================= --}}
        <div class="section-title">Report Filters</div>
        <div class="filter-box">
            <table class="filter-table">
                <tr>
                    <td>
                        <span class="filter-label">Date Range:</span>
                        <span class="filter-value">
                            {{ $startDate->format('Y-m-d') }} → {{ $endDate->format('Y-m-d') }}
                        </span>
                    </td>

                    <td>
                        <span class="filter-label">Status:</span>
                        <span class="filter-value">
                            {{ !empty($filters['status']) ? ucfirst($filters['status']) : 'All' }}
                        </span>
                    </td>

                    <td>
                        <span class="filter-label">Payment Method:</span>
                        <span class="filter-value">
                            {{ !empty($filters['payment_method']) ? strtoupper($filters['payment_method']) : 'All' }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="filter-label">Payment Status:</span>
                        <span class="filter-value">
                            {{ !empty($filters['payment_status']) ? ucfirst($filters['payment_status']) : 'All' }}
                        </span>
                    </td>

                    <td>
                        <span class="filter-label">Province:</span>
                        <span class="filter-value">
                            {{ !empty($filters['province']) ? $filters['province'] : 'All' }}
                        </span>
                    </td>

                    <td>
                        <span class="filter-label">District:</span>
                        <span class="filter-value">
                            {{ !empty($filters['district']) ? $filters['district'] : 'All' }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="filter-label">Sangkat:</span>
                        <span class="filter-value">
                            {{ !empty($filters['sangkat']) ? $filters['sangkat'] : 'All' }}
                        </span>
                    </td>

                    <td>
                        <span class="filter-label">Street:</span>
                        <span class="filter-value">
                            {{ !empty($filters['street']) ? $filters['street'] : 'All' }}
                        </span>
                    </td>

                    <td>
                        <span class="filter-label">Keyword:</span>
                        <span class="filter-value">
                            {{ !empty($filters['keyword']) ? $filters['keyword'] : 'None' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ================= SUMMARY ================= --}}
        <div class="section-title">Summary</div>
        <table class="summary-grid">
            <tr>
                <td>
                    <div class="summary-card">
                        <div class="summary-label">Total Orders</div>
                        <div class="summary-value">
                            {{ number_format($summary['total_orders']) }}
                        </div>
                        <div class="summary-note">Orders matched by current filters</div>
                    </div>
                </td>

                <td>
                    <div class="summary-card">
                        <div class="summary-label">Gross Sales</div>
                        <div class="summary-value">
                            ${{ number_format($summary['gross_sales'], 2) }}
                        </div>
                        <div class="summary-note">Total sales before payment status split</div>
                    </div>
                </td>

                <td>
                    <div class="summary-card">
                        <div class="summary-label">Paid Revenue</div>
                        <div class="summary-value paid">
                            ${{ number_format($summary['paid_revenue'], 2) }}
                        </div>
                        <div class="summary-note">Only paid orders / payments counted</div>
                    </div>
                </td>

                <td>
                    <div class="summary-card">
                        <div class="summary-label">Total Discount</div>
                        <div class="summary-value discount">
                            ${{ number_format($summary['total_discount'], 2) }}
                        </div>
                        <div class="summary-note">
                            Avg Order:
                            ${{ number_format($summary['average_order_value'], 2) }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- ================= TABLE ================= --}}
        <div class="section-title">Daily Sales Breakdown</div>

        <div class="table-wrap">
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 22%;">Date</th>
                        <th style="width: 13%;" class="text-center">Orders</th>
                        <th style="width: 22%;" class="text-right">Gross Sales</th>
                        <th style="width: 20%;" class="text-right">Discount</th>
                        <th style="width: 23%;" class="text-right">Paid Revenue</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td class="date-cell">
                                {{ \Carbon\Carbon::parse($row->sale_date)->format('Y-m-d') }}
                            </td>

                            <td class="text-center">
                                {{ number_format($row->total_orders) }}
                            </td>

                            <td class="text-right money">
                                ${{ number_format((float) $row->gross_sales, 2) }}
                            </td>

                            <td class="text-right money discount">
                                ${{ number_format((float) $row->total_discount, 2) }}
                            </td>

                            <td class="text-right money paid">
                                ${{ number_format((float) $row->paid_revenue, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center muted" style="padding: 20px;">
                                No sales data found for the selected filters.
                            </td>
                        </tr>
                    @endforelse

                    @if(count($rows))
                        <tr class="total-row">
                            <td>TOTAL</td>
                            <td class="text-center">
                                {{ number_format($summary['total_orders']) }}
                            </td>
                            <td class="text-right">
                                ${{ number_format($summary['gross_sales'], 2) }}
                            </td>
                            <td class="text-right">
                                ${{ number_format($summary['total_discount'], 2) }}
                            </td>
                            <td class="text-right">
                                ${{ number_format($summary['paid_revenue'], 2) }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- ================= FOOTER ================= --}}
        <div class="footer">
            <table>
                <tr>
                    <td>
                        Exported from Sales Report System
                    </td>
                    <td class="footer-right">
                        Period: {{ $startDate->format('Y-m-d') }} → {{ $endDate->format('Y-m-d') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>