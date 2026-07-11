<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['code', 'client_id', 'name', 'type', 'project_value', 'description'];

    /** Generate a unique, non-guessable tracking code. */
    public static function generateCode(): string
    {
        do {
            $code = 'PRJ-' . strtoupper(\Illuminate\Support\Str::random(8));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
