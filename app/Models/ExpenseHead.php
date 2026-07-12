<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseHead extends Model
{
    protected $fillable = ['name', 'description'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
