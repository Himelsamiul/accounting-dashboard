@extends('layouts.public')

@section('title', 'Request Tracking Code')

@section('head')
<style>
    .rc-hero { background: linear-gradient(180deg, var(--bg-alt), var(--bg)); padding: 44px 0 20px; }
    .rc-box { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 26px; box-shadow: var(--shadow); max-width: 560px; }
    .rc-box label { display: block; font-size: 0.86rem; font-weight: 600; margin-bottom: 7px; }
    .rc-box input, .rc-box textarea { width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--border); font-family: inherit; font-size: 0.98rem; outline: none; background: var(--surface); color: var(--text); margin-bottom: 16px; }
    .rc-box input:focus, .rc-box textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }
    .rc-box textarea { resize: vertical; min-height: 90px; }
    .rc-hint { font-size: 0.86rem; color: var(--muted); margin: -8px 0 18px; }
    .rc-note { background: var(--success-soft); color: var(--success); padding: 14px 16px; border-radius: 10px; font-size: 0.92rem; max-width: 560px; margin-bottom: 18px; }
</style>
@endsection

@section('content')
<section class="rc-hero">
    <div class="wrap">
        <div class="section-head" style="text-align:left; margin:0 0 20px; max-width:none;">
            <span class="eyebrow">Lost your code?</span>
            <h2>Request your tracking code</h2>
            <p>Forgot your project tracking code? Enter the email address used when your project was created, and our team will email your code to you.</p>
        </div>

        @if(session('status'))
            <div class="rc-note">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="rc-note" style="background:var(--danger-soft); color:var(--danger);">{{ $errors->first() }}</div>
        @endif

        <div class="rc-box">
            <form method="POST" action="{{ route('portal.request-code.submit') }}">
                @csrf
                <label>Project email address</label>
                <input type="email" name="email" value="{{ old('email', auth('customer')->user()->email ?? '') }}" placeholder="you@example.com" required>
                <div class="rc-hint">Use the email address the project was registered with.</div>
                <label>Note (optional)</label>
                <textarea name="note" placeholder="Anything you'd like our team to know…">{{ old('note') }}</textarea>
                <button class="btn btn-primary" type="submit" style="width:100%;">Send request</button>
            </form>
        </div>
    </div>
</section>
@endsection
