<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width"/>
    <title>Your OTP Code</title>
  </head>
  <body style="margin:0;padding:0;background:#f5f5f7;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
      <tr>
        <td align="center" style="padding:30px 16px;">
          <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;border-radius:8px;overflow:hidden;font-family:Arial, Helvetica, sans-serif;">
            <tr>
              <td style="padding:28px 32px;text-align:left;">
                <h1 style="margin:0;font-size:20px;color:#111;font-weight:600;">Your verification code</h1>
                <p style="margin:8px 0 24px;color:#4b5563;font-size:14px;">Use the following one-time code to verify your email address.</p>

                <table role="presentation" cellpadding="0" cellspacing="0" style="margin:18px 0 26px;">
                  <tr>
                    <td align="center" style="background:#fafafa;border:1px solid #e5e7eb;padding:18px 28px;border-radius:6px;">
                      <span style="font-size:28px;letter-spacing:2px;font-weight:700;color:#111;">{{ $otp }}</span>
                    </td>
                  </tr>
                </table>

                <p style="margin:0;color:#6b7280;font-size:13px;">This code will expire in <strong>5 minutes</strong>. If you did not request this code, you can safely ignore this email.</p>

                <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">

                <p style="margin:0;color:#9ca3af;font-size:12px;">If you have any problems, contact our support.</p>
              </td>
            </tr>

            <tr>
              <td style="background:#fafafa;padding:12px 32px;text-align:center;color:#9ca3af;font-size:12px;">
                © {{ date('Y') }} Skin Care App — All rights reserved
              </td>
            </tr>

          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
