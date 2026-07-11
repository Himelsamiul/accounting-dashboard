@extends('layouts.public')

@section('title', 'Write a Review')

@section('head')
<style>
    .stars-input { display: inline-flex; gap: 6px; font-size: 2.1rem; cursor: pointer; }
    .stars-input span { color: #d1d5db; transition: color .1s; }
    .stars-input span.on { color: #f59e0b; }
    :root[data-theme="dark"] .stars-input span { color: #374151; }
    :root[data-theme="dark"] .stars-input span.on { color: #fbbf24; }
</style>
@endsection

@section('content')
<section class="section">
    <div class="wrap" style="max-width:560px;">
        <div class="section-head" style="margin-bottom:24px;">
            <span class="eyebrow">Your feedback</span>
            <h2>{{ $existing ? 'Update your review' : 'Write a review' }}</h2>
            <p>Tell us and other clients about your experience.</p>
        </div>
        <div class="form-card">
            <form method="POST" action="{{ route('portal.review.submit') }}">
                @csrf
                <div class="fld">
                    <label>Your Rating <span class="req">*</span></label>
                    <div class="stars-input" id="starInput" role="radiogroup" aria-label="Rating">
                        <span data-v="1">★</span><span data-v="2">★</span><span data-v="3">★</span><span data-v="4">★</span><span data-v="5">★</span>
                    </div>
                    <input type="hidden" name="rating" id="ratingVal" value="{{ old('rating', $existing->rating ?? 5) }}">
                    @error('rating')<span style="color:var(--danger); font-size:0.82rem;">{{ $message }}</span>@enderror
                </div>
                <div class="fld">
                    <label>Your Role / Company <span style="color:var(--muted); font-weight:400;">(optional)</span></label>
                    <input type="text" name="role" value="{{ old('role', $existing->role ?? '') }}" placeholder="e.g. Founder, Acme Ltd.">
                </div>
                <div class="fld">
                    <label>Your Review <span class="req">*</span></label>
                    <textarea name="comment" rows="5" required placeholder="Share your experience...">{{ old('comment', $existing->comment ?? '') }}</textarea>
                    @error('comment')<span style="color:var(--danger); font-size:0.82rem;">{{ $message }}</span>@enderror
                </div>
                <button class="btn btn-primary" type="submit" style="width:100%; justify-content:center;">{{ $existing ? 'Update Review' : 'Submit Review' }}</button>
                <div class="form-legend"><span class="req">*</span> Required field</div>
            </form>
        </div>
        <p style="text-align:center; margin:18px 0 0; font-size:0.9rem; color:var(--muted);">
            <a href="{{ route('public.home') }}" style="color:var(--primary); font-weight:600;">← Back to home</a>
        </p>
    </div>
</section>
@endsection

@section('scripts')
<script>
    (function () {
        var wrap = document.getElementById('starInput');
        var input = document.getElementById('ratingVal');
        if (!wrap || !input) return;
        var stars = Array.prototype.slice.call(wrap.querySelectorAll('span'));
        function paint(v) { stars.forEach(function (s) { s.classList.toggle('on', Number(s.getAttribute('data-v')) <= v); }); }
        stars.forEach(function (s) {
            s.addEventListener('click', function () { input.value = s.getAttribute('data-v'); paint(Number(input.value)); });
            s.addEventListener('mouseenter', function () { paint(Number(s.getAttribute('data-v'))); });
        });
        wrap.addEventListener('mouseleave', function () { paint(Number(input.value)); });
        paint(Number(input.value) || 5);
    })();
</script>
@endsection
