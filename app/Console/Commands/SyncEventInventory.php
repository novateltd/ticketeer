<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class SyncEventInventory extends Command
{
    protected $signature = 'events:sync-inventory {--event= : Limit the sync to a specific event ID}';

    protected $description = 'Create any missing ticket rows for events up to their configured capacity';

    public function handle(): int
    {
        $query = Event::query()->orderBy('id');

        if ($eventId = $this->option('event')) {
            $query->whereKey($eventId);
        }

        $events = $query->get();

        if ($events->isEmpty()) {
            $this->warn('No matching events found.');

            return self::SUCCESS;
        }

        $rows = [];

        foreach ($events as $event) {
            $before = $event->tickets()->count();

            $event->syncTicketInventory();

            $after = $event->tickets()->count();

            $rows[] = [
                'id' => $event->id,
                'title' => $event->title,
                'capacity' => $event->capacity ?? 0,
                'before' => $before,
                'after' => $after,
                'added' => max(0, $after - $before),
            ];
        }

        $this->table(
            ['Event ID', 'Title', 'Capacity', 'Tickets Before', 'Tickets After', 'Added'],
            array_map(fn (array $row) => [
                $row['id'],
                $row['title'],
                $row['capacity'],
                $row['before'],
                $row['after'],
                $row['added'],
            ], $rows)
        );

        $added = collect($rows)->sum('added');

        $this->info("Inventory sync complete. Added {$added} ticket(s).");

        return self::SUCCESS;
    }
}
