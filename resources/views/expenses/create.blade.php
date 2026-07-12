@extends('layouts.admin')

@section('title', 'Add Expense')

@section('content')
<div class="page-header">
    <div>
        <h1>Add Expense</h1>
        <div class="sub">Record an expense under an expense head.</div>
    </div>
    <a href="{{ route('expenses.index') }}" class="btn btn-ghost">Back to list</a>
</div>

@if($heads->isEmpty())
<div class="card" style="max-width:760px;">
    <div class="card-body">
        <div class="empty-state">
            <div>You need at least one expense head before adding an expense.</div>
            <a href="{{ route('expense-heads.create') }}" class="btn btn-primary" style="margin-top:12px;">Create an Expense Head</a>
        </div>
    </div>
</div>
@else
<div class="card" style="max-width:760px;">
    <div class="card-header"><h2>Expense details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('expenses.create') }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label>Expense Head <span class="req">*</span></label>
                    <select class="select" name="expense_head_id" required>
                        <option value="">Select head…</option>
                        @foreach($heads as $head)
                            <option value="{{ $head->id }}" {{ (string) old('expense_head_id') === (string) $head->id ? 'selected' : '' }}>{{ $head->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Title <span class="req">*</span></label>
                    <input class="input" type="text" name="title" value="{{ old('title') }}" placeholder="e.g. July rent" required>
                </div>
                <div class="field">
                    <label>Amount (৳) <span class="req">*</span></label>
                    <input class="input" type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}" placeholder="0.00" required>
                </div>
                <div class="field">
                    <label>Date</label>
                    <input class="input" type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}">
                </div>
                <div class="field">
                    <label>Payment Method</label>
                    <input class="input" type="text" name="payment_method" value="{{ old('payment_method') }}" placeholder="e.g. Cash, bKash, Bank">
                </div>
                <div class="field">
                    <label>Bank</label>
                    <select class="select" name="bank_id">
                        <option value="">— None (cash) —</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ (string) old('bank_id') === (string) $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field col-span">
                    <label>Note</label>
                    <textarea class="textarea" rows="3" name="note" placeholder="Optional note">{{ old('note') }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Save Expense</button>
                <a href="{{ route('expenses.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
            <div class="form-legend"><span class="req">*</span> Required field</div>
        </form>
    </div>
</div>
@endif
@endsection
