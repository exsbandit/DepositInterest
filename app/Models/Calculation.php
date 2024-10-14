<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    use HasFactory;
    protected $fillable = [
        'bank_id',
        'bank_name',
        'on_duration',
        'gross_interest',
        'tax',
        'rate',
        'amount',
        'net_interest',
        'final_balance',
        'currency',
        'duration',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
