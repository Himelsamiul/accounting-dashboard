<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"></head>
<body style="margin:0; padding:0; background:#f1f4fa; font-family:Arial,Helvetica,sans-serif; color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f4fa; padding:28px 0;">
        <tr><td align="center">
            <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 8px 30px rgba(16,24,40,0.08);">
                <tr>
                    <td style="background:linear-gradient(135deg,#4f46e5,#7c3aed); padding:26px 32px; color:#ffffff;">
                        <div style="font-size:20px; font-weight:bold;">Prime Byte Software Solution</div>
                        <div style="font-size:13px; opacity:0.85;">Project Tracking</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px 32px;">
                        <p style="margin:0 0 14px; font-size:15px;">Hello {{ $clientName ?: 'valued client' }},</p>
                        <p style="margin:0 0 20px; font-size:14px; line-height:1.7; color:#4b5563;">
                            As requested, here {{ $projects->count() === 1 ? 'is your project tracking code' : 'are your project tracking codes' }}.
                            Use {{ $projects->count() === 1 ? 'it' : 'them' }} on our client portal to check project status, payment summary and invoices.
                        </p>
                        @foreach($projects as $p)
                        <div style="margin:0 0 14px; border:1px solid #eef0f4; border-radius:10px; padding:14px 16px;">
                            <div style="font-size:14px; font-weight:bold; color:#111827;">{{ $p->name }}</div>
                            <div style="margin-top:8px;">
                                <span style="display:inline-block; background:#eef0fe; color:#4338ca; font-size:18px; font-weight:bold; letter-spacing:2px; padding:8px 16px; border-radius:8px;">{{ $p->code }}</span>
                            </div>
                        </div>
                        @endforeach
                        <p style="margin:18px 0 0; font-size:13px; color:#6b7280;">Thank you,<br>Prime Byte Software Solution</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 32px; background:#f9fafb; border-top:1px solid #eef0f4; font-size:11px; color:#9ca3af; text-align:center;">
                        This is an automated message. Please keep your tracking codes confidential.
                    </td>
                </tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
