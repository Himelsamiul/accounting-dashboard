<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', Arial, sans-serif; color: #1f2937; font-size: 12px; }
        .sheet { padding: 34px 40px; }
        .head { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .head td { vertical-align: top; }
        .brand-name { font-size: 20px; font-weight: bold; color: #4f46e5; }
        .brand-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
        .brand-contact { font-size: 9px; color: #9ca3af; margin-top: 4px; }
        .doc-title { text-align: right; }
        .doc-title .t { font-size: 18px; font-weight: bold; color: #111827; }
        .doc-title .d { font-size: 10px; color: #9ca3af; margin-top: 4px; }
        .divider { border: none; border-top: 2px solid #eef0fe; margin: 0 0 16px; }

        .filters { border: 1px solid #e5e7eb; border-radius: 6px; padding: 9px 14px; margin-bottom: 14px; font-size: 10px; color: #4b5563; }
        .filters .lbl { color: #9ca3af; text-transform: uppercase; letter-spacing: 0.4px; font-size: 8.5px; }
        .filters .chip { display: inline-block; margin-right: 14px; }
        .filters .chip b { color: #111827; }

        .summary { display: table; width: 100%; margin-bottom: 18px; border-spacing: 8px 0; }
        .summary .cell { display: table-cell; width: 33%; background: #eef0fe; border-radius: 6px; padding: 12px 14px; }
        .summary .val { font-size: 16px; font-weight: bold; color: #3730a3; }
        .summary .cap { font-size: 9.5px; color: #6366f1; text-transform: uppercase; letter-spacing: 0.4px; margin-top: 3px; }

        .section-title { font-size: 12px; font-weight: bold; color: #111827; margin: 4px 0 8px; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data th { background: #4f46e5; color: #fff; font-size: 10px; text-transform: uppercase; letter-spacing: 0.4px; padding: 8px 10px; text-align: left; }
        table.data th.right { text-align: right; }
        table.data td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; color: #374151; }
        table.data td.right { text-align: right; }
        table.data tr:nth-child(even) td { background: #f9fafb; }
        table.data tfoot td { background: #eef0fe; font-weight: bold; color: #3730a3; border-bottom: none; }
        .name { font-weight: bold; color: #111827; }

        .foot { margin-top: 26px; padding-top: 12px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 10px; color: #9ca3af; }
        .empty { padding: 24px; text-align: center; color: #9ca3af; }
    </style>
</head>
<body>
<div class="sheet">
    <table class="head">
        <tr>
            <td>
                <div class="brand-name">{{ $company['company_name'] }}</div>
                <div class="brand-sub">{{ $company['company_tagline'] }}</div>
                @if($company['company_email'] || $company['company_phone'])
                    <div class="brand-contact">
                        {{ $company['company_email'] }}{{ $company['company_email'] && $company['company_phone'] ? ' · ' : '' }}{{ $company['company_phone'] }}
                    </div>
                @endif
            </td>
            <td class="doc-title">
                <div class="t">EXPENSE REPORT</div>
                <div class="d">Generated: {{ $generatedAt }}</div>
            </td>
        </tr>
    </table>
    <hr class="divider">

    {{-- Active filters --}}
    <div class="filters">
        <span class="lbl">Filters</span> &nbsp;
        <span class="chip">Head: <b>{{ $filters['head'] ?? 'All' }}</b></span>
        <span class="chip">Method: <b>{{ $filters['method'] ?? 'All' }}</b></span>
        <span class="chip">From: <b>{{ $filters['from'] ? \Illuminate\Support\Carbon::parse($filters['from'])->format('d M Y') : 'Any' }}</b></span>
        <span class="chip">To: <b>{{ $filters['to'] ? \Illuminate\Support\Carbon::parse($filters['to'])->format('d M Y') : 'Any' }}</b></span>
    </div>

    {{-- Summary --}}
    <div class="summary">
        <div class="cell">
            <div class="val">Tk{{ number_format($total, 2) }}</div>
            <div class="cap">Total Expense</div>
        </div>
        <div class="cell">
            <div class="val">{{ $expenses->count() }}</div>
            <div class="cap">Entries</div>
        </div>
        <div class="cell">
            <div class="val">Tk{{ number_format($expenses->count() ? $total / $expenses->count() : 0, 2) }}</div>
            <div class="cap">Average / Entry</div>
        </div>
    </div>

    @if($expenses->isEmpty())
        <div class="empty">No expenses match the selected filters.</div>
    @else
        {{-- Head-wise breakdown --}}
        <div class="section-title">Head-wise Breakdown</div>
        <table class="data">
            <thead>
                <tr>
                    <th>Expense Head</th>
                    <th class="right">Entries</th>
                    <th class="right">Total (Tk)</th>
                    <th class="right">% of Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($byHead as $name => $row)
                    <tr>
                        <td class="name">{{ $name }}</td>
                        <td class="right">{{ $row['count'] }}</td>
                        <td class="right">{{ number_format($row['total'], 2) }}</td>
                        <td class="right">{{ $total > 0 ? number_format($row['total'] / $total * 100, 1) : '0.0' }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Detailed entries --}}
        <div class="section-title">Detailed Entries</div>
        <table class="data">
            <thead>
                <tr>
                    <th style="width:24px;">#</th>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Head</th>
                    <th>Method</th>
                    <th class="right">Amount (Tk)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $i => $expense)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $expense->expense_date ? $expense->expense_date->format('d M Y') : '—' }}</td>
                        <td class="name">{{ $expense->title }}</td>
                        <td>{{ $expense->head?->name ?? '—' }}</td>
                        <td>{{ $expense->payment_method ?: '—' }}{{ $expense->bank ? ' · ' . $expense->bank->name : '' }}</td>
                        <td class="right">{{ number_format((float) $expense->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="right">Total</td>
                    <td class="right">Tk{{ number_format($total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="foot">Generated by {{ $company['company_name'] }} · Computer-generated report.</div>
</div>
</body>
</html>
