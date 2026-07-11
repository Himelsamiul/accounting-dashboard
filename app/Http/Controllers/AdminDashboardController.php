<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $earning = (float) Project::sum('project_value');
        $collection = (float) Invoice::sum('paid_amount');
        $stats = [
            'earning_projects' => $earning,
            'total_collection' => $collection,
            'total_outstanding' => max(0, $earning - $collection),
            'total_banks' => Bank::count(),
            'total_clients' => Client::count(),
            'total_projects' => Project::count(),
            'total_invoices' => Invoice::count(),
        ];

        // Collection over the last 6 months for the bar chart.
        $months = [];
        $now = now()->startOfMonth();
        for ($i = 5; $i >= 0; $i--) {
            $month = (clone $now)->subMonths($i);
            $months[] = [
                'label' => $month->format('M'),
                'key' => $month->format('Y-m'),
                'total' => 0.0,
            ];
        }

        $invoices = Invoice::whereNotNull('invoice_date')->get(['invoice_date', 'paid_amount']);
        foreach ($invoices as $invoice) {
            try {
                $key = \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m');
            } catch (\Exception $e) {
                continue;
            }
            foreach ($months as &$month) {
                if ($month['key'] === $key) {
                    $month['total'] += (float) $invoice->paid_amount;
                }
            }
            unset($month);
        }

        // Invoice status breakdown for the donut chart.
        $statusBreakdown = [
            'Paid' => Invoice::where('status', 'Paid')->count(),
            'Partial' => Invoice::where('status', 'Partial')->count(),
            'Pending' => Invoice::where('status', 'Pending')->count(),
        ];

        // Project status breakdown (derived from collection) for the dashboard chart.
        $projectStatus = ['Fully Paid' => 0, 'Partial' => 0, 'Open' => 0];
        foreach ($this->projectsWithRemaining() as $p) {
            if ($p->project_value > 0 && $p->remaining <= 0.009) {
                $projectStatus['Fully Paid']++;
            } elseif ($p->collected > 0) {
                $projectStatus['Partial']++;
            } else {
                $projectStatus['Open']++;
            }
        }

        // Team payout snapshot.
        $teamStats = [
            'members' => \App\Models\TeamMember::count(),
            'active' => \App\Models\TeamMember::where('is_active', true)->count(),
            'paid_out' => (float) \App\Models\TeamPayment::sum('amount'),
        ];

        // Recent invoices for the activity table.
        $recentInvoices = Invoice::with(['client', 'project'])->latest()->take(6)->get();

        // Top clients by total project value.
        $topClients = Client::withSum('projects as total_value', 'project_value')
            ->withCount('projects')
            ->orderByDesc('total_value')
            ->take(5)
            ->get()
            ->filter(fn ($c) => $c->total_value > 0)
            ->values();

        return view('dashboard', compact(
            'stats', 'months', 'statusBreakdown', 'projectStatus',
            'teamStats', 'recentInvoices', 'topClients'
        ));
    }

    public function indexClients()
    {
        $clients = Client::latest()->get();
        return view('clients.index', compact('clients'));
    }

    public function showClient(Client $client)
    {
        $client->load('projects', 'invoices');
        return view('clients.show', compact('client'));
    }

    public function createProject()
    {
        $clients = Client::all();
        return view('projects.create', compact('clients'));
    }

    public function indexProjects()
    {
        $projects = Project::with('client')
            ->withSum('invoices as paid_total', 'paid_amount')
            ->latest()
            ->get();
        return view('projects.index', compact('projects'));
    }

    public function showProject(Project $project)
    {
        $project->load('client', 'invoices');
        return view('projects.show', compact('project'));
    }

    public function storeProject(Request $request)
    {
        $project = Project::create(array_merge(
            $request->only(['client_id', 'name', 'type', 'project_value', 'start_date', 'end_date', 'status', 'description']),
            ['code' => Project::generateCode()]
        ));

        // Email the tracking code to the client; don't fail creation if mail is down.
        $message = 'Project created successfully. Tracking code: ' . $project->code;
        $project->load('client');
        if ($project->client && $project->client->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($project->client->email)
                    ->send(new \App\Mail\ProjectCodeMail($project));
                $message .= ' — emailed to ' . $project->client->email;
            } catch (\Throwable $e) {
                $message .= ' (email could not be sent: ' . $e->getMessage() . ')';
            }
        }

        return redirect()->route('projects.index')->with('status', $message);
    }

    public function editProject(Project $project)
    {
        $clients = Client::all();
        return view('projects.edit', compact('project', 'clients'));
    }

    public function updateProject(Request $request, Project $project)
    {
        $project->update($request->only(['client_id', 'name', 'type', 'project_value', 'start_date', 'end_date', 'status', 'description']));
        return redirect()->route('projects.index')->with('status', 'Project updated successfully.');
    }

    public function deleteProject(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('status', 'Project deleted successfully.');
    }

    public function createBank()
    {
        return view('banks.create');
    }

    public function indexBanks()
    {
        $banks = Bank::latest()->get();
        return view('banks.index', compact('banks'));
    }

    public function showBank(Bank $bank)
    {
        $bank->load('invoices');
        return view('banks.show', compact('bank'));
    }

    public function storeBank(Request $request)
    {
        Bank::create($request->only(['name', 'account_number', 'branch', 'notes']));
        return redirect()->route('banks.index')->with('status', 'Bank created successfully.');
    }

    public function editBank(Bank $bank)
    {
        return view('banks.edit', compact('bank'));
    }

    public function updateBank(Request $request, Bank $bank)
    {
        $bank->update($request->only(['name', 'account_number', 'branch', 'notes']));
        return redirect()->route('banks.index')->with('status', 'Bank updated successfully.');
    }

    public function deleteBank(Bank $bank)
    {
        $bank->delete();
        return redirect()->route('banks.index')->with('status', 'Bank deleted successfully.');
    }

    public function createClient()
    {
        return view('clients.create');
    }

    public function storeClient(Request $request)
    {
        Client::create($request->only(['name', 'company', 'email', 'phone', 'address']));
        return redirect()->route('clients.index')->with('status', 'Client created successfully.');
    }

    public function editClient(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function updateClient(Request $request, Client $client)
    {
        $client->update($request->only(['name', 'company', 'email', 'phone', 'address']));
        return redirect()->route('clients.index')->with('status', 'Client updated successfully.');
    }

    public function deleteClient(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('status', 'Client deleted successfully.');
    }

    public function createInvoice()
    {
        $clients = Client::all();
        $banks = Bank::all();
        $projects = $this->projectsWithRemaining();
        $openProjects = $projects->filter(fn ($p) => $p->remaining > 0.009)->values();
        $completedProjects = $projects->filter(fn ($p) => $p->project_value > 0 && $p->remaining <= 0.009)->values();

        return view('invoices.create', compact('clients', 'banks', 'openProjects', 'completedProjects'));
    }

    public function storeInvoice(Request $request)
    {
        $project = Project::find($request->input('project_id'));
        if (! $project) {
            return back()->withErrors(['project_id' => 'Please select a valid project.'])->withInput();
        }

        $collected = (float) Invoice::where('project_id', $project->id)->sum('paid_amount');
        $remaining = max(0, (float) $project->project_value - $collected);

        if ($remaining <= 0.009) {
            return back()->withErrors(['project_id' => 'This project is already fully collected. No further invoices can be created.'])->withInput();
        }

        // The invoice bills the outstanding balance; paid can never exceed it.
        $paid = min(max(0, (float) $request->input('paid_amount', 0)), $remaining);
        $amount = $remaining;

        $invoiceNumber = $request->input('invoice_number');
        if (! $invoiceNumber || Invoice::where('invoice_number', $invoiceNumber)->exists()) {
            $invoiceNumber = $this->generateInvoiceNumber();
        }

        $invoice = new Invoice($request->only(['project_id', 'client_id', 'bank_id', 'invoice_date', 'handover_to', 'description']));
        $invoice->invoice_number = $invoiceNumber;
        $invoice->amount = $amount;
        $invoice->paid_amount = $paid;
        $invoice->balance_amount = max(0, $amount - $paid);
        $invoice->status = $this->resolveInvoiceStatus($amount, $paid, $request->input('status'));
        $invoice->save();

        return redirect()->route('invoices.index')->with('status', 'Invoice created successfully.');
    }

    public function editInvoice(Invoice $invoice)
    {
        $clients = Client::all();
        $banks = Bank::all();
        // Remaining excludes THIS invoice so its own payment is editable.
        $projects = $this->projectsWithRemaining($invoice->id);

        return view('invoices.edit', compact('invoice', 'clients', 'banks', 'projects'));
    }

    public function updateInvoice(Request $request, Invoice $invoice)
    {
        $project = Project::find($request->input('project_id')) ?? $invoice->project;
        if (! $project) {
            return back()->withErrors(['project_id' => 'Please select a valid project.'])->withInput();
        }

        // Collected by every OTHER invoice on this project.
        $collectedOthers = (float) Invoice::where('project_id', $project->id)
            ->where('id', '!=', $invoice->id)
            ->sum('paid_amount');
        $remaining = max(0, (float) $project->project_value - $collectedOthers);

        $paid = min(max(0, (float) $request->input('paid_amount', 0)), $remaining);
        $amount = $remaining;

        $invoice->fill($request->only(['project_id', 'client_id', 'bank_id', 'invoice_number', 'invoice_date', 'handover_to', 'description']));
        $invoice->amount = $amount;
        $invoice->paid_amount = $paid;
        $invoice->balance_amount = max(0, $amount - $paid);
        $invoice->status = $this->resolveInvoiceStatus($amount, $paid, $request->input('status'));
        $invoice->save();

        return redirect()->route('invoices.index')->with('status', 'Invoice updated successfully.');
    }

    /**
     * All projects with their collected + remaining amounts computed.
     * Optionally exclude one invoice's payment (used when editing it).
     */
    private function projectsWithRemaining($excludeInvoiceId = null)
    {
        $paidByProject = Invoice::when($excludeInvoiceId, function ($query) use ($excludeInvoiceId) {
                $query->where('id', '!=', $excludeInvoiceId);
            })
            ->selectRaw('project_id, SUM(paid_amount) as paid')
            ->groupBy('project_id')
            ->pluck('paid', 'project_id');

        return Project::with('client')->withCount('invoices')->get()->map(function ($project) use ($paidByProject) {
            $collected = (float) ($paidByProject[$project->id] ?? 0);
            $project->collected = $collected;
            $project->remaining = max(0, (float) $project->project_value - $collected);

            return $project;
        });
    }

    public function deleteInvoice(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('status', 'Invoice deleted successfully.');
    }

    public function invoiceList()
    {
        $invoices = Invoice::with(['client', 'project', 'bank'])->latest()->get();
        $completedProjects = $this->projectsWithRemaining()
            ->filter(fn ($p) => $p->project_value > 0 && $p->remaining <= 0.009)
            ->values();
        return view('invoices.index', compact('invoices', 'completedProjects'));
    }

    public function showInvoice(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'bank']);
        return view('invoices.show', compact('invoice'));
    }

    /** Fully paid (fully collected) projects. */
    private function fullyPaidProjects()
    {
        return $this->projectsWithRemaining()
            ->filter(fn ($p) => $p->project_value > 0 && $p->remaining <= 0.009)
            ->values();
    }

    public function fullyPaidIndex()
    {
        $projects = $this->fullyPaidProjects();
        return view('fully-paid.index', compact('projects'));
    }

    public function fullyPaidPdf()
    {
        $projects = $this->fullyPaidProjects();
        $generatedAt = now()->format('d M Y, h:i A');
        $pdf = Pdf::loadView('fully-paid.pdf', compact('projects', 'generatedAt'));
        return $pdf->download('fully-paid-projects.pdf');
    }

    public function fullyPaidExcel()
    {
        $projects = $this->fullyPaidProjects();
        $filename = 'fully-paid-projects-' . now()->format('Y-m-d') . '.xls';

        return response()
            ->view('fully-paid.excel', compact('projects'))
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function downloadInvoicePdf($id)
    {
        $invoice = Invoice::with(['client', 'project', 'bank'])->findOrFail($id);
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    private function generateInvoiceNumber()
    {
        $next = (int) Invoice::max('id') + 1;
        do {
            $number = 'INV-' . str_pad($next, 3, '0', STR_PAD_LEFT);
            $next++;
        } while (Invoice::where('invoice_number', $number)->exists());

        return $number;
    }

    private function resolveInvoiceStatus($amount, $paidAmount, $requestedStatus = null)
    {
        if ($requestedStatus && in_array($requestedStatus, ['Pending', 'Partial', 'Paid'])) {
            return $requestedStatus;
        }

        if ($amount > 0 && $paidAmount >= $amount) {
            return 'Paid';
        }

        if ($paidAmount > 0) {
            return 'Partial';
        }

        return 'Pending';
    }
}
