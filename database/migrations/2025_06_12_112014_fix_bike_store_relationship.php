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
        Schema::table('bikes', function (Blueprint $table) {
            // 先移除舊的外鍵
            $table->dropForeign(['store_id']);
            
            // 重命名欄位
            $table->renameColumn('store_id', 'store_store_id');
            
            // 添加新的外鍵
            $table->foreign('store_store_id')
                  ->references('store_id')
                  ->on('stores')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bikes', function (Blueprint $table) {
            // 移除新的外鍵
            $table->dropForeign(['store_store_id']);
            
            // 重命名回原來的欄位名
            $table->renameColumn('store_store_id', 'store_id');
            
            // 恢復舊的外鍵
            $table->foreign('store_id')
                  ->references('store_id')
                  ->on('stores')
                  ->onDelete('cascade');
        });
    }
};
