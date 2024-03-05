<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Enums\TicketEnum;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('time');
            $table->text('description');
            $table->string('slug');
            $table->date('onsale');
            $table->timestamps();
        });

        $event = Event::forceCreate([
            'date' => '2024-04-11',
            'time' => '2pm',
            'description' => '2024 Classical Piano Concert',
            'slug' => '2024-piano-concert',
            'onsale' => '2024-03-02',
        ]);

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Event::class);
            $table->foreignIdFor(Transaction::class)->nullable();
            $table->string('number',10);
            $table->string('status',1)->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });

        foreach(range(1,config('ticketeer.total_tickets')) as $index) {

            $number = Str::padLeft($index,3,'0');

            Ticket::forceCreate([
                'event_id' => $event->id,
                'number' => $number,
                'status' => TicketEnum::AVAILABLE,
            ]);

        }

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Event::class);
            $table->string('status',1);
            $table->string('payment_intent')->nullable();
            $table->dateTime('completion')->nullable();
            $table->integer('cost');
            $table->integer('ticket_count')->default(0);
            $table->string('description');
            $table->string('tickets_bought')->nullable();
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('transactions');
    }
};
