<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bike_prices', function (Blueprint $table) {
            $table->decimal('original_price', 10, 2)->nullable()->after('price_type');
        });

        // 將現有的 price_amount 複製到 original_price
        DB::statement('UPDATE bike_prices SET original_price = price_amount');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bike_prices', function (Blueprint $table) {
            $table->dropColumn('original_price');
        });
    }
};
