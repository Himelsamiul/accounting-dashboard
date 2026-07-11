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
                        <p style="margin:0 0 14px; font-size:15px;">Hello {{ $project->client->name ?? 'valued client' }},</p>
                        <p style="margin:0 0 20px; font-size:14px; line-height:1.7; color:#4b5563;">
                            Your project <strong>{{ $project->name }}</strong> has been registered in our system.
                            Use the tracking code below to check your project status, payment summary and invoices
                            on our client portal.
                        </p>
                        <div style="text-align:center; margin:26px 0;">
                            <div style="font-size:12px; letter-spacing:1px; text-transform:uppercase; color:#9ca3af; margin-bottom:8px;">Your Tracking Code</div>
                            <div style="display:inline-block; background:#eef0fe; color:#4338ca; font-size:24px; font-weight:bold; letter-spacing:3px; padding:14px 26px; border-radius:10px;">{{ $project->code }}</div>
                        </div>
                        <p style="margin:0 0 8px; font-size:13px; line-height:1.7; color:#6b7280;">
                            To view your project, register or sign in to the client portal, then enter this code on the
                            <strong>Track Your Project</strong> page.
                        </p>
                        <p style="margin:20px 0 0; font-size:13px; color:#6b7280;">Thank you,<br>Prime Byte Software Solution</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 32px; background:#f9fafb; border-top:1px solid #eef0f4; font-size:11px; color:#9ca3af; text-align:center;">
                        This is an automated message. Please keep your tracking code confidential.
                    </td>
                </tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
