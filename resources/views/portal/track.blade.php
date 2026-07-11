@extends('layouts.public')

@section('title', 'Track Project')

@section('head')
<style>
    .track-hero { background: linear-gradient(180deg, var(--bg-alt), var(--bg)); padding: 44px 0 10px; }
    .track-box { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 22px; box-shadow: var(--shadow); max-width: 620px; }
    .track-box form { display: flex; gap: 8px; }
    .track-box input { flex: 1; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--border); font-family: inherit; font-size: 0.98rem; outline: none; text-transform: uppercase; background: var(--surface); color: var(--text); }
    .track-box input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }
    .notfound { background: var(--danger-soft); color: var(--danger); padding: 14px 16px; border-radius: 10px; font-size: 0.92rem; max-width: 620px; margin-top: 16px; }

    .result { display: grid; gap: 20px; }
    .rcard { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow); }
    .rcard-head { padding: 22px; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; flex-wrap: wrap; }
    .rcard-head h2 { font-size: 1.3rem; }
    .rcard-head .code { font-size: 0.82rem; color: var(--muted); margin-top: 4px; letter-spacing: 1px; }
    .rcard-body { padding: 22px; }
    .sbadge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 999px; font-size: 0.82rem; font-weight: 700; }
    .sbadge::before { content:''; width:7px; height:7px; border-radius:50%; background: currentColor; }
    .s-paid { background: var(--success-soft); color: var(--success); }
    .s-progress { background: var(--warning-soft); color: var(--warning); }
    .s-pending { background: var(--danger-soft); color: var(--danger); }
    .money-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin: 4px 0 20px; }
    .money { background: var(--bg-alt); border-radius: 12px; padding: 16px; }
    .money .l { font-size: 0.78rem; color: var(--muted); }
    .money .v { font-size: 1.3rem; font-weight: 800; margin-top: 3px; }
    .prog { height: 12px; border-radius: 999px; background: var(--bg-alt); overflow: hidden; }
    .prog > span { display: block; height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--primary), #8b5cf6); }
    .prog.done > span { background: linear-gradient(90deg, #16a34a, #14b8a6); }
    .detail-list { display: grid; grid-template-columns: repeat(2,1fr); gap: 1px; background: var(--border); border-radius: 10px; overflow: hidden; }
    .detail-list .d { background: var(--surface); padding: 13px 15px; }
    .detail-list .d.full { grid-column: 1 / -1; }
    .detail-list .dt { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); font-weight: 600; margin-bottom: 4px; }
    .detail-list .dd { font-size: 0.95rem; font-weight: 500; }
    .pay-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .pay-table th { text-align: left; padding: 11px 14px; background: var(--bg-alt); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); }
    .pay-table th.r, .pay-table td.r { text-align: right; }
    .pay-table td { padding: 12px 14px; border-bottom: 1px solid var(--border); }
    .pay-table tbody tr:last-child td { border-bottom: none; }
    .pill { display:inline-block; padding:3px 10px; border-radius:999px; font-size:0.74rem; font-weight:700; }
    .card-actions { display: flex; gap: 10px; flex-wrap: wrap; }
    @media (max-width: 620px) { .money-grid { grid-template-columns: 1fr; } .detail-list { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<section class="track-hero">
    <div class="wrap">
        <div class="section-head" style="text-align:left; margin:0 0 20px; max-width:none;">
            <span class="eyebrow">Project Tracking</span>
            <h2>Track your project</h2>
            <p>Enter the tracking code we emailed you to view your project status and payments.</p>
        </div>
        <div class="track-box">
            <form method="GET" action="{{ route('portal.track') }}">
                <input type="text" name="code" value="{{ $code }}" placeholder="PRJ-XXXXXXXX" required>
                <button class="btn btn-primary" type="submit">Track</button>
            </form>
        </div>
        <div style="margin-top:12px; font-size:0.9rem; color:var(--muted);">
            Forgot your code? <a href="{{ route('portal.request-code') }}" style="color:var(--primary); font-weight:600;">Request it here →</a>
        </div>
        @if($searched && !$project)
            <div class="notfound">No project found for code <strong>{{ $code }}</strong>. Please check the code and try again.</div>
        @endif
    </div>
</section>

@if($project)
<section class="section" style="padding-top:34px;">
    <div class="wrap">
        <div class="result">
            {{-- Status --}}
            <div class="rcard">
                <div class="rcard-head">
                    <div>
                        <h2>{{ $project->name }}</h2>
                        <div class="code">CODE: {{ $project->code }}</div>
                    </div>
                    @php $sc = $project->statusLabel === 'Fully Paid' ? 's-paid' : ($project->statusLabel === 'In Progress' ? 's-progress' : 's-pending'); @endphp
                    <span class="sbadge {{ $sc }}">{{ $project->statusLabel }}</span>
                </div>
                <div class="rcard-body">
                    <div class="money-grid">
                        <div class="money"><div class="l">Project Value</div><div class="v">৳{{ number_format($project->project_value, 0) }}</div></div>
                        <div class="money"><div class="l">Collected</div><div class="v" style="color:var(--success);">৳{{ number_format($project->collected, 0) }}</div></div>
                        <div class="money"><div class="l">Outstanding</div><div class="v" style="color:var(--danger);">৳{{ number_format($project->remaining, 0) }}</div></div>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:var(--muted); margin-bottom:6px;">
                        <span>Payment progress</span><span>{{ $project->pct }}%</span>
                    </div>
                    <div class="prog {{ $project->pct >= 100 ? 'done' : '' }}"><span style="width:{{ $project->pct }}%"></span></div>
                </div>
            </div>

            {{-- Details --}}
            <div class="rcard">
                <div class="rcard-head"><h2 style="font-size:1.1rem;">Project Details</h2></div>
                <div class="rcard-body">
                    <div class="detail-list">
                        <div class="d"><div class="dt">Client</div><div class="dd">{{ $project->client->name ?? '—' }}</div></div>
                        <div class="d"><div class="dt">Type</div><div class="dd">{{ $project->type ?: '—' }}</div></div>
                        <div class="d"><div class="dt">Start Date</div><div class="dd">{{ $project->start_date ? $project->start_date->format('d M Y') : '—' }}</div></div>
                        <div class="d"><div class="dt">Estimated End Date</div><div class="dd">{{ $project->end_date ? $project->end_date->format('d M Y') : '—' }}</div></div>
                        <div class="d"><div class="dt">Invoices</div><div class="dd">{{ $project->invoices->count() }}</div></div>
                        <div class="d"><div class="dt">Registered</div><div class="dd">{{ $project->created_at ? $project->created_at->format('d M Y') : '—' }}</div></div>
                        <div class="d full"><div class="dt">Description</div><div class="dd">{{ $project->description ?: '—' }}</div></div>
                    </div>
                </div>
            </div>

            {{-- Payment summary --}}
            <div class="rcard">
                <div class="rcard-head">
                    <h2 style="font-size:1.1rem;">Payment Summary</h2>
                    <div class="card-actions">
                        <a href="{{ route('portal.print.project', $project->code) }}" target="_blank" class="btn btn-ghost">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                            Print Summary
                        </a>
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="pay-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice #</th>
                                <th class="r">Amount</th>
                                <th class="r">Paid</th>
                                <th class="r">Balance</th>
                                <th>Status</th>
                                <th class="r">Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($project->invoices as $inv)
                                @php $pc = $inv->status === 'Paid' ? 's-paid' : ($inv->status === 'Partial' ? 's-progress' : 's-pending'); @endphp
                                <tr>
                                    <td>{{ $inv->invoice_date ?: '—' }}</td>
                                    <td>{{ $inv->invoice_number }}</td>
                                    <td class="r">৳{{ number_format($inv->amount, 2) }}</td>
                                    <td class="r" style="color:var(--success); font-weight:600;">৳{{ number_format($inv->paid_amount, 2) }}</td>
                                    <td class="r">৳{{ number_format($inv->balance_amount, 2) }}</td>
                                    <td><span class="pill {{ $pc }}">{{ $inv->status }}</span></td>
                                    <td class="r">
                                        <a href="{{ route('portal.print.invoice', [$project->code, $inv->id]) }}" target="_blank" class="btn btn-ghost" style="padding:6px 12px;">Print</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" style="text-align:center; color:var(--muted); padding:26px;">No payments recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
