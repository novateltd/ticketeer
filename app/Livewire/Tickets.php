<?php

namespace App\Livewire;

use Livewire\Component;

class Tickets extends Component
{
    public array $tickets = [];
    public array $ticket_choices = [];
    public int $total = 0;

    public function mount()
    {
        $this->ticket_choices = config('ticketeer.tickets');
        
        foreach($this->ticket_choices as $key => $choice) {
            $this->tickets[$key]['count'] = $choice['min'];
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

        return view('livewire.tickets');
    }

    public function calculateTotals()
    {
        $this->total=0;
        
        foreach($this->ticket_choices as $key => $choice) {
            $this->total += $this->tickets[$key]['total'] = $choice['price'] * $this->tickets[$key]['count'];
        }
    }

}
