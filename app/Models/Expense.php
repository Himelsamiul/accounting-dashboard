<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_head_id',
        'bank_id',
        'title',
        'amount',
        'expense_date',
        'payment_method',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    public function head()
    {
        return $this->belongsTo(ExpenseHead::class, 'expense_head_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
