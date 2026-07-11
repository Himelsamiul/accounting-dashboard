<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Console\Command;

class ReconcileInvoices extends Command
{
    protected $signature = 'invoices:reconcile {--dry-run : Show what would change without saving}';

    protected $description = 'Cap over-collected invoices and recompute amount/balance/status per project';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $changed = 0;

        foreach (Project::all() as $project) {
            $value = (float) $project->project_value;
            $collected = 0.0;

            $invoices = Invoice::where('project_id', $project->id)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();

            foreach ($invoices as $invoice) {
                $outstandingBefore = max(0, $value - $collected);
                $paid = min(max(0, (float) $invoice->paid_amount), $outstandingBefore);
                $amount = $outstandingBefore;
                $balance = max(0, $amount - $paid);

                $status = 'Pending';
                if ($amount > 0 && $balance <= 0.009) {
                    $status = 'Paid';
                } elseif ($paid > 0) {
                    $status = 'Partial';
                }

                $needsUpdate = abs((float) $invoice->paid_amount - $paid) > 0.009
                    || abs((float) $invoice->amount - $amount) > 0.009
                    || abs((float) $invoice->balance_amount - $balance) > 0.009
                    || $invoice->status !== $status;

                if ($needsUpdate) {
                    $changed++;
                    $this->line(sprintf(
                        '  %s (%s): paid %s→%s, amount %s→%s, balance→%s, status→%s',
                        $invoice->invoice_number,
                        $project->name,
                        number_format((float) $invoice->paid_amount, 2),
                        number_format($paid, 2),
                        number_format((float) $invoice->amount, 2),
                        number_format($amount, 2),
                        number_format($balance, 2),
                        $status
                    ));

                    if (! $dry) {
                        $invoice->amount = $amount;
                        $invoice->paid_amount = $paid;
                        $invoice->balance_amount = $balance;
                        $invoice->status = $status;
                        $invoice->save();
                    }
                }

                $collected += $paid;
            }
        }

        if ($changed === 0) {
            $this->info('All invoices already consistent. Nothing to reconcile.');
        } else {
            $this->info(($dry ? '[dry-run] ' : '') . "Reconciled {$changed} invoice(s).");
        }

        return self::SUCCESS;
    }
}
