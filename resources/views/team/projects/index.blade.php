@extends('layouts.admin')

@section('title', 'Team Projects')

@section('content')
<div class="page-header">
    <div>
        <h1>Projects &amp; Payments</h1>
        <div class="sub">Assign team members and track how much each project owes vs paid</div>
    </div>
    <div class="header-actions">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search projects…" data-search="#teamProjectsTable">
        </div>
        <a href="{{ route('team.members.index') }}" class="btn btn-ghost">Team Members</a>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table" id="teamProjectsTable">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Client</th>
                    <th style="text-align:right;">Value</th>
                    <th style="text-align:right;">Members</th>
                    <th style="text-align:right;">Collected</th>
                    <th style="text-align:right;">Paid to team</th>
                    <th style="text-align:right;">Available</th>
                    <th style="text-align:right;">Manage</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    @php
                        $value = (float) $project->project_value;
                        $paid = (float) $project->team_paid_total;
                        $collected = (float) $project->client_paid_total;
                        $available = round($collected - $paid, 2);
                    @endphp
                    <tr>
                        <td class="strong">{{ $project->name }}</td>
                        <td>{{ $project->client->name ?? '—' }}</td>
                        <td style="text-align:right;">৳{{ number_format($value, 2) }}</td>
                        <td style="text-align:right;">{{ $project->team_members_count }}</td>
                        <td style="text-align:right;">৳{{ number_format($collected, 2) }}</td>
                        <td style="text-align:right;">৳{{ number_format($paid, 2) }}</td>
                        <td style="text-align:right;">
                            @if($available <= 0.009)
                                <span class="badge badge-neutral">৳0.00</span>
                            @else
                                <span class="badge badge-warning">৳{{ number_format($available, 2) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                <a href="{{ route('team.projects.show', $project->id) }}" class="btn btn-ghost btn-sm">Manage</a>
                                <a href="{{ route('team.projects.summary', $project->id) }}" target="_blank" class="act" title="Payment summary (print)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                </a>
                                <a href="{{ route('team.projects.summary.excel', $project->id) }}" class="act" title="Download Excel">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-empty><td colspan="8">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                            <div>No projects yet. Create a project in the Projects section first.</div>
                        </div>
                    </td></tr>
                @endforelse
                <tr class="no-results" style="display:none;"><td colspan="8">No projects match your search.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
