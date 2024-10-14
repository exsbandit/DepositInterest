<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositFormRequest;
use App\Models\Calculation;
use App\Models\DepositRate;
use App\Models\Log as Logar;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index()
    {
        try {
            $depositRates = DepositRate::all();
            $currencies = $depositRates->pluck('currency')->unique()->values();
            $durations = $depositRates->pluck('duration')->unique()->values();

            Log::info('Deposit rates fetched successfully.', [
                'currencies' => $currencies,
                'durations' => $durations,
            ]);

            return response()->json([
                'currencies' => $currencies,
                'durations' => $durations,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching deposit rates: ' . $e->getMessage());
            Sentry\captureException($e);
            return response()->json(['message' => 'Hata oluştu.'], 500);
        }
    }

    public function calculateInterest(DepositFormRequest $request)
    {
        try {

            $validated = $request->validated();

            $amount = $validated['amount'];
            $currency = $validated['currency'];
            $duration = $validated['duration'];

            $closestDuration = $this->findClosestDuration($duration);
            $depositRates = DepositRate::where('currency', $currency)
                ->where('duration', '=', $closestDuration)
                ->get();

            if (!$depositRates) {
                return response()->json(['message' => 'Faiz oranı bulunamadı.'], 404);
            }

            $results = $this->calculateDepositRates($depositRates, $amount, $duration);

            Log::info('Interest calculation results generated successfully.', [
                'results' => $results,
            ]);

            return response()->json($results);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Error during interest calculation.');
        }
    }

    private function findClosestDuration($duration)
    {
        $durations = DepositRate::pluck('duration')->unique()->sort()->toArray();
        $closestDuration = collect($durations)->first(fn($d) => $d >= $duration) ?: max($durations);
        return $closestDuration;
    }

    private function calculateDepositRates($depositRates, $amount, $duration)
    {
        return $depositRates->map(function ($depositRate) use ($amount, $duration) {
            $grossInterest = ($amount * $depositRate->rate * $duration) / 36500;
            $tax = $grossInterest * $depositRate->bank->tax;
            $netInterest = $grossInterest - $tax;
            $finalBalance = $amount + $netInterest;

            return [
                'bank_name' => $depositRate->bank->name,
                'on_duration' => number_format($depositRate->duration) . ' Gün',
                'gross_interest' => number_format($grossInterest, 2) . ' TL',
                'tax' => number_format($tax, 2) . ' TL',
                'rate' => number_format($depositRate->rate, 2) . ' %',
                'net_interest' => number_format($netInterest, 2) . ' TL',
                'final_balance' => number_format($finalBalance, 2) . ' TL',
            ];
        })->toArray();
    }

    private function handleException(\Exception $e, $message)
    {
        Log::error($message . ' ' . $e->getMessage());
        Sentry\captureException($e);
        return response()->json(['message' => 'Hata oluştu.'], 500);
    }


    private function logCalculation($depositRate, $grossInterest, $tax, $rate, $amount, $netInterest, $finalBalance, $currency, $duration)
    {
        Calculation::create([
            'bank_id' => $depositRate->bank_id,
            'bank_name' => $depositRate->bank->name,
            'on_duration' => $depositRate->duration,
            'gross_interest' => $grossInterest,
            'tax' => $tax,
            'rate' => $rate,
            'amount' => $amount,
            'net_interest' => $netInterest,
            'final_balance' => $finalBalance,
            'currency' => $currency,
            'duration' => $duration,
        ]);
    }
}
