@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="page-header">
    <div>
        <h1>Users</h1>
        <div class="sub">{{ $users->count() }} user{{ $users->count() === 1 ? '' : 's' }} · manage accounts, roles and passwords</div>
    </div>
    <div class="header-actions">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search users…" data-search="#usersTable">
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-ghost">Roles</a>
        @if(auth()->user()->hasPermission('users', 'create'))
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New User
            </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table" id="usersTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Designation</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $palette = ['#4f46e5','#0ea5e9','#8b5cf6','#ec4899','#f59e0b','#16a34a','#14b8a6','#f43f5e']; @endphp
                @forelse($users as $user)
                    @php $c = $palette[$user->id % count($palette)]; @endphp
                    <tr>
                        <td class="strong">
                            <div class="cell-name">
                                <span class="cell-avatar" style="background:{{ $c }}">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                                {{ $user->name }}
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->isSuperAdmin())
                                <span class="badge badge-primary">Super Admin</span>
                            @elseif($user->role)
                                <span class="badge badge-neutral">{{ $user->role->name }}</span>
                            @else
                                <span class="badge badge-warning">No role</span>
                            @endif
                        </td>
                        <td>{{ $user->designation ?: '—' }}</td>
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                @if($user->isSuperAdmin())
                                    <span class="progress-meta">Protected</span>
                                @else
                                    @if(auth()->user()->hasPermission('users', 'edit'))
                                        <form method="POST" action="{{ route('users.generate-password', $user->id) }}" onsubmit="return confirm('Generate a new password for {{ $user->name }}?')">@csrf
                                            <button type="submit" class="act" title="Generate new password">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"/><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M3 22v-6h6"/><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/></svg>
                                            </button>
                                        </form>
                                        <a href="{{ route('users.edit', $user->id) }}" class="act" title="Edit">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </a>
                                    @endif
                                    @if(auth()->user()->hasPermission('users', 'delete'))
                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Delete this user?')">@csrf @method('DELETE')
                                            <button type="submit" class="act danger" title="Delete">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-empty><td colspan="5">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            <div>No users yet.</div>
                        </div>
                    </td></tr>
                @endforelse
                <tr class="no-results" style="display:none;"><td colspan="5">No users match your search.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
