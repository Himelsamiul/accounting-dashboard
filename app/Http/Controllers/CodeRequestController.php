<?php

namespace App\Http\Controllers;

use App\Mail\ProjectCodesMail;
use App\Models\CodeRequest;
use App\Models\Project;
use Illuminate\Support\Facades\Mail;

class CodeRequestController extends Controller
{
    public function index()
    {
        $requests = CodeRequest::with('customer')
            ->orderByRaw("status = 'pending' desc")
            ->latest()
            ->paginate(30);

        return view('code-requests.index', compact('requests'));
    }

    /** Resend the tracking code(s) for every project whose client uses the requested email. */
    public function send(CodeRequest $codeRequest)
    {
        // A request can only be fulfilled once.
        if ($codeRequest->status === 'sent') {
            return back()->with('status', 'This request has already been sent. Code cannot be sent again for it.');
        }

        $projects = Project::with('client')
            ->whereHas('client', fn ($q) => $q->where('email', $codeRequest->email))
            ->get();

        if ($projects->isEmpty()) {
            return back()->with('status', 'No projects are registered under ' . $codeRequest->email . '. Nothing to send.');
        }

        try {
            Mail::to($codeRequest->email)->send(
                new ProjectCodesMail($projects, $projects->first()->client->name ?? null)
            );
        } catch (\Throwable $e) {
            return back()->with('status', 'Could not send the email: ' . $e->getMessage());
        }

        $codeRequest->update(['status' => 'sent', 'handled_at' => now()]);

        return back()->with('status', 'Tracking code(s) emailed to ' . $codeRequest->email . '.');
    }

    public function destroy(CodeRequest $codeRequest)
    {
        $codeRequest->delete();

        return back()->with('status', 'Request removed.');
    }
}
