@extends('layouts.admin')

@section('title', 'Add Expense Head')

@section('content')
<div class="page-header">
    <div>
        <h1>Add Expense Head</h1>
        <div class="sub">Create a category under which expenses can be recorded.</div>
    </div>
    <a href="{{ route('expense-heads.index') }}" class="btn btn-ghost">Back to list</a>
</div>

<div class="card" style="max-width:720px;">
    <div class="card-header"><h2>Expense head details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('expense-heads.create') }}">
            @csrf
            <div class="form-grid">
                <div class="field col-span">
                    <label>Head Name <span class="req">*</span></label>
                    <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Office Rent" required>
                </div>
                <div class="field col-span">
                    <label>Description</label>
                    <textarea class="textarea" rows="3" name="description" placeholder="Optional description">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Save Expense Head</button>
                <a href="{{ route('expense-heads.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
            <div class="form-legend"><span class="req">*</span> Required field</div>
        </form>
    </div>
</div>
@endsection
