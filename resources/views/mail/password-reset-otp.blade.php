<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>AIN Password Reset OTP</title></head>
<body style="margin:0;background:#f3f6fb;font-family:Arial,sans-serif;color:#182230;padding:32px 12px;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0"><tr><td align="center">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:580px;background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 12px 34px rgba(16,24,40,.1)">
<tr><td style="padding:30px 36px;background:#243b72;background-image:linear-gradient(135deg,#101828,#4f46e5);color:#fff"><div style="display:inline-block;background:#fff;color:#273b73;font-size:18px;font-weight:800;border-radius:10px;padding:9px 12px">AIN</div><h1 style="margin:24px 0 6px;font-size:26px">Password reset OTP</h1><p style="margin:0;color:#dbe4ff;font-size:14px">Use this one-time code to continue securely.</p></td></tr>
<tr><td style="padding:32px 36px 12px"><p style="font-size:16px;font-weight:700;margin:0 0 14px">Hello {{ $name }},</p><p style="font-size:14px;line-height:1.7;color:#667085;margin:0">Enter the following verification code in the AIN app:</p></td></tr>
<tr><td align="center" style="padding:24px 36px"><div style="display:inline-block;background:#f1f3ff;border:1px solid #d9dcff;border-radius:13px;padding:17px 28px;color:#3730a3;font-size:32px;font-weight:800;letter-spacing:9px">{{ $otp }}</div></td></tr>
<tr><td style="padding:0 36px 30px;color:#667085;font-size:13px;line-height:1.7"><strong style="color:#182230">Expires in {{ $expiresIn }} minutes.</strong><br>Never share this OTP with anyone. If you did not request a reset, ignore this email.</td></tr>
<tr><td align="center" style="padding:20px;background:#f8fafc;border-top:1px solid #eaecf0;color:#98a2b3;font-size:12px">&copy; {{ date('Y') }} Assignment In Need &middot; Secure notification</td></tr>
</table></td></tr></table></body></html>
