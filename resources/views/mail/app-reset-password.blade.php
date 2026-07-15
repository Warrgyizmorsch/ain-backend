<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset your AIN password</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;font-family:Arial,'Helvetica Neue',sans-serif;color:#182230;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f3f6fb;padding:36px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:600px;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 12px 35px rgba(16,24,40,.09);">
                <tr>
                    <td style="padding:0;background:linear-gradient(135deg,#101828 0%,#253b73 55%,#4f46e5 100%);">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td style="padding:30px 36px;">
                                    <div style="display:inline-block;background:#ffffff;color:#273b73;font-size:18px;font-weight:800;letter-spacing:1px;border-radius:10px;padding:9px 12px;">AIN</div>
                                    <div style="margin-top:24px;color:#ffffff;font-size:27px;line-height:1.25;font-weight:750;letter-spacing:-.4px;">Reset your password</div>
                                    <div style="margin-top:8px;color:#dbe4ff;font-size:14px;line-height:1.6;">A secure password reset was requested for your mobile app account.</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:34px 36px 12px;">
                        <p style="margin:0 0 16px;font-size:17px;line-height:1.6;font-weight:700;">Hello {{ $name }},</p>
                        <p style="margin:0;color:#475467;font-size:15px;line-height:1.75;">We received a request to reset the password for <strong style="color:#182230;">{{ $email }}</strong>. Tap the button below to continue securely in the AIN app.</p>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding:24px 36px;">
                        <a href="{{ $resetUrl }}" style="display:inline-block;background:#4f46e5;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:15px 28px;border-radius:10px;box-shadow:0 8px 18px rgba(79,70,229,.24);">Reset Password in App</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 36px 26px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f8fafc;border:1px solid #e7ecf3;border-radius:12px;">
                            <tr>
                                <td width="48" valign="top" style="padding:18px 0 18px 18px;font-size:22px;">&#128274;</td>
                                <td style="padding:17px 18px 17px 8px;color:#475467;font-size:13px;line-height:1.65;">
                                    <strong style="display:block;color:#182230;font-size:14px;margin-bottom:3px;">Secure link</strong>
                                    This link expires in {{ $expiresIn }} minutes and can only be used to reset this account's password.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 36px 34px;color:#667085;font-size:13px;line-height:1.7;">
                        If you did not request this change, no action is required. Your current password will remain unchanged.
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding:22px 28px;background:#f8fafc;border-top:1px solid #eaecf0;color:#98a2b3;font-size:12px;line-height:1.6;">
                        &copy; {{ date('Y') }} Assignment In Need &nbsp;&middot;&nbsp; Secure account notification
                    </td>
                </tr>
            </table>
            <div style="max-width:560px;margin:18px auto 0;color:#98a2b3;font-size:11px;line-height:1.6;text-align:center;">This is an automated security email. Please do not reply.</div>
        </td>
    </tr>
</table>
</body>
</html>
