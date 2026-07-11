@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')
<div class="page-header">
    <div>
        <h1>Invoices</h1>
        <div class="sub">Client, project, paid amount, remaining balance and payment status.</div>
    </div>
    <div class="header-actions">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search invoices…" data-search="#invoicesTable">
        </div>
        @if(auth()->user()->hasPermission('invoices', 'create'))
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Create Invoice
        </a>
        @endif
    </div>
</div>

<div class="list-layout">
    <div class="card">
        <div class="card-toolbar">
            <div class="filter-chips">
                <button type="button" class="chip active" data-filter="#invoicesTable" data-value="all">All</button>
                <button type="button" class="chip" data-filter="#invoicesTable" data-value="Paid">Paid</button>
                <button type="button" class="chip" data-filter="#invoicesTable" data-value="Partial">Partial</button>
                <button type="button" class="chip" data-filter="#invoicesTable" data-value="Pending">Pending</button>
            </div>
            <span class="progress-meta">{{ $invoices->count() }} invoice{{ $invoices->count() === 1 ? '' : 's' }}</span>
        </div>
        <div class="table-wrap">
            <table class="table" id="invoicesTable">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Client</th>
                        <th>Project</th>
                        <th style="text-align:right;">Amount</th>
                        <th style="text-align:right;">Paid</th>
                        <th style="text-align:right;">Balance</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        @php
                            $badge = ['Paid' => 'badge-success', 'Partial' => 'badge-warning', 'Pending' => 'badge-danger'][$invoice->status] ?? 'badge-neutral';
                        @endphp
                        <tr data-status="{{ $invoice->status }}">
                            <td class="strong">{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->client->name ?? '—' }}</td>
                            <td>{{ $invoice->project->name ?? '—' }}</td>
                            <td style="text-align:right;">৳{{ number_format($invoice->amount, 2) }}</td>
                            <td style="text-align:right;">৳{{ number_format($invoice->paid_amount, 2) }}</td>
                            <td style="text-align:right;">৳{{ number_format($invoice->balance_amount, 2) }}</td>
                            <td><span class="badge {{ $badge }}">{{ $invoice->status }}</span></td>
                            <td>
                                <div class="row-actions" style="justify-content:flex-end;">
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="act" title="View">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <a href="{{ route('invoices.pdf', $invoice->id) }}" class="act" title="Download PDF">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    </a>
                                    @if(auth()->user()->hasPermission('invoices', 'edit'))
                                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="act" title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                    @endif
                                    @if(auth()->user()->hasPermission('invoices', 'delete'))
                                    <form method="POST" action="{{ route('invoices.delete', $invoice->id) }}" onsubmit="return confirm('Delete this invoice?')">@csrf
                                        <button type="submit" class="act danger" title="Delete">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr data-empty><td colspan="8">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <div>No invoices yet. Create your first invoice.</div>
                            </div>
                        </td></tr>
                    @endforelse
                    <tr class="no-results" style="display:none;"><td colspan="8">No invoices match your search.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <aside class="card">
        <div class="card-header">
            <div>
                <h3>Fully paid projects</h3>
                <div class="sub">Collected in full</div>
            </div>
            <span class="badge badge-success">{{ $completedProjects->count() }}</span>
        </div>
        <div class="card-body" style="padding:8px 18px;">
            @forelse($completedProjects as $cp)
                <div class="completed-item">
                    <div>
                        <div class="ci-name">{{ $cp->name }}</div>
                        <div class="ci-sub">{{ $cp->client->name ?? 'No client' }} · ৳{{ number_format($cp->project_value, 0) }}</div>
                    </div>
                    <span class="badge badge-success">Paid</span>
                </div>
            @empty
                <div style="color:var(--muted); font-size:0.85rem; padding:12px 0; text-align:center;">
                    No fully paid projects yet.
                </div>
            @endforelse
        </div>
    </aside>
</div>
@endsection
