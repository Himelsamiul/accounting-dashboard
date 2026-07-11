<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Summary — {{ $project->code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', Arial, sans-serif; color: #1f2937; font-size: 13px; padding: 34px 40px; }
        .head { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #eef0fe; padding-bottom: 18px; margin-bottom: 22px; }
        .brand { font-size: 19px; font-weight: bold; color: #4f46e5; }
        .brand-sub { font-size: 11px; color: #6b7280; }
        .doc { text-align: right; }
        .doc .t { font-size: 16px; font-weight: bold; }
        .doc .c { font-size: 12px; color: #6b7280; margin-top: 4px; letter-spacing: 1px; }
        .meta { display: flex; gap: 40px; margin-bottom: 22px; }
        .meta .lbl { font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #9ca3af; }
        .meta .val { font-size: 14px; font-weight: bold; margin-top: 3px; }
        .summary-row { display: flex; gap: 14px; margin-bottom: 24px; }
        .box { flex: 1; background: #f5f7fc; border-radius: 8px; padding: 14px; }
        .box .l { font-size: 11px; color: #6b7280; }
        .box .v { font-size: 17px; font-weight: bold; margin-top: 3px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #4f46e5; color: #fff; font-size: 10px; text-transform: uppercase; padding: 9px 11px; text-align: left; }
        th.r, td.r { text-align: right; }
        td { padding: 10px 11px; border-bottom: 1px solid #e5e7eb; font-size: 12px; }
        tr:nth-child(even) td { background: #f9fafb; }
        .status { font-weight: bold; }
        .foot { margin-top: 30px; padding-top: 14px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 10px; color: #9ca3af; }
        .print-btn { position: fixed; top: 16px; right: 16px; background: #4f46e5; color: #fff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: bold; cursor: pointer; font-family: inherit; }
        @media print { .print-btn { display: none; } body { padding: 0; } }
    </style>
</head>
<body onload="window.print()">
    <button class="print-btn" onclick="window.print()">🖨 Print</button>

    <div class="head">
        <div>
            <div class="brand">Prime Byte Software Solution</div>
            <div class="brand-sub">Client Payment Summary</div>
        </div>
        <div class="doc">
            <div class="t">PAYMENT SUMMARY</div>
            <div class="c">{{ $project->code }}</div>
        </div>
    </div>

    <div class="meta">
        <div>
            <div class="lbl">Project</div>
            <div class="val">{{ $project->name }}</div>
        </div>
        <div>
            <div class="lbl">Client</div>
            <div class="val">{{ $project->client->name ?? '—' }}</div>
        </div>
        <div>
            <div class="lbl">Status</div>
            <div class="val">{{ $project->statusLabel }}</div>
        </div>
        <div>
            <div class="lbl">Start Date</div>
            <div class="val">{{ $project->start_date ? $project->start_date->format('d M Y') : '—' }}</div>
        </div>
        <div>
            <div class="lbl">Est. End Date</div>
            <div class="val">{{ $project->end_date ? $project->end_date->format('d M Y') : '—' }}</div>
        </div>
    </div>

    <div class="summary-row">
        <div class="box"><div class="l">Project Value</div><div class="v">Tk{{ number_format($project->project_value, 2) }}</div></div>
        <div class="box"><div class="l">Total Collected</div><div class="v" style="color:#15803d;">Tk{{ number_format($project->collected, 2) }}</div></div>
        <div class="box"><div class="l">Outstanding</div><div class="v" style="color:#b91c1c;">Tk{{ number_format($project->remaining, 2) }}</div></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Invoice #</th>
                <th class="r">Amount</th>
                <th class="r">Paid</th>
                <th class="r">Balance</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($project->invoices as $inv)
                <tr>
                    <td>{{ $inv->invoice_date ?: '—' }}</td>
                    <td>{{ $inv->invoice_number }}</td>
                    <td class="r">Tk{{ number_format($inv->amount, 2) }}</td>
                    <td class="r">Tk{{ number_format($inv->paid_amount, 2) }}</td>
                    <td class="r">Tk{{ number_format($inv->balance_amount, 2) }}</td>
                    <td class="status">{{ $inv->status }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center; color:#9ca3af; padding:20px;">No payments recorded yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="foot">Generated {{ now()->format('d M Y, h:i A') }} · Prime Byte Software Solution · Computer-generated document.</div>
</body>
</html>
