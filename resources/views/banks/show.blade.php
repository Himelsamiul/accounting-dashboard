@extends('layouts.admin')

@section('title', 'Bank Details')

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $bank->name }}</h1>
        <div class="sub">Bank account details</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('banks.edit', $bank->id) }}" class="btn btn-ghost">Edit</a>
        <a href="{{ route('banks.index') }}" class="btn btn-ghost">Back to list</a>
    </div>
</div>

<div class="card" style="max-width:900px;">
    <div class="card-header"><h2>Account information</h2></div>
    <div class="card-body">
        <div class="detail-list">
            <div class="detail-row"><div class="dt">Bank Name</div><div class="dd">{{ $bank->name }}</div></div>
            <div class="detail-row"><div class="dt">Account Number</div><div class="dd">{{ $bank->account_number ?: '—' }}</div></div>
            <div class="detail-row"><div class="dt">Branch</div><div class="dd">{{ $bank->branch ?: '—' }}</div></div>
            <div class="detail-row"><div class="dt">Linked Invoices</div><div class="dd">{{ $bank->invoices->count() }}</div></div>
            <div class="detail-row full"><div class="dt">Notes</div><div class="dd">{{ $bank->notes ?: '—' }}</div></div>
        </div>
    </div>
</div>
@endsection
