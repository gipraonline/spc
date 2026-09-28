<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password reset code</title>
</head>
<body style="margin:0;padding:0;background:#f3f6f3;font-family:'Segoe UI',Helvetica,Arial,sans-serif;color:#1a1a1a;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6f3;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="max-width:480px;width:100%;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 8px 28px rgba(7,78,48,.12);">
                    <tr><td style="height:6px;background:#1F5C2E;background-image:linear-gradient(90deg,#1F5C2E,#5E8D3D);font-size:0;line-height:0;">&nbsp;</td></tr>

                    <tr>
                        <td align="center" style="padding:30px 30px 6px;">
                            <img src="{{ $message->embed(public_path('dist/images/logos/spclogo.png')) }}" alt="SPC" width="130" style="display:block;border:0;height:auto;">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:14px 36px 4px;text-align:center;">
                            <h1 style="margin:0 0 10px;font-size:22px;color:#1F5C2E;">Reset your password</h1>
                            <p style="margin:0;font-size:15px;line-height:1.6;color:#555;">
                                Hi {{ $name }}, use the verification code below to reset your SPC account password.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:22px 36px 8px;">
                            <div style="display:inline-block;background:#f3f9f2;border:1.5px dashed #5E8D3D;border-radius:14px;padding:16px 26px;">
                                <span style="font-size:34px;font-weight:700;letter-spacing:12px;color:#1F5C2E;font-family:'Courier New',monospace;padding-left:12px;">{{ $code }}</span>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:8px 36px 6px;text-align:center;">
                            <p style="margin:0;font-size:14px;color:#555;">
                                This code expires in <strong>{{ $minutes }} minutes</strong> and can be used only once.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 36px 28px;">
                            <div style="background:#fff8e6;border-left:4px solid #f5a623;border-radius:8px;padding:12px 14px;font-size:13px;line-height:1.55;color:#6b5210;">
                                <strong>Didn't request this?</strong> You can safely ignore this email — your password will not change.
                                Never share this code with anyone. SPC staff will never ask for it.
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background:#f8faf8;padding:16px 20px;font-size:12px;color:#888;">
                            © {{ date('Y') }} SPC. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
