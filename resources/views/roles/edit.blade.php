@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Role</h1>
        <div class="sub">Update permissions for {{ $role->name }}.</div>
    </div>
    <a href="{{ route('roles.index') }}" class="btn btn-ghost">Back to roles</a>
</div>

<form method="POST" action="{{ route('roles.update', $role->id) }}">
    @csrf @method('PUT')
    <div class="card" style="max-width:820px; margin-bottom:18px;">
        <div class="card-header"><h2>Role details</h2></div>
        <div class="card-body">
            <div class="field">
                <label>Role Name <span class="req">*</span></label>
                <input class="input" type="text" name="name" value="{{ old('name', $role->name) }}" required style="max-width:400px;">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2>Permissions</h2></div>
        <div class="card-body">
            @include('roles._matrix', ['selected' => old('permissions', $role->permissions ?? [])])
            <div class="form-actions" style="margin-top:18px;">
                <button class="btn btn-primary" type="submit">Update Role</button>
                <a href="{{ route('roles.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
            <div class="form-legend"><span class="req">*</span> Required field</div>
        </div>
    </div>
</form>
@endsection
