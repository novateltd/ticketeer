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
use Illuminate\Support\Facades\Mail;
use App\Mail\Receipt;
use Vinkla\Hashids\Facades\Hashids;

class PaymentController extends Controller
{
    public function __invoke(Request $request)
    {
        // if id passed in the URL, and transaction found, and status is PAID then just show receipt

        if($request->has('id')) {
            $transaction = Transaction::find(Hashids::decode($request->input('id'))[0]);
            ray($transaction);
            if(!$transaction) {
                return $this->error('Transaction was not found (' . $request->input('id') . ')' );
            }

            if($transaction->status != TransactionEnum::PAID->value) {
                return $this->error('Transaction is not settled. Contact us (' . $request->input('id') . ')' );
            }

            return $this->showReceipt($transaction);
        }

        // if session has transaction id then load transaction and get paymentIntent ID

        if(session()->has('transaction_id')) {
            $transaction = Transaction::find(session('transaction_id'));
            if(!$transaction) {
                return $this->error('Transaction in session does not exist');
            }
        } else {
            return $this->error('No transaction specified');
        }

        // if the total is 0 OR If stripe says paid and the payment amount is correct then 
            // issue tickets and set final status
            // mail receipt if we have an email address
            // redirect to this same controller action with id in url

        if($transaction->cost == 0 || $this->stripeOK($transaction)) {

            $this->issueTickets($transaction);
            $this->mailReceipt($transaction);

            session()->forget('transaction_id');
            return redirect(route('confirmpayment', ['id' => Hashids::encode($transaction->id)]));

        }

        return ($this->error('We were unable to verify the transaction. Please contact us'));
    }

    private function error($message)
    {
        Log::error($message);
        return view('error_view', compact('message'));
    }

    private function issueTickets(Transaction $transaction): void
    {
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

    }

    private function mailReceipt(Transaction $transaction): void 
    {
        if(empty($transaction->email)) return;

        try {
            Mail::to($transaction->email)->send(new Receipt($transaction));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return;
        }

    }

    private function stripeOK(Transaction $transaction): bool
    {
        // call the Stripe API to check if they confirm payment
        Stripe::setApiKey(config('stripe.secret'));

        $pi = PaymentIntent::retrieve($transaction->payment_intent);

        if($pi->status != 'succeeded') {
            Log::info('Stripe status was ' . $pi->status);
            Log::info($transaction);
            return false;
        }

        if($pi->amount_received != $transaction->cost) {
            Log::info('Stripe says that the amount is different');
            Log::info($transaction);
            
            return false;
        }

        return true;
    }


    private function showReceipt($transaction)
    {
        $transaction->load('event','tickets');
        return view('transaction-complete')->withTransaction($transaction);
    }


}
