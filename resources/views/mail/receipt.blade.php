<x-mail::message>
# Thankyou for purchasing tickets to our event.

# {{ $transaction->event->description }} on {{ $transaction->event->date->format('l jS M y') }} at {{ $transaction->event->time }}

# Receipt

|Type|Number|Cost|
|:---------|:-----------:|--------:|
@foreach($transaction->ticketsArray as $line)
|{{ $line['type'] }}|{{ $line['count'] }}|{{ Number::currency($line['total']/100, in:'GBP') }}|
@endforeach
|||--------|
||Total Paid:|{{ Number::currency($transaction->cost/100, in:'GBP') }}|
|||--------|
    
    
@if($transaction->promo)
Promo code applied:  {{ strtoupper($transaction->promo) }} 
@endif
    
    
@if(count($transaction->tickets) == 1)
__Your ticket number is {{ $transaction->tickets->first()->number }}__
@else
__Your ticket numbers are
    @foreach($transaction->tickets as $ticket)
            @if($loop->last && !$loop->first)
                &amp;
            @endif
            <span class="font-bold">{{ $ticket->number }}@if($loop->last)__ @endif </span>@if($loop->remaining == 1 || $loop->last) @else,@endif
    @endforeach
@endif
    
    
Thanks,<br>
{{ config('app.name') }}

_Payment will appear on your statement as RBROTARY.ORG.UK_
</x-mail::message>
