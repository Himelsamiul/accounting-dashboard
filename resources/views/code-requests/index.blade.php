@extends('layouts.admin')

@section('title', 'Code Requests')

@section('content')
<div class="page-header">
    <div>
        <h1>Tracking Code Requests</h1>
        <div class="sub">Clients who asked to receive their project tracking code again</div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Requested By</th>
                    <th>Note</th>
                    <th>When</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td class="strong">{{ $req->email }}</td>
                        <td>{{ $req->customer->name ?? '—' }}</td>
                        <td>{{ $req->note ?: '—' }}</td>
                        <td>{{ $req->created_at->diffForHumans() }}</td>
                        <td>
                            @if($req->status === 'sent')
                                <span class="badge badge-success">Sent</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                @if($req->status === 'sent')
                                    <span class="badge badge-success" title="Sent {{ optional($req->handled_at)->format('d M Y, h:i A') }}">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" style="margin-right:2px;"><polyline points="20 6 9 17 4 12"/></svg>
                                        Code sent
                                    </span>
                                @elseif(auth()->user()->hasPermission('code_requests', 'edit'))
                                <form method="POST" action="{{ route('code-requests.send', $req->id) }}"
                                      onsubmit="return confirm('Email the tracking code(s) for {{ $req->email }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                        Send code
                                    </button>
                                </form>
                                @endif
                                @if(auth()->user()->hasPermission('code_requests', 'delete'))
                                <form method="POST" action="{{ route('code-requests.destroy', $req->id) }}"
                                      onsubmit="return confirm('Remove this request?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="act danger" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <div>No code requests yet.</div>
                        </div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
    <div style="padding:16px 22px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
        <span style="font-size:0.83rem; color:var(--muted);">Page {{ $requests->currentPage() }} of {{ $requests->lastPage() }}</span>
        <div style="display:flex; gap:8px;">
            @if(!$requests->onFirstPage())<a class="btn btn-ghost btn-sm" href="{{ $requests->previousPageUrl() }}">Previous</a>@endif
            @if($requests->hasMorePages())<a class="btn btn-ghost btn-sm" href="{{ $requests->nextPageUrl() }}">Next</a>@endif
        </div>
    </div>
    @endif
</div>
@endsection
