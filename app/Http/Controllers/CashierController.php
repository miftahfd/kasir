<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index() {
        return view('cashier');
    }

    public function calculate(Request $request) {
        $nominal = (int)str_replace('.', '', $request->nominal);
        $denominations = [
            100, 200, 500, 1000, 2000, 5000, 10000, 20000, 50000, 100000
        ];

        $results = [];
        foreach ($denominations as $denom) {
            $payment = ceil($nominal / $denom) * $denom;
            if ($payment > $nominal) {
                if (!in_array($payment, $results)) {
                    $results[] = $payment;
                }
            }
        }
        $results[] = 'Uang Pas';

        return response()->json([
            'status' => true,
            'data' => $results
        ]);
    }
}
