<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ trans("otp_verification") }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0px 2px 8px rgba(0,0,0,0.1);">
        
        <h2 style="color: #333;">🔐 {{ trans("verify_your_email") }}</h2>
        <p>{{ trans("hello") }},</p>
        <p>{{ trans("thank_you_for_registering") }} <strong>{{ trans("traders_platform") }}</strong>.</p>
        <p>{{ trans("your_otp_is") }}</p>

        <div style="text-align: center; margin: 20px 0;">
            <span style="display: inline-block; background: #007bff; color: #fff; font-size: 24px; font-weight: bold; padding: 12px 24px; border-radius: 6px;">
                {{ $otp }}
            </span>
        </div>

        <p>{{ trans("otp_valid_for") }} <strong>10 {{ trans("minutes") }}</strong>. {{ trans("do_not_share") }}</p>

        <p style="margin-top: 30px;">{{ trans("best_regards") }},<br>
        <strong>{{ trans("traders_platform_team") }}</strong></p>
    </div>

</body>
</html>
