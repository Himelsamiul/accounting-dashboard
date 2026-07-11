@extends('layouts.admin')

@section('title', 'History')

@section('content')
@php
    $actionBadge = [
        'created' => 'badge-success',
        'updated' => 'badge-warning',
        'deleted' => 'badge-danger',
        'login'   => 'badge-primary',
        'logout'  => 'badge-neutral',
    ];
    $hasFilters = $filters['user_id'] !== '' || $filters['action'] !== '' || $filters['from'] !== '' || $filters['to'] !== '' || $filters['q'] !== '';
@endphp

<div class="page-header">
    <div>
        <h1>History</h1>
        <div class="sub">Activity log — every login, logout, create, update and delete across the system</div>
    </div>
    @if(auth()->user()->hasPermission('history', 'delete'))
    <div class="header-actions">
        <form method="POST" action="{{ route('history.clear') }}" onsubmit="return confirm('Clear ALL history records? This cannot be undone.')">
            @csrf @method('DELETE')
            <button class="btn btn-danger" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Clear log
            </button>
        </form>
    </div>
    @endif
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:18px;">
    <div class="card-body">
        <form method="GET" action="{{ route('history.index') }}" class="filter-form">
            <div class="filter-field">
                <label>User</label>
                <select class="select" name="user_id">
                    <option value="">All users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (string) $filters['user_id'] === (string) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-field">
                <label>Action</label>
                <select class="select" name="action">
                    <option value="">All actions</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ $filters['action'] === $act ? 'selected' : '' }}>{{ ucfirst($act) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-field">
                <label>From</label>
                <input class="input" type="date" name="from" value="{{ $filters['from'] }}">
            </div>
            <div class="filter-field">
                <label>To</label>
                <input class="input" type="date" name="to" value="{{ $filters['to'] }}">
            </div>
            <div class="filter-field grow">
                <label>Search</label>
                <input class="input" type="text" name="q" value="{{ $filters['q'] }}" placeholder="Subject, description, user…">
            </div>
            <div class="filter-actions">
                <button class="btn btn-primary" type="submit">Apply</button>
                @if($hasFilters)
                    <a class="btn btn-ghost" href="{{ route('history.index') }}">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>When</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Subject</th>
                    <th>Details</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td style="white-space:nowrap;">
                            <div class="strong" style="color:var(--text);">{{ $log->created_at->format('d M Y') }}</div>
                            <div class="progress-meta">{{ $log->created_at->format('h:i:s A') }}</div>
                        </td>
                        <td>
                            <div class="cell-name">
                                <span class="cell-avatar" style="background:var(--primary);">{{ strtoupper(mb_substr($log->user_name ?: 'S', 0, 1)) }}</span>
                                <div>
                                    <div class="strong" style="color:var(--text);">{{ $log->user_name ?: 'System' }}</div>
                                    <div class="progress-meta">{{ $log->user_role ?: '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge {{ $actionBadge[$log->action] ?? 'badge-neutral' }}">{{ ucfirst($log->action) }}</span></td>
                        <td>
                            @if($log->subject_type)
                                <div class="strong" style="color:var(--text);">{{ $log->subject_type }}</div>
                                <div class="progress-meta">{{ $log->subject_label ?: ('#' . $log->subject_id) }}</div>
                            @else
                                <span class="progress-meta">—</span>
                            @endif
                        </td>
                        <td style="max-width:280px; white-space:normal;">
                            <div>{{ $log->description }}</div>
                            @if(!empty($log->changes['fields']))
                                <div class="progress-meta" style="margin-top:3px;">Fields: {{ implode(', ', $log->changes['fields']) }}</div>
                            @endif
                        </td>
                        <td class="progress-meta">{{ $log->ip_address ?: '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <div>{{ $hasFilters ? 'No activity matches these filters.' : 'No activity recorded yet.' }}</div>
                        </div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div style="padding:16px 22px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
        <span style="font-size:0.83rem; color:var(--muted);">Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }}</span>
        <div style="display:flex; gap:8px;">
            @if(!$logs->onFirstPage())<a class="btn btn-ghost btn-sm" href="{{ $logs->previousPageUrl() }}">Previous</a>@endif
            @if($logs->hasMorePages())<a class="btn btn-ghost btn-sm" href="{{ $logs->nextPageUrl() }}">Next</a>@endif
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<style>
    .filter-form { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
    .filter-field { display: flex; flex-direction: column; gap: 6px; }
    .filter-field label { font-size: 0.78rem; font-weight: 600; color: var(--text-soft); }
    .filter-field .select, .filter-field .input { min-width: 150px; }
    .filter-field.grow { flex: 1; min-width: 180px; }
    .filter-field.grow .input { width: 100%; }
    .filter-actions { display: flex; gap: 8px; }
    @media (max-width: 640px) { .filter-field, .filter-field .select, .filter-field .input { min-width: 100%; width: 100%; } }
</style>
@endsection
