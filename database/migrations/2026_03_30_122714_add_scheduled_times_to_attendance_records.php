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
               // إضافة وقت الدوام المجدول مباشرة (بدل الشيفت)
            $table->timestamp('scheduled_start')->nullable()->after('work_date');
            $table->timestamp('scheduled_end')->nullable()->after('scheduled_start');

            // نُبقي على work_shift_id لو في سجلات قديمة (nullable)
            // لا نحذفه حتى لا يكسر البيانات الموجودة
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
             $table->dropColumn(['scheduled_start', 'scheduled_end']);
        });
    }
};
