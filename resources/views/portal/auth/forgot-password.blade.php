@extends('layouts.public')

@section('title', 'Forgot Password')

@section('content')
<section class="section">
    <div class="wrap" style="max-width:460px;">
        <div class="section-head" style="margin-bottom:20px;">
            <span class="eyebrow">Account Recovery</span>
            <h2>Forgot your password?</h2>
            <p>Enter your email and we'll send you a reset link.</p>
        </div>
        <div class="form-card">
            <form method="POST" action="{{ route('customer.password.email') }}">
                @csrf
                <div class="fld">
                    <label>Email <span class="req">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')<span style="color:var(--danger); font-size:0.82rem;">{{ $message }}</span>@enderror
                </div>
                <button class="btn btn-primary" type="submit" style="width:100%; justify-content:center;">Send Reset Link</button>
            </form>
            <p style="text-align:center; margin:18px 0 0; font-size:0.9rem; color:var(--muted);">
                <a href="{{ route('customer.login') }}" style="color:var(--primary); font-weight:600;">← Back to sign in</a>
            </p>
        </div>
    </div>
</section>
@endsection
