@extends('layouts.admin')

@section('title', 'Edit Expense')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Expense</h1>
        <div class="sub">Update {{ $expense->title }}.</div>
    </div>
    <a href="{{ route('expenses.index') }}" class="btn btn-ghost">Back to list</a>
</div>

<div class="card" style="max-width:760px;">
    <div class="card-header"><h2>Expense details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('expenses.edit', $expense->id) }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label>Expense Head <span class="req">*</span></label>
                    <select class="select" name="expense_head_id" required>
                        <option value="">Select head…</option>
                        @foreach($heads as $head)
                            <option value="{{ $head->id }}" {{ (string) old('expense_head_id', $expense->expense_head_id) === (string) $head->id ? 'selected' : '' }}>{{ $head->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Title <span class="req">*</span></label>
                    <input class="input" type="text" name="title" value="{{ old('title', $expense->title) }}" required>
                </div>
                <div class="field">
                    <label>Amount (৳) <span class="req">*</span></label>
                    <input class="input" type="number" step="0.01" min="0" name="amount" value="{{ old('amount', $expense->amount) }}" required>
                </div>
                <div class="field">
                    <label>Date</label>
                    <input class="input" type="date" name="expense_date" value="{{ old('expense_date', optional($expense->expense_date)->format('Y-m-d')) }}">
                </div>
                <div class="field">
                    <label>Payment Method</label>
                    <input class="input" type="text" name="payment_method" value="{{ old('payment_method', $expense->payment_method) }}">
                </div>
                <div class="field">
                    <label>Bank</label>
                    <select class="select" name="bank_id">
                        <option value="">— None (cash) —</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ (string) old('bank_id', $expense->bank_id) === (string) $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field col-span">
                    <label>Note</label>
                    <textarea class="textarea" rows="3" name="note">{{ old('note', $expense->note) }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Update Expense</button>
                <a href="{{ route('expenses.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
            <div class="form-legend"><span class="req">*</span> Required field</div>
        </form>
    </div>
</div>
@endsection
