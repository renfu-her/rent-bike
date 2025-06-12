<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bike_prices', function (Blueprint $table) {
            $table->id('price_id');
            $table->foreignId('bike_id')->constrained('bikes');
            $table->integer('rental_days');
            $table->decimal('price_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bike_prices');
    }
};
