@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@php
    $maxMonth = collect($months)->max('total') ?: 1;
    $cards = [
        [
            'label' => 'Earning Projects',
            'value' => '৳' . number_format($stats['earning_projects'], 0),
            'sub' => $stats['total_projects'] . ' projects tracked',
            'grad' => 'linear-gradient(135deg,#4f46e5,#06b6d4)',
            'icon' => '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>',
        ],
        [
            'label' => 'Total Collection',
            'value' => '৳' . number_format($stats['total_collection'], 0),
            'sub' => '৳' . number_format($stats['total_outstanding'], 0) . ' outstanding',
            'grad' => 'linear-gradient(135deg,#8b5cf6,#ec4899)',
            'icon' => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
        ],
        [
            'label' => 'Total Bank Account',
            'value' => number_format($stats['total_banks']),
            'sub' => 'Active payment channels',
            'grad' => 'linear-gradient(135deg,#f59e0b,#f43f5e)',
            'icon' => '<line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/>',
        ],
        [
            'label' => 'Total Client',
            'value' => number_format($stats['total_clients']),
            'sub' => $stats['total_invoices'] . ' invoices issued',
            'grad' => 'linear-gradient(135deg,#16a34a,#14b8a6)',
            'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        ],
    ];

    // Donut: invoice status breakdown.
    $totalInv = array_sum($statusBreakdown);
    $paidPct = $totalInv ? ($statusBreakdown['Paid'] / $totalInv) * 100 : 0;
    $partialPct = $totalInv ? ($statusBreakdown['Partial'] / $totalInv) * 100 : 0;
    $s1 = round($paidPct, 2);
    $s2 = round($paidPct + $partialPct, 2);
    if ($totalInv) {
        $donut = "conic-gradient(#16a34a 0 {$s1}%, #f59e0b {$s1}% {$s2}%, #ef4444 {$s2}% 100%)";
    } else {
        $donut = 'conic-gradient(var(--surface-3) 0 100%)';
    }
    $legend = [
        ['Paid', $statusBreakdown['Paid'], '#16a34a'],
        ['Partial', $statusBreakdown['Partial'], '#f59e0b'],
        ['Pending', $statusBreakdown['Pending'], '#ef4444'],
    ];

    // Project status chart data.
    $maxProj = max(1, max($projectStatus));
    $projRows = [
        ['Fully Paid', $projectStatus['Fully Paid'], 'linear-gradient(90deg,#16a34a,#14b8a6)'],
        ['Partial', $projectStatus['Partial'], 'linear-gradient(90deg,#f59e0b,#f43f5e)'],
        ['Open', $projectStatus['Open'], 'linear-gradient(90deg,#4f46e5,#8b5cf6)'],
    ];
@endphp

<div class="page-header">
    <div>
        <h1>Welcome back 👋</h1>
        <div class="sub">A live snapshot of clients, collections, bank accounts and project earnings.</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Invoice
        </a>
        <a href="{{ route('projects.create') }}" class="btn btn-ghost">New Project</a>
    </div>
</div>

<div class="stat-grid">
    @foreach ($cards as $card)
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon" style="background:{{ $card['grad'] }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $card['icon'] !!}</svg>
                </div>
            </div>
            <div>
                <div class="stat-value">{{ $card['value'] }}</div>
                <div class="stat-label">{{ $card['label'] }}</div>
            </div>
            <div class="stat-sub">{{ $card['sub'] }}</div>
        </div>
    @endforeach
</div>

<div class="dash-grid">
    <section class="card">
        <div class="card-header">
            <div>
                <h2>Performance pulse</h2>
                <div class="sub">Collection over the last 6 months</div>
            </div>
            <span class="badge badge-primary">৳{{ number_format($stats['total_collection'], 0) }} total</span>
        </div>
        <div class="card-body">
            <div class="chart">
                @foreach ($months as $month)
                    @php $h = max(4, round(($month['total'] / $maxMonth) * 100)); @endphp
                    <div class="chart-col">
                        <div class="chart-track">
                            <div class="chart-bar" style="height:{{ $h }}%">
                                <span class="chart-tip">৳{{ number_format($month['total'], 0) }}</span>
                            </div>
                        </div>
                        <span class="chart-x">{{ $month['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-header">
            <h2>Invoice status</h2>
            <span class="badge badge-neutral">{{ $totalInv }} total</span>
        </div>
        <div class="card-body">
            <div class="donut-wrap">
                <div class="donut" style="background:{{ $donut }};">
                    <div class="donut-center">
                        <div class="donut-num">{{ $totalInv }}</div>
                        <div class="donut-cap">Invoices</div>
                    </div>
                </div>
                <div class="donut-legend">
                    @foreach ($legend as $l)
                        <div class="legend-row">
                            <span class="legend-dot" style="background:{{ $l[2] }}"></span>
                            <span class="legend-label">{{ $l[0] }}</span>
                            <span class="legend-val">{{ $l[1] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>

<div class="dash-grid" style="margin-top:18px;">
    <section class="card">
        <div class="card-header">
            <div>
                <h2>Projects by status</h2>
                <div class="sub">Based on how much has been collected</div>
            </div>
            <span class="badge badge-neutral">{{ array_sum($projectStatus) }} projects</span>
        </div>
        <div class="card-body">
            <div class="hbar-chart">
                @foreach ($projRows as $row)
                    @php $w = round(($row[1] / $maxProj) * 100); @endphp
                    <div class="hbar-row">
                        <span class="hbar-label">{{ $row[0] }}</span>
                        <div class="hbar-track"><div class="hbar-fill" style="width:{{ $w }}%; background:{{ $row[2] }}"></div></div>
                        <span class="hbar-val">{{ $row[1] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-header">
            <h2>Quick actions</h2>
            <span class="badge badge-success">Live</span>
        </div>
        <div class="card-body">
            <div class="quick-links">
            @php
                $qs = [
                    ['New client', route('clients.create'), '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>'],
                    ['New project', route('projects.create'), '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>'],
                    ['New bank', route('banks.create'), '<polygon points="12 2 20 7 4 7"/><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/>'],
                    ['Generate invoice', route('invoices.create'), '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>'],
                ];
            @endphp
            @foreach ($qs as $q)
                <a href="{{ $q[1] }}" class="quick-item">
                    <span class="qi-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $q[2] !!}</svg></span>
                    <span class="qi-text">{{ $q[0] }}</span>
                    <span class="qi-go"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></span>
                </a>
            @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
