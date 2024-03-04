<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Enums\TicketEnum;
use App\Models\Transaction;
use Livewire\Attributes\Session;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class Tickets extends Component
{
    public array $tickets = [];
    public array $ticket_choices = [];
    public int $total = 0;
    public int $event_id;
    public string $name = '';
    public string $email = '';
    
    #[Session]
    public int|null $transaction_id = null;

    public function mount()
    {
        $this->ticket_choices = config('ticketeer.tickets');
        
        foreach($this->ticket_choices as $key => $choice) {
            $this->tickets[$key]['count'] = $choice['min'];
        }

        $this->event_id = Event::first()->id;
    }
    

    public function plus($key)
    {
        $this->tickets[$key]['count']++;
    }

    public function minus($key)
    {
        if($this->tickets[$key]['count'] != config("ticketeer.tickets.{$key}.min" )) {
            $this->tickets[$key]['count']--;
        }
    }

    public function render()
    {
        $this->calculateTotals();
        $this->updateTransaction();

        return view('livewire.tickets')->withEvent(Event::find($this->event_id));
    }

    public function calculateTotals()
    {
        $this->total=0;
        
        foreach($this->ticket_choices as $key => $choice) {
            $this->total += $this->tickets[$key]['total'] = $choice['price'] * $this->tickets[$key]['count'];
        }
    }

    public function updateTransaction()
    {
        $count = 0;
        foreach($this->ticket_choices as $key => $choice) {
            $count += $this->tickets[$key]['count'];
        }

        $data =  [
            'event_id' => $this->event_id,
            'status' => TicketEnum::PENDING,
            'cost' => $this->total,
            'ticket_count' => $count,
            'description' => Event::where('id',$this->event_id)->value('slug'),
        ];

        if($this->transaction_id) {
            Transaction::find($this->transaction_id)->update($data);
        } else {
            $this->transaction_id = (Transaction::create($data))->id;
        }
            
    }

    public function proceed()
    {
        $this->setupPI();
        session()->put('transaction_id',$this->transaction_id);
        return redirect(route('payment'));
    }

    private function setupPI()
    {
        $transaction =  Transaction::find($this->transaction_id);

        Stripe::setApiKey(config('stripe.secret'));
        
        $paymentIntent = PaymentIntent::create([
            'amount' => ($transaction->cost),
            'currency' => 'GBP',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
            'receipt_email' => empty($this->email) ? null : $this->email,
            'description' => $transaction->description, 
        ]);

        $transaction->payment_intent = $paymentIntent->id;
        $transaction->save();
    }
}
