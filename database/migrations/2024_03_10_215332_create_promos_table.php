<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Promo;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('discount');
            $table->integer('count');
            $table->integer('remaining');
            $table->timestamps();
        });

        Promo::forceCreate([
            'code' => 'TALENT',
            'discount' => 100,
            'count' => 3,
            'remaining' => 7,
        ]);

        Promo::forceCreate([
            'code' => 'STAFF100',
            'discount' => 100,
            'count' => 1,
            'remaining' => 6,
        ]);

        Promo::forceCreate([
            'code' => 'MEMBER',
            'discount' => 50,
            'count' => 2,
            'remaining' => 14,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
