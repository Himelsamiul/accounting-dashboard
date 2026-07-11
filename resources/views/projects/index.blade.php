@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
<div class="page-header">
    <div>
        <h1>Projects</h1>
        <div class="sub">{{ $projects->count() }} project{{ $projects->count() === 1 ? '' : 's' }} tracked</div>
    </div>
    <div class="header-actions">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search projects…" data-search="#projectsTable">
        </div>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Project
        </a>
    </div>
</div>

<div class="card">
    <div class="card-toolbar">
        <div class="filter-chips">
            <button type="button" class="chip active" data-filter="#projectsTable" data-value="all">All</button>
            <button type="button" class="chip" data-filter="#projectsTable" data-value="Fully Paid">Fully Paid</button>
            <button type="button" class="chip" data-filter="#projectsTable" data-value="Partial">Partial</button>
            <button type="button" class="chip" data-filter="#projectsTable" data-value="Open">Open</button>
        </div>
        <span class="progress-meta">{{ $projects->count() }} project{{ $projects->count() === 1 ? '' : 's' }}</span>
    </div>
    <div class="table-wrap">
        <table class="table" id="projectsTable">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Client</th>
                    <th style="text-align:right;">Value</th>
                    <th>Collection</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $palette = ['#4f46e5','#0ea5e9','#8b5cf6','#ec4899','#f59e0b','#16a34a','#14b8a6','#f43f5e']; @endphp
                @forelse($projects as $project)
                    @php
                        $c = $palette[$project->id % count($palette)];
                        $value = (float) $project->project_value;
                        $collected = (float) ($project->paid_total ?? 0);
                        $pct = $value > 0 ? min(100, round($collected / $value * 100)) : 0;
                        $done = $value > 0 && $collected >= $value - 0.009;
                        $statusLabel = $done ? 'Fully Paid' : ($collected > 0 ? 'Partial' : 'Open');
                    @endphp
                    <tr data-status="{{ $statusLabel }}">
                        <td class="strong">
                            <div class="cell-name">
                                <span class="cell-avatar" style="background:{{ $c }}">{{ strtoupper(mb_substr($project->name, 0, 1)) }}</span>
                                {{ $project->name }}
                            </div>
                        </td>
                        <td>{{ $project->client->name ?? '—' }}</td>
                        <td style="text-align:right;" class="val-accent">৳{{ number_format($value, 2) }}</td>
                        <td>
                            <div class="progress-cell">
                                <div class="bar-progress {{ $done ? 'done' : '' }}"><span style="width:{{ $pct }}%"></span></div>
                                <div class="progress-meta">৳{{ number_format($collected, 0) }} of ৳{{ number_format($value, 0) }} ({{ $pct }}%)</div>
                            </div>
                        </td>
                        <td>
                            @if($done)
                                <span class="badge badge-success">Fully Paid</span>
                            @elseif($collected > 0)
                                <span class="badge badge-warning">Partial</span>
                            @else
                                <span class="badge badge-neutral">Open</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                <a href="{{ route('projects.show', $project->id) }}" class="act" title="View">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="{{ route('projects.edit', $project->id) }}" class="act" title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('projects.delete', $project->id) }}" onsubmit="return confirm('Delete this project?')">@csrf
                                    <button type="submit" class="act danger" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-empty><td colspan="6">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                            <div>No projects yet. Create your first project.</div>
                        </div>
                    </td></tr>
                @endforelse
                <tr class="no-results" style="display:none;"><td colspan="6">No projects match your search.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
