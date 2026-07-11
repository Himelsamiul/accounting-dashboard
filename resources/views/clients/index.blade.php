@extends('layouts.admin')

@section('title', 'Clients')

@section('content')
<div class="page-header">
    <div>
        <h1>Clients</h1>
        <div class="sub">{{ $clients->count() }} client{{ $clients->count() === 1 ? '' : 's' }} registered</div>
    </div>
    <div class="header-actions">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search clients…" data-search="#clientsTable">
        </div>
        @if(auth()->user()->hasPermission('clients', 'create'))
        <a href="{{ route('clients.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Client
        </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table" id="clientsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $palette = ['#4f46e5','#0ea5e9','#8b5cf6','#ec4899','#f59e0b','#16a34a','#14b8a6','#f43f5e']; @endphp
                @forelse($clients as $client)
                    @php $c = $palette[$client->id % count($palette)]; @endphp
                    <tr>
                        <td class="strong">
                            <div class="cell-name">
                                <span class="cell-avatar" style="background:{{ $c }}">{{ strtoupper(mb_substr($client->name, 0, 1)) }}</span>
                                {{ $client->name }}
                            </div>
                        </td>
                        <td>{{ $client->company ?: '—' }}</td>
                        <td>{{ $client->email ?: '—' }}</td>
                        <td>{{ $client->phone ?: '—' }}</td>
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                <a href="{{ route('clients.show', $client->id) }}" class="act" title="View">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                @if(auth()->user()->hasPermission('clients', 'edit'))
                                <a href="{{ route('clients.edit', $client->id) }}" class="act" title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                @endif
                                @if(auth()->user()->hasPermission('clients', 'delete'))
                                <form method="POST" action="{{ route('clients.delete', $client->id) }}" onsubmit="return confirm('Delete this client?')">@csrf
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
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            <div>No clients yet. Add your first client to get started.</div>
                        </div>
                    </td></tr>
                @endforelse
                <tr class="no-results" style="display:none;"><td colspan="5">No clients match your search.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
