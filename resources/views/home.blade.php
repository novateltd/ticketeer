<x-layouts.app>
    <div class="max-w-3xl px-4 py-10 mx-auto">
        <div class="p-8 bg-white rounded-lg shadow-lg">
            @if($events->isEmpty())
                <h1 class="text-2xl font-extrabold text-green-900 uppercase">No Active Events</h1>
                <p class="mt-4 text-zinc-700">There are no events currently on sale. Please check back soon.</p>
            @else
                <h1 class="text-2xl font-extrabold text-green-900 uppercase">Choose An Event</h1>
                <p class="mt-4 text-zinc-700">Current events are listed below.</p>

                <div class="mt-8 space-y-4">
                    @foreach($events as $event)
                        @if($event->isSoldOut)
                            <div class="block p-5 border rounded-lg bg-zinc-50 border-zinc-200">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="text-lg font-bold text-green-900">{{ $event->title }}</div>
                                        <div class="mt-1 text-sm text-zinc-700">{{ $event->date->format('jS F Y') }} at {{ $event->time }}</div>
                                    </div>
                                    <div class="px-3 py-1 text-xs font-extrabold tracking-widest text-white uppercase bg-red-700 rounded">Sold Out</div>
                                </div>
                                <div class="mt-2 text-sm text-zinc-600">{{ $event->description }}</div>
                            </div>
                        @else
                            <a href="{{ route('tickets', $event) }}" class="block p-5 transition border rounded-lg hover:border-green-700 hover:bg-green-50">
                                <div class="text-lg font-bold text-green-900">{{ $event->title }}</div>
                                <div class="mt-1 text-sm text-zinc-700">{{ $event->date->format('jS F Y') }} at {{ $event->time }}</div>
                                <div class="mt-2 text-sm text-zinc-600">{{ $event->description }}</div>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
