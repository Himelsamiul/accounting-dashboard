@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<h1>Forgot password?</h1>
<p class="lead">Enter your account email and we'll send you a reset link.</p>

@if (session('status'))
    <div class="alert">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="field">
        <label>Email address <span class="req">*</span></label>
        <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
        @error('email')<div class="error">{{ $message }}</div>@enderror
    </div>
    <button class="btn" type="submit">Send reset link</button>
</form>

<div class="foot"><a href="{{ route('login') }}">← Back to sign in</a></div>
@endsection
