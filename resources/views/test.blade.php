<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>KHQR Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Optional Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container text-center mt-5">

        <h2>Scan to Pay</h2>

        @if ($qr)
            <div class="d-flex justify-content-center">
                {!! QrCode::size(220)->generate($qr) !!}
            </div>

            <h4>{{ number_format($amount, 0) }} ៛</h4>

            <p class="text-muted">
                Scan using ABA / Bakong App
            </p>
        @else
            <p class="alert alert-danger">⚠ Failed to generate QR</p>
        @endif

    </div>

    <!-- Script -->


</body>

</html>