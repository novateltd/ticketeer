<?php

use App\Models\Event;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->foreignIdFor(Event::class)->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        $defaultEventId = Event::query()->active()->value('id')
            ?? Event::query()->orderBy('id')->value('id');

        if ($defaultEventId) {
            \App\Models\Promo::query()
                ->whereNull('event_id')
                ->update(['event_id' => $defaultEventId]);
        }

        Schema::table('promos', function (Blueprint $table) {
            $table->unique(['event_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'code']);
            $table->dropConstrainedForeignId('event_id');
        });
    }
};
