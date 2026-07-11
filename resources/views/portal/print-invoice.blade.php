<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page { size: A4; margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; color: #1f2937; background: #eef1f8; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .page { width: 820px; min-height: 1120px; margin: 26px auto; background: #fff; box-shadow: 0 20px 60px rgba(16,24,40,0.14); display: flex; flex-direction: column; }

        /* Top accent header */
        .inv-head { background: linear-gradient(120deg, #4f46e5, #7c3aed); color: #fff; padding: 40px 48px; display: flex; justify-content: space-between; align-items: flex-start; }
        .brand-row { display: flex; align-items: center; gap: 14px; }
        .brand-mark { width: 50px; height: 50px; border-radius: 13px; background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.35); display: grid; place-items: center; }
        .brand-mark svg { width: 28px; height: 28px; }
        .brand-name { font-size: 20px; font-weight: 800; letter-spacing: -0.3px; }
        .brand-sub { font-size: 11px; opacity: 0.85; margin-top: 2px; }
        .brand-contact { font-size: 10.5px; opacity: 0.9; margin-top: 12px; line-height: 1.7; }
        .head-right { text-align: right; }
        .inv-title { font-size: 34px; font-weight: 800; letter-spacing: 2px; }
        .inv-num { font-size: 12px; opacity: 0.9; margin-top: 4px; }
        .status-tag { display: inline-block; margin-top: 14px; padding: 6px 16px; border-radius: 20px; font-size: 11px; font-weight: 800; letter-spacing: 0.5px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4); }

        .body { padding: 36px 48px; flex: 1; }

        /* Parties */
        .parties { display: flex; justify-content: space-between; gap: 30px; margin-bottom: 34px; }
        .party { flex: 1; }
        .party .lbl { font-size: 10px; text-transform: uppercase; letter-spacing: 1.2px; color: #9ca3af; font-weight: 700; margin-bottom: 8px; }
        .party .nm { font-size: 15px; font-weight: 700; color: #111827; margin-bottom: 3px; }
        .party .ln { font-size: 12px; color: #4b5563; margin-bottom: 2px; }
        .party.right { text-align: right; }

        /* Items table */
        .items { width: 100%; border-collapse: collapse; margin-bottom: 24px; border-radius: 10px; overflow: hidden; }
        .items thead th { background: #111827; color: #fff; font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.6px; padding: 13px 16px; text-align: left; }
        .items thead th.r { text-align: right; }
        .items tbody td { padding: 18px 16px; border-bottom: 1px solid #eef0f4; font-size: 13px; color: #374151; vertical-align: top; }
        .items tbody td.r { text-align: right; }
        .items .it-title { font-weight: 700; color: #111827; font-size: 13.5px; }
        .items .it-desc { color: #6b7280; font-size: 11.5px; margin-top: 4px; }

        /* Summary */
        .summary-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 30px; margin-bottom: 30px; }
        .pay-note { flex: 1; }
        .pay-note .lbl { font-size: 10px; text-transform: uppercase; letter-spacing: 1.2px; color: #9ca3af; font-weight: 700; margin-bottom: 6px; }
        .pay-note p { font-size: 11.5px; color: #6b7280; line-height: 1.7; max-width: 300px; }
        .totals { width: 300px; }
        .totals .tr { display: flex; justify-content: space-between; padding: 9px 16px; font-size: 13px; }
        .totals .tr .l { color: #6b7280; }
        .totals .tr .v { font-weight: 700; color: #111827; }
        .totals .sub { border-bottom: 1px solid #eef0f4; }
        .totals .grand { background: linear-gradient(120deg, #4f46e5, #7c3aed); color: #fff; border-radius: 10px; margin-top: 8px; padding: 14px 16px; }
        .totals .grand .l { color: #e0e7ff; font-size: 13px; font-weight: 600; }
        .totals .grand .v { color: #fff; font-size: 17px; }

        /* Signature + seal */
        .sign-area { display: flex; justify-content: space-between; align-items: flex-end; padding-top: 26px; border-top: 1px dashed #d1d5db; }
        .seal-col, .sig-col { width: 46%; text-align: center; }
        .col-lbl { font-size: 10px; text-transform: uppercase; letter-spacing: 1.2px; color: #9ca3af; font-weight: 700; margin-bottom: 14px; }
        .seal { width: 124px; height: 124px; margin: 0 auto; border: 3px double #4f46e5; border-radius: 50%; color: #4f46e5; display: flex; flex-direction: column; align-items: center; justify-content: center; transform: rotate(-9deg); opacity: 0.92; }
        .seal .s1 { font-size: 12px; font-weight: 800; letter-spacing: 0.5px; }
        .seal .s2 { font-size: 8.5px; font-weight: 700; margin-top: 2px; }
        .seal .s3 { font-size: 10px; margin-top: 8px; }
        .seal .s4 { font-size: 7px; letter-spacing: 1.5px; margin-top: 4px; }
        .sig svg { display: block; margin: 0 auto -4px; }
        .sig-line { border-top: 1.5px solid #374151; width: 210px; margin: 0 auto; }
        .sig-name { font-size: 14px; font-weight: 800; color: #111827; margin-top: 8px; }
        .sig-desig { font-size: 11px; color: #6b7280; margin-top: 1px; }
        .sig-company { font-size: 11px; color: #4f46e5; font-weight: 700; margin-top: 1px; }
        .sig-auth { font-size: 8.5px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }

        .foot { background: #f8fafc; padding: 16px 48px; text-align: center; font-size: 10.5px; color: #9ca3af; border-top: 1px solid #eef0f4; }
        .foot strong { color: #4f46e5; }

        .print-btn { position: fixed; top: 18px; right: 18px; background: #4f46e5; color: #fff; border: none; padding: 11px 20px; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: inherit; box-shadow: 0 8px 20px rgba(79,70,229,0.35); z-index: 10; }
        @media print { .print-btn { display: none; } body { background: #fff; } .page { margin: 0; box-shadow: none; width: 100%; min-height: 100vh; } }
    </style>
</head>
<body onload="window.print()">
    <button class="print-btn" onclick="window.print()">🖨 Print Invoice</button>

    <div class="page">
        <div class="inv-head">
            <div>
                <div class="brand-row">
                    <span class="brand-mark">
                        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g stroke="#fff" stroke-width="2.6" stroke-linecap="round"><path d="M12 27V22"/><path d="M20 27V15"/><path d="M28 27V19"/></g>
                            <path d="M11 27h18" stroke="#fff" stroke-width="2.6" stroke-linecap="round"/><circle cx="28" cy="13" r="3" fill="#fff"/>
                        </svg>
                    </span>
                    <div>
                        <div class="brand-name">Prime Byte Software Solution</div>
                        <div class="brand-sub">Accounting &amp; Project Management</div>
                    </div>
                </div>
                <div class="brand-contact">Dhaka, Bangladesh<br>info@primebyte.com &nbsp;·&nbsp; +880 1XXX-XXXXXX</div>
            </div>
            <div class="head-right">
                <div class="inv-title">INVOICE</div>
                <div class="inv-num"># {{ $invoice->invoice_number }}</div>
                <div><span class="status-tag">{{ strtoupper($invoice->status) }}</span></div>
            </div>
        </div>

        <div class="body">
            <div class="parties">
                <div class="party">
                    <div class="lbl">Billed To</div>
                    <div class="nm">{{ $invoice->client->name ?? 'N/A' }}</div>
                    @if($invoice->client->company ?? false)<div class="ln">{{ $invoice->client->company }}</div>@endif
                    @if($invoice->client->email ?? false)<div class="ln">{{ $invoice->client->email }}</div>@endif
                    @if($invoice->client->phone ?? false)<div class="ln">{{ $invoice->client->phone }}</div>@endif
                </div>
                <div class="party right">
                    <div class="lbl">Invoice Details</div>
                    <div class="ln"><strong>Date:</strong> {{ $invoice->invoice_date }}</div>
                    <div class="ln"><strong>Project:</strong> {{ $invoice->project->name ?? 'N/A' }}</div>
                    <div class="ln"><strong>Bank:</strong> {{ $invoice->bank->name ?? 'N/A' }}</div>
                    @if($invoice->handover_to)<div class="ln"><strong>Handover To:</strong> {{ $invoice->handover_to }}</div>@endif
                </div>
            </div>

            <table class="items">
                <thead>
                    <tr><th>Description</th><th class="r">Amount</th><th class="r">Paid</th><th class="r">Balance</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="it-title">{{ $invoice->project->name ?? 'Project Invoice' }}</div>
                            @if($invoice->description)<div class="it-desc">{{ $invoice->description }}</div>@endif
                        </td>
                        <td class="r">Tk{{ number_format($invoice->amount, 2) }}</td>
                        <td class="r">Tk{{ number_format($invoice->paid_amount, 2) }}</td>
                        <td class="r">Tk{{ number_format($invoice->balance_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="summary-row">
                <div class="pay-note">
                    <div class="lbl">Notes</div>
                    <p>Thank you for your business. Please retain this invoice for your records. Payments are recorded against the project's outstanding balance.</p>
                </div>
                <div class="totals">
                    <div class="tr sub"><span class="l">Invoice Amount</span><span class="v">Tk{{ number_format($invoice->amount, 2) }}</span></div>
                    <div class="tr sub"><span class="l">Paid</span><span class="v">Tk{{ number_format($invoice->paid_amount, 2) }}</span></div>
                    <div class="tr grand"><span class="l">Balance Due</span><span class="v">Tk{{ number_format($invoice->balance_amount, 2) }}</span></div>
                </div>
            </div>

            <div class="sign-area">
                <div class="seal-col">
                    <div class="col-lbl">Company Seal</div>
                    <div class="seal">
                        <div class="s1">PRIME BYTE</div>
                        <div class="s2">SOFTWARE SOLUTION</div>
                        <div class="s3">★ DHAKA ★</div>
                        <div class="s4">OFFICIAL SEAL</div>
                    </div>
                </div>
                <div class="sig-col">
                    <div class="col-lbl">Authorized By</div>
                    <div class="sig">
                        <svg width="180" height="52" viewBox="0 0 180 52">
                            <path d="M8 38 C20 8 32 8 36 32 C39 48 48 18 58 30 C66 40 74 12 86 32 C94 44 106 14 118 32 C126 44 138 16 150 32 C158 42 168 28 174 20" fill="none" stroke="#1e293b" stroke-width="2.2" stroke-linecap="round"/>
                            <path d="M78 44 L 172 38" fill="none" stroke="#1e293b" stroke-width="1"/>
                        </svg>
                    </div>
                    <div class="sig-line"></div>
                    <div class="sig-name">Samiul Alam Himel</div>
                    <div class="sig-desig">Managing Director</div>
                    <div class="sig-company">Prime Byte Software Solution</div>
                    <div class="sig-auth">Authorized Signatory</div>
                </div>
            </div>
        </div>

        <div class="foot">This is a computer-generated invoice from <strong>Prime Byte Software Solution</strong> · Dhaka, Bangladesh</div>
    </div>
</body>
</html>
