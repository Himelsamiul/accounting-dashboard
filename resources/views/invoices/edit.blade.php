@extends('layouts.admin')

@section('title', 'Edit Invoice')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Invoice</h1>
        <div class="sub">Update {{ $invoice->invoice_number }}.</div>
    </div>
    <a href="{{ route('invoices.index') }}" class="btn btn-ghost">Back to list</a>
</div>

<form method="POST" action="{{ route('invoices.edit', $invoice->id) }}">
    @csrf
    <input type="hidden" name="amount" id="amountHidden" value="{{ $invoice->amount }}">

    <div class="invoice-layout">
        <div class="card">
            <div class="card-header"><h2>Invoice details</h2></div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="field">
                        <label>Project <span class="req">*</span></label>
                        <select class="select" name="project_id" id="projectSelect" required>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" data-remaining="{{ $project->remaining }}" {{ $invoice->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }} — ৳{{ number_format($project->remaining, 0) }} due</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Client <span class="req">*</span></label>
                        <select class="select" name="client_id" required>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $invoice->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Bank <span class="req">*</span></label>
                        <select class="select" name="bank_id" required>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}" {{ $invoice->bank_id == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Invoice Number <span class="req">*</span></label>
                        <input class="input" type="text" name="invoice_number" value="{{ $invoice->invoice_number }}" required>
                    </div>
                    <div class="field">
                        <label>Invoice Date <span class="req">*</span></label>
                        <input class="input" type="date" name="invoice_date" value="{{ $invoice->invoice_date }}" required>
                    </div>
                    <div class="field">
                        <label>Paid Amount (৳)</label>
                        <input class="input" type="number" step="0.01" name="paid_amount" id="paidInput" value="{{ $invoice->paid_amount }}" min="0">
                        <span class="hint">Cannot exceed the outstanding balance.</span>
                    </div>
                    <div class="field">
                        <label>Handover To</label>
                        <input class="input" type="text" name="handover_to" value="{{ $invoice->handover_to }}">
                    </div>
                    <div class="field">
                        <label>Status</label>
                        <select class="select" name="status" id="invoiceStatus">
                            <option value="Pending" {{ $invoice->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Partial" {{ $invoice->status == 'Partial' ? 'selected' : '' }}>Partial</option>
                            <option value="Paid" {{ $invoice->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div class="field col-span">
                        <label>Description</label>
                        <textarea class="textarea" rows="3" name="description">{{ $invoice->description }}</textarea>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Update Invoice</button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
                <div class="form-legend"><span class="req">*</span> Required field</div>
            </div>
        </div>

        <aside class="inv-summary">
            <h3>Outstanding to bill</h3>
            <div class="inv-total" id="sumTotal">৳0</div>
            <div class="inv-line"><span>Paying now</span><span id="sumPaid">৳0</span></div>
            <div class="inv-line"><span>Balance after</span><span id="sumBalance">৳0</span></div>
            <div class="inv-status-pill" id="sumStatus">Pending</div>
        </aside>
    </div>
</form>
@endsection

@section('scripts')
<script>
    const projectSelect = document.getElementById('projectSelect');
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

    // Initialise from the currently-selected project's remaining balance.
    (function () {
        const opt = projectSelect.options[projectSelect.selectedIndex];
        if (opt) amountHidden.value = Number(opt.getAttribute('data-remaining') || 0);
        paidInput.max = amountHidden.value;
        refresh();
    })();

    projectSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        amountHidden.value = Number(opt.getAttribute('data-remaining') || 0);
        paidInput.max = amountHidden.value;
        refresh();
    });
    paidInput.addEventListener('input', refresh);
</script>
@endsection
