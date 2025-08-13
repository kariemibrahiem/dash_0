<!DOCTYPE html>
<html lang="en" style="margin:0;padding:0;">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your OTP Code</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: Arial, sans-serif;">

  <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td align="center" style="padding: 20px 0;">
        
        <!-- Main container -->
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
          
          <!-- Header -->
          <tr>
            <td align="center" bgcolor="#4CAF50" style="padding: 20px;">
              <h1 style="color:#ffffff; margin:0; font-size: 24px;">🔒 Verification Code</h1>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding: 30px;">
              <p style="font-size:16px; color:#333333; margin:0 0 15px 0;">Hello,</p>
              <p style="font-size:16px; color:#333333; margin:0 0 25px 0;">
                Use the OTP code below to complete your verification process. This code will expire in 5 minutes.
              </p>
              
              <p style="text-align:center; margin: 30px 0;">
                <span style="display:inline-block; font-size:28px; letter-spacing:8px; font-weight:bold; color:#4CAF50;">
                  {{ $otp }}
                </span>
              </p>

              <p style="font-size:14px; color:#777777; margin:0;">
                If you did not request this code, please ignore this email or contact support.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td bgcolor="#f4f4f4" style="padding: 20px; text-align:center; font-size:12px; color:#888888;">
              &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>

</body>
</html>
