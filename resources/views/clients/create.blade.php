@extends('layouts.admin')

@section('title', 'Add Client')

@section('content')
<div class="page-header">
    <div>
        <h1>Add Client</h1>
        <div class="sub">Create a client profile to assign projects and generate invoices.</div>
    </div>
    <a href="{{ route('clients.index') }}" class="btn btn-ghost">Back to list</a>
</div>

<div class="card" style="max-width:820px;">
    <div class="card-header"><h2>Client details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('clients.create') }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label>Client Name</label>
                    <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. GreenTech Ltd." required>
                </div>
                <div class="field">
                    <label>Company</label>
                    <input class="input" type="text" name="company" value="{{ old('company') }}" placeholder="e.g. Prime Byte Soft">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="client@example.com" required>
                </div>
                <div class="field">
                    <label>Phone</label>
                    <input class="input" type="text" name="phone" value="{{ old('phone') }}" placeholder="+8801XXXXXXXXX">
                </div>
                <div class="field col-span">
                    <label>Address</label>
                    <textarea class="textarea" rows="3" name="address" placeholder="Client address and contact info">{{ old('address') }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Save Client</button>
                <a href="{{ route('clients.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
