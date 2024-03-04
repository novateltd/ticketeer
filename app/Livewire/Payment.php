<?php

namespace App\Livewire;

use Livewire\Component;
use Stripe\PaymentIntent;
use App\Models\Transaction;
use Stripe\Stripe;

class Payment extends Component
{
    public function render()
    {
        $transaction = Transaction::findOrFail(session('transaction_id'));

        Stripe::setApiKey(config('stripe.secret'));

        $pi = PaymentIntent::retrieve($transaction->payment_intent);

        $client_secret = $pi->client_secret;

        return view('livewire.payment', compact('client_secret', 'transaction'));
    }
}
