<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Brands Report</title>

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

    <h1>Brands Report</h1>

    <div class="subtitle">
        Generated on {{ now()->format('F d, Y h:i A') }}
    </div>

    <div class="meta">
        Total Brands: <strong>{{ $brands->count() }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">ID</th>
                <th width="40%">Name</th>
                <th width="25%">Country</th>
                <th width="25%">Created At</th>
            </tr>
        </thead>

        <tbody>
            @forelse($brands as $b)
                <tr>
                    <td class="text-center">
                        {{ $b->id }}
                    </td>
                    <td>
                        {{ $b->name }}
                    </td>
                    <td>
                        {{ $b->country ?? 'N/A' }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($b->created_at)->format('Y-m-d') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        No brands found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Exported from Brand Management System
    </div>

</body>

</html>