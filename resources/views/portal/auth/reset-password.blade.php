@extends('layouts.public')

@section('title', 'Reset Password')

@section('content')
<section class="section">
    <div class="wrap" style="max-width:460px;">
        <div class="section-head" style="margin-bottom:20px;">
            <span class="eyebrow">Account Recovery</span>
            <h2>Reset your password</h2>
            <p>Choose a new password for your account.</p>
        </div>
        <div class="form-card">
            <form method="POST" action="{{ route('customer.password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="fld">
                    <label>Email <span class="req">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $email) }}" required readonly>
                    @error('email')<span style="color:var(--danger); font-size:0.82rem;">{{ $message }}</span>@enderror
                </div>
                <div class="fld">
                    <label>New Password <span class="req">*</span></label>
                    <div class="pw-wrap">
                        <input type="password" id="rp_pw" name="password" required autofocus>
                        <button type="button" class="pw-toggle" data-target="rp_pw" aria-label="Show password">
                            <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    @error('password')<span style="color:var(--danger); font-size:0.82rem;">{{ $message }}</span>@enderror
                </div>
                <div class="fld">
                    <label>Confirm Password <span class="req">*</span></label>
                    <div class="pw-wrap">
                        <input type="password" id="rp_pw2" name="password_confirmation" required>
                        <button type="button" class="pw-toggle" data-target="rp_pw2" aria-label="Show password">
                            <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit" style="width:100%; justify-content:center;">Reset Password</button>
            </form>
        </div>
    </div>
</section>
@endsection
