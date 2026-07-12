<table border="1">
    <thead>
        <tr>
            <th colspan="6" style="background:#4f46e5;color:#ffffff;font-size:16px;">{{ $company['company_name'] }} — Expense Report</th>
        </tr>
        <tr>
            <td colspan="6" style="background:#eef0fe;font-size:11px;">
                Generated: {{ $generatedAt }}
                &nbsp;|&nbsp; Head: {{ $filters['head'] ?? 'All' }}
                &nbsp;|&nbsp; Method: {{ $filters['method'] ?? 'All' }}
                &nbsp;|&nbsp; From: {{ $filters['from'] ?? 'Any' }}
                &nbsp;|&nbsp; To: {{ $filters['to'] ?? 'Any' }}
            </td>
        </tr>
        <tr>
            <th style="background:#eef0fe;">#</th>
            <th style="background:#eef0fe;">Date</th>
            <th style="background:#eef0fe;">Title</th>
            <th style="background:#eef0fe;">Head</th>
            <th style="background:#eef0fe;">Method</th>
            <th style="background:#eef0fe;">Amount (Tk)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($expenses as $i => $expense)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $expense->expense_date ? $expense->expense_date->format('Y-m-d') : '' }}</td>
                <td>{{ $expense->title }}</td>
                <td>{{ $expense->head?->name ?? '' }}</td>
                <td>{{ $expense->payment_method }}{{ $expense->bank ? ' - ' . $expense->bank->name : '' }}</td>
                <td>{{ number_format((float) $expense->amount, 2, '.', '') }}</td>
            </tr>
        @empty
            <tr><td colspan="6">No expenses match the selected filters.</td></tr>
        @endforelse
        @if($expenses->isNotEmpty())
            <tr>
                <td colspan="5" style="font-weight:bold;background:#f3f4f6;">Total</td>
                <td style="font-weight:bold;background:#f3f4f6;">{{ number_format($total, 2, '.', '') }}</td>
            </tr>
        @endif
    </tbody>
</table>

@if($byHead->isNotEmpty())
<br>
<table border="1">
    <thead>
        <tr>
            <th colspan="3" style="background:#4f46e5;color:#ffffff;font-size:14px;">Head-wise Breakdown</th>
        </tr>
        <tr>
            <th style="background:#eef0fe;">Expense Head</th>
            <th style="background:#eef0fe;">Entries</th>
            <th style="background:#eef0fe;">Total (Tk)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byHead as $name => $row)
            <tr>
                <td>{{ $name }}</td>
                <td>{{ $row['count'] }}</td>
                <td>{{ number_format($row['total'], 2, '.', '') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif
