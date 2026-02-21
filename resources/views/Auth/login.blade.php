<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}"> 
    
</head>

<body>

    <div class="login-container">
        <h2>Admin Login</h2>
        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="username or email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="footer-text">
            &copy; 2026 Darita Mart. All rights reserved.
        </div>
    </div>

</body>

</html>
