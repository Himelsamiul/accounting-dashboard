<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['client_id', 'project_id', 'bank_id', 'invoice_number', 'invoice_date', 'amount', 'paid_amount', 'balance_amount', 'handover_to', 'description', 'status'];

    protected $appends = ['pending_amount'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function getPendingAmountAttribute()
    {
        return max(0, (float) $this->amount - (float) $this->paid_amount);
    }
}
