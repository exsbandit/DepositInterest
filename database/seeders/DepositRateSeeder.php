<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\DepositRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepositRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $depositRates = [
            ['bank_id' => Bank::where('name', 'Bank A')->first()->id, 'days' => 30, 'rate' => 10.00],
            ['bank_id' => Bank::where('name', 'Bank B')->first()->id, 'days' => 30, 'rate' => 12.00],
            ['bank_id' => Bank::where('name', 'Bank C')->first()->id, 'days' => 30, 'rate' => 11.50],
            ['bank_id' => Bank::where('name', 'Bank A')->first()->id, 'days' => 60, 'rate' => 10.50],
            ['bank_id' => Bank::where('name', 'Bank B')->first()->id, 'days' => 60, 'rate' => 12.50],
            ['bank_id' => Bank::where('name', 'Bank C')->first()->id, 'days' => 60, 'rate' => 11.75],
        ];

        foreach ($depositRates as $rate) {
            DepositRate::create($rate);
        }
    }
}
