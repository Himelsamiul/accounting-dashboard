@extends('layouts.admin')

@section('title', 'Create Role')

@section('content')
<div class="page-header">
    <div>
        <h1>Create Role</h1>
        <div class="sub">Name the role and choose exactly what it can access.</div>
    </div>
    <a href="{{ route('roles.index') }}" class="btn btn-ghost">Back to roles</a>
</div>

<form method="POST" action="{{ route('roles.store') }}">
    @csrf
    <div class="card" style="max-width:820px; margin-bottom:18px;">
        <div class="card-header"><h2>Role details</h2></div>
        <div class="card-body">
            <div class="field">
                <label>Role Name <span class="req">*</span></label>
                <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Accountant, Sub Admin" required style="max-width:400px;">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2>Permissions</h2></div>
        <div class="card-body">
            @include('roles._matrix', ['selected' => old('permissions', [])])
            <div class="form-actions" style="margin-top:18px;">
                <button class="btn btn-primary" type="submit">Save Role</button>
                <a href="{{ route('roles.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
            <div class="form-legend"><span class="req">*</span> Required field</div>
        </div>
    </div>
</form>
@endsection
