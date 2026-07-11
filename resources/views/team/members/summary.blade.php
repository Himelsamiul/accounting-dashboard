<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Summary — {{ $member->name }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; color: #0f172a; margin: 0; background: #f4f6fb; }
        .sheet { max-width: 920px; margin: 24px auto; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 8px 24px rgba(16,24,40,0.06); }
        .toolbar { max-width: 920px; margin: 20px auto 0; display: flex; gap: 10px; justify-content: flex-end; padding: 0 8px; flex-wrap: wrap; }
        .btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 16px; border-radius: 9px; border: 1px solid #d5dce7; background: #fff; color: #0f172a; font-size: .88rem; font-weight: 600; text-decoration: none; cursor: pointer; }
        .btn svg { width: 16px; height: 16px; }
        .btn-primary { background: #4f46e5; border-color: #4f46e5; color: #fff; }
        .btn-success { background: #16a34a; border-color: #16a34a; color: #fff; }

        .doc-head { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #0f172a; padding-bottom: 18px; margin-bottom: 22px; }
        .brand { font-size: 1.35rem; font-weight: 800; letter-spacing: -.01em; }
        .brand small { display: block; font-size: .72rem; font-weight: 500; color: #64748b; letter-spacing: .12em; text-transform: uppercase; margin-top: 2px; }
        .doc-title { text-align: right; }
        .doc-title h1 { font-size: 1.1rem; margin: 0 0 4px; }
        .doc-title .muted { color: #64748b; font-size: .8rem; }

        .meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px 28px; margin-bottom: 22px; font-size: .88rem; }
        .meta-grid .row { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px dashed #e5e9f0; }
        .meta-grid .row .k { color: #64748b; }
        .meta-grid .row .v { font-weight: 600; }

        .stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 26px; }
        .stat { border: 1px solid #e5e9f0; border-radius: 10px; padding: 12px 14px; }
        .stat .l { font-size: .72rem; text-transform: uppercase; letter-spacing: .04em; color: #64748b; }
        .stat .v { font-size: 1.1rem; font-weight: 700; margin-top: 3px; }
        .stat.green { border-left: 3px solid #16a34a; }
        .stat.amber { border-left: 3px solid #d97706; }
        .stat.indigo { border-left: 3px solid #4f46e5; }
        .stat.rose { border-left: 3px solid #f43f5e; }

        h2.section { font-size: .95rem; margin: 26px 0 10px; padding-bottom: 6px; border-bottom: 1px solid #e5e9f0; }
        table { width: 100%; border-collapse: collapse; font-size: .85rem; }
        th, td { padding: 9px 10px; text-align: left; border-bottom: 1px solid #eef2f8; }
        th { background: #f7f9fc; font-size: .74rem; text-transform: uppercase; letter-spacing: .03em; color: #64748b; }
        td.num, th.num { text-align: right; }
        tfoot td { font-weight: 700; border-top: 2px solid #e5e9f0; }
        .pill { display: inline-block; padding: 2px 9px; border-radius: 999px; font-size: .72rem; font-weight: 600; }
        .pill.ok { background: #e7f6ee; color: #16a34a; }
        .pill.due { background: #fdf1e2; color: #d97706; }
        .foot-note { margin-top: 28px; font-size: .74rem; color: #94a3b8; text-align: center; border-top: 1px solid #e5e9f0; padding-top: 14px; }

        @media print {
            body { background: #fff; }
            .toolbar { display: none; }
            .sheet { box-shadow: none; margin: 0; max-width: none; padding: 0; border-radius: 0; }
            @page { margin: 14mm; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="{{ route('team.members.index') }}" class="btn">← Back</a>
        <a href="{{ route('team.members.summary.excel', $member->id) }}" class="btn btn-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download Excel
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Print
        </button>
    </div>

    <div class="sheet">
        <div class="doc-head">
            <div class="brand">Prime Byte<small>Software Solution</small></div>
            <div class="doc-title">
                <h1>Team Member Payment Summary</h1>
                <div class="muted">Generated {{ $generatedAt }}</div>
            </div>
        </div>

        <div class="meta-grid">
            <div class="row"><span class="k">Member</span><span class="v">{{ $member->name }}</span></div>
            <div class="row"><span class="k">Role</span><span class="v">{{ $member->role ?: '—' }}</span></div>
            <div class="row"><span class="k">Phone</span><span class="v">{{ $member->phone ?: '—' }}</span></div>
            <div class="row"><span class="k">Status</span><span class="v">{{ $member->is_active ? 'Active' : 'Inactive' }}</span></div>
            <div class="row"><span class="k">Projects Assigned</span><span class="v">{{ $member->projects->count() }}</span></div>
            <div class="row"><span class="k">Payments Recorded</span><span class="v">{{ $payments->count() }}</span></div>
        </div>

        <div class="stat-row">
            <div class="stat indigo"><div class="l">Total Share</div><div class="v">৳{{ number_format($totalShare, 2) }}</div></div>
            <div class="stat green"><div class="l">Total Paid</div><div class="v">৳{{ number_format($totalPaid, 2) }}</div></div>
            <div class="stat amber"><div class="l">Remaining</div><div class="v">৳{{ number_format($totalRemaining, 2) }}</div></div>
            <div class="stat rose"><div class="l">Projects</div><div class="v">{{ $member->projects->count() }}</div></div>
        </div>

        <h2 class="section">Per-project breakdown</h2>
        <table>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Client</th>
                    <th>Code</th>
                    <th class="num">Share</th>
                    <th class="num">Paid</th>
                    <th class="num">Remaining</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    <tr>
                        <td>{{ $row['project']->name }}</td>
                        <td>{{ $row['project']->client->name ?? '—' }}</td>
                        <td>{{ $row['project']->code ?? '—' }}</td>
                        <td class="num">৳{{ number_format($row['share'], 2) }}</td>
                        <td class="num">৳{{ number_format($row['paid'], 2) }}</td>
                        <td class="num">৳{{ number_format($row['remaining'], 2) }}</td>
                        <td>@if($row['remaining'] <= 0.009)<span class="pill ok">Cleared</span>@else<span class="pill due">Due</span>@endif</td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center; color:#94a3b8;">Not assigned to any project yet.</td></tr>
                @endforelse
            </tbody>
            @if($rows->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="3" class="num">Total</td>
                    <td class="num">৳{{ number_format($totalShare, 2) }}</td>
                    <td class="num">৳{{ number_format($totalPaid, 2) }}</td>
                    <td class="num">৳{{ number_format($totalRemaining, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>

        <h2 class="section">Full payment log ({{ $payments->count() }})</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Project</th>
                    <th>Method</th>
                    <th>Bank / Where</th>
                    <th>Note</th>
                    <th class="num">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                    <tr>
                        <td>{{ $p->paid_on ? $p->paid_on->format('d M Y') : $p->created_at->format('d M Y') }}</td>
                        <td>{{ $p->project->name ?? '—' }}</td>
                        <td>{{ $p->method ?: '—' }}</td>
                        <td>{{ $p->bank->name ?? '—' }}</td>
                        <td>{{ $p->note ?: '—' }}</td>
                        <td class="num">৳{{ number_format((float) $p->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center; color:#94a3b8;">No payments recorded yet.</td></tr>
                @endforelse
            </tbody>
            @if($payments->isNotEmpty())
            <tfoot>
                <tr><td colspan="5" class="num">Total paid</td><td class="num">৳{{ number_format($totalPaid, 2) }}</td></tr>
            </tfoot>
            @endif
        </table>

        <div class="foot-note">Prime Byte Software Solution · Payment summary for {{ $member->name }} · Generated {{ $generatedAt }}</div>
    </div>

    <script>
        if (new URLSearchParams(location.search).get('print') === '1') { window.addEventListener('load', () => window.print()); }
    </script>
</body>
</html>
