@extends('layouts.admin')

@section('title', 'Invoice Details')

@section('content')
@php
    $badge = ['Paid' => 'badge-success', 'Partial' => 'badge-warning', 'Pending' => 'badge-danger'][$invoice->status] ?? 'badge-neutral';
@endphp
<div class="page-header">
    <div>
        <h1>{{ $invoice->invoice_number }}</h1>
        <div class="sub">Invoice for {{ $invoice->client->name ?? '—' }}</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download PDF
        </a>
        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-ghost">Edit</a>
        <a href="{{ route('invoices.index') }}" class="btn btn-ghost">Back to list</a>
    </div>
</div>

<div class="card" style="max-width:900px;">
    <div class="card-header">
        <h2>Invoice summary</h2>
        <span class="badge {{ $badge }}">{{ $invoice->status }}</span>
    </div>
    <div class="card-body">
        <div class="detail-list">
            <div class="detail-row"><div class="dt">Invoice #</div><div class="dd">{{ $invoice->invoice_number }}</div></div>
            <div class="detail-row"><div class="dt">Invoice Date</div><div class="dd">{{ $invoice->invoice_date ?: '—' }}</div></div>
            <div class="detail-row"><div class="dt">Client</div><div class="dd">{{ $invoice->client->name ?? '—' }}</div></div>
            <div class="detail-row"><div class="dt">Project</div><div class="dd">{{ $invoice->project->name ?? '—' }}</div></div>
            <div class="detail-row"><div class="dt">Bank</div><div class="dd">{{ $invoice->bank->name ?? '—' }}</div></div>
            <div class="detail-row"><div class="dt">Handover To</div><div class="dd">{{ $invoice->handover_to ?: '—' }}</div></div>
            <div class="detail-row"><div class="dt">Total Amount</div><div class="dd">৳{{ number_format($invoice->amount, 2) }}</div></div>
            <div class="detail-row"><div class="dt">Paid Amount</div><div class="dd">৳{{ number_format($invoice->paid_amount, 2) }}</div></div>
            <div class="detail-row"><div class="dt">Balance Due</div><div class="dd">৳{{ number_format($invoice->balance_amount, 2) }}</div></div>
            <div class="detail-row"><div class="dt">Status</div><div class="dd">{{ $invoice->status }}</div></div>
            <div class="detail-row full"><div class="dt">Description</div><div class="dd">{{ $invoice->description ?: '—' }}</div></div>
        </div>
    </div>
</div>
@endsection
