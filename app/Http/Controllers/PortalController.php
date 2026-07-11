<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function showReviewForm()
    {
        $existing = Review::where('customer_id', Auth::guard('customer')->id())->first();
        return view('portal.review', compact('existing'));
    }

    public function submitReview(Request $request)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'role' => ['nullable', 'string', 'max:120'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        $customer = Auth::guard('customer')->user();

        Review::updateOrCreate(
            ['customer_id' => $customer->id],
            [
                'name' => $customer->name,
                'role' => $data['role'] ?? 'Client',
                'rating' => $data['rating'],
                'comment' => $data['comment'],
                'is_approved' => true,
            ]
        );

        \App\Models\AdminNotification::record('New ' . $data['rating'] . '★ review from ' . $customer->name, [
            'type' => 'review',
            'body' => \Illuminate\Support\Str::limit($data['comment'], 90),
            'url' => route('reviews.index'),
            'icon' => 'star',
        ]);

        return redirect()->route('public.home')->with('status', 'Thank you for your review!');
    }

    public function track(Request $request)
    {
        $code = trim((string) $request->query('code', ''));
        $project = null;
        $searched = $code !== '';

        if ($searched) {
            $project = Project::with(['client', 'invoices' => fn ($q) => $q->latest('invoice_date')])
                ->where('code', $code)
                ->first();

            if (! $project) {
                return view('portal.track', ['project' => null, 'searched' => true, 'code' => $code])
                    ->with('notFound', true);
            }
        }

        return view('portal.track', [
            'project' => $project ? $this->decorate($project) : null,
            'searched' => $searched,
            'code' => $code,
        ]);
    }

    /** Form where a logged-in client asks admin to resend their tracking code(s). */
    public function showRequestCode()
    {
        return view('portal.request-code');
    }

    public function submitRequestCode(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:160'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $customer = Auth::guard('customer')->user();

        \App\Models\CodeRequest::create([
            'email' => $data['email'],
            'customer_id' => $customer?->id,
            'note' => $data['note'] ?? null,
            'status' => 'pending',
        ]);

        \App\Models\AdminNotification::record('Tracking code resend requested', [
            'type' => 'message',
            'body' => 'For ' . $data['email'] . ($customer ? ' · by ' . $customer->name : ''),
            'url' => route('code-requests.index'),
            'icon' => 'mail',
        ]);

        return back()->with('status', 'Your request has been sent. Our team will email your tracking code shortly.');
    }

    public function printProject(string $code)
    {
        $project = Project::with(['client', 'invoices' => fn ($q) => $q->latest('invoice_date')])
            ->where('code', $code)
            ->firstOrFail();

        return view('portal.print-project', ['project' => $this->decorate($project)]);
    }

    public function printInvoice(string $code, Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'bank']);

        // The customer must hold the project's code to print its invoice.
        abort_unless($invoice->project && $invoice->project->code === $code, 403);

        return view('portal.print-invoice', compact('invoice'));
    }

    /** Attach computed collection figures + status to a project. */
    private function decorate(Project $project): Project
    {
        $value = (float) $project->project_value;
        $collected = (float) $project->invoices->sum('paid_amount');
        $remaining = max(0, $value - $collected);

        $project->collected = $collected;
        $project->remaining = $remaining;
        $project->pct = $value > 0 ? min(100, round($collected / $value * 100)) : 0;
        $project->statusLabel = ($value > 0 && $remaining <= 0.009)
            ? 'Fully Paid'
            : ($collected > 0 ? 'In Progress' : 'Pending');

        return $project;
    }
}
