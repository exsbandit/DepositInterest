<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\BankInterestRate;
use App\Models\DepositRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run()
    {
        // Bankaları oluştur
        $banks = [
            ['name' => 'Bank A', 'tax' => '0.15'],
            ['name' => 'Bank B', 'tax' => '0.16'],
            ['name' => 'Bank C', 'tax' => '0.12'],
        ];

        foreach ($banks as $bankData) {
            $bank = Bank::create($bankData);

            // Her bankanın faiz oranlarını ayrı tanımlayalım
            $this->addInterestRates($bank);
        }
    }

    private function addInterestRates(Bank $bank)
    {
        // Bankaya göre faiz oranlarını belirleyelim
        $interestRates = [];

        if ($bank->name == 'Bank A') {
            // Bank A'nın faiz oranları
            $interestRates = [
                ['currency' => 'TRY', 'duration' => 30, 'interest_rate' => 10.5],
                ['currency' => 'TRY', 'duration' => 90, 'interest_rate' => 11.0],
                ['currency' => 'TRY', 'duration' => 180, 'interest_rate' => 12.0],

                ['currency' => 'USD', 'duration' => 30, 'interest_rate' => 1.1],
                ['currency' => 'USD', 'duration' => 90, 'interest_rate' => 1.2],
                ['currency' => 'USD', 'duration' => 180, 'interest_rate' => 1.3],

                ['currency' => 'EUR', 'duration' => 30, 'interest_rate' => 0.9],
                ['currency' => 'EUR', 'duration' => 90, 'interest_rate' => 1.0],
                ['currency' => 'EUR', 'duration' => 180, 'interest_rate' => 1.1],
            ];
        } elseif ($bank->name == 'Bank B') {
            // Bank B'nin faiz oranları
            $interestRates = [
                ['currency' => 'TRY', 'duration' => 30, 'interest_rate' => 9.5],
                ['currency' => 'TRY', 'duration' => 90, 'interest_rate' => 10.0],
                ['currency' => 'TRY', 'duration' => 180, 'interest_rate' => 10.5],

                ['currency' => 'USD', 'duration' => 30, 'interest_rate' => 1.05],
                ['currency' => 'USD', 'duration' => 90, 'interest_rate' => 1.15],
                ['currency' => 'USD', 'duration' => 180, 'interest_rate' => 1.25],

                ['currency' => 'EUR', 'duration' => 30, 'interest_rate' => 0.85],
                ['currency' => 'EUR', 'duration' => 90, 'interest_rate' => 0.95],
                ['currency' => 'EUR', 'duration' => 180, 'interest_rate' => 1.05],
            ];
        } elseif ($bank->name == 'Bank C') {
            // Bank C'nin faiz oranları
            $interestRates = [
                ['currency' => 'TRY', 'duration' => 30, 'interest_rate' => 11.0],
                ['currency' => 'TRY', 'duration' => 90, 'interest_rate' => 11.5],
                ['currency' => 'TRY', 'duration' => 180, 'interest_rate' => 12.5],

                ['currency' => 'USD', 'duration' => 30, 'interest_rate' => 1.2],
                ['currency' => 'USD', 'duration' => 90, 'interest_rate' => 1.3],
                ['currency' => 'USD', 'duration' => 180, 'interest_rate' => 1.4],

                ['currency' => 'EUR', 'duration' => 30, 'interest_rate' => 0.95],
                ['currency' => 'EUR', 'duration' => 90, 'interest_rate' => 1.05],
                ['currency' => 'EUR', 'duration' => 180, 'interest_rate' => 1.15],
            ];
        }

        // Faiz oranlarını veritabanına kaydedelim
        foreach ($interestRates as $rate) {
            DepositRate::create([
                'bank_id' => $bank->id,
                'currency' => $rate['currency'],
                'duration' => $rate['duration'],
                'rate' => $rate['interest_rate'],
            ]);
        }
    }
}
