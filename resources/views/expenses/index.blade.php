@extends('layouts.admin')

@section('title', 'Expenses')

@section('content')
<div class="page-header">
    <div>
        <h1>Expenses</h1>
        <div class="sub">{{ $expenses->count() }} expense{{ $expenses->count() === 1 ? '' : 's' }} · Total ৳{{ number_format($total, 2) }}</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('expense-heads.index') }}" class="btn btn-ghost">Expense Heads</a>
        <a href="{{ route('expenses.report') }}" class="btn btn-ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
            Report
        </a>
        <form method="GET" action="{{ route('expenses.index') }}" style="display:flex;">
            <select class="select" name="head" onchange="this.form.submit()" style="min-width:180px;">
                <option value="">All heads</option>
                @foreach($heads as $head)
                    <option value="{{ $head->id }}" {{ (string) request('head') === (string) $head->id ? 'selected' : '' }}>{{ $head->name }}</option>
                @endforeach
            </select>
        </form>
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search expenses…" data-search="#expensesTable">
        </div>
        @if(auth()->user()->hasPermission('expenses', 'create'))
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Expense
        </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table" id="expensesTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Head</th>
                    <th>Method</th>
                    <th style="text-align:right;">Amount</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->expense_date ? $expense->expense_date->format('d M Y') : '—' }}</td>
                        <td class="strong">{{ $expense->title }}</td>
                        <td>{{ $expense->head?->name ?? '—' }}</td>
                        <td>{{ $expense->payment_method ?: '—' }}{{ $expense->bank ? ' · ' . $expense->bank->name : '' }}</td>
                        <td style="text-align:right;">৳{{ number_format((float) $expense->amount, 2) }}</td>
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                @if(auth()->user()->hasPermission('expenses', 'edit'))
                                <a href="{{ route('expenses.edit', $expense->id) }}" class="act" title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                @endif
                                @if(auth()->user()->hasPermission('expenses', 'delete'))
                                <form method="POST" action="{{ route('expenses.delete', $expense->id) }}" onsubmit="return confirm('Delete this expense?')">@csrf
                                    <button type="submit" class="act danger" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-empty><td colspan="6">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            <div>No expenses recorded yet. @if($heads->isEmpty())Create an expense head first, then add expenses.@else Add one to get started.@endif</div>
                        </div>
                    </td></tr>
                @endforelse
                <tr class="no-results" style="display:none;"><td colspan="6">No expenses match your search.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
