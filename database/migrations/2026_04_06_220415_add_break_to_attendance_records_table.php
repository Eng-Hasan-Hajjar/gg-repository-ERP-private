<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->timestamp('break_start_at')->nullable()->after('check_out_at');
            $table->timestamp('break_end_at')->nullable()->after('break_start_at');
            $table->unsignedInteger('break_minutes')->default(0)->after('break_end_at');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropColumn(['break_start_at', 'break_end_at', 'break_minutes']);
        });
    }
};