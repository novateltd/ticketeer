<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class SetEventSalesStatus extends Command
{
    protected $signature = 'events:sales
        {event : Event ID or slug}
        {--open : Reopen ticket sales for the event}';

    protected $description = 'Close or reopen ticket sales for an event';

    public function handle(): int
    {
        $event = Event::query()
            ->whereKey($this->argument('event'))
            ->orWhere('slug', $this->argument('event'))
            ->first();

        if (! $event) {
            $this->error('No matching event found.');
            return self::FAILURE;
        }

        $event->forceFill([
            'sales_closed' => ! $this->option('open'),
        ])->save();

        $status = $event->sales_closed ? 'closed' : 'open';

        $this->info("Ticket sales are now {$status} for {$event->title}.");

        return self::SUCCESS;
    }
}
