<table border="1">
    <tr><td colspan="7" style="font-size:16px; font-weight:bold;">Prime Byte — Member Payment Summary</td></tr>
    <tr><td colspan="7">Generated: {{ $generatedAt }}</td></tr>
    <tr><td colspan="7"></td></tr>
    <tr><td style="font-weight:bold;">Member</td><td>{{ $member->name }}</td><td style="font-weight:bold;">Role</td><td>{{ $member->role ?: '-' }}</td><td style="font-weight:bold;">Phone</td><td colspan="2">{{ $member->phone ?: '-' }}</td></tr>
    <tr><td style="font-weight:bold;">Total Share</td><td>{{ number_format($totalShare, 2) }}</td><td style="font-weight:bold;">Total Paid</td><td>{{ number_format($totalPaid, 2) }}</td><td style="font-weight:bold;">Remaining</td><td colspan="2">{{ number_format($totalRemaining, 2) }}</td></tr>
    <tr><td colspan="7"></td></tr>

    <tr><td colspan="7" style="font-weight:bold; background:#eef2f8;">PER-PROJECT BREAKDOWN</td></tr>
    <tr style="font-weight:bold; background:#f7f9fc;">
        <td>Project</td><td>Client</td><td>Code</td><td>Share</td><td>Paid</td><td>Remaining</td><td>Status</td>
    </tr>
    @foreach($rows as $row)
    <tr>
        <td>{{ $row['project']->name }}</td>
        <td>{{ $row['project']->client->name ?? '-' }}</td>
        <td>{{ $row['project']->code ?? '-' }}</td>
        <td>{{ number_format($row['share'], 2) }}</td>
        <td>{{ number_format($row['paid'], 2) }}</td>
        <td>{{ number_format($row['remaining'], 2) }}</td>
        <td>{{ $row['remaining'] <= 0.009 ? 'Cleared' : 'Due' }}</td>
    </tr>
    @endforeach
    <tr style="font-weight:bold;">
        <td colspan="3">Total</td>
        <td>{{ number_format($totalShare, 2) }}</td>
        <td>{{ number_format($totalPaid, 2) }}</td>
        <td>{{ number_format($totalRemaining, 2) }}</td>
        <td></td>
    </tr>
    <tr><td colspan="7"></td></tr>

    <tr><td colspan="7" style="font-weight:bold; background:#eef2f8;">FULL PAYMENT LOG</td></tr>
    <tr style="font-weight:bold; background:#f7f9fc;">
        <td>Date</td><td>Project</td><td>Method</td><td>Bank / Where</td><td>Note</td><td colspan="2">Amount</td>
    </tr>
    @foreach($payments as $p)
    <tr>
        <td>{{ $p->paid_on ? $p->paid_on->format('d M Y') : $p->created_at->format('d M Y') }}</td>
        <td>{{ $p->project->name ?? '-' }}</td>
        <td>{{ $p->method ?: '-' }}</td>
        <td>{{ $p->bank->name ?? '-' }}</td>
        <td>{{ $p->note ?: '-' }}</td>
        <td colspan="2">{{ number_format((float) $p->amount, 2) }}</td>
    </tr>
    @endforeach
    <tr style="font-weight:bold;">
        <td colspan="5">Total paid</td>
        <td colspan="2">{{ number_format($totalPaid, 2) }}</td>
    </tr>
</table>
