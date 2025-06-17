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
        Schema::create('accessory_bike', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bike_id')->constrained('bikes', 'bike_id')->onDelete('cascade');
            $table->foreignId('accessory_id')->constrained('accessories')->onDelete('cascade');
            $table->integer('qty')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('status')->default(true); // 啟用/停用
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accessory_bike');
    }
};
