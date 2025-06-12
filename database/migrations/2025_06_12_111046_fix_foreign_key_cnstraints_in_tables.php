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
            $table->dropForeign(['store_id']);
            $table->foreign('store_id')->references('store_id')->on('stores')->onDelete('cascade');
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->dropForeign(['bike_id']);
            $table->foreign('bike_id')->references('bike_id')->on('bikes')->onDelete('cascade');
        });

        Schema::table('bike_prices', function (Blueprint $table) {
            $table->dropForeign(['bike_id']);
            $table->foreign('bike_id')->references('bike_id')->on('bikes')->onDelete('cascade');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['bike_id']);
            $table->foreign('bike_id')->references('bike_id')->on('bikes')->onDelete('cascade');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['bike_id']);
            $table->dropForeign(['related_order_id']);
            $table->foreign('bike_id')->references('bike_id')->on('bikes')->onDelete('cascade');
            $table->foreign('related_order_id')->references('order_id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bikes', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->dropForeign(['bike_id']);
            $table->foreign('bike_id')->references('id')->on('bikes')->onDelete('cascade');
        });

        Schema::table('bike_prices', function (Blueprint $table) {
            $table->dropForeign(['bike_id']);
            $table->foreign('bike_id')->references('id')->on('bikes')->onDelete('cascade');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['bike_id']);
            $table->foreign('bike_id')->references('id')->on('bikes')->onDelete('cascade');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['bike_id']);
            $table->dropForeign(['related_order_id']);
            $table->foreign('bike_id')->references('id')->on('bikes')->onDelete('cascade');
            $table->foreign('related_order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }
};
