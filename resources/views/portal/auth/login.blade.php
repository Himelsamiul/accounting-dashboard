@extends('layouts.public')

@section('title', 'Login')

@section('content')
<section class="section">
    <div class="wrap" style="max-width:480px;">
        <div class="section-head" style="margin-bottom:24px;">
            <span class="eyebrow">Client Account</span>
            <h2>Welcome back</h2>
            <p>Sign in to track your project.</p>
        </div>
        <div class="form-card">
            <form method="POST" action="{{ route('customer.login') }}">
                @csrf
                <div class="fld">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')<span style="color:var(--danger); font-size:0.82rem;">{{ $message }}</span>@enderror
                </div>
                <div class="fld">
                    <label>Password</label>
                    <div class="pw-wrap">
                        <input type="password" id="login_pw" name="password" required>
                        <button type="button" class="pw-toggle" data-target="login_pw" aria-label="Show password">
                            <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>
                <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:16px;">
                    <label style="display:flex; align-items:center; gap:8px; font-size:0.88rem; color:var(--muted);">
                        <input type="checkbox" name="remember" value="1"> Keep me signed in
                    </label>
                    <a href="{{ route('customer.password.request') }}" style="color:var(--primary); font-weight:600; font-size:0.88rem;">Forgot password?</a>
                </div>
                <button class="btn btn-primary" type="submit" style="width:100%; justify-content:center;">Sign In</button>
            </form>
            <p style="text-align:center; margin:18px 0 0; font-size:0.9rem; color:var(--muted);">
                New client? <a href="{{ route('customer.register') }}" style="color:var(--primary); font-weight:600;">Create an account</a>
            </p>
        </div>
    </div>
</section>
@endsection
