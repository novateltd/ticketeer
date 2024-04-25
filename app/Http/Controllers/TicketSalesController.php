<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TicketSalesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $transactions = Transaction::query()
                    ->completed()
                    ->with('tickets')
                    ->latest()
                    ->get();

        $total['adults'] = $total['juniors'] = $total['sales'] = 0;

        $transactions->each(function($transaction) use(&$total) {
            $total['adults'] += $transaction->adult_tickets;
            $total['juniors'] += $transaction->junior_tickets;
            $total['sales'] += $transaction->cost;
        });

        // return $transactions;

        return view('ticketsales', compact('transactions','total'));
    }
}
