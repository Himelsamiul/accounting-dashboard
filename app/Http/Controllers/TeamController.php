<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\TeamPayment;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Team members
    |--------------------------------------------------------------------------
    */

    public function membersIndex()
    {
        $members = TeamMember::withCount('projects')
            ->withSum('payments as paid_total', 'amount')
            ->latest()
            ->get();

        return view('team.members.index', compact('members'));
    }

    public function memberCreate()
    {
        return view('team.members.create');
    }

    public function memberStore(Request $request)
    {
        $data = $request->only(['name', 'phone', 'role', 'notes']);
        $data['is_active'] = $request->boolean('is_active', true);

        TeamMember::create($data);

        return redirect()->route('team.members.index')->with('status', 'Team member added successfully.');
    }

    public function memberEdit(TeamMember $member)
    {
        return view('team.members.edit', compact('member'));
    }

    public function memberUpdate(Request $request, TeamMember $member)
    {
        $data = $request->only(['name', 'phone', 'role', 'notes']);
        $data['is_active'] = $request->boolean('is_active', false);

        $member->update($data);

        return redirect()->route('team.members.index')->with('status', 'Team member updated successfully.');
    }

    public function memberDelete(TeamMember $member)
    {
        $member->delete();

        return redirect()->route('team.members.index')->with('status', 'Team member deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Projects (assignment + payment tracking)
    |--------------------------------------------------------------------------
    */

    public function projectsIndex()
    {
        $projects = Project::with('client')
            ->withCount('teamMembers')
            ->withSum('teamPayments as team_paid_total', 'amount')
            ->withSum('invoices as client_paid_total', 'paid_amount')
            ->latest()
            ->get();

        return view('team.projects.index', compact('projects'));
    }

    public function projectShow(Project $project)
    {
        $project->load(['client', 'teamMembers', 'teamPayments.bank', 'invoices']);

        $allMembers = TeamMember::orderBy('name')->get();
        $banks = Bank::orderBy('name')->get();

        // Money received from the client comes from invoices (paid_amount) — caps what we can distribute.
        $collected = (float) $project->invoices->sum('paid_amount');
        $paidToTeam = (float) $project->teamPayments->sum('amount');
        $available = round($collected - $paidToTeam, 2);

        // Per-member breakdown: share (equal split), paid so far, remaining.
        $share = $project->memberShare();
        $breakdown = $project->teamMembers->map(function ($member) use ($project, $share) {
            $paid = (float) $project->teamPayments->where('team_member_id', $member->id)->sum('amount');

            return [
                'member' => $member,
                'share' => $share,
                'paid' => $paid,
                'remaining' => max(0, round($share - $paid, 2)),
            ];
        });

        return view('team.projects.show', compact(
            'project', 'allMembers', 'banks', 'breakdown', 'share',
            'collected', 'paidToTeam', 'available'
        ));
    }

    /** Shared data for the printable / exportable payment summary. */
    private function projectSummaryData(Project $project): array
    {
        $project->load(['client', 'teamMembers', 'teamPayments.bank', 'teamPayments.teamMember', 'invoices']);

        $collected = (float) $project->invoices->sum('paid_amount');
        $paidToTeam = (float) $project->teamPayments->sum('amount');
        $available = round($collected - $paidToTeam, 2);
        $share = $project->memberShare();

        $breakdown = $project->teamMembers->map(function ($member) use ($project, $share) {
            $paid = (float) $project->teamPayments->where('team_member_id', $member->id)->sum('amount');

            return [
                'member' => $member,
                'share' => $share,
                'paid' => $paid,
                'remaining' => max(0, round($share - $paid, 2)),
            ];
        });

        $payments = $project->teamPayments->sortByDesc('paid_on')->values();
        $generatedAt = now()->format('d M Y, h:i A');

        return compact('project', 'collected', 'paidToTeam', 'available', 'share', 'breakdown', 'payments', 'generatedAt');
    }

    public function summary(Project $project)
    {
        return view('team.projects.summary', $this->projectSummaryData($project));
    }

    public function summaryExcel(Project $project)
    {
        $data = $this->projectSummaryData($project);
        $filename = 'team-payment-summary-' . ($project->code ?? $project->id) . '-' . now()->format('Y-m-d') . '.xls';

        return response()
            ->view('team.projects.summary-excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /*
    |--------------------------------------------------------------------------
    | Per-member payment summary (across all their projects)
    |--------------------------------------------------------------------------
    */

    /** Shared data for one member's printable / exportable payment summary. */
    private function memberSummaryData(TeamMember $member): array
    {
        $member->load([
            'projects.client',
            'projects.teamMembers',
            'payments.bank',
            'payments.project',
        ]);

        // Per-project breakdown: this member's share, what they've been paid, and what remains.
        $rows = $member->projects->map(function ($project) use ($member) {
            $share = $project->memberShare();
            $paid = (float) $member->payments->where('project_id', $project->id)->sum('amount');

            return [
                'project' => $project,
                'share' => $share,
                'paid' => $paid,
                'remaining' => max(0, round($share - $paid, 2)),
            ];
        })->values();

        $totalShare = round($rows->sum('share'), 2);
        $totalPaid = round((float) $member->payments->sum('amount'), 2);
        $totalRemaining = round($rows->sum('remaining'), 2);

        $payments = $member->payments->sortByDesc('paid_on')->values();
        $generatedAt = now()->format('d M Y, h:i A');

        return compact(
            'member', 'rows', 'totalShare', 'totalPaid', 'totalRemaining', 'payments', 'generatedAt'
        );
    }

    public function memberSummary(TeamMember $member)
    {
        return view('team.members.summary', $this->memberSummaryData($member));
    }

    public function memberSummaryExcel(TeamMember $member)
    {
        $data = $this->memberSummaryData($member);
        $filename = 'member-payment-summary-' . \Illuminate\Support\Str::slug($member->name) . '-' . now()->format('Y-m-d') . '.xls';

        return response()
            ->view('team.members.summary-excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function assignMembers(Request $request, Project $project)
    {
        $ids = $request->input('member_ids', []);
        $project->teamMembers()->sync($ids);

        return redirect()->route('team.projects.show', $project->id)
            ->with('status', 'Team members updated for this project.');
    }

    public function payStore(Request $request, Project $project)
    {
        // Accepts one or many payment rows: rows[i] = {team_member_id, amount, method, bank_id, paid_on, note}
        $rows = $request->input('rows', []);
        $assignedIds = $project->teamMembers()->pluck('team_members.id')->all();

        $valid = [];
        $total = 0.0;
        foreach ($rows as $row) {
            $amount = (float) ($row['amount'] ?? 0);
            $memberId = $row['team_member_id'] ?? null;

            // Skip blank rows silently.
            if ($amount <= 0 || ! $memberId) {
                continue;
            }
            if (! in_array((int) $memberId, $assignedIds, true)) {
                return redirect()->route('team.projects.show', $project->id)
                    ->with('status', 'One of the selected members is not assigned to this project.');
            }

            $valid[] = [
                'team_member_id' => (int) $memberId,
                'amount' => $amount,
                'method' => $row['method'] ?? null,
                'bank_id' => ($row['bank_id'] ?? null) ?: null,
                'paid_on' => $row['paid_on'] ?? null,
                'note' => $row['note'] ?? null,
            ];
            $total += $amount;
        }

        if (empty($valid)) {
            return redirect()->route('team.projects.show', $project->id)
                ->with('status', 'Please enter at least one member and amount.');
        }

        // Constraint: the total distributed cannot exceed what the client has paid (from invoices).
        $collected = (float) $project->invoices()->sum('paid_amount');
        $paidToTeam = (float) $project->teamPayments()->sum('amount');
        $available = round($collected - $paidToTeam, 2);

        if ($total > $available + 0.009) {
            $message = $collected <= 0
                ? 'The client has not paid anything yet (no invoice collection) — you can only pay the team from what the client has paid.'
                : 'Cannot pay ৳' . number_format($total, 2) . ' total. Only ৳' . number_format(max(0, $available), 2)
                    . ' is available to distribute (client paid ৳' . number_format($collected, 2)
                    . ', already distributed ৳' . number_format($paidToTeam, 2) . ').';

            return redirect()->route('team.projects.show', $project->id)->with('status', $message);
        }

        foreach ($valid as $payment) {
            $project->teamPayments()->create($payment);
        }

        $count = count($valid);
        return redirect()->route('team.projects.show', $project->id)
            ->with('status', $count === 1 ? 'Payment recorded successfully.' : "{$count} payments recorded successfully.");
    }

    public function payUpdate(Request $request, Project $project, TeamPayment $payment)
    {
        $newAmount = (float) $request->input('amount', 0);

        if ($newAmount <= 0) {
            return redirect()->route('team.projects.show', $project->id)
                ->with('status', 'Please enter a valid payment amount.');
        }

        // Constraint on edit: distributed total (excluding this payment's old amount) plus the new amount
        // cannot exceed what the client has paid.
        $collected = (float) $project->invoices()->sum('paid_amount');
        $paidExcludingThis = (float) $project->teamPayments()->where('id', '!=', $payment->id)->sum('amount');
        $availableForThis = round($collected - $paidExcludingThis, 2);

        if ($newAmount > $availableForThis + 0.009) {
            return redirect()->route('team.projects.show', $project->id)
                ->with('status', 'Cannot set ৳' . number_format($newAmount, 2) . '. Only ৳' . number_format(max(0, $availableForThis), 2)
                    . ' is available for this payment (client paid ৳' . number_format($collected, 2) . ').');
        }

        $payment->update([
            'amount' => $newAmount,
            'method' => $request->input('method'),
            'bank_id' => $request->input('bank_id') ?: null,
            'paid_on' => $request->input('paid_on'),
            'note' => $request->input('note'),
        ]);

        return redirect()->route('team.projects.show', $project->id)
            ->with('status', 'Payment updated successfully.');
    }

    public function payDelete(Project $project, TeamPayment $payment)
    {
        $payment->delete();

        return redirect()->route('team.projects.show', $project->id)
            ->with('status', 'Payment deleted successfully.');
    }
}
