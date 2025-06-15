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
        Schema::create('accessories', function (Blueprint $table) {
            $table->id('accessory_id');
            $table->foreignId('bike_id')->constrained('bikes', 'bike_id');
            $table->integer('helmet_count')->default(2);
            $table->boolean('has_lock')->default(true);
            $table->boolean('has_toolkit')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accessories');
    }
};
