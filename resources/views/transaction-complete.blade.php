<x-app-layout>
    <div class="my-8">

        <div class="max-w-xl px-4 py-8 mx-auto bg-white rounded-lg shadow-lg">

            <h1 class="text-2xl font-bold">Complete</h1>
            <h2 class="text-xl font-bold text-blue-700">
                {{ $transaction->event->description }} {{ $transaction->event->date->format('jS F Y') }} at {{ $transaction->event->time }}</h2>
            
            <p>Thankyou, your ticket purchase is complete. We look forward to seeing you.</p>

                @foreach($transaction->ticketsArray as $line)
            
                    <div class="flex flex-row items-center justify-between my-8">
                        <div class="text-lg font-semibold">{{ $line['type'] }} </div>
                        <div class="font-semibold text-center">{{ $line['count'] }}</div>
                        <div class="px-4 font-semibold text-right">{{ Number::currency($line['total']/100, in:'GBP') }}</div>
                    </div>
            

                @endforeach

                Your ticket numbers are;
                            @foreach($transaction->tickets as $ticket)
                            {{ $ticket->number }},
                            @endforeach
                    
        </div>
    </div>
</x-app-layout>