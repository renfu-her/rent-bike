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
            $table->string('ticket_number')->nullable()->after('ticket_id')->comment('罰單編號');
            $table->string('violation_location')->nullable()->after('issued_time')->comment('違規地點');
            $table->text('violation_description')->nullable()->after('violation_location')->comment('違規事實');
            $table->date('due_date')->nullable()->after('violation_description')->comment('應到案日期');
            $table->string('fined_person_name')->nullable()->after('due_date')->comment('罰鍰人姓名');
            $table->string('fined_person_id_number')->nullable()->after('fined_person_name')->comment('罰鍰人身分證號');
            $table->string('issuer_name')->nullable()->after('amount')->comment('填單人');
            $table->string('issuing_authority')->nullable()->after('issuer_name')->comment('舉發單位');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'ticket_number',
                'violation_location',
                'violation_description',
                'due_date',
                'fined_person_name',
                'fined_person_id_number',
                'issuer_name',
                'issuing_authority',
            ]);
        });
    }
};
