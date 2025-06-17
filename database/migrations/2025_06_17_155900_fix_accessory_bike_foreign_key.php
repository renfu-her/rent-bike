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
        Schema::table('accessory_bike', function (Blueprint $table) {
            // 先移除舊的外鍵
            $table->dropForeign(['accessory_id']);
            // 新增正確的外鍵
            $table->foreign('accessory_id')->references('accessory_id')->on('accessories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accessory_bike', function (Blueprint $table) {
            $table->dropForeign(['accessory_id']);
            $table->foreign('accessory_id')->references('id')->on('accessories')->onDelete('cascade');
        });
    }
}; 