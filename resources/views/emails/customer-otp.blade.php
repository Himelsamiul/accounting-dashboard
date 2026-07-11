<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"></head>
<body style="margin:0; padding:0; background:#f1f4fa; font-family:Arial,Helvetica,sans-serif; color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f4fa; padding:28px 0;">
        <tr><td align="center">
            <table width="520" cellpadding="0" cellspacing="0" style="background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 8px 30px rgba(16,24,40,0.08);">
                <tr>
                    <td style="background:linear-gradient(135deg,#4f46e5,#7c3aed); padding:24px 32px; color:#fff;">
                        <div style="font-size:19px; font-weight:bold;">Prime Byte Software Solution</div>
                        <div style="font-size:12px; opacity:0.85;">Email Verification</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px 32px;">
                        <p style="margin:0 0 14px; font-size:15px;">Hello {{ $customer->name }},</p>
                        <p style="margin:0 0 22px; font-size:14px; line-height:1.7; color:#4b5563;">
                            Use the verification code below to confirm your email address and activate your client portal account.
                        </p>
                        <div style="text-align:center; margin:24px 0;">
                            <div style="font-size:12px; letter-spacing:1px; text-transform:uppercase; color:#9ca3af; margin-bottom:10px;">Your Code</div>
                            <div style="display:inline-block; background:#eef0fe; color:#4338ca; font-size:34px; font-weight:bold; letter-spacing:10px; padding:14px 28px; border-radius:10px;">{{ $otp }}</div>
                        </div>
                        <p style="margin:0; font-size:13px; color:#b91c1c; text-align:center;">⏱ This code expires in 2 minutes.</p>
                        <p style="margin:20px 0 0; font-size:13px; color:#6b7280;">If you didn't create an account, you can safely ignore this email.</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 32px; background:#f9fafb; border-top:1px solid #eef0f4; font-size:11px; color:#9ca3af; text-align:center;">
                        Never share this code with anyone. Prime Byte will never ask for it.
                    </td>
                </tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
