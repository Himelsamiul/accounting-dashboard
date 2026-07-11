@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
<div class="page-header">
    <div>
        <h1>Client Reviews</h1>
        <div class="sub">{{ $reviews->count() }} review{{ $reviews->count() === 1 ? '' : 's' }} · average rating {{ $avg }}★</div>
    </div>
    <div class="header-actions">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search reviews…" data-search="#reviewsTable">
        </div>
    </div>
</div>

<div class="card">
    <div class="card-toolbar">
        <div class="filter-chips">
            <button type="button" class="chip active" data-filter="#reviewsTable" data-value="all">All</button>
            <button type="button" class="chip" data-filter="#reviewsTable" data-value="published">Published</button>
            <button type="button" class="chip" data-filter="#reviewsTable" data-value="hidden">Hidden</button>
        </div>
    </div>
    <div class="table-wrap">
        <table class="table" id="reviewsTable">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $palette = ['#4f46e5','#0ea5e9','#8b5cf6','#ec4899','#f59e0b','#16a34a','#14b8a6','#f43f5e']; @endphp
                @forelse($reviews as $review)
                    @php $c = $palette[$review->id % count($palette)]; @endphp
                    <tr data-status="{{ $review->is_approved ? 'published' : 'hidden' }}">
                        <td class="strong">
                            <div class="cell-name">
                                <span class="cell-avatar" style="background:{{ $c }}">{{ strtoupper(mb_substr($review->name, 0, 1)) }}</span>
                                <div>{{ $review->name }}<div style="font-size:0.76rem; color:var(--muted); font-weight:400;">{{ $review->role }}</div></div>
                            </div>
                        </td>
                        <td style="color:#f59e0b; white-space:nowrap;">{{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }}</td>
                        <td style="max-width:360px; white-space:normal; color:var(--text-soft);">{{ $review->comment }}</td>
                        <td>{{ $review->created_at ? $review->created_at->format('d M Y') : '—' }}</td>
                        <td><span class="badge {{ $review->is_approved ? 'badge-success' : 'badge-neutral' }}">{{ $review->is_approved ? 'Published' : 'Hidden' }}</span></td>
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                @if(auth()->user()->hasPermission('reviews', 'edit'))
                                    <form method="POST" action="{{ route('reviews.toggle', $review->id) }}">@csrf
                                        <button class="act" title="{{ $review->is_approved ? 'Hide from website' : 'Publish to website' }}" style="color:{{ $review->is_approved ? 'var(--warning)' : 'var(--success)' }};">
                                            @if($review->is_approved)
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                            @else
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                @endif
                                @if(auth()->user()->hasPermission('reviews', 'delete'))
                                    <form method="POST" action="{{ route('reviews.destroy', $review->id) }}" onsubmit="return confirm('Delete this review?')">@csrf @method('DELETE')
                                        <button class="act danger" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-empty><td colspan="6"><div class="empty-state"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3 6 6 .9-4.5 4.3 1 6.1L12 17l-5.5 2.3 1-6.1L3 8.9 9 8z"/></svg><div>No reviews yet.</div></div></td></tr>
                @endforelse
                <tr class="no-results" style="display:none;"><td colspan="6">No reviews match your search.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
