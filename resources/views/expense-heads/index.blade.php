@extends('layouts.admin')

@section('title', 'Expense Heads')

@section('content')
<div class="page-header">
    <div>
        <h1>Expense Heads</h1>
        <div class="sub">{{ $heads->count() }} head{{ $heads->count() === 1 ? '' : 's' }} on file</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('expenses.index') }}" class="btn btn-ghost">Expenses</a>
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search heads…" data-search="#headsTable">
        </div>
        @if(auth()->user()->hasPermission('expenses', 'create'))
        <a href="{{ route('expense-heads.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Expense Head
        </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table" id="headsTable">
            <thead>
                <tr>
                    <th>Head Name</th>
                    <th>Description</th>
                    <th style="text-align:right;">Expenses</th>
                    <th style="text-align:right;">Total</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($heads as $head)
                    <tr>
                        <td class="strong">{{ $head->name }}</td>
                        <td>{{ $head->description ?: '—' }}</td>
                        <td style="text-align:right;">{{ $head->expenses_count }}</td>
                        <td style="text-align:right;">৳{{ number_format((float) $head->expenses_total, 2) }}</td>
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                @if(auth()->user()->hasPermission('expenses', 'edit'))
                                <a href="{{ route('expense-heads.edit', $head->id) }}" class="act" title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                @endif
                                @if(auth()->user()->hasPermission('expenses', 'delete'))
                                <form method="POST" action="{{ route('expense-heads.delete', $head->id) }}" onsubmit="return confirm('Delete this expense head? All expenses under it will also be deleted.')">@csrf
                                    <button type="submit" class="act danger" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-empty><td colspan="5">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18"/><path d="M3 12h18"/><path d="M3 17h18"/></svg>
                            <div>No expense heads yet. Add one to start recording expenses.</div>
                        </div>
                    </td></tr>
                @endforelse
                <tr class="no-results" style="display:none;"><td colspan="5">No heads match your search.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
