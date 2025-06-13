<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bikes', function (Blueprint $table) {
            if (Schema::hasColumn('bikes', 'helmet_qty')) $table->dropColumn('helmet_qty');
            if (Schema::hasColumn('bikes', 'helmet_enabled')) $table->dropColumn('helmet_enabled');
            if (Schema::hasColumn('bikes', 'helmet_cover_qty')) $table->dropColumn('helmet_cover_qty');
            if (Schema::hasColumn('bikes', 'helmet_cover_enabled')) $table->dropColumn('helmet_cover_enabled');
            if (Schema::hasColumn('bikes', 'phone_holder_qty')) $table->dropColumn('phone_holder_qty');
            if (Schema::hasColumn('bikes', 'phone_holder_enabled')) $table->dropColumn('phone_holder_enabled');
            if (Schema::hasColumn('bikes', 'battery_qty')) $table->dropColumn('battery_qty');
            if (Schema::hasColumn('bikes', 'battery_enabled')) $table->dropColumn('battery_enabled');
            if (Schema::hasColumn('bikes', 'raincoat_qty')) $table->dropColumn('raincoat_qty');
            if (Schema::hasColumn('bikes', 'raincoat_enabled')) $table->dropColumn('raincoat_enabled');
            if (Schema::hasColumn('bikes', 'raincoat_price')) $table->dropColumn('raincoat_price');
        });
    }

    public function down(): void
    {
        Schema::table('bikes', function (Blueprint $table) {
            $table->unsignedTinyInteger('helmet_qty')->default(2)->after('status');
            $table->boolean('helmet_enabled')->default(true)->after('helmet_qty');
            $table->unsignedTinyInteger('helmet_cover_qty')->default(2)->after('helmet_enabled');
            $table->boolean('helmet_cover_enabled')->default(false)->after('helmet_cover_qty');
            $table->unsignedTinyInteger('phone_holder_qty')->default(1)->after('helmet_cover_enabled');
            $table->boolean('phone_holder_enabled')->default(false)->after('phone_holder_qty');
            $table->unsignedTinyInteger('battery_qty')->default(0)->after('phone_holder_enabled');
            $table->boolean('battery_enabled')->default(false)->after('battery_qty');
            $table->unsignedTinyInteger('raincoat_qty')->default(2)->after('battery_enabled');
            $table->boolean('raincoat_enabled')->default(false)->after('raincoat_qty');
            $table->unsignedInteger('raincoat_price')->default(50)->after('raincoat_enabled');
        });
    }
}; 