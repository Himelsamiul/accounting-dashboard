@extends('layouts.admin')

@section('title', 'Add Bank')

@section('content')
<div class="page-header">
    <div>
        <h1>Add Bank</h1>
        <div class="sub">Add a bank account or payment channel for invoices and transactions.</div>
    </div>
    <a href="{{ route('banks.index') }}" class="btn btn-ghost">Back to list</a>
</div>

<div class="card" style="max-width:720px;">
    <div class="card-header"><h2>Bank details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('banks.create') }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label>Bank Name <span class="req">*</span></label>
                    <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. DBBL" required>
                </div>
                <div class="field">
                    <label>Account Number</label>
                    <input class="input" type="text" name="account_number" value="{{ old('account_number') }}" placeholder="123456789">
                </div>
                <div class="field">
                    <label>Branch</label>
                    <input class="input" type="text" name="branch" value="{{ old('branch') }}" placeholder="Dhanmondi">
                </div>
                <div class="field col-span">
                    <label>Notes</label>
                    <textarea class="textarea" rows="3" name="notes" placeholder="Optional description">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Save Bank</button>
                <a href="{{ route('banks.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
            <div class="form-legend"><span class="req">*</span> Required field</div>
        </form>
    </div>
</div>
@endsection
