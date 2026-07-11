@extends('layouts.admin')

@section('title', 'Roles')

@section('content')
<div class="page-header">
    <div>
        <h1>Roles</h1>
        <div class="sub">{{ $roles->count() }} role{{ $roles->count() === 1 ? '' : 's' }} · define what each role can access</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('users.index') }}" class="btn btn-ghost">Users</a>
        @if(auth()->user()->hasPermission('users', 'create'))
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Role
            </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th style="text-align:center;">Users</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td class="strong">{{ $role->name }}</td>
                        <td>
                            @php $perms = $role->permissions ?? []; @endphp
                            @if(empty($perms))
                                <span class="badge badge-neutral">No access</span>
                            @else
                                @foreach($perms as $module => $acts)
                                    <span class="badge badge-primary" style="margin:2px 2px;">{{ \App\Models\Role::modules()[$module] ?? $module }}: {{ implode('/', $acts) }}</span>
                                @endforeach
                            @endif
                        </td>
                        <td style="text-align:center;">{{ $role->users_count }}</td>
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                @if(auth()->user()->hasPermission('users', 'edit'))
                                    <a href="{{ route('roles.edit', $role->id) }}" class="act" title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                @endif
                                @if(auth()->user()->hasPermission('users', 'delete'))
                                    <form method="POST" action="{{ route('roles.destroy', $role->id) }}" onsubmit="return confirm('Delete this role?')">@csrf @method('DELETE')
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
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l7 4v6c0 5-3.5 8-7 10-3.5-2-7-5-7-10V6z"/></svg>
                            <div>No roles yet. Create a role and assign permissions.</div>
                        </div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
