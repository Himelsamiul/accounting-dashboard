@extends('layouts.admin')

@section('title', 'Client Details')

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $client->name }}</h1>
        <div class="sub">{{ $client->company ?: 'Client profile' }}</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-ghost">Edit</a>
        <a href="{{ route('clients.index') }}" class="btn btn-ghost">Back to list</a>
    </div>
</div>

<div class="card" style="max-width:900px;">
    <div class="card-header"><h2>Profile</h2></div>
    <div class="card-body">
        <div class="detail-list">
            <div class="detail-row"><div class="dt">Client Name</div><div class="dd">{{ $client->name }}</div></div>
            <div class="detail-row"><div class="dt">Company</div><div class="dd">{{ $client->company ?: '—' }}</div></div>
            <div class="detail-row"><div class="dt">Email</div><div class="dd">{{ $client->email ?: '—' }}</div></div>
            <div class="detail-row"><div class="dt">Phone</div><div class="dd">{{ $client->phone ?: '—' }}</div></div>
            <div class="detail-row"><div class="dt">Projects</div><div class="dd">{{ $client->projects->count() }}</div></div>
            <div class="detail-row"><div class="dt">Invoices</div><div class="dd">{{ $client->invoices->count() }}</div></div>
            <div class="detail-row full"><div class="dt">Address</div><div class="dd">{{ $client->address ?: '—' }}</div></div>
        </div>
    </div>
</div>
@endsection
