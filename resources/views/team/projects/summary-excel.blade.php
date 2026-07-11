<table border="1">
    <tr><td colspan="6" style="font-size:16px; font-weight:bold;">Prime Byte — Team Payment Summary</td></tr>
    <tr><td colspan="6">Generated: {{ $generatedAt }}</td></tr>
    <tr><td colspan="6"></td></tr>
    <tr><td style="font-weight:bold;">Project</td><td>{{ $project->name }}</td><td style="font-weight:bold;">Code</td><td>{{ $project->code ?? '-' }}</td><td style="font-weight:bold;">Client</td><td>{{ $project->client->name ?? '-' }}</td></tr>
    <tr><td style="font-weight:bold;">Project Value</td><td>{{ number_format((float) $project->project_value, 2) }}</td><td style="font-weight:bold;">Collected</td><td>{{ number_format($collected, 2) }}</td><td style="font-weight:bold;">Paid to Team</td><td>{{ number_format($paidToTeam, 2) }}</td></tr>
    <tr><td style="font-weight:bold;">Available</td><td>{{ number_format(max(0, $available), 2) }}</td><td style="font-weight:bold;">Members</td><td>{{ $project->teamMembers->count() }}</td><td style="font-weight:bold;">Share each</td><td>{{ number_format($share, 2) }}</td></tr>
    <tr><td colspan="6"></td></tr>

    <tr><td colspan="6" style="font-weight:bold; background:#eef2f8;">PER-MEMBER BREAKDOWN</td></tr>
    <tr style="font-weight:bold; background:#f7f9fc;">
        <td>Member</td><td>Role</td><td>Share</td><td>Paid</td><td>Remaining</td><td>Status</td>
    </tr>
    @foreach($breakdown as $row)
    <tr>
        <td>{{ $row['member']->name }}</td>
        <td>{{ $row['member']->role ?: '-' }}</td>
        <td>{{ number_format($row['share'], 2) }}</td>
        <td>{{ number_format($row['paid'], 2) }}</td>
        <td>{{ number_format($row['remaining'], 2) }}</td>
        <td>{{ $row['remaining'] <= 0.009 ? 'Cleared' : 'Due' }}</td>
    </tr>
    @endforeach
    <tr style="font-weight:bold;">
        <td colspan="3">Total</td>
        <td>{{ number_format($paidToTeam, 2) }}</td>
        <td>{{ number_format($breakdown->sum('remaining'), 2) }}</td>
        <td></td>
    </tr>
    <tr><td colspan="6"></td></tr>

    <tr><td colspan="6" style="font-weight:bold; background:#eef2f8;">FULL PAYMENT LOG</td></tr>
    <tr style="font-weight:bold; background:#f7f9fc;">
        <td>Date</td><td>Member</td><td>Method</td><td>Bank / Where</td><td>Note</td><td>Amount</td>
    </tr>
    @foreach($payments as $p)
    <tr>
        <td>{{ $p->paid_on ? $p->paid_on->format('d M Y') : $p->created_at->format('d M Y') }}</td>
        <td>{{ $p->teamMember->name ?? '-' }}</td>
        <td>{{ $p->method ?: '-' }}</td>
        <td>{{ $p->bank->name ?? '-' }}</td>
        <td>{{ $p->note ?: '-' }}</td>
        <td>{{ number_format((float) $p->amount, 2) }}</td>
    </tr>
    @endforeach
    <tr style="font-weight:bold;">
        <td colspan="5">Total paid to team</td>
        <td>{{ number_format($paidToTeam, 2) }}</td>
    </tr>
</table>
