<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeRequest extends Model
{
    protected $fillable = ['email', 'customer_id', 'note', 'status', 'handled_at'];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
