@extends('layouts.admin')

@section('title', 'Add Team Member')

@section('content')
<div class="page-header">
    <div>
        <h1>Add Team Member</h1>
        <div class="sub">Create a team member to assign to projects and track payments.</div>
    </div>
    <a href="{{ route('team.members.index') }}" class="btn btn-ghost">Back to list</a>
</div>

<div class="card" style="max-width:820px;">
    <div class="card-header"><h2>Member details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('team.members.create') }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label>Name <span class="req">*</span></label>
                    <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Rahim Uddin" required>
                </div>
                <div class="field">
                    <label>Role / Designation</label>
                    <input class="input" type="text" name="role" value="{{ old('role') }}" placeholder="e.g. Developer, Designer">
                </div>
                <div class="field">
                    <label>Phone</label>
                    @include('partials.phone-input', ['value' => old('phone')])
                </div>
                <div class="field">
                    <label>Status</label>
                    <select class="select" name="is_active">
                        <option value="1" @selected(old('is_active', '1') === '1')>Active</option>
                        <option value="0" @selected(old('is_active') === '0')>Inactive</option>
                    </select>
                </div>
                <div class="field col-span">
                    <label>Notes</label>
                    <textarea class="textarea" rows="3" name="notes" placeholder="Any note about this member">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Save Member</button>
                <a href="{{ route('team.members.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
            <div class="form-legend"><span class="req">*</span> Required field</div>
        </form>
    </div>
</div>
@endsection
