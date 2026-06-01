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
    Schema::table('attendance_records', function (Blueprint $table) {
        $table->unsignedBigInteger('leave_request_id')->nullable()->after('work_shift_id');
        $table->string('checkout_latitude')->nullable()->after('worked_minutes');
        $table->string('checkout_longitude')->nullable()->after('checkout_latitude');
        $table->string('checkout_address')->nullable()->after('checkout_longitude');
    });
}

public function down(): void
{
    Schema::table('attendance_records', function (Blueprint $table) {
        $table->dropColumn([
            'leave_request_id',
            'checkout_latitude',
            'checkout_longitude',
            'checkout_address',
        ]);
    });
}
};
