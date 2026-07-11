@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="page-header">
    <div>
        <h1>Settings</h1>
        <div class="sub">Manage your profile, security, and project preferences.</div>
    </div>
</div>

<div class="settings-tabs" id="settingsTabs">
    <button class="stab active" data-tab="profile">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Profile
    </button>
    <button class="stab" data-tab="security">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Password
    </button>
    @if($canEditSettings)
    <button class="stab" data-tab="project">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
        Project Settings
    </button>
    <button class="stab" data-tab="company">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/></svg>
        Company
    </button>
    @endif
</div>

{{-- PROFILE --}}
<section class="stab-panel active" data-panel="profile">
    <div class="card">
        <div class="card-header">
            <div>
                <h2>Profile details</h2>
                <div class="sub">Your account information.</div>
            </div>
        </div>
        <div class="card-body">
            <div class="profile-head">
                <div class="profile-avatar">{{ strtoupper(mb_substr($user->name ?? 'U', 0, 1)) }}</div>
                <div>
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-role">{{ $user->isSuperAdmin() ? 'Super Admin' : ($user->role->name ?? 'No role assigned') }}</div>
                    <div class="profile-email">{{ $user->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('settings.account') }}">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label>Full name <span class="req">*</span></label>
                        <input class="input" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="field">
                        <label>Email address <span class="req">*</span></label>
                        <input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Save profile</button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- PASSWORD --}}
<section class="stab-panel" data-panel="security">
    <div class="card">
        <div class="card-header">
            <div>
                <h2>Change password</h2>
                <div class="sub">Use a strong password of at least 8 characters.</div>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('settings.password') }}" style="max-width:520px;">
                @csrf
                <div class="field">
                    <label>Current password <span class="req">*</span></label>
                    <input class="input" type="password" name="current_password" autocomplete="current-password" required>
                </div>
                <div class="field">
                    <label>New password <span class="req">*</span></label>
                    <input class="input" type="password" name="password" autocomplete="new-password" required>
                </div>
                <div class="field">
                    <label>Confirm new password <span class="req">*</span></label>
                    <input class="input" type="password" name="password_confirmation" autocomplete="new-password" required>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Update password</button>
                </div>
            </form>
        </div>
    </div>
</section>

@if($canEditSettings)
{{-- PROJECT SETTINGS --}}
<section class="stab-panel" data-panel="project">
    <form method="POST" action="{{ route('settings.company') }}">
        @csrf
        {{-- Company fields travel with this form too so nothing is wiped on save. --}}
        <input type="hidden" name="company_name" value="{{ $company['company_name'] }}">
        <input type="hidden" name="company_tagline" value="{{ $company['company_tagline'] }}">
        <input type="hidden" name="company_email" value="{{ $company['company_email'] }}">
        <input type="hidden" name="company_phone" value="{{ $company['company_phone'] }}">
        <input type="hidden" name="company_address" value="{{ $company['company_address'] }}">
        <input type="hidden" name="currency_symbol" value="{{ $company['currency_symbol'] }}">

        <div class="card">
            <div class="card-header">
                <div>
                    <h2>Project settings</h2>
                    <div class="sub">Defaults applied when creating projects.</div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="field">
                        <label>Project code prefix</label>
                        <input class="input" type="text" name="project_code_prefix" value="{{ old('project_code_prefix', $project['project_code_prefix']) }}" placeholder="PRJ" maxlength="12">
                        <span class="hint">New tracking codes look like <strong>{{ $project['project_code_prefix'] }}-AB12CD34</strong>. Letters &amp; numbers only.</span>
                    </div>
                    <div class="field">
                        <label>Default project value</label>
                        <input class="input" type="number" step="0.01" min="0" name="default_project_value" value="{{ old('default_project_value', $project['default_project_value']) }}" placeholder="e.g. 200000">
                        <span class="hint">Pre-fills the value field on the new project form (optional).</span>
                    </div>
                    <div class="field col-span">
                        <label>Project types</label>
                        <textarea class="textarea" name="project_types" rows="6" placeholder="One type per line">{{ old('project_types', $project['project_types']) }}</textarea>
                        <span class="hint">One per line. These appear as quick suggestions in the project “Type” field.</span>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Save project settings</button>
                </div>
            </div>
        </div>
    </form>
</section>

{{-- COMPANY --}}
<section class="stab-panel" data-panel="company">
    <form method="POST" action="{{ route('settings.company') }}">
        @csrf
        {{-- Project fields travel with this form too so nothing is wiped on save. --}}
        <input type="hidden" name="project_code_prefix" value="{{ $project['project_code_prefix'] }}">
        <input type="hidden" name="project_types" value="{{ $project['project_types'] }}">
        <input type="hidden" name="default_project_value" value="{{ $project['default_project_value'] }}">

        <div class="card">
            <div class="card-header">
                <div>
                    <h2>Company information</h2>
                    <div class="sub">Shown on invoices, summaries and printed documents.</div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="field">
                        <label>Company name</label>
                        <input class="input" type="text" name="company_name" value="{{ old('company_name', $company['company_name']) }}">
                    </div>
                    <div class="field">
                        <label>Tagline</label>
                        <input class="input" type="text" name="company_tagline" value="{{ old('company_tagline', $company['company_tagline']) }}">
                    </div>
                    <div class="field">
                        <label>Email</label>
                        <input class="input" type="email" name="company_email" value="{{ old('company_email', $company['company_email']) }}">
                    </div>
                    <div class="field">
                        <label>Phone</label>
                        <input class="input" type="text" name="company_phone" value="{{ old('company_phone', $company['company_phone']) }}">
                    </div>
                    <div class="field">
                        <label>Currency symbol</label>
                        <input class="input" type="text" name="currency_symbol" value="{{ old('currency_symbol', $company['currency_symbol']) }}" maxlength="8">
                    </div>
                    <div class="field col-span">
                        <label>Address</label>
                        <textarea class="textarea" name="company_address" rows="3">{{ old('company_address', $company['company_address']) }}</textarea>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Save company info</button>
                </div>
            </div>
        </div>
    </form>
</section>
@endif
@endsection

@section('scripts')
<style>
    .settings-tabs { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 20px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 6px; box-shadow: var(--shadow-sm); }
    .stab { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: var(--radius-sm); border: none; background: transparent; color: var(--muted); font-family: inherit; font-size: 0.88rem; font-weight: 600; cursor: pointer; transition: background .15s, color .15s; }
    .stab svg { width: 17px; height: 17px; }
    .stab:hover { background: var(--surface-3); color: var(--text); }
    .stab.active { background: var(--primary); color: #fff; }
    .stab-panel { display: none; }
    .stab-panel.active { display: block; animation: fadeIn .2s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: none; } }
    .profile-head { display: flex; align-items: center; gap: 18px; padding-bottom: 20px; margin-bottom: 20px; border-bottom: 1px solid var(--border); }
    .profile-avatar { width: 64px; height: 64px; border-radius: 16px; background: linear-gradient(135deg, var(--primary), #8b5cf6); color: #fff; display: grid; place-items: center; font-weight: 800; font-size: 1.6rem; flex-shrink: 0; }
    .profile-name { font-size: 1.15rem; font-weight: 700; }
    .profile-role { font-size: 0.82rem; color: var(--primary); font-weight: 600; margin-top: 2px; }
    .profile-email { font-size: 0.85rem; color: var(--muted); margin-top: 2px; }
    @media (max-width: 560px) { .settings-tabs { flex-direction: column; } .stab { justify-content: flex-start; } }
</style>
<script>
    (function () {
        var tabs = document.querySelectorAll('#settingsTabs .stab');
        var panels = document.querySelectorAll('.stab-panel');
        function activate(name) {
            tabs.forEach(function (t) { t.classList.toggle('active', t.getAttribute('data-tab') === name); });
            panels.forEach(function (p) { p.classList.toggle('active', p.getAttribute('data-panel') === name); });
            try { history.replaceState(null, '', '#' + name); } catch (e) {}
        }
        tabs.forEach(function (t) { t.addEventListener('click', function () { activate(this.getAttribute('data-tab')); }); });
        var hash = (location.hash || '').replace('#', '');
        if (hash && document.querySelector('.stab[data-tab="' + hash + '"]')) activate(hash);
    })();
</script>
@endsection
