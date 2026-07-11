@extends('layouts.admin')

@section('title', 'Portal Customers')

@section('content')
<div class="page-header">
    <div>
        <h1>Portal Customers</h1>
        <div class="sub">{{ $customers->count() }} registered client{{ $customers->count() === 1 ? '' : 's' }} on the tracking portal</div>
    </div>
    <div class="header-actions">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search customers…" data-search="#customersTable">
        </div>
    </div>
</div>

<div class="card">
    <div class="card-toolbar">
        <div class="filter-chips">
            <button type="button" class="chip active" data-filter="#customersTable" data-value="all">All</button>
            <button type="button" class="chip" data-filter="#customersTable" data-value="active">Active</button>
            <button type="button" class="chip" data-filter="#customersTable" data-value="inactive">Inactive</button>
            <button type="button" class="chip" data-filter="#customersTable" data-value="blocked">Blocked</button>
        </div>
    </div>
    <div class="table-wrap">
        <table class="table" id="customersTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Registered</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $palette = ['#4f46e5','#0ea5e9','#8b5cf6','#ec4899','#f59e0b','#16a34a','#14b8a6','#f43f5e']; @endphp
                @forelse($customers as $customer)
                    @php
                        $c = $palette[$customer->id % count($palette)];
                        $badge = ['active' => 'badge-success', 'inactive' => 'badge-warning', 'blocked' => 'badge-danger'][$customer->status] ?? 'badge-neutral';
                    @endphp
                    <tr data-status="{{ $customer->status }}">
                        <td class="strong">
                            <div class="cell-name">
                                <span class="cell-avatar" style="background:{{ $c }}">{{ strtoupper(mb_substr($customer->name, 0, 1)) }}</span>
                                {{ $customer->name }}
                            </div>
                        </td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?: '—' }}</td>
                        <td>{{ $customer->created_at ? $customer->created_at->format('d M Y') : '—' }}</td>
                        <td>
                            <span class="badge {{ $badge }}">{{ ucfirst($customer->status) }}</span>
                            @if(!$customer->email_verified_at)<span class="badge badge-neutral" title="Email not verified">Unverified</span>@endif
                        </td>
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                @if(auth()->user()->hasPermission('customers', 'edit'))
                                    @if($customer->status !== 'active')
                                        <form method="POST" action="{{ route('customers.status', $customer->id) }}">@csrf
                                            <input type="hidden" name="status" value="active">
                                            <button class="act" title="Activate" style="color:var(--success);">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if($customer->status !== 'inactive')
                                        <form method="POST" action="{{ route('customers.status', $customer->id) }}">@csrf
                                            <input type="hidden" name="status" value="inactive">
                                            <button class="act" title="Deactivate" style="color:var(--warning);">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if($customer->status !== 'blocked')
                                        <form method="POST" action="{{ route('customers.status', $customer->id) }}" onsubmit="return confirm('Block {{ $customer->name }}? They will not be able to sign in.')">@csrf
                                            <input type="hidden" name="status" value="blocked">
                                            <button class="act danger" title="Block">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                @if(auth()->user()->hasPermission('customers', 'delete'))
                                    <form method="POST" action="{{ route('customers.destroy', $customer->id) }}" onsubmit="return confirm('Delete this customer permanently?')">@csrf @method('DELETE')
                                        <button class="act danger" title="Delete">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-empty><td colspan="6">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            <div>No portal customers have registered yet.</div>
                        </div>
                    </td></tr>
                @endforelse
                <tr class="no-results" style="display:none;"><td colspan="6">No customers match your search.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
