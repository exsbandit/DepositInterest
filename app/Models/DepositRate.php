<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositRate extends Model
{
    use HasFactory;
    protected $fillable = ['bank_id', 'days', 'rate'];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

}
