<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\DepositController;
use Illuminate\Support\Facades\Route;

Route::get('/deposit-rates', [DepositController::class, 'index']);
Route::post('/calculate-interest', [DepositController::class, 'calculateInterest']);
Route::get('/banks', [BankController::class, 'index']);
