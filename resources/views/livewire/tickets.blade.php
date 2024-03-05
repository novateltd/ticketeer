<div class="my-8">

    <div class="max-w-xl px-4 py-8 mx-auto bg-white rounded-lg shadow-lg">

        <h1 class="text-xl font-bold text-blue-700">{{ $event->description }} {{ $event->date->format('jS F Y') }} at {{ $event->time }}</h1>
        
        @if($event->isOnsale)
        
            @foreach($this->ticket_choices as $choice)
        
                <div class="flex flex-row items-center justify-between my-8">
                    <div class="text-lg font-semibold">{{ $choice['type'] }} at
                        {{ Number::currency($choice['price']/100, in: 'GBP') }} each</div>
                    <x-secondary-button wire:click="minus({{ $loop->index }})">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                        </svg>
                    </x-secondary-button>
                    <x-primary-button wire:click="plus({{ $loop->index }})">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </x-primary-button>
                    <div class="font-semibold text-center">{{ $this->tickets[$loop->index]['count'] }}</div>
                    <div class="px-4 font-semibold text-right">{{ Number::currency($this->tickets[$loop->index]['total']/100, in:'GBP') }}</div>
                </div>
        

            @endforeach

            
            <div class="flex flex-row justify-end text-right">
                <div class="px-4 pt-2 font-semibold border-t-2 border-zinc-700">{{ Number::currency($this->total/100, in:'GBP') }}</div>
            </div>

            <div class="w-full p-4 mt-6 border rounded-lg shadow-lg">
                Please provide a contact name and email address incase we need to alter the arrangements for the concert. This is optional.
                <div class="flex my-4">
                    <x-input-label class="w-1/4">Your Name:</x-input-label>
                    <x-text-input class="w-1/2" wire:model="name" type="text" />
                </div>
                <div class="flex my-4">
                    <x-input-label class="w-1/4">Your Email:</x-input-label>
                    <x-text-input type="email" class="w-1/2" wire:model.blur="email" type="email" />
                </div>
                @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="my-6 text-center">
                <x-primary-button class="justify-center w-full font-bold" wire:click="proceed">Proceed</x-primary-button>
            </div>
            
        @else
            
            <p>Sorry tickets only available from {{ $event->onsale->format('jS F Y') }}. Please check back.
                
        @endif
    </div>
</div>