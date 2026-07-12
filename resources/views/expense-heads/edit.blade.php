@extends('layouts.admin')

@section('title', 'Edit Expense Head')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Expense Head</h1>
        <div class="sub">Update {{ $head->name }}.</div>
    </div>
    <a href="{{ route('expense-heads.index') }}" class="btn btn-ghost">Back to list</a>
</div>

<div class="card" style="max-width:720px;">
    <div class="card-header"><h2>Expense head details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('expense-heads.edit', $head->id) }}">
            @csrf
            <div class="form-grid">
                <div class="field col-span">
                    <label>Head Name <span class="req">*</span></label>
                    <input class="input" type="text" name="name" value="{{ old('name', $head->name) }}" required>
                </div>
                <div class="field col-span">
                    <label>Description</label>
                    <textarea class="textarea" rows="3" name="description">{{ old('description', $head->description) }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Update Expense Head</button>
                <a href="{{ route('expense-heads.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
            <div class="form-legend"><span class="req">*</span> Required field</div>
        </form>
    </div>
</div>
@endsection
