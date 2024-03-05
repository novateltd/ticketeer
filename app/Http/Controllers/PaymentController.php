<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Enums\TransactionEnum;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Log;
use App\Enums\TicketEnum;
use App\Models\Ticket;

class PaymentController extends Controller
{
    public function __invoke(Request $request)
    {

        if($request->input('redirect_status') != 'succeeded') {
            return $this->paymentFailure();
        }

        $transaction = Transaction::query()
                        ->where('payment_intent', $request->input('payment_intent'))
                        ->first();

        if($transaction->status == TransactionEnum::PAID->value) {
            return $this->transactionComplete($transaction);
        }

    
        if($transaction->status == TransactionEnum::PENDING->value) {
            return $this->verifyTransaction($transaction);
        }

        return $this->paymentFailure($transaction);

        
    }

    private function paymentFailure(Transaction $transaction)
    {
        return $transaction;
    }

    private function verifyTransaction(Transaction $transaction)
    {
        // call the Stripe API to check if they confirm payment

        Stripe::setApiKey(config('stripe.secret'));

        $pi = PaymentIntent::retrieve($transaction->payment_intent);

        if($pi->amount_received != $transaction->cost) {
            Log::info('Stripe says that the amount is different');
            Log::info($transaction);
            
            return $this->paymentFailure($transaction);
        }

        // complete transaction

        $transaction->status = TransactionEnum::PAID;
        $transaction->completion = now();
        $transaction->save();

        // issue tickets

        foreach(range(1, $transaction->ticket_count) as $issue) {

            $ticket = Ticket::where('status', TicketEnum::AVAILABLE->value)->first();

            if($ticket) {

                $ticket->transaction_id = $transaction->id;
                $ticket->status = TicketEnum::BOUGHT;
                $ticket->save();
            }
            
        }

        return $this->transactionComplete($transaction);

    }


    private function transactionComplete($transaction)
    {
        $transaction->load('event','tickets');
        return view('transaction-complete')->withTransaction($transaction);
    }


}
