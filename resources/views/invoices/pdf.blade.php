<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', Arial, sans-serif; color: #1f2937; font-size: 12px; line-height: 1.5; }
        .sheet { padding: 40px 44px; }

        /* Header band */
        .header { width: 100%; border-collapse: collapse; margin-bottom: 34px; }
        .header td { vertical-align: top; }
        .brand-name { font-size: 22px; font-weight: bold; color: #4f46e5; letter-spacing: -0.5px; }
        .brand-sub { font-size: 11px; color: #6b7280; margin-top: 3px; }
        .brand-contact { font-size: 10px; color: #9ca3af; margin-top: 8px; line-height: 1.6; }
        .inv-badge { text-align: right; }
        .inv-word { font-size: 26px; font-weight: bold; color: #111827; letter-spacing: 1px; }
        .inv-no { font-size: 12px; color: #6b7280; margin-top: 6px; }
        .status-tag { display: inline-block; margin-top: 10px; padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .status-Paid { background: #dcfce7; color: #15803d; }
        .status-Partial { background: #fef3c7; color: #b45309; }
        .status-Pending { background: #fee2e2; color: #b91c1c; }

        .divider { border: none; border-top: 2px solid #eef0fe; margin: 0 0 26px; }

        /* Meta columns */
        .meta { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .meta td { vertical-align: top; width: 50%; padding-right: 18px; }
        .meta-label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af; font-weight: bold; margin-bottom: 6px; }
        .meta-name { font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 3px; }
        .meta-line { font-size: 11px; color: #4b5563; margin-bottom: 2px; }

        /* Line items */
        .items { width: 100%; border-collapse: collapse; margin-bottom: 26px; }
        .items th { background: #4f46e5; color: #fff; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; padding: 11px 14px; text-align: left; }
        .items th.right, .items td.right { text-align: right; }
        .items td { padding: 13px 14px; border-bottom: 1px solid #e5e7eb; font-size: 12px; color: #374151; }
        .items .desc-title { font-weight: bold; color: #111827; }

        /* Totals */
        .totals { width: 100%; border-collapse: collapse; }
        .totals td { vertical-align: top; }
        .notes-box { font-size: 10px; color: #6b7280; padding-right: 24px; }
        .totals-table { width: 260px; border-collapse: collapse; float: right; }
        .totals-table td { padding: 8px 14px; font-size: 12px; }
        .totals-table .lbl { color: #6b7280; }
        .totals-table .val { text-align: right; font-weight: bold; color: #111827; }
        .totals-table .grand { background: #4f46e5; color: #fff; border-radius: 6px; }
        .totals-table .grand .lbl { color: #e0e7ff; font-size: 13px; }
        .totals-table .grand .val { color: #fff; font-size: 15px; }

        .footer { margin-top: 48px; padding-top: 16px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 10px; color: #9ca3af; }
        .footer strong { color: #4f46e5; }
    </style>
</head>
<body>
<div class="sheet">
    <table class="header">
        <tr>
            <td>
                <div class="brand-name">Prime Byte Software Solution</div>
                <div class="brand-sub">Accounting &amp; Project Management</div>
                <div class="brand-contact">
                    Dhaka, Bangladesh<br>
                    info@primebyte.com &nbsp;·&nbsp; +880 1XXX-XXXXXX
                </div>
            </td>
            <td class="inv-badge">
                <div class="inv-word">INVOICE</div>
                <div class="inv-no"># {{ $invoice->invoice_number }}</div>
                <span class="status-tag status-{{ $invoice->status }}">{{ strtoupper($invoice->status) }}</span>
            </td>
        </tr>
    </table>

    <hr class="divider">

    <table class="meta">
        <tr>
            <td>
                <div class="meta-label">Billed To</div>
                <div class="meta-name">{{ $invoice->client->name ?? 'N/A' }}</div>
                @if($invoice->client->company ?? false)<div class="meta-line">{{ $invoice->client->company }}</div>@endif
                @if($invoice->client->email ?? false)<div class="meta-line">{{ $invoice->client->email }}</div>@endif
                @if($invoice->client->phone ?? false)<div class="meta-line">{{ $invoice->client->phone }}</div>@endif
            </td>
            <td>
                <div class="meta-label">Invoice Details</div>
                <div class="meta-line"><strong>Date:</strong> {{ $invoice->invoice_date }}</div>
                <div class="meta-line"><strong>Project:</strong> {{ $invoice->project->name ?? 'N/A' }}</div>
                <div class="meta-line"><strong>Bank:</strong> {{ $invoice->bank->name ?? 'N/A' }}</div>
                <div class="meta-line"><strong>Handover To:</strong> {{ $invoice->handover_to ?: 'N/A' }}</div>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Amount</th>
                <th class="right">Paid</th>
                <th class="right">Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="desc-title">{{ $invoice->project->name ?? 'Project Invoice' }}</div>
                    @if($invoice->description)<div style="color:#6b7280; font-size:11px; margin-top:4px;">{{ $invoice->description }}</div>@endif
                </td>
                <td class="right">Tk{{ number_format($invoice->amount, 2) }}</td>
                <td class="right">Tk{{ number_format($invoice->paid_amount, 2) }}</td>
                <td class="right">Tk{{ number_format($invoice->balance_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td class="notes-box">
                <div class="meta-label">Notes</div>
                Thank you for your business. Please retain this invoice for your records.
                Payments are recorded against the project's outstanding balance.
            </td>
            <td>
                <table class="totals-table">
                    <tr><td class="lbl">Invoice Amount</td><td class="val">Tk{{ number_format($invoice->amount, 2) }}</td></tr>
                    <tr><td class="lbl">Paid</td><td class="val">Tk{{ number_format($invoice->paid_amount, 2) }}</td></tr>
                    <tr class="grand"><td class="lbl">Balance Due</td><td class="val">Tk{{ number_format($invoice->balance_amount, 2) }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer">
        Generated by <strong>Prime Byte Software Solution</strong> · This is a computer-generated invoice and requires no signature.
    </div>
</div>
</body>
</html>
