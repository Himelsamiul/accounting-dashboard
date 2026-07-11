@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('content')
<div class="page-header">
    <div>
        <h1>Contact Messages</h1>
        <div class="sub">{{ $messages->count() }} message{{ $messages->count() === 1 ? '' : 's' }} · {{ $unread }} unread</div>
    </div>
    <div class="header-actions">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search messages…" data-search="#messagesTable">
        </div>
    </div>
</div>

<div class="card">
    <div class="card-toolbar">
        <div class="filter-chips">
            <button type="button" class="chip active" data-filter="#messagesTable" data-value="all">All</button>
            <button type="button" class="chip" data-filter="#messagesTable" data-value="unread">Unread</button>
            <button type="button" class="chip" data-filter="#messagesTable" data-value="read">Read</button>
        </div>
    </div>
    <div class="table-wrap">
        <table class="table" id="messagesTable">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Received</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr data-status="{{ $msg->is_read ? 'read' : 'unread' }}">
                        <td class="strong">{{ $msg->name }}</td>
                        <td><a href="mailto:{{ $msg->email }}" style="color:var(--primary);">{{ $msg->email }}</a></td>
                        <td style="max-width:380px; white-space:normal; color:var(--text-soft);">{{ $msg->message }}</td>
                        <td style="white-space:nowrap;">{{ $msg->created_at ? $msg->created_at->format('d M Y, h:i A') : '—' }}</td>
                        <td><span class="badge {{ $msg->is_read ? 'badge-neutral' : 'badge-primary' }}">{{ $msg->is_read ? 'Read' : 'New' }}</span></td>
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                <form method="POST" action="{{ route('contacts.read', $msg->id) }}">@csrf
                                    <button class="act" title="{{ $msg->is_read ? 'Mark unread' : 'Mark read' }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    </button>
                                </form>
                                @if(auth()->user()->hasPermission('contacts', 'delete'))
                                    <form method="POST" action="{{ route('contacts.destroy', $msg->id) }}" onsubmit="return confirm('Delete this message?')">@csrf @method('DELETE')
                                        <button class="act danger" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-empty><td colspan="6"><div class="empty-state"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/></svg><div>No messages yet.</div></div></td></tr>
                @endforelse
                <tr class="no-results" style="display:none;"><td colspan="6">No messages match your search.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
