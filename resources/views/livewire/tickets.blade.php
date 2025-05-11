<div class="my-8">

    <div class="max-w-xl px-4 py-8 mx-auto bg-white rounded-lg shadow-lg">

        <h1 class="text-xl font-extrabold text-center text-green-900 uppercase">{{ $event->title }}<br />
            {{ $event->date->format('jS F Y') }} at {{ $event->time }}</h1>
        
        @if($event->isOnsale)

            <div class="grid grid-cols-3 mt-8 mb-4 gap-y-2">
                @foreach($this->ticket_choices as $choice)
                    <div class="text-base font-semibold text-left md:text-lg">{{ $choice['type'] }}</div>
                    <div class="grid items-center grid-cols-3">
                        <div class="text-right">
                            <x-secondary-button wire:click="minus({{ $loop->index }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                </svg>
                            </x-secondary-button>
                        </div>
                        <div class="font-semibold text-center">{{ $this->tickets[$loop->index]['count'] }}</div>
                        <div class="text-left">
                            <x-primary-button wire:click="plus({{ $loop->index }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </x-primary-button>
                        </div>
                    </div>
                    <div class="px-4 font-semibold text-right ">{{ Number::currency($this->tickets[$loop->index]['total']/100, in:'GBP') }}</div>
                @endforeach
            </div>

            
            <div class="flex flex-row justify-end text-right">
                <div class="px-4 pt-2 font-semibold border-t-2 border-zinc-700"><span class="px-8 font-normal">Total: </span>{{ Number::currency($this->total/100, in:'GBP') }}</div>
            </div>

            <div class="w-full p-4 mt-6 border rounded-lg shadow-lg">
                If you have a promotion code, enter it here:
                <div class="flex items-center my-4">
                    <x-input-label class="w-1/4">Code:</x-input-label>
                    <x-text-input class="w-1/2 uppercase" wire:model.live.debounce.1500ms="code" type="text" />
                </div>
                <div class="font-semibold text-red-600">{{ $this->promoMessage }}</div>
            </div>

            <div class="w-full p-4 mt-6 border rounded-lg shadow-lg">
                Please provide a contact name and email address so that we can send you a confirmation of purchase email.
                We will also use these details incase we need to alter the arrangements for the concert.
                <div class="flex items-center my-4">
                    <x-input-label class="w-1/4">Your Name:</x-input-label>
                    <x-text-input class="w-1/2" wire:model="name" type="text" />
                </div>
                <span class="italic">Optional</span><br />
                <div class="flex items-center my-4">
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