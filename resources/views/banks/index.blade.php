@extends('layouts.admin')

@section('title', 'Banks')

@section('content')
<div class="page-header">
    <div>
        <h1>Bank Accounts</h1>
        <div class="sub">{{ $banks->count() }} account{{ $banks->count() === 1 ? '' : 's' }} on file</div>
    </div>
    <div class="header-actions">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search banks…" data-search="#banksTable">
        </div>
        @if(auth()->user()->hasPermission('banks', 'create'))
        <a href="{{ route('banks.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Bank
        </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table" id="banksTable">
            <thead>
                <tr>
                    <th>Bank Name</th>
                    <th>Account #</th>
                    <th>Branch</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banks as $bank)
                    <tr>
                        <td class="strong">{{ $bank->name }}</td>
                        <td>{{ $bank->account_number ?: '—' }}</td>
                        <td>{{ $bank->branch ?: '—' }}</td>
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                <a href="{{ route('banks.show', $bank->id) }}" class="act" title="View">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                @if(auth()->user()->hasPermission('banks', 'edit'))
                                <a href="{{ route('banks.edit', $bank->id) }}" class="act" title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                @endif
                                @if(auth()->user()->hasPermission('banks', 'delete'))
                                <form method="POST" action="{{ route('banks.delete', $bank->id) }}" onsubmit="return confirm('Delete this bank?')">@csrf
                                    <button type="submit" class="act danger" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-empty><td colspan="4">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>
                            <div>No bank accounts yet. Add one to record payments.</div>
                        </div>
                    </td></tr>
                @endforelse
                <tr class="no-results" style="display:none;"><td colspan="4">No banks match your search.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
