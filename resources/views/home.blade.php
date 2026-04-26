<x-layouts.app>
    <div class="max-w-3xl px-4 py-10 mx-auto">
        <div class="p-8 bg-white rounded-lg shadow-lg">
            @if($events->isEmpty())
                <h1 class="text-2xl font-extrabold text-green-900 uppercase">No Active Events</h1>
                <p class="mt-4 text-zinc-700">There are no events currently on sale. Please check back soon.</p>
            @else
                <h1 class="text-2xl font-extrabold text-green-900 uppercase">Choose An Event</h1>
                <p class="mt-4 text-zinc-700">Tickets are currently available for the events below.</p>

                <div class="mt-8 space-y-4">
                    @foreach($events as $event)
                        <a href="{{ route('tickets', $event) }}" class="block p-5 transition border rounded-lg hover:border-green-700 hover:bg-green-50">
                            <div class="text-lg font-bold text-green-900">{{ $event->title }}</div>
                            <div class="mt-1 text-sm text-zinc-700">{{ $event->date->format('jS F Y') }} at {{ $event->time }}</div>
                            <div class="mt-2 text-sm text-zinc-600">{{ $event->description }}</div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
