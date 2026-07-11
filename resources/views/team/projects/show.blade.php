@extends('layouts.admin')

@section('title', 'Manage Project Team')

@section('content')
@php
    $canEdit = auth()->user()->hasPermission('team', 'edit');
    $canDelete = auth()->user()->hasPermission('team', 'delete');
    $value = (float) $project->project_value;
    $memberCount = $project->teamMembers->count();
    $assignedIds = $project->teamMembers->pluck('id')->all();
    $today = now()->format('Y-m-d');
    $methods = ['Cash', 'Bank', 'bKash', 'Nagad', 'Rocket', 'Other'];
@endphp

<div class="page-header">
    <div>
        <h1>{{ $project->name }}</h1>
        <div class="sub">{{ $project->client->name ?? 'Unassigned client' }} · Team distribution &amp; payments</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('team.projects.summary', $project->id) }}" target="_blank" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Payment Summary
        </a>
        <a href="{{ route('team.projects.index') }}" class="btn btn-ghost">Back to list</a>
    </div>
</div>

<div class="stat-grid" style="margin-bottom:20px;">
    <div class="stat-card" style="border-left:3px solid var(--primary);">
        <div class="stat-label">Project Value</div>
        <div class="stat-value">৳{{ number_format($value, 2) }}</div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--success);">
        <div class="stat-label">Collected from Client</div>
        <div class="stat-value">৳{{ number_format($collected, 2) }}</div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--warning);">
        <div class="stat-label">Paid to Team</div>
        <div class="stat-value">৳{{ number_format($paidToTeam, 2) }}</div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--primary-600);">
        <div class="stat-label">Available to Distribute</div>
        <div class="stat-value">৳{{ number_format(max(0, $available), 2) }}</div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--muted);">
        <div class="stat-label">Members · Share each</div>
        <div class="stat-value">{{ $memberCount }} · ৳{{ number_format($share, 2) }}</div>
    </div>
</div>

{{-- Assign members --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h2>Assigned members</h2></div>
    <div class="card-body">
        @if($canEdit)
        <form method="POST" action="{{ route('team.projects.assign', $project->id) }}">
            @csrf
            @if($allMembers->isEmpty())
                <p class="sub">No team members exist yet. <a href="{{ route('team.members.create') }}">Add a member</a> first.</p>
            @else
                <div class="form-grid" style="grid-template-columns:repeat(auto-fill,minmax(200px,1fr));">
                    @foreach($allMembers as $m)
                        <label class="check-chip">
                            <input type="checkbox" name="member_ids[]" value="{{ $m->id }}" @checked(in_array($m->id, $assignedIds))>
                            <span>{{ $m->name }}@if($m->role)<span class="sub"> · {{ $m->role }}</span>@endif</span>
                        </label>
                    @endforeach
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Save Assignment</button>
                </div>
                <div class="form-legend">The project value is split equally among the selected members.</div>
            @endif
        </form>
        @else
            @forelse($project->teamMembers as $m)
                <span class="badge badge-neutral">{{ $m->name }}</span>
            @empty
                <p class="sub">No members assigned.</p>
            @endforelse
        @endif
    </div>
</div>

{{-- Client collection — pulled from invoices for this project --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <h2>Client collection (from invoices)</h2>
        @if(auth()->user()->canView('invoices'))
            <a href="{{ route('invoices.create') }}" class="btn btn-ghost btn-sm">+ New Invoice</a>
        @endif
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th style="text-align:right;">Invoice Amount</th>
                    <th style="text-align:right;">Paid by Client</th>
                </tr>
            </thead>
            <tbody>
                @forelse($project->invoices->sortByDesc('invoice_date') as $inv)
                    <tr>
                        <td class="strong">{{ $inv->invoice_number }}</td>
                        <td>{{ $inv->invoice_date ? \Illuminate\Support\Carbon::parse($inv->invoice_date)->format('d M Y') : '—' }}</td>
                        <td><span class="badge {{ $inv->status === 'Paid' ? 'badge-success' : ($inv->status === 'Partial' ? 'badge-warning' : 'badge-neutral') }}">{{ $inv->status }}</span></td>
                        <td style="text-align:right;">৳{{ number_format((float) $inv->amount, 2) }}</td>
                        <td style="text-align:right;">৳{{ number_format((float) $inv->paid_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">
                        <div class="empty-state"><div>No invoices for this project yet. Create an invoice to record what the client has paid — you can only distribute money the client has paid.</div></div>
                    </td></tr>
                @endforelse
            </tbody>
            @if($project->invoices->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right;" class="strong">Total collected from client</td>
                    <td style="text-align:right;" class="strong">৳{{ number_format($collected, 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- Per-member payment breakdown --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h2>Payment status per member</h2></div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Member</th>
                    <th style="text-align:right;">Will get (share)</th>
                    <th style="text-align:right;">Paid</th>
                    <th style="text-align:right;">Remaining</th>
                    <th style="text-align:right;">Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($breakdown as $row)
                    @php $pc = $project->teamPayments->where('team_member_id', $row['member']->id)->count(); @endphp
                    <tr>
                        <td class="strong">{{ $row['member']->name }}</td>
                        <td style="text-align:right;">৳{{ number_format($row['share'], 2) }}</td>
                        <td style="text-align:right;">৳{{ number_format($row['paid'], 2) }}</td>
                        <td style="text-align:right;">
                            @if($row['remaining'] <= 0.009)
                                <span class="badge badge-success">Cleared</span>
                            @else
                                <span class="badge badge-warning">৳{{ number_format($row['remaining'], 2) }}</span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            <button type="button" class="btn btn-ghost btn-sm" onclick="openModal('member-modal-{{ $row['member']->id }}')">
                                View{{ $pc ? ' ('.$pc.')' : '' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">
                        <div class="empty-state"><div>No members assigned yet. Assign members above to start tracking payments.</div></div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Record payments to the team (multi-row) --}}
@if($canEdit && $memberCount > 0)
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h2>Record a payment to the team</h2></div>
    <div class="card-body">
        @if($available <= 0.009)
            <p class="sub" style="color:var(--warning);">No money available to distribute. Record a client invoice payment first — you can only pay the team from what the client has paid.</p>
        @else
            <p class="sub" style="margin-bottom:14px;">You can distribute up to <strong>৳{{ number_format($available, 2) }}</strong> right now (based on what the client has paid). Use <strong>+</strong> to pay several members at once.</p>
        @endif
        <form method="POST" action="{{ route('team.payments.store', $project->id) }}" id="payForm">
            @csrf
            <div id="payRows">
                <div class="pay-row" data-index="0">
                    <div class="field">
                        <label>Member</label>
                        <select class="select" name="rows[0][team_member_id]">
                            <option value="">Select member…</option>
                            @foreach($project->teamMembers as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Amount</label>
                        <input class="input" type="number" step="0.01" min="0" name="rows[0][amount]" placeholder="Amount">
                    </div>
                    <div class="field">
                        <label>Method</label>
                        <select class="select" name="rows[0][method]">
                            @foreach($methods as $method)
                                <option value="{{ $method }}">{{ $method }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Bank / Where</label>
                        <select class="select" name="rows[0][bank_id]">
                            <option value="">— none —</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Date</label>
                        <input class="input" type="date" name="rows[0][paid_on]" value="{{ $today }}">
                    </div>
                    <div class="field">
                        <label>Note</label>
                        <input class="input" type="text" name="rows[0][note]" placeholder="Optional">
                    </div>
                    <div class="pay-row-actions">
                        <button type="button" class="icon-btn add-row" title="Add another member" onclick="addPayRow()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                        <button type="button" class="icon-btn remove-row" title="Remove" onclick="removePayRow(this)" style="display:none;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Record Payment(s)</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Team payment history with filters --}}
<div class="card">
    <div class="card-header"><h2>Team payment history</h2></div>
    <div class="card-body" style="padding-bottom:0;">
        <div class="filter-bar">
            <div class="search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="histSearch" placeholder="Search notes…" oninput="filterHistory()">
            </div>
            <select class="select" id="filterMember" onchange="filterHistory()" style="max-width:200px;">
                <option value="">All members</option>
                @foreach($project->teamMembers as $m)
                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                @endforeach
            </select>
            <select class="select" id="filterMethod" onchange="filterHistory()" style="max-width:160px;">
                <option value="">All methods</option>
                @foreach($methods as $method)
                    <option value="{{ $method }}">{{ $method }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="table-wrap">
        <table class="table" id="teamHistoryTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Member</th>
                    <th>Method</th>
                    <th>Bank / Where</th>
                    <th>Note</th>
                    <th style="text-align:right;">Amount</th>
                    @if($canEdit || $canDelete)<th style="text-align:right;">Actions</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($project->teamPayments->sortByDesc('paid_on') as $payment)
                    @php $pm = $project->teamMembers->firstWhere('id', $payment->team_member_id); @endphp
                    <tr data-member="{{ $payment->team_member_id }}" data-method="{{ $payment->method }}" data-note="{{ strtolower($payment->note ?? '') }}">
                        <td>{{ $payment->paid_on ? $payment->paid_on->format('d M Y') : $payment->created_at->format('d M Y') }}</td>
                        <td>{{ $pm->name ?? ($payment->teamMember->name ?? '—') }}</td>
                        <td>{{ $payment->method ?: '—' }}</td>
                        <td>{{ $payment->bank->name ?? '—' }}</td>
                        <td>{{ $payment->note ?: '—' }}</td>
                        <td style="text-align:right;">৳{{ number_format((float) $payment->amount, 2) }}</td>
                        @if($canEdit || $canDelete)
                        <td>
                            <div class="row-actions" style="justify-content:flex-end;">
                                @if($canEdit && $pm)
                                <button type="button" class="act" title="Edit" onclick="openModal('member-modal-{{ $payment->team_member_id }}')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                @endif
                                @if($canDelete)
                                <form method="POST" action="{{ route('team.payments.delete', [$project->id, $payment->id]) }}" onsubmit="return confirm('Delete this payment record?')">@csrf
                                    <button type="submit" class="act danger" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="{{ ($canEdit || $canDelete) ? 7 : 6 }}">
                        <div class="empty-state"><div>No payments recorded yet.</div></div>
                    </td></tr>
                @endforelse
                <tr class="no-results" style="display:none;"><td colspan="{{ ($canEdit || $canDelete) ? 7 : 6 }}">No payments match your filter.</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Per-member payment detail + edit modals --}}
@foreach($project->teamMembers as $m)
    @php
        $mPayments = $project->teamPayments->where('team_member_id', $m->id)->sortByDesc('paid_on');
        $mPaid = (float) $mPayments->sum('amount');
        $mRemaining = max(0, round($share - $mPaid, 2));
    @endphp
    <div class="modal-overlay" id="member-modal-{{ $m->id }}" onclick="if(event.target===this)closeModal(this)">
        <div class="modal">
            <div class="modal-head">
                <div>
                    <h2 style="margin:0;">{{ $m->name }}</h2>
                    <div class="sub">Share ৳{{ number_format($share, 2) }} · Paid ৳{{ number_format($mPaid, 2) }} · Remaining ৳{{ number_format($mRemaining, 2) }}</div>
                </div>
                <button type="button" class="icon-btn" onclick="closeModal(this.closest('.modal-overlay'))" aria-label="Close">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-sub-label">Payment history for this project</div>
                @forelse($mPayments as $p)
                    <div class="pay-edit">
                        @if($canEdit)
                        <form method="POST" action="{{ route('team.payments.update', [$project->id, $p->id]) }}" class="pay-edit-grid">@csrf
                            <div class="field">
                                <label>Amount</label>
                                <input class="input" type="number" step="0.01" min="0.01" name="amount" value="{{ (float) $p->amount }}" required>
                            </div>
                            <div class="field">
                                <label>Method</label>
                                <select class="select" name="method">
                                    @foreach($methods as $method)
                                        <option value="{{ $method }}" @selected($p->method === $method)>{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>Bank</label>
                                <select class="select" name="bank_id">
                                    <option value="">— none —</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" @selected($p->bank_id == $bank->id)>{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>Date</label>
                                <input class="input" type="date" name="paid_on" value="{{ $p->paid_on ? $p->paid_on->format('Y-m-d') : $today }}">
                            </div>
                            <div class="field pay-edit-note">
                                <label>Note</label>
                                <input class="input" type="text" name="note" value="{{ $p->note }}" placeholder="Optional">
                            </div>
                            <div class="pay-edit-actions">
                                <button class="btn btn-primary btn-sm" type="submit">Save</button>
                            </div>
                        </form>
                        @else
                        <div class="pay-edit-readonly">
                            <span>{{ $p->paid_on ? $p->paid_on->format('d M Y') : '—' }}</span>
                            <span>{{ $p->method ?: '—' }}</span>
                            <span>{{ $p->bank->name ?? '—' }}</span>
                            <span>{{ $p->note ?: '—' }}</span>
                            <strong>৳{{ number_format((float) $p->amount, 2) }}</strong>
                        </div>
                        @endif
                        @if($canDelete)
                        <form method="POST" action="{{ route('team.payments.delete', [$project->id, $p->id]) }}" onsubmit="return confirm('Delete this payment?')" class="pay-edit-del">@csrf
                            <button type="submit" class="act danger" title="Delete">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                @empty
                    <p class="sub">No payments to this member yet for this project.</p>
                @endforelse
            </div>
        </div>
    </div>
@endforeach

<style>
    .check-chip { display:flex; align-items:center; gap:10px; margin:0; padding:10px 12px; border:1px solid var(--border); border-radius:var(--radius-sm); cursor:pointer; transition:border-color .15s, background .15s; }
    .check-chip:hover { border-color:var(--border-strong); background:var(--surface-2); }
    .check-chip input { width:16px; height:16px; accent-color:var(--primary); }
    .btn-sm { padding:6px 12px !important; font-size:.82rem; }

    .pay-row { display:grid; grid-template-columns:1.4fr 1fr 1fr 1.2fr 1fr 1.4fr auto; gap:12px; align-items:end; padding:12px 0; border-top:1px dashed var(--border); }
    .pay-row:first-child { border-top:none; padding-top:0; }
    .pay-row-actions { display:flex; gap:6px; padding-bottom:2px; }
    .pay-row-actions .icon-btn { border:1px solid var(--border); }
    .add-row { color:var(--primary); }
    .remove-row { color:var(--danger); }

    .filter-bar { display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-bottom:8px; }
    .filter-bar .search-box { flex:1; min-width:180px; }

    .modal-overlay { position:fixed; inset:0; background:rgba(15,23,42,.55); backdrop-filter:blur(2px); display:none; align-items:flex-start; justify-content:center; z-index:200; padding:40px 16px; overflow-y:auto; }
    .modal-overlay.open { display:flex; }
    .modal { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); width:100%; max-width:720px; box-shadow:var(--shadow); }
    .modal-head { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; padding:18px 20px; border-bottom:1px solid var(--border); }
    .modal-head h2 { font-size:1.05rem; }
    .modal-body { padding:16px 20px 20px; }
    .modal-sub-label { font-size:.78rem; text-transform:uppercase; letter-spacing:.04em; color:var(--muted); margin-bottom:10px; }

    .pay-edit { display:flex; align-items:end; gap:10px; padding:12px 0; border-top:1px solid var(--border); }
    .pay-edit:first-of-type { border-top:none; }
    .pay-edit-grid { flex:1; display:grid; grid-template-columns:1fr 1fr 1fr 1.1fr; gap:10px; }
    .pay-edit-note { grid-column:1 / -1; }
    .pay-edit-actions { display:flex; align-items:end; }
    .pay-edit-del { display:flex; align-items:end; padding-bottom:2px; }
    .pay-edit-readonly { flex:1; display:flex; flex-wrap:wrap; gap:14px; align-items:center; padding:4px 0; }

    @media (max-width:760px) {
        .pay-row { grid-template-columns:1fr 1fr; }
        .pay-row-actions { grid-column:1 / -1; justify-content:flex-end; }
        .pay-edit { flex-direction:column; align-items:stretch; }
        .pay-edit-grid { grid-template-columns:1fr 1fr; }
        .pay-edit-actions, .pay-edit-del { justify-content:flex-end; }
    }
</style>

<script>
    let payRowIndex = 1;
    function addPayRow() {
        const rows = document.getElementById('payRows');
        const tpl = rows.querySelector('.pay-row');
        const clone = tpl.cloneNode(true);
        clone.setAttribute('data-index', payRowIndex);
        clone.querySelectorAll('[name]').forEach(function (el) {
            el.name = el.name.replace(/rows\[\d+\]/, 'rows[' + payRowIndex + ']');
            if (el.tagName === 'SELECT') { el.selectedIndex = 0; }
            else if (el.type === 'date') { el.value = el.defaultValue || el.getAttribute('value') || ''; }
            else { el.value = ''; }
        });
        clone.querySelector('.remove-row').style.display = '';
        rows.appendChild(clone);
        payRowIndex++;
    }
    function removePayRow(btn) {
        const row = btn.closest('.pay-row');
        if (document.querySelectorAll('#payRows .pay-row').length > 1) { row.remove(); }
    }

    function openModal(id) {
        const el = document.getElementById(id);
        if (el) { el.classList.add('open'); document.body.style.overflow = 'hidden'; }
    }
    function closeModal(el) {
        el.classList.remove('open');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.open').forEach(closeModal);
        }
    });

    function filterHistory() {
        const q = (document.getElementById('histSearch').value || '').toLowerCase();
        const member = document.getElementById('filterMember').value;
        const method = document.getElementById('filterMethod').value;
        const rows = document.querySelectorAll('#teamHistoryTable tbody tr[data-member]');
        let shown = 0;
        rows.forEach(function (tr) {
            const okMember = !member || tr.getAttribute('data-member') === member;
            const okMethod = !method || tr.getAttribute('data-method') === method;
            const okSearch = !q || (tr.getAttribute('data-note') || '').indexOf(q) !== -1;
            const visible = okMember && okMethod && okSearch;
            tr.style.display = visible ? '' : 'none';
            if (visible) shown++;
        });
        const none = document.querySelector('#teamHistoryTable .no-results');
        if (none) none.style.display = shown === 0 ? '' : 'none';
    }
</script>
@endsection
