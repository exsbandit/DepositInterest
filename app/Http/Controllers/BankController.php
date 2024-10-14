<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /*
     *
     */
    public function index()
    {
        $banks = Bank::all();
        return response()->json([
            'banks' => $banks,
        ]);
    }
}
