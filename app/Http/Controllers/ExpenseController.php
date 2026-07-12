<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Expense;
use App\Models\ExpenseHead;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Expense heads
    |--------------------------------------------------------------------------
    */

    public function headsIndex()
    {
        $heads = ExpenseHead::withCount('expenses')
            ->withSum('expenses as expenses_total', 'amount')
            ->latest()
            ->get();

        return view('expense-heads.index', compact('heads'));
    }

    public function headCreate()
    {
        return view('expense-heads.create');
    }

    public function headStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ExpenseHead::create($data);

        return redirect()->route('expense-heads.index')->with('status', 'Expense head created successfully.');
    }

    public function headEdit(ExpenseHead $head)
    {
        return view('expense-heads.edit', compact('head'));
    }

    public function headUpdate(Request $request, ExpenseHead $head)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $head->update($data);

        return redirect()->route('expense-heads.index')->with('status', 'Expense head updated successfully.');
    }

    public function headDelete(ExpenseHead $head)
    {
        $head->delete();

        return redirect()->route('expense-heads.index')->with('status', 'Expense head deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Expenses
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Expense::with(['head', 'bank']);

        // Optional filter by head.
        if ($request->filled('head')) {
            $query->where('expense_head_id', $request->input('head'));
        }

        $expenses = $query->latest('expense_date')->latest()->get();
        $heads = ExpenseHead::orderBy('name')->get();
        $total = (float) $expenses->sum('amount');

        return view('expenses.index', compact('expenses', 'heads', 'total'));
    }

    public function create()
    {
        $heads = ExpenseHead::orderBy('name')->get();
        $banks = Bank::orderBy('name')->get();

        return view('expenses.create', compact('heads', 'banks'));
    }

    public function store(Request $request)
    {
        $data = $this->validateExpense($request);

        Expense::create($data);

        return redirect()->route('expenses.index')->with('status', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        $heads = ExpenseHead::orderBy('name')->get();
        $banks = Bank::orderBy('name')->get();

        return view('expenses.edit', compact('expense', 'heads', 'banks'));
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $this->validateExpense($request);

        $expense->update($data);

        return redirect()->route('expenses.index')->with('status', 'Expense updated successfully.');
    }

    public function delete(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('status', 'Expense deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Report (filterable, printable, exportable)
    |--------------------------------------------------------------------------
    */

    public function report(Request $request)
    {
        return view('expenses.report', $this->reportData($request));
    }

    public function reportPdf(Request $request)
    {
        $data = $this->reportData($request);
        $pdf = Pdf::loadView('expenses.pdf', $data);

        return $pdf->download('expense-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function reportExcel(Request $request)
    {
        $data = $this->reportData($request);
        $filename = 'expense-report-' . now()->format('Y-m-d') . '.xls';

        return response()
            ->view('expenses.excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /** Shared filtered dataset used by the report page, PDF and Excel export. */
    private function reportData(Request $request): array
    {
        $query = Expense::with(['head', 'bank']);

        if ($request->filled('head')) {
            $query->where('expense_head_id', $request->input('head'));
        }
        if ($request->filled('method')) {
            $query->where('payment_method', 'like', '%' . $request->input('method') . '%');
        }
        if ($request->filled('from')) {
            $query->whereDate('expense_date', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('expense_date', '<=', $request->input('to'));
        }

        $expenses = $query->orderByDesc('expense_date')->orderByDesc('id')->get();
        $total = (float) $expenses->sum('amount');

        // Head-wise breakdown for the summary section.
        $byHead = $expenses
            ->groupBy(fn ($e) => $e->head?->name ?? '—')
            ->map(fn ($rows) => [
                'count' => $rows->count(),
                'total' => (float) $rows->sum('amount'),
            ])
            ->sortByDesc('total');

        $heads = ExpenseHead::orderBy('name')->get();
        $company = Setting::company();
        $generatedAt = now()->format('d M Y, h:i A');

        // Human-readable description of the active filters (for report headers).
        $filters = [
            'head' => $request->filled('head') ? optional(ExpenseHead::find($request->input('head')))->name : null,
            'method' => $request->input('method') ?: null,
            'from' => $request->input('from') ?: null,
            'to' => $request->input('to') ?: null,
        ];

        return compact('expenses', 'total', 'byHead', 'heads', 'company', 'generatedAt', 'filters');
    }

    private function validateExpense(Request $request): array
    {
        $data = $request->validate([
            'expense_head_id' => 'required|exists:expense_heads,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:255',
            'bank_id' => 'nullable|exists:banks,id',
            'note' => 'nullable|string',
        ]);

        $data['bank_id'] = $data['bank_id'] ?? null;

        return $data;
    }
}
