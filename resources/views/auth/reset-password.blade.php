@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<h1>Reset password</h1>
<p class="lead">Choose a new password for your account.</p>

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="field">
        <label>Email address <span class="req">*</span></label>
        <input class="input" type="email" name="email" value="{{ old('email', $email) }}" required readonly>
        @error('email')<div class="error">{{ $message }}</div>@enderror
    </div>
    <div class="field">
        <label>New password <span class="req">*</span></label>
        <div class="input-wrap">
            <input class="input has-toggle" type="password" id="password" name="password" placeholder="At least 8 characters" required autofocus>
            <button type="button" class="pw-toggle" data-target="password" aria-label="Show password">
                <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
        </div>
        @error('password')<div class="error">{{ $message }}</div>@enderror
    </div>
    <div class="field">
        <label>Confirm password <span class="req">*</span></label>
        <div class="input-wrap">
            <input class="input has-toggle" type="password" id="password_confirmation" name="password_confirmation" placeholder="Re-enter password" required>
            <button type="button" class="pw-toggle" data-target="password_confirmation" aria-label="Show password">
                <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
        </div>
    </div>
    <button class="btn" type="submit">Reset password</button>
</form>

<div class="foot"><a href="{{ route('login') }}">← Back to sign in</a></div>
@endsection
