@extends('layouts.admin')

@section('title', 'Edit Client')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Client</h1>
        <div class="sub">Update the profile for {{ $client->name }}.</div>
    </div>
    <a href="{{ route('clients.index') }}" class="btn btn-ghost">Back to list</a>
</div>

<div class="card" style="max-width:820px;">
    <div class="card-header"><h2>Client details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('clients.edit', $client->id) }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label>Client Name <span class="req">*</span></label>
                    <input class="input" type="text" name="name" value="{{ old('name', $client->name) }}" required>
                </div>
                <div class="field">
                    <label>Company</label>
                    <input class="input" type="text" name="company" value="{{ old('company', $client->company) }}">
                </div>
                <div class="field">
                    <label>Email <span class="req">*</span></label>
                    <input class="input" type="email" name="email" value="{{ old('email', $client->email) }}" required>
                </div>
                <div class="field">
                    <label>Phone <span class="req">*</span></label>
                    @include('partials.phone-input', ['value' => old('phone', $client->phone)])
                </div>
                <div class="field col-span">
                    <label>Address</label>
                    <textarea class="textarea" rows="3" name="address">{{ old('address', $client->address) }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Update Client</button>
                <a href="{{ route('clients.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
            <div class="form-legend"><span class="req">*</span> Required field</div>
        </form>
    </div>
</div>
@endsection
