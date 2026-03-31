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
        Schema::table('employee_schedules', function (Blueprint $table) {
            // إزالة الـ foreign key والعمود القديم
            $table->dropForeign(['work_shift_id']);
            $table->dropColumn('work_shift_id');

            // إضافة الأعمدة الجديدة
            $table->time('start_time')->nullable()->after('weekday'); // وقت البداية
            $table->time('end_time')->nullable()->after('start_time'); // وقت النهاية
            $table->boolean('is_off')->default(false)->after('end_time'); // عطلة؟
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_schedules', function (Blueprint $table) {
                    $table->dropColumn(['start_time', 'end_time', 'is_off']);
            $table->foreignId('work_shift_id')->nullable()->constrained()->nullOnDelete();
      
        });
    }
};
