<x-layouts.app>
    <div class="my-8">

        <div class="max-w-xl px-4 py-8 mx-auto bg-white rounded-lg shadow-lg">

            <h1 class="text-2xl font-bold ">Complete</h1>
            <h2 class="text-xl font-extrabold text-green-900 uppercase">
                {{ $transaction->event->description }} {{ $transaction->event->date->format('jS F Y') }} at {{ $transaction->event->time }}</h2>
            
            <p>Thankyou, your ticket purchase is complete. We look forward to seeing you.</p>

                @foreach($transaction->ticketsArray as $line)
            
                    <div class="flex flex-row items-center my-8">
                        <div class="w-1/3 text-lg font-semibold">{{ $line['type'] }} </div>
                        <div class="w-1/3 font-semibold text-center">{{ $line['count'] }}</div>
                        <div class="w-1/3 px-4 font-semibold text-right">{{ Number::currency($line['total']/100, in:'GBP') }}</div>
                    </div>
            

                @endforeach

                @if($transaction->promo)
                    <div class="font-semibold uppercase">Promo code applied:  {{ $transaction->promo }} </div>
                @endif

                <div class="flex flex-row justify-end my-4 text-right">
                    <div class="px-4 pt-2 font-semibold border-t-2 border-zinc-700">{{ Number::currency($transaction->cost/100, in:'GBP') }}</div>
                </div>

                <div class="text-2xl font-semibold">
                    @if(count($transaction->tickets) == 1)
                        Your ticket number is {{ $transaction->tickets->first()->number }}
                    @else
                        Your ticket numbers are
                            @foreach($transaction->tickets as $ticket)
                                    @if($loop->last && !$loop->first)
                                        &amp;
                                    @endif
                                    <span class="font-bold">{{ $ticket->number }}</span>@if($loop->remaining == 1 || $loop->last) @else,@endif
                            @endforeach
                    @endif
                </div>
                    
        </div>
    </div>
</x-layouts.app>
