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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('image')->nullable()->after('amount');
            $table->enum('status', ['sent', 'unsent'])->default('unsent')->after('is_resolved');
            $table->foreignId('handler_id')->nullable()->after('status')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['handler_id']);
            $table->dropColumn(['image', 'status', 'handler_id']);
        });
    }
};
