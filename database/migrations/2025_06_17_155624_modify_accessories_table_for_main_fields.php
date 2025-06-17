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
            // 移除不需要的欄位
            if (Schema::hasColumn('accessories', 'bike_id')) {
                $table->dropForeign(['bike_id']);
                $table->dropColumn('bike_id');
            }
            if (Schema::hasColumn('accessories', 'status')) {
                $table->dropColumn('status');
            }
            // 新增必要欄位
            if (!Schema::hasColumn('accessories', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('accessories', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('name');
            }
            if (!Schema::hasColumn('accessories', 'qty')) {
                $table->integer('qty')->default(0)->after('price');
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
            $table->boolean('status')->default(true);
            $table->dropColumn('name');
            $table->dropColumn('price');
            $table->dropColumn('qty');
        });
    }
};
