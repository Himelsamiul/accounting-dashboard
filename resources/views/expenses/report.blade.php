@extends('layouts.admin')

@section('title', 'Expense Report')

@section('content')
<div class="page-header">
    <div>
        <h1>Expense Report</h1>
        <div class="sub">{{ $expenses->count() }} expense{{ $expenses->count() === 1 ? '' : 's' }} · Total ৳{{ number_format($total, 2) }}</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('expenses.index') }}" class="btn btn-ghost">Back to Expenses</a>
        <a href="{{ route('expenses.report.pdf', request()->query()) }}" class="btn btn-ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
            PDF
        </a>
        <a href="{{ route('expenses.report.excel', request()->query()) }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
            Excel
        </a>
    </div>
</div>

{{-- Filter bar --}}
<div class="card" style="margin-bottom:18px;">
    <div class="card-body">
        <form method="GET" action="{{ route('expenses.report') }}">
            <div class="form-grid" style="align-items:flex-end;">
                <div class="field">
                    <label>Expense Head</label>
                    <select class="select" name="head">
                        <option value="">All heads</option>
                        @foreach($heads as $head)
                            <option value="{{ $head->id }}" {{ (string) request('head') === (string) $head->id ? 'selected' : '' }}>{{ $head->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Payment Method</label>
                    <input class="input" type="text" name="method" value="{{ request('method') }}" placeholder="e.g. Cash, bKash">
                </div>
                <div class="field">
                    <label>From Date</label>
                    <input class="input" type="date" name="from" value="{{ request('from') }}">
                </div>
                <div class="field">
                    <label>To Date</label>
                    <input class="input" type="date" name="to" value="{{ request('to') }}">
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Apply Filters</button>
                <a href="{{ route('expenses.report') }}" class="btn btn-ghost">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Summary tiles --}}
<div class="stat-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom:18px;">
    <div class="stat-card">
        <div class="stat-value">৳{{ number_format($total, 2) }}</div>
        <div class="stat-label">Total Expense</div>
        <div class="stat-sub">across selected filters</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $expenses->count() }}</div>
        <div class="stat-label">Entries</div>
        <div class="stat-sub">{{ $byHead->count() }} head{{ $byHead->count() === 1 ? '' : 's' }} involved</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">৳{{ number_format($expenses->count() ? $total / $expenses->count() : 0, 2) }}</div>
        <div class="stat-label">Average / Entry</div>
        <div class="stat-sub">mean expense amount</div>
    </div>
</div>

{{-- Head-wise breakdown --}}
@if($byHead->isNotEmpty())
<div class="card" style="margin-bottom:18px;">
    <div class="card-header"><h2>Head-wise Breakdown</h2></div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Expense Head</th>
                    <th style="text-align:right;">Entries</th>
                    <th style="text-align:right;">Total</th>
                    <th style="text-align:right;">% of Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($byHead as $name => $row)
                    <tr>
                        <td class="strong">{{ $name }}</td>
                        <td style="text-align:right;">{{ $row['count'] }}</td>
                        <td style="text-align:right;">৳{{ number_format($row['total'], 2) }}</td>
                        <td style="text-align:right;">{{ $total > 0 ? number_format($row['total'] / $total * 100, 1) : '0.0' }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Detailed entries --}}
<div class="card">
    <div class="card-header"><h2>Detailed Entries</h2></div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:26px;">#</th>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Head</th>
                    <th>Method</th>
                    <th style="text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $i => $expense)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $expense->expense_date ? $expense->expense_date->format('d M Y') : '—' }}</td>
                        <td class="strong">{{ $expense->title }}</td>
                        <td>{{ $expense->head?->name ?? '—' }}</td>
                        <td>{{ $expense->payment_method ?: '—' }}{{ $expense->bank ? ' · ' . $expense->bank->name : '' }}</td>
                        <td style="text-align:right;">৳{{ number_format((float) $expense->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            <div>No expenses match the selected filters.</div>
                        </div>
                    </td></tr>
                @endforelse
            </tbody>
            @if($expenses->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align:right;font-weight:700;">Total</td>
                    <td style="text-align:right;font-weight:700;">৳{{ number_format($total, 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
