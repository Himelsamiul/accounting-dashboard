@extends('layouts.public')

@section('title', 'Verify Email')

@section('head')
<style>
    .otp-input { letter-spacing: 14px; text-align: center; font-size: 1.5rem; font-weight: 700; }
    .countdown { font-variant-numeric: tabular-nums; font-weight: 700; }
    .otp-expired { color: var(--danger); }
</style>
@endsection

@section('content')
<section class="section">
    <div class="wrap" style="max-width:460px;">
        <div class="section-head" style="margin-bottom:20px;">
            <span class="eyebrow">Email Verification</span>
            <h2>Enter your code</h2>
            <p>We sent a 6-digit code to <strong>{{ $email }}</strong>. It expires in 2 minutes.</p>
        </div>
        <div class="form-card">
            <form method="POST" action="{{ route('customer.otp.verify') }}">
                @csrf
                <div class="fld">
                    <label>Verification Code <span class="req">*</span></label>
                    <input class="otp-input" type="text" name="otp" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" placeholder="------" required autofocus autocomplete="one-time-code">
                    @error('otp')<span style="color:var(--danger); font-size:0.82rem;">{{ $message }}</span>@enderror
                </div>
                <div style="text-align:center; margin-bottom:16px; font-size:0.9rem; color:var(--muted);">
                    <span id="cdText">Code expires in <span class="countdown" id="cd">2:00</span></span>
                </div>
                <button class="btn btn-primary" type="submit" style="width:100%; justify-content:center;">Verify &amp; Continue</button>
            </form>
            <form method="POST" action="{{ route('customer.otp.resend') }}" style="margin-top:14px; text-align:center;">
                @csrf
                <span style="font-size:0.88rem; color:var(--muted);">Didn't get the code?</span>
                <button type="submit" id="resendBtn" style="background:none; border:none; color:var(--primary); font-weight:700; cursor:pointer; font-family:inherit; font-size:0.88rem;">Resend code</button>
            </form>
        </div>
        <p style="text-align:center; margin:18px 0 0; font-size:0.9rem; color:var(--muted);">
            Wrong email? <a href="{{ route('customer.register') }}" style="color:var(--primary); font-weight:600;">Register again</a>
        </p>
    </div>
</section>
@endsection

@section('scripts')
<script>
    (function () {
        var expiresAt = @json($expiresAt);
        var cd = document.getElementById('cd');
        var cdText = document.getElementById('cdText');
        if (!expiresAt || !cd) return;
        var end = new Date(expiresAt).getTime();

        function tick() {
            var diff = Math.max(0, Math.floor((end - Date.now()) / 1000));
            var m = Math.floor(diff / 60), s = diff % 60;
            cd.textContent = m + ':' + (s < 10 ? '0' : '') + s;
            if (diff <= 0) {
                cdText.innerHTML = '<span class="otp-expired">Code expired — please resend a new one.</span>';
                clearInterval(t);
            }
        }
        tick();
        var t = setInterval(tick, 1000);
    })();
</script>
@endsection
