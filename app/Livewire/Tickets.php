<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Enums\TicketEnum;
use App\Models\Transaction;
use Livewire\Attributes\Session;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Livewire\Attributes\Validate;
use App\Models\Promo;
use Illuminate\Support\Arr;

class Tickets extends Component
{
    public array $tickets = [];
    public array $ticket_choices = [];
    public int $total = 0;
    public int $event_id;
    public string|null $name = '';
    public string|null $code='';
    public string $promoMessage = '';
    
    #[Validate('email:rfc,dns')]
    public string|null $email = '';
    
    #[Session(key: 'transaction_id')]
    public int|null $transaction_id = null;

    public function mount()
    {
        $this->ticket_choices = config('ticketeer.tickets');
        
        foreach($this->ticket_choices as $key => $choice) {
            $this->tickets[$key]['count'] = $choice['min'];
        }

        $this->event_id = Event::first()->id;

        if(session('transaction_id',false)) {
            $transaction = Transaction::findOrFail($this->transaction_id);

            $this->email = $transaction->email;
            $this->name = $transaction->name;

            $this->tickets = json_decode($transaction->tickets_bought,true);

        }
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
        $this->applyPromo();
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

    public function applyPromo()
    {
        if(empty($this->code)) return;

        $this->promoMessage = '';

        $code = Promo::where('code',$this->code)->first();

        if(!$code) {
            $this->promoMessage = 'That is not a valid code';
            return;
        }

        if($code->remaining == 0) {
            $this->promoMessage = 'Sorry that code is no longer available';
            return;
        }

        if($code->count < array_sum(Arr::pluck($this->tickets,'count'))) {
            $this->promoMessage = 'This code is restricted to ' . $code->count . ' tickets only';
            return;
        }

        $this->total = $this->total - ($this->total * ($code->discount / 100));

    }

    public function proceed()
    {
        if($this->total > 0) {
            $this->setupPI();
        }

        foreach($this->tickets as $key => $ticket) {
            $this->tickets[$key]['type'] = $this->ticket_choices[$key]['type'];
        }

        Transaction::where('id', $this->transaction_id)
            ->update([
                'tickets_bought' => $this->tickets,
                'email' => $this->email,
                'name' => $this->name,
                'promo' => $this->code,
            ]);

        if($this->total == 0) {
            return redirect(route('confirmpayment',['redirect_status' => 'succeeded']));
        }
    
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
