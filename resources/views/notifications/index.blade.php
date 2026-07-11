@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
@php
    // Map a notification's icon/type to an inline SVG + accent colour.
    $iconFor = function ($n) {
        $key = $n->icon ?: $n->type;
        return match ($key) {
            'user', 'customer' => ['#4f46e5', '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'],
            'star', 'review'   => ['#f59e0b', '<polygon points="12 2 15 8.5 22 9.3 17 14 18.2 21 12 17.5 5.8 21 7 14 2 9.3 9 8.5 12 2"/>'],
            'mail', 'message'  => ['#0ea5e9', '<path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/>'],
            default            => ['#8b5cf6', '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>'],
        };
    };
    $unreadCount = $notifications->getCollection()->where('is_read', false)->count();
@endphp

<div class="page-header">
    <div>
        <h1>Notifications</h1>
        <div class="sub">{{ $notifications->total() }} total · activity from across your workspace</div>
    </div>
    <div class="header-actions">
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button class="btn btn-ghost" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Mark all read
            </button>
        </form>
    </div>
</div>

<div class="card">
    <div class="notif-list">
        @forelse($notifications as $n)
            @php [$accent, $path] = $iconFor($n); @endphp
            <a href="{{ route('notifications.open', $n->id) }}" class="notif-item {{ $n->is_read ? '' : 'unread' }}">
                <span class="notif-ic" style="background:{{ $accent }}1a; color:{{ $accent }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $path !!}</svg>
                </span>
                <span class="notif-main">
                    <span class="notif-title">{{ $n->title }}</span>
                    @if($n->body)<span class="notif-body">{{ $n->body }}</span>@endif
                    <span class="notif-time">{{ $n->created_at->diffForHumans() }} · {{ $n->created_at->format('d M Y, h:i A') }}</span>
                </span>
                @unless($n->is_read)<span class="notif-dot" title="Unread"></span>@endunless
            </a>
        @empty
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <div>You're all caught up. No notifications yet.</div>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="pager">
        <div class="pager-info">Page {{ $notifications->currentPage() }} of {{ $notifications->lastPage() }}</div>
        <div class="pager-links">
            @if($notifications->onFirstPage())
                <span class="pager-btn disabled">Previous</span>
            @else
                <a class="pager-btn" href="{{ $notifications->previousPageUrl() }}">Previous</a>
            @endif
            @if($notifications->hasMorePages())
                <a class="pager-btn" href="{{ $notifications->nextPageUrl() }}">Next</a>
            @else
                <span class="pager-btn disabled">Next</span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<style>
    .notif-list { display: flex; flex-direction: column; }
    .notif-item { display: flex; align-items: flex-start; gap: 14px; padding: 16px 22px; border-bottom: 1px solid var(--border); text-decoration: none; color: var(--text); transition: background .12s ease; position: relative; }
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background: var(--surface-2); }
    .notif-item.unread { background: var(--primary-soft); }
    .notif-item.unread:hover { background: var(--primary-soft); filter: brightness(0.98); }
    .notif-ic { width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center; flex-shrink: 0; }
    .notif-ic svg { width: 20px; height: 20px; }
    .notif-main { display: flex; flex-direction: column; gap: 3px; min-width: 0; flex: 1; }
    .notif-title { font-weight: 600; font-size: 0.92rem; }
    .notif-body { font-size: 0.85rem; color: var(--text-soft); overflow: hidden; text-overflow: ellipsis; }
    .notif-time { font-size: 0.76rem; color: var(--muted); margin-top: 2px; }
    .notif-dot { width: 9px; height: 9px; border-radius: 50%; background: var(--primary); flex-shrink: 0; margin-top: 6px; }
    .pager { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 16px 22px; border-top: 1px solid var(--border); flex-wrap: wrap; }
    .pager-info { font-size: 0.83rem; color: var(--muted); }
    .pager-links { display: flex; gap: 8px; }
    .pager-btn { padding: 8px 16px; border-radius: var(--radius-sm); border: 1px solid var(--border-strong); background: var(--surface); color: var(--text-soft); font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: background .12s; }
    .pager-btn:hover:not(.disabled) { background: var(--surface-3); }
    .pager-btn.disabled { opacity: 0.45; cursor: not-allowed; }
</style>
@endsection
