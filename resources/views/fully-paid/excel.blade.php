<table border="1">
    <thead>
        <tr>
            <th colspan="7" style="background:#4f46e5;color:#ffffff;font-size:16px;">Prime Byte — Fully Paid Projects</th>
        </tr>
        <tr>
            <th style="background:#eef0fe;">#</th>
            <th style="background:#eef0fe;">Project</th>
            <th style="background:#eef0fe;">Client</th>
            <th style="background:#eef0fe;">Type</th>
            <th style="background:#eef0fe;">Value (Tk)</th>
            <th style="background:#eef0fe;">Collected (Tk)</th>
            <th style="background:#eef0fe;">Invoices</th>
        </tr>
    </thead>
    <tbody>
        @forelse($projects as $i => $project)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $project->name }}</td>
                <td>{{ $project->client->name ?? '' }}</td>
                <td>{{ $project->type }}</td>
                <td>{{ number_format($project->project_value, 2, '.', '') }}</td>
                <td>{{ number_format($project->collected, 2, '.', '') }}</td>
                <td>{{ $project->invoices_count }}</td>
            </tr>
        @empty
            <tr><td colspan="7">No fully paid projects.</td></tr>
        @endforelse
        @if($projects->isNotEmpty())
            <tr>
                <td colspan="4" style="font-weight:bold;background:#f3f4f6;">Total</td>
                <td style="font-weight:bold;background:#f3f4f6;">{{ number_format($projects->sum('project_value'), 2, '.', '') }}</td>
                <td style="font-weight:bold;background:#f3f4f6;">{{ number_format($projects->sum('collected'), 2, '.', '') }}</td>
                <td style="background:#f3f4f6;"></td>
            </tr>
        @endif
    </tbody>
</table>
