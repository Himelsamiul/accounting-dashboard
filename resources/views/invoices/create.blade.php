@extends('layouts.admin')

@section('title', 'Create Invoice')

@section('content')
<div class="page-header">
    <div>
        <h1>Create Invoice</h1>
        <div class="sub">Bill a project's outstanding balance and record the payment.</div>
    </div>
    <a href="{{ route('invoices.index') }}" class="btn btn-ghost">Back to list</a>
</div>

<form method="POST" action="{{ route('invoices.create') }}">
    @csrf
    {{-- Amount = the selected project's remaining balance (derived, not user-editable) --}}
    <input type="hidden" name="amount" id="amountHidden" value="0">

    <div class="invoice-layout">
        <div class="card">
            <div class="card-header"><h2>Invoice details</h2></div>
            <div class="card-body">
                @if($openProjects->isEmpty())
                    <div class="empty-state" style="padding:32px 16px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <div>All projects are fully collected. Add a new project to create more invoices.</div>
                        <a href="{{ route('projects.create') }}" class="btn btn-primary" style="margin-top:14px;">Add Project</a>
                    </div>
                @else
                    <div class="form-grid">
                        <div class="field">
                            <label>Project</label>
                            <select class="select" name="project_id" id="projectSelect" required>
                                <option value="">-- Select Project --</option>
                                @foreach($openProjects as $project)
                                    <option value="{{ $project->id }}" data-client="{{ $project->client_id }}" data-remaining="{{ $project->remaining }}">{{ $project->name }} — ৳{{ number_format($project->remaining, 0) }} due</option>
                                @endforeach
                            </select>
                            <span class="hint">Only projects with an outstanding balance are shown.</span>
                        </div>
                        <div class="field">
                            <label>Client</label>
                            <select class="select" name="client_id" id="clientSelect" required>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Bank</label>
                            <select class="select" name="bank_id" required>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Invoice Date</label>
                            <input class="input" type="date" name="invoice_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="field">
                            <label>Paid Amount (৳)</label>
                            <input class="input" type="number" step="0.01" name="paid_amount" id="paidInput" placeholder="0" value="0" min="0">
                            <span class="hint" id="paidHint">Cannot exceed the outstanding balance.</span>
                        </div>
                        <div class="field">
                            <label>Handover To</label>
                            <input class="input" type="text" name="handover_to" placeholder="e.g. Rahim">
                        </div>
                        <div class="field">
                            <label>Status</label>
                            <select class="select" name="status" id="invoiceStatus">
                                <option value="Pending">Pending</option>
                                <option value="Partial">Partial</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                        <div class="field col-span">
                            <label>Description</label>
                            <textarea class="textarea" rows="3" name="description" placeholder="Invoice details and payment summary"></textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">Create Invoice</button>
                        <a href="{{ route('invoices.index') }}" class="btn btn-ghost">Cancel</a>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <aside class="inv-summary">
                <h3>Outstanding to bill</h3>
                <div class="inv-total" id="sumTotal">৳0</div>
                <div class="inv-line"><span>Paying now</span><span id="sumPaid">৳0</span></div>
                <div class="inv-line"><span>Balance after</span><span id="sumBalance">৳0</span></div>
                <div class="inv-status-pill" id="sumStatus">Pending</div>
            </aside>

            <div class="card" style="margin-top:18px;">
                <div class="card-header"><h3>Fully collected</h3></div>
                <div class="card-body" style="padding:8px 18px;">
                    @forelse($completedProjects as $cp)
                        <div class="completed-item">
                            <div>
                                <div class="ci-name">{{ $cp->name }}</div>
                                <div class="ci-sub">{{ $cp->client->name ?? 'No client' }} · ৳{{ number_format($cp->project_value, 0) }}</div>
                            </div>
                            <span class="badge badge-success">Paid</span>
                        </div>
                    @empty
                        <div style="color:var(--muted); font-size:0.85rem; padding:8px 0;">No fully collected projects yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    const projectSelect = document.getElementById('projectSelect');
    if (projectSelect) {
        const clientSelect = document.getElementById('clientSelect');
        const paidInput = document.getElementById('paidInput');
        const amountHidden = document.getElementById('amountHidden');
        const invoiceStatus = document.getElementById('invoiceStatus');
        const sumTotal = document.getElementById('sumTotal');
        const sumPaid = document.getElementById('sumPaid');
        const sumBalance = document.getElementById('sumBalance');
        const sumStatus = document.getElementById('sumStatus');

        function fmt(v) { return '৳' + new Intl.NumberFormat('en-BD', { maximumFractionDigits: 2 }).format(v || 0); }

        function refresh() {
            const total = Number(amountHidden.value || 0);
            let paid = Number(paidInput.value || 0);
            if (paid > total) { paid = total; paidInput.value = total; }
            if (paid < 0) { paid = 0; paidInput.value = 0; }
            const balance = Math.max(0, total - paid);

            sumTotal.textContent = fmt(total);
            sumPaid.textContent = fmt(paid);
            sumBalance.textContent = fmt(balance);

            let status = 'Pending';
            if (total > 0 && balance <= 0) status = 'Paid';
            else if (paid > 0) status = 'Partial';
            invoiceStatus.value = status;
            sumStatus.textContent = status;
        }

        projectSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            amountHidden.value = Number(opt.getAttribute('data-remaining') || 0);
            paidInput.value = 0;
            paidInput.max = amountHidden.value;
            const clientId = opt.getAttribute('data-client') || '';
            if (clientId) {
                Array.from(clientSelect.options).forEach(o => o.selected = o.value === clientId);
            }
            refresh();
        });
        paidInput.addEventListener('input', refresh);
        refresh();
    }
</script>
@endsection
