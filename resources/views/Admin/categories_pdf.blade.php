<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Categories Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        thead {
            background-color: #bcd4e6;
        }

        th {
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }

        td {
            padding: 6px;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        img {
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <h2>Categories Report</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                {{-- <th>IMAGE</th> --}}
                <th>NAME</th>
                <th>CREATED</th>
            </tr>
        </thead>

        <tbody>
            @foreach($categories as $c)
                <tr>

                    <td>{{ $c->id }}</td>

                    {{-- <td>
                        @if($c->image)
                            <img src="{{ $c->image }}" width="40" height="40">
                        @else
                            N/A
                        @endif
                    </td> --}}

                    <td>{{ $c->name }}</td>

                    <td>{{ \Carbon\Carbon::parse($c->created_at)->format('Y-m-d') }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>