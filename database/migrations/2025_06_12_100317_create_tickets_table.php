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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('ticket_id');
            $table->foreignId('bike_id')->constrained('bikes', 'bike_id');
            $table->dateTime('issued_time');
            $table->decimal('amount', 10, 2);
            $table->boolean('is_resolved')->default(false);
            $table->unsignedBigInteger('related_order_id')->nullable();
            $table->foreign('related_order_id')->references('order_id')->on('orders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
