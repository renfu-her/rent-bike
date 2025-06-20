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
            // 先刪除舊的 enum 約束
            $table->dropColumn('status');
        });

        Schema::table('bikes', function (Blueprint $table) {
            // 重新建立包含 pending 的 enum
            $table->enum('status', ['available', 'pending', 'rented', 'maintenance', 'disabled'])->default('available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bikes', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('bikes', function (Blueprint $table) {
            $table->enum('status', ['available', 'rented', 'maintenance', 'disabled'])->default('available');
        });
    }
};
