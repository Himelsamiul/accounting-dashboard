<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = ['name', 'account_number', 'branch', 'notes'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
