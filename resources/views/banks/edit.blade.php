@extends('layouts.admin')

@section('title', 'Edit Bank')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Bank</h1>
        <div class="sub">Update {{ $bank->name }}.</div>
    </div>
    <a href="{{ route('banks.index') }}" class="btn btn-ghost">Back to list</a>
</div>

<div class="card" style="max-width:720px;">
    <div class="card-header"><h2>Bank details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('banks.edit', $bank->id) }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label>Bank Name</label>
                    <input class="input" type="text" name="name" value="{{ old('name', $bank->name) }}" required>
                </div>
                <div class="field">
                    <label>Account Number</label>
                    <input class="input" type="text" name="account_number" value="{{ old('account_number', $bank->account_number) }}">
                </div>
                <div class="field">
                    <label>Branch</label>
                    <input class="input" type="text" name="branch" value="{{ old('branch', $bank->branch) }}">
                </div>
                <div class="field col-span">
                    <label>Notes</label>
                    <textarea class="textarea" rows="3" name="notes">{{ old('notes', $bank->notes) }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Update Bank</button>
                <a href="{{ route('banks.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
