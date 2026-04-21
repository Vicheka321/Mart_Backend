<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OTP Code</title>
    
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:20px;">
<tr>
<td align="center">

<!-- CARD -->
<table width="420" cellpadding="0" cellspacing="0"
style="background:#ffffff;border-radius:16px;padding:30px;text-align:center;
box-shadow:0 10px 25px rgba(0,0,0,0.05);">

    <!-- PROFILE -->
    <tr>
        <td align="center">
            <div style="
                width:60px;
                height:60px;
                border-radius:999px;
                background:#6366f1;
                color:white;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:22px;
                font-weight:bold;
                margin:0 auto 10px;
            ">
                D
            </div>
        </td>
    </tr>

    <!-- APP NAME -->
    <tr>
        <td style="font-size:18px;font-weight:600;color:#111827;">
            Darita Mart
        </td>
    </tr>

    <!-- TITLE -->
    <tr>
        <td style="padding-top:15px;font-size:14px;color:#6b7280;">
            Your verification code
        </td>
    </tr>

    <!-- OTP -->
    <tr>
        <td style="padding:25px 0;">
            <div style="
                font-size:34px;
                font-weight:700;
                letter-spacing:8px;
                color:#111827;
                background:#f9fafb;
                padding:18px 24px;
                border-radius:10px;
                display:inline-block;
                border:1px solid #e5e7eb;
            ">
                {{ $otp }}
            </div>
        </td>
    </tr>

    <!-- INFO -->
    <tr>
        <td style="font-size:13px;color:#9ca3af;">
            This code expires in <b style="color:#111827;">5 minutes</b>
        </td>
    </tr>

    <!-- DIVIDER -->
    <tr>
        <td style="padding:20px 0;">
            <hr style="border:none;border-top:1px solid #e5e7eb;">
        </td>
    </tr>

    <!-- FOOTER -->
    <tr>
        <td style="font-size:12px;color:#9ca3af;">
            If you didn’t request this, you can safely ignore this email.
        </td>
    </tr>

</table>

</td>
</tr>
</table>

</body>
</html>