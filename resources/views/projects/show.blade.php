@extends('layouts.admin')

@section('title', 'Project Details')

@section('content')
@php
    $value = (float) $project->project_value;
    $collected = (float) $project->invoices->sum('paid_amount');
    $remaining = max(0, $value - $collected);
    $pct = $value > 0 ? min(100, round($collected / $value * 100)) : 0;
    $done = $value > 0 && $collected >= $value - 0.009;
    $statusLabel = $done ? 'Fully Paid' : ($collected > 0 ? 'Partial' : 'Open');
    $statusBadge = $done ? 'badge-success' : ($collected > 0 ? 'badge-warning' : 'badge-neutral');
@endphp
<div class="page-header">
    <div>
        <h1>{{ $project->name }}</h1>
        <div class="sub">{{ $project->client->name ?? 'Unassigned client' }}</div>
    </div>
    <div class="header-actions">
        <span class="badge {{ $project->statusBadgeClass() }}" style="align-self:center;">{{ $project->status ?: 'Pending' }}</span>
        <span class="badge {{ $statusBadge }}" style="align-self:center;">{{ $statusLabel }}</span>
        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-ghost">Edit</a>
        <a href="{{ route('projects.index') }}" class="btn btn-ghost">Back to list</a>
    </div>
</div>

<div class="card" style="max-width:900px;">
    <div class="card-header"><h2>Project overview</h2></div>
    <div class="card-body">
        <div class="detail-list">
            <div class="detail-row"><div class="dt">Project Name</div><div class="dd">{{ $project->name }}</div></div>
            <div class="detail-row"><div class="dt">Tracking Code</div><div class="dd"><span class="badge badge-primary" style="letter-spacing:1px;">{{ $project->code ?? '—' }}</span></div></div>
            <div class="detail-row"><div class="dt">Client</div><div class="dd">{{ $project->client->name ?? '—' }}</div></div>
            <div class="detail-row"><div class="dt">Type</div><div class="dd">{{ $project->type ?: '—' }}</div></div>
            <div class="detail-row"><div class="dt">Project Value</div><div class="dd">৳{{ number_format($value, 2) }}</div></div>
            <div class="detail-row"><div class="dt">Start Date</div><div class="dd">{{ $project->start_date ? $project->start_date->format('d M Y') : '—' }}</div></div>
            <div class="detail-row"><div class="dt">Estimated End Date</div><div class="dd">{{ $project->end_date ? $project->end_date->format('d M Y') : '—' }}</div></div>
            <div class="detail-row"><div class="dt">Project Status</div><div class="dd"><span class="badge {{ $project->statusBadgeClass() }}">{{ $project->status ?: 'Pending' }}</span></div></div>
            <div class="detail-row"><div class="dt">Payment Status</div><div class="dd"><span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span></div></div>
            <div class="detail-row"><div class="dt">Collected</div><div class="dd">৳{{ number_format($collected, 2) }}</div></div>
            <div class="detail-row"><div class="dt">Outstanding</div><div class="dd">৳{{ number_format($remaining, 2) }}</div></div>
            <div class="detail-row"><div class="dt">Invoices</div><div class="dd">{{ $project->invoices->count() }}</div></div>
            <div class="detail-row"><div class="dt">Created</div><div class="dd">{{ $project->created_at ? $project->created_at->format('d M Y') : '—' }}</div></div>
            <div class="detail-row full">
                <div class="dt">Collection progress ({{ $pct }}%)</div>
                <div class="dd">
                    <div class="bar-progress {{ $done ? 'done' : '' }}" style="width:100%; max-width:360px; margin-top:6px;"><span style="width:{{ $pct }}%"></span></div>
                </div>
            </div>
            <div class="detail-row full"><div class="dt">Description</div><div class="dd">{{ $project->description ?: '—' }}</div></div>
        </div>
    </div>
</div>
@endsection
