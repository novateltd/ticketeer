<?php

use App\Models\Event;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedInteger('capacity')->nullable()->after('onsale');
        });

        Schema::create('event_ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Event::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('price');
            $table->unsignedInteger('minimum')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $legacyTicketChoices = config('ticketeer.tickets', []);
        $legacyCapacity = (int) config('ticketeer.total_tickets', 0);

        Event::query()->orderBy('id')->get()->each(function (Event $event) use ($legacyTicketChoices, $legacyCapacity) {
            $existingCapacity = (int) $event->tickets()->count();

            $event->forceFill([
                'capacity' => $existingCapacity > 0 ? $existingCapacity : $legacyCapacity,
            ])->save();

            $hasTicketTypes = DB::table('event_ticket_types')
                ->where('event_id', $event->id)
                ->exists();

            if ($hasTicketTypes) {
                return;
            }

            foreach ($legacyTicketChoices as $index => $ticketChoice) {
                DB::table('event_ticket_types')->insert([
                    'event_id' => $event->id,
                    'name' => $ticketChoice['type'],
                    'price' => $ticketChoice['price'],
                    'minimum' => $ticketChoice['min'] ?? 0,
                    'sort_order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_ticket_types');

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('capacity');
        });
    }
};
