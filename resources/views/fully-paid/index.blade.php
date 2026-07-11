@extends('layouts.admin')

@section('title', 'Fully Paid')

@section('content')
<div class="page-header">
    <div>
        <h1>Fully Paid Projects</h1>
        <div class="sub">{{ $projects->count() }} project{{ $projects->count() === 1 ? '' : 's' }} collected in full</div>
    </div>
    <div class="header-actions">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search…" data-search="#fullyPaidTable">
        </div>
        <a href="{{ route('fully-paid.pdf') }}" class="btn btn-ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            PDF
        </a>
        <a href="{{ route('fully-paid.excel') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Excel
        </a>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table" id="fullyPaidTable">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th style="text-align:right;">Value</th>
                    <th style="text-align:right;">Collected</th>
                    <th style="text-align:center;">Invoices</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $palette = ['#4f46e5','#0ea5e9','#8b5cf6','#ec4899','#f59e0b','#16a34a','#14b8a6','#f43f5e']; @endphp
                @forelse($projects as $project)
                    @php $c = $palette[$project->id % count($palette)]; @endphp
                    <tr>
                        <td class="strong">
                            <div class="cell-name">
                                <span class="cell-avatar" style="background:{{ $c }}">{{ strtoupper(mb_substr($project->name, 0, 1)) }}</span>
                                {{ $project->name }}
                            </div>
                        </td>
                        <td>{{ $project->client->name ?? '—' }}</td>
                        <td>{{ $project->type ?: '—' }}</td>
                        <td style="text-align:right;" class="val-accent">৳{{ number_format($project->project_value, 2) }}</td>
                        <td style="text-align:right;">৳{{ number_format($project->collected, 2) }}</td>
                        <td style="text-align:center;">{{ $project->invoices_count }}</td>
                        <td><span class="badge badge-success">Fully Paid</span></td>
                    </tr>
                @empty
                    <tr data-empty><td colspan="7">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <div>No fully paid projects yet. Once a project's full value is collected, it appears here.</div>
                        </div>
                    </td></tr>
                @endforelse
                <tr class="no-results" style="display:none;"><td colspan="7">No projects match your search.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
