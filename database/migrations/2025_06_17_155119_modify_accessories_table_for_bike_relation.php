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
        Schema::table('accessories', function (Blueprint $table) {
            if (Schema::hasColumn('accessories', 'bike_id')) {
                $table->dropForeign(['bike_id']);
                $table->dropColumn('bike_id');
            }
            if (Schema::hasColumn('accessories', 'qty')) {
                $table->dropColumn('qty');
            }
            if (Schema::hasColumn('accessories', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('accessories', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accessories', function (Blueprint $table) {
            $table->unsignedBigInteger('bike_id')->nullable();
            $table->integer('qty')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('status')->default(true);
        });
    }
};
