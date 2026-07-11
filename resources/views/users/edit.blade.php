@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit User</h1>
        <div class="sub">Update {{ $user->name }}'s account.</div>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-ghost">Back to users</a>
</div>

<div class="card" style="max-width:820px;">
    <div class="card-header"><h2>Account details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="field">
                    <label>User Name <span class="req">*</span></label>
                    <input class="input" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="field">
                    <label>Email <span class="req">*</span></label>
                    <input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="field">
                    <label>New Password</label>
                    <input class="input" type="text" name="password" placeholder="Leave blank to keep current">
                    <span class="hint">Only fill this to change the password.</span>
                </div>
                <div class="field">
                    <label>Role <span class="req">*</span></label>
                    <select class="select" name="role_id" required>
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field col-span">
                    <label>Designation</label>
                    <input class="input" type="text" name="designation" value="{{ old('designation', $user->designation) }}" placeholder="e.g. Junior Accountant">
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Update User</button>
                <a href="{{ route('users.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
            <div class="form-legend"><span class="req">*</span> Required field</div>
        </form>
    </div>
</div>
@endsection
