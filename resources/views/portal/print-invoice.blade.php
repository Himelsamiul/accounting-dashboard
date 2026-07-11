<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', Arial, sans-serif; color: #1f2937; font-size: 13px; padding: 40px 44px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
        .brand-name { font-size: 22px; font-weight: bold; color: #4f46e5; }
        .brand-sub { font-size: 11px; color: #6b7280; margin-top: 3px; }
        .inv-word { font-size: 26px; font-weight: bold; text-align: right; }
        .inv-no { font-size: 12px; color: #6b7280; text-align: right; margin-top: 6px; }
        .status-tag { display: inline-block; margin-top: 10px; padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .status-Paid { background: #dcfce7; color: #15803d; }
        .status-Partial { background: #fef3c7; color: #b45309; }
        .status-Pending { background: #fee2e2; color: #b91c1c; }
        .divider { border: none; border-top: 2px solid #eef0fe; margin: 0 0 26px; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .meta .lbl { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af; font-weight: bold; margin-bottom: 6px; }
        .meta .name { font-size: 14px; font-weight: bold; margin-bottom: 3px; }
        .meta .line { font-size: 11px; color: #4b5563; margin-bottom: 2px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 26px; }
        table.items th { background: #4f46e5; color: #fff; font-size: 10px; text-transform: uppercase; padding: 11px 14px; text-align: left; }
        table.items th.r, table.items td.r { text-align: right; }
        table.items td { padding: 13px 14px; border-bottom: 1px solid #e5e7eb; }
        .totals { width: 260px; float: right; border-collapse: collapse; }
        .totals td { padding: 8px 14px; font-size: 12px; }
        .totals .lbl { color: #6b7280; }
        .totals .val { text-align: right; font-weight: bold; }
        .totals .grand { background: #4f46e5; color: #fff; border-radius: 6px; }
        .totals .grand .lbl { color: #e0e7ff; }
        .foot { clear: both; margin-top: 60px; padding-top: 16px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 10px; color: #9ca3af; }
        .print-btn { position: fixed; top: 16px; right: 16px; background: #4f46e5; color: #fff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: bold; cursor: pointer; font-family: inherit; }
        @media print { .print-btn { display: none; } body { padding: 0; } }
    </style>
</head>
<body onload="window.print()">
    <button class="print-btn" onclick="window.print()">🖨 Print</button>

    <div class="header">
        <div>
            <div class="brand-name">Prime Byte Software Solution</div>
            <div class="brand-sub">Accounting &amp; Project Management</div>
        </div>
        <div>
            <div class="inv-word">INVOICE</div>
            <div class="inv-no"># {{ $invoice->invoice_number }}</div>
            <div style="text-align:right;"><span class="status-tag status-{{ $invoice->status }}">{{ strtoupper($invoice->status) }}</span></div>
        </div>
    </div>
    <hr class="divider">

    <div class="meta">
        <div>
            <div class="lbl">Billed To</div>
            <div class="name">{{ $invoice->client->name ?? 'N/A' }}</div>
            @if($invoice->client->company ?? false)<div class="line">{{ $invoice->client->company }}</div>@endif
            @if($invoice->client->email ?? false)<div class="line">{{ $invoice->client->email }}</div>@endif
        </div>
        <div>
            <div class="lbl">Invoice Details</div>
            <div class="line"><strong>Date:</strong> {{ $invoice->invoice_date }}</div>
            <div class="line"><strong>Project:</strong> {{ $invoice->project->name ?? 'N/A' }}</div>
            <div class="line"><strong>Bank:</strong> {{ $invoice->bank->name ?? 'N/A' }}</div>
        </div>
    </div>

    <table class="items">
        <thead>
            <tr><th>Description</th><th class="r">Amount</th><th class="r">Paid</th><th class="r">Balance</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $invoice->project->name ?? 'Project Invoice' }}</strong>
                    @if($invoice->description)<div style="color:#6b7280; font-size:11px; margin-top:4px;">{{ $invoice->description }}</div>@endif
                </td>
                <td class="r">Tk{{ number_format($invoice->amount, 2) }}</td>
                <td class="r">Tk{{ number_format($invoice->paid_amount, 2) }}</td>
                <td class="r">Tk{{ number_format($invoice->balance_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr><td class="lbl">Invoice Amount</td><td class="val">Tk{{ number_format($invoice->amount, 2) }}</td></tr>
        <tr><td class="lbl">Paid</td><td class="val">Tk{{ number_format($invoice->paid_amount, 2) }}</td></tr>
        <tr class="grand"><td class="lbl">Balance Due</td><td class="val">Tk{{ number_format($invoice->balance_amount, 2) }}</td></tr>
    </table>

    <div class="foot">Thank you for your business · Prime Byte Software Solution · Computer-generated invoice.</div>
</body>
</html>
