<x-layouts.app>
    <div class="my-8">

        <div class="max-w-xl px-4 py-8 mx-auto bg-white rounded-lg shadow-lg print:shadow-none">

            <img src="/images/RotarySimplified.png" class="hidden h-24 mx-auto mt-2 mb-2 print:block"> 

            <h1 class="my-4 text-2xl font-bold text-center">Complete</h1>
            <h2 class="my-4 text-xl font-extrabold text-center text-green-900 uppercase">{{ $transaction->event->title }}</h2>
            <p class="my-4 font-semibold text-center">{{ $transaction->event->description }}</p>
            
            <p>Your ticket purchase is complete. We look forward to seeing you.</p>

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
                <div class="my-4 text-center print:hidden">If you are unable to print this page, just make a note of your ticket numbers and bring them to the event </div>
                    
                <div class="w-full my-8 text-center print:hidden">
                    <a href="/" class="block w-full px-2 py-1 text-xs font-semibold tracking-widest text-center text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md md:px-4 md:py-2 hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2'"> Buy more tickets? </a>
                </div>

                <div class="w-full my-8 italic">
                    Payment will appear on your statement as RBROTARY.ORG.UK
                </div>
        </div>
    </div>
</x-layouts.app>
